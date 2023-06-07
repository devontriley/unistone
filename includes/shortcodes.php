<?php

/** 
 * Removes empty paragraph tags from shortcodes in WordPress.
 * https://thomasgriffin.com/how-to-remove-empty-paragraph-tags-from-shortcodes-in-wordpress/
 */
function tg_remove_empty_paragraph_tags_from_shortcodes_wordpress( $content ) {
    $toFix = array( 
        '<p>['    => '[', 
        ']</p>'   => ']', 
        ']<br />' => ']'
    ); 
    return strtr( $content, $toFix );
}
add_filter( 'the_content', 'tg_remove_empty_paragraph_tags_from_shortcodes_wordpress' );


function bootstrap_row($atts, $content = null) {
	return '<div class="row">'. do_shortcode($content) .'</div>';
}
add_shortcode('row', 'bootstrap_row');

function bootstrap_column($atts, $content = null) {
    $a = shortcode_atts(array(
        'classes' => 'col'
    ), $atts);
	return '<div class="'. esc_attr($a['classes']) .'">'. do_shortcode($content) .'</div>';
}
add_shortcode('col', 'bootstrap_column');