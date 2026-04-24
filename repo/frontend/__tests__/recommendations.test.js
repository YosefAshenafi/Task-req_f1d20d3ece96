/**
 * Unit tests for src/modules/recommendations.js
 *
 * Covers:
 *   - module load + exported surface
 *   - load() hits /recommendations with context + limit
 *   - loadPopular() hits /recommendations/popular with limit
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

require('../src/modules/recommendations');

const recommendations = window.__layuiModules['recommendations'];

describe('recommendations module surface', () => {
    test('exports load, render, view, loadPopular', () => {
        ['load', 'render', 'view', 'loadPopular'].forEach((m) =>
            expect(typeof recommendations[m]).toBe('function'));
    });
});

describe('recommendations.load', () => {
    beforeEach(() => window.layui.common.request.mockClear());

    test('requests /recommendations with default context=list, limit=10', () => {
        recommendations.load('container-1');
        const call = window.layui.common.request.mock.calls[0][0];
        expect(call.url).toBe('/recommendations?context=list&limit=10');
    });

    test('forwards a non-default context and limit', () => {
        recommendations.load('container-2', 'detail', 5);
        const call = window.layui.common.request.mock.calls[0][0];
        expect(call.url).toBe('/recommendations?context=detail&limit=5');
    });
});

describe('recommendations.loadPopular', () => {
    beforeEach(() => window.layui.common.request.mockClear());

    test('requests /recommendations/popular with default limit=10', () => {
        recommendations.loadPopular('container-3');
        const call = window.layui.common.request.mock.calls[0][0];
        expect(call.url).toBe('/recommendations/popular?limit=10');
    });

    test('forwards a custom limit', () => {
        recommendations.loadPopular('container-4', 25);
        const call = window.layui.common.request.mock.calls[0][0];
        expect(call.url).toBe('/recommendations/popular?limit=25');
    });
});
