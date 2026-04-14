/**
 * CampusOps Upload Module
 * File upload with drag-drop, progress, and validation.
 */
layui.define(['jquery', 'layer', 'common'], function (exports) {
    var $ = layui.jquery;
    var layer = layui.layer;
    var common = layui.common;

    var upload = {
        maxFileSize: 10 * 1024 * 1024, // 10 MB
        allowedTypes: ['image/jpeg', 'image/png', 'application/pdf'],

        /**
         * Render upload component.
         */
        render: function (options) {
            options = options || {};
            var containerId = options.container || 'upload-container';
            var multiple = options.multiple || false;
            var callback = options.callback || function () {};

            var html = '<div class="upload-zone" id="' + containerId + '" style="border:2px dashed #ddd;padding:30px;text-align:center;cursor:pointer;">' +
                '<i class="layui-icon layui-icon-upload" style="font-size:50px;color:#999;"></i>' +
                '<p style="margin-top:10px;">Drag files here or click to upload</p>' +
                '<p style="color:#999;font-size:12px;">JPG, PNG, PDF (max 10MB)</p>' +
                '<input type="file" id="file-input" style="display:none;" ' + (multiple ? 'multiple' : '') + ' accept=".jpg,.jpeg,.png,.pdf">' +
                '</div>' +
                '<div class="upload-progress" style="display:none;margin-top:10px;">' +
                '<div class="layui-progress layui-progress-lg" lay-showPercent="true" style="width:100%;">' +
                '<div class="layui-progress-bar" lay-percent="0%"></div>' +
                '</div>' +
                '</div>' +
                '<div class="upload-list" style="margin-top:15px;"></div>';

            $('#' + containerId).html(html);

            this.bindEvents(containerId, callback);
        },

        /**
         * Bind upload events.
         */
        bindEvents: function (containerId, callback) {
            var that = this;
            var $container = $('#' + containerId);
            var $input = $container.find('#file-input');

            $container.on('click', function () {
                $input.click();
            });

            $input.on('change', function () {
                that.uploadFiles(this.files, containerId, callback);
            });

            $container.on('dragover', function (e) {
                e.preventDefault();
                $(this).css('border-color', '#1E9FFF');
            });

            $container.on('dragleave', function () {
                $(this).css('border-color', '#ddd');
            });

            $container.on('drop', function (e) {
                e.preventDefault();
                $(this).css('border-color', '#ddd');
                that.uploadFiles(e.originalEvent.dataTransfer.files, containerId, callback);
            });
        },

        /**
         * Upload files.
         */
        uploadFiles: function (files, containerId, callback) {
            var that = this;

            for (var i = 0; i < files.length; i++) {
                var file = files[i];

                if (!this.validateFile(file)) {
                    layer.msg('Invalid file: ' + file.name + ' (type or size)', { icon: 2 });
                    continue;
                }

                this.uploadFile(file, containerId, callback);
            }
        },

        /**
         * Validate file.
         */
        validateFile: function (file) {
            return this.allowedTypes.indexOf(file.type) >= 0 && file.size <= this.maxFileSize;
        },

        /**
         * Upload single file.
         */
        uploadFile: function (file, containerId, callback) {
            var that = this;
            var formData = new FormData();
            formData.append('file', file);

            $('#' + containerId).find('.upload-progress').show();

            $.ajax({
                url: CampusOps.config.apiBase + '/upload',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'Authorization': 'Bearer ' + common.getToken()
                },
                xhr: function () {
                    var xhr = new XMLHttpRequest();
                    xhr.upload.addEventListener('progress', function (e) {
                        var pct = Math.round(e.loaded / e.total * 100);
                        $('#' + containerId).find('.layui-progress-bar').css('width', pct + '%').attr('lay-percent', pct + '%');
                    });
                    return xhr;
                },
                success: function (res) {
                    $('#' + containerId).find('.upload-progress').hide();
                    if (res.success) {
                        that.renderFileItem(res.data, containerId);
                        if (callback) callback(res.data);
                    } else {
                        layer.msg(res.error || 'Upload failed', { icon: 2 });
                    }
                },
                error: function (xhr) {
                    $('#' + containerId).find('.upload-progress').hide();
                    layer.msg('Upload failed', { icon: 2 });
                }
            });
        },

        /**
         * Render uploaded file item.
         */
        renderFileItem: function (file, containerId) {
            var $list = $('#' + containerId).find('.upload-list');
            var icon = file.original_name.match(/\.pdf$/) ? 'layui-icon-pdf' : 'layui-icon-picture';
            var html = '<div class="upload-item" style="padding:10px;margin:5px 0;background:#f8f8f8;border-radius:4px;">' +
                '<i class="layui-icon ' + icon + '"></i> ' +
                '<span>' + file.original_name + '</span> ' +
                '<span style="color:#999;">(' + this.formatSize(file.size) + ')</span> ' +
                '<input type="hidden" name="file_ids[]" value="' + file.id + '">' +
                '<i class="layui-icon layui-icon-delete" style="float:right;cursor:pointer;" onclick="$(this).parent().remove()"></i>' +
                '<div style="font-size:11px;color:#666;">SHA256: ' + file.sha256.substring(0, 16) + '...</div>' +
                '</div>';
            $list.append(html);
        },

        /**
         * Format file size.
         */
        formatSize: function (bytes) {
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
        }
    };

    // Expose for direct use
    window.layui = window.layui || {};
    window.layui.upload = upload;

    exports('upload', upload);
});