# CampusOps Static Audit Report

## 1. Verdict
- **Overall conclusion:** **Fail**
- The repository has broad feature coverage, but multiple **High** severity gaps against Prompt-critical requirements (violation evidence validation path, group-level demerit alerting, logistics search requirement fit, activity signup state consistency, dashboard favorites schema/code mismatch), plus non-functional test harness evidence.

## 2. Scope and Static Verification Boundary
- **Reviewed:** repository docs, route registration, middleware/auth/RBAC, backend services/controllers/models/migrations/seeds, frontend static pages/modules, test config/suites/results, and export/search/recommendation/dashboard modules.
- **Not reviewed:** runtime behavior in browser/API server, Dockerized environment behavior, cron scheduling behavior, actual DB runtime migrations/constraints execution, real file upload HTTP transport behavior.
- **Intentionally not executed:** project startup, Docker, tests, external services (per audit rules).
- **Manual verification required:** end-to-end UI interaction correctness, route/middleware runtime binding behavior in ThinkPHP, cron cadence execution for `orders:auto-cancel`/`index:cleanup`, export file rendering fidelity.

## 3. Repository / Requirement Mapping Summary
- **Prompt core goal mapped:** unified on-prem campus operations portal with activity lifecycle/versioning, order lifecycle/state machine, fulfillment tracking, violations/appeals, search/recommendations, dashboards/exports, and offline security controls.
- **Mapped implementation areas:** `backend/route/app.php`, `backend/app/{controller,service,middleware,model}`, `backend/database/{migrations,seeds}`, `frontend/public/index.html`, `frontend/src/{modules,views}`, `docs/*`, test suites and `phpunit.xml`.
- **Primary risk areas found:** requirement-fit deltas in logistics search and demerit/group workflows, data-state inconsistencies affecting activity signup lifecycle, schema/service drift in dashboard favorites, and weak static test reliability.

## 4. Section-by-section Review

### 4.1 Hard Gates

#### 4.1.1 Documentation and static verifiability
- **Conclusion:** **Partial Pass**
- **Rationale:** Startup/config/test docs exist and are detailed, but they overstate test verifiability because committed test artifacts show most suites failing due framework/DB bootstrap issues.
- **Evidence:** `repo/README.md:7`, `repo/README.md:51`, `repo/Makefile:33`, `repo/phpunit.xml:10`, `repo/unit_tests/bootstrap.php:5`, `repo/test-results/output.txt:6`, `repo/test-results/junit.xml:3`
- **Manual verification note:** Actual runbook success (startup/migrate/seed/tests) requires manual execution.

#### 4.1.2 Material deviation from Prompt
- **Conclusion:** **Fail**
- **Rationale:** Several Prompt-explicit behaviors are not fully implemented (logistics search pinyin/spell fit, group-level point alerting, strict evidence attachment validation path, signup lifecycle consistency for acknowledgment/headcount).
- **Evidence:** `repo/backend/app/service/SearchService.php:113`, `repo/backend/app/service/SearchService.php:171`, `repo/backend/app/service/ViolationService.php:191`, `repo/backend/app/service/ViolationService.php:228`, `repo/backend/app/service/ViolationService.php:345`, `repo/backend/app/service/ActivityService.php:281`, `repo/backend/app/service/ActivityService.php:437`, `repo/backend/database/migrations/20260414100006_create_activity_signups_table.php:14`, `repo/backend/database/seeds/DatabaseSeeder.php:240`

### 4.2 Delivery Completeness

#### 4.2.1 Core requirements coverage
- **Conclusion:** **Partial Pass**
- **Rationale:** Many core modules exist (auth/RBAC, activities, orders, shipments, violations, search, recommendations, dashboards, exports), but key requirement details are incomplete/inconsistent.
- **Evidence:** `repo/backend/route/app.php:44`, `repo/backend/route/app.php:76`, `repo/backend/route/app.php:119`, `repo/backend/route/app.php:137`, `repo/backend/route/app.php:228`, `repo/backend/route/app.php:267`, `repo/backend/route/app.php:277`, `repo/backend/route/app.php:299`

#### 4.2.2 End-to-end deliverable vs partial/demo
- **Conclusion:** **Partial Pass**
- **Rationale:** Project is structurally complete (backend/frontend/docs/tests), but production confidence is reduced by broken automated test execution evidence and several mismatched business rules.
- **Evidence:** `repo/README.md:66`, `repo/frontend/public/index.html:53`, `repo/backend/public/index.php:8`, `repo/test-results/junit.xml:3`, `repo/test-results/output.txt:13`

