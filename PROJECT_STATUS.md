# Bike Rental Marketplace — Project Description & Status

> Generated from the live codebase. Companion to `about_project.md` (spec) and `DESIGN.md` (design system).

---

## 1. What It Is

A multi-role web marketplace where:

- **Companies** register, get verified, and list bikes for rent
- **Customers** browse, book, pay, and review bikes
- **Admins** verify users, manage listings, and monitor platform finances

Built spec-first from `about_project.md` (784-line MVP spec).

---

## 2. Tech Stack

| Layer | Tech |
|---|---|
| Backend | Laravel 12, PHP 8.2, MySQL, Sanctum, Spatie Permission |
| Frontend | Blade + Tailwind CSS 4 + Alpine.js + ApexCharts (Vite pipeline) |
| Admin | Filament 5 (amber theme, `/admin`) — separate from custom Blade dashboards |
| Payments | Khalti + eSewa (sandbox) via `app/Services/PaymentService.php` |
| Money | Wallet ledger, commission, payouts (QR/proof), refunds |
| Tests | PHPUnit, sqlite in-memory (`phpunit.xml`) |

---

## 3. Architecture

Feature-based layout:

```
app/Http/Controllers/{Public,Auth,Customer,Company,Admin}
app/Services        BookingPricingService, CommissionService, PaymentService,
                    PayoutService, RefundService, WalletService
app/Policies        BikePolicy (+ others)
app/Enums           BookingStatusEnum, RoleEnum, AccountStatusEnum
app/Filament        Resources (Bikes, Bookings, Users, Companies, Payments,
                    Payouts, Wallet, Reviews, Categories, Extensions,
                    CompanyVerifications), Widgets, Report pages
app/Notifications   In-app (database) notifications
routes/web.php      All public/customer/company routes
```

**Booking is the core entity** (spec §28): price snapshots, commission, payments, extensions, reviews, notifications, and calendar views all hang off it.

---

## 4. User Roles

| Role | Capabilities | Gates |
|---|---|---|
| Customer | Register, verify identity, browse, book, pay, cancel, review, extensions, profile | Cannot book until verified (spec §2.1) |
| Company | Register, verify business, list/manage bikes, manage bookings, calendar, reports, payouts, bank details | Cannot publish bikes before verification |
| Admin | Filament panel: users, companies, bikes, bookings, verifications, payments, payouts, wallet, reports + custom `/admin/financial` dashboard | Admin accounts not self-deletable |

---

## 5. What's DONE (verified in code)

### Phase 0 — Foundation ✅
Laravel 12 scaffold, Sanctum, Spatie Permission, Filament 5, Tailwind 4, Alpine.js, ApexCharts, Lucide, base layouts (`public`, `customer`, `company`, `admin`), reusable components (Button, Card, Modal, Input, Select, Badge, Table, StatCard, SidebarLink), feature directory structure.

### Phase 1 — Auth & Roles ✅
Customer/company registration with role auto-assignment, role-based redirect login, `role:` middleware, password reset, email verification routes, rate limiting (login 5/min, register 3/min).

### Phase 2 — Profiles & Verification ✅
Customer profile (photo, personal info), company profile (logo, cover, hours, social links), customer identity document submission, company business document submission, account deactivation with business-rule checks, soft deletes.

