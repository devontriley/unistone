<?php

// Add Status column to users screen in admin panel

function usi_modify_user_table($column) {
    $column['status'] = 'Status';
    return $column;
}
add_filter('manage_users_columns', 'usi_modify_user_table');

function usi_modify_user_table_row($val, $column_name, $user_id) {
    switch ($column_name) {
        case 'status' :
            return get_the_author_meta('account_status', $user_id);
        default:
    }
    return $val;
}
add_filter('manage_users_custom_column', 'usi_modify_user_table_row', 10, 3);

function usi_set_users_sortable_columns($columns)
{
    $columns['status'] = 'account_status';
    return $columns;
}
add_filter('manage_users_sortable_columns', 'usi_set_users_sortable_columns');

function usi_sort_custom_column_query($query) {
    if(!is_admin()) return;

    $orderby = $query->get('orderby');

    if('account_status' == $orderby) {
        $query->set('meta_key', 'account_status');
        $query->set('orderby', 'meta_value');
    }
}
add_action('pre_get_users', 'usi_sort_custom_column_query');

$customUserMetaFields = array(
    'account_status' => array(
        'name' => 'Account Status',
        'field_type' => 'select',
        'options' => array(
            'approved' => 'Approved',
            'awaiting_admin_review' => 'Awaiting Admin Review'
        )
    ),
    'business_name' => array(
        'name' => 'Business Name',
        'field_type' => 'text'
    ),
    'favorite_color' => array(
        'name' => 'Favorite Color',
        'field_type' => 'text'
    ),
    'favorite_team' => array(
        'name' => 'Favorite Team',
        'field_type' => 'text'
    ),
    'shirt_size' => array(
        'name' => 'T-Shirt size',
        'field_type' => 'select',
        'options' => array(
            's' => 'Small',
            'm' => 'Medium',
            'l' => 'Large',
            'xl' => 'XL',
            'xxl' => 'XXL',
            'xxxl' => 'XXXL'
        )
    ),
    'shoe_size' => array(
        'name' => 'Shoe size',
        'field_type' => 'select',
        'options' => array(
            '6' => '6',
            '7' => '7',
            '8' => '8',
            '9' => '9',
            '10' => '10',
            '11' => '11'
        )
    )
);

function usi_add_custom_user_meta_fields($user) {
    global $customUserMetaFields;

    $userMeta = get_user_meta($user->ID); ?>

    <h3>Custom User Fields</h3>

    <table class="form-table">
        <?php foreach($customUserMetaFields as $key => $value) : ?>
            <tr>
                <th><label for="account_status"><?php echo $value['name'] ?></label></th>
                <td>
                    <?php if($value['field_type'] === 'text') : ?>
                        <input type="text" name="<?php echo $key ?>" value="<?php echo esc_attr(get_the_author_meta($key, $user->ID)); ?>" class="regular-text" />
                    <?php elseif($value['field_type'] === 'select') : ?>
                        <select name="<?php echo $key ?>">
                            <option disabled <?php if(!$userMeta[$key][0]){ echo 'selected'; }?>>Choose <?php echo $value['name'] ?></option>
                            <?php foreach($value['options'] as $optionKey => $optionValue) : ?>
                                <option value="<?php echo $optionKey ?>" <?php if($userMeta[$key][0] == $optionKey){ echo 'selected'; }?>><?php echo $optionValue ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php
}
add_action( 'show_user_profile', 'usi_add_custom_user_meta_fields' );
add_action( 'edit_user_profile', 'usi_add_custom_user_meta_fields' );

function usi_save_custom_user_meta_fields($user_id) {
    global $customUserMetaFields;

    foreach($customUserMetaFields as $key => $value) {
        update_user_meta($user_id, $key, sanitize_text_field($_POST[$key]));
    }
}
add_action('personal_options_update', 'usi_save_custom_user_meta_fields');
add_action('edit_user_profile_update', 'usi_save_custom_user_meta_fields');

// Send notification email to user when account is approved
function usi_account_status_updated($meta_id, $object_id, $meta_key, $_meta_value) {
    if($meta_key === 'account_status' && $_meta_value === 'approved')
    {
        $user = get_user_by('id', $object_id);

        if(!$user) {
            // Not sure if this should email the admin because there was an error or something
            return;
        }

        $userEmail = $user->user_email;

        $emailBody = '';
        ob_start();
        include(get_stylesheet_directory() . '/includes/email/account-approved.php');
        $emailBody = ob_get_contents();
        ob_end_clean();
        $headers = array( 'Content-Type: text/html; charset=UTF-8' );

        wp_mail(
            $userEmail,
            'Your Universal Stone account has been approved!',
            $emailBody,
            $headers
        );
    }
}
add_action('updated_user_meta', 'usi_account_status_updated', 10, 4);