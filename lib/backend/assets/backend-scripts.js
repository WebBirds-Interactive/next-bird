jQuery(document).ready(function($){
    var custom_uploader;
    var wrap = $('.wrap');
    var self;

    wrap.on('click', '.upload',function(e) {
        self = $(this);

        e.preventDefault();

        //If the uploader object has already been created, reopen the dialog
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }

        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });

        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on('select', function() {
            var attachment = custom_uploader.state().get('selection').first().toJSON();

            self.parent().find('.file').val(attachment.url);
            self.parent().find('img').remove();
            self = '';
        });

        //Open the uploader dialog
        custom_uploader.open();
    });

    wrap.on('click', '.add_repeater', function(){
        var template = $('.uploader_template').html();
        $('.repeating').append(template.replace('[X]', '[]'));
    });
    wrap.on('click', '.remove_repeater', function(){
        if (confirm('Wirklich löschen?'))
            $(this).parent().remove();
    });
});
