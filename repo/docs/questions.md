# CampusOps - Assumptions & Design Decisions

## Ambiguous Requirements - Decisions Made

### 1. Refund Authorization
**Question:** Which roles can process refunds?
**Decision:** Only administrators. The `orders.refund` permission is exclusively granted to the administrator role. An explicit role check (`role === 'administrator'`) is enforced in `OrderService::refund` as a defense-in-depth measure beyond RBAC middleware.

### 2. Closed-Order Address Correction
**Question:** Can any user with `orders.update` directly modify a closed order's address?
**Decision:** No. Closed orders require a formal request+approve workflow. Any user can submit a request via `POST /orders/:id/request-address-correction`, but only reviewers or administrators can approve via `POST /orders/:id/approve-address-correction`. Direct `PUT /orders/:id/address` rejects closed orders.

### 3. Auto-Cancel Timer
**Question:** How is the 30-minute payment timer enforced?
**Decision:** When payment is initiated, `auto_cancel_at` is set to `now + 30 minutes`. A CLI command `orders:auto-cancel` runs on a cron schedule (every minute) to cancel expired orders. The command is registered in `backend/config/console.php`.

### 4. Search Ranking
**Question:** What dimensions should search support for sorting/filtering?
**Decision:** Three sort modes: `relevance` (title match priority + recency), `recency` (newest first), `popularity` (most viewed first). Field-level highlighting is included by default. Logistics search adds tracking number tokenization and synonym expansion.

### 5. Recommendation Diversity
**Question:** How is the 40% diversity cap applied?
**Decision:** Per-tag diversity capping. Each individual tag is tracked across recommendations, and no single tag can appear in more than 40% of the result set. This prevents homogeneous results even when activities share overlapping tag sets.

### 6. PDF Export
**Question:** What PDF generation approach to use without external dependencies?
**Decision:** Native PDF 1.4 binary generation. The export service builds a minimal valid PDF structure with text content and watermarks, avoiding HTML-to-PDF conversion dependencies.

### 7. Sensitive Data in List Responses
**Question:** Should sensitive field masking apply to paginated list endpoints?
**Decision:** Yes. The `SensitiveDataMiddleware` recursively traverses response payloads including `data.list[*]` arrays to mask configured sensitive fields (e.g., `invoice_address`) for non-administrator users.

### 8. Object-Level Authorization
**Question:** Should regular users be able to view order history for orders they don't own?
**Decision:** No. `OrderService::getHistory` enforces ownership checks. Only administrators, operations staff, and reviewers can access history for any order. Regular users can only see history for their own orders.

### 9. Rate Limiting Strategy
**Question:** In-memory vs shared storage for rate limiting?
**Decision:** File-based shared storage using the runtime directory. This ensures consistent rate limiting across multiple PHP-FPM workers and survives process restarts, while avoiding external dependencies like Redis.

### 10. Debug Mode
**Question:** Should `APP_DEBUG` be enabled?
**Decision:** `APP_DEBUG=false` in the committed `.env` file. Debug mode should only be enabled in local development via environment override, never in shared/production configs.
