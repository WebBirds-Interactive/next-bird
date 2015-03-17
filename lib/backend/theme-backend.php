<?php
class MySettingsPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );

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
     * Add options page
     */
    public function add_plugin_page()
    {
        wp_enqueue_media();
        // This page will be under "Settings"
        add_theme_page(
            __('Theme Setting'),
            __('Theme Settings'),
            'manage_options',
            'nextbird-options',
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'nextbird-settings' );
        ?>
        <div class="wrap">
            <h2>Theme Settings</h2>
            <form method="post" action="options.php">
                <?php
                // This prints out all hidden setting fields
                settings_fields( 'my_option_group' );
                do_settings_sections( 'nextbird-options' );
                submit_button();
                ?>
            </form>
        </div>
    <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {
        register_setting(
            'my_option_group', // Option group
            'nextbird-settings', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_fp', // ID
            'Frontpage Settings', // Title
            array( $this, 'print_section_info' ), // Callback
            'nextbird-options' // Page
        );

        add_settings_field(
            'fp_headline', // ID
            'Headline', // Title
            array( $this, 'single_line_callback' ), // Callback
            'nextbird-options', // Page
            'setting_section_fp', // Section
            array('key' => 'fp_headline')
        );

        add_settings_field(
            'fp_teaser',
            'Teaser Text',
            array( $this, 'multi_line_callback' ),
            'nextbird-options',
            'setting_section_fp', // Section
            array('key' => 'fp_teaser')
        );

        add_settings_field(
            'fp_logo',
            'Logo',
            array( $this, 'image_upload_callback' ),
            'nextbird-options',
            'setting_section_fp', // Section
            array('key' => 'fp_logo')
        );

        add_settings_field(
            'fp_long_logo',
            'Bigger Logo',
            array( $this, 'image_upload_callback' ),
            'nextbird-options',
            'setting_section_fp', // Section
            array('key' => 'fp_long_logo')
        );

        add_settings_field(
            'fp_galery',
            'Fullscreen Gallery',
            array( $this, 'repeating_upload_callback' ),
            'nextbird-options',
            'setting_section_fp', // Section
            array('key' => 'fp_galery')
        );
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     * @return array $new_input
     */
    public function sanitize( $input )
    {
        $new_input = array();
        foreach( $input as $key => $val ){
            if ( is_array( $val )){
                if ( isset($val['X']) ){
                    unset($val['X']);
                }
                $new_val = array();
                foreach($val as $v){
                    if (! empty($v) ){
                        $new_val[] = $v;
                    }
                }

                $new_input[$key] = serialize($new_val);
            } else {
                $new_input[$key] = sanitize_text_field( $val );
            }
        }

        return $new_input;
    }

    /**
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Enter your settings below:';
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function single_line_callback($args)
    {
        printf(
            '<input type="text" id="%s" name="nextbird-settings[%s]" value="%s" class="regular-text ltr">',
            $args['key'], $args['key'],
            isset( $this->options[$args['key']] ) ? esc_attr( $this->options[$args['key']]) : ''
        );
    }
    /**
     * Get the settings option array and print one of its values
     */
    public function image_upload_callback($args)
    {
        if( !empty( $this->options[$args['key']] ) )
            echo '<img src="' .  $this->options[$args['key']] . '" style="width:auto;max-height:150px;"><br>';
        printf(
            '<p class="upload_form"><input type="text" name="nextbird-settings[%s]" value="%s" class="regular-text code file">
            <input type="button" class="button upload" value="Upload"></p>',
            $args['key'],
            isset( $this->options[$args['key']] ) ? esc_attr( $this->options[$args['key']]) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function multi_line_callback($args)
    {
        printf(
            '<textarea id="%s" name="nextbird-settings[%s]" cols="3" class="large-text code">%s</textarea>',
            $args['key'], $args['key'],
            isset( $this->options[$args['key']] ) ? esc_attr( $this->options[$args['key']]) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function repeating_upload_callback($args)
    {
        echo '<div class="hidden uploader_template">';
        printf(
            '<p class="upload_form"><input type="button" class="remove_repeater button warning" value="x">
            <input type="text" name="nextbird-settings[%s][X]" value="" class="regular-text code file">
            <input type="button" class="button upload" value="Upload"></p>',
            $args['key']
        );
        echo '</div>';
        echo '<div class="repeating">';

        $repeaters = unserialize((! empty( $this->options[$args['key']] ) ) ? $this->options[$args['key']] : serialize(array('')) );
        foreach ( $repeaters as $repeater ){
            printf(
                '<p class="upload_form"><input type="button" class="remove_repeater button warning" value="x">
                <input type="text" name="nextbird-settings[%s][]" value="%s" class="regular-text code file">
                %s
                <input type="button" class="button upload" value="Upload"></p>',
                $args['key'],
                esc_attr( $repeater ),
                wp_get_attachment_image(get_attachment_id_from_url(esc_attr( $repeater )),array(32,32))
            );
        }
        echo '</div>';
        echo '<input type="button" value="+" class="add_repeater button">';
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function title_callback()
    {
        printf(
            '<input type="text" id="title" name="nextbird-settings[title]" value="%s" />',
            isset( $this->options['title'] ) ? esc_attr( $this->options['title']) : ''
        );
    }
}

$my_settings_page = new MySettingsPage();

/*
 *  Setup main navigation menu
 */
add_action( 'init', 'register_my_menu' );
function register_my_menu() {
    if ( function_exists('register_nav_menus') ) {
        register_nav_menus(array(
            'primary-menu' => __( 'Hauptnavigation' )
        ));
    }
}
