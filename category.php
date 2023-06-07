<?php get_header(); ?>

<?php
$query = get_queried_object();
?>

<?php if(!$posts) : ?>
    <div class="container my-5">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <p>There are no posts in this category</p>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if($posts) : ?>
    <div class="post-grid">
        <div class="container">
            <h1><?php echo $query->name ?></h1>
            <div class="row">
                <?php foreach($posts as $p) : ?>
                    <div class="col-md-4">
                        <div class="post-thumbnail">
                            <a href="<?php echo get_permalink($p->ID) ?>" class="cover-link"></a>
                            <div class="hover-overlay">
                                <div class="copy">
                                    <p class="post-title"><?php echo $p->post_title ?></p>
                                    <p class="post-excerpt"><?php echo $p->post_excerpt ?></p>
                                </div>
                            </div>
                            <div class="title">
                                <p class="post-title"><?php echo $p->post_title ?></p>
                            </div>
                            <?php echo get_the_post_thumbnail($p->ID, 'large'); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php get_footer(); ?>
