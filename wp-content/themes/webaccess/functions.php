<?php
/* Make theme available for translation */
load_theme_textdomain( 'webaccess', TEMPLATEPATH . '/languages' );

$locale = get_locale();
$locale_file = TEMPLATEPATH . "/languages/$locale.php";
if ( is_readable( $locale_file ) )
	require_once( $locale_file );
	
if ( ! isset( $content_width ) ) $content_width = 800;

add_theme_support('automatic-feed-links');

/* Add callback for custom TinyMCE editor stylesheets. */
add_editor_style('css/editor.css');

/* Register sidebar for widgets in theme */
if ( function_exists('register_sidebar') )
	register_sidebar(array(
		'name' => 'sidebar',
		'before_widget' => '<div id="%1$s" class="widget %2$s">', 
		'after_widget' => '</div>', 
		'before_title' => '<h2 class="title">', 
		'after_title' => '</h2>', 
	));
	
/* This theme uses wp_nav_menu() in one location. */
register_nav_menus( array(
	'primary' => __( 'Primary Navigation', 'webaccess' ),
) );

/* 
 * emm_paginate - WordPress Pagination Function
 * Version: 1.0
 * 
 * Copyright (c) 2009 Eric Martin http://www.ericmmartin.com/
 * Link: http://www.ericmmartin.com/pagination-function-for-wordpress
 */
include_once('functions/pagination.php');
	
/* For accessibility and legibility, the archive count must be inside the a href. */
add_filter('get_archives_link', 'archive_count_inline');
function archive_count_inline($links) {
	//$trans_posts = __('posts');
	//$trans_post = __('post', 'webaccess');
	$links = str_replace('</a>&nbsp;(', ' (', $links);
	$links = str_replace(')', ')</a>', $links);
	//$links = str_replace(')', " $trans_posts)</a>", $links);
	//$links = str_replace("1 $trans_posts)", "1 $trans_post)", $links);
	return $links;
}

/* For accessibility and legibility, the category count must be inside the a href. */
add_filter('wp_list_categories', 'cat_count_inline');
function cat_count_inline($links) {
	//$trans_posts = __('posts');
	//$trans_post = __('post', 'webaccess');
	$links = str_replace('</a> (', ' (', $links);
	$links = str_replace(')', ')</a>', $links);
	//$links = str_replace(')', " $trans_posts)</a>", $links);
	//$links = str_replace("1 $trans_posts)", "1 $trans_post)", $links);
	return $links;
}

/* Replaces wp_tag_cloud(), instead of viewing the 
 * topic_count_text_callback on hover, it is part of the a href 
 */
function bwp_tag_cloud($args = '') {
	$defaults = array(
			'smallest' => 8, 'largest' => 22, 'unit' => 'pt', 'number' => 45,
			'format' => 'flat', 'separator' => "\n", 'orderby' => 'name', 'order' => 'ASC',
			'exclude' => '', 'include' => '', 'link' => 'view', 'taxonomy' => 'post_tag', 'echo' => true
	);
	$args = wp_parse_args( $args, $defaults );
	$tags = get_terms( $args['taxonomy'], array_merge( $args, array( 'orderby' => 'count', 'order' => 'DESC' ) ) ); // Always query top tags
	if ( empty( $tags ) )
			return;
	foreach ( $tags as $key => $tag ) {
			if ( 'edit' == $args['link'] )
					$link = get_edit_tag_link( $tag->term_id, $tag->taxonomy );
			else
					$link = get_term_link( intval($tag->term_id), $tag->taxonomy );
			if ( is_wp_error( $link ) )
					return false;
			$tags[ $key ]->link = $link;
			$tags[ $key ]->id = $tag->term_id;
	}
    
    /* copy all the codes from wp_tag_cloud() here, except this line
     * $return = wp_generate_tag_cloud( $tags, $args ); 
     * change it to:
     */
    $return = bwp_generate_tag_cloud($tags, $args);
    
     $return = apply_filters( 'wp_tag_cloud', $return, $args );
        if ( 'array' == $args['format'] || empty($args['echo']) )
                return $return;
      echo $return;
}
 
