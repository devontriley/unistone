<?php get_header(); ?>

<?php
global $customUserMetaFields;

$user = wp_get_current_user();
//print_r($user);

if($user) :
    $userID = $user->ID;
    $userRoles = $user->roles;
    $userData = $user->data;
    $userMeta = get_user_meta($userID);
    //print_r($userMeta);

    $userName = ($userMeta['first_name'][0] && $userMeta['last_name'][0]) ? $userMeta['first_name'][0] . ' ' . $userMeta['last_name'][0] : $userData->user_email;
    $userEmail = $userData->user_email;
?>

<div class="user-profile" style="margin: 100px 0;">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <h1 class="mb-0"><?php echo $userName ?></h1>
                <h4><?php echo $userEmail ?></h4>
                <table class="table table-striped mt-5">
                    <tbody>
                    <?php if($customUserMetaFields) :
                        foreach($customUserMetaFields as $key => $value) :
                            echo '<tr>';
                            echo '<td><strong>'.$value["name"].'</strong></td>';
                            echo '<td>'. esc_html($userMeta[$key][0]) .'</td>';
                            echo '</tr>';
                        endforeach;
                    endif; ?>
                    </tbody>
                </table>
                <a href="<?php echo get_home_url().'/edit-user-profile'; ?>" class="btn btn-primary">Edit Profile</a>
            </div>
        </div>
    </div>
</div>

<?php else : ?>

<div class="no-user">
    <div class="row">
        <div class="col-lg-8">
            Maybe this page should just redirect to home or something when no user is logged in?
        </div>
    </div>
</div>

<?php endif; ?>

<?php get_footer(); ?>
