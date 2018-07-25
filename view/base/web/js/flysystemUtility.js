define([
    'jquery'
    ], function($) {

    return function (data) {
        window.flysystemUtility = {
            init: function() {
                $('#preview-file-btn').on('click', function() {
                    window.flysystemUtility.openPreview(data.previewUrl);
                });

                $('#preview-close-btn').on('click', function() {
                    window.flysystemUtility.closePreview();
                });

                if($('#open-wysiwyg-btn')) {
                    $('#open-wysiwyg-btn').on('click', function() {
                        MediabrowserUtility.openDialog(data.wysiwygUrl);
                    });
                }
            },

            openPreview: function(url) {
                var modal = $('#modal_dialog_message').children().first();
                var fileId = $(modal).find('[data-row=file].selected').attr('id');

                return $.ajax({
                    url: url,
                    data: {
                        filename: fileId,
                        form_key: FORM_KEY
                    }
                }).done($.proxy(function(data) {
                        console.log(data.error);
                        if(!data.error) {
                            var previewHtml = $('#flysystem-image-preview');
                            previewHtml.find('img').attr('src', data.previewUrl);
                            previewHtml.show();
                        }
                    }, this)
                );
            },

            closePreview: function() {
                var previewHtml = $('#flysystem-image-preview');
                previewHtml.hide();
            }
        };

        window.flysystemUtility.init();
    };

});