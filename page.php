<?php get_header();?>
    <main class="site-content container">
        <article class="post-content">
            <div class="post-header">
               <h1 class="post-title"><?php the_title(); ?></h1>
            </div>
            <div class="post-main">
            <?php while( have_posts() ): the_post(); ?>
            <?php the_content();?>
            <?php endwhile; ?>
            </div>
        </article>
        <?php if ( comments_open() || get_comments_number() ) :?>
        <?php comments_template();?>
        <?php endif;?>
    </main>
    <script>hljs.initHighlightingOnLoad();hljs.initLineNumbersOnLoad();function highlightjs(){$("code.hljs").each(function(i,block){hljs.lineNumbersBlock(block)})}document.addEventListener("highlightjs",highlightjs,false);</script>
    <script>document.addEventListener('DOMContentLoaded', event => typeof hljs !== 'undefined' && (hljs.initHighlighting(), hljs.initLineNumbersOnLoad()));</script>
<?php get_footer();