### 4.3 Engineering and Architecture Quality

#### 4.3.1 Structure and module decomposition
- **Conclusion:** **Pass**
- **Rationale:** Backend has clear separation across controllers/services/models/middleware/commands/validation; route map is centralized; frontend has modular files (though `frontend/public/index.html` is also large and monolithic).
- **Evidence:** `repo/README.md:70`, `repo/backend/route/app.php:6`, `repo/backend/config/middleware.php:9`, `repo/frontend/src/modules/activities.js:1`, `repo/frontend/public/index.html:95`

#### 4.3.2 Maintainability/extensibility
- **Conclusion:** **Partial Pass**
- **Rationale:** Core structure is extensible, but schema/service drift and state-model inconsistencies indicate maintainability risks.
- **Evidence:** `repo/backend/app/service/DashboardService.php:80`, `repo/backend/database/migrations/20260414100025_create_dashboard_favorites_table.php:13`, `repo/backend/app/service/ActivityService.php:437`, `repo/backend/database/migrations/20260414100006_create_activity_signups_table.php:14`

### 4.4 Engineering Details and Professionalism

#### 4.4.1 Error handling/logging/validation/API practice
- **Conclusion:** **Partial Pass**
- **Rationale:** Basic validation and JSON error responses exist, but important paths are weak (violation evidence path bypasses file validation constraints; status code handling is inconsistent in some controllers).
- **Evidence:** `repo/backend/app/service/UploadService.php:27`, `repo/backend/app/service/ViolationService.php:191`, `repo/backend/app/controller/OrderController.php:85`, `repo/backend/app/controller/OrderController.php:89`, `repo/backend/app/validate/OrderValidate.php:9`

#### 4.4.2 Product-level completeness vs demo
- **Conclusion:** **Partial Pass**
- **Rationale:** Overall shape resembles a product, but critical workflow gaps and failing tests indicate it remains below delivery-acceptance quality.
- **Evidence:** `repo/backend/app/service/OrderService.php:190`, `repo/backend/app/command/AutoCancelOrders.php:22`, `repo/backend/app/service/RecommendationService.php:17`, `repo/test-results/junit.xml:3`

### 4.5 Prompt Understanding and Requirement Fit

#### 4.5.1 Business goal and implicit constraints fit
- **Conclusion:** **Fail**
- **Rationale:** Core business intent is understood, but multiple explicit constraints are not fully honored in implementation details.
- **Evidence:** `repo/docs/design.md:5`, `repo/backend/app/service/SearchService.php:113`, `repo/backend/app/service/ViolationService.php:345`, `repo/backend/app/service/ActivityService.php:281`, `repo/backend/database/seeds/DatabaseSeeder.php:240`

### 4.6 Aesthetics (frontend)

#### 4.6.1 Visual/interaction quality
- **Conclusion:** **Partial Pass**
- **Rationale:** UI includes role-based navigation, cards, spacing, badges, and interaction feedback. However, heavy inline monolithic markup and mixed style patterns reduce consistency/maintainability.
- **Evidence:** `repo/frontend/public/index.html:53`, `repo/frontend/public/index.html:114`, `repo/frontend/src/assets/css/app.css:36`, `repo/frontend/src/modules/activities.js:301`
- **Manual verification note:** Cross-device rendering quality and full interaction polish require browser verification.

## 5. Issues / Suggestions (Severity-Rated)

### Blocker / High

1) **Severity:** **High**  
   **Title:** Violation evidence validation can be bypassed in core violation creation flow  
   **Conclusion:** Fail  
   **Evidence:** `repo/backend/app/service/ViolationService.php:191`, `repo/backend/app/service/ViolationService.php:199`, `repo/backend/app/service/UploadService.php:27`, `repo/backend/app/service/UploadService.php:31`  
   **Impact:** Prompt requires JPG/PNG/PDF up to 10MB with validation and SHA-256 fingerprinting; current violation creation accepts arbitrary `filename/sha256/file_path` payload without enforcing upload constraints, allowing unvalidated evidence metadata.  
   **Minimum actionable fix:** In `ViolationService::createViolation`, accept only previously validated `file_uploads` IDs or invoke shared upload validation pipeline; reject raw evidence metadata payloads without server-side file/type/size checks.

