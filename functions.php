<?php

// Theme support
if (function_exists('add_theme_support')) {
    add_theme_support( 'post-thumbnails' );
}

// Remove default content editor
add_action('admin_init', 'remove_textarea');
function remove_textarea() {
    remove_post_type_support( 'page', 'editor' );
}

// Disable Gutenberg
add_filter('use_block_editor_for_post', '__return_false', 10);

add_action( 'wp_enqueue_scripts', function() {
    // Remove CSS on the front end.
    wp_dequeue_style( 'wp-block-library' );
    // Remove Gutenberg theme.
    wp_dequeue_style( 'wp-block-library-theme' );
    // Remove inline global CSS on the front end.
    wp_dequeue_style( 'global-styles' );
}, 20 );

// Allow SVG uploads
function cc_mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');

// Register styles
if (!function_exists('usi_styles')) :
    function usi_styles() {
        wp_register_style( 'usi-style', get_stylesheet_uri(), array(), time() );
        wp_enqueue_style( 'usi-style' );

    }
endif;
add_action( 'wp_enqueue_scripts', 'usi_styles' );
//add_action( 'admin_enqueue_scripts', 'usi_styles' );

// Register scripts
if (!function_exists('usi_scripts')) :
    function usi_scripts()
    {
        wp_register_script('google-recaptcha', 'https://www.google.com/recaptcha/api.js?render=6Leny8chAAAAAG9VA9cEjcPpBrtrxfzBfMouIb2o', array('jquery'), null, true);
        wp_enqueue_script('google-recaptcha');

        wp_register_script( 'usi-script', get_template_directory_uri().'/main.js', array('jquery'), '1.0', true );
        wp_enqueue_script( 'usi-script' );
        wp_localize_script('usi-script', 'localizedVars', array(
            'pageID' => get_the_ID()
        ));

        // Password strength for registration form
        wp_enqueue_script( 'password-strength-meter' );

    }
endif;
add_action( 'wp_enqueue_scripts', 'usi_scripts' );

// Register menus
register_nav_menus(
    array(
        'primary-menu' => __( 'Primary Menu' )
    )
);

// Include shortcodes
include('includes/shortcodes.php');

// Hide admin toolbar when logged in
function usi_remove_admin_bar() {
    if (!current_user_can('administrator') && !is_admin()) {
        show_admin_bar(false);
    }
}
add_action('after_setup_theme', 'usi_remove_admin_bar');

//Disable the new user notification sent to the site admin
function usi_disable_new_user_notifications()
{
    //Remove original use created emails
    remove_action('register_new_user', 'usi_send_new_user_notifications');
    remove_action('edit_user_created_user', 'usi_send_new_user_notifications', 10, 2);

    // Remove password changed notifications
    remove_action( 'after_password_reset', 'wp_password_change_notification' );

    //Add new function to take over email creation
    add_action('register_new_user', 'usi_send_new_user_notifications');
    add_action('edit_user_created_user', 'usi_send_new_user_notifications', 10, 2);
}
add_action('init', 'usi_disable_new_user_notifications');

function usi_send_new_user_notifications($user_id, $notify = 'user')
{
    if (empty($notify) || $notify == 'admin') {
        return;
    } elseif ($notify == 'both') {
        //Only send the new user their email, not the admin
        $notify = 'user';
    }
    wp_send_new_user_notifications($user_id, $notify);
}

// Register custom post types
function register_custom_post_types() {
    register_post_type('products',
        array(
            'labels'      => array(
                'name'          => 'Products',
                'singular_name' => 'Product'
            ),
            'menu_icon' => 'dashicons-cart',
            'public'      => true,
            'publicly_queryable' => false,
            'has_archive' => false,
            'taxonomies' => array('color'),
            'hierarchical' => true,
            'supports' => array('title', 'author', 'excerpt', 'thumbnail')
        )
    );

    register_post_type('modals',
        array(
            'labels'      => array(
                'name'          => 'Modals',
                'singular_name' => 'Modal'
            ),
            'menu_icon' => 'dashicons-fullscreen-alt',
            'public'      => true,
            'publicly_queryable' => false,
            'has_archive' => false,
            'hierarchical' => false,
            'supports' => array('title')
        )
    );
}
add_action('init', 'register_custom_post_types');

