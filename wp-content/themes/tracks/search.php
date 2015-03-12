<?php get_header(); ?>

<div class="entry">
    <div class="entry-container">

        <h1 class="entry-title">
            <?php
            global $wp_query;
            $total_results = $wp_query->found_posts;
            if($total_results) {
                printf(__('%d результаты поиска','tracks'),$total_results);
            } else {
                _e("Ничего не найдено для ");
            }
            ?>
            <span>"<?php echo $s ?>"</span>
        </h1>
        <?php get_search_form(); ?>

            <?php
            // The loop
            if ( have_posts() ) :
                while (have_posts() ) :
                    the_post();
                    get_template_part( 'content-search' );
                endwhile;
            endif;
            ?>

        <?php if ( current_theme_supports( 'loop-pagination' ) ) loop_pagination(); ?>

        <div class="search-bottom">
            <p>К сожалению, по вашему запросу ничего не найдено, попробуйте другой запрос:</p>
            <?php get_search_form(); ?>
        </div>

    </div>
</div>

<?php get_footer(); ?>