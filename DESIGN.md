# Bike Rental Marketplace — UI/UX Design System

> **Reverse-engineered from the live codebase.**
> This document is a single source of truth for the project's visual design, component library, layout system, interaction patterns, and UX conventions — exactly as implemented.
> 
> **Framework:** Laravel 12 + Tailwind CSS 4 + Alpine.js 3 + Filament 5.6 + ApexCharts 5  
> **Font:** Instrument Sans (system fallback: ui-sans-serif, system-ui)  
> **Currency:** NPR (Nepalese Rupee)  
> **Admin Panel:** Filament (amber theme, `/admin` path)  
> **Icon Library:** Heroicons (Filament), Lucide (npm), SVG inline (custom)

---

## 1. Project Overview

### Design Philosophy
The design follows a **utility-first minimalism** approach. Every UI element is constructed from Tailwind utility classes directly in Blade templates — there is no custom CSS beyond the Tailwind import and font declaration. The visual style is **clean, monochrome-dominant, with trust signals** (green for positive actions/statuses, yellow for warnings, red for destructive/cancellation, blue for info/ongoing).

### Visual Style
- **Minimalist** — sparse layouts with generous whitespace, thin borders, subtle shadows.
- **Monochromatic base** — text is `gray-900`, backgrounds are `white` or `gray-50`, borders are `gray-200`, secondary text is `gray-500`/`gray-600`.
- **Accent via status** — color is used primarily for semantic meaning (status badges, notifications, validation) rather than decoration.
- **Card-centric** — content is grouped into white rounded cards on a light gray canvas (`bg-gray-50`).

### Branding
- **App name:** Configurable via `APP_NAME` in `.env` (default: "Bike Rental Marketplace").
- **Logo:** Text-only — no image logo exists. The brand name appears in the top-left corner of every layout as `text-xl font-bold text-gray-900`.
- **Color:** No branded primary color in the public/company/customer UI. The branding is typographic (gray-900) with functional green/red/yellow/blue for states. The admin Filament panel uses amber.
- **Currency:** NPR always displayed as `NPR X,XXX.XX` with `number_format()`.

### Design Language
- **Rounded everywhere** — `rounded-lg` on inputs, buttons, cards; `rounded-xl` on cards and major containers; `rounded-full` on badges.
- **Subtle borders** — `border border-gray-200` on cards and containers; `border-gray-300` on inputs.
- **Shadows** — `shadow-sm` on cards; `shadow-lg` on dropdowns/modals; `shadow-md` on hover.
- **Transitions** — `transition-colors`, `transition-shadow`, `transition-transform` with `duration-300` for interactions.
- **Icons** — inline SVGs from Heroicons/Lucide patterns; no icon component.

### UI Inspiration
The design follows **Laravel Jetstream / Tailwind UI** card-and-grid patterns. The public marketplace resembles an e-commerce product grid. The dashboard layout mirrors typical SaaS admin panels with a sidebar nav + sticky header + content area.

### UX Goals
- **Low cognitive load** — clear section headings, consistent `label → value` pattern in detail views, breadcrumbs on nested pages.
- **Trust** — verification badges, status badges, company info cards on bike detail, "Available for booking" green indicator.
- **Progressive disclosure** — booking flow is a multi-section card form; cancellation requires confirmation dialog; extensions require separate request flow.
- **Immediate feedback** — success/error alerts via `session('success')`/`$errors`, Alpine.js live price calculation, notification bell unread badge.

---

## 2. Color System

### Primary (Gray-Based UI)
| Token | Tailwind Class | Hex | Usage |
|-------|----------------|-----|-------|
| Text (headings/body) | `text-gray-900` | `#111827` | All headings, primary text, navigation labels |
| Text (secondary) | `text-gray-600` | `#52525B` | Body text, descriptions, subtitles |
| Text (tertiary/muted) | `text-gray-500` | `#71717A` | Helper text, metadata, placeholder labels |
| Text (extra muted) | `text-gray-400` | `#A1A1AA` | Date stamps, secondary metadata, icon fills |
| Background (page) | `bg-gray-50` | `#F9FAFB` | Page backgrounds (body) |
| Background (white) | `bg-white` | `#FFFFFF` | Cards, headers, sidebars, modals |
| Border (containers) | `border-gray-200` | `#E5E7EB` | Cards, headers, dropdowns, table rows |
| Border (inputs) | `border-gray-300` | `#D4D4D8` | Input fields, selects, textareas |
| Border (hover) | `hover:border-gray-900` | `#111827` | Input focus states |
| Border (divider) | `border-gray-100` | `#F3F4F6` | Internal dividers within cards |
| Button primary bg | `bg-gray-900` | `#111827` | Primary buttons |
| Button primary hover | `hover:bg-gray-800` | `#1F2937` | Primary button hover |
| Button primary text | `text-white` | `#FFFFFF` | Primary button text |
| Link text | `text-gray-900` | `#111827` | All hyperlinks |
| Link hover | `hover:text-gray-900` (or `hover:underline`) | `#111827` | Link hover state |
| Sidebar active | `bg-gray-100` | `#F3F4F6` | Active sidebar link background |
| Sidebar hover | `hover:bg-gray-100` | `#F3F4F6` | Sidebar link hover background |

### Semantic Colors
| Token | Tailwind Class | Hex | Usage |
|-------|----------------|-----|-------|
| Success | `green` | Standard green | Badge variant "green", alert backgrounds (`bg-green-50`, `border-green-200`, `text-green-700`), "Available" indicators (`text-green-600`) |
| Success badge bg | `bg-green-100` | `#DCFCE7` | Completed/verified status badge bg |
| Success badge text | `text-green-700` | `#15803D` | Completed/verified status badge text |
| Warning | `yellow` | Standard yellow | Badge variant "yellow", alert backgrounds (`bg-yellow-50`, `border-yellow-200`, `text-yellow-700`) |
| Warning badge bg | `bg-yellow-100` | `#FEF9C3` | Pending status badge bg |
| Warning badge text | `text-yellow-700` | `#A16207` | Pending status badge text |
| Danger | `red` | Standard red | Badge variant "red", button (`bg-red-600`, `hover:bg-red-700`), alert backgrounds (`bg-red-50`, `border-red-200`, `text-red-600`/`text-red-700`) |
| Danger badge bg | `bg-red-100` | `#FEE2E2` | Cancelled/rejected status badge bg |
| Danger badge text | `text-red-700` | `#B91C1C` | Cancelled/rejected status badge text |
| Info | `blue` | Standard blue | Badge variant "blue" |
| Info badge bg | `bg-blue-100` | `#DBEAFE` | Ongoing/confirmed status badge bg |
| Info badge text | `text-blue-700` | `#1D4ED8` | Ongoing/confirmed status badge text |
| Notification unread | `bg-blue-50` | `#EFF6FF` | Unread notification item bg |
| Star rating | `text-yellow-400` | `#FACC15` | Review star ratings |
| Star empty | `text-gray-300` | `#D4D4D8` | Unfilled star ratings |

### Feedback Colors
| Pattern | Background | Border | Text |
|---------|-----------|--------|------|
| Success alert | `bg-green-50` | `border-green-200` | `text-green-700` |
| Error alert | `bg-red-50` | `border-red-200` | `text-red-600` |
| Warning alert | `bg-yellow-50` | `border-yellow-200` | `text-yellow-700` |
| Error input | — | `border-red-500 focus:border-red-500 focus:ring-red-500` | `text-red-600` |
| Normal input focus | — | `focus:border-gray-900 focus:ring-1 focus:ring-gray-900` | — |

### Dark Mode
**Not implemented.** No dark mode styles exist anywhere in the codebase. All colors are hardcoded as light-mode Tailwind classes.

---

## 3. Typography

