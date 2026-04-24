/**
 * Unit tests for src/modules/shipments.js
 *
 * Covers pure helpers:
 *   - getStatusBadge() — shipment status → badge mapping
 *   - escapeHtml()    — XSS-safe text sanitization
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

require('../src/modules/shipments');

const shipments = window.__layuiModules['shipments'];

describe('shipments.getStatusBadge', () => {
    test('pending maps to orange badge labelled "Pending"', () => {
        const html = shipments.getStatusBadge('pending');
        expect(html).toContain('layui-bg-orange');
        expect(html).toContain('Pending');
    });

    test('in_transit maps to blue badge labelled "In Transit"', () => {
        const html = shipments.getStatusBadge('in_transit');
        expect(html).toContain('layui-bg-blue');
        expect(html).toContain('In Transit');
    });

    test('delivered and exception render distinct colour classes', () => {
        expect(shipments.getStatusBadge('delivered')).toContain('layui-bg-green');
        expect(shipments.getStatusBadge('exception')).toContain('layui-bg-red');
    });

    test('unknown status preserves raw value', () => {
        const html = shipments.getStatusBadge('cancelled');
        expect(html).toContain('cancelled');
    });
});

describe('shipments.escapeHtml', () => {
    test('returns empty string for falsy input', () => {
        expect(shipments.escapeHtml(null)).toBe('');
        expect(shipments.escapeHtml('')).toBe('');
        expect(shipments.escapeHtml(undefined)).toBe('');
    });

    test('escapes <script> tags', () => {
        const out = shipments.escapeHtml('<script>alert(1)</script>');
        expect(out).not.toContain('<script>');
        expect(out).toContain('&lt;');
    });

    test('leaves plain tracking numbers intact', () => {
        expect(shipments.escapeHtml('TRK-12345-XY')).toBe('TRK-12345-XY');
    });
});
