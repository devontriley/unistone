<?php get_header() ?>

<?php
$productType = $_GET['productType'];

// Get all top level product type categories
$productTypes = get_terms(array(
    'taxonomy' => 'product-type',
    'parent' => 0,
    'hide_empty' => true
));
?>

<div id="products-page-container" class="products-page-container container-fluid active">
    <div class="row">
        <div class="col-12 text-center">
            <p>Browse products by:</p>
            <ul class="products-page-nav">
                <?php foreach ($productTypes as $k => $v) :
                    $slug = $v->slug; ?>
                    <li>
                        <button name="<?php echo $v->slug ?>">
                            <?php echo $v->name ?>
                        </button>
                    </li>
                <?php endforeach; ?>

                <li class="<?php if($productType === 'color'){ echo 'active'; } ?>">
                    <button name="color">Color</button>
                </li>
            </ul>
        </div>
        <div class="col-12">
            <div class="product-loader active">
                <div class="product-loading spinner-border" role="status"></div>
            </div>
            <div class="products-page-products row"></div>
        </div>
    </div>
</div>

<?php get_footer() ?>