### Font Family
- **Primary:** `'Instrument Sans', ui-sans-serif, system-ui, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji'`
- **Declaration source:** `resources/css/app.css` via Tailwind v4 `@theme { --font-sans: ... }`
- **Monospace:** None used in the application.
- **Font Smoothing:** `antialiased` on `<body>`.

### Font Weights
| Weight | Tailwind Class | Usage |
|--------|---------------|-------|
| 400 (normal) | `font-normal` | Body text, table data, input labels |
| 500 (medium) | `font-medium` | Label text, sidebar links, table headers, button text |
| 600 (semibold) | `font-semibold` | Card headers, section titles, stat values, list item emphasis |
| 700 (bold) | `font-bold` | Page titles (`text-2xl`), stat card values, bike price, branding in header |

### Type Scale
| Size | Tailwind Class | Usage |
|------|---------------|-------|
| 10px | `text-[10px]` | Notification badge number, timestamp in notification dropdown |
| 11px | N/A (ApexCharts config) | Chart axis labels, chart legend text |
| 12px | `text-xs` | Status badges, helper text, metadata, file input text, clear link, secondary info |
| 14px | `text-sm` | Body text, button labels, input text, sidebar links, breadcrumbs, form labels, table cells |
| 16px | `text-base` | Large button text (size="lg"), price breakdown total |
| 18px | `text-lg` | Card headers, pricing displays, stat labels, company name in sidebar header |
| 24px | `text-2xl` | Page titles, modal titles, hero heading |
| 30px | `text-3xl` | Bike detail price, emoji icons in cards |
| 36px | `text-4xl` | Homepage hero main heading |
| 48px | `text-5xl` | Homepage hero heading (on desktop via `sm:text-5xl`) |

### Line Heights
- **Standard:** Tailwind defaults (tight: 1.25, normal: 1.5, relaxed: 1.625)
- `leading-relaxed` used on description paragraphs.

### Letter Spacing
- `tracking-tight` on the homepage hero heading (`text-4xl sm:text-5xl font-bold tracking-tight`)
- `tracking-wider` on table header uppercase labels

### Button Text
- `text-sm font-medium` for all button sizes; `text-base` for `size="lg"`.

### Label Text
- `text-sm font-medium text-gray-700` for all form labels.

### Captions / Helper Text
- `text-xs text-gray-500` for image upload hints, metadata timestamps, secondary info.
- `text-xs text-gray-400` for notification timestamps, extra-muted metadata.

---

## 4. Spacing System

### Tailwind Spacing Scale Used
| Class | Value (rem) | Usage |
|-------|-------------|-------|
| `p-0` | 0 | — |
| `p-0.5` | 0.125rem | Star icon padding |
| `p-1` | 0.25rem | Calendar cell padding, icon padding |
| `p-1.5` | 0.375rem | Calendar day cell padding |
| `p-2` | 0.5rem | Sidebar link padding, button padding, icon container |
| `p-3` | 0.75rem | Card content padding, notification items |
| `p-4` | 1rem | Main content padding on mobile (`p-4`), card inner content, sidebar nav padding |
| `p-6` | 1.5rem | Card body padding (`px-6 py-5`), empty state padding |
| `p-8` | 2rem | Full page vertical padding (`py-8`), notification empty state |
| `p-12` | 3rem | Empty table cell vertical padding |
| `p-16` | 4rem | Auth page vertical padding (`py-16`), homepage hero vertical padding |

### Margin / Gap Scale
| Class | Value | Usage |
|-------|-------|-------|
| `mt-1` | 0.25rem | Below headings, above labels |
| `mt-2` | 0.5rem | Below section descriptions, above paragraphs |
| `mt-3` | 0.75rem | Review reply spacing |
| `mt-4` | 1rem | Form section spacing, below cards |
| `mt-6` | 1.5rem | Below page titles, between form sections and cards |
| `mt-8` | 2rem | Below stat card grids, above major sections |
| `mt-16` | 4rem | Before footer, before related/reviews sections |
| `mt-24` | 6rem | Homepage feature card section |
| `mb-2` | 0.5rem | Below subheadings |
| `mb-4` | 1rem | Below section headers |
| `mb-6` | 1.5rem | Below page titles |
| `mx-auto` | auto | Center containers |
| `my-4` | 1rem | Horizontal rules |
| `gap-1` | 0.25rem | Star rating containers |
| `gap-2` | 0.5rem | Small icon+text groups |
| `gap-3` | 0.75rem | Filter row items, card inner groups |
| `gap-4` | 1rem | Default flex/grid gap, header nav items |
| `gap-6` | 1.5rem | Card grids, dashboard sections |
| `gap-8` | 2rem | Large grid columns (public detail) |
| `space-y-1` | 0.25rem | Form label+input groups |
| `space-y-2` | 0.5rem | Compact vertical lists |
| `space-y-3` | 0.75rem | Small vertical item groups |
| `space-y-4` | 1rem | Form sections, card content |
| `space-y-6` | 1.5rem | Between cards, form sections |

### Section Spacing
- Page top: `py-8` (or `py-16` for auth pages)
- Between sections: `mt-8`
- Footer top: `mt-16`
- Card padding: `px-6 py-5`
- Card header padding: `px-6 py-4` with `border-b border-gray-200`

### Container Widths
| Container | Max Width | Usage |
|-----------|-----------|-------|
| `max-w-7xl` | 1280px | Homepage, bike listing, bike detail, public layout |
| `max-w-4xl` | 896px | Company booking show, extension list, calendar |
| `max-w-3xl` | 768px | Customer booking show, bike create/edit forms |
| `max-w-2xl` | 672px | Profile edit, verification forms |
| `max-w-lg` | 512px | Extension create form |
| `max-w-md` | 448px | Auth forms (login, register, password reset) |

### Responsive Padding
- Page horizontal padding: `px-4 sm:px-6 lg:px-8`
- Main content area: `p-4 sm:p-6 lg:p-8`

---

## 5. Border Radius

| Level | Tailwind Class | Usage |
|-------|---------------|-------|
| Full | `rounded-full` | Badges, notification count badge |
| Extra large | `rounded-xl` | Cards, dropdowns, modals, calendar container |
| Large | `rounded-lg` | Buttons, inputs, selects, textareas, images, avatars, stat card icon container, sidebar links |
| XSmall | `rounded` | Remember-me checkbox |

### Component-Specific Radii
| Component | Radius | Value (approximate) |
|-----------|--------|---------------------|
| Cards (default) | `rounded-xl` | 12px |
| Cards (auth) | `rounded-xl` (via `<x-card>`) | 12px |
| Buttons (all variants) | `rounded-lg` | 8px |
| Input fields | `rounded-lg` | 8px |
| Select dropdowns | `rounded-lg` | 8px |
| Textareas | `rounded-lg` | 8px |
| Images (bike cards) | `rounded-xl` (on container) | 12px |
| Thumbnail images | `rounded-lg` | 8px |
| Avatar (company logo) | `rounded-lg` | 8px |
| Stat card icon | `rounded-lg` | 8px |
| Badge pill | `rounded-full` | 9999px |
| Notification bell badge | `rounded-full` | 9999px |
| Calendar day (today) | `rounded-full` | 9999px |
| Modal | `rounded-xl` | 12px |
| File input button | `rounded-lg` | 8px |
| Sidebar link | `rounded-lg` | 8px |

---

## 6. Shadows

| Level | Tailwind Class | Usage |
|-------|---------------|-------|
| Card | `shadow-sm` | Default card state, bike cards, table containers, form sections |
| Card hover | `hover:shadow-md` | Bike card hover, booking card hover |
| Header | `shadow-sm` | Top navigation bar (public, customer, company) |
| Dropdown | `shadow-lg` | Notification dropdown |
| Modal | `shadow-xl` | Modal dialog container |
| Chart | — | No shadow on chart container |

### Implementation
- Cards use `shadow-sm border border-gray-200` (border provides the definition, shadow adds subtle depth).
- On hover (`hover:shadow-md`), cards used as links lift slightly to indicate interactivity.
- The header uses `shadow-sm border-b border-gray-200` with the border being the primary separator.

