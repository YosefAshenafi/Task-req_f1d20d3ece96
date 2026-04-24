/**
 * Unit tests for src/modules/notifications.js
 *
 * Covers:
 *   - module load + exported surface
 *   - load(page) hits /notifications with paging
 *   - view(id) PUTs /notifications/:id/read
 *   - loadPreferences() hits /preferences
 *   - savePreferences(data) PUTs /preferences
 */

function chainable() {
    const c = {};
    ['empty', 'append', 'html', 'on', 'val', 'show', 'hide', 'text', 'attr',
     'addClass', 'removeClass', 'each', 'find', 'data', 'click', 'off'].forEach((m) => {
        c[m] = jest.fn().mockReturnValue(c);
    });
    c.length = 0;
    return c;
}

const $stub = jest.fn(() => chainable());
$stub.ajax = jest.fn();
$stub.extend = (a, b, c) => Object.assign({}, b, c);

window.layui.jquery = $stub;
window.layui.form = { render: jest.fn(), on: jest.fn(), val: jest.fn() };

window.__layuiModules['common'] = {
    getToken: () => null,
    getUser: () => ({ role: 'administrator' }),
    request: jest.fn(),
    uuid: () => '00000000-0000-4000-8000-000000000000',
    formatDateTime: (s) => s || '',
    formatDate: (s) => s || ''
};
window.layui.common = window.__layuiModules['common'];

require('../src/modules/notifications');

const notifications = window.__layuiModules['notifications'];

describe('notifications module surface', () => {
    test('exports init, initList, load, view, loadPreferences, savePreferences', () => {
        ['init', 'initList', 'load', 'view', 'loadPreferences', 'savePreferences']
            .forEach((m) => expect(typeof notifications[m]).toBe('function'));
    });
});

describe('notifications.load', () => {
    beforeEach(() => window.layui.common.request.mockClear());

    test('requests /notifications with page=1, limit=20 by default', () => {
        notifications.load();
        const call = window.layui.common.request.mock.calls[0][0];
        expect(call.url).toBe('/notifications');
        expect(call.data).toEqual({ page: 1, limit: 20 });
    });

    test('forwards a requested page number', () => {
        notifications.load(4);
        const call = window.layui.common.request.mock.calls[0][0];
        expect(call.data.page).toBe(4);
    });
});

describe('notifications.view', () => {
    beforeEach(() => window.layui.common.request.mockClear());

    test('PUTs /notifications/:id/read', () => {
        notifications.view(123, null, null);
        const call = window.layui.common.request.mock.calls[0][0];
        expect(call.url).toBe('/notifications/123/read');
        expect(call.method).toBe('PUT');
    });
});

describe('notifications preferences', () => {
    beforeEach(() => window.layui.common.request.mockClear());

    test('loadPreferences requests /preferences', () => {
        notifications.loadPreferences();
        const call = window.layui.common.request.mock.calls[0][0];
        expect(call.url).toBe('/preferences');
    });

    test('savePreferences PUTs /preferences with the supplied payload', () => {
        const payload = { arrival_reminders: 1, order_alerts: 0 };
        notifications.savePreferences(payload);
        const call = window.layui.common.request.mock.calls[0][0];
        expect(call.url).toBe('/preferences');
        expect(call.method).toBe('PUT');
        expect(call.data).toEqual(payload);
    });
});
