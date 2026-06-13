#!/bin/bash
set -e

WP_PATH="/var/www/html"
SCRIPT_DIR="/scripts"

echo "==> Waiting for WordPress to be ready..."
for i in $(seq 1 60); do
  if wp core is-installed --path="$WP_PATH" --allow-root 2>/dev/null; then
    echo "WordPress is installed."
    break
  fi
  if [ "$i" -eq 1 ]; then
    echo "Installing WordPress..."
    wp core install \
      --path="$WP_PATH" \
      --url="http://localhost:8080" \
      --title="TravelWorld" \
      --admin_user="admin" \
      --admin_password="admin123" \
      --admin_email="admin@travelworld.com" \
      --skip-email \
      --allow-root 2>/dev/null || true
  fi
  sleep 3
done

if ! wp core is-installed --path="$WP_PATH" --allow-root; then
  echo "ERROR: WordPress installation failed. Make sure containers are running."
  exit 1
fi

echo "==> Activating TravelWorld theme..."
wp theme activate travelworld --path="$WP_PATH" --allow-root

echo "==> Creating pages..."
HOME_ID=$(wp post create --post_type=page --post_title="Home" --post_status=publish --post_name="home" --porcelain --path="$WP_PATH" --allow-root)
ABOUT_ID=$(wp post create --post_type=page --post_title="About Us" --post_status=publish --post_name="about-us" --page_template="page-about.php" --porcelain --path="$WP_PATH" --allow-root)
PACKAGES_ID=$(wp post create --post_type=page --post_title="Tour Packages" --post_status=publish --post_name="tour-packages" --page_template="page-tour-packages.php" --porcelain --path="$WP_PATH" --allow-root)
CONTACT_ID=$(wp post create --post_type=page --post_title="Contact" --post_status=publish --post_name="contact" --page_template="page-contact.php" --porcelain --path="$WP_PATH" --allow-root)

wp post update "$ABOUT_ID" --page_template="page-about.php" --path="$WP_PATH" --allow-root
wp post update "$PACKAGES_ID" --page_template="page-tour-packages.php" --path="$WP_PATH" --allow-root
wp post update "$CONTACT_ID" --page_template="page-contact.php" --path="$WP_PATH" --allow-root

echo "==> Setting front page..."
wp option update show_on_front page --path="$WP_PATH" --allow-root
wp option update page_on_front "$HOME_ID" --path="$WP_PATH" --allow-root
wp option update blogname "TravelWorld" --path="$WP_PATH" --allow-root
wp option update blogdescription "Making international travel accessible and stress-free" --path="$WP_PATH" --allow-root
wp rewrite structure '/%postname%/' --path="$WP_PATH" --allow-root

echo "==> Creating navigation menu..."
MENU_ID=$(wp menu create "Primary Menu" --porcelain --path="$WP_PATH" --allow-root 2>/dev/null || wp menu list --format=ids --path="$WP_PATH" --allow-root | head -1)

wp menu item add-post "$MENU_ID" "$HOME_ID" --path="$WP_PATH" --allow-root 2>/dev/null || true
wp menu item add-post "$MENU_ID" "$ABOUT_ID" --path="$WP_PATH" --allow-root 2>/dev/null || true
wp menu item add-post "$MENU_ID" "$PACKAGES_ID" --path="$WP_PATH" --allow-root 2>/dev/null || true
wp menu item add-post "$MENU_ID" "$CONTACT_ID" --path="$WP_PATH" --allow-root 2>/dev/null || true
wp menu location assign "$MENU_ID" primary --path="$WP_PATH" --allow-root 2>/dev/null || true

echo "==> Importing images and creating destinations..."

import_image() {
  local url="$1"
  local title="$2"
  wp media import "$url" --title="$title" --porcelain --path="$WP_PATH" --allow-root 2>/dev/null || echo ""
}

# Destinations
declare -A DEST_IMAGES
DEST_IMAGES["mauritius"]="https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=800&q=80"
DEST_IMAGES["georgia"]="https://images.unsplash.com/photo-1565008576549-57569a49371d?w=800&q=80"
DEST_IMAGES["switzerland"]="https://images.unsplash.com/photo-1531366936337-7c912a4589a7?w=800&q=80"
DEST_IMAGES["australia"]="https://images.unsplash.com/photo-1523482580670-f738a847f5c0?w=800&q=80"
DEST_IMAGES["dubai"]="https://images.unsplash.com/photo-1512453979798-5ea266f8880c?w=800&q=80"
DEST_IMAGES["thailand"]="https://images.unsplash.com/photo-1552465011-b4e21bf6e79a?w=800&q=80"