---

## 7. Elevation

Elevation is expressed through a combination of **shadows**, **borders**, and **background layering**:

- **Layer 0: Page canvas** — `bg-gray-50`, flat, no shadow.
- **Layer 1: Cards & tables** — `bg-white rounded-xl shadow-sm border border-gray-200`. The primary content container. The `shadow-sm` with a border creates a subtle raised appearance against the gray canvas.
- **Layer 2: Fixed header** — `bg-white shadow-sm border-b border-gray-200 sticky top-0 z-40`. Higher z-index, floats above page content on scroll.
- **Layer 3: Sidebar (mobile)** — `fixed inset-0 z-50`. Absolutely positioned above all content. Has a `bg-black/50` backdrop overlay (z-index implicit via stacking context).
- **Layer 4: Modal** — `fixed inset-0 z-50`. Same z-index level as mobile sidebar. Uses `bg-black/50` backdrop. Content panel has `shadow-xl` for the highest elevation.
- **Layer 5: Notification dropdown** — `absolute right-0 mt-2 z-50`. Positioned relative to the bell icon. Renders above all page content with `shadow-lg` and `border border-gray-200`.

---

## 8. Grid System

### Container Width Strategy
The project uses Tailwind's max-width containers. All content is centered with `mx-auto`. There is no full-width layout (except the header, which uses `max-w-7xl` inner wrapper).

### Responsive Grid Column Patterns
| Grid Pattern | Code | Usage |
|-------------|------|-------|
| 1 → 2 → 3 → 4 cols | `grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4` | Public bike listing, related bikes |
| 1 → 2 → 3 cols | `grid-cols-1 sm:grid-cols-2 lg:grid-cols-3` | Company bike listing |
| 1 → 2 cols | `grid-cols-1 sm:grid-cols-2` | Homepage features (desktop: 3 cols via `md:grid-cols-3`) |
| 1 → 4 cols | `grid-cols-1 sm:grid-cols-2 lg:grid-cols-4` | Stat card dashboards |
| 1 → 3 cols | `grid-cols-1 sm:grid-cols-3` | Report index cards, customer quick links |
| 1 → 2 cols (form) | `grid-cols-2` | Form field pairs (always 2 cols, not responsive) |
| 1 → 2 cols (form) | `grid-cols-1 md:grid-cols-2` | Responsive form field pairs |
| 1 → 3 cols (form) | `grid-cols-3` | Pricing fields (always 3 cols) |
| 1 → 5 col (detail) | `grid-cols-1 lg:grid-cols-5` | Bike detail page, booking detail (main 3 + sidebar 2) |
| 1 → 3 col (dashboard) | `grid-cols-1 lg:grid-cols-3` | Dashboard main + sidebar layout |
| 1 → 2 col (dashboard) | `grid-cols-1 lg:grid-cols-2` | Customer dashboard two-column section |
| 2 col (register) | `grid-cols-2` | Role selection cards |
| 7 col (calendar) | `grid-cols-7` | Calendar day-of-week and week grid |
| 4 col (images) | `grid-cols-4` | Bike edit image display |

### Column Span
| Span | Usage |
|------|-------|
| `col-span-full` | Empty-state card filling full grid width |
| `lg:col-span-3` | Main content area in 5-col grid |
| `lg:col-span-2` | Sidebar in 5-col grid, secondary dashboard column |
| `md:col-span-3` | Main content in booking detail |
| `md:col-span-2` | Sidebar in booking detail |

### Responsive Breakpoints
| Breakpoint | Tailwind | Min-Width | Layout Changes |
|------------|----------|-----------|---------------|
| Default (mobile) | `''` | 0px | Single column, sidebar hidden, hamburger menu |
| Small | `sm` | 640px | 2-column grids begin, stat cards go side by side |
| Medium | `md` | 768px | Nav appears in public header, 2-3 column content, sidebar becomes visible on lg |
| Large | `lg` | 1024px | Sidebar becomes visible and fixed position, 3-4 column grids, 5-col detail layouts, form sidebars |
| Extra large | `xl` | 1280px | 4-column bike grid |

---

## 9. Responsive Design

### Desktop (xl+)
- Full sidebar visible (fixed `w-64`).
- 4-column bike grid.
- Breadcrumb navigation visible.
- Notification dropdown hover interaction.
- Calendar full month view.

### Laptop (lg)
- Sidebar visible.
- 3-column bike grid.
- 5-col detail layout (3+2 split).
- Stat cards: 4 per row.

### Tablet (md)
- Sidebar hidden by default; activated via hamburger button.
- Overlay sidebar (`fixed inset-0 z-50` with `bg-black/50` backdrop).
- 2-column bike grid.
- Stat cards: 2 per row.

### Mobile (< md)
- Single column for all grids.
- Hamburger menu in public header.
- Slide-in sidebar with close button.
- Tables use `overflow-x-auto` for horizontal scrolling.
- Compact padding (`p-4`).
- Auth forms fill full width.

### Sidebar Behavior
- **Desktop (lg+):** `lg:relative lg:flex lg:w-64 lg:shrink-0` — sidebar is part of normal flow, always visible.
- **Mobile/tablet:** `fixed inset-0 z-50` with `x-show="sidebarOpen"` controlled by Alpine.js.
- Backdrop: `absolute inset-0 bg-black/50 lg:hidden` — only on mobile.
- Close button: visible only on `lg:hidden`.
- `<main>` area: `flex-1 p-4 sm:p-6 lg:p-8`.

### Navigation Changes
- Public layout: horizontal nav on `md+`, hamburger dropdown on mobile.
- Customer/company: sidebar nav on `lg+`, hamburger toggle on mobile.
- Header: `sticky top-0 z-40` in authenticated layouts, static in public layout.

### Table Responsiveness
- All tables are wrapped in `overflow-x-auto` containers for horizontal scroll on small screens.
- Tables use `w-full text-sm` with consistent `p-3`/`p-4` cell padding.
- No column hiding or reflow pattern — tables scroll horizontally.

### Card Responsiveness
- Cards in grids collapse to single column on mobile.
- Card internal layout uses responsive flex/grid: e.g., `flex items-start justify-between` stacks vertically on narrow screens.
- Booking list cards use `flex items-center gap-4` with image + text.

---

## 10. Navigation

### Top Navbar (Public)
- **Logo:** Left, `text-xl font-bold text-gray-900`.
- **Nav links:** Hidden on mobile (`hidden md:flex`). "Browse Bikes" link.
- **Auth section:** Right side. Shows "Log in" + "Register" button for guests; "Dashboard" for authenticated users.
- **Hamburger:** `md:hidden` button with Alpine.js `x-data="{ mobileMenuOpen: false }"`.
- **Mobile menu:** Slide-down panel (`x-show="mobileMenuOpen"`), bordered section with stacked links.

### Top Navbar (Customer/Company)
- **Mobile sidebar toggle:** Left, `lg:hidden` hamburger button.
- **Logo:** Left of header.
- **User info:** Right side: notification bell + user name + logout.
- **Sticky:** `sticky top-0 z-40`.
- **Height:** `h-16`.

### Sidebar (Customer)
- **Fixed width:** `w-64`.
- **Scroll:** `overflow-y-auto`.
- **Navigation links:** `<x-sidebar-link>` component.
  - Dashboard
  - My Bookings
  - Extensions
  - My Reviews
  - Saved Bikes
  - Invoices
  - Profile
  - Verification
- **Section divider:** `<hr class="my-4">`.
- **External link:** "Browse Bikes" below the divider.
- **Active state:** `bg-gray-100 text-gray-900 font-medium`.
- **Inactive state:** `text-gray-600 hover:text-gray-900 hover:bg-gray-100`.

