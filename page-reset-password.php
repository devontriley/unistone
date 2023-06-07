<?php get_header(); ?>

<div class="reset-password-page">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <h2>Reset password</h2>
                <form id="usiResetPasswordForm" class="usiResetPasswordForm" noValidate>
                    <?php wp_nonce_field( 'usi_reset_password_form' ); ?>
                    <div class="mb-3">
                        <label for="emailAddress" class="form-label">Email address</label>
                        <input type="text" class="form-control" id="email" name="email">
                        <span class="errorMessage"></span>
                    </div>
                    <button type="submit" id="resetPasswordSubmit" class="btn btn-primary">Submit</button>
                    <div class="formMessage"></div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
