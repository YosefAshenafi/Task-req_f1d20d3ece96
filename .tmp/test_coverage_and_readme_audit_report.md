# Test Coverage Audit

## Project Type Detection
- Declared in README: **Fullstack web application** (`repo/README.md`).
- Inferred type (confirmed): **fullstack** (backend routes + frontend modules/tests present).

## Backend Endpoint Inventory
Total endpoints discovered from `repo/backend/route/app.php`: **110** unique `METHOD + PATH`.

```text
GET /api/v1/ping
POST /api/v1/auth/login
POST /api/v1/auth/logout
POST /api/v1/auth/unlock
GET /api/v1/users
POST /api/v1/users
PUT /api/v1/users/:id/role
PUT /api/v1/users/:id/password
GET /api/v1/users/:id
PUT /api/v1/users/:id
DELETE /api/v1/users/:id
GET /api/v1/activities/:activity_id/tasks
POST /api/v1/activities/:activity_id/tasks
GET /api/v1/activities/:activity_id/checklists
POST /api/v1/activities/:activity_id/checklists
GET /api/v1/activities/:activity_id/staffing
POST /api/v1/activities/:activity_id/staffing
GET /api/v1/activities
GET /api/v1/activities/:id/versions
GET /api/v1/activities/:id/signups
GET /api/v1/activities/:id/change-log
POST /api/v1/activities
PUT /api/v1/activities/:id
POST /api/v1/activities/:id/publish
POST /api/v1/activities/:id/start
POST /api/v1/activities/:id/complete
POST /api/v1/activities/:id/archive
POST /api/v1/activities/:id/signups/:signup_id/acknowledge
POST /api/v1/activities/:id/signups
DELETE /api/v1/activities/:id/signups/:signup_id
GET /api/v1/activities/:id
GET /api/v1/orders
GET /api/v1/orders/:id
GET /api/v1/orders/:id/history
POST /api/v1/orders
PUT /api/v1/orders/:id
POST /api/v1/orders/:id/initiate-payment
POST /api/v1/orders/:id/confirm-payment
POST /api/v1/orders/:id/start-ticketing
POST /api/v1/orders/:id/ticket
POST /api/v1/orders/:id/refund
POST /api/v1/orders/:id/cancel
POST /api/v1/orders/:id/close
PUT /api/v1/orders/:id/address
POST /api/v1/orders/:id/request-address-correction
POST /api/v1/orders/:id/approve-address-correction
GET /api/v1/orders/:order_id/shipments
POST /api/v1/orders/:order_id/shipments
GET /api/v1/shipments
POST /api/v1/shipments/:id/scan
GET /api/v1/shipments/:id/scan-history
POST /api/v1/shipments/:id/confirm-delivery
GET /api/v1/shipments/:id/exceptions
POST /api/v1/shipments/:id/exceptions
GET /api/v1/shipments/:id
GET /api/v1/violations/rules
GET /api/v1/violations/rules/:id
POST /api/v1/violations/rules
PUT /api/v1/violations/rules/:id
DELETE /api/v1/violations/rules/:id
GET /api/v1/violations
GET /api/v1/violations/:id
POST /api/v1/violations
GET /api/v1/violations/user/:user_id
GET /api/v1/violations/group/:group_id
POST /api/v1/violations/:id/appeal
POST /api/v1/violations/:id/review
POST /api/v1/violations/:id/final-decision
POST /api/v1/upload
GET /api/v1/upload/:id/download
GET /api/v1/upload/:id
DELETE /api/v1/upload/:id
PUT /api/v1/tasks/:id
PUT /api/v1/tasks/:id/status
DELETE /api/v1/tasks/:id
PUT /api/v1/checklists/:id
DELETE /api/v1/checklists/:id
POST /api/v1/checklists/:checklistId/items/:itemId/complete
PUT /api/v1/staffing/:id
DELETE /api/v1/staffing/:id
GET /api/v1/search
GET /api/v1/search/suggest
GET /api/v1/search/logistics
GET /api/v1/index/status
POST /api/v1/index/rebuild
POST /api/v1/index/cleanup
GET /api/v1/notifications
PUT /api/v1/notifications/:id/read
GET /api/v1/notifications/settings
PUT /api/v1/notifications/settings
GET /api/v1/preferences
PUT /api/v1/preferences
GET /api/v1/recommendations
GET /api/v1/recommendations/popular
GET /api/v1/recommendations/orders
GET /api/v1/dashboard
GET /api/v1/dashboard/custom
POST /api/v1/dashboard/custom
PUT /api/v1/dashboard/custom/:id
DELETE /api/v1/dashboard/custom
GET /api/v1/dashboard/favorites
POST /api/v1/dashboard/favorites
DELETE /api/v1/dashboard/favorites/:widget_id
GET /api/v1/dashboard/drill/:widget_id
GET /api/v1/dashboard/snapshot
GET /api/v1/export/orders
GET /api/v1/export/activities
GET /api/v1/export/violations
GET /api/v1/export/download
GET /api/v1/audit
```

## API Test Mapping Table
Source of truth: `repo/backend/route/app.php`; route exercise evidence from `repo/API_tests/HttpTestCase.php` and endpoint tests.

