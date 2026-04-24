/**
 * Unit tests for src/modules/polish.js
 *
 * Covers the pure helpers:
 *   - formatBytes()    — byte-count humanisation
 *   - debounce()       — timer-gated call batching
 *   - showBreadcrumb() — breadcrumb HTML assembly
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

require('../src/modules/polish');

const polish = window.__layuiModules['polish'];

// ============================================================
// formatBytes
// ============================================================

describe('polish.formatBytes', () => {
    test('returns "0 B" for zero', () => {
        expect(polish.formatBytes(0)).toBe('0 B');
    });

    test('scales to KB at 1024 bytes', () => {
        expect(polish.formatBytes(1024)).toBe('1 KB');
    });

    test('scales to MB with two-decimal precision', () => {
        const result = polish.formatBytes(1024 * 1024 * 1.5);
        expect(result).toBe('1.5 MB');
    });

    test('scales to GB for values over a gigabyte', () => {
        const result = polish.formatBytes(1024 * 1024 * 1024 * 2);
        expect(result).toBe('2 GB');
    });

    test('renders sub-KB values as bytes', () => {
        expect(polish.formatBytes(512)).toContain(' B');
    });
});

// ============================================================
// debounce
// ============================================================

describe('polish.debounce', () => {
    beforeEach(() => {
        jest.useFakeTimers();
    });

    afterEach(() => {
        jest.useRealTimers();
    });

    test('defers the wrapped call until the wait window elapses', () => {
        const fn = jest.fn();
        const debounced = polish.debounce(fn, 100);
        debounced();
        expect(fn).not.toHaveBeenCalled();
        jest.advanceTimersByTime(100);
        expect(fn).toHaveBeenCalledTimes(1);
    });

    test('coalesces rapid successive calls into a single invocation', () => {
        const fn = jest.fn();
        const debounced = polish.debounce(fn, 50);
        debounced('a');
        debounced('b');
        debounced('c');
        jest.advanceTimersByTime(50);
        expect(fn).toHaveBeenCalledTimes(1);
        expect(fn).toHaveBeenCalledWith('c');
    });
});

// ============================================================
// showBreadcrumb
// ============================================================

describe('polish.showBreadcrumb', () => {
    test('renders a single-item breadcrumb with <cite> when no URL is provided', () => {
        const html = polish.showBreadcrumb([{ title: 'Home' }]);
        expect(html).toContain('<cite>Home</cite>');
        expect(html).toContain('layui-breadcrumb');
    });

    test('renders links for items with url', () => {
        const html = polish.showBreadcrumb([{ title: 'Orders', url: '/orders' }]);
        expect(html).toContain('<a href="/orders">Orders</a>');
    });

    test('inserts a separator between consecutive items', () => {
        const html = polish.showBreadcrumb([
            { title: 'Home', url: '/' },
            { title: 'Orders', url: '/orders' },
            { title: 'Detail' }
        ]);
        const separators = (html.match(/lay-separator/g) || []).length;
        expect(separators).toBe(2);
        expect(html).toContain('<cite>Detail</cite>');
    });
});
