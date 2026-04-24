/**
 * Unit tests for src/modules/dashboard.js
 *
 * Covers:
 *   - module load + exported surface
 *   - load() hits /dashboard
 *   - favoriteWidget() POSTs /dashboard/favorites
 *   - unfavoriteWidget() DELETEs /dashboard/favorites/:widget_id
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

window.__layuiModules['common'] = {
    getToken: () => null,
    getUser: () => ({ role: 'administrator' }),
    request: jest.fn(),
    uuid: () => '00000000-0000-4000-8000-000000000000',
    formatDateTime: (s) => s || '',
    formatDate: (s) => s || ''
};
window.layui.common = window.__layuiModules['common'];

require('../src/modules/dashboard');

const dashboard = window.__layuiModules['dashboard'];

describe('dashboard module surface', () => {
    test('exports init, load, render, favoriteWidget, unfavoriteWidget, saveLayout, loadLayout', () => {
        ['init', 'load', 'render', 'favoriteWidget', 'unfavoriteWidget', 'saveLayout', 'loadLayout']
            .forEach((m) => expect(typeof dashboard[m]).toBe('function'));
    });
});

describe('dashboard.load', () => {
    beforeEach(() => window.layui.common.request.mockClear());

    test('calls /dashboard with no pagination params', () => {
        dashboard.load();
        const call = window.layui.common.request.mock.calls[0][0];
        expect(call.url).toBe('/dashboard');
    });
});

describe('dashboard.favoriteWidget', () => {
    beforeEach(() => window.layui.common.request.mockClear());

    test('POSTs /dashboard/favorites with the widget_id payload', () => {
        dashboard.favoriteWidget('orders_by_state');
        const call = window.layui.common.request.mock.calls[0][0];
        expect(call.url).toBe('/dashboard/favorites');
        expect(call.method).toBe('POST');
        expect(call.data).toEqual({ widget_id: 'orders_by_state' });
    });
});

describe('dashboard.unfavoriteWidget', () => {
    beforeEach(() => window.layui.common.request.mockClear());

    test('DELETEs /dashboard/favorites/:widget_id with URL-encoding', () => {
        dashboard.unfavoriteWidget('recent orders');
        const call = window.layui.common.request.mock.calls[0][0];
        expect(call.url).toBe('/dashboard/favorites/recent%20orders');
        expect(call.method).toBe('DELETE');
    });
});
