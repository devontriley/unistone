<?php

if (is_user_logged_in()) :
    $user = wp_get_current_user();
    $userRoles = $user->roles;
    $siteURL = get_home_url();

    foreach ($userRoles as $role) {
        switch ($role) {
            case 'administrator':
                echo '<li class="menu-item"><a href="' . $siteURL . '/wp-admin">Dashboard</a></li>';
                echo '<li class="dealer-login menu-item"><a href="' . $siteURL . '/dealer-information-east">East</a></li>';
                echo '<li class="dealer-login menu-item"><a href="' . $siteURL . '/dealer-information-west">West</a></li>';
                echo '<li class="dealer-login menu-item"><a href="' . $siteURL . '/dealer-information-s1-east">S1E</a></li>';
                echo '<li class="dealer-login menu-item"><a href="' . $siteURL . '/dealer-information-s1-west">S1W</a></li>';
                echo '<li class="dealer-login menu-item"><a href="' . $siteURL . '/contractor-information">Con</a></li>';
                echo '<li class="dealer-login menu-item"><a href="' . $siteURL . '/dealer-information-ns-east">NS</a></li>';
                echo '<li class="dealer-login menu-item"><a href="' . $siteURL . '/architect-designer-information">Arch/Des</a></li>';
                echo '<li class="dealer-login menu-item"><a href="' . $siteURL . '/homeowner">Homeowner</a></li>';
                echo '<li class="dealer-login menu-item"><a href="' . $siteURL . '/vendor">Vendor</a></li>';
                break;
            case 'um_authorized-dealer':
                echo '<li class="dealer-login menu-item"><a href="' . $siteURL . '/dealer-information-east">Authorized Dealer East Information</a></li>';
                break;
            case 'um_authorized-dealer-west':
                echo '<li class="dealer-login menu-item"><a href="' . $siteURL . '/dealer-information-west">Authorized Dealer West Information</a></li>';
                break;
            case 'um_vendor':
                echo '<li class="dealer-login menu-item"><a href="' . $siteURL . '/vendor">Vendor Information</a></li>';
                break;
            case 'um_s1-west':
                echo '<li class="dealer-login menu-item"><a href="' . $siteURL . '/dealer-information-s1-west">SiteOne West Information</a></li>';
                break;
            case 'um_s-1':
                echo '<li class="dealer-login menu-item"><a href="' . $siteURL . '/dealer-information-s1-east">SiteOne East Information</a></li>';
                break;
            case 'um_residential-end-user':
                echo '<li class="dealer-login menu-item"><a href="' . $siteURL . '/homeowner">Homeowner Information</a></li>';
                break;
            case 'um_hr-manager':
                echo '';
                break;
            case 'um_contractor':
                echo '<li class="dealer-login menu-item"><a href="' . $siteURL . '/contractor-information">Contractor Information</a></li>';
                break;
            case 'um_authorized-ns-dealer':
                echo '<li class="dealer-login menu-item"><a href="' . $siteURL . '/dealer-information-ns-east">No Stock Dealer Information</a></li>';
                break;
            case 'um_designer-architect':
                echo '<li class="dealer-login menu-item"><a href="' . $siteURL . '/architect-designer-information">Architect/Designer Information</a></li>';
                break;
            default:
                echo '';
        }
    }
    echo '<li class="menu-item"><a href="' . esc_url(wp_logout_url(get_home_url())) . '">Logout</a></li>';
else :
    echo '<li class="dealer-login menu-item"><a href="' . $siteURL . '/login">Dealer Login</a></li>';
endif;