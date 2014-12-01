<article <?php post_class('col-sm-6 teaser-post'); ?>>
  <div class="post-thumb">
  <?php if ( has_post_thumbnail() ) : ?>
    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
      <?php the_post_thumbnail('featured'); ?>
      <div class="caption">
        <h3 class="posttitle"><?php the_title(); ?></h3>
        <!--div class="postmeta"><?php get_template_part('templates/entry-meta'); ?></div-->
      </div>
    </a>
  </div>
  <?php endif; ?>
  <div class="excerpt">
    <?php the_excerpt(); ?>
    <div class="more">
      <a class="more-link" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">Read More →</a>
    </div>
  </div>
</article>
