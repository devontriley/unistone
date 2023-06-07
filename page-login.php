<?php get_header(); ?>

<div class="login-page">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <h2>Log in</h2>
                <form id="usiLoginForm" class="usiLoginForm">
                    <?php //wp_nonce_field( 'usi_login_form' ); ?>
                    <div class="mb-3">
                        <label for="emailAddress" class="form-label">Email address</label>
                        <input type="text" class="form-control" id="emailAddress" name="email" value="">
                        <span class="errorMessage"></span>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" value="">
                    </div>
                    <button type="submit" id="registrationSubmit" class="btn btn-primary">Submit</button>
                    <div class="formMessage"></div>
                </form>

                <p class="fs-6">Having trouble logging in? <a href="<?php echo get_home_url().'/reset-password' ?>/">Reset your password</a></p>
                <p class="fs-6">Don't have a user account? <a href="<?php echo get_home_url().'/register'; ?>">Register</a></p>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
