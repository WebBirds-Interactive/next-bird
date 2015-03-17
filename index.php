<?php get_header();

if ( have_posts() ) {
    while ( have_posts() ) {
        the_post();
         ?>

        <article class="col-lg-6 col-sm-7 col-xs-12">
            <header>
                <h1><?php the_title(); ?></h1>
            </header>

            <div class="content">
                <?php the_content(); ?>
            </div>
            <div class="social-media">
                <a href="https://www.facebook.com/pages/Hochzeitsfotografie-Kade/102540886533876?fref=ts">
                    <img src="http://hochzeitsfotografie-ka.de/wp-content/uploads/2014/08/facebook.png"/>
                </a>
            </div>
        </article>

    <?php
    } // end while
} // end if

get_footer();