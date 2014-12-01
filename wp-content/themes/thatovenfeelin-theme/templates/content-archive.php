<article <?php post_class('col-sm-3 archive-post'); ?>>
  <div class="post-thumb">
  <?php if ( has_post_thumbnail() ) : ?>
    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
      <?php the_post_thumbnail('thumbnail'); ?>
      <div class="caption">
        <h3 class="posttitle"><?php the_title(); ?></h3>
      </div>
    </a>
  </div>
  <?php endif; ?>
</article>