// Register custom taxonomies
function register_custom_taxonomies() {
    $labels = array(
        'name'              => 'Colors',
        'singular_name'     => 'Color',
        'search_items'      => 'Search Colors',
        'all_items'         => 'All Colors',
        'parent_item'       => 'Parent Colors',
        'parent_item_colon' => 'Parent Colors:',
        'edit_item'         => 'Edit Color',
        'update_item'       => 'Update Color',
        'add_new_item'      => 'Add New Color',
        'new_item_name'     => 'New Color Name',
        'menu_name'         => 'Colors',
    );
    $args   = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => [ 'slug' => 'color' ],
    );
    register_taxonomy( 'color', [ 'products' ], $args );


    $labels = array(
        'name'              => 'Product Types',
        'singular_name'     => 'Product Type',
        'search_items'      => 'Search Product Types',
        'all_items'         => 'All Product Types',
        'parent_item'       => 'Parent Product Types',
        'parent_item_colon' => 'Parent Product Types:',
        'edit_item'         => 'Edit Product Type',
        'update_item'       => 'Update Product Type',
        'add_new_item'      => 'Add New Product Type',
        'new_item_name'     => 'New Product Type',
        'menu_name'         => 'Product Types',
    );
    $args   = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => [ 'slug' => 'product-types' ],
    );
    register_taxonomy( 'product-type', [ 'products' ], $args );
}
add_action( 'init', 'register_custom_taxonomies' );

// Add Admin Product Types filter
function products_taxonomy_filter($post_type) {
    if( $post_type == 'products' ) {
        $taxonomy_names = array('product-type');
        foreach ($taxonomy_names as $single_taxonomy) {
            $current_taxonomy = isset( $_GET[$single_taxonomy] ) ? $_GET[$single_taxonomy] : '';
            $taxonomy_object = get_taxonomy( $single_taxonomy );
            $taxonomy_name = strtolower( $taxonomy_object->labels->name );
            $taxonomy_terms = get_terms( $single_taxonomy );
            if(count($taxonomy_terms) > 0) {
                echo "<select name='$single_taxonomy' id='$single_taxonomy'>";
                echo "<option value=''>All $taxonomy_name</option>";
                foreach ($taxonomy_terms as $single_term) {
                    echo '<option value='. $single_term->slug, $current_taxonomy == $single_term->slug ? ' selected="selected"' : '','>' . $single_term->name .' (' . $single_term->count .')</option>';
                }
                echo "</select>";
            }
        }
    }
}
add_action( 'restrict_manage_posts', 'products_taxonomy_filter' );

// What do we need to build our own custom user roles

// 1. Register form with role select
// 2. Custom roles added to wp
// 3. Custom notification email when use registers
// 4. Custom redirect when users login



// Add custom user roles
/*
um_authorized-dealer
um_vendor
um_s1-west
um_s-1
um_residential-end-user
um_hr-manager
um_contractor
um_authorized-dealer-west
um_authorized-ns-dealer
*/
function usi_update_custom_roles() {
    if ( get_option( 'custom_roles_version' ) < 2 ) {
        add_role( 'um_authorized-ns-dealer', 'No Stock Dealer', array(
            'read' => true,
            'level_0' => true
        ));
        update_option( 'custom_roles_version', 2 );
    }
}
//add_action( 'init', 'usi_update_custom_roles' );

// Temporary user redirect because Ultimate Member redirect isn't working after login
//function my_on_login_before_redirect($user_id) {
//    exit(wp_redirect(site_url().'/all-products'));
//}
//add_action( 'um_on_login_before_redirect', 'my_on_login_before_redirect', 10, 1 );
//
//function custom_redirect_on_logout() {
//    exit(wp_redirect(site_url().'/all-products'));
//}
//add_action( 'wp_logout', 'custom_redirect_on_logout' );