### Sidebar (Company)
- **Navigation links:**
  - Dashboard
  - My Bikes
  - Bookings
  - Extensions
  - Calendar
  - Reviews
  - Analytics (placeholder)
  - Reports
  - Company Profile
- **External link:** "View Marketplace" below divider.

### Breadcrumbs
- Used on nested pages: bike detail, booking detail (both customer and company), review creation.
- Pattern: `<nav class="text-sm text-gray-500 mb-6">` with `hover:text-gray-900` links separated by `<span class="mx-2">/</span>`.
- Current page: `text-gray-900` (no link).

### Dropdowns
- **Notification dropdown:** `absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden z-50`.
- Controlled by Alpine.js `x-data="{ notifOpen: false }"`.
- Closes on click-away via `@click.away="notifOpen = false"`.
- Uses `x-cloak` to prevent flash.

### User Menu
- **Text only:** User name (`text-sm text-gray-500`) and "Logout" button.
- No dropdown user menu — logout is a visible text button.

---

## 11. Components

### Button (`<x-button>`)
**File:** `resources/views/components/button.blade.php`

| Prop | Type | Default | Options |
|------|------|---------|---------|
| `variant` | string | `'primary'` | `primary`, `secondary`, `danger`, `ghost` |
| `size` | string | `'md'` | `sm`, `md`, `lg` |
| `type` | string | `'button'` | `button`, `submit` |
| `disabled` | bool | `false` | — |
| `href` | string | `null` | Renders as `<a>` instead of `<button>` |

**Variants:**
- `primary`: `text-white bg-gray-900 hover:bg-gray-800 focus:ring-gray-500`
- `secondary`: `text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:ring-gray-500`
- `danger`: `text-white bg-red-600 hover:bg-red-700 focus:ring-red-500`
- `ghost`: `text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:ring-gray-500`

**Sizes:**
- `sm`: `px-3 py-1.5 text-sm`
- `md`: `px-4 py-2 text-sm`
- `lg`: `px-6 py-3 text-base`

**Disabled state:** `opacity-50 cursor-not-allowed` added when `$disabled` is true.

**Focus:** `focus:outline-none focus:ring-2 focus:ring-offset-2` for all variants.

**When `href` is provided,** renders as `<a>` with `inline-flex` class. Otherwise renders as `<button>`.

### Card (`<x-card>`)
**File:** `resources/views/components/card.blade.php`

| Prop | Type | Default | Options |
|------|------|---------|---------|
| `padding` | bool | `true` | Controls whether card body has `px-6 py-5` padding |
| `header` | string/html | `null` | Rendered in a bordered top section `px-6 py-4 border-b border-gray-200` |

**Structure:** `bg-white rounded-xl shadow-sm border border-gray-200` → optional header div → slot div.

### Input (`<x-input>`)
**File:** `resources/views/components/input.blade.php`

| Prop | Type | Default | Options |
|------|------|---------|---------|
| `label` | string | `null` | Form label text |
| `name` | string (required) | — | Input name attribute |
| `id` | string | `$name` | Input id attribute |
| `type` | string | `'text'` | text, email, password, number, date, file, etc. |
| `error` | string | `null` | Override error message |

**Error state:** Auto-detected from `$errors->has($name)`. Border changes to `border-red-500 focus:border-red-500 focus:ring-red-500`. Error message displayed as `text-sm text-red-600`.

**Focus state:** `focus:border-gray-900 focus:ring-1 focus:ring-gray-900`.

**Default border:** `border-gray-300`.

**File input styling:** Uses the `file:` prefix for custom file input styling: `file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200`.

### Select (`<x-select>`)
**File:** `resources/views/components/select.blade.php`

| Prop | Type | Default | Options |
|------|------|---------|---------|
| `label` | string | `null` | Form label text |
| `name` | string (required) | — | Select name attribute |
| `id` | string | `$name` | Select id attribute |
| `options` | array | `[]` | Key-value pairs for `<option>` elements |
| `placeholder` | string | `null` | First disabled option |
| `value` | string | `null` | Selected value |
| `error` | string | `null` | Override error message |

**Styling:** Identical to `<x-input>` — same border, focus, error states. Adds `bg-white`.

### Badge (`<x-badge>`)
**File:** `resources/views/components/badge.blade.php`

| Prop | Type | Default | Options |
|------|------|---------|---------|
| `variant` | string | `'gray'` | `gray`, `green`, `red`, `yellow`, `blue`, `purple` |

**Base:** `inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium`.

**Variants:**
- `gray`: `bg-gray-100 text-gray-700`
- `green`: `bg-green-100 text-green-700`
- `red`: `bg-red-100 text-red-700`
- `yellow`: `bg-yellow-100 text-yellow-700`
- `blue`: `bg-blue-100 text-blue-700`
- `purple`: `bg-purple-100 text-purple-700`

### Stat Card (`<x-stat-card>`)
**File:** `resources/views/components/stat-card.blade.php`

| Prop | Type | Default | Options |
|------|------|---------|---------|
| `label` | string (required) | — | Stat label |
| `value` | string/number (required) | — | Stat value |
| `icon` | string/html | `null` | SVG icon or HTML rendered in gray circle |
| `trend` | string | `null` | Trend text |
| `trendUp` | bool | `true` | Controls trend color/arrow direction |

**Structure:** `bg-white rounded-xl shadow-sm border border-gray-200 p-6`.
- Label: `text-sm font-medium text-gray-500`.
- Value: `mt-1 text-2xl font-semibold text-gray-900`.
- Trend: `mt-1 text-sm inline-flex items-center gap-1`. Green with up arrow if `trendUp`, red with down arrow if not.
- Icon: `p-3 bg-gray-100 rounded-lg` container.

### Table (`<x-table>`)
**File:** `resources/views/components/table.blade.php`

| Prop | Type | Default | Options |
|------|------|---------|---------|
| `headers` | array | `[]` | Header text strings |
| `rows` | array | `[]` | Row content (each element rendered as `<tr>`) |
| `striped` | bool | `false` | Alternating row background |

**Structure:** `overflow-x-auto` wrapper → `table min-w-full divide-y divide-gray-200`.
- Header: `bg-gray-50`, `text-xs font-medium text-gray-500 uppercase tracking-wider`, `px-6 py-3`.
- Body rows: `bg-white divide-y divide-gray-200`.

**Empty state:** "No data available." spanning all columns.

### Sidebar Link (`<x-sidebar-link>`)
**File:** `resources/views/components/sidebar-link.blade.php`

| Prop | Type | Default | Options |
|------|------|---------|---------|
| `href` | string (required) | — | Link URL |
| `active` | bool | `false` | Active state |

**Active:** `block px-3 py-2 text-sm rounded-lg bg-gray-100 text-gray-900 font-medium`.
**Inactive:** `block px-3 py-2 text-sm rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-100`.
**Transition:** `transition-colors`.

### Modal (`<x-modal>`)
**File:** `resources/views/components/modal.blade.php`

| Prop | Type | Default | Options |
|------|------|---------|---------|
| `name` | string (required) | — | Unused in template (prop only) |
| `show` | bool | `false` | Initial open state |
| `maxWidth` | string | `'md'` | `sm`, `md`, `lg`, `xl`, `2xl` |

**Structure:**
- `fixed inset-0 z-50 overflow-y-auto`.
- Centering: `flex items-center justify-center min-h-screen`.
- Backdrop: `fixed inset-0 bg-black/50` with fade transition.
- Panel: `relative bg-white rounded-xl shadow-xl w-full sm:max-w-{size}`.
- **Transitions:** Fade + scale via `x-transition:enter/leave` with `ease-out duration-300` / `ease-in duration-200`.
- **Close:** Escape key, backdrop click, or programmatic.
- **Body lock:** Adds/removes `overflow-hidden` on document.body.

