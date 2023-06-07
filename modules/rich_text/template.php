<?php
$header = get_sub_field('header');
$copy = get_sub_field('copy');
$image = get_sub_field('image');
?>

<div class="rich_text_module">
    <div class="row">
        <div class="col-md-8 col-xxxl-6">
            <?php if($header): ?>
                <p class="h1 header_green_bar"><?php echo $header ?></p>
            <?php endif; ?>
            <?php if($copy): ?>
                <?php echo $copy ?>
            <?php endif; ?>
        </div>
        <?php if($image): ?>
            <div class="image col-12 col-xxxl-5 offset-xxxl-1">
                <?php echo wp_get_attachment_image($image['ID'], 'full'); ?>
            </div>
        <?php endif; ?>
    </div>
</div>