| Endpoint | Covered | Test Type | Test File | Evidence |
|---|---|---|---|---|
| `GET /api/v1/ping` | YES | true no-mock HTTP | `repo/API_tests/EndpointPingAuthTest.php#testPingReturns200WithoutAuth` | `repo/API_tests/EndpointPingAuthTest.php#testPingReturns200WithoutAuth` |
| `POST /api/v1/auth/login` | YES | true no-mock HTTP | `repo/API_tests/EndpointPingAuthTest.php#testLoginReturns200WithValidCredentials` | `repo/API_tests/EndpointPingAuthTest.php#testLoginReturns200WithValidCredentials` |
| `POST /api/v1/auth/logout` | YES | true no-mock HTTP | `repo/API_tests/EndpointPingAuthTest.php#testLogoutReturns200WhenAuthenticated` | `repo/API_tests/EndpointPingAuthTest.php#testLogoutReturns200WhenAuthenticated` |
| `POST /api/v1/auth/unlock` | YES | true no-mock HTTP | `repo/API_tests/EndpointPingAuthTest.php#testUnlockReturns401WhenUnauthenticated` | `repo/API_tests/EndpointPingAuthTest.php#testUnlockReturns401WhenUnauthenticated` |
| `GET /api/v1/users` | YES | true no-mock HTTP | `repo/API_tests/EndpointUserTest.php#testListUsersReturns401WhenUnauthenticated` | `repo/API_tests/EndpointUserTest.php#testListUsersReturns401WhenUnauthenticated` |
| `POST /api/v1/users` | YES | true no-mock HTTP | `repo/API_tests/EndpointUserTest.php#testCreateUserReturns401WhenUnauthenticated` | `repo/API_tests/EndpointUserTest.php#testCreateUserReturns401WhenUnauthenticated` |
| `PUT /api/v1/users/:id/role` | YES | true no-mock HTTP | `repo/API_tests/EndpointUserTest.php#testChangeRoleReturns401WhenUnauthenticated` | `repo/API_tests/EndpointUserTest.php#testChangeRoleReturns401WhenUnauthenticated` |
| `PUT /api/v1/users/:id/password` | YES | true no-mock HTTP | `repo/API_tests/EndpointUserTest.php#testResetPasswordReturns401WhenUnauthenticated` | `repo/API_tests/EndpointUserTest.php#testResetPasswordReturns401WhenUnauthenticated` |
| `GET /api/v1/users/:id` | YES | true no-mock HTTP | `repo/API_tests/EndpointUserTest.php#testGetUserReturns401WhenUnauthenticated` | `repo/API_tests/EndpointUserTest.php#testGetUserReturns401WhenUnauthenticated` |
| `PUT /api/v1/users/:id` | YES | true no-mock HTTP | `repo/API_tests/EndpointUserTest.php#testUpdateUserReturns401WhenUnauthenticated` | `repo/API_tests/EndpointUserTest.php#testUpdateUserReturns401WhenUnauthenticated` |
| `DELETE /api/v1/users/:id` | YES | true no-mock HTTP | `repo/API_tests/EndpointUserTest.php#testDeleteUserReturns401WhenUnauthenticated` | `repo/API_tests/EndpointUserTest.php#testDeleteUserReturns401WhenUnauthenticated` |
| `GET /api/v1/activities/:activity_id/tasks` | YES | true no-mock HTTP | `repo/API_tests/EndpointTaskChecklistStaffingTest.php#testTaskIndexUnauthorized` | `repo/API_tests/EndpointTaskChecklistStaffingTest.php#testTaskIndexUnauthorized` |
| `POST /api/v1/activities/:activity_id/tasks` | YES | true no-mock HTTP | `repo/API_tests/EndpointTaskChecklistStaffingTest.php#testTaskCreateUnauthorized` | `repo/API_tests/EndpointTaskChecklistStaffingTest.php#testTaskCreateUnauthorized` |
| `GET /api/v1/activities/:activity_id/checklists` | YES | true no-mock HTTP | `repo/API_tests/EndpointTaskChecklistStaffingTest.php#testChecklistIndexUnauthorized` | `repo/API_tests/EndpointTaskChecklistStaffingTest.php#testChecklistIndexUnauthorized` |
| `POST /api/v1/activities/:activity_id/checklists` | YES | true no-mock HTTP | `repo/API_tests/EndpointTaskChecklistStaffingTest.php#testChecklistCreateUnauthorized` | `repo/API_tests/EndpointTaskChecklistStaffingTest.php#testChecklistCreateUnauthorized` |
| `GET /api/v1/activities/:activity_id/staffing` | YES | true no-mock HTTP | `repo/API_tests/EndpointTaskChecklistStaffingTest.php#testStaffingIndexUnauthorized` | `repo/API_tests/EndpointTaskChecklistStaffingTest.php#testStaffingIndexUnauthorized` |
| `POST /api/v1/activities/:activity_id/staffing` | YES | true no-mock HTTP | `repo/API_tests/EndpointTaskChecklistStaffingTest.php#testStaffingCreateUnauthorized` | `repo/API_tests/EndpointTaskChecklistStaffingTest.php#testStaffingCreateUnauthorized` |
| `GET /api/v1/activities` | YES | true no-mock HTTP | `repo/API_tests/EndpointActivityTest.php#testListActivitiesReturns401WhenUnauthenticated` | `repo/API_tests/EndpointActivityTest.php#testListActivitiesReturns401WhenUnauthenticated` |
| `GET /api/v1/activities/:id/versions` | YES | true no-mock HTTP | `repo/API_tests/EndpointActivityTest.php#testGetVersionsReturns200ForAdmin` | `repo/API_tests/EndpointActivityTest.php#testGetVersionsReturns200ForAdmin` |
| `GET /api/v1/activities/:id/signups` | YES | true no-mock HTTP | `repo/API_tests/EndpointActivityTest.php#testGetSignupsReturns200ForAdmin` | `repo/API_tests/EndpointActivityTest.php#testGetSignupsReturns200ForAdmin` |
| `GET /api/v1/activities/:id/change-log` | YES | true no-mock HTTP | `repo/API_tests/EndpointActivityTest.php#testGetChangeLogReturns200ForAdmin` | `repo/API_tests/EndpointActivityTest.php#testGetChangeLogReturns200ForAdmin` |
| `POST /api/v1/activities` | YES | true no-mock HTTP | `repo/API_tests/EndpointActivityTest.php#testCreateActivityReturns401WhenUnauthenticated` | `repo/API_tests/EndpointActivityTest.php#testCreateActivityReturns401WhenUnauthenticated` |
| `PUT /api/v1/activities/:id` | YES | true no-mock HTTP | `repo/API_tests/EndpointActivityTest.php#testUpdateActivityReturns401WhenUnauthenticated` | `repo/API_tests/EndpointActivityTest.php#testUpdateActivityReturns401WhenUnauthenticated` |
| `POST /api/v1/activities/:id/publish` | YES | true no-mock HTTP | `repo/API_tests/EndpointActivityExtTest.php#testPublishActivitySuccessForAdmin` | `repo/API_tests/EndpointActivityExtTest.php#testPublishActivitySuccessForAdmin` |
| `POST /api/v1/activities/:id/start` | YES | true no-mock HTTP | `repo/API_tests/EndpointActivityExtTest.php#testStartActivityReturns401WhenUnauthenticated` | `repo/API_tests/EndpointActivityExtTest.php#testStartActivityReturns401WhenUnauthenticated` |
| `POST /api/v1/activities/:id/complete` | YES | true no-mock HTTP | `repo/API_tests/EndpointActivityExtTest.php#testCompleteActivityReturns401WhenUnauthenticated` | `repo/API_tests/EndpointActivityExtTest.php#testCompleteActivityReturns401WhenUnauthenticated` |
| `POST /api/v1/activities/:id/archive` | YES | true no-mock HTTP | `repo/API_tests/EndpointActivityExtTest.php#testArchiveActivityReturns401WhenUnauthenticated` | `repo/API_tests/EndpointActivityExtTest.php#testArchiveActivityReturns401WhenUnauthenticated` |
| `POST /api/v1/activities/:id/signups/:signup_id/acknowledge` | YES | true no-mock HTTP | `repo/API_tests/EndpointActivityExtTest.php#testAcknowledgeSignupReturns401WhenUnauthenticated` | `repo/API_tests/EndpointActivityExtTest.php#testAcknowledgeSignupReturns401WhenUnauthenticated` |
| `POST /api/v1/activities/:id/signups` | YES | true no-mock HTTP | `repo/API_tests/EndpointActivityExtTest.php#testSignupActivityReturns401WhenUnauthenticated` | `repo/API_tests/EndpointActivityExtTest.php#testSignupActivityReturns401WhenUnauthenticated` |
| `DELETE /api/v1/activities/:id/signups/:signup_id` | YES | true no-mock HTTP | `repo/API_tests/EndpointActivityExtTest.php#testCancelSignupReturns401WhenUnauthenticated` | `repo/API_tests/EndpointActivityExtTest.php#testCancelSignupReturns401WhenUnauthenticated` |
| `GET /api/v1/activities/:id` | YES | true no-mock HTTP | `repo/API_tests/EndpointActivityTest.php#testGetActivityReturns401WhenUnauthenticated` | `repo/API_tests/EndpointActivityTest.php#testGetActivityReturns401WhenUnauthenticated` |
| `GET /api/v1/orders` | YES | true no-mock HTTP | `repo/API_tests/EndpointOrderTest.php#testListOrdersReturns401WhenUnauthenticated` | `repo/API_tests/EndpointOrderTest.php#testListOrdersReturns401WhenUnauthenticated` |
| `GET /api/v1/orders/:id` | YES | true no-mock HTTP | `repo/API_tests/EndpointOrderTest.php#testGetOrderReturns401WhenUnauthenticated` | `repo/API_tests/EndpointOrderTest.php#testGetOrderReturns401WhenUnauthenticated` |
| `GET /api/v1/orders/:id/history` | YES | true no-mock HTTP | `repo/API_tests/EndpointOrderTest.php#testGetOrderHistoryReturns200ForAdmin` | `repo/API_tests/EndpointOrderTest.php#testGetOrderHistoryReturns200ForAdmin` |
| `POST /api/v1/orders` | YES | true no-mock HTTP | `repo/API_tests/EndpointOrderTest.php#testCreateOrderReturns401WhenUnauthenticated` | `repo/API_tests/EndpointOrderTest.php#testCreateOrderReturns401WhenUnauthenticated` |
| `PUT /api/v1/orders/:id` | YES | true no-mock HTTP | `repo/API_tests/EndpointOrderTest.php#testUpdateOrderReturns401WhenUnauthenticated` | `repo/API_tests/EndpointOrderTest.php#testUpdateOrderReturns401WhenUnauthenticated` |
| `POST /api/v1/orders/:id/initiate-payment` | YES | true no-mock HTTP | `repo/API_tests/EndpointOrderTransitionTest.php#testInitiatePaymentReturns401WhenUnauthenticated` | `repo/API_tests/EndpointOrderTransitionTest.php#testInitiatePaymentReturns401WhenUnauthenticated` |
| `POST /api/v1/orders/:id/confirm-payment` | YES | true no-mock HTTP | `repo/API_tests/EndpointOrderTransitionTest.php#testConfirmPaymentReturns401WhenUnauthenticated` | `repo/API_tests/EndpointOrderTransitionTest.php#testConfirmPaymentReturns401WhenUnauthenticated` |
| `POST /api/v1/orders/:id/start-ticketing` | YES | true no-mock HTTP | `repo/API_tests/EndpointOrderTransitionTest.php#testStartTicketingReturns401WhenUnauthenticated` | `repo/API_tests/EndpointOrderTransitionTest.php#testStartTicketingReturns401WhenUnauthenticated` |
| `POST /api/v1/orders/:id/ticket` | YES | true no-mock HTTP | `repo/API_tests/EndpointOrderTransitionTest.php#testTicketReturns401WhenUnauthenticated` | `repo/API_tests/EndpointOrderTransitionTest.php#testTicketReturns401WhenUnauthenticated` |
| `POST /api/v1/orders/:id/refund` | YES | true no-mock HTTP | `repo/API_tests/EndpointOrderTransitionTest.php#testRefundReturns401WhenUnauthenticated` | `repo/API_tests/EndpointOrderTransitionTest.php#testRefundReturns401WhenUnauthenticated` |
| `POST /api/v1/orders/:id/cancel` | YES | true no-mock HTTP | `repo/API_tests/EndpointOrderTest.php#testCancelOrderReturns401WhenUnauthenticated` | `repo/API_tests/EndpointOrderTest.php#testCancelOrderReturns401WhenUnauthenticated` |
| `POST /api/v1/orders/:id/close` | YES | true no-mock HTTP | `repo/API_tests/EndpointOrderTransitionTest.php#testCloseReturns401WhenUnauthenticated` | `repo/API_tests/EndpointOrderTransitionTest.php#testCloseReturns401WhenUnauthenticated` |
| `PUT /api/v1/orders/:id/address` | YES | true no-mock HTTP | `repo/API_tests/EndpointOrderTest.php#testUpdateAddressReturns401WhenUnauthenticated` | `repo/API_tests/EndpointOrderTest.php#testUpdateAddressReturns401WhenUnauthenticated` |
| `POST /api/v1/orders/:id/request-address-correction` | YES | true no-mock HTTP | `repo/API_tests/EndpointOrderTransitionTest.php#testRequestAddressCorrectionReturns401WhenUnauthenticated` | `repo/API_tests/EndpointOrderTransitionTest.php#testRequestAddressCorrectionReturns401WhenUnauthenticated` |
| `POST /api/v1/orders/:id/approve-address-correction` | YES | true no-mock HTTP | `repo/API_tests/EndpointOrderTransitionTest.php#testApproveAddressCorrectionReturns401WhenUnauthenticated` | `repo/API_tests/EndpointOrderTransitionTest.php#testApproveAddressCorrectionReturns401WhenUnauthenticated` |
| `GET /api/v1/orders/:order_id/shipments` | YES | true no-mock HTTP | `repo/API_tests/EndpointShipmentUploadTest.php#testListOrderShipmentsReturns401WhenUnauthenticated` | `repo/API_tests/EndpointShipmentUploadTest.php#testListOrderShipmentsReturns401WhenUnauthenticated` |
| `POST /api/v1/orders/:order_id/shipments` | YES | true no-mock HTTP | `repo/API_tests/EndpointShipmentUploadTest.php#testCreateShipmentReturns401WhenUnauthenticated` | `repo/API_tests/EndpointShipmentUploadTest.php#testCreateShipmentReturns401WhenUnauthenticated` |
| `GET /api/v1/shipments` | YES | true no-mock HTTP | `repo/API_tests/EndpointShipmentUploadTest.php#testListShipmentsReturns401WhenUnauthenticated` | `repo/API_tests/EndpointShipmentUploadTest.php#testListShipmentsReturns401WhenUnauthenticated` |
| `POST /api/v1/shipments/:id/scan` | YES | true no-mock HTTP | `repo/API_tests/EndpointShipmentUploadTest.php#testScanShipmentReturns401WhenUnauthenticated` | `repo/API_tests/EndpointShipmentUploadTest.php#testScanShipmentReturns401WhenUnauthenticated` |
| `GET /api/v1/shipments/:id/scan-history` | YES | true no-mock HTTP | `repo/API_tests/EndpointShipmentUploadTest.php#testGetScanHistoryReturns401WhenUnauthenticated` | `repo/API_tests/EndpointShipmentUploadTest.php#testGetScanHistoryReturns401WhenUnauthenticated` |
| `POST /api/v1/shipments/:id/confirm-delivery` | YES | true no-mock HTTP | `repo/API_tests/EndpointShipmentUploadTest.php#testConfirmDeliveryReturns401WhenUnauthenticated` | `repo/API_tests/EndpointShipmentUploadTest.php#testConfirmDeliveryReturns401WhenUnauthenticated` |
| `GET /api/v1/shipments/:id/exceptions` | YES | true no-mock HTTP | `repo/API_tests/EndpointShipmentUploadTest.php#testGetExceptionsReturns401WhenUnauthenticated` | `repo/API_tests/EndpointShipmentUploadTest.php#testGetExceptionsReturns401WhenUnauthenticated` |
| `POST /api/v1/shipments/:id/exceptions` | YES | true no-mock HTTP | `repo/API_tests/EndpointShipmentUploadTest.php#testReportExceptionReturns401WhenUnauthenticated` | `repo/API_tests/EndpointShipmentUploadTest.php#testReportExceptionReturns401WhenUnauthenticated` |
| `GET /api/v1/shipments/:id` | YES | true no-mock HTTP | `repo/API_tests/EndpointShipmentUploadTest.php#testGetShipmentReturns401WhenUnauthenticated` | `repo/API_tests/EndpointShipmentUploadTest.php#testGetShipmentReturns401WhenUnauthenticated` |
| `GET /api/v1/violations/rules` | YES | true no-mock HTTP | `repo/API_tests/EndpointViolationTest.php#testListRulesReturns401WhenUnauthenticated` | `repo/API_tests/EndpointViolationTest.php#testListRulesReturns401WhenUnauthenticated` |
| `GET /api/v1/violations/rules/:id` | YES | true no-mock HTTP | `repo/API_tests/EndpointViolationExtTest.php#testGetRuleReturns401WhenUnauthenticated` | `repo/API_tests/EndpointViolationExtTest.php#testGetRuleReturns401WhenUnauthenticated` |
| `POST /api/v1/violations/rules` | YES | true no-mock HTTP | `repo/API_tests/EndpointViolationTest.php#testCreateRuleReturns401WhenUnauthenticated` | `repo/API_tests/EndpointViolationTest.php#testCreateRuleReturns401WhenUnauthenticated` |
| `PUT /api/v1/violations/rules/:id` | YES | true no-mock HTTP | `repo/API_tests/EndpointViolationExtTest.php#testUpdateRuleReturns401WhenUnauthenticated` | `repo/API_tests/EndpointViolationExtTest.php#testUpdateRuleReturns401WhenUnauthenticated` |
| `DELETE /api/v1/violations/rules/:id` | YES | true no-mock HTTP | `repo/API_tests/EndpointViolationExtTest.php#testDeleteRuleReturns401WhenUnauthenticated` | `repo/API_tests/EndpointViolationExtTest.php#testDeleteRuleReturns401WhenUnauthenticated` |
| `GET /api/v1/violations` | YES | true no-mock HTTP | `repo/API_tests/EndpointViolationTest.php#testListViolationsReturns401WhenUnauthenticated` | `repo/API_tests/EndpointViolationTest.php#testListViolationsReturns401WhenUnauthenticated` |
| `GET /api/v1/violations/:id` | YES | true no-mock HTTP | `repo/API_tests/EndpointViolationExtTest.php#testGetRuleReturns401WhenUnauthenticated` | `repo/API_tests/EndpointViolationExtTest.php#testGetRuleReturns401WhenUnauthenticated` |
| `POST /api/v1/violations` | YES | true no-mock HTTP | `repo/API_tests/EndpointViolationTest.php#testCreateViolationReturns401WhenUnauthenticated` | `repo/API_tests/EndpointViolationTest.php#testCreateViolationReturns401WhenUnauthenticated` |
| `GET /api/v1/violations/user/:user_id` | YES | true no-mock HTTP | `repo/API_tests/EndpointViolationExtTest.php#testGetViolationsByUserReturns401WhenUnauthenticated` | `repo/API_tests/EndpointViolationExtTest.php#testGetViolationsByUserReturns401WhenUnauthenticated` |
| `GET /api/v1/violations/group/:group_id` | YES | true no-mock HTTP | `repo/API_tests/EndpointViolationExtTest.php#testGetViolationsByGroupReturns401WhenUnauthenticated` | `repo/API_tests/EndpointViolationExtTest.php#testGetViolationsByGroupReturns401WhenUnauthenticated` |
| `POST /api/v1/violations/:id/appeal` | YES | true no-mock HTTP | `repo/API_tests/EndpointViolationTest.php#testAppealViolationReturns401WhenUnauthenticated` | `repo/API_tests/EndpointViolationTest.php#testAppealViolationReturns401WhenUnauthenticated` |
| `POST /api/v1/violations/:id/review` | YES | true no-mock HTTP | `repo/API_tests/EndpointViolationTest.php#testReviewViolationReturns401WhenUnauthenticated` | `repo/API_tests/EndpointViolationTest.php#testReviewViolationReturns401WhenUnauthenticated` |
| `POST /api/v1/violations/:id/final-decision` | YES | true no-mock HTTP | `repo/API_tests/EndpointViolationExtTest.php#testFinalDecisionReturns401WhenUnauthenticated` | `repo/API_tests/EndpointViolationExtTest.php#testFinalDecisionReturns401WhenUnauthenticated` |
| `POST /api/v1/upload` | YES | true no-mock HTTP | `repo/API_tests/EndpointUploadExtTest.php#testUploadReturns401WhenUnauthenticated` | `repo/API_tests/EndpointUploadExtTest.php#testUploadReturns401WhenUnauthenticated` |
| `GET /api/v1/upload/:id/download` | YES | true no-mock HTTP | `repo/API_tests/EndpointUploadExtTest.php#testUploadDownloadReturns401WhenUnauthenticated` | `repo/API_tests/EndpointUploadExtTest.php#testUploadDownloadReturns401WhenUnauthenticated` |
| `GET /api/v1/upload/:id` | YES | true no-mock HTTP | `repo/API_tests/EndpointShipmentUploadTest.php#testGetFileReturns401WhenUnauthenticated` | `repo/API_tests/EndpointShipmentUploadTest.php#testGetFileReturns401WhenUnauthenticated` |
| `DELETE /api/v1/upload/:id` | YES | true no-mock HTTP | `repo/API_tests/EndpointShipmentUploadTest.php#testDeleteFileReturns401WhenUnauthenticated` | `repo/API_tests/EndpointShipmentUploadTest.php#testDeleteFileReturns401WhenUnauthenticated` |
| `PUT /api/v1/tasks/:id` | YES | true no-mock HTTP | `repo/API_tests/EndpointTaskChecklistStaffingTest.php#testTaskUpdateUnauthorized` | `repo/API_tests/EndpointTaskChecklistStaffingTest.php#testTaskUpdateUnauthorized` |
| `PUT /api/v1/tasks/:id/status` | YES | true no-mock HTTP | `repo/API_tests/EndpointTaskChecklistStaffingTest.php#testTaskUpdateStatusUnauthorized` | `repo/API_tests/EndpointTaskChecklistStaffingTest.php#testTaskUpdateStatusUnauthorized` |
| `DELETE /api/v1/tasks/:id` | YES | true no-mock HTTP | `repo/API_tests/EndpointTaskChecklistStaffingTest.php#testTaskDeleteUnauthorized` | `repo/API_tests/EndpointTaskChecklistStaffingTest.php#testTaskDeleteUnauthorized` |
| `PUT /api/v1/checklists/:id` | YES | true no-mock HTTP | `repo/API_tests/EndpointTaskChecklistStaffingTest.php#testChecklistUpdateUnauthorized` | `repo/API_tests/EndpointTaskChecklistStaffingTest.php#testChecklistUpdateUnauthorized` |
| `DELETE /api/v1/checklists/:id` | YES | true no-mock HTTP | `repo/API_tests/EndpointTaskChecklistStaffingTest.php#testChecklistDeleteUnauthorized` | `repo/API_tests/EndpointTaskChecklistStaffingTest.php#testChecklistDeleteUnauthorized` |
| `POST /api/v1/checklists/:checklistId/items/:itemId/complete` | YES | true no-mock HTTP | `repo/API_tests/EndpointTaskChecklistStaffingTest.php#testChecklistCompleteItemUnauthorized` | `repo/API_tests/EndpointTaskChecklistStaffingTest.php#testChecklistCompleteItemUnauthorized` |
| `PUT /api/v1/staffing/:id` | YES | true no-mock HTTP | `repo/API_tests/EndpointTaskChecklistStaffingTest.php#testStaffingUpdateUnauthorized` | `repo/API_tests/EndpointTaskChecklistStaffingTest.php#testStaffingUpdateUnauthorized` |
| `DELETE /api/v1/staffing/:id` | YES | true no-mock HTTP | `repo/API_tests/EndpointTaskChecklistStaffingTest.php#testStaffingDeleteUnauthorized` | `repo/API_tests/EndpointTaskChecklistStaffingTest.php#testStaffingDeleteUnauthorized` |
| `GET /api/v1/search` | YES | true no-mock HTTP | `repo/API_tests/EndpointMiscTest.php#testSearchReturns401WhenUnauthenticated` | `repo/API_tests/EndpointMiscTest.php#testSearchReturns401WhenUnauthenticated` |
| `GET /api/v1/search/suggest` | YES | true no-mock HTTP | `repo/API_tests/EndpointSearchIndexExportTest.php#testSearchSuggestReturns401WhenUnauthenticated` | `repo/API_tests/EndpointSearchIndexExportTest.php#testSearchSuggestReturns401WhenUnauthenticated` |
| `GET /api/v1/search/logistics` | YES | true no-mock HTTP | `repo/API_tests/EndpointSearchIndexExportTest.php#testSearchLogisticsReturns401WhenUnauthenticated` | `repo/API_tests/EndpointSearchIndexExportTest.php#testSearchLogisticsReturns401WhenUnauthenticated` |
| `GET /api/v1/index/status` | YES | true no-mock HTTP | `repo/API_tests/EndpointSearchIndexExportTest.php#testIndexStatusReturns401WhenUnauthenticated` | `repo/API_tests/EndpointSearchIndexExportTest.php#testIndexStatusReturns401WhenUnauthenticated` |
| `POST /api/v1/index/rebuild` | YES | true no-mock HTTP | `repo/API_tests/EndpointSearchIndexExportTest.php#testIndexRebuildReturns401WhenUnauthenticated` | `repo/API_tests/EndpointSearchIndexExportTest.php#testIndexRebuildReturns401WhenUnauthenticated` |
| `POST /api/v1/index/cleanup` | YES | true no-mock HTTP | `repo/API_tests/EndpointSearchIndexExportTest.php#testIndexCleanupReturns401WhenUnauthenticated` | `repo/API_tests/EndpointSearchIndexExportTest.php#testIndexCleanupReturns401WhenUnauthenticated` |
| `GET /api/v1/notifications` | YES | true no-mock HTTP | `repo/API_tests/EndpointMiscTest.php#testGetNotificationsReturns401WhenUnauthenticated` | `repo/API_tests/EndpointMiscTest.php#testGetNotificationsReturns401WhenUnauthenticated` |
| `PUT /api/v1/notifications/:id/read` | YES | true no-mock HTTP | `repo/API_tests/EndpointUploadExtTest.php#testMarkNotificationReadReturns401WhenUnauthenticated` | `repo/API_tests/EndpointUploadExtTest.php#testMarkNotificationReadReturns401WhenUnauthenticated` |
| `GET /api/v1/notifications/settings` | YES | true no-mock HTTP | `repo/API_tests/EndpointMiscTest.php#testGetNotificationSettingsReturns401WhenUnauthenticated` | `repo/API_tests/EndpointMiscTest.php#testGetNotificationSettingsReturns401WhenUnauthenticated` |
| `PUT /api/v1/notifications/settings` | YES | true no-mock HTTP | `repo/API_tests/EndpointMiscTest.php#testUpdateNotificationSettingsReturns401WhenUnauthenticated` | `repo/API_tests/EndpointMiscTest.php#testUpdateNotificationSettingsReturns401WhenUnauthenticated` |
| `GET /api/v1/preferences` | YES | true no-mock HTTP | `repo/API_tests/EndpointMiscTest.php#testGetPreferencesReturns401WhenUnauthenticated` | `repo/API_tests/EndpointMiscTest.php#testGetPreferencesReturns401WhenUnauthenticated` |
| `PUT /api/v1/preferences` | YES | true no-mock HTTP | `repo/API_tests/EndpointMiscTest.php#testUpdatePreferencesReturns401WhenUnauthenticated` | `repo/API_tests/EndpointMiscTest.php#testUpdatePreferencesReturns401WhenUnauthenticated` |
| `GET /api/v1/recommendations` | YES | true no-mock HTTP | `repo/API_tests/EndpointMiscTest.php#testGetRecommendationsReturns401WhenUnauthenticated` | `repo/API_tests/EndpointMiscTest.php#testGetRecommendationsReturns401WhenUnauthenticated` |
| `GET /api/v1/recommendations/popular` | YES | true no-mock HTTP | `repo/API_tests/EndpointMiscTest.php#testGetPopularReturns401WhenUnauthenticated` | `repo/API_tests/EndpointMiscTest.php#testGetPopularReturns401WhenUnauthenticated` |
| `GET /api/v1/recommendations/orders` | YES | true no-mock HTTP | `repo/API_tests/EndpointMiscTest.php#testGetOrderRecommendationsReturns401WhenUnauthenticated` | `repo/API_tests/EndpointMiscTest.php#testGetOrderRecommendationsReturns401WhenUnauthenticated` |
| `GET /api/v1/dashboard` | YES | true no-mock HTTP | `repo/API_tests/EndpointMiscTest.php#testGetDashboardReturns401WhenUnauthenticated` | `repo/API_tests/EndpointMiscTest.php#testGetDashboardReturns401WhenUnauthenticated` |
| `GET /api/v1/dashboard/custom` | YES | true no-mock HTTP | `repo/API_tests/EndpointDashboardExtTest.php#testGetCustomDashboardReturns401WhenUnauthenticated` | `repo/API_tests/EndpointDashboardExtTest.php#testGetCustomDashboardReturns401WhenUnauthenticated` |
| `POST /api/v1/dashboard/custom` | YES | true no-mock HTTP | `repo/API_tests/EndpointDashboardExtTest.php#testCreateCustomDashboardReturns401WhenUnauthenticated` | `repo/API_tests/EndpointDashboardExtTest.php#testCreateCustomDashboardReturns401WhenUnauthenticated` |
| `PUT /api/v1/dashboard/custom/:id` | YES | true no-mock HTTP | `repo/API_tests/EndpointDashboardExtTest.php#testUpdateCustomDashboardReturns401WhenUnauthenticated` | `repo/API_tests/EndpointDashboardExtTest.php#testUpdateCustomDashboardReturns401WhenUnauthenticated` |
| `DELETE /api/v1/dashboard/custom` | YES | true no-mock HTTP | `repo/API_tests/EndpointDashboardExtTest.php#testDeleteCustomDashboardReturns401WhenUnauthenticated` | `repo/API_tests/EndpointDashboardExtTest.php#testDeleteCustomDashboardReturns401WhenUnauthenticated` |
| `GET /api/v1/dashboard/favorites` | YES | true no-mock HTTP | `repo/API_tests/EndpointMiscTest.php#testGetDashboardFavoritesReturns401WhenUnauthenticated` | `repo/API_tests/EndpointMiscTest.php#testGetDashboardFavoritesReturns401WhenUnauthenticated` |
| `POST /api/v1/dashboard/favorites` | YES | true no-mock HTTP | `repo/API_tests/EndpointDashboardExtTest.php#testAddFavoriteReturns401WhenUnauthenticated` | `repo/API_tests/EndpointDashboardExtTest.php#testAddFavoriteReturns401WhenUnauthenticated` |
| `DELETE /api/v1/dashboard/favorites/:widget_id` | YES | true no-mock HTTP | `repo/API_tests/EndpointDashboardExtTest.php#testRemoveFavoriteReturns401WhenUnauthenticated` | `repo/API_tests/EndpointDashboardExtTest.php#testRemoveFavoriteReturns401WhenUnauthenticated` |
| `GET /api/v1/dashboard/drill/:widget_id` | YES | true no-mock HTTP | `repo/API_tests/EndpointDashboardExtTest.php#testDrillReturns401WhenUnauthenticated` | `repo/API_tests/EndpointDashboardExtTest.php#testDrillReturns401WhenUnauthenticated` |
| `GET /api/v1/dashboard/snapshot` | YES | true no-mock HTTP | `repo/API_tests/EndpointDashboardExtTest.php#testSnapshotReturns401WhenUnauthenticated` | `repo/API_tests/EndpointDashboardExtTest.php#testSnapshotReturns401WhenUnauthenticated` |
| `GET /api/v1/export/orders` | YES | true no-mock HTTP | `repo/API_tests/EndpointMiscTest.php#testExportOrdersReturns401WhenUnauthenticated` | `repo/API_tests/EndpointMiscTest.php#testExportOrdersReturns401WhenUnauthenticated` |
| `GET /api/v1/export/activities` | YES | true no-mock HTTP | `repo/API_tests/EndpointSearchIndexExportTest.php#testExportActivitiesReturns401WhenUnauthenticated` | `repo/API_tests/EndpointSearchIndexExportTest.php#testExportActivitiesReturns401WhenUnauthenticated` |
| `GET /api/v1/export/violations` | YES | true no-mock HTTP | `repo/API_tests/EndpointSearchIndexExportTest.php#testExportViolationsReturns401WhenUnauthenticated` | `repo/API_tests/EndpointSearchIndexExportTest.php#testExportViolationsReturns401WhenUnauthenticated` |
| `GET /api/v1/export/download` | YES | true no-mock HTTP | `repo/API_tests/EndpointSearchIndexExportTest.php#testExportDownloadReturns401WhenUnauthenticated` | `repo/API_tests/EndpointSearchIndexExportTest.php#testExportDownloadReturns401WhenUnauthenticated` |
| `GET /api/v1/audit` | YES | true no-mock HTTP | `repo/API_tests/EndpointMiscTest.php#testGetAuditReturns401WhenUnauthenticated` | `repo/API_tests/EndpointMiscTest.php#testGetAuditReturns401WhenUnauthenticated` |

