/**
 * Unit tests for src/modules/tasks.js
 *
 * Covers:
 *   - getStatusBadge() — task status → badge mapping (pure helper)
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

require('../src/modules/tasks');

const tasks = window.__layuiModules['tasks'];

describe('tasks.getStatusBadge', () => {
    test('pending maps to orange badge labelled "Pending"', () => {
        const html = tasks.getStatusBadge('pending');
        expect(html).toContain('layui-bg-orange');
        expect(html).toContain('Pending');
    });

    test('in_progress maps to blue badge labelled "In Progress"', () => {
        const html = tasks.getStatusBadge('in_progress');
        expect(html).toContain('layui-bg-blue');
        expect(html).toContain('In Progress');
    });

    test('completed maps to green badge labelled "Completed"', () => {
        const html = tasks.getStatusBadge('completed');
        expect(html).toContain('layui-bg-green');
        expect(html).toContain('Completed');
    });

    test('unknown status preserves raw value and still renders a badge span', () => {
        const html = tasks.getStatusBadge('archived');
        expect(html).toMatch(/^<span class="layui-badge/);
        expect(html).toContain('archived');
    });
});