### Phase 3 — Bike Management ✅
Bike CRUD gated by `BikePolicy` + company verification, categories (7 seeded), multiple image upload, hourly/daily/weekly pricing, inventory fields (VIN, reg #, bike #), status management, rental rules (JSON), availability toggle.

### Phase 4 — Marketplace ✅
Public listing with filters (brand, category, price, fuel, transmission), sort, search (name/brand/model/company), detail page with Alpine gallery, specs, company card, reviews, related bikes, pagination with query-string preservation.

### Phase 5 — Booking Engine ✅
Booking creation with live Alpine price calculation, overlapping-booking validation, status lifecycle (pending → confirmed → ongoing → completed / cancelled / expired), customer + company cancellation with refund rules (>24h 100%, <24h 50%, after start 0%), late-return fees, rental extension requests (customer asks, company approves/denies), company month-view calendar, booking numbers `BK-YYYYMMDD-XXXXXX`.

### Phase 6 — Reviews & Notifications ✅
Reviews (1–5 stars, post-completed-booking only, unique per booking), company replies, public display on bike detail, Laravel database notifications (BookingCreated/Confirmed/Completed/Cancelled), notification bell + dropdown with unread badge, mark-all-read.

### Phase 7 — Dashboards ✅
Customer dashboard (stats, upcoming/active/recent bookings, verification status, quick links), company dashboard (revenue/bikes/stats, 30-day ApexCharts area chart, top-performing bikes, recent bookings), admin Filament StatsOverview widget.

### Phase 8 — Reports ✅
Company revenue/booking/bike-performance reports with date-range filters, CSV export for all three, admin Filament report pages (RevenueReport, CompanyPerformanceReport).

### Marketplace Money Layer ✅ (beyond the written phases)
- **Checkout**: deposit + remaining payment flow, Khalti & eSewa sandbox integration, callbacks, success/failure pages
- **Wallet**: per-company credit/debit ledger (`WalletService`, `wallet_transactions`)
- **Commission**: snapshot on booking confirmation (`CommissionService`)
- **Payouts**: request → approve → mark paid (with QR + payment proof) → failed (`PayoutService`)
- **Bank details**: company CRUD + QR code support
- **Refunds**: rules-based calculation (`RefundService`), wired to cancellations
- **Admin finance**: custom `/admin/financial` dashboard (commission, revenue, payouts, refunds, failed payments)
- **Cleanup**: `bookings:expire-pending-payments` scheduled every minute (`routes/console.php`)

### Phase 9 — Production Readiness (partial) ✅
Rate limiting, form validation across controllers, policies + route middleware authorization, CSRF, eager loading (anti N+1), soft deletes, responsive grid/`overflow-x-auto` tables, 6 smoke tests passing.

---

## 6. What's LEFT To Do

### 🔴 Broken / incomplete flows (fix first)

1. **Customer verification has no approval path**
   `Customer\VerificationController::submit()` sets `status = 'pending'`, but no Filament resource or admin page exists to approve/reject it (only `CompanyVerifications` exists). Customers are stuck pending forever.
   → `app/Http/Controllers/Customer/VerificationController.php:65`

2. **Booking not gated on customer verification**
   Spec §2.1: unverified customers cannot book. `Customer\BookingController` has no verification check.
   → `app/Http/Controllers/Customer/BookingController.php:51,60`

3. **Stub views**
   `customer/wishlist`, `customer/invoices`, `company/analytics` are "coming soon" empty cards served by closure routes.
   → `routes/web.php:108,112,143`

4. **eSewa refunds are manual-only**
   `PaymentService::refundEsewa()` logs a warning instead of calling the API.
   → `app/Services/PaymentService.php`

5. **Unstaged changes**
   `DEPLOYMENT.md`, `IMPLEMENTATION_PROGRESS.md`, `Redesign.md` deleted; bookings migration default changed `Pending → PendingPayment`. Commit or restore.

### 🟡 Missing spec features

6. Notification **index page** (only the bell dropdown exists)
7. Search filters spec'd but absent: location, rating, availability; sorts "Most Booked" / "Highest Rated" (§8.2–8.3)
8. No-show handling (§26.3/§26.8), company reliability score, company no-show penalty
9. Real invoices (PDF generation) — CSV export exists, PDF doesn't
10. Admin notifications for new customer verification requests (§17.3)

### 🟠 `Redesign.md` design roadmap — none implemented

11. Dark mode (DESIGN.md §2: "Not implemented")
12. Airbnb-style hero + search homepage, booking stepper (Dates → Review → Payment → Confirmation)
13. Skeleton loading, empty-state illustrations, custom 404/403/500 pages
14. Accessibility: ARIA labels, focus states, 44px touch targets, skip-to-content
15. Mobile bottom nav (component exists at `components/mobile-bottom-nav.blade.php` but unused)

### 🟣 Production readiness

16. Tests: only 6 page-load smoke tests — no booking, payment, refund, or authorization tests
17. Mail config (password reset / email verify silently fail without SMTP)
18. Docs: README and CHANGELOG still stock Laravel; `DEPLOYMENT.md` deleted
19. Deploy checklist: cron for `schedule:run`, admin user seeder, `storage:link`, Khalti/eSewa env keys

---

## 7. Recommended Order

| # | Task | Why |
|---|---|---|
| 1 | Admin approval for customer verification | Blocks the entire customer journey |
| 2 | Gate booking on verified status | Spec compliance + trust |
| 3 | Implement wishlist / invoices / analytics | Dead links in nav |
| 4 | Restore or commit deleted docs + migration change | Repo is dirty |
| 5 | Notification page + missing filters | Core UX gaps |
| 6 | Booking/payment/refund feature tests | Money paths untested |
| 7 | Redesign.md items, then deploy | Polish, then ship |
