<div class='entry'>
	<div class='entry-header'>
            <h1 class='entry-title'><?php the_title(); ?></h1>
    </div>
    <div class="entry-container">
        <div class='entry-content'>
            <article>
                <?php the_content(); ?>
                <?php wp_link_pages(array('before' => '<p class="singular-pagination">' . __('Страницы:','tracks'), 'after' => '</p>', ) ); ?>
            </article>
        </div>
    </div>
</div>