2) **Severity:** **High**  
   **Title:** Group-level demerit aggregation alerts (25/50) are missing  
   **Conclusion:** Fail  
   **Evidence:** `repo/backend/app/service/ViolationService.php:228`, `repo/backend/app/service/ViolationService.php:240`, `repo/backend/app/service/ViolationService.php:345`, `repo/backend/app/service/ViolationService.php:367`  
   **Impact:** Prompt requires auto-aggregation for individuals **and groups** with threshold alerts; implementation only triggers per-user notifications.  
   **Minimum actionable fix:** Add persisted group-point aggregation and threshold evaluation after violation create/final decision; emit manager/admin notifications for group 25/50 thresholds.

3) **Severity:** **High**  
   **Title:** Logistics search does not implement required pinyin/spell-correction behavior  
   **Conclusion:** Fail  
   **Evidence:** `repo/backend/app/service/SearchService.php:113`, `repo/backend/app/service/SearchService.php:171`, `repo/backend/app/service/SearchService.php:176`, `repo/backend/app/service/SearchService.php:316`  
   **Impact:** Prompt explicitly requires logistics search with tokenization plus optional synonym and pinyin matching for names and basic spell correction; logistics path currently implements tokenization+synonyms only.  
   **Minimum actionable fix:** Extend `searchLogistics` to include pinyin-normalized matching and “did-you-mean” correction path (reuse existing pinyin/spell services).

4) **Severity:** **High**  
   **Title:** Activity signup status model is internally inconsistent and breaks lifecycle semantics  
   **Conclusion:** Fail  
   **Evidence:** `repo/backend/database/migrations/20260414100006_create_activity_signups_table.php:14`, `repo/backend/database/seeds/DatabaseSeeder.php:240`, `repo/backend/app/service/ActivityService.php:281`, `repo/backend/app/service/ActivityService.php:437`, `repo/backend/app/service/ActivityService.php:448`  
   **Impact:** Service logic relies on `confirmed/pending_acknowledgement` for headcount and version-ack flow, while schema/seed use `active`; this can undercount occupancy and skip pending-ack transitions for existing signups.  
   **Minimum actionable fix:** Standardize signup status enum across migration/seed/service (`confirmed`, `pending_acknowledgement`, `cancelled`), migrate old values, and enforce via validation/constants.

5) **Severity:** **High**  
   **Title:** Dashboard favorites implementation mismatches DB schema (likely broken feature)  
   **Conclusion:** Fail  
   **Evidence:** `repo/backend/app/service/DashboardService.php:80`, `repo/backend/app/service/DashboardService.php:87`, `repo/backend/database/migrations/20260414100025_create_dashboard_favorites_table.php:13`, `repo/backend/app/model/DashboardFavorite.php:13`  
   **Impact:** Service writes/queries `widget_id` but table/model define `dashboard_id`; favorites behavior is likely non-functional or silently wrong.  
   **Minimum actionable fix:** Align schema and service contract (either store dashboard IDs consistently or add `widget_id` column + migration + model updates + route contract update).

6) **Severity:** **High**  
   **Title:** Automated test suites are largely non-functional (framework/DB bootstrap missing)  
   **Conclusion:** Fail  
   **Evidence:** `repo/unit_tests/bootstrap.php:5`, `repo/unit_tests/bootstrap.php:18`, `repo/test-results/output.txt:6`, `repo/test-results/output.txt:13`, `repo/test-results/junit.xml:3`  
   **Impact:** Core quality claims cannot be validated; severe defects could pass undetected because most integration-like suites error before assertions.  
   **Minimum actionable fix:** Initialize ThinkPHP app/container + DB connection in test bootstrap, provide isolated test DB config, and ensure API/e2e suites run with deterministic fixtures.

### Medium / Low

7) **Severity:** **Medium**  
   **Title:** Change-log UX does not implement explicit highlighted diff behavior for users  
   **Conclusion:** Partial Fail  
   **Evidence:** `repo/backend/app/service/ActivityService.php:263`, `repo/frontend/src/modules/activities.js:392`, `repo/frontend/src/modules/activities.js:402`  
   **Impact:** Prompt asks for highlighted change log after published edits; current UI renders plain key/value transitions without clear visual emphasis rules.  
   **Minimum actionable fix:** Add field-level visual diff formatting (added/removed/changed highlight styles) and user-facing pending-ack banner flow.

