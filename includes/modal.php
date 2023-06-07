<?php

function fetchModalContent() {
    $modalID = $_POST['modalID'];

    $query = new WP_Query(array(
        'post_type' => 'modals',
        'p' => $modalID
    ));

    if ( $query->found_posts === 0 ) {
        $error = new WP_Error( 'not_found', 'Modal with ID ' . $modalID . ' not found.' );
        wp_send_json_error( $error );
    }

    $modal = $query->posts[0];
    $content = get_field( 'modal_content', $modal->ID );

    wp_send_json_success(array(
        'modalID' => $modalID,
        'html' => $content
    ));
}
add_action( 'wp_ajax_nopriv_fetch_modal_content', 'fetchModalContent' );
add_action( 'wp_ajax_fetch_modal_content', 'fetchModalContent' );