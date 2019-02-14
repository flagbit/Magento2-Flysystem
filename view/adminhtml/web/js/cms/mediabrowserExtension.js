define([
    'jquery'
], function($) {
    return function(data) {
        if($('#modal_dialog_message')) {
            var newButton = document.createElement('button');
            newButton.title = data.flysystemButtonTitle;
            newButton.type = 'button';
            newButton.innerHTML = '<span>' + data.flysystemButtonTitle + '</span>';
            newButton.classList.add('action-default');
            newButton.classList.add('scalable');
            newButton.classList.add('action-quaternary');
            newButton.on('click', function () {
                MediabrowserUtility.openDialog(data.flysystemButtonUrl)
            });


            if($('#modal_dialog_message').find('.page-action-buttons').length) {
                $('#modal_dialog_message').find('.page-action-buttons').first().prepend(newButton);
            } else if($('#modal_dialog_message').find('.insert-actions').length) {
                $('#modal_dialog_message').find('.insert-actions').first().prepend(newButton);
            }
        }
    }
});