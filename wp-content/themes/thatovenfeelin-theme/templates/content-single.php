<?php while (have_posts()) : the_post(); ?>
  <article <?php post_class(); ?>>
    <header>
      <h2 class="entry-title"><span><?php the_title(); ?></span></h2>
      <?php get_template_part('templates/entry-meta'); ?>
    </header>
    <div class="entry-content">
      <?php the_content(); ?>
    </div>
    <div style="text-align:center">
      <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
      <!-- Medium Rectangle 4 -->
      <ins class="adsbygoogle"
      style="display:inline-block;width:300px;height:250px"
      data-ad-client="ca-pub-9330994516507784"
      data-ad-slot="2793669759"></ins>
      <script>
      (adsbygoogle = window.adsbygoogle || []).push({});
      </script>
    </div>
    <footer>
      <?php wp_link_pages(array('before' => '<nav class="page-nav"><p>' . __('Pages:', 'roots'), 'after' => '</p></nav>')); ?>
    </footer>
    <?php comments_template('/templates/comments.php'); ?>
  </article>
<?php endwhile; ?>
