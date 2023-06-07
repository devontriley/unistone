<?php get_header(); ?>

<?php
$user = wp_get_current_user();
//print_r($user);

if($user) :
    $userID = $user->ID;
    $userRoles = $user->roles;
    $userData = $user->data;
    $userMeta = get_user_meta($userID);
    //print_r($userMeta);

    $userEmail = $userData->user_email;
    $userFirstName = $userMeta['first_name'][0];
    $userLastName = $userMeta['last_name'][0];
    $userName = ($userFirstName && $userLastName) ? $userFirstName . ' ' . $userLastName : $userEmail;
    $businessName = get_user_meta($userID, 'business_name', true);
    $favoriteColor = get_user_meta($userID, 'favorite_color', true);
    $favoriteTeam = get_user_meta($userID, 'favorite_team', true);
    $shoeSize = get_user_meta($userID, 'shoe_size', true);
    $shirtSize = get_user_meta($userID, 'shirt_size', true);
?>

<div class="edit-user-profile" style="margin: 100px 0;">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <form id="editUserProfileForm" class="editUserProfileForm">
                    <?php wp_nonce_field( 'usi_edit_user_profile' ); ?>

                    <h1>Edit profile</h1>

                    <h4 class="mb-3">Account Info</h4>

                    <div class="mb-3">
                        <label for="emailAddress" class="form-label">Email address</label>
                        <input type="text" class="form-control" id="emailAddress" name="email" value="<?php echo esc_html($userEmail) ?>">
                        <span class="errorMessage"></span>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <label for="firstName" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="firstName" name="first_name" value="<?php echo esc_html($userFirstName) ?>">
                            <span class="errorMessage"></span>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label for="lastName" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="lastName" name="last_name" value="<?php echo esc_html($userLastName) ?>">
                            <span class="errorMessage"></span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="businessName" class="form-label">Business name</label>
                        <input type="text" class="form-control" id="businessName" name="business_name" value="<?php echo esc_html($businessName) ?>">
                        <span class="errorMessage"></span>
                    </div>

                    <h4 class="mt-5 mb-3">Personal Preferences</h4>

                    <div class="mb-3">
                        <label for="favoriteColor" class="form-label">Favorite color</label>
                        <input type="text" class="form-control" id="favoriteColor" name="favorite_color" value="<?php echo esc_html($favoriteColor) ?>">
                        <span class="errorMessage"></span>
                    </div>
                    <div class="mb-3">
                        <label for="favoriteSportsTeam" class="form-label">Favorite sports team</label>
                        <input type="text" class="form-control" id="favoriteSportsTeam" name="favorite_team" value="<?php echo esc_html($favoriteTeam) ?>">
                        <span class="errorMessage"></span>
                    </div>
                    <div class="mb-3">
                        <label for="shirtSize" class="form-label">T-Shirt size</label>
                        <select class="form-select" aria-label="T-Shirt size" id="shirtSize" name="shirt_size">
                            <option disabled <?php if(!$shirtSize){ echo 'selected'; }?>>Choose size</option>
                            <option value="s" <?php if($shirtSize === 's'){ echo 'selected'; }?>>Small</option>
                            <option value="m" <?php if($shirtSize === 'm'){ echo 'selected'; }?>>Medium</option>
                            <option value="l" <?php if($shirtSize === 'l'){ echo 'selected'; }?>>Large</option>
                            <option value="xl" <?php if($shirtSize === 'xl'){ echo 'selected'; }?>>XL</option>
                            <option value="xxl" <?php if($shirtSize === 'xxl'){ echo 'selected'; }?>>XXL</option>
                            <option value="xxxl" <?php if($shirtSize === 'xxxl'){ echo 'selected'; }?>>XXXL</option>
                        </select>
                        <span class="errorMessage"></span>
                    </div>
                    <div class="mb-3">
                        <label for="shoeSize" class="form-label">Shoe size</label>
                        <select class="form-select" aria-label="Shoe size" id="shoeSize" name="shoe_size">
                            <option disabled <?php if(!$shoeSize){ echo 'selected'; }?>>Choose size</option>
                            <option value="6" <?php if($shoeSize === '6'){ echo 'selected'; }?>>6</option>
                            <option value="7" <?php if($shoeSize === '7'){ echo 'selected'; }?>>7</option>
                            <option value="8" <?php if($shoeSize === '8'){ echo 'selected'; }?>>8</option>
                            <option value="9" <?php if($shoeSize === '9'){ echo 'selected'; }?>>9</option>
                            <option value="10" <?php if($shoeSize === '10'){ echo 'selected'; }?>>10</option>
                            <option value="11" <?php if($shoeSize === '11'){ echo 'selected'; }?>>11</option>
                        </select>
                        <span class="errorMessage"></span>
                    </div>

                    <button type="submit" id="editUserProfileSubmit" class="btn btn-primary">Update profile</button>

                    <div class="formMessage"></div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php else : ?>

Not logged in

<?php endif; ?>

<?php get_footer(); ?>