function bwp_generate_tag_cloud($tags, $args = '')
{
	global $wp_rewrite;
	$defaults = array(
			'smallest' => 8, 'largest' => 22, 'unit' => 'pt', 'number' => 0,
			'format' => 'flat', 'separator' => "\n", 'orderby' => 'name', 'order' => 'ASC',
			'topic_count_text_callback' => 'default_topic_count_text',
			'topic_count_scale_callback' => 'default_topic_count_scale', 'filter' => 1,
	);
	if ( !isset( $args['topic_count_text_callback'] ) && isset( $args['single_text'] ) && isset( $args['multiple_text'] ) ) {
			$body = 'return sprintf (
					_n(' . var_export($args['single_text'], true) . ', ' . var_export($args['multiple_text'], true) . ', $count),
					number_format_i18n( $count ));';
			$args['topic_count_text_callback'] = create_function('$count', $body);
	}
	$args = wp_parse_args( $args, $defaults );
	extract( $args );
	if ( empty( $tags ) )
			return;
	$tags_sorted = apply_filters( 'tag_cloud_sort', $tags, $args );
	if ( $tags_sorted != $tags  ) { // the tags have been sorted by a plugin
			$tags = $tags_sorted;
			unset($tags_sorted);
	} else {
			if ( 'RAND' == $order ) {
					shuffle($tags);
			} else {
					// SQL cannot save you; this is a second (potentially different) sort on a subset of data.
					if ( 'name' == $orderby )
							uasort( $tags, create_function('$a, $b', 'return strnatcasecmp($a->name, $b->name);') );
					else
							uasort( $tags, create_function('$a, $b', 'return ($a->count > $b->count);') );
					if ( 'DESC' == $order )
							$tags = array_reverse( $tags, true );
			}
	}
	if ( $number > 0 )
			$tags = array_slice($tags, 0, $number);
	$counts = array();
	$real_counts = array(); // For the alt tag
	foreach ( (array) $tags as $key => $tag ) {
			$real_counts[ $key ] = $tag->count;
			$counts[ $key ] = $topic_count_scale_callback($tag->count);
	}
	$min_count = min( $counts );
	$spread = max( $counts ) - $min_count;
	if ( $spread <= 0 )
			$spread = 1;
	$font_spread = $largest - $smallest;
	if ( $font_spread < 0 )
			$font_spread = 1;
	$font_step = $font_spread / $spread;
	$a = array();
	foreach ( $tags as $key => $tag ) {
			$count = $counts[ $key ];
			$real_count = $real_counts[ $key ];
			$tag_link = '#' != $tag->link ? esc_url( $tag->link ) : '#';
			$tag_id = isset($tags[ $key ]->id) ? $tags[ $key ]->id : $key;
			$tag_name = $tags[ $key ]->name;
			$a[] = "<a href='$tag_link' class='tag-link-$tag_id' title='" . esc_attr( call_user_func( $topic_count_text_callback, $real_count ) ) . "' style='font-size: " .
					( $smallest + ( ( $count - $min_count ) * $font_step ) )	
   
    /* copy all codes except this line:
     * . "$unit;'>$tag_name</a>";
     *  change it to:
     */
    . "$unit;'>$tag_name (" . $real_count . ")</a>";
   // . "$unit;'>$tag_name (" . $topic_count_text_callback($real_count) . ")</a>";
}
	switch ( $format ) :
	case 'array' :
			$return =& $a;
			break;
	case 'list' :
			$return = "<ul class='wp-tag-cloud'>\n\t<li>";
			$return .= join( "</li>\n\t<li>", $a );
			$return .= "</li>\n</ul>\n";
			break;
	default :
			$return = join( $separator, $a );
			break;
	endswitch;
	if ( $filter )
			return apply_filters( 'wp_generate_tag_cloud', $return, $tags, $args );
	else
			return $return;    
}

