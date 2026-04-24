/**
 * Unit tests for src/modules/activities.js
 *
 * Covers pure helpers that do not depend on a live DOM:
 *   - getStateBadge() — lifecycle state → badge mapping
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

require('../src/modules/activities');

const activities = window.__layuiModules['activities'];

// ============================================================
// getStateBadge — lifecycle state rendering
// ============================================================

describe('activities.getStateBadge', () => {
    test('draft maps to gray badge labelled "Draft"', () => {
        const html = activities.getStateBadge('draft');
        expect(html).toContain('layui-bg-gray');
        expect(html).toContain('Draft');
    });

    test('published maps to blue badge labelled "Published"', () => {
        const html = activities.getStateBadge('published');
        expect(html).toContain('layui-bg-blue');
        expect(html).toContain('Published');
    });

    test('in_progress, completed, and archived each render distinct colour classes', () => {
        expect(activities.getStateBadge('in_progress')).toContain('layui-bg-green');
        expect(activities.getStateBadge('completed')).toContain('layui-bg-orange');
        expect(activities.getStateBadge('archived')).toContain('layui-bg-black');
    });

    test('unknown state falls back to gray and preserves the raw state name', () => {
        const html = activities.getStateBadge('mystery-state');
        expect(html).toContain('layui-bg-gray');
        expect(html).toContain('mystery-state');
    });

    test('returns a well-formed layui-badge span', () => {
        const html = activities.getStateBadge('draft');
        expect(html).toMatch(/^<span class="layui-badge/);
        expect(html).toMatch(/<\/span>$/);
    });
});

// ============================================================
// escapeHtml — XSS prevention
// ============================================================

describe('activities.escapeHtml', () => {
    test('neutralises a <script> injection attempt', () => {
        const out = activities.escapeHtml('<script>alert(1)</script>');
        expect(out).not.toContain('<script>');
        expect(out).toContain('&lt;');
        expect(out).toContain('&gt;');
    });

    test('passes plain text through unchanged', () => {
        expect(activities.escapeHtml('Spring Hackathon 2026')).toBe('Spring Hackathon 2026');
    });

    test('escapes ampersand characters', () => {
        expect(activities.escapeHtml('Tom & Jerry')).toContain('&amp;');
    });
});
