jQuery(document).ready(function(){

    /* Register the buttons */
    tinymce.create('tinymce.plugins.RRratingButtons', {
        init : function(ed, url) {
            /**
             * Inserts shortcode content
             */
            ed.addButton( 'button_green', {
                title : 'Insert RunRepeat Rating Solution shortcode',
                image : rr_plugin.url + '/img/rr_rating_sc.png',
                onclick : function() {
                    ed.selection.setContent('[runrepeatratings]');
                }
            });

        },
        createControl : function(n, cm) {
            return null;
        },
    });
    /* Start the buttons */
    tinymce.PluginManager.add( 'my_button_script', tinymce.plugins.RRratingButtons );

})
