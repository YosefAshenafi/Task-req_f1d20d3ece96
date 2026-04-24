/**
 * Unit tests for src/modules/nav.js
 *
 * Covers:
 *   - build(role) — renders role-specific menu items into #sidebar-nav
 *                   and invokes element.render('nav', 'sidebar').
 *
 * The module depends on layui.element (stubbed) and a small jQuery-like
 * façade with `empty()` / `append()`. The façade is installed on
 * window.layui.jquery for the duration of these tests.
 */

// Install a jQuery-like stub that operates over the real jsdom document so
// append() actually mutates the DOM we assert against.
const $stub = function (selector) {
    const el = typeof selector === 'string'
        ? document.querySelector(selector)
        : selector;
    return {
        empty: function () { if (el) el.innerHTML = ''; return this; },
        append: function (html) {
            if (el) el.insertAdjacentHTML('beforeend', html);
            return this;
        }
    };
};
$stub.ajax = jest.fn();
$stub.extend = (deep, target, src) => Object.assign({}, target, src);

window.layui.jquery = $stub;
window.layui.element = { render: jest.fn() };

// nav.js reads CampusOps.menus[role] at call time
window.CampusOps = window.CampusOps || {};
window.CampusOps.menus = {
    administrator: [
        { title: 'Dashboard', url: '/dashboard', icon: 'layui-icon-home' },
        { title: 'Users', url: '/users', icon: 'layui-icon-user' },
        { title: 'Orders', url: '/orders', icon: 'layui-icon-cart' }
    ],
    regular_user: [
        { title: 'My Orders', url: '/orders', icon: 'layui-icon-cart' }
    ]
};

require('../src/modules/nav');

const nav = window.__layuiModules['nav'];

describe('nav.build', () => {
    beforeEach(() => {
        document.body.innerHTML = '<ul id="sidebar-nav"></ul>';
        window.layui.element.render.mockClear();
    });

    test('renders one <li> per menu entry for the given role', () => {
        nav.build('administrator');
        const items = document.querySelectorAll('#sidebar-nav li.layui-nav-item');
        expect(items.length).toBe(3);
    });

    test('marks the first item active with layui-this', () => {
        nav.build('administrator');
        const first = document.querySelector('#sidebar-nav li');
        expect(first.className).toContain('layui-this');
    });

    test('embeds the data-url attribute from the menu definition', () => {
        nav.build('administrator');
        const anchors = document.querySelectorAll('#sidebar-nav a[data-url]');
        const urls = Array.from(anchors).map(a => a.getAttribute('data-url'));
        expect(urls).toEqual(['/dashboard', '/users', '/orders']);
    });

    test('renders the regular_user menu when that role is passed', () => {
        nav.build('regular_user');
        const items = document.querySelectorAll('#sidebar-nav li.layui-nav-item');
        expect(items.length).toBe(1);
        expect(items[0].textContent).toContain('My Orders');
    });

    test('falls back to regular_user menu for an unknown role', () => {
        nav.build('ghost-role');
        const items = document.querySelectorAll('#sidebar-nav li.layui-nav-item');
        expect(items.length).toBe(1);
    });

    test('invokes element.render("nav", "sidebar") so Layui re-binds the nav', () => {
        nav.build('administrator');
        expect(window.layui.element.render).toHaveBeenCalledWith('nav', 'sidebar');
    });
});
