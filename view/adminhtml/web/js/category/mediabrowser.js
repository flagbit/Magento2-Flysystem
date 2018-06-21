define([
    'Magento_Ui/js/form/element/abstract',
    'uiRegistry'
], function (Element, reg) {
    'use strict';

    return Element.extend({
        defaults: {
            listens: {
                value: 'changeValue'
            }
        },

        flysystemvalue: '',

        changeValue: function(value) {
            if(value && value!=this.flysystemvalue) {
                this.flysystemvalue = value;

                var uploader = reg.get('category_form.category_form.content.image');
                uploader.addFile(JSON.parse(value));
            }
        }
    });

});
