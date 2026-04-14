/**
 * CampusOps Global Configuration
 */
window.CampusOps = window.CampusOps || {};

CampusOps.config = {
    // API base URL
    apiBase: '/api/v1',

    // Application name
    appName: 'CampusOps',

    // Date/time format
    dateFormat: 'MM/DD/YYYY',
    timeFormat: 'hh:mm A',
    dateTimeFormat: 'MM/DD/YYYY hh:mm A',

    // Pagination defaults
    pageSize: 20,
    pageSizes: [10, 20, 50, 100],

    // File upload limits
    maxFileSize: 10 * 1024 * 1024, // 10 MB
    allowedFileTypes: ['jpg', 'jpeg', 'png', 'pdf'],
};

/**
 * Role-based navigation menus
 */
CampusOps.menus = {
    administrator: [
        { title: 'Dashboard', icon: 'layui-icon-home', url: '/src/views/home.html' },
        { title: 'Users', icon: 'layui-icon-username', url: '/src/views/users/list.html' },
        { title: 'Activities', icon: 'layui-icon-flag', url: '/src/views/activities/list.html' },
        { title: 'Orders', icon: 'layui-icon-cart', url: '/src/views/orders/list.html' },
        { title: 'Violations', icon: 'layui-icon-release', url: '/src/views/violations/list.html' },
        { title: 'Search', icon: 'layui-icon-search', url: '/src/views/search/results.html' },
        { title: 'Reports', icon: 'layui-icon-chart', url: '/src/views/dashboard/home.html' },
        { title: 'Audit', icon: 'layui-icon-log', url: '/src/views/audit/list.html' },
    ],
    operations_staff: [
        { title: 'Dashboard', icon: 'layui-icon-home', url: '/src/views/home.html' },
        { title: 'Activities', icon: 'layui-icon-flag', url: '/src/views/activities/list.html' },
        { title: 'Orders', icon: 'layui-icon-cart', url: '/src/views/orders/list.html' },
        { title: 'Shipments', icon: 'layui-icon-transfer', url: '/src/views/shipments/list.html' },
        { title: 'Search', icon: 'layui-icon-search', url: '/src/views/search/results.html' },
    ],
    team_lead: [
        { title: 'Dashboard', icon: 'layui-icon-home', url: '/src/views/home.html' },
        { title: 'Activities', icon: 'layui-icon-flag', url: '/src/views/activities/list.html' },
        { title: 'Tasks', icon: 'layui-icon-form', url: '/src/views/tasks/list.html' },
        { title: 'Staffing', icon: 'layui-icon-group', url: '/src/views/staffing/list.html' },
        { title: 'Checklists', icon: 'layui-icon-list', url: '/src/views/checklists/list.html' },
    ],
    reviewer: [
        { title: 'Dashboard', icon: 'layui-icon-home', url: '/src/views/home.html' },
        { title: 'Approvals', icon: 'layui-icon-vercode', url: '/src/views/violations/list.html' },
        { title: 'Violations', icon: 'layui-icon-release', url: '/src/views/violations/list.html' },
        { title: 'Audit', icon: 'layui-icon-log', url: '/src/views/audit/list.html' },
    ],
    regular_user: [
        { title: 'Dashboard', icon: 'layui-icon-home', url: '/src/views/home.html' },
        { title: 'Activities', icon: 'layui-icon-flag', url: '/src/views/activities/list.html' },
        { title: 'Orders', icon: 'layui-icon-cart', url: '/src/views/orders/list.html' },
        { title: 'Notifications', icon: 'layui-icon-notice', url: '/src/views/notifications/list.html' },
    ],
};

// Layui configuration
layui.config({
    base: '/src/modules/'
});