### Input (inline, non-component)
Raw `<input>` / `<textarea>` / `<button>` tags used when not wrapped in the component:
- `<input type="checkbox">` with `rounded border-gray-300` for "Remember me".
- `<input type="datetime-local">` for date pickers in booking form.
- `<input type="file">` with custom file styling.
- Raw `<textarea>` with `class="block w-full rounded-lg border border-gray-300 text-sm px-3 py-2 focus:border-gray-900 focus:ring-1 focus:ring-gray-900"`.

### Status Button (Company Booking)
Used in `company.bookings.show` for status transitions:
- Cancel: `text-red-600 border-red-200 hover:bg-red-50`
- Complete: `text-green-600 border-green-200 hover:bg-green-50`
- Other: `text-gray-700 border-gray-300 hover:bg-gray-50`
- Full width: `w-full px-4 py-2 text-sm font-medium rounded-lg border text-left`

### Review Stars (Alpine.js Interactive)
Inline in `customer/reviews/create.blade.php`:
- 5 star buttons with `x-data="{ rating: 0, hovered: 0 }"`.
- Stars change color on hover/click: `i <= (hovered || rating) ? 'text-yellow-400' : 'text-gray-300'`.
- Hidden input stores rating value.

### Review Stars (Static Display)
- `@for ($i = 1; $i <= 5; $i++)` loop.
- Filled star: `text-yellow-400`.
- Empty star: `text-gray-300`.
- Star SVG: 20x20 viewBox star path.

### Alert Messages
- **Success:** `p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700`.
- **Error:** `p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-600`.
- **Warning (info):** `p-4 bg-yellow-50 border border-yellow-200 rounded-lg text-sm text-yellow-700`.
- **Warning (verification):** Same pattern with appropriate colors.

### Extension Status Badge (Inline, non-component)
Used in customer/company extension views:
- Pending: `px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-700`
- Approved: `px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700`
- Denied: `px-3 py-1 text-xs font-medium rounded-full bg-red-100 text-red-600`

### Company Initials Avatar (Fallback)
When no company logo exists, a text-initials fallback is used:
`w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400 text-lg font-bold`
`{{ strtoupper(substr($company->company_name, 0, 1)) }}`

Sizes vary by context: `w-12 h-12` (default), `w-8 h-8` (compact), `w-20 h-20` (booking bike info).

---

## 12. Forms

### Layout
- Forms use card groupings: each logical section is wrapped in `<x-card header="<h3>...</h3>">`.
- Fields within a card use either `space-y-4` (single column) or `grid grid-cols-2 gap-4` / `grid grid-cols-3 gap-4` (multi-column).
- Submit actions are right-aligned with `flex justify-end gap-4`.
- Cancel buttons are `variant="secondary"`.

### Validation
- **Display:** Inline below each field via `@error('name')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror`.
- **Summary:** `$errors->first()` displayed in a red alert box at the top of auth forms.
- **Date validation:** `@error('dates')` used for overlapping booking errors.

### Required Indicators
- Fields use the `required` HTML attribute.
- No asterisk indicators on labels — required is implicit.

### Error State
- Input border: `border-red-500 focus:border-red-500 focus:ring-1 focus:ring-red-500`.
- Error text: `text-sm text-red-600 mt-1`.

### Helper Text
- `text-xs text-gray-500` placed below input descriptions (e.g., "The first image will be used as the primary photo.").
- Placeholder text used for format hints (e.g., "e.g. 150cc", "Helmet included").

### Disabled State
- `disabled` attribute on `<input>` elements (e.g., email in profile edit).
- No special disabled styling beyond the default browser opacity reduction.

### Success State
- Green border alert: `p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700`.
- Displayed via `@if (session('success'))`.

---

## 13. Tables

### Style
- Simple unstyled `<table>` with `w-full text-sm`.
- **Header:** `border-b border-gray-200 text-left text-gray-500` with `pb-3` or `p-4` padding.
- **Rows:** `divide-y divide-gray-100` or `divide-gray-200`.
- **Hover:** `hover:bg-gray-50`.
- **No** sticky headers, no row striping by default (available via `<x-table striped>`).

### Sorting
No client-side sorting implemented. Server-side sorting via query parameters (`sort=newest`, `sort=price_low`).

### Filtering
- Inline filter forms with `<select>` dropdowns that auto-submit via `onchange="this.form.submit()"`.
- Filter state preserved in query string.
- "Clear" link appears when any filter is active.

### Pagination
- Laravel default pagination links (`{{ $bookings->links() }}`).
- Tailwind-styled pagination via Tailwind's pagination view (not customized).
- No vendor pagination views published — uses Laravel's default Tailwind views.

### Bulk Actions
Not implemented anywhere.

### Selection
Not implemented anywhere.

---

## 14. Dashboard Design

### Customer Dashboard
- **Header:** "Welcome, {name}" with subtitle.
- **Stat cards:** 4-column grid: Upcoming Bookings, Active Rentals, Completed, Verification status.
- **Two-column section:**
  - Left: "Upcoming & Active Bookings" list with image + bike name + dates + status badge.
  - Right: "Recently Completed" list with image + bike name + dates + amount.
- **Quick links:** 3 card grid: Browse Bikes, My Profile, Verification.

### Company Dashboard
- **Header:** "Company Dashboard" with subtitle.
- **Stat cards:** 4-column grid: Total Revenue, Total Bikes, Available Bikes, Active Bookings.
- **Two-column section:**
  - Left (2/3 width): Revenue chart (ApexCharts area chart, last 30 days).
  - Right (1/3): Top Performing Bikes list.
- **Recent Bookings:** Full-width table with Booking #, Bike, Customer, Amount, Status columns.

### Admin Dashboard (Filament)
- **Widgets:** StatsOverview (Total Users, Total Bikes, Total Bookings, Revenue, Pending Verifications).
- **Resources:** User, Bike, Booking, CompanyVerification CRUD.
- **Pages:** Revenue Report, Company Performance Report.
- **Theme:** Amber primary color (`Color::Amber`).

### Chart Configuration (ApexCharts)
- **Type:** Area chart.
- **Stroke:** Smooth curve, 2px, `#18181b` (gray-900).
- **Fill:** Gradient from 30% opacity to 0.
- **X-axis:** Dates, `#71717a` labels, `11px`, rotated -45°, formatted "MMM dd".
- **Y-axis:** NPR formatter.
- **Grid:** `#e4e4e7` with dashed lines (`strokeDashArray: 4`).
- **Tooltip:** NPR format.
- **No toolbar, no zoom.**

---

## 15. Public Pages

### Homepage
- **Layout:** `layouts.public`.
- **Hero:** Centered heading "Rent a Bike, Anywhere." (`text-4xl sm:text-5xl font-bold tracking-tight`), subtitle, two CTA buttons ("Browse Bikes" primary, "Get Started" secondary).
- **Feature cards:** 3-column grid: "Wide Selection", "Flexible Pricing", "Verified Companies". Each with emoji icon + title + description.
- **Footer:** Simple centered copyright.

### Bike Listing
- **Header:** "Browse Bikes" with total count.
- **Search bar:** Full-width text input with search icon overlay.
- **Sort dropdown:** Newest, Price Low-High, Price High-Low, Name.
- **Filters:** Brand, Category, Fuel Type, Transmission dropdowns + Min/Max price inputs + Clear link.
- **Grid:** 1/2/3/4 columns responsive. Card layout with image (hover scale), name, brand/model, specs, daily price + company name.
- **Empty state:** "No bikes match your filters."
- **Pagination:** Standard Laravel links.

### Bike Detail
- **Breadcrumbs:** Home > Bikes > Bike Name.
- **Gallery (Alpine.js):** Main image (16:9 aspect ratio) with clickable thumbnails below.
- **Description card:** Text paragraph.
- **Features card:** Checkmark list.
- **Rental rules card:** Info icon list.
- **Pricing sidebar:** Name + brand/model, daily price (large text), hourly/weekly prices below, specs grid (2x3: Category, Fuel, Transmission, Engine, Mileage, Color), "Available" green indicator.
- **Company card:** Logo+name+address.
- **CTA button:** "Book Now" (customers) or "Log in to Book" (guests).
- **Reviews section:** Star ratings with text, company replies (green left border).
- **Related bikes:** 4-column grid with smaller cards.