/* Replaces excerpt_more(). It expresses the ellipsis with proper
 * encoding and adds the title of the article.
 * 
 * Remplace excerpt_more(). Il exprime les points de suspension avec 
 * un codage correct et ajoute le titre de l'article.
 * 
 * SGQRI 008-01 Spécification 18 d) pour un hyperlien constituant la 
 * seule façon d’accéder à une destination à partir de cette page, 
 * libeller l’hyperlien pour que sa destination puisse être déterminée 
 * hors de son contexte immédiat;
 */
function new_excerpt_more($more) {
	return ' <a href="'.get_permalink().'">[&hellip;] <span class="screen_reader">'. __('Continue reading the article', 'webaccess').'<em>'. get_the_title().'</em></span></a>';
}
add_filter('excerpt_more', 'new_excerpt_more');

/* Removes references to aria-required in the comment form, 
 * page does not validate under XHTML 1.0 Strict otherwise. 
 */
add_filter( 'comment_form_default_fields', 'custom_remove_aria_required' );
/* @author Gary Jones
 * @link http://dev.studiopress.com/remove-aria-required-attribute.htm
 *
 * @param array $args Comment form arguments
 * @return array Amended comment form arguments
 */
function custom_remove_aria_required($args) {
	$args = str_replace( " aria-required='true'", '', $args );
    return $args;
}




/* Replaces original comments_template() */
function webaccess_comment($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; ?>
   <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
     <div id="comment-<?php comment_ID(); ?>">
		  <div class="comment-author vcard">
				<!-- The h3 element must be unique and represent the current comment -->
				<h3>
				<?php
					if (function_exists('get_avatar')) {
						echo get_avatar( $comment->comment_author_email, $size = '24', $comment->comment_author_link);
					}
				?>	
				<?php _e('Comment by', 'webaccess'); echo ' '.get_comment_author_link(); ?>
				<span class="comment-meta commentmetadata">
					<?php echo __('published on', 'webaccess').' '; printf(__('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?>
				</span>  
				</h3>
		  </div>
		  
		  <?php if ($comment->comment_approved == '0') : ?>
			 <em><?php _e('Your comment is awaiting moderation.', 'webaccess') ?></em> <br />
		  <?php endif; // end comment approved ?>	
		  
		  <?php comment_text() ?>
		  
		 <?php if (is_user_logged_in()) : ?>
			<div class="edit-comment">
			<?php 
			/* Makes the a href unique and represent the current comment
			 * 
			 * Le a href est unique et représente le commentaire actuel
			 * 
			 * SGQRI 008-01 Spécification 18 d) pour un hyperlien constituant la 
			 * seule façon d’accéder à une destination à partir de cette page, 
			 * libeller l’hyperlien pour que sa destination puisse être déterminée 
			 * hors de son contexte immédiat;
			 */
			$edit_comment_txt = __('Edit comment', 'webaccess'); 
			$authorDate = ' <em class="screen_reader">'.__('by', 'webaccess').' '.get_comment_author().' '.__('published on', 'webaccess').' '.get_comment_date().' '.__('at', 'webaccess').' '.get_comment_time().'</em>';
			edit_comment_link($edit_comment_txt.$authorDate,'','');
			?>
			</div>
		 <?php endif; //end user_logged ?>
		 
		 <div class="reply">
			<?php 
			/* Makes the a href unique and represent the current comment
			 * 
			 * Le a href est unique et représente le commentaire actuel
			 * 
			 * SGQRI 008-01 Spécification 18 d) pour un hyperlien constituant la 
			 * seule façon d’accéder à une destination à partir de cette page, 
			 * libeller l’hyperlien pour que sa destination puisse être déterminée 
			 * hors de son contexte immédiat;
			 */
			comment_reply_link(array_merge( $args, array('reply_text' => __('Reply to comment', 'webaccess').$authorDate, 'depth' => $depth, 'max_depth' => $args['max_depth']))) 
			?>
		  </div>
		  
     </div><!-- end comment-ID-->
<?php
}
?>
