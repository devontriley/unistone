<?php
$image = get_sub_field('image');
$alignment = get_sub_field('alignment');
$link = get_sub_field('link');
?>

<div class="single_image">
    <div class="container-fluid text-<?php echo $alignment ?>">
        <?php if($link){ echo '<a href="'.$link['url'].'" target="'.$link['target'].'">'; }?>
        <?php echo wp_get_attachment_image($image['id'], 'full'); ?>
        <?php if($link){ echo '</a>'; } ?>
    </div>
</div>