## API Test Classification
1. **True No-Mock HTTP** (14 files):
   - `EndpointPingAuthTest.php`, `EndpointUserTest.php`, `EndpointActivityTest.php`, `EndpointActivityExtTest.php`, `EndpointOrderTest.php`, `EndpointOrderTransitionTest.php`, `EndpointShipmentUploadTest.php`, `EndpointUploadExtTest.php`, `EndpointViolationTest.php`, `EndpointViolationExtTest.php`, `EndpointTaskChecklistStaffingTest.php`, `EndpointSearchIndexExportTest.php`, `EndpointMiscTest.php`, `EndpointDashboardExtTest.php`
   - Evidence: all extend `HttpTestCase`; `HttpTestCase::request()` dispatches via `self::$app->http->run($req)` through routing + middleware in `repo/API_tests/HttpTestCase.php`.
2. **HTTP with Mocking**: **None detected**.
3. **Non-HTTP (unit/integration without HTTP)**:
   - `AuthApiTest.php`, `OrderApiTest.php`, `RbacApiTest.php`, `ObjectAuthTest.php`, `HttpMiddlewareTest.php` (service/middleware direct invocation).

## Mock Detection
- Static scan for `jest.mock`, `vi.mock`, `sinon.stub`, `Mockery`, `shouldReceive` across backend API/unit test directories: **no mock/stub directives detected**.
- Note: frontend Jest tests use `jest.fn()` stubs for frontend module isolation; this does not affect API true-no-mock classification.