function fetchGalleryImages()
{
    $isPreview = $_POST['isPreview'];
    $colorsValues = $_POST['colorsValues'];
    $applicationValues = $_POST['applicationValues'];
    $currentPage = intval($_POST['currentPage']);
    $imagesHTML = '';

    $args = array(
        'orderby' => $isPreview ? 'rand' : 'ASC',
        'post_type' => 'attachment',
        'posts_per_page' => 6,
        'post_status' => 'inherit',
        'paged' => $currentPage,
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => 'show_in_gallery',
                'value' => true,
                'compare' => 'LIKE'
            )
        )
    );

    $additionalMetaQuery = array(
        'relation' => 'AND'
    );

    if($colorsValues) {
        foreach($colorsValues as $color) :
            // wrap value in quotes to match values in serialized data in database
            array_push($additionalMetaQuery, array(
                'key' => 'color',
                'value' => '"'.$color.'"',
                'compare' => 'LIKE'
            ));
        endforeach;
    }

    if($applicationValues) {
        foreach($applicationValues as $application) :
            // wrap value in quotes to match values in serialized data in database
            array_push($additionalMetaQuery, array(
                'key' => 'application',
                'value' => '"'.$application.'"',
                'compare' => 'LIKE'
            ));
        endforeach;
    }

    if($colorsValues || $applicationValues) {
        array_push($args['meta_query'], $additionalMetaQuery);
    }

    $images = new WP_Query($args);

    if($images->found_posts) {
        $rowCounter = 0;
        $counter = 0;
        foreach($images->posts as $image) :
            $fullImage = wp_get_attachment_image_src($image->ID, 'full');
            $largeImage = wp_get_attachment_image($image->ID, 'large');
            $colWidth = ($isPreview) ? 4 : (($rowCounter == 1) ? (($counter == 2) ? 4 : 8) : 6);
            $imagesHTML .= '<div class="col-md-'.$colWidth.'"><div class="gallery_image">';
            $imagesHTML .= '<a href="'. $fullImage[0] .'" class="glightbox">';
            $imagesHTML .= $largeImage;
            $imagesHTML .= '</a>';
            $imagesHTML .= '</div></div>';
            $counter++;
            if($counter % 2 == 0) $rowCounter++;
        endforeach;
    } else {
        $imagesHTML .= '<div class="gallery_no_images col-md-6 offset-md-3">';
        $imagesHTML .= '<p>No images found</p>';
        $imagesHTML .= '</div>';
    }

    wp_send_json_success([
        'imagesObject' => $images,
        'args' => $args,
        'currentPage' => $currentPage,
        'pages' => $images->max_num_pages,
        'found_images' => $images->found_posts,
        'images' => $images->posts,
        'html' => $imagesHTML,
        'colors' => $colorsValues,
        'applications' => $applicationValues
    ]);
}
add_action('wp_ajax_nopriv_fetch_gallery_images', 'fetchGalleryImages');
add_action('wp_ajax_fetch_gallery_images', 'fetchGalleryImages');

function fetchTermData()
{
    $productType = $_POST['productType'];
    $typeParentTermObject = null;
    $product = $_POST['product'];

    // if no productType or product, we can assume we're on the default "all-products" page which is hardscape
    if (!$productType && !$product) {
        $productType = 'hardscape';
    }

    // If productType exists and isn't 'color', then fetch parent if its a child
    if ($productType && $productType !== 'color') {
        // Get the term object from the productType slug
        $typeTermObject = get_term_by('slug', $productType, 'product-type');
        // Get term parent or self if no parent exists
        $typeParentTermObject = ($typeTermObject->parent !== 0) ?
            get_term_by('id', $typeTermObject->parent, 'product-type') :
            $typeTermObject;
    }

    // If no productType but have product, then query for the productType using the product slug
    if (!$productType && $product) {
        $productQuery = new WP_Query(array(
            'post_type' => 'products',
            'posts_per_page' => 1,
            'name' => $product
        ));
        $productObject = $productQuery->posts[0];
        $productTerms = wp_get_post_terms($productObject->ID, 'product-type', array('orderby' => 'parent'));

        if ($productTerms) {
            // If the product term isn't top level, then get the top level term
            if ($productTerms[0]->parent !== 0) {
                $typeParentTermObject = get_term($productTerms[0]->parent);
            } else {
                $typeParentTermObject = $productTerms[0];
            }

            $productType = $typeParentTermObject->slug;
        }
    }

    wp_send_json_success(array(
        'productType' => $productType,
        'typeParentTermObject' => $typeParentTermObject,
        'product' => $product,
        'productObject' => $productObject,
        'productID' => $productObject->ID,
        'productTerms' => $productTerms
    ));
}
add_action('wp_ajax_nopriv_fetch_term_data', 'fetchTermData');
add_action('wp_ajax_fetch_term_data', 'fetchTermData');

