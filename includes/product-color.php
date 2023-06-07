<?php
$selectedColor = ($variant === 'false') ? null : $variant;
$selectedProduct = null;
$term = get_term_by('slug', $slug, 'color');
$termID = $term->term_id;
$colorTerm = get_terms(array(
    'taxonomy' => 'color',
    'include' => $termID,
    'orderby' => 'include'
));
$colorTermImage = get_field('color_swatch', 'color_'.$termID);
$thumbnail = get_field('color_image', 'term_'.$termID);
$products = new WP_Query(array(
    'post_type' => 'products',
    'posts_per_page' => -1,
    'tax_query' => array(
        array(
            'taxonomy' => 'color',
            'field' => 'id',
            'terms' => $termID
        )
    )
));
$productsArray = array();
if($products->found_posts) :
    foreach($products->posts as $product) :
        if($selectedColor === $product->post_name) $selectedProduct = $product;

        $productsArray[$product->ID] = array(
            'id' => $product->ID,
            'name' => $product->post_title,
            'slug' => $product->post_name
        );

        $productColors = get_field('colors', $product->ID);
        foreach($productColors as $productColor) :
            $productColorTerm = $productColor['color'];
            if($productColorTerm->term_id == $termID) :
                $productsArray[$product->ID]['image'] = $productColor['image'];
                $productsArray[$product->ID]['description'] = $productColor['description'];
            endif;
        endforeach;
    endforeach;
endif;
?>

<div class="product-container">
    <div class="row align-items-center">
        <div class="col-md-6 product-image">
            <img data-slug="<?php echo $slug ?>" data-id="<?php echo $termID; ?>" class="<?php if(!$selectedColor){ echo 'active'; }?>" src="<?php echo $thumbnail['url'] ?>" />
            <?php if($productsArray) :
                foreach($productsArray as $product) : ?>
                    <img class="<?php if($selectedColor === $product['slug']){ echo 'active'; }?>" data-id="<?php echo $product['id']; ?>" src="<?php echo $product['image']['url']; ?>" />
                <?php endforeach;
            endif;
            ?>
        </div>
        <div class="col-md-6 product-details">
            <h2 data-name="<?php echo $name ?>">
                <?php echo $colorTerm[0]->name ?> <span class="product-color-name"><?php if($selectedProduct){ echo ' - ' . $selectedProduct->post_title; }?></span>
            </h2>

            <div data-id="<?php echo $termID ?>" class="product-description <?php if(!$selectedColor){ echo 'active'; }?>">
                <p><?php echo $colorTerm[0]->description ?></p>
            </div>

            <?php
            if(!empty($productsArray)) :
                foreach($productsArray as $product) : ?>
                    <div data-id="<?php echo $product['id'] ?>" class="product-description <?php if($selectedColor === $product['slug']){ echo 'active'; }?>">
                        <p>
                            <?php echo $product['description'] ?>
                        </p>
                    </div>
                <?php endforeach;
            endif;
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-12 product-colors">
            <button class="color <?php if(!$selectedColor){ echo 'active'; }?>" data-id="<?php echo $termID ?>" data-name="<?php echo $colorTerm[0]->name ?>">
                <img src="<?php echo $colorTermImage['url'] ?>" />
            </button>
            <?php
            if(!empty($productsArray)) :
                foreach($productsArray as $product) : ?>
                    <button class="color <?php if($selectedColor === $product['slug']){ echo 'active'; }?>" data-id="<?php echo $product['id'] ?>" data-name="<?php echo $product['name'] ?>" data-slug="<?php echo $product['slug'] ?>">
                        <img src="<?php echo $product['image']['url']; ?>" />
                    </button>
                <?php endforeach;
            endif;
            ?>
        </div>
        <?php if($installPhotos) : ?>
            <div class="col-12 product-install-photos">
                <div class="row">
                    <?php foreach($installPhotos as $photo) : ?>
                        <div class="col-md-3">
                            <img src="<?php echo $photo['install_photo']['url'] ?>" />
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>