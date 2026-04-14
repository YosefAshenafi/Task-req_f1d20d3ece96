# CampusOps Design Specification

## System Overview

CampusOps is a unified offline campus operations portal supporting activity lifecycle management, order processing with a multi-state workflow, logistics tracking, violation management with appeals, search with ranking, personalized recommendations, role-based dashboards, and data export.

## Domain Model

### Activities
- Organized into groups with versioned content
- Lifecycle: Draft -> Published -> In Progress -> Completed -> Archived
- Signup management with capacity limits and eligibility tags
- Change log tracking across versions

### Orders
- Tied to activities for supply/equipment procurement
- State machine: Placed -> Pending Payment -> Paid -> Ticketing -> Ticketed -> Closed
- 30-minute auto-cancel for unpaid orders
- Refund restricted to administrators only
- Closed-order address correction requires reviewer approval workflow

### Shipments
- Linked to orders for logistics tracking
- Barcode/QR scan events with location tracking
- Delivery confirmation and exception reporting
- States: Created -> In Transit -> Delivered | Exception

### Violations
- Point-based system with configurable rules
- Categories: attendance, conduct, reward
- Multi-stage workflow: Pending -> Under Review -> Approved/Rejected -> Resolved
- Appeal and final decision process

### Users & RBAC
- Roles: administrator, operations_staff, team_lead, reviewer, regular_user
- Permission-based access with wildcard support
- Object-level authorization (users can only access their own resources unless privileged)
- Account lockout after 5 failed login attempts (15-minute cooldown)

## Security

- Passwords: bcrypt with per-user salt
- Sessions: token-based authentication
- Sensitive data: AES encryption for invoice addresses, field-level masking in API responses
- Rate limiting: per-IP request throttling
- Input validation: server-side validation on all endpoints

## Search

- Full-text search across activities and orders
- Pinyin support for Chinese character input
- Spell correction with Levenshtein distance
- Sort modes: relevance, recency, popularity
- Field-level highlighting in results
- Logistics-specific search with tracking number tokenization and synonym expansion

## Recommendations

- Personalized based on signup history and tag analysis
- Per-tag diversity cap (40%) to prevent homogeneous results
- Multi-signal scoring: tag overlap, recency, popularity, views, scarcity
- Cold-start fallback using recent popular activities

## Export

- Formats: PNG (image), PDF (native), CSV/Excel
- User-attributable watermarks on all exports
- Secure download endpoint with access control