function fetchProducts() {
    $productType = $_POST['productType'];
    $html = '';
    $children = false;
    $termByName = get_term_by('slug', $productType, 'product-type');

    if($productType === 'color')
    {
        $colors = get_terms(array(
            'taxonomy' => 'color'
        ));
        ob_start();
        foreach($colors as $color) {
            include('includes/product-thumbnail.php');
        }
        $html .= ob_get_clean();
    }
    //elseif($productType === 'hardscape' || $productType === 'veneer')
    else
    {
        if ($termByName) {
            $children = get_terms(array(
                'taxonomy' => 'product-type',
                'parent'    => $termByName->term_id,
                'hide_empty' => true
            ));
        }

        $args = array(
            'post_type' => 'products',
            'posts_per_page' => -1,
            'orderby' => 'menu_order',
            'order' => 'ASC',
            'tax_query' => array(
                array(
                    'taxonomy' => 'product-type',
                    'field' => 'slug',
                    'terms' => $productType,
                    'include_children' => false
                )
            )
        );

        $products = new WP_Query($args);

        if($products->found_posts) {
            ob_start();

            if($productType === 'veneer') { ?>
            <div class="products-grid-three row">
            <?php }

            foreach($products->posts as $product) :
                include('includes/product-thumbnail.php');
            endforeach;

            if ($children) {
                foreach ($children as $child) {
                    include('includes/product-thumbnail.php');
                }
            }

            if($productType === 'veneer') { ?>
            </div>
            <?php }

            $html .= ob_get_clean();
        }
    }

    wp_send_json_success([
        'productsObject' => $products,
        'products' => $products->posts,
        'foundProducts' => $products->found_posts,
        'productType' => $productType,
        'html' => $html,
        'colors' => $colors,
        'children' => $children,
        'termID' => $termID,
        'termByName' => $termByName
    ]);
}
add_action('wp_ajax_nopriv_fetch_products', 'fetchProducts');
add_action('wp_ajax_fetch_products', 'fetchProducts');

function fetchProduct() {
    $id = intval($_POST['id']);
    $slug = $_POST['slug'];
    $isColor = $_POST['isColor'];
    $name = $_POST['name'];
    $variant = $_POST['variant'];
    $html = '';

    if($isColor === 'true')
    {
        ob_start();
        include('includes/product-color.php');
        $html = ob_get_clean();
    }
    else
    {
        $args = array(
            'post_type' => 'products',
            'posts_per_page' => 1,
            'name' => $slug
        );

        $productObject = new WP_Query($args);

        if($productObject->found_posts)
        {
            $product = $productObject->posts[0];
            ob_start();
            include('includes/product.php');
            $html .= ob_get_clean();
        }
    }

    wp_send_json_success([
        'isColor' => $isColor,
        'variant' => $variant,
        'html' => $html
    ]);
}
add_action('wp_ajax_nopriv_fetch_product', 'fetchProduct');
add_action('wp_ajax_fetch_product', 'fetchProduct');

// Don't let users access user profile or edit user profile if they aren't logged in
function usi_user_profile_redirect() {
    global $post;

    if($post->post_name === 'user-profile' || $post->post_name === 'edit-user-profile') {
        if(!is_user_logged_in()) {
            wp_redirect(get_home_url());
            exit();
        }
    }
}
add_action('template_redirect', 'usi_user_profile_redirect');

// Redirect user to home after logout
function usi_redirect_on_logout() {
    wp_redirect(get_home_url());
}
add_action('wp_logout', 'usi_redirect_on_logout');

include ('includes/admin-users.php');
include('includes/register.php');
include('includes/login.php');
include('includes/edit-profile.php');
include('includes/reset-password.php');
include('includes/modal.php');
//include('includes/dropbox.php');