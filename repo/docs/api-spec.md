# CampusOps API Specification

Base URL: `/api/v1`

## Authentication

### POST /auth/login
Login with username and password. Returns auth token.

### POST /auth/logout
Logout and invalidate current session token. Requires auth.

### POST /auth/unlock
Unlock a locked user account. Requires `users.password` permission.

## Users

### GET /users
List users with pagination. Requires `users.read`. Sensitive fields masked for non-admins.

### GET /users/:id
Get user detail. Requires `users.read`.

### POST /users
Create user. Requires `users.create`.

### PUT /users/:id
Update user. Requires `users.update`.

### DELETE /users/:id
Delete user. Requires `users.delete`.

### PUT /users/:id/role
Change user role. Requires `users.update`.

### PUT /users/:id/password
Reset user password. Requires `users.password`.

## Activities

### GET /activities
List activities with pagination and filters. Requires `activities.read`.

### GET /activities/:id
Get activity detail. Requires `activities.read`.

### GET /activities/:id/versions
Get version history. Requires `activities.read`.

### GET /activities/:id/signups
Get signups for activity. Requires `activities.read`.

### GET /activities/:id/change-log
Get change log. Requires `activities.read`.

### POST /activities
Create activity. Requires `activities.create`.

### PUT /activities/:id
Update activity. Requires `activities.update`.

### POST /activities/:id/publish
Publish activity. Requires `activities.publish`.

### POST /activities/:id/start
Start activity. Requires `activities.transition`.

### POST /activities/:id/complete
Complete activity. Requires `activities.transition`.

### POST /activities/:id/archive
Archive activity. Requires `activities.transition`.

### POST /activities/:id/signups
Sign up for activity. Requires `activities.signup`.

### DELETE /activities/:id/signups/:signup_id
Cancel signup. Requires `activities.signup`.

### POST /activities/:id/signups/:signup_id/acknowledge
Acknowledge signup. Requires `activities.signup`.

## Orders

### GET /orders
List orders with pagination. Requires `orders.read`. Sensitive fields masked.

### GET /orders/:id
Get order detail. Requires `orders.read`. Returns 404 if not found or access denied.

### GET /orders/:id/history
Get order state history. Requires `orders.read`. Object-level authorization enforced.

### POST /orders
Create order. Requires `orders.create`.

### PUT /orders/:id
Update order (non-closed only). Requires `orders.update`.

### POST /orders/:id/initiate-payment
Start payment process (sets 30-min auto-cancel timer). Requires `orders.payment`.

### POST /orders/:id/confirm-payment
Confirm payment. Requires `orders.payment`.

### POST /orders/:id/start-ticketing
Start ticketing process. Requires `orders.ticketing`.

### POST /orders/:id/ticket
Add ticket number. Requires `orders.ticketing`.

### POST /orders/:id/refund
Refund paid order. **Administrator only** (explicit role check, not just permission).

### POST /orders/:id/cancel
Cancel order. Requires `orders.cancel`.

### POST /orders/:id/close
Close ticketed order. Requires `orders.close`.

### PUT /orders/:id/address
Update address (non-closed orders only). Closed orders must use request+approve flow.

### POST /orders/:id/request-address-correction
Request address correction for closed order. All roles submit requests; approval required.

### POST /orders/:id/approve-address-correction
Approve address correction. **Reviewer/administrator only**.

## Shipments

### GET /shipments
List all shipments with pagination and status filter. Requires `shipments.read`.

### GET /orders/:order_id/shipments
List shipments for a specific order. Requires `shipments.read`.

### POST /orders/:order_id/shipments
Create shipment. Requires `shipments.create`.

### GET /shipments/:id
Get shipment detail. Requires `shipments.read`. Object-level authorization enforced.

### POST /shipments/:id/scan
Process scan event. Requires `shipments.update`.

### GET /shipments/:id/scan-history
Get scan history. Requires `shipments.read`.

### POST /shipments/:id/confirm-delivery
Confirm delivery. Requires `shipments.deliver`.

### GET /shipments/:id/exceptions
Get exceptions. Requires `shipments.read`.

### POST /shipments/:id/exceptions
Report exception. Requires `shipments.exception`.

## Violations

### GET /violations/rules
List violation rules. Requires `violations.read`.

### POST /violations/rules
Create rule. Requires `violations.rules`.

### GET /violations
List violations with pagination. Requires `violations.read`.

### POST /violations/:id/appeal
Submit appeal. Requires `violations.appeal`.

### POST /violations/:id/review
Review violation. Requires `violations.review`.

### POST /violations/:id/final-decision
Final decision on violation. Requires `violations.review`.

## Search

### GET /search
Search with: `q`, `type`, `page`, `limit`, `sort` (relevance|recency|popularity), `highlight` (0|1).

### GET /search/suggest
Autocomplete suggestions with Pinyin support.

### GET /search/logistics
Logistics-specific search with tracking number tokenization and synonym handling.

## Export

### GET /export/orders
Export orders. Format: `csv`, `png`, `pdf`. Requires `dashboard.export`.

### GET /export/activities
Export activities.

### GET /export/violations
Export violations.

### GET /export/download
Download generated export file by filename.

## Dashboard, Notifications, Preferences, Recommendations, Audit

Standard CRUD/read endpoints with role-based access. See route definitions in `backend/route/app.php`.
