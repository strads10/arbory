CKEDITOR.plugins.add('imagezoom', {
    icons: 'zoom',
    init: function (editor) {
        editor.addCommand('zoom', {
            exec: function (editor) {
                if (editor.getSelection().getStartElement().hasClass('zoom')) {
                    $('.cke_button__imagezoom').removeClass('cke_button_on').addClass('cke_button_off');
                    editor.getSelection().getStartElement().removeClass('zoom');
                } else {
                    $('.cke_button__imagezoom').removeClass('cke_button_off').addClass('cke_button_on');
                    editor.getSelection().getStartElement().addClass('zoom');
                }
            }
        });
        editor.ui.addButton('ImageZoom', {
            icon: 'zoom',
            label: 'Zoom Image',
            command: 'zoom',
            toolbar: 'imagezoom'
        });

        editor.on('selectionChange', function (evt) {
            var landscapeButton = this.getCommand('zoom');

            if (evt.data.path.lastElement.is('img') || evt.data.path.lastElement.hasClass('zoom')) {
                $('.cke_button__imagezoom').removeClass('cke_button_off').addClass('cke_button_on');
                landscapeButton.enable();
            } else {
                $('.cke_button__imagezoom').removeClass('cke_button_on').addClass('cke_button_off');
                landscapeButton.disable();
            }
        });
    }
});
