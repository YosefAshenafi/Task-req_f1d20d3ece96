/**
 * CampusOps Common Extensions
 * Loading spinners, error handling, and UI polish.
 */
layui.define(['jquery', 'layer'], function (exports) {
    var $ = layui.jquery;
    var layer = layui.layer;

    var polish = {
        /**
         * Show loading spinner.
         */
        showLoading: function (target) {
            target = target || 'body';
            var html = '<div class="app-loading" style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);z-index:9999;">' +
                '<i class="layui-icon layui-icon-loading layui-anim layui-anim-rotate layui-anim-loop" style="font-size:40px;color:#1E9FFF;"></i>' +
                '</div>';
            $(target).append(html);
            return $('.app-loading');
        },

        /**
         * Hide loading spinner.
         */
        hideLoading: function () {
            $('.app-loading').remove();
        },

        /**
         * Show error message.
         */
        showError: function (msg, title) {
            title = title || 'Error';
            layer.msg(msg, { icon: 2, title: title });
        },

        /**
         * Show success message.
         */
        showSuccess: function (msg) {
            layer.msg(msg, { icon: 1 });
        },

        /**
         * Confirm dialog.
         */
        confirm: function (msg, callback) {
            layer.confirm(msg, { icon: 3, title: 'Confirm', btn: ['OK', 'Cancel'] }, function (idx) {
                if (callback) callback();
                layer.close(idx);
            });
        },

        /**
         * Format bytes to human readable.
         */
        formatBytes: function (bytes) {
            if (bytes === 0) return '0 B';
            var k = 1024;
            var sizes = ['B', 'KB', 'MB', 'GB'];
            var i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        },

        /**
         * Debounce function.
         */
        debounce: function (func, wait) {
            var timeout;
            return function () {
                var context = this;
                var args = arguments;
                clearTimeout(timeout);
                timeout = setTimeout(function () {
                    func.apply(context, args);
                }, wait);
            };
        },

        /**
         * Show breadcrumb.
         */
        showBreadcrumb: function (items) {
            var html = '<div class="layui-breadcrumb" style="padding:10px 15px;">';
            for (var i = 0; i < items.length; i++) {
                var item = items[i];
                if (i > 0) html += ' <span lay-separator>/</span> ';
                if (item.url) {
                    html += '<a href="' + item.url + '">' + item.title + '</a>';
                } else {
                    html += '<cite>' + item.title + '</cite>';
                }
            }
            html += '</div>';
            return html;
        }
    };

    // Extend common module
    var common = layui.common;
    if (common) {
        common.showLoading = polish.showLoading;
        common.hideLoading = polish.hideLoading;
        common.showError = polish.showError;
        common.showSuccess = polish.showSuccess;
    }

    window.layui = window.layui || {};
    window.layui.polish = polish;

    exports('polish', polish);
});