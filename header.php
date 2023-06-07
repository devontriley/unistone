<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title></title>
    <meta name="viewport" content="width=device-width, minimum-scale=1, initial-scale=1" />
    <link rel="stylesheet" href="https://unpkg.com/splitting/dist/splitting.css" />
    <link rel="stylesheet" href="https://unpkg.com/splitting/dist/splitting-cells.css" />

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_bloginfo('template_directory') ?>/src/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo get_bloginfo('template_directory') ?>/src/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo get_bloginfo('template_directory') ?>/src/favicon/favicon-16x16.png">
    <link rel="manifest" href="<?php echo get_bloginfo('template_directory') ?>/src/favicon/site.webmanifest">
    <link rel="mask-icon" href="<?php echo get_bloginfo('template_directory') ?>/src/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <!-- Favicon -->

    <?php wp_head() ?>
    <script>
        const ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ); ?>'
    </script>
</head>

<?php
$enableNotificationBar = get_field('notification_enable', 'option');
?>

<body data-barba="wrapper" class="<?php if($enableNotificationBar){ echo 'enable-notification-bar'; }?>">

<div id="transition-panel"></div>

<div id="viewport-border"></div>

<main data-barba="container" data-barba-namespace="home">

    <?php include('includes/notification-bar.php'); ?>

    <div class="menu_drawer offcanvas offcanvas-start" tabindex="-1" id="menuDrawer" aria-labelledby="offcanvasLabel">
        <div class="offcanvas-body">
            <div>
                <?php wp_nav_menu(['menu' => 'primary-menu']); ?>

                <div class="menu-primary-menu-container">
                    <ul class="menu">
                        <li class="menu-item <?php if(trim($_SERVER['REQUEST_URI'], '/') === 'contact'){ echo 'current-menu-item'; }?>"><a href="<?php echo get_bloginfo('url') ?>/contact">Contact</a></li>
                        <?php include('includes/dealer-login-links.php'); ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="site-wrapper">

        <?php include('includes/primary_menu.php'); ?>