declare -A DEST_TAGLINES
DEST_TAGLINES["mauritius"]="Tropical paradise with pristine beaches"
DEST_TAGLINES["georgia"]="Adventure and culture in the Caucasus"
DEST_TAGLINES["switzerland"]="Alpine beauty and scenic landscapes"
DEST_TAGLINES["australia"]="Wildlife, beaches, and urban adventures"
DEST_TAGLINES["dubai"]="Luxury and modern Arabian experiences"
DEST_TAGLINES["thailand"]="Temples, beaches, and vibrant culture"

declare -A DEST_PRICES
DEST_PRICES["mauritius"]=1899
DEST_PRICES["georgia"]=1299
DEST_PRICES["switzerland"]=2499
DEST_PRICES["australia"]=2199
DEST_PRICES["dubai"]=1599
DEST_PRICES["thailand"]=999

declare -A DEST_IDS

for slug in mauritius georgia switzerland australia dubai thailand; do
  TITLE=$(echo "$slug" | awk '{print toupper(substr($0,1,1)) substr($0,2)}')
  IMG_ID=$(import_image "${DEST_IMAGES[$slug]}" "$TITLE")
  
  DEST_ID=$(wp post create \
    --post_type=destination \
    --post_title="$TITLE" \
    --post_status=publish \
    --post_name="$slug" \
    --post_excerpt="${DEST_TAGLINES[$slug]}" \
    --porcelain \
    --path="$WP_PATH" \
    --allow-root)

  wp post meta update "$DEST_ID" _tagline "${DEST_TAGLINES[$slug]}" --path="$WP_PATH" --allow-root
  wp post meta update "$DEST_ID" _starting_price "${DEST_PRICES[$slug]}" --path="$WP_PATH" --allow-root
  
  if [ -n "$IMG_ID" ]; then
    wp post meta update "$DEST_ID" _thumbnail_id "$IMG_ID" --path="$WP_PATH" --allow-root
  fi
  
  DEST_IDS[$slug]=$DEST_ID
  echo "  Created destination: $TITLE (ID: $DEST_ID)"
done

echo "==> Creating tour packages..."

create_package() {
  local title="$1"
  local slug="$2"
  local dest_slug="$3"
  local price="$4"
  local original="$5"
  local duration="$6"
  local meal="$7"
  local hotel_rating="$8"
  local hotel_name="$9"
  local rating="${10}"
  local reviews="${11}"
  local highlights="${12}"
  local trending="${13}"
  local image_url="${14}"

  IMG_ID=$(import_image "$image_url" "$title")
  
  PKG_ID=$(wp post create \
    --post_type=tour_package \
    --post_title="$title" \
    --post_status=publish \
    --post_name="$slug" \
    --porcelain \
    --path="$WP_PATH" \
    --allow-root)

  wp post meta update "$PKG_ID" _destination_id "${DEST_IDS[$dest_slug]}" --path="$WP_PATH" --allow-root
  wp post meta update "$PKG_ID" _price "$price" --path="$WP_PATH" --allow-root
  wp post meta update "$PKG_ID" _original_price "$original" --path="$WP_PATH" --allow-root
  wp post meta update "$PKG_ID" _duration "$duration" --path="$WP_PATH" --allow-root
  wp post meta update "$PKG_ID" _meal_plan "$meal" --path="$WP_PATH" --allow-root
  wp post meta update "$PKG_ID" _hotel_rating "$hotel_rating" --path="$WP_PATH" --allow-root
  wp post meta update "$PKG_ID" _hotel_name "$hotel_name" --path="$WP_PATH" --allow-root
  wp post meta update "$PKG_ID" _rating "$rating" --path="$WP_PATH" --allow-root
  wp post meta update "$PKG_ID" _review_count "$reviews" --path="$WP_PATH" --allow-root
  wp post meta update "$PKG_ID" _highlights "$highlights" --path="$WP_PATH" --allow-root
  wp post meta update "$PKG_ID" _trending "$trending" --path="$WP_PATH" --allow-root

  if [ -n "$IMG_ID" ]; then
    wp post meta update "$PKG_ID" _thumbnail_id "$IMG_ID" --path="$WP_PATH" --allow-root
  fi

  echo "  Created package: $title (ID: $PKG_ID)" >&2
  echo "$PKG_ID"
}

