# Bike Rental Marketplace - Complete Project Specification (MVP)

## 1. Project Overview

A web-based bike rental marketplace where:
- Companies register and list bikes for rent
- Customers browse, compare, and book bikes
- Admins manage the platform, verify users, and monitor overall activity

The platform supports multiple rental durations (hourly, daily, weekly), online booking management, identity verification, analytics, and business dashboards.

---

## 2. User Roles

### 2.1 Customer
**Can:**
- Register/Login
- Complete profile
- Verify identity
- Browse bikes
- Search and filter bikes
- View bike details
- Book bikes
- View booking history
- Cancel bookings (according to policy)
- Leave reviews
- View invoices
- Manage profile

**Cannot:**
- Book bikes until verified

### 2.2 Company
**Can:**
- Register
- Complete company profile
- Upload verification documents
- List bikes
- Edit bikes
- Remove bikes
- Manage bookings
- Accept/Reject bookings
- View analytics
- View revenue
- Track bike availability
- Manage company information

**Cannot:**
- Publish bikes before company verification

### 2.3 Admin
**Can:**
- Manage customers
- Manage companies
- Manage bikes
- Manage bookings
- Verify identities
- Verify companies
- Suspend users
- Remove fraudulent listings
- View platform analytics
- Manage reviews
- Manage categories

---

## 3. Authentication

### 3.1 Registration

**Customer:**
- Name
- Email
- Phone
- Password

**Company:**
- Owner Name
- Company Name
- Email
- Phone
- Password

### 3.2 Login
- Email
- Password

### 3.3 Password Reset
- Forgot Password
- Email verification
- Reset Password

---

## 4. Customer Verification

**Required before booking.**

### 4.1 Personal Information
- Full Name
- Date of Birth
- Gender
- Citizenship Number
- Permanent Address
- Current Address

### 4.2 Driving License
- License Number
- Expiry Date
- Front Image
- Back Image

### 4.3 Documents
- Citizenship Front
- Citizenship Back
- Selfie

### 4.4 Status
- Unverified
- Pending
- Verified
- Rejected

---

## 5. Company Verification

**Required before publishing bikes.**

### 5.1 Business Information
- Company Name
- Registration Number
- PAN Number
- Address
- Contact Number

### 5.2 Documents
- Company Registration Certificate
- PAN Certificate
- Owner Citizenship
- Owner Photo

### 5.3 Status
- Pending
- Verified
- Rejected

---

## 6. Company Profile

- Logo
- Cover Image
- Description
- Address
- Contact Number
- Opening Hours
- Social Links
- Verification Badge
- Rating

---

## 7. Bike Management

### 7.1 Basic Information
- Name
- Brand
- Model
- Year
- Engine Capacity
- Fuel Type
- Transmission
- Mileage
- Color

### 7.2 Pricing
- Hourly Price
- Daily Price
- Weekly Price

### 7.3 Inventory
- Bike Number
- Registration Number
- VIN
- Availability

### 7.4 Images
- Multiple Images

### 7.5 Additional Details
- Description
- Features
- Specifications
- Rental Rules

### 7.6 Status
- Active
- Inactive
- Maintenance
- Booked

---

## 8. Bike Listing

### 8.1 Customer Can
- Browse bikes
- Filter
- Sort
- Search

### 8.2 Filters
- Brand
- Price
- Location
- Fuel
- Transmission
- Rating
- Availability

### 8.3 Sort Options
- Newest
- Lowest Price
- Highest Price
- Most Booked
- Highest Rated

---

## 9. Bike Details Page

- Gallery
- Bike Details
- Pricing
- Company Information
- Availability Calendar
- Specifications
- Features
- Reviews
- Related Bikes
- Book Now Button

---

## 10. Booking System

### 10.1 Customer Selection
- Pickup Date
- Pickup Time
- Return Date
- Return Time
- Rental Type
  - Hourly
  - Daily
  - Weekly

### 10.2 System Calculation
- Duration
- Total Price

### 10.3 Booking Status
- Pending
- Confirmed
- Ongoing
- Completed
- Cancelled

---

## 11. Booking Calendar

**Company views:**
- Monthly calendar
- Each bike's booked dates
- Available dates
- Upcoming pickups
- Upcoming returns

---

## 12. Customer Dashboard

**Overview:**
- Upcoming Booking
- Current Booking
- Rental History
- Reviews
- Saved Bikes (Wishlist)
- Invoices
- Profile
- Verification Status

---

## 13. Company Dashboard

### 13.1 Overview Cards
- Total Revenue
- Monthly Revenue
- Total Bikes
- Available Bikes
- Booked Bikes
- Active Bookings
- Completed Bookings
- Total Customers

### 13.2 Analytics
- Revenue Chart
- Booking Chart
- Bike Utilization
- Top Performing Bikes
- Least Performing Bikes

