<?php get_header() ?>

<div class="post-single-container">
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="featured-image">
                    <?php echo get_the_post_thumbnail(get_the_ID(), 'full'); ?>
                </div>
                <?php the_content() ?>
            </div>
        </div>
    </div>
</div>

<?php include('modules.php') ?>

<?php get_footer() ?>
