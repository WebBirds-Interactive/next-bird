<?php
// general functions
require_once('lib/functions.php');

if (is_admin()){
    // stuff only needed for dashboard
    require_once('lib/backend/theme-backend.php');
    require_once('lib/backend/custom-metabox.php');
} else {
    //stuff only needed on Frontend
    require_once('lib/theme.php');
}



if ( function_exists( 'add_image_size' ) ) {
    add_image_size( 'gallery-stripes', 440, 250, true ); // (cropped)
}
add_filter('image_size_names_choose', 'my_image_sizes');
function my_image_sizes($sizes) {
    $addsizes = array(
        "gallery-stripes" => __( "Galerie Bilder")
    );
$newsizes = array_merge($sizes, $addsizes);
return $newsizes;
}