    <footer class="site-footer">
        <div class="container">
            <ul class="footer-nav-list flex">
                <?php wp_nav_menu(['container' => true, 'items_wrap' => '%3$s', 'theme_location' => 'footer-menu', 'fallback_cb' => false]); ?>
            </ul>
<br>
            <div class="Copyright flex">
                <span>Copyright © 2024 <a href="https://www.qichiyu.com">七尺宇</a> All Rights Reserved.</span>
            </div>
        </div>
    </footer>
    <div class="mask"></div>
    <div id="goTop" class="hoverBg"><i class="iconfont icon-top-line"></i><em>回到顶部</em></div>
    <?php wp_footer();?>
</body>
</html>