8) **Severity:** **Medium**  
   **Title:** Some controller error mappings collapse authorization/resource distinctions  
   **Conclusion:** Partial Fail  
   **Evidence:** `repo/backend/app/controller/OrderController.php:85`, `repo/backend/app/controller/OrderController.php:89`, `repo/backend/app/controller/ShipmentController.php:103`, `repo/backend/app/controller/ShipmentController.php:107`  
   **Impact:** Returning fixed 404 on mixed errors reduces API correctness/diagnostics and can hide authorization semantics.  
   **Minimum actionable fix:** Preserve thrown status codes consistently (`$e->getCode()` with safe fallback) and standardize API error contract.

## 6. Security Review Summary

- **authentication entry points:** **Pass**  
  Evidence: `repo/backend/route/app.php:15`, `repo/backend/app/middleware/AuthMiddleware.php:19`, `repo/backend/app/service/AuthService.php:30`, `repo/backend/app/model/User.php:28`  
  Reasoning: bearer auth + lockout + salted hashing are implemented.

- **route-level authorization:** **Partial Pass**  
  Evidence: `repo/backend/route/app.php:24`, `repo/backend/route/app.php:315`, `repo/backend/app/middleware/RbacMiddleware.php:34`  
  Reasoning: route-level RBAC is broadly present; quality depends on correct permission modeling per route.

- **object-level authorization:** **Partial Pass**  
  Evidence: `repo/backend/app/service/OrderService.php:95`, `repo/backend/app/service/OrderService.php:112`, `repo/backend/app/service/ViolationService.php:154`, `repo/backend/app/service/ActivityService.php:469`  
  Reasoning: implemented in several services, but not uniformly strong for all business-sensitive reads/workflows.

- **function-level authorization:** **Partial Pass**  
  Evidence: `repo/backend/app/service/OrderService.php:294`, `repo/backend/app/service/OrderService.php:418`, `repo/backend/app/service/ViolationService.php:274`  
  Reasoning: defense-in-depth checks exist for critical actions, but some paths rely mainly on route permission assumptions.

- **tenant / user data isolation:** **Cannot Confirm Statistically**  
  Evidence: `repo/backend/app/service/OrderService.php:45`, `repo/backend/app/service/ViolationService.php:111`  
  Reasoning: no explicit multi-tenant model in code/docs; user-level isolation exists in parts, but no tenant boundary to evaluate.

- **admin / internal / debug endpoint protection:** **Pass**  
  Evidence: `repo/backend/route/app.php:237`, `repo/backend/route/app.php:241`, `repo/backend/route/app.php:243`  
  Reasoning: index maintenance routes are protected by `index.manage` permission and auth middleware.

## 7. Tests and Logging Review

- **Unit tests:** **Partial Pass**  
  Evidence: `repo/phpunit.xml:11`, `repo/unit_tests/services/AuthServiceTest.php:10`, `repo/unit_tests/services/OrderServiceTest.php:15`  
  Reasoning: test files exist with meaningful intent, but many require DB/app bootstrap that is currently broken.

- **API / integration tests:** **Fail**  
  Evidence: `repo/phpunit.xml:14`, `repo/API_tests/RbacApiTest.php:14`, `repo/test-results/junit.xml:3`, `repo/test-results/output.txt:57`  
  Reasoning: suites exist but are largely service-layer proxies and current execution artifacts show widespread runtime errors before assertions.

- **Logging categories / observability:** **Partial Pass**  
  Evidence: `repo/backend/config/log.php:5`, `repo/backend/app/service/AuthService.php:21`, `repo/backend/app/service/OrderService.php:477`, `repo/backend/app/middleware/RbacMiddleware.php:35`  
  Reasoning: structured file logging exists in key modules, but there is no strong event taxonomy/trace correlation beyond request headers.

- **Sensitive-data leakage risk in logs / responses:** **Partial Pass**  
  Evidence: `repo/backend/app/middleware/SensitiveDataMiddleware.php:11`, `repo/backend/app/service/AuthService.php:21`, `repo/backend/app/service/AuthService.php:39`  
  Reasoning: response masking exists on selected routes; however auth logs include usernames and detailed failure reasons, increasing identity enumeration/log exposure risk.

## 8. Test Coverage Assessment (Static Audit)

### 8.1 Test Overview
- Unit/API/e2e suites are declared in PHPUnit config.  
  Evidence: `repo/phpunit.xml:10`
- Framework: PHPUnit 10.  
  Evidence: `repo/backend/composer.json:15`, `repo/test-results/output.txt:1`
