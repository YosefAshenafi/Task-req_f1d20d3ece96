/**
 * Unit tests for src/modules/checklists.js
 *
 * Covers:
 *   - module load + exported surface
 *   - load(activityId) hits /activities/:id/checklists
 *   - toggleItem() hits /checklists/:cid/items/:iid/complete (PUT)
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

require('../src/modules/checklists');

const checklists = window.__layuiModules['checklists'];

describe('checklists module surface', () => {
    test('exports initList, load, render, toggleItem, deleteChecklist', () => {
        expect(typeof checklists.initList).toBe('function');
        expect(typeof checklists.load).toBe('function');
        expect(typeof checklists.render).toBe('function');
        expect(typeof checklists.toggleItem).toBe('function');
        expect(typeof checklists.deleteChecklist).toBe('function');
    });
});

describe('checklists.load', () => {
    beforeEach(() => window.layui.common.request.mockClear());

    test('requests /activities/:id/checklists for the given activity', () => {
        checklists.load(42);
        const call = window.layui.common.request.mock.calls[0][0];
        expect(call.url).toBe('/activities/42/checklists');
    });

    test('sets currentActivityId so later item actions know the context', () => {
        checklists.load(99);
        expect(checklists.currentActivityId).toBe(99);
    });
});

describe('checklists.toggleItem', () => {
    beforeEach(() => window.layui.common.request.mockClear());

    test('PUTs /checklists/:cid/items/:iid/complete', () => {
        checklists.toggleItem(5, 17);
        const call = window.layui.common.request.mock.calls[0][0];
        expect(call.url).toBe('/checklists/5/items/17/complete');
        expect(call.method).toBe('PUT');
    });
});
