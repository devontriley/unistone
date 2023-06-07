<div id="primary_menu" class="primary_menu">
    <div class="logo">
        <a href="<?php echo get_bloginfo('url') ?>">
            <img src="<?php echo get_bloginfo('template_directory') ?>/src/images/usi_logo_1.svg" />
        </a>
    </div>
    <div class="d-flex justify-content-center align-self-stretch">
        <?php wp_nav_menu(['menu' => 'primary-menu']); ?>

        <ul class="menu dealer-links">
            <?php
            $pageID = get_the_ID();
            $dealerPagesIds = array(47, 49, 51, 53, 55, 57, 59, 584, 581);
            if(is_user_logged_in()) : ?>
                <li class="menu-item <?php if(in_array($pageID, $dealerPagesIds)){ echo 'current-menu-item'; }?>">
                    <svg class="dealer-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 108.38 108.38">
                        <path d="M54.24,21.13c11.39,.06,20.32,9.12,20.22,20.52-.09,11.16-9.23,20.06-20.5,19.97-11.15-.09-20.04-9.22-19.96-20.51,.08-11.05,9.19-20.04,20.23-19.98Z"/>
                        <path d="M54.19,0C24.31,0,0,24.31,0,54.19s24.31,54.19,54.19,54.19,54.19-24.31,54.19-54.19S84.07,0,54.19,0Zm30.82,91.2c-.03-.33-.12-.69-.25-1.1-5.76-17.52-19.96-27.82-35.28-24.98-12.58,2.33-21.15,11.24-25.97,25.64-.06,.17-.08,.32-.11,.47-10.62-8.85-17.4-22.17-17.4-37.04C6,27.62,27.62,6,54.19,6s48.19,21.62,48.19,48.19c0,14.86-6.76,28.17-17.37,37.01Z"/>
                    </svg>
                    <span>Dealer</span>
                    <div class="dropdown">
                        <ul>
                            <?php include('dealer-login-links.php'); ?>
                        </ul>
                    </div>
                </li>
            <?php else : ?>
                <li class="menu-item">
                    <a href="<?php echo get_bloginfo('url') ?>/login">Dealer Login</a>
                </li>
            <?php endif; ?>
            <li class="menu-item <?php if(trim($_SERVER['REQUEST_URI'], '/') === 'contact'){ echo 'current-menu-item'; }?>"><a href="<?php echo get_bloginfo('url') ?>/contact">Contact</a></li>
        </ul>

        <button class="hamburger hamburger--collapse" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuDrawer" aria-controls="menuDrawer">
            <span class="hamburger-box">
                <span class="hamburger-inner"></span>
            </span>
        </button>
    </div>
</div>