<?php
/**
 * Standard ultimate posts widget template
 *
 * @version     2.0.0
 */
?>

<?php if ($instance['before_posts']) : ?>
  <div class="upw-before">
    <?php echo wpautop($instance['before_posts']); ?>
  </div>
<?php endif; ?>

<div class="featuredposts row">

  <?php if ($upw_query->have_posts()) : ?>

      <?php while ($upw_query->have_posts()) : $upw_query->the_post(); ?>

        <?php $current_post = ($post->ID == $current_post_id && is_single()) ? 'active' : ''; ?>

        <div class='teaser-post col-xs-4'>
          <div class="post-thumb">
            <?php get_template_part('templates/featured-meta'); ?>
            <a href="<?php the_permalink(); ?>" rel="bookmark">
              <?php the_post_thumbnail($instance['thumb_size']); ?>
              <div class="caption hidden-xs">
                <h3 class="posttitle">
                  <?php the_title(); ?>
                </h3>
              </div>
            </a>
          </div>
        </div>

      <?php endwhile; ?>
  <?php else : ?>

    <p class="upw-not-found">
      <?php _e('No posts found.', 'upw'); ?>
    </p>

  <?php endif; ?>

</div>

<?php if ($instance['after_posts']) : ?>
  <div class="upw-after">
    <?php echo wpautop($instance['after_posts']); ?>
  </div>
<?php endif; ?>
