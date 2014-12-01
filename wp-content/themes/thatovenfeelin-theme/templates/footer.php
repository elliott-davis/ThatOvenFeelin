<footer class="content-info" role="contentinfo">
  <div class="container">
    <?php
      if (has_nav_menu('footer_navigation')) :
        wp_nav_menu(array('theme_location' => 'footer_navigation', 'menu_class' => 'footernav'));
      endif;
    ?>
    <div class="copyright pull-right">©2013-2014 That Oven Feelin'<br>
    </div>
  </div>
</footer>