# Mauritius packages
MAURITIUS_PKG1=$(create_package \
  "Mauritius Paradise Escape" "mauritius-paradise-escape" "mauritius" \
  1899 2499 "7 Days / 6 Nights" "Breakfast & Dinner" "5-Star" "Paradise Beach Resort & Spa" \
  4.8 124 "Beach resort stay, Water sports, Island tours" 1 \
  "https://images.unsplash.com/photo-1573843981267-be1999ff37cd?w=800&q=80")

MAURITIUS_PKG2=$(create_package \
  "Mauritius Luxury Retreat" "mauritius-luxury-retreat" "mauritius" \
  2499 3199 "5 Days / 4 Nights" "All Inclusive" "5-Star" "Luxury Lagoon Resort" \
  4.9 89 "Private beach, Spa treatments, Catamaran cruise" 0 \
  "https://images.unsplash.com/photo-1582719508461-905c673771fd?w=800&q=80")

# Georgia packages
GEORGIA_PKG1=$(create_package \
  "Georgia Mountain Adventure" "georgia-mountain-adventure" "georgia" \
  1299 1699 "8 Days / 7 Nights" "Breakfast" "4-Star" "Mountain View Hotel Tbilisi" \
  4.9 156 "Mountain hiking, Wine tasting, Cultural tours" 1 \
  "https://images.unsplash.com/photo-1565008576549-57569a49371d?w=800&q=80")

# Switzerland packages
SWISS_PKG1=$(create_package \
  "Swiss Alps Experience" "swiss-alps-experience" "switzerland" \
  2499 3199 "7 Days / 6 Nights" "Breakfast & Dinner" "5-Star" "Alpine Grand Hotel" \
  4.8 98 "Scenic train rides, Mountain excursions, Lake cruises" 1 \
  "https://images.unsplash.com/photo-1531366936337-7c912a4589a7?w=800&q=80")

# Australia packages
AUSTRALIA_PKG1=$(create_package \
  "Australia Explorer" "australia-explorer" "australia" \
  2199 2799 "10 Days / 9 Nights" "Breakfast" "4-Star" "Sydney Harbour Hotel" \
  4.7 112 "Great Barrier Reef, Sydney Opera House, Wildlife parks" 0 \
  "https://images.unsplash.com/photo-1523482580670-f738a847f5c0?w=800&q=80")

# Dubai packages
DUBAI_PKG1=$(create_package \
  "Dubai Luxury Getaway" "dubai-luxury-getaway" "dubai" \
  1599 2099 "6 Days / 5 Nights" "Breakfast & Dinner" "5-Star" "Burj Al Arab Suites" \
  4.8 203 "Desert safari, Burj Khalifa, Luxury shopping" 0 \
  "https://images.unsplash.com/photo-1512453979798-5ea266f8880c?w=800&q=80")

# Thailand packages
THAILAND_PKG1=$(create_package \
  "Thailand Beach & Temple Tour" "thailand-beach-temple" "thailand" \
  999 1399 "7 Days / 6 Nights" "Breakfast" "4-Star" "Phuket Beach Resort" \
  4.6 178 "Temple visits, Island hopping, Thai cooking class" 0 \
  "https://images.unsplash.com/photo-1552465011-b4e21bf6e79a?w=800&q=80")

echo "==> Adding itinerary to Mauritius Paradise Escape..."

wp eval-file /scripts/set-itinerary.php --path="$WP_PATH" --allow-root

echo "==> Seeding theme settings and blog posts..."
wp eval-file /scripts/seed-blog-settings.php --path="$WP_PATH" --allow-root

echo "==> Flushing rewrite rules..."
wp rewrite flush --path="$WP_PATH" --allow-root

echo ""
echo "========================================="
echo "  TravelWorld setup complete!"
echo "========================================="
echo ""
echo "  Website:  http://localhost:8080"
echo "  Admin:    http://localhost:8080/wp-admin"
echo "  Username: admin"
echo "  Password: admin123"
echo ""
echo "  Pages created:"
echo "    - Home (front page)"
echo "    - About Us"
echo "    - Tour Packages"
echo "    - Contact"
echo ""
echo "  6 Destinations and 7 Tour Packages seeded."
echo "========================================="