## Coverage Summary
- Total endpoints: **110**
- Endpoints with HTTP tests: **110**
- Endpoints with TRUE no-mock HTTP tests: **110**
- HTTP coverage: **100.0%**
- True API coverage: **100.0%**

## Unit Test Summary
### Backend Unit Tests
- Present under `repo/unit_tests/`, `repo/backend/tests/`, and non-HTTP API suites in `repo/API_tests/`.
- Covered module categories:
  - services: Auth, User, Activity, Order, Shipment, Violation, Search, Export, Dashboard, Notification, Upload, Checklist, Task, Staffing, Recommendation, Audit
  - middleware: RateLimit, SensitiveData, Auth, RBAC
  - validation: UserValidate, OrderValidate, ActivityValidate
  - auth flow: backend and e2e auth lifecycle tests
- Important backend modules NOT directly unit-tested: `SpellCorrectionService`, `PinyinService`, `EncryptionService` (`repo/backend/app/service`).

### Frontend Unit Tests
- Frontend test files: present in `repo/frontend/__tests__/*.test.js` (17 files).
- Framework/tooling detected: Jest + jsdom (`repo/frontend/package.json`).
- Modules covered (direct imports): `activities`, `audit`, `checklists`, `common`, `dashboard`, `nav`, `notifications`, `orders`, `polish`, `recommendations`, `search`, `shipments`, `staffing`, `tasks`, `upload`, `users`, `violations`.
- Important frontend modules/components not tested: `src/config.js`, HTML views in `src/views/**`, end-to-end UI flows in browser context.
- **Frontend unit tests: PRESENT**

