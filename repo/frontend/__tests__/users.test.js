/**
 * Unit tests for src/modules/users.js
 *
 * Covers pure helpers — render-level logic that does not require a real
 * jQuery DOM implementation:
 *   - getRoleBadge()  — role → badge HTML mapping
 *   - escapeHtml()    — text sanitization
 *   - generatePassword() — randomness contract
 *
 * Stubs follow the pattern established by orders.test.js: a minimal
 * common/form stub is injected before the module is required.
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

require('../src/modules/users');

const users = window.__layuiModules['users'];

// ============================================================
// getRoleBadge — render behavior (F1)
// ============================================================

describe('users.getRoleBadge', () => {
    test('administrator maps to red badge labelled "Admin"', () => {
        const html = users.getRoleBadge('administrator');
        expect(html).toContain('layui-bg-red');
        expect(html).toContain('Admin');
    });

    test('operations_staff maps to orange badge labelled "Ops Staff"', () => {
        const html = users.getRoleBadge('operations_staff');
        expect(html).toContain('layui-bg-orange');
        expect(html).toContain('Ops Staff');
    });

    test('team_lead, reviewer, and regular_user each render distinct classes', () => {
        expect(users.getRoleBadge('team_lead')).toContain('layui-bg-blue');
        expect(users.getRoleBadge('reviewer')).toContain('layui-bg-purple');
        expect(users.getRoleBadge('regular_user')).toContain('layui-bg-gray');
    });

    test('unknown role falls back to gray badge and preserves the raw role name', () => {
        const html = users.getRoleBadge('mystery-role');
        expect(html).toContain('layui-bg-gray');
        expect(html).toContain('mystery-role');
    });
});

// ============================================================
// escapeHtml — sanitization (F2)
// ============================================================

describe('users.escapeHtml', () => {
    test('escapes < and > from a script-injection attempt', () => {
        const escaped = users.escapeHtml('<script>alert(1)</script>');
        expect(escaped).not.toContain('<script>');
        expect(escaped).toContain('&lt;');
        expect(escaped).toContain('&gt;');
    });

    test('passes through plain text unchanged', () => {
        expect(users.escapeHtml('alice@example.com')).toBe('alice@example.com');
    });
});

// ============================================================
// generatePassword — randomness contract (F3)
// ============================================================

describe('users.generatePassword', () => {
    test('produces a 16-character hex string (8 bytes)', () => {
        const pwd = users.generatePassword();
        expect(pwd).toMatch(/^[0-9a-f]{16}$/);
    });

    test('successive calls return distinct values', () => {
        const a = users.generatePassword();
        const b = users.generatePassword();
        expect(a).not.toBe(b);
    });
});
