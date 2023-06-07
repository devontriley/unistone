<?php
if($color) {
    $title = $color->name;
    $id = $color->term_id;
    $slug = $color->slug;
    $image = get_field('color_image', 'term_'.$id);
    $url = '';
}

if($product) {
    $title = $product->post_title;
    $id = $product->ID;
    $slug = $product->post_name;
    $image = get_field('thumbnail', $id);
    $url = get_permalink($id);
}

if($child) {
    $title = $child->name;
    $id = $child->term_id;
    $slug = $child->slug;
    $image = get_field('thumbnail', 'product-type_38');
    $url = null;
}

$colWidth = $productType === 'veneer' ? 4 : 3
?>

<div class="col-6 col-md-<?php echo $colWidth ?>">
    <div class="product-thumbnail">
        <a href="#"
           class="product-thumbnail-link"
           data-child="<?php echo boolval($child) ?>"
           data-slug="<?php echo $slug ?>"
           data-id="<?php echo $id ?>"
           data-name="<?php echo strtolower($title) ?>"
        >
            <div class="product-thumbnail-image">
                <?php if ($image) { ?>
                    <img src="<?php echo $image['url'] ?>" style="width: 100%;" />
                <?php } ?>
            </div>
            <span class="product-title"><?php echo $title; ?></span>
        </a>
    </div>
</div>

<?php
unset($child, $product, $color);
?>
