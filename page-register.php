<?php get_header(); ?>

<div class="register-page">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <h2>Register</h2>

                <form id="usiRegistrationForm" class="usiRegistrationForm">
                    <div class="mb-3">
                        <label for="emailAddress" class="form-label">Email address</label>
                        <input type="text" class="form-control" id="emailAddress" name="email" value="">
                        <span class="errorMessage"></span>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" value="">
                    </div>
                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label">Confirm password</label>
                        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" value="">
                        <span id="password-strength"></span>
                    </div>
                    <div class="mb-3">
                        <label for="accountType" class="form-label">Account type</label>
                        <select class="form-select" aria-label="Account type" id="accountType" name="accountType">
                            <option disabled selected>Choose account type</option>
                            <option value="um_residential-end-user">Homeowner</option>
                            <option value="um_contractor">Contractor</option>
                            <option value="um_authorized-dealer">Authorized Dealer</option>
                            <option value="um_vendor">Vendor</option>
                        </select>
                        <span class="errorMessage"></span>
                    </div>
                    <div class="mb-3">
                        <label for="firstName" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="firstName" name="firstName" value="">
                        <span class="errorMessage"></span>
                    </div>
                    <div class="mb-3">
                        <label for="lastName" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="lastName" name="lastName" value="">
                        <span class="errorMessage"></span>
                    </div>
                    <div class="mb-3">
                        <label for="businessName" class="form-label">Business name</label>
                        <input type="text" class="form-control" id="businessName" name="businessName" value="">
                        <span class="errorMessage"></span>
                    </div>
                    <button type="submit" id="registrationSubmit" class="btn btn-primary">Submit</button>
                    <div class="formMessage"></div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
