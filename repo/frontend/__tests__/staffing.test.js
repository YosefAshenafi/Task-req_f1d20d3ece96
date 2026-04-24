/**
 * Unit tests for src/modules/staffing.js
 *
 * Covers:
 *   - module load + exported surface
 *   - load(activityId) hits /activities/:id/staffing
 *   - currentActivityId context is retained between calls
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
window.layui.form = { render: jest.fn(), on: jest.fn() };

window.__layuiModules['common'] = {
    getToken: () => null,
    getUser: () => ({ role: 'administrator' }),
    request: jest.fn(),
    uuid: () => '00000000-0000-4000-8000-000000000000',
    formatDateTime: (s) => s || '',
    formatDate: (s) => s || ''
};
window.layui.common = window.__layuiModules['common'];

require('../src/modules/staffing');

const staffing = window.__layuiModules['staffing'];

describe('staffing module surface', () => {
    test('exports initList, load, render, showForm, delete', () => {
        ['initList', 'load', 'render', 'showForm', 'delete'].forEach((m) =>
            expect(typeof staffing[m]).toBe('function'));
    });
});

describe('staffing.load', () => {
    beforeEach(() => window.layui.common.request.mockClear());

    test('requests /activities/:id/staffing for the given activity', () => {
        staffing.load(11);
        const call = window.layui.common.request.mock.calls[0][0];
        expect(call.url).toBe('/activities/11/staffing');
    });

    test('records the activity id on the module so later actions inherit it', () => {
        staffing.load(42);
        expect(staffing.currentActivityId).toBe(42);
    });

    test('updating the activity id via a second load() replaces the first', () => {
        staffing.load(1);
        staffing.load(2);
        expect(staffing.currentActivityId).toBe(2);
    });
});
