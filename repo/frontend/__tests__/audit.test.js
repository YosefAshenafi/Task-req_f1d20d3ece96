/**
 * Unit tests for src/modules/audit.js
 *
 * Covers:
 *   - module load + exported surface
 *   - load(page) builds the `/audit` request with pagination + filter params
 */

function chainable() {
    const c = {};
    ['empty', 'append', 'html', 'on', 'val', 'show', 'hide', 'text', 'attr',
     'addClass', 'removeClass', 'each', 'find', 'data', 'click', 'off'].forEach((m) => {
        c[m] = jest.fn().mockReturnValue(c);
    });
    c.val = jest.fn().mockReturnValue('');
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

require('../src/modules/audit');

const audit = window.__layuiModules['audit'];

describe('audit module surface', () => {
    test('exports initList, load, render, bindEvents', () => {
        expect(typeof audit.initList).toBe('function');
        expect(typeof audit.load).toBe('function');
        expect(typeof audit.render).toBe('function');
        expect(typeof audit.bindEvents).toBe('function');
    });
});

describe('audit.load', () => {
    beforeEach(() => {
        window.layui.common.request.mockClear();
    });

    test('sends a GET-style request to /audit with default page=1 and limit=50', () => {
        audit.load();
        expect(window.layui.common.request).toHaveBeenCalledTimes(1);
        const call = window.layui.common.request.mock.calls[0][0];
        expect(call.url).toBe('/audit');
        expect(call.data.page).toBe(1);
        expect(call.data.limit).toBe(50);
    });

    test('forwards a requested page number', () => {
        audit.load(7);
        const call = window.layui.common.request.mock.calls[0][0];
        expect(call.data.page).toBe(7);
    });

    test('always supplies filter keys (entity_type, date_from, date_to)', () => {
        audit.load(1);
        const call = window.layui.common.request.mock.calls[0][0];
        expect(call.data).toHaveProperty('entity_type');
        expect(call.data).toHaveProperty('date_from');
        expect(call.data).toHaveProperty('date_to');
    });
});