### Cross-Layer Observation
- Both backend and frontend unit layers are covered.
- Gap remains at true end-to-end browser-to-backend integration level (no real FE↔BE flow test found).

## API Observability Check
- Strong: endpoint method/path is explicit in most tests; request inputs are visible in `post/put` payloads; status assertions are common.
- Weak areas: some tests assert only “not 401/403” instead of precise status + response schema (e.g., `testSignupActivitySuccessForRegularUser`, `testCancelSignupSuccessForAdmin`, `testAcknowledgeSignupSuccessForAdmin` in `repo/API_tests/EndpointActivityExtTest.php`).

## Tests Check
- Success/failure/permission paths: broadly present across endpoint suites.
- Validation/edge cases: present in service + validate suites.
- Assertion depth: mixed; many strong assertions, but some permissive assertions remain.
- `run_tests.sh`: Docker-based execution is implemented (good), but it performs runtime `npm install` inside container, which introduces network/install-time variability (`repo/run_tests.sh`).

## Test Coverage Score (0-100)
**90/100**

## Score Rationale
- + Full endpoint-level HTTP coverage with true no-mock route dispatch.
- + Broad backend and frontend unit coverage.
- - Some HTTP tests use weak assertions (`not 401/403`) instead of deterministic response contracts.
- - No true browser FE↔BE end-to-end tests for a fullstack system.
- - A few backend utility services remain untested.

