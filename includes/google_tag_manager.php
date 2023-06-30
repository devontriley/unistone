<?php

if ( !function_exists( 'load_google_tag_manager' ) ) {
    function load_google_tag_manager() { ?>
        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
                j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
                'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','GTM-TXKZNMK');</script>
        <!-- End Google Tag Manager -->
    <?php }
}
if ( wp_get_environment_type() === 'production' ) {
    add_action( 'wp_head', 'load_google_tag_manager' );
}

if ( ! function_exists( 'load_google_tag_manager_noscript' ) ) {
    function load_google_tag_manager_noscript() { ?>
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TXKZNMK" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
    <?php }
}
if ( wp_get_environment_type() === 'production' ) {
    add_action( 'wp_body_open', 'load_google_tag_manager_noscript' );
}