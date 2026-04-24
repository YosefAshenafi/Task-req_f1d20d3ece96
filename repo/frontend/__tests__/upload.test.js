/**
 * Unit tests for src/modules/upload.js
 *
 * Covers the pure helpers:
 *   - validateFile() — MIME-type + size gate
 *   - formatSize()   — human-readable byte scaling
 */

window.__layuiModules['common'] = {
    getToken: () => 'test-token',
    getUser: () => ({ role: 'administrator' }),
    request: jest.fn(),
    uuid: () => '00000000-0000-4000-8000-000000000000',
    formatDateTime: (s) => s || '',
    formatDate: (s) => s || ''
};
window.layui.common = window.__layuiModules['common'];

require('../src/modules/upload');

const upload = window.__layuiModules['upload'];

// ============================================================
// validateFile — MIME + size gate
// ============================================================

describe('upload.validateFile', () => {
    test('accepts a JPEG under the 10 MB cap', () => {
        const ok = upload.validateFile({ type: 'image/jpeg', size: 1024 * 1024 });
        expect(ok).toBe(true);
    });

    test('accepts PNG and PDF types', () => {
        expect(upload.validateFile({ type: 'image/png', size: 100 })).toBe(true);
        expect(upload.validateFile({ type: 'application/pdf', size: 100 })).toBe(true);
    });

    test('rejects a disallowed MIME type (GIF)', () => {
        expect(upload.validateFile({ type: 'image/gif', size: 100 })).toBe(false);
    });

    test('rejects a file exceeding the 10 MB limit', () => {
        const oversized = { type: 'image/png', size: 11 * 1024 * 1024 };
        expect(upload.validateFile(oversized)).toBe(false);
    });

    test('rejects at the exact over-the-limit boundary', () => {
        const justOver = { type: 'image/png', size: 10 * 1024 * 1024 + 1 };
        expect(upload.validateFile(justOver)).toBe(false);
    });
});

// ============================================================
// formatSize — byte → human string
// ============================================================

describe('upload.formatSize', () => {
    test('returns "512 B" for small files', () => {
        expect(upload.formatSize(512)).toBe('512 B');
    });

    test('scales to KB with one decimal place', () => {
        expect(upload.formatSize(2048)).toBe('2.0 KB');
    });

    test('scales to MB for files over a megabyte', () => {
        expect(upload.formatSize(1024 * 1024 * 3)).toBe('3.0 MB');
    });

    test('crosses the B→KB boundary at 1024', () => {
        expect(upload.formatSize(1023)).toBe('1023 B');
        expect(upload.formatSize(1024)).toBe('1.0 KB');
    });
});