### Auth Pages
- **Login:** Email + Password + Remember me + Forgot password link.
- **Register (role selection):** Two-card grid: Customer vs Company.
- **Register Customer:** Name, Email, Phone, Password, Confirm Password.
- **Register Company:** Owner Name, Company Name, Email, Phone, Password, Confirm Password.
- **Forgot Password:** Email input + send link button.
- **Reset Password:** Token hidden + Email + Password + Confirm Password.
- **Verify Email:** Information + Resend button + Logout link.

### Verification Pages
- **Customer verification:** Multi-card form: Personal Info, Driving License (front+back), Documents (citizenship front+back, selfie).
- **Company verification:** Business Details + Documents (registration cert, PAN cert, owner citizenship, owner photo).
- **Status alerts:** Pending (yellow), Verified (green), Rejected (red).

---

## 16. Icons

### Icon Library
- **Lucide** — installed as npm dependency but no direct usage found in Blade files.
- **Heroicons** — used by Filament (via `Heroicon::OutlinedRectangleStack`).
- **Inline SVGs** — custom SVG paths from Heroicons/Lucide copied directly into Blade templates.

### Inline SVG Patterns
- **Hamburger menu:** 3 horizontal lines + X close icon (with Alpine.js conditional visibility).
- **Search:** Magnifying glass.
- **Notification:** Bell.
- **Trend up/down:** Arrow icons.
- **Checkmark:** Green check for features.
- **Info:** i-in-circle for rental rules.
- **Verified:** Green check circle for availability.
- **Star:** Filled/empty star paths for ratings.
- **Close:** X icon for mobile sidebar close button.

### Icon Sizes
- `w-4 h-4` — Small icons (trend arrows, checkmarks, info, stars)
- `w-5 h-5` — Bell notification, sidebar close
- `w-6 h-6` — Hamburger menu, sidebar close (desktop)
- `w-3.5 h-3.5` — Availability indicator

### Icon Colors
- Default: `text-gray-400` / `text-gray-500` / `text-gray-600`.
- Semantic: `text-green-600`, `text-yellow-400`, `text-red-500`.

### Emoji as Icons
- Used in cards and navigation for visual metaphor (e.g., homepage features, role selection, report types).
- Common emojis: 🏍️, 💰, ✅, 👤, 🏢, 📋.

---

## 17. Images

### Gallery (Bike Detail)
- Aspect ratio: `aspect-[16/9]` with `object-cover`.
- Container: `rounded-xl overflow-hidden bg-gray-100`.
- Thumbnails: `w-20 h-16 rounded-lg overflow-hidden border border-gray-200`.
- Active thumbnail: `ring-2 ring-gray-900`.

### Bike Card Images
- Dimensions: `w-full h-48 object-cover`.
- Hover effect: `group-hover:scale-105 transition-transform duration-300`.
- Fallback: `w-full h-48 bg-gray-100 flex items-center justify-center text-gray-400` with "No image" text.

### Related Bike Card Images
- Dimensions: `w-full h-40 object-cover`.

### Company Logo
- Default display: `w-12 h-12 rounded-lg object-cover`.
- Compact: `w-8 h-8 rounded`.
- In booking detail: `w-20 h-20 rounded-lg object-cover`.

### Profile Photo
- Upload field without preview.
- Fallback: Initials avatar (text-based).

### Placeholders
- Gray background (`bg-gray-100`) with gray text (`text-gray-400`) showing "No image" / "No img" / initials.
- Empty state illustrations: none — all empty states are text-only.

### Storage
All images served via `asset('storage/' . $image->image_path)`. The `storage:link` symlink is required.

---

## 18. Motion

### Hover Animations
- **Cards (as links):** `hover:shadow-md transition-shadow` (bike cards, booking cards).
- **Bike card images:** `group-hover:scale-105 transition-transform duration-300`.
- **Buttons:** `transition-colors` (all variants).
- **Sidebar links:** `transition-colors`.
- **Table rows:** `hover:bg-gray-50` (background color change, no transition).
- **Breadcrumb links:** `hover:text-gray-900` (no explicit transition).
- **Thumbnail border:** `hover:border-gray-400 transition-colors`.

### Button Animations
- Color transitions via `transition-colors` on all button variants.

### Page Transitions
None. Standard full-page load (no Turbolinks/Livewire navigation).

### Loading Animations
None. No loading spinners, skeletons, or progress bars.

### Dropdown Animation
None. The notification dropdown uses `x-show` with no transition — appears/disappears instantly.

### Modal Animation
- **Enter:** `ease-out duration-300`, starts from `opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95`, ends at `opacity-100 translate-y-0 sm:scale-100`.
- **Leave:** `ease-in duration-200`, starts from `opacity-100 translate-y-0 sm:scale-100`, ends at `opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95`.
- **Backdrop:** Fade in/out with `duration-300` / `duration-200`, `opacity-0` ↔ `opacity-100`.

---

## 19. UX Patterns

### Search
- Single text input with search icon overlay.
- Placeholder: "Search by name, brand, or company...".
- Server-side query, full page reload.
- Results counter: "X bikes available".

### Filter
- Dropdown selects for categorical filters (brand, category, fuel type, transmission).
- Number inputs for price range.
- Auto-submit on change (`onchange="this.form.submit()"`).
- "Clear" link to reset all filters.
- Query string preserved across requests.

### Booking Flow
1. **Discovery:** Browse bikes on marketplace → click bike card → view detail.
2. **Creation:** Customer clicks "Book Now" → date/time picker form with Alpine.js live price calculation → "Confirm Booking" button.
3. **Confirmation:** Redirect to booking detail page with success message.
4. **Status lifecycle:** Pending → Confirmed (by company) → Ongoing (by company) → Completed (by company). Cancellable from pending/confirmed by customer or company.
5. **Post-completion:** Customer can leave a review.

### Confirmation Dialogs
- `onclick="return confirm('Are you sure...')"` for destructive actions (cancel booking, status transitions).
- Native browser `confirm()` dialog — no custom modal for confirmation.

### Notifications
- **Bell icon:** Top-right of header with unread count badge.
- **Dropdown:** Shows last 10 notifications with title, message, timestamp.
- **Unread indicator:** Blue background (`bg-blue-50`) on unread items.
- **Mark all read:** Link at top of dropdown.
- **Notification types:** Booking created, confirmed, completed, cancelled.

### Destructive Actions
- Red buttons (`variant="danger"` or inline red-styled buttons).
- Confirmation dialog via native `confirm()`.
- Cancel button on forms (secondary variant, links back).

### Loading
No loading indicators — all operations are synchronous form submissions.

### Error Handling
- **Validation:** Inline errors below fields (`text-red-600`).
- **General errors:** Red alert boxes at top of page.
- **403:** `abort_if()` with default 403 page.
- **404:** Automatic for missing models.
- **Overlapping booking:** Custom error on dates field.

### Empty States
All follow the pattern: `<x-card><p class="text-gray-500 text-center py-8">Message</p></x-card>` with optional action link.

Known empty states:
- No bikes match filters (with "Clear filters" link)
- No bookings (with "Browse bikes" link)
- No reviews
- No extension requests (with "View your bookings" link)
- No invoices
- No saved bikes
- No analytics data (placeholder)

### Success Feedback
- Green alert box at page top: `p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700`.
- Persistent across redirects via `session('success')`.

---

## 20. Accessibility

### Focus States
- **Buttons:** `focus:outline-none focus:ring-2 focus:ring-offset-2` with ring color matching variant.
- **Inputs:** `focus:border-gray-900 focus:ring-1 focus:ring-gray-900`.
- **Error inputs:** `focus:border-red-500 focus:ring-1 focus:ring-red-500`.
- **Sidebar links:** Default browser focus outline (no custom focus ring).
- **No** skip-to-content link.

