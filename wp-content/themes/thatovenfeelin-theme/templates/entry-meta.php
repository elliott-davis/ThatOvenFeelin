<div class="standout clearfix">
  <div class="col-md-6">
    <i class="fa fa-clock-o"></i><time class="updated" datetime="<?php echo get_the_time('c'); ?>"><?php echo get_the_date(); ?></time>
  </div>
  <div class="col-md-6">
    <i class="fa fa-star"></i><?php the_category(', '); ?>
  </div>
</div>
