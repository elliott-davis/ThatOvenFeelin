<?php get_template_part('templates/page', 'header'); ?>
<?php
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$args = array(
  'category_name' => single_cat_title( '', false ),
  'posts_per_page' => 32,
  'paged' => $paged
);

query_posts($args);
?>
<div class="clearfix">
<?php if (!have_posts()) : ?>
  <div class="alert alert-warning">
    <?php _e('Sorry, no results were found.', 'roots'); ?>
  </div>
  <?php get_search_form(); ?>
<?php endif; ?>

<?php while (have_posts()) : the_post(); ?>
  <?php get_template_part('templates/content-archive', get_post_format()); ?>
<?php endwhile; ?>
</div>
<?php if ($wp_query->max_num_pages > 1) : ?>
  <div class="pagination pull-right">
    <?php echo paginate_links( $args ); ?>
  </div>
<?php endif; ?>
