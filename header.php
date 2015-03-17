<?php
$theme_settings = get_option( 'nextbird-settings' );
?><!DOCTYPE html>
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" >
    <meta name="viewport" content="width=device-width">
    <title><?php wp_title( '|', true, 'right' ); ?></title>
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

    <link href='//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Italianno' rel='stylesheet' type='text/css'>
    <?php // Loads HTML5 JavaScript file to add support for HTML5 elements in older IE versions. ?>

    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div class="container-fluid">

    <!-- Begin template wrapper -->
    <div id="wrapper" class="row">
        <div class="col-xs-12 <?php if( !is_home() && !is_front_page() ) : ?>col-sm-3 col-lg-2<?php endif;?> top_bar">
            <div class="row">
                <div class="col-sm-12 col-xs-9">
                    <a id="logo" class="logo_wrapper" href="<?php echo home_url(); ?>">
                        <?php echo wp_get_attachment_image( get_attachment_id_from_url($theme_settings['fp_logo']), array(150,150) ); ?>
                        <?php if( is_home() || is_front_page() ) {
                            echo wp_get_attachment_image( get_attachment_id_from_url($theme_settings['fp_long_logo']), array(999,150) );
                        } ?>
                    </a>
                </div>
                <div class="visible-xs pull-right">
                    <a id="menu_close" data-toggle="collapse" data-target="#menu_wrapper" href="javascript:;" title="Schließen"><?php _e( '&times;', THEMEDOMAIN ); ?></a>
                </div>
            </div>

            <div id="menu_wrapper" class="row collapse navbar-collapse">
                <!-- Begin main nav -->
                <div id="nav_wrapper">
                    <nav>
                        <?php
                        //Get page nav
                        wp_nav_menu(
                            array(
                                'theme_location' 	=> 'primary-menu',
                                'menu_id'			=> 'main_menu',
                                'menu_class'		=> 'nav',
                            )
                        );
                        ?>
                    </nav>
                </div>
                <!-- End main nav -->

            </div>
        </div>