### 13.3 Other Sections
- Upcoming Pickups
- Upcoming Returns
- Booking Calendar
- Recent Reviews

---

## 14. Admin Dashboard

### 14.1 Cards
- Total Companies
- Total Customers
- Total Bikes
- Active Bookings
- Revenue
- Pending Verifications

### 14.2 Management
- Users
- Companies
- Bikes
- Bookings
- Reviews
- Categories
- Verification Requests
- Platform Analytics

---

## 15. Reviews

**Customer can review after completed booking.**

- Rating: 1–5
- Review Text
- Company replies

---

## 16. Search

**Search by:**
- Bike Name
- Brand
- Location
- Company

---

## 17. Notifications

**In-app notifications only (MVP)**

### 17.1 Customer
- Booking confirmed
- Booking cancelled
- Booking completed
- Verification approved

### 17.2 Company
- New booking
- Booking cancelled
- Bike approved
- Verification approved

### 17.3 Admin
- New verification request
- New company registration
- Reported issue

---

## 18. Analytics

### 18.1 Company
- Revenue
- Booking trend
- Popular bikes
- Least rented bikes
- Bike utilization
- Average rental duration
- Monthly earnings
- Customer statistics

### 18.2 Admin
- Platform growth
- Most active companies
- Revenue
- Bookings
- Verification statistics

---

## 19. Reports

### 19.1 Company
- Revenue Report
- Booking Report
- Bike Report
- Customer Report

### 19.2 Admin
- Platform Report
- Company Report
- Booking Report

---

## 20. Profile Management

### 20.1 Customer
- Update profile
- Upload profile photo
- Change password
- Delete account (subject to business rules)

### 20.2 Company
- Edit profile
- Upload logo
- Upload cover
- Business information
- Change password

---

## 21. Rental Rules

**Each company defines:**
- Helmet included
- Fuel policy
- Late return policy
- Security instructions
- Pickup location
- Return location
- Minimum age
- Required documents

---

## 22. Database Modules

1. Users
2. Roles
3. Customer Profiles
4. Company Profiles
5. Company Verification
6. Customer Verification
7. Bikes
8. Bike Images
9. Bike Categories
10. Bike Pricing
11. Bookings
12. Booking Timeline
13. Reviews
14. Notifications
15. Analytics
16. Reports

---

## 23. Technology Stack

### 23.1 Backend
- Laravel 12
- PHP 8.4+
- MySQL
- Laravel Sanctum and session-based auth for web

### 23.2 Frontend
- Blade
- Ready-made UI/UX components (e.g., Shadcn)

### 23.3 Charts
- Suitable chart library for Blade

### 23.4 Storage
- Laravel Storage

---

## 24. Development Phases

### Phase 1 – Foundation
- Authentication
- Role management
- Customer/company profiles
- Verification workflow
- Dashboard layouts

### Phase 2 – Core Marketplace
- Bike CRUD
- Search & filters
- Availability
- Booking system
- Pricing engine
- Reviews

### Phase 3 – Business Features
- Company analytics
- Admin dashboard
- Reports
- Notifications
- Calendar views

### Phase 4 – Production Readiness
- Performance optimization
- Security hardening
- Testing
- Responsive UI
- Deployment
- Monitoring and backups

---

## 25. Future Roadmap (Not Part of MVP)

- Dynamic pricing (weekends, holidays, demand)
- Coupon and promotional codes
- Referral program
- Security deposit management
- Damage reporting with photo evidence
- GPS tracking integration
- Digital rental agreements and e-signatures
- In-app messaging between customers and companies
- Insurance options
- Waitlist for unavailable bikes
- Multi-branch company support
- Export reports (PDF/Excel)
- Role-based staff accounts for companies
- Email/SMS notifications
- Audit logs

---

## 26. Booking Policies

### 26.1 Customer Cancellation Policy

**More than 24 hours before pickup:**
- Customer can cancel for free
- Booking status → Cancelled
- Full refund (if prepaid)
- Bike becomes available immediately

**Within 24 hours before pickup:**
- Customer can still cancel, but a cancellation fee applies
- Refund: 75%
- Cancellation Fee: 25%

**After the rental start time:**
- Customer cannot cancel
- Booking becomes No Show if they never arrive
- No refund unless the company decides otherwise

**During an active rental:**
- Customers cannot cancel an ongoing rental
- If they return the bike early: No automatic refund
- Company policy determines whether any refund is given

### 26.2 Company Cancellation Policy

Companies should have stricter rules because cancellations by the owner create a poor customer experience.

A company should only be allowed to cancel for valid reasons such as:
- Bike damaged
- Bike under maintenance
- Mechanical issue
- Safety concern

If a company cancels:
- Customer receives a full refund
- The cancellation is recorded
- Frequent cancellations can affect the company's reliability score

### 26.3 Admin Override

The admin can:
- Cancel any booking
- Issue full or partial refunds
- Resolve disputes
- Restore bookings if appropriate

