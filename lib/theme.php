<?php

add_theme_support( 'html5' );
add_theme_support( 'custom-header' );
add_theme_support( 'custom-background' );
add_theme_support( 'post-thumbnails' );


/**
 * Proper way to enqueue scripts and styles
 */
add_action( 'wp_enqueue_scripts', 'theme_name_scripts' );
function theme_name_scripts() {
    wp_enqueue_style('bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css' , null, '3.2.0');
    wp_enqueue_style('main', get_template_directory_uri() . '/assets/css/style.css' , 'bootstrap-css', 0.1);
    wp_enqueue_style('supersized', get_template_directory_uri() . '/assets/css/supersized.css' , 'main-css', '3.2.7');

    wp_enqueue_script('bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.min.js' , array( 'jquery' ), '3.2.0', true);
    wp_enqueue_script('supersized', get_template_directory_uri() . '/assets/js/supersized.3.2.7.min.js' , array( 'jquery' ), '3.2.7', true);
}

add_action( 'wp_footer', 'add_supersized_script',99);
function add_supersized_script() {
    $options = get_option( 'nextbird-settings' );

    $slide_interval = ( isset($options['super_interval']) )? $options['super_slide_interval'] : 10000; //default to 10sec
    $transition = ( isset($options['super_transition']) )? $options['super_transition'] : 1; //default to fade
    $transition_speed = ( isset($options['super_speed']) )? $options['super_speed'] : 700; //default to 700ms
    $images = generate_supersized_image_code()
     ?>

    <script>
        jQuery(function($){

            $.supersized({
                 // Functionality
                 slide_interval   : <?php echo $slide_interval ?>,
                 transition       : <?php echo $transition ?>,
                 transition_speed : <?php echo $transition_speed ?>,
                 slide_links      : 'false',
                 slides           : [
                     <?php echo $images ?>
                 ]
            });
        });
    </script>
    <?
}

function generate_supersized_image_code(){
    $code = '';
    if ( ! is_front_page() ){
        $options['fp_galery'] = unserialize( get_post_meta( get_the_ID(), '_page-gallery', true ) );
    } else {
        $options = get_option( 'nextbird-settings' );
    }

    if ( empty( $options['fp_galery'] ) ){
        $options = get_option( 'nextbird-settings' );
    }

    if( ! is_array( $options['fp_galery'] ) )
        $options['fp_galery'] = unserialize($options['fp_galery']);

    if( ! is_array( $options['fp_galery'] ) )
        return '';

    $images = $options['fp_galery'];

    foreach($images as $image){
        $code .= "{image : '$image', title : '', thumb : '', url : ''},\n";
    }
    return rtrim($code,",\n");
}

