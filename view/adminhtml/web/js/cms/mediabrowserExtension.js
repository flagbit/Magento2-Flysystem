define([
    'jquery'
], function($) {
    return function(data) {
        if($('#modal_dialog_message')) {
            var newButton = document.createElement('button');
            newButton.title = data.flysystemButtonTitle;
            newButton.type = 'button';
            newButton.innerHTML = '<span>' + data.flysystemButtonTitle + '</span>';
            newButton.on('click', function () {
                MediabrowserUtility.openDialog(data.flysystemButtonUrl)
            });

            $('#modal_dialog_message').find('.insert-actions').first().prepend(newButton);
        }
    }
});