### Keyboard Navigation
- **Modal:** `@keydown.escape.window="open = false"` — escape key closes.
- **Native form navigation:** All forms support tab navigation natively.
- **No** custom keyboard shortcuts.

### Contrast
- Primary text: `#111827` (gray-900) on white or `#F9FAFB` (gray-50) backgrounds.
- Secondary text: `#52525B` on white — meets WCAG AA.
- Muted text: `#71717A` on white — meets WCAG AA for 18px+ text.
- File input text: `text-gray-500` on white — may be low contrast for some users.
- Green success: `text-green-700` (`#15803D`) on `bg-green-50` (`#F0FDF4`).
- Red error: `text-red-600` (`#DC2626`) on `bg-red-50` (`#FEF2F2`).

### ARIA Usage
- No explicit `aria-*` attributes found in Blade templates.
- No `role` attributes.
- No `aria-live` regions.
- No `aria-label` on icon buttons.

### Screen Reader Support
- Icon-only buttons (hamburger, close, notification bell) have no accessible label text.
- Star rating visual interaction is keyboard-accessible (button elements) but has no `aria-label`.
- No `sr-only` text helpers found.
- No `alt` text on bike images beyond empty/default.

---

## 21. Filament Theme

### Panel Configuration
- **ID:** `admin`
- **Path:** `/admin`
- **Login:** Enabled (uses Filament's built-in login page).
- **Primary color:** Amber (`Color::Amber`).
- **Notifications:** Database notifications with 30s polling.
- **Access control:** `canAccess()` checks for `Admin` role.

### Resources
- **UserResource:** Name, Email, Phone, Email verified at, Account status, Created/Updated at. Form: name, email, phone, email_verified_at, password, account_status.
- **BikeResource:** All bike fields (company, category, name, brand, model, year, engine, fuel, transmission, mileage, color, numbers, VIN, description, features, specs, rules, pricing, status, is_available). Soft delete support with restore/force delete.
- **BookingResource:** All booking fields including new late_fee/refund/extended_until fields.
- **CompanyVerificationResource:** Company ID, document paths, status, rejected_reason, verified_at. Documents are text fields (paths), not file uploads.

### Widgets
- **StatsOverview:** Total Users, Total Bikes, Total Bookings, Revenue, Pending Verifications.

### Pages
- **Dashboard:** Default Filament dashboard.
- **RevenueReport:** Completed bookings table with date range filter, columns for booking number, customer, bike, amount, late fee, refund, date.
- **CompanyPerformanceReport:** Company table with bike count, booking count, revenue, sorted by revenue descending.

### Navigation
- Filament's default sidebar navigation.
- Resources appear with `Heroicon::OutlinedRectangleStack` icons.
- Report pages grouped under "Reports" navigation group.

### Icons in Filament
- All resources use `Heroicon::OutlinedRectangleStack` as navigation icon.

---

## 22. Tailwind Configuration

### Configuration File
**No tailwind.config.* file exists.** The project uses Tailwind CSS v4 which uses `@import 'tailwindcss'` and the `@theme` directive for configuration, all within `resources/css/app.css`.

### Custom Theme (via `@theme`)
```css
@theme {
    --font-sans: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji',
        'Segoe UI Symbol', 'Noto Color Emoji';
}
```
Only the sans font family is customized. All other values use Tailwind v4 defaults.

### Source Discovery
```css
@source '../../vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php';
@source '../../storage/framework/views/*.php';
@source '../**/*.blade.php';
@source '../**/*.js';
```

### Plugins
- `@tailwindcss/vite` (Vite plugin for Tailwind v4).
- No Tailwind UI plugin.
- No custom plugins.
- No PostCSS config — Tailwind v4's Vite plugin handles processing.

### Screens
Only responsive breakpoints are standard Tailwind: `sm` (640px), `md` (768px), `lg` (1024px), `xl` (1280px), `2xl` (1536px). No custom breakpoints.

### Custom Utilities
None. All styles are utility classes directly in Blade.

---

## 23. Design Tokens

### Spacing Tokens
| Token | Value | Usage |
|-------|-------|-------|
| `--space-1` | 4px | `p-1`, `gap-1`, `m-1` |
| `--space-2` | 8px | `p-2`, `gap-2`, `m-2` |
| `--space-3` | 12px | `p-3`, `gap-3` |
| `--space-4` | 16px | `p-4`, `gap-4` |
| `--space-5` | 20px | `p-5` (card body padding) |
| `--space-6` | 24px | `p-6`, `gap-6` |
| `--space-8` | 32px | `p-8`, `gap-8` |
| `--space-12` | 48px | `p-12` |
| `--space-16` | 64px | `p-16` |
| `--space-24` | 96px | `mt-24` |

### Radius Tokens
| Token | Value | Usage |
|-------|-------|-------|
| `--radius-lg` | 8px | Inputs, buttons, selects, images |
| `--radius-xl` | 12px | Cards, modals, dropdowns |
| `--radius-full` | 9999px | Badges, notification count |

### Font Tokens
| Token | Value |
|-------|-------|
| `--font-sans` | `'Instrument Sans', ui-sans-serif, system-ui, sans-serif, ...` |

### Color Tokens (Gray Scale)
| Token | Value | Usage |
|-------|-------|-------|
| `--color-gray-50` | `#F9FAFB` | Page background |
| `--color-gray-100` | `#F3F4F6` | Sidebar active, table header, file input bg |
| `--color-gray-200` | `#E5E7EB` | Borders, dividers |
| `--color-gray-300` | `#D4D4D8` | Input borders, empty stars |
| `--color-gray-400` | `#A1A1AA` | Muted icons, timestamps |
| `--color-gray-500` | `#71717A` | Muted text, secondary labels |
| `--color-gray-600` | `#52525B` | Body text, descriptions |
| `--color-gray-700` | `#374151` | Form labels, badge text |
| `--color-gray-800` | `#1F2937` | Button hover |
| `--color-gray-900` | `#111827` | Primary text, buttons, brand |

### Color Tokens (Semantic)
| Token | Value |
|-------|-------|
| `--color-green-50` | Success alert bg |
| `--color-green-100` | Success badge bg |
| `--color-green-200` | Success alert border |
| `--color-green-600` | Available indicator |
| `--color-green-700` | Success badge/alert text |
| `--color-red-50` | Error alert bg |
| `--color-red-100` | Error badge bg |
| `--color-red-200` | Error alert border |
| `--color-red-500` | Error input border |
| `--color-red-600` | Error text, danger button bg |
| `--color-red-700` | Danger button hover, error badge text |
| `--color-yellow-50` | Warning alert bg |
| `--color-yellow-100` | Warning badge bg |
| `--color-yellow-200` | Warning alert border |
| `--color-yellow-400` | Star rating filled |
| `--color-yellow-700` | Warning text |
| `--color-blue-50` | Unread notification bg |
| `--color-blue-100` | Info badge bg |
| `--color-blue-700` | Info badge text |

### Shadow Tokens
| Token | Value |
|-------|-------|
| `--shadow-sm` | 0 1px 2px 0 rgb(0 0 0 / 0.05) |
| `--shadow-md` | 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1) |
| `--shadow-lg` | 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1) |
| `--shadow-xl` | 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1) |

---

## 24. Component Inventory

### Reusable Blade Components (`resources/views/components/`)
- [x] `<x-button>` — Button + link with 4 variants, 3 sizes, disabled state
- [x] `<x-card>` — Container card with optional header and padding toggle
- [x] `<x-input>` — Form input with label, error state, file styling
- [x] `<x-select>` — Form select with label, error state, placeholder
- [x] `<x-badge>` — Status badge with 6 color variants
- [x] `<x-stat-card>` — Statistic display card with optional icon and trend
- [x] `<x-table>` — Generic table with headers, striped rows, empty state
- [x] `<x-sidebar-link>` — Sidebar navigation link with active state
- [x] `<x-modal>` — Modal dialog with transitions, backdrop, escape close

