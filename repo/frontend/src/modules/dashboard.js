/**
 * CampusOps Dashboard Module
 * Dashboard widgets and data.
 */
layui.define(['jquery', 'layer', 'common'], function (exports) {
    var $ = layui.jquery;
    var layer = layui.layer;
    var common = layui.common;

    var dashboard = {
        init: function () {
            this.load();
        },

        load: function () {
            var that = this;
            common.request({
                url: '/dashboard',
                success: function (res) {
                    if (res.success) {
                        that.render(res.data);
                    }
                }
            });
        },

        render: function (data) {
            this.renderOrdersChart(data.widgets.orders_by_state || []);
            this.renderActivitiesChart(data.widgets.activities_by_state || []);
            this.renderRecentOrders(data.widgets.recent_orders || []);
        },

        renderOrdersChart: function (data) {
            var $container = $('#orders-chart');
            if (!data || data.length === 0) {
                $container.html('<div style="color:#999;padding:20px;text-align:center;">No data</div>');
                return;
            }
            var html = '<table class="layui-table"><thead><tr><th>State</th><th>Count</th></tr></thead><tbody>';
            for (var i = 0; i < data.length; i++) {
                var item = data[i];
                html += '<tr><td>' + item.state + '</td><td>' + item.count + '</td></tr>';
            }
            html += '</tbody></table>';
            $container.html(html);
        },

        renderActivitiesChart: function (data) {
            var $container = $('#activities-chart');
            if (!data || data.length === 0) {
                $container.html('<div style="color:#999;padding:20px;text-align:center;">No data</div>');
                return;
            }
            var html = '<table class="layui-table"><thead><tr><th>State</th><th>Count</th></tr></thead><tbody>';
            for (var i = 0; i < data.length; i++) {
                var item = data[i];
                html += '<tr><td>' + item.state + '</td><td>' + item.count + '</td></tr>';
            }
            html += '</tbody></table>';
            $container.html(html);
        },

        renderRecentOrders: function (data) {
            var $container = $('#recent-orders');
            if (!data || data.length === 0) {
                $container.html('<div style="color:#999;padding:20px;text-align:center;">No orders</div>');
                return;
            }
            var html = '<table class="layui-table"><thead><tr><th>ID</th><th>State</th><th>Amount</th></tr></thead><tbody>';
            for (var i = 0; i < data.length; i++) {
                var o = data[i];
                html += '<tr><td>' + o.id + '</td><td>' + o.state + '</td><td>$' + o.amount + '</td></tr>';
            }
            html += '</tbody></table>';
            $container.html(html);
        }
    };

    window.layui = window.layui || {};
    window.layui.dashboard = dashboard;
    exports('dashboard', dashboard);
});