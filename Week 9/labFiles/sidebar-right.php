<?php

/**

 * The right sidebar template

 *

 *

 * @package Customizr

 * @since Customizr 3.1.0

 */

/*
if ( apply_filters( 'czr_ms', true ) ) {

  do_action( 'czr_ms_tmpl', 'sidebar-right' );

  return;

}
*/

dynamic_sidebar( 'right' );
?>

<!-- Start Changes -->
<?php 
    $args= array ('category_name' => 'WordPress');
    $wp = new WP_Query($args);

    if ($wp -> have_posts()) : while ($wp -> have_posts()) : $wp -> the_post();
?>

    <a 
        href="<?php the_permalink(); ?>"
        title="<?php the_title(); ?>"
        excerpt="<?php the_excerpt(); ?>">
        <?php the_title(); ?><?php the_time('M,Y'); ?><?php the_excerpt(); ?>
    </a>

    <?php wp_reset_postdata(); ?>

<?php endwhile; else: ?>
    <p>That's it, no more</p>

<?php endif; ?>
<!-- End Changes -->