### Inline Components (Blade partials, not extracted)
- Notification list item (dropdown)
- Review star display (5-star SVG loop)
- Review star input (Alpine.js interactive)
- Status transition buttons (company booking)
- Breadcrumb nav
- Price breakdown list
- Empty state card
- Alert messages (success/error/warning)
- Extension status badge
- Company avatar (text initials fallback)
- Bike card (grid listing)
- Bike spec table (detail sidebar)
- Image gallery (Alpine.js)

### Filament Components
- StatsOverview (widget)
- User Form/Schema + Table
- Bike Form/Schema + Table (with soft deletes)
- Booking Form/Schema + Table
- CompanyVerification Form/Schema + Table
- RevenueReport (page with table + filters)
- CompanyPerformanceReport (page with table)

---

## 25. Page Inventory

### Public Pages

| Page | Route | Layout | Components | Responsive |
|------|-------|--------|------------|------------|
| **Homepage** | `/` | `layouts.public` | `x-button`, `x-card` | 3-col md, 1-col mobile |
| **Bike Listing** | `/bikes` | `layouts.public` | `x-card` (empty), inline bike cards | 1→2→3→4 col grid |
| **Bike Detail** | `/bikes/{bike}` | `layouts.public` | `x-card`, `x-button` | 5-col lg, 1-col mobile |
| **Login** | `/login` | `layouts.public` | `x-card`, `x-input`, `x-button` | Single col, max-w-md |
| **Register** | `/register` | `layouts.public` | `x-card` | 2-col card grid, single col mobile |
| **Register Customer** | `/register/customer` | `layouts.public` | `x-card`, `x-input`, `x-button` | Single col |
| **Register Company** | `/register/company` | `layouts.public` | `x-card`, `x-input`, `x-button` | Single col |
| **Forgot Password** | `/forgot-password` | `layouts.public` | `x-button` | Single col, max-w-md |
| **Reset Password** | `/reset-password/{token}` | `layouts.public` | `x-button` | Single col, max-w-md |
| **Verify Email** | `/email/verify` | `layouts.public` | `x-button` | Single col, max-w-md |

### Customer Pages

| Page | Route | Layout | Components | Responsive |
|------|-------|--------|------------|------------|
| **Dashboard** | `/customer/dashboard` | `layouts.customer` | `x-stat-card`, `x-card`, `x-badge` | 4→2→1 col stats, 2→1 col sections |
| **Bookings List** | `/customer/bookings` | `layouts.customer` | `x-card` (empty), `x-badge` | Card list, responsive |
| **Booking Detail** | `/customer/bookings/{booking}` | `layouts.customer` | `x-card`, `x-badge`, `x-button` | 5-col md: 3+2 split, 1-col mobile |
| **Booking Create** | `/customer/bookings/create/{bike}` | `layouts.customer` | `x-card`, `x-input`, `x-button` | 5-col md: 3+2 split |
| **Reviews List** | `/customer/reviews` | `layouts.customer` | `x-card` (empty) | Card list |
| **Review Create** | `/customer/reviews/create/{booking}` | `layouts.customer` | `x-card`, Alpine.js stars | Single col max-w-2xl |
| **Extensions List** | `/customer/extensions` | `layouts.customer` | Inline status badges | Card list |
| **Extension Create** | `/customer/extensions/create/{booking}` | `layouts.customer` | `x-button` | Single col max-w-lg |
| **Profile Edit** | `/customer/profile` | `layouts.customer` | `x-card`, `x-input`, `x-select`, `x-button` | Single col max-w-2xl |
| **Verification** | `/customer/verification` | `layouts.customer` | `x-card`, `x-input`, `x-select`, `x-button` | Single col max-w-2xl |
| **Saved Bikes** | `/customer/wishlist` | `layouts.customer` | `x-card` (empty) | Single col |
| **Invoices** | `/customer/invoices` | `layouts.customer` | `x-card` (empty) | Single col |

### Company Pages

| Page | Route | Layout | Components | Responsive |
|------|-------|--------|------------|------------|
| **Dashboard** | `/company/dashboard` | `layouts.company` | `x-stat-card`, `x-card`, `x-badge`, ApexCharts | 4-col stats, 3-col lg (2+1), table |
| **Bikes List** | `/company/bikes` | `layouts.company` | `x-button`, `x-card` (empty), `x-badge` | 1→2→3 col cards, responsive |
| **Bike Create** | `/company/bikes/create` | `layouts.company` | `x-card`, `x-input`, `x-select`, `x-button` | max-w-3xl, fields 2-col grid |
| **Bike Edit** | `/company/bikes/{bike}/edit` | `layouts.company` | `x-card`, `x-input`, `x-select`, `x-badge`, `x-button` | max-w-3xl, fields 2-col, images 4-col |
| **Bookings List** | `/company/bookings` | `layouts.company` | `x-card` (empty), `x-badge` | Table with overflow-x-auto |
| **Booking Detail** | `/company/bookings/{booking}` | `layouts.company` | `x-card`, `x-badge` | 5-col md: 3+2 split |
| **Reviews List** | `/company/reviews` | `layouts.company` | `x-card` (empty) | Card list |
| **Extensions List** | `/company/extensions` | `layouts.company` | `x-button` | Card list |
| **Calendar** | `/company/calendar` | `layouts.company` | Inline table grid | 7-column grid |
| **Analytics** | `/company/analytics` | `layouts.company` | `x-card` (placeholder) | Single col |
| **Reports Index** | `/company/reports` | `layouts.company` | `x-card` | 1→3 col grid |
| **Revenue Report** | `/company/reports/revenue` | `layouts.company` | `x-stat-card`, `x-card` | Single col, overflow-x-auto table |
| **Booking Report** | `/company/reports/bookings` | `layouts.company` | `x-card`, `x-badge` | Table with overflow-x-auto |
| **Bike Report** | `/company/reports/bikes` | `layouts.company` | `x-stat-card`, `x-card` | Table with overflow-x-auto |
| **Profile Edit** | `/company/profile` | `layouts.company` | `x-card`, `x-input`, `x-button` | Single col max-w-2xl |
| **Verification** | `/company/verification` | `layouts.company` | `x-card`, `x-input`, `x-button` | Single col max-w-2xl |

### Admin Pages (Filament)

| Page | Path | Components |
|------|------|------------|
| **Dashboard** | `/admin` | StatsOverview, AccountWidget, FilamentInfoWidget |
| **Users List** | `/admin/users` | Filament table with search, edit, delete |
| **Users Create** | `/admin/users/create` | Filament form |
| **Users Edit** | `/admin/users/{record}/edit` | Filament form + delete action |
| **Bikes List** | `/admin/bikes` | Filament table (full bike fields, trashed filter) |
| **Bikes Create** | `/admin/bikes/create` | Filament form |
| **Bikes Edit** | `/admin/bikes/{record}/edit` | Filament form + delete/force-delete/restore |
| **Bookings List** | `/admin/bookings` | Filament table (all booking fields) |
| **Bookings Create** | `/admin/bookings/create` | Filament form |
| **Bookings Edit** | `/admin/bookings/{record}/edit` | Filament form + delete |
| **Company Verifications List** | `/admin/company-verifications` | Filament table |
| **Company Verifications Create** | `/admin/company-verifications/create` | Filament form |
| **Company Verifications Edit** | `/admin/company-verifications/{record}/edit` | Filament form + delete |
| **Revenue Report** | `/admin/reports/revenue` | Filament page with table (date filter, completed bookings) |
| **Company Performance** | `/admin/reports/companies` | Filament page with table (company stats, sorted by revenue) |

---

*End of DESIGN.md — reverse-engineered from the project source code on July 3, 2026.*
