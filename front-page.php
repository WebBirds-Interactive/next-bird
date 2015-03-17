<?php get_header(); ?>

    <?php $bird_opions = get_option( 'nextbird-settings' ); ?>

    <div class="front-page-teaser">
        <h1>
            <?php if ( $bird_opions['fp_headline'] ) echo $bird_opions['fp_headline']; ?>
        </h1>
        <p>
            <?php if ( $bird_opions['fp_teaser'] ) echo $bird_opions['fp_teaser']; ?>
        </p>
    </div>

<?php get_footer();