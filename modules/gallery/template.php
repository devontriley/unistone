<?php
$preview = get_sub_field('gallery_preview');
$subheader = get_sub_field('subheader');

$colors = get_terms(array(
    'taxonomy' => 'color'
));
$applications = $field = get_field_object('field_62ec053105892');
?>

<script type="text/javascript">
    window.galleryModulePreview = '<?php echo $preview ?>';
</script>

<div class="gallery_module" data-preview="<?php echo $preview ?>">
    <div class="row">
        <div class="col-12">
            <div class="row justify-content-between">
                <div class="<?php if($preview){ echo 'col text-center'; } else { echo 'col-12 col-md-auto'; } ?>">
                    <h1 class="h2">Project Gallery</h1>
                    <?php if($preview) : ?>
                        <?php if($subheader) : ?>
                            <p><?php echo $subheader ?></p>
                        <?php endif; ?>
                        <a href="<?php echo get_bloginfo('url') ?>/gallery" class="btn btn-link">View Full Gallery</a>
                    <?php endif; ?>
                </div>

                <?php if(!$preview) : ?>
                    <div id="gallery_filters" class="gallery_filters col-12 col-md-auto">
                        <button type="button" id="clear_gallery_filters" class="clear_gallery_filters btn btn-link">Clear Filters</button>

                        <div class="gallery_filter application_filters">
                            <button class="gallery_filter_button">
                                <span>Application</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="form-check-arrow" width="21.521" height="12.528" viewBox="0 0 21.521 12.528">
                                    <g id="Group_10" data-name="Group 10" transform="translate(-509.117 380.423) rotate(-45)">
                                        <line id="Line_3" data-name="Line 3" y1="14.718" transform="translate(629 92.5)" fill="none" stroke="#707070" stroke-width="3"/>
                                        <line id="Line_4" data-name="Line 4" x2="14.718" transform="translate(628 106.218)" fill="none" stroke="#707070" stroke-width="3"/>
                                    </g>
                                </svg>
                            </button>
                            <div class="filter_dropdown">
                                <?php foreach($applications['choices'] as $key => $value) : ?>
                                    <div class="form-check">
                                        <input class="form-check-input" id="<?php echo $key ?>" type="checkbox" name="application_filter" value="<?php echo $key ?>" />
                                        <label class="form-check-label" for="<?php echo $key ?>"><?php echo $value ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="gallery_filter color_filters">
                            <button class="gallery_filter_button">
                                <span>Color</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="form-check-arrow" width="21.521" height="12.528" viewBox="0 0 21.521 12.528">
                                    <g id="Group_10" data-name="Group 10" transform="translate(-509.117 380.423) rotate(-45)">
                                        <line id="Line_3" data-name="Line 3" y1="14.718" transform="translate(629 92.5)" fill="none" stroke="#707070" stroke-width="3"/>
                                        <line id="Line_4" data-name="Line 4" x2="14.718" transform="translate(628 106.218)" fill="none" stroke="#707070" stroke-width="3"/>
                                    </g>
                                </svg>
                            </button>
                            <div class="filter_dropdown">
                                <?php foreach($colors as $color) : ?>
                                    <div class="form-check">
                                        <input class="form-check-input" id="<?php echo $color->term_id ?>" type="checkbox" name="color_filter" value="<?php echo $color->term_id ?>" />
                                        <label class="form-check-label" for="<?php echo $color->term_id ?>"><?php echo $color->name ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-12 text-center">
            <div id="gallery_images_container" class="row gallery_images"></div>
            <button id="gallery_load_more" class="gallery_load_more btn btn-primary" data-page="0">Load More Images</button>
        </div>
    </div>
</div>