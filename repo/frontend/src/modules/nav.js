/**
 * CampusOps Navigation Module
 * Builds sidebar menu dynamically based on user role.
 */
layui.define(['element', 'jquery'], function (exports) {
    var element = layui.element;
    var $ = layui.jquery;

    var nav = {
        /**
         * Build the sidebar navigation for a given role.
         * @param {string} role - User role (e.g. 'administrator', 'regular_user')
         */
        build: function (role) {
            var menus = CampusOps.menus[role] || CampusOps.menus['regular_user'];
            var $sidebar = $('#sidebar-nav');
            $sidebar.empty();

            for (var i = 0; i < menus.length; i++) {
                var item = menus[i];
                var li = '<li class="layui-nav-item' + (i === 0 ? ' layui-this' : '') + '">';
                li += '<a href="javascript:;" data-url="' + item.url + '">';
                li += '<i class="layui-icon ' + item.icon + '"></i> ';
                li += '<span>' + item.title + '</span>';
                li += '</a></li>';
                $sidebar.append(li);
            }

            // Re-render the nav element
            element.render('nav', 'sidebar');
        }
    };

    // Expose globally for use in index.html inline script
    CampusOps.nav = nav;

    exports('nav', nav);
});
