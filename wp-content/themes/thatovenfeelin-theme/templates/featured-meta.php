<div class="postmeta">
  <?php if(get_post_meta($post->ID, 'prep_time', true)):  ?><div><i class="fa fa-clock-o"></i><?php echo get_post_meta($post->ID, 'prep_time', true); ?></div><?php endif; ?>
  <?php if(get_post_meta($post->ID, 'primary_category', true)):  ?><div><i class="fa fa-tag"></i><?php echo get_post_meta($post->ID, 'primary_category', true); ?></div><?php endif; ?>
</div>
