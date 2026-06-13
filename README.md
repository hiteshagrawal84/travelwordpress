# TravelWorld - WordPress Travel Agency Website

A complete WordPress travel agency website with a custom theme matching the TravelWorld design mockups.

## Features

- **Custom TravelWorld Theme** — Modern, responsive design with blue/navy color palette
- **Custom Post Types** — Destinations (countries) and Tour Packages
- **Full Page Templates** — Home, About Us, Tour Packages, Contact, Package Detail, Destination Listing
- **Inquiry System** — Modal inquiry form with AJAX submission and admin storage
- **Hero Slider** — Auto-rotating destination carousel on homepage
- **Day-by-Day Itinerary** — Accordion itinerary on package detail pages
- **Inclusions/Exclusions** — Structured package information
- **WhatsApp & Call Integration** — Direct contact buttons on package pages

## Quick Start

### Prerequisites

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) installed and running

### Launch the Site

```bash
chmod +x start.sh scripts/setup.sh
./start.sh
```

This will:
1. Start WordPress + MySQL via Docker
2. Install WordPress
3. Activate the TravelWorld theme
4. Create all pages (Home, About Us, Tour Packages, Contact)
5. Seed 6 destinations and 7 tour packages with full content

### Access the Site

| | URL |
|---|---|
| **Website** | http://localhost:8080 |
| **Admin Panel** | http://localhost:8080/wp-admin |
| **Username** | `admin` |
| **Password** | `admin123` |

## Manual Commands

```bash
# Start containers
docker compose up -d

# Stop containers
docker compose down

# Re-run setup (seed content)
docker compose exec wpcli bash /scripts/setup.sh

# View logs
docker compose logs -f wordpress
```

## Site Structure

```
Homepage
├── Hero slider with destination highlights
├── Trending packages section
├── Why Choose Us features
├── Testimonials
└── Call to action

Tour Packages
└── Destination grid (Mauritius, Georgia, Switzerland, Australia, Dubai, Thailand)
    └── Country page with package listings
        └── Package detail with itinerary, inclusions, booking sidebar

About Us
├── Mission section
├── Statistics
├── Values
└── Team members

Contact
├── Contact info cards
├── Inquiry form
├── Google Maps embed
└── Quick contact / Emergency support
```

## Theme Development

The theme lives at `wp-content/themes/travelworld/`:

```
travelworld/
├── style.css              # Theme metadata
├── functions.php          # Theme setup & enqueues
├── front-page.php         # Homepage template
├── page-about.php         # About Us template
├── page-contact.php       # Contact template
├── page-tour-packages.php # Destinations listing
├── single-destination.php # Country packages page
├── single-tour_package.php # Package detail page
├── header.php / footer.php
├── inc/
│   ├── custom-post-types.php
│   ├── meta-boxes.php
│   ├── template-tags.php
│   └── inquiry-handler.php
└── assets/
    ├── css/main.css
    └── js/main.js
```

Changes to theme files are reflected immediately (volume-mounted into the container).

## Managing Content

### Destinations
Go to **Destinations** in the WordPress admin to add/edit countries. Set:
- Tagline (e.g., "Tropical paradise with pristine beaches")
- Starting price
- Featured image

### Tour Packages
Go to **Tour Packages** in the admin to manage packages. Set:
- Destination, pricing, duration, hotel info
- Highlights (comma-separated tags)
- Day-by-day itinerary
- Inclusions and exclusions
- "Show in Trending" checkbox for homepage display

### Inquiries
Submitted inquiries are stored under **Inquiries** in the admin and emailed to the site admin.

### Quick Start (Fresh Install)

1. Activate the TravelWorld theme
2. Go to **Appearance → Import Sample Data**
3. Click **Import Sample Data** — this sets up the full demo site in one click

Or run via Docker CLI:
```bash
./start.sh
```

### What Sample Import Includes

- Theme settings (top bar, contact page, blog section)
- Pages: Home, About Us, Tour Packages, Contact, Blog
- Navigation menu
- 6 destinations + 7 tour packages with images
- Full Mauritius itinerary with inclusions/exclusions
- 3 blog posts
- Homepage and permalink configuration

You can re-import anytime from **Appearance → Import Sample Data**.

## Customization

All theme settings are managed in the WordPress admin:

**Appearance → Theme Settings**

### Top Bar
- Enable/disable top bar
- Phone, email, and support text
- Toggle visibility for each item

### Contact Page
- Hero title and subtitle
- Phone numbers, emails, office address
- Working hours, map embed URL
- Form title, intro text, emergency support message

### Homepage Blog
- Enable/disable blog section (shown after testimonials)
- Section title, subtitle, number of posts

### General
- Footer about text

## Port Configuration

Default port is `8080`. Change it in `.env`:

```
WP_PORT=8080
```
