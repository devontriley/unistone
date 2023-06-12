<?php
$id = $product->ID;
$selectedColor = ($variant === 'false') ? null : $variant;
$thumbnail = get_field('thumbnail', $id);
$placeholderImage = get_template_directory_uri() . '/src/images/missing-image.png';

$colors = get_field('colors', $id);
$firstColor = null;

// Set first color using selectedColor if a variant was passed in the URL,
// otherwise use the first color in the array
if($selectedColor) {
    foreach($colors as $color) :
        if($color['color']->slug === $selectedColor) {
            $firstColor = $color;
            break;
        }
    endforeach;
} else {
    $firstColor = $colors[0];
}
?>

<div class="product-container">
    <div class="row align-items-center">
        <div class="col-md-6 product-image">
            <?php if($colors) :
                $counter = 0;
                foreach($colors as $color) :
                    $term = $color['color'];
                    $image = $color['image']['url'] ?? $placeholderImage;
                    $active = ($selectedColor) ? ($selectedColor === $term->slug) : ($term->slug === $firstColor['color']->slug);
                    ?>
                    <img class="<?php if($active){ echo 'active'; }?>" src="<?php echo $image ?>" data-id="<?php echo $term->term_id ?>" />
                <?php $counter++; endforeach;
            endif; ?>
        </div>
        <div class="col-md-6 product-details">
            <h2>
                <span class="product-color-name"><?php echo $firstColor['color']->name ?></span> <?php echo $product->post_title ?>
            </h2>
            <?php
            if($colors) :
                $counter = 0;
                foreach($colors as $color) :
                    $term = $color['color'];
                    $active = ($selectedColor) ? ($selectedColor === $term->slug) : ($term->slug === $firstColor['color']->slug);
                    ?>
                    <div data-id="<?php echo $term->term_id ?>" class="product-description <?php if($active){ echo 'active'; }?>">
                        <p>
                            <?php echo $color['description'] ?>
                        </p>
                    </div>
                <?php
                $counter++;
                endforeach;
            endif;
            ?>
        </div>
    </div>
    <div class="row w-100">
        <div class="col-12 product-colors">
            <?php
            if($colors) :
                $counter = 0;
                foreach($colors as $color) :
                    $term = $color['color'];
                    $swatch = get_field('color_swatch', 'color_'.$term->term_id);
                    $active = ($selectedColor) ? ($selectedColor === $term->slug) : ($term->slug === $firstColor['color']->slug);
                    ?>
                    <button class="color <?php if($active){ echo 'active'; }?>" data-id="<?php echo $term->term_id ?>" data-name="<?php echo $term->name ?>" data-slug="<?php echo $term->slug ?>">
                        <img src="<?php echo $swatch['url'] ?>" />
                    </button>
                    <?php
                    $counter++;
                endforeach;
            endif;
            ?>
        </div>
    </div>
    <?php if($colors) : ?>
        <div class="product-install-photos-container">
            <?php $counter = 0;
            foreach($colors as $color) :
                $term = $color['color'];
                $installPhotos = $color['install_photos'];
                $active = ($selectedColor) ? ($selectedColor === $term->slug) : ($term->slug === $firstColor['color']->slug);
                if($installPhotos) : ?>
                <div class="col-12 product-install-photos <?php if($active){ echo 'active'; }?>" data-install="<?php echo $term->term_id ?>">
                    <div class="row justify-content-center">
                        <?php foreach($installPhotos as $photo) : ?>
                            <div class="col-sm-6 col-md-4 col-lg-3">
                                <a href="<?php echo $photo['install_photo']['url'] ?>" class="glightbox" data-gallery="<?php echo $term->term_id ?>">
                                    <img src="<?php echo $photo['install_photo']['url'] ?>" />
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif;
                $counter++;
            endforeach; ?>
        </div>
    <?php endif; ?>
</div>