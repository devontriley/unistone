<?php
$type = get_sub_field('type');
$image = get_sub_field('image');
?>

<div class="hero" style="background-image: url(<?php echo $image['url'] ?>);">
    <p class="h1"><?php the_sub_field('header') ?></p>
    <?php if($type === 'primary') : ?>
        <div class="scroll_down">
            Scroll Down<br />
            <svg xmlns="http://www.w3.org/2000/svg" width="26.4" height="26.4" viewBox="0 0 26.4 26.4">
                <g id="Icon_ICON_feather_arrow-down-circle_SIZE_LARGE_STYLE_STYLE1_" data-name="Icon [ICON=feather/arrow-down-circle][SIZE=LARGE][STYLE=STYLE1]" transform="translate(-434.8 1.2)">
                    <g id="Icon" transform="translate(435 -1)">
                        <circle id="_25a5e17b-e5f6-42da-a237-235265bee632" data-name="25a5e17b-e5f6-42da-a237-235265bee632" cx="12" cy="12" r="12" transform="translate(1 1)" fill="none" stroke="#eee" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4"/>
                        <path id="d454f6dc-3a9c-411c-bef3-c915b40e1ef7" d="M9.6,14.4l4.8,4.8,4.8-4.8" transform="translate(-1.4 -1.4)" fill="none" stroke="#eee" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4"/>
                        <line id="_4e614625-7ca3-4541-bcd9-33dc3ad57a47" data-name="4e614625-7ca3-4541-bcd9-33dc3ad57a47" y2="9.6" transform="translate(13 8.2)" fill="none" stroke="#eee" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4"/>
                    </g>
                </g>
            </svg>
        </div>
    <?php endif; ?>
</div>