<?php

/**
 * Calls the class on the post edit screen.
 */
function call_someClass() {
    new someClass();
}

if ( is_admin() ) {
    add_action( 'load-post.php', 'call_someClass' );
    add_action( 'load-post-new.php', 'call_someClass' );
}

/**
 * The Class.
 */
class someClass {

    /**
     * Hook into the appropriate actions when the class is constructed.
     */
    public function __construct() {
        add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
        add_action( 'save_post', array( $this, 'save' ) );

        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_custom_admin_scripts' ) );
    }

    /**
     * Enqueue the date picker
     */
    public function enqueue_custom_admin_scripts(){
        wp_enqueue_media();

        wp_enqueue_script(
            'backend-js',
            get_template_directory_uri( __FILE__ ) . '/lib/backend/assets/backend-scripts.js',
            array('jquery', 'jquery-ui-core', 'jquery-ui-datepicker'),
            1.0,
            true
        );
    }

    /**
     * Adds the meta box container.
     */
    public function add_meta_box( $post_type ) {
        $post_types = array('post', 'page');     //limit meta box to certain post types
        if ( in_array( $post_type, $post_types )) {
            add_meta_box(
                'page-gallery'
                ,__( 'Page specific background Gallery', 'myplugin_textdomain' )
                ,array( $this, 'render_meta_box_content' )
                ,$post_type
                ,'advanced'
                ,'high'
            );
        }
    }

    /**
     * Save the meta when the post is saved.
     *
     * @param int $post_id The ID of the post being saved.
     */
    public function save( $post_id ) {

        /*
         * We need to verify this came from the our screen and with proper authorization,
         * because save_post can be triggered at other times.
         */

        // Check if our nonce is set.
        if ( ! isset( $_POST['myplugin_inner_custom_box_nonce'] ) )
            return $post_id;

        $nonce = $_POST['myplugin_inner_custom_box_nonce'];

        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce, 'myplugin_inner_custom_box' ) )
            return $post_id;

        // If this is an autosave, our form has not been submitted,
        //     so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
            return $post_id;

        // Check the user's permissions.
        if ( 'page' == $_POST['post_type'] ) {

            if ( ! current_user_can( 'edit_page', $post_id ) )
                return $post_id;

        } else {

            if ( ! current_user_can( 'edit_post', $post_id ) )
                return $post_id;
        }

        /* OK, its safe for us to save the data now. */

        // Sanitize the user input.
        $mydata = $_POST['page-gallery'];
        if ( isset($mydata['X']) ){
            unset($mydata['X']);
        }
        $new_val = array();
        foreach($mydata as $v){
            if (! empty($v) ){
                $new_val[] = $v;
            }
        }

        $new_input = serialize($new_val);

        // Update the meta field.
        update_post_meta( $post_id, '_page-gallery', $new_input );
    }


    /**
     * Render Meta Box content.
     *
     * @param WP_Post $post The post object.
     */
    public function render_meta_box_content( $post ) {

        // Add an nonce field so we can check for it later.
        wp_nonce_field( 'myplugin_inner_custom_box', 'myplugin_inner_custom_box_nonce' );

        // Use get_post_meta to retrieve an existing value from the database.
        $values = get_post_meta( $post->ID, '_page-gallery', true );

        // Display the form, using the current value.
        echo '<div class="hidden uploader_template">';
        echo '<p class="upload_form"><input type="button" class="remove_repeater button warning" value="x">
            <input type="text" name="page-gallery[X]" value="" class="regular-text code file">
            <input type="button" class="button upload" value="Upload"></p>';
        echo '</div>';
        echo '<div class="repeating">';

        $values = unserialize((! empty( $values ) ) ? $values : serialize(array('')) );
        foreach ( $values as $value ){
            printf(
                '<p class="upload_form"><input type="button" class="remove_repeater button warning" value="x">
                <input type="text" name="page-gallery[]" value="%s" class="regular-text code file">
                %s
                <input type="button" class="button upload" value="Upload"></p>',
                esc_attr( $value ),
                wp_get_attachment_image(get_attachment_id_from_url(esc_attr( $value )),array(32,32))
            );
        }
        echo '</div>';
        echo '<input type="button" value="+" class="add_repeater button">';
    }
}