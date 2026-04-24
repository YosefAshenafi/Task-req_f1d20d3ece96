/**
 * Unit tests for src/modules/violations.js
 *
 * Covers the pure helper:
 *   - getStatusBadge() — violation lifecycle → badge mapping
 */

window.__layuiModules['common'] = {
    getToken: () => null,
    getUser: () => ({ role: 'administrator' }),
    request: jest.fn(),
    uuid: () => '00000000-0000-4000-8000-000000000000',
    formatDateTime: (s) => s || '',
    formatDate: (s) => s || ''
};
window.layui.common = window.__layuiModules['common'];
window.layui.form = { render: jest.fn(), on: jest.fn() };

require('../src/modules/violations');

const violations = window.__layuiModules['violations'];

describe('violations.getStatusBadge', () => {
    test('pending maps to orange badge labelled "Pending"', () => {
        const html = violations.getStatusBadge('pending');
        expect(html).toContain('layui-bg-orange');
        expect(html).toContain('Pending');
    });

    test('approved maps to red and rejected maps to green (enforcement colours)', () => {
        expect(violations.getStatusBadge('approved')).toContain('layui-bg-red');
        expect(violations.getStatusBadge('approved')).toContain('Approved');
        expect(violations.getStatusBadge('rejected')).toContain('layui-bg-green');
        expect(violations.getStatusBadge('rejected')).toContain('Rejected');
    });

    test('under_review and resolved render distinct colours and labels', () => {
        const under = violations.getStatusBadge('under_review');
        expect(under).toContain('layui-bg-blue');
        expect(under).toContain('Under Review');
        const resolved = violations.getStatusBadge('resolved');
        expect(resolved).toContain('layui-bg-cyan');
        expect(resolved).toContain('Resolved');
    });

    test('unknown status preserves the raw value inside a badge span', () => {
        const html = violations.getStatusBadge('escalated');
        expect(html).toMatch(/^<span class="layui-badge/);
        expect(html).toContain('escalated');
    });
});