### 26.4 Booking Status Flow

```
Pending
   ↓
Confirmed
   ↓
Ongoing
   ↓
Completed
```

**Alternative states:**
```
Pending → Cancelled
Confirmed → No Show
Confirmed → Cancelled by Company
```

### 26.5 Refund Rules

| Situation | Refund |
|-----------|--------|
| Customer cancels >24 hours before pickup | 100% |
| Customer cancels within 24 hours | 75% |
| Customer does not show up | 0% |
| Company cancels | 100% |
| Admin cancels due to platform issue | 100% |

### 26.6 Rental Extension

Customers can request an extension only if:
- The bike is not already booked by another customer immediately afterward
- The company approves the extension

If the bike has another booking, the extension request is rejected.

### 26.7 Late Return Policy

- **Up to 30 minutes late** → No charge (grace period)
- **30 minutes to 2 hours** → Hourly late fee
- **More than 2 hours** → Charge for one additional rental day

### 26.8 Customer No-Show

If the customer does not arrive within a defined grace period (e.g., 1 hour after scheduled pickup):
- Booking status changes to No Show
- Bike becomes available again
- No refund

### 26.9 Company No-Show

If the company fails to provide the booked bike:
- Full refund to the customer
- Booking marked as Company Failed to Fulfill
- Count toward the company's reliability metrics

### 26.10 MVP Policy Recommendation

- Free cancellation more than 24 hours before pickup
- 25% cancellation fee if cancelled within 24 hours
- No refund after the rental start time or for customer no-shows
- Full refund if the company cancels
- 30-minute grace period for late returns, then apply late fees
- Allow rental extensions only when there is no conflicting future booking

---

## 27. Account Deletion Rules

### 27.1 Customer Account Deletion

A customer cannot delete their account if:
- They have an active booking
- They have a future confirmed booking
- They have a pending payment
- They have an unresolved dispute
- Their identity verification is under review
- They are involved in an ongoing investigation (e.g., fraud or damage claim)

If none of the above applies:
- The account can be deactivated immediately
- Personal information can be hidden from public view
- Booking history is retained for reporting and legal purposes

### 27.2 Company Account Deletion

A company cannot delete its account if:
- It has active bike listings
- It has ongoing or upcoming bookings
- It has pending payouts or financial settlements
- It has unresolved customer disputes
- It has verification requests under review

Before deletion, the company should:
- Remove or deactivate all bike listings
- Complete or cancel future bookings
- Resolve all pending payments
- Resolve all disputes

### 27.3 Admin Account Rules

- Admin accounts should never be self-deletable
- Super Admin can deactivate admin accounts
- Keep an audit trail of all admin actions

### 27.4 Soft Delete vs Hard Delete

**Soft Delete (Recommended):**
- Account is marked as deleted but remains in the database
- Advantages:
  - Booking history remains intact
  - Revenue reports stay accurate
  - Can restore the account if deletion was accidental
  - Easier to comply with audits and investigations
- Laravel supports this using the SoftDeletes trait

**Hard Delete:**
- Completely removes the account and all associated data
- Use only when:
  - The account has never made a booking
  - No financial records exist
  - No legal or business reason requires retention
  - An administrator explicitly approves the deletion

### 27.5 What Happens After Deactivation?

**Customer:**
- Cannot log in
- Cannot make new bookings
- Booking history remains
- Reviews remain (marked as from a deleted account if desired)

**Company:**
- Bikes are hidden from the marketplace
- Future bookings are not allowed
- Company profile is no longer publicly visible
- Historical bookings and financial records remain

### 27.6 Suggested Account Statuses

- Active
- Pending Verification
- Suspended
- Deactivated (requested by user)
- Deleted (soft deleted)

### 27.7 Admin Controls

The admin should be able to:
- Deactivate an account
- Reactivate an account
- Soft delete an account
- Permanently delete an account (only if eligible)
- Suspend an account for policy violations

### 27.8 MVP Recommendation

For your MVP, do not implement permanent account deletion by users.

**Use this workflow:**
1. User clicks "Delete Account"
2. The system checks all business rules
3. If eligible, the account is soft deleted (deactivated)
4. Historical bookings, payments, reviews, and reports remain intact
5. Only an admin can permanently remove the account after confirming there are no legal, financial, or operational reasons to retain it

---

## 28. Architectural Advice

> **Treat Booking as the heart of the system.**

Every important business process should relate to a booking:
- Bike availability
- Revenue calculations
- Customer rental history
- Company earnings
- Analytics
- Reviews (only after completed bookings)
- Notifications
- Calendar views

If the booking model is designed well—with proper statuses, timestamps, pricing snapshots, and relationships—you'll be able to add future features like deposits, dynamic pricing, extensions, insurance, and reporting without redesigning the core database.

This approach keeps the project maintainable and scalable as it grows.