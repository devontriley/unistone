<div style="max-width: 560px;padding: 20px;background: #ffffff;border-radius: 5px;margin: 40px auto;font-family: Open Sans,Helvetica,Arial;font-size: 15px;color: #666">
    <div style="color: #444444;font-weight: normal">
        <div style="text-align: center;font-weight: 600;font-size: 26px;padding: 10px 0;border-bottom: solid 3px #eeeeee">
            <img src="<?php echo get_template_directory_uri().'/src/images/usi_logo.png' ?>" alt="Universal Stone Imports" />
        </div>
    </div>
    <div style="padding: 30px;border-bottom: 3px solid #eeeeee">
        <div style="font-size: 16px;text-align: center;line-height: 1.5em;">
            A new user has registered on the Universal Stone website.
        </div>
        <div style="padding: 30px 0 0 0;text-align: center">
            <a style="background: #555555;color: #fff;padding: 12px 30px;text-decoration: none;border-radius: 3px;" href="<?php echo bloginfo('url') ?>/wp-admin/user-edit.php?user_id=<?php echo $user_id; ?>" target="_blank">
                Review user account
            </a>
        </div>
    </div>
    <div style="color: #999;padding: 20px 30px; text-align: center;">
        <div>Thank you!</div>
        <div>The <a style="color: #3ba1da;text-decoration: none" href="<?php echo get_home_url() ?>">Universal Stone</a> Team</div>
    </div>
</div>