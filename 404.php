<?php get_header(); ?>

<?php
$page = get_page_by_path('not-found');
$modulesPageID = $page->ID;
include('modules.php')
?>

<?php get_footer(); ?>