## Key Gaps
1. Missing browser-level FE↔BE E2E scenarios (critical for fullstack confidence).
2. Weak assertion style in select API tests (status/content ambiguity).
3. No direct unit tests for `SpellCorrectionService`, `PinyinService`, `EncryptionService`.

## Confidence & Assumptions
- Confidence: **High** for static route/test mapping and classification.
- Assumptions:
  - Coverage defined strictly as route invocation evidence in source.
  - No hidden dynamic route registration outside `backend/route/app.php`.
  - No runtime-generated tests beyond inspected files.

## Test Coverage Verdict
**PASS with gaps** (coverage breadth is strong; rigor and E2E depth are the primary deficiencies).

---

# README Audit

## Hard Gate Evaluation
- README existence at required path `repo/README.md`: **PASS**.
- Project type declaration near top: **PASS** (`Project type: Fullstack web application`).
- Startup instructions for fullstack include `docker-compose up`: **PASS** (legacy command explicitly shown; modern `docker compose` also provided).
- Access method (URL + port): **PASS** (`http://localhost:8080`).
- Verification method: **PASS** (curl checks for ping and login).
- Environment rules (no host-side package installs/manual DB setup): **PASS in README text** (Docker-contained workflow). Note: `run_tests.sh` performs `npm install` inside container at runtime.
- Demo credentials with roles (auth exists): **PASS** (admin, ops staff, team lead, reviewer, regular users documented).

## Engineering Quality
- Tech stack clarity: **Strong**.
- Architecture explanation: **Strong**.
- Testing instructions: **Strong** (wrapper + manual paths).
- Security/roles documentation: **Strong**.
- Workflow clarity and presentation: **Strong**.

## High Priority Issues
- None.

## Medium Priority Issues
1. Fullstack README verification focuses on API curl and does not provide a concrete web UI validation flow.
2. README test guidance does not explicitly call out that frontend test execution performs in-container `npm install`, which can impact reproducibility in restricted/offline environments.

## Low Priority Issues
1. Mixed `docker compose` vs `docker-compose` wording may confuse readers despite legacy note.

## Hard Gate Failures
- **None**

## README Verdict (PASS / PARTIAL PASS / FAIL)
**PASS**