- Test entry points: `unit_tests`, `API_tests`, `e2e_tests`, invoked via `run_tests.sh`.  
  Evidence: `repo/phpunit.xml:11`, `repo/run_tests.sh:13`
- Test command is documented in README.  
  Evidence: `repo/README.md:51`
- Static artifact shows 60 tests, with severe errors (48 errors, 1 failure).  
  Evidence: `repo/test-results/junit.xml:3`

### 8.2 Coverage Mapping Table

| Requirement / Risk Point | Mapped Test Case(s) | Key Assertion / Fixture / Mock | Coverage Assessment | Gap | Minimum Test Addition |
|---|---|---|---|---|---|
| Auth lockout after 5 failures | `repo/API_tests/AuthApiTest.php:82`, `repo/e2e_tests/AuthFlowTest.php:88` | Exception code 429 expected | insufficient | Suites error on DB bootstrap before reliable flow checks | Add HTTP-level auth tests with initialized app + seeded DB |
| Token auth invalid token -> unauthenticated | `repo/API_tests/RbacApiTest.php:65` | `validateToken()` returns null | basically covered | Service-level only; no middleware/route 401 assertion | Add route-level 401 test through HTTP kernel |
| RBAC permission checks (403) | `repo/API_tests/RbacApiTest.php:38` | `hasPermission` true/false | insufficient | No real route guard assertions; not end-to-end middleware coverage | Add API tests asserting 403 on protected endpoints |
| Order object-level authorization | `repo/API_tests/OrderApiTest.php:67` | regular user list excludes foreign order IDs | insufficient | No runtime route assertions; suite currently errors | Add controller/API tests for `/orders/:id` 404/403 behavior |
| Activity versioning/list dedup | `repo/unit_tests/services/ActivityServiceTest.php:36` | one row per group, canonical published view | insufficient | Tests do not run successfully in recorded artifact | Fix bootstrap and add assertions for changelog + ack transitions |
| Order state-machine transitions | `repo/unit_tests/services/OrderServiceTest.php:36` | refund/cancel/close/cancelBySystem checks | insufficient | DB bootstrap failure blocks evidence | Add fully isolated DB fixture + transition matrix tests |
| Search SQL-safety and cleanup | `repo/unit_tests/services/SearchServiceTest.php:35` | injection payloads return arrays; cleanup behavior | insufficient | Runtime errors prevent meaningful assurance | Add passing tests with initialized ORM and fixture index data |
| Violation object-level authorization | `repo/unit_tests/services/ViolationServiceTest.php:50` | regular user restricted to own violations | insufficient | Runtime errors prevent verification | Add API tests for `/violations/:id` with role matrix |
| 404/403 distinction | No robust passing tests found | N/A | missing | Error-code mapping regressions can persist undetected | Add negative-path API assertions per endpoint family |
| Pagination/sorting/filtering correctness | Minimal in service tests only | N/A | insufficient | No comprehensive tests for sort/filter semantics from Prompt | Add targeted search/orders/activity pagination+sort tests |

### 8.3 Security Coverage Audit
- **authentication:** **Insufficient** — tests exist but currently fail widely before proving behavior. Evidence: `repo/API_tests/AuthApiTest.php:26`, `repo/test-results/junit.xml:3`.
- **route authorization:** **Missing/Insufficient** — no robust HTTP middleware-level permission tests. Evidence: `repo/API_tests/RbacApiTest.php:14`.
- **object-level authorization:** **Insufficient** — some service-layer tests exist, but failures and lack of route-level verification leave major risk. Evidence: `repo/API_tests/OrderApiTest.php:67`, `repo/test-results/output.txt:206`.
- **tenant/data isolation:** **Cannot Confirm** — no tenant-model tests or architecture. Evidence: `repo/phpunit.xml:10`.
- **admin/internal protection:** **Insufficient** — no explicit tests for `/index/*` and other internal endpoints with role constraints. Evidence: `repo/backend/route/app.php:237`.

### 8.4 Final Coverage Judgment
**Fail**

Major risks are only partially targeted by test files, and static artifacts show most suites erroring before meaningful assertions. This means severe defects in authz, workflow transitions, and Prompt-critical business rules could remain undetected while tests still appear present in repository structure.

## 9. Final Notes
- The codebase is substantial and close to product shape, but delivery acceptance fails on requirement-fit and verification confidence.
- Highest-priority remediation should focus on: (1) validation-integrity/security gaps, (2) business-rule consistency for activity/violation/search flows, and (3) making tests reliably executable and representative of route-level behavior.
