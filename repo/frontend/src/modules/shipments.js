/**
 * CampusOps Shipments Module
 * Shipment tracking and management.
 */
layui.define(['jquery', 'layer', 'form', 'common'], function (exports) {
    var $ = layui.jquery;
    var layer = layui.layer;
    var form = layui.form;
    var common = layui.common;

    var shipments = {
        currentPage: 1,
        pageSize: 20,

        /**
         * Initialize the shipments list view.
         */
        initList: function () {
            this.loadShipments();
            this.bindListEvents();
        },

        /**
         * Load shipments with current filters.
         */
        loadShipments: function (page = 1) {
            var that = this;
            var params = {
                page: page,
                limit: that.pageSize,
                status: $('#filter-status').val() || '',
                tracking: $('#filter-tracking').val() || ''
            };

            var orderId = $('#filter-order-id').val() || '';
            common.request({
                url: orderId ? '/orders/' + orderId + '/shipments' : '/shipments',
                data: params,
                success: function (res) {
                    if (res.success) {
                        that.renderTable(res.data.list);
                        that.renderPagination(res.data.total, res.data.page, res.data.limit);
                    }
                },
                error: function (xhr) {
                    $('#shipments-tbody').html('<tr><td colspan="9" style="text-align: center; color: #999;">No shipments found</td></tr>');
                }
            });
        },

        /**
         * Render the shipments table.
         */
        renderTable: function (list) {
            var $tbody = $('#shipments-tbody');
            $tbody.empty();

            if (!list || list.length === 0) {
                $tbody.append('<tr><td colspan="9" style="text-align: center; color: #999;">No shipments found</td></tr>');
                return;
            }

            for (var i = 0; i < list.length; i++) {
                var shipment = list[i];
                var statusBadge = this.getStatusBadge(shipment.status);

                var row = '<tr>' +
                    '<td>' + shipment.id + '</td>' +
                    '<td>' + shipment.order_id + '</td>' +
                    '<td>' + this.escapeHtml(shipment.tracking_number) + '</td>' +
                    '<td>' + this.escapeHtml(shipment.carrier) + '</td>' +
                    '<td>' + statusBadge + '</td>' +
                    '<td>' + this.escapeHtml(shipment.origin) + '</td>' +
                    '<td>' + this.escapeHtml(shipment.destination) + '</td>' +
                    '<td>' + common.formatDateTime(shipment.created_at) + '</td>' +
                    '<td>' +
                    '<button class="layui-btn layui-btn-xs" data-action="view" data-id="' + shipment.id + '">View</button>' +
                    '<button class="layui-btn layui-btn-xs" data-action="track" data-id="' + shipment.id + '">Track</button>' +
                    '</td>' +
                    '</tr>';
                $tbody.append(row);
            }
        },

        /**
         * Render pagination.
         */
        renderPagination: function (total, page, limit) {
            var totalPages = Math.ceil(total / limit);
            var paginationHtml = '<span>Total: ' + total + '</span> ';
            paginationHtml += '<button class="layui-btn layui-xs" ' + (page > 1 ? 'onclick="layui.shipments.loadShipments(' + (page - 1) + ')"' : 'disabled') + '>Prev</button> ';
            paginationHtml += '<span>Page ' + page + ' of ' + totalPages + '</span> ';
            paginationHtml += '<button class="layui-btn layui-xs" ' + (page < totalPages ? 'onclick="layui.shipments.loadShipments(' + (page + 1) + ')"' : 'disabled') + '>Next</button>';
            $('#shipments-pagination').html(paginationHtml);
        },

        /**
         * Bind list view events.
         */
        bindListEvents: function () {
            var that = this;

            $('#btn-search').on('click', function () {
                that.loadShipments(1);
            });

            $('#filter-status').on('change', function () {
                that.loadShipments(1);
            });

            $('#shipments-tbody').on('click', '[data-action]', function () {
                var action = $(this).attr('data-action');
                var id = $(this).attr('data-id');
                if (action === 'view') {
                    that.viewShipment(id);
                } else if (action === 'track') {
                    that.trackShipment(id);
                }
            });

            form.render('select', 'shipment-filters');
        },

        /**
         * View shipment details.
         */
        viewShipment: function (id) {
            common.request({
                url: '/shipments/' + id,
                success: function (res) {
                    if (res.success) {
                        layer.msg('Shipment: ' + res.data.tracking_number);
                    }
                }
            });
        },

        /**
         * Track shipment.
         */
        trackShipment: function (id) {
            common.request({
                url: '/shipments/' + id + '/scan-history',
                success: function (res) {
                    if (res.success) {
                        layer.msg('Tracking loaded');
                    }
                }
            });
        },

        /**
         * Get status badge HTML.
         */
        getStatusBadge: function (status) {
            var colors = {
                pending: 'layui-bg-orange',
                in_transit: 'layui-bg-blue',
                delivered: 'layui-bg-green',
                exception: 'layui-bg-red'
            };
            var labels = {
                pending: 'Pending',
                in_transit: 'In Transit',
                delivered: 'Delivered',
                exception: 'Exception'
            };
            var color = colors[status] || '';
            var label = labels[status] || status;
            return '<span class="layui-badge ' + color + '">' + label + '</span>';
        },

        /**
         * Escape HTML.
         */
        escapeHtml: function (text) {
            if (!text) return '';
            var div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    };

    // Expose to global for onclick handlers
    window.layui = window.layui || {};
    window.layui.shipments = shipments;

    exports('shipments', shipments);
});