<?php
global $post;
$componentsPath = 'modules/';

$pageID = $modulesPageID ?? $post->ID;

if(have_rows('modules', $pageID)) :
    while(have_rows('modules', $pageID)): the_row();

        switch(get_row_layout())
        {
            case 'hero':
                include($componentsPath . 'hero/template.php');
                break;

            case 'rich_text':
                include($componentsPath . 'rich_text/template.php');
                break;

            case 'column_content':
                include($componentsPath . 'column_content/template.php');
                break;

            case 'gallery':
                include($componentsPath . 'gallery/template.php');
                break;

            case 'contact_form':
                include($componentsPath . 'contact_form/template.php');
                break;

            case 'single_image':
                include($componentsPath . 'single_image/template.php');
                break;
        }
    endwhile;
endif;