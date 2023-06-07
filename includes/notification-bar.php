<?php
function getDaysLeft($date) {
    $now = time();
    $your_date = strtotime($date);
    $datediff = $now - $your_date;

    return abs(round($datediff / (60 * 60 * 24)));
}

$enableNotificationBar = get_field('notification_enable', 'option');
$notificationText = get_field('notification_text', 'option');
$notificationCountdown = get_field('notification_countdown', 'option');
$daysLeft = null;

if($notificationCountdown) {
    $daysLeft = getDaysLeft($notificationCountdown);
}
?>

<div class="notification-bar <?php if($enableNotificationBar){ echo 'd-block'; } else { echo 'd-none'; }?>">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-auto text-center">
                <?php if($daysLeft){ echo $daysLeft; } ?>
                <?php echo $notificationText ?>
            </div>
        </div>
    </div>
</div>