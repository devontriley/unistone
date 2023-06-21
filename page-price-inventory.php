<?php // Template Name: Price & Inventory
get_header(); ?>

<?php if ( !is_user_logged_in() ) { ?>

    You shouldn't be here

<?php } else { ?>

    <?php
    $user = wp_get_current_user();
    $filePath = get_template_directory().'/includes/sapReports/SAP Reports';
    $files = array_diff( scandir( $filePath ), array( '.', '..' ) );
    $sortedFiles = array(
        'east_coast' => array(),
        'west_coast' => array(),
        'so_east_coast' => array(),
        'so_west_coast' => array(),
        'nsd' => array()
    );

    foreach ( $files as $filename ) :
        switch ( $filename ) {
            case strpos( $filename, 'USI East Coast -' ) !== false:
                array_push( $sortedFiles['east_coast'], $filename );
                break;
            case strpos( $filename, 'USI West Coast -' ) !== false:
                array_push( $sortedFiles['west_coast'], $filename );
                break;
            case strpos( $filename, 'USI West Coast SO' ) !== false:
                array_push( $sortedFiles['so_west_coast'], $filename );
                break;
            case strpos( $filename, 'USI East Coast SO' ) !== false:
                array_push( $sortedFiles['so_east_coast'], $filename );
                break;
            case strpos( $filename, 'USI NSD' ) !== false:
                array_push( $sortedFiles['nsd'], $filename );
                break;
        }
    endforeach;

    // File names to use
    // Price and Inventory - Excel
    // Price and Inventory - PDF
    // Price and Inventory - PO Form
    // Price and Inventory - PO Form and Weight Calculator

    function displayFiles( $title, $files ) { ?>
        <div class="role-container-column col-md-6">
            <div class="role-container p-5 shadow bg-white border" style="border-bottom: 10px solid var(--green) !important; border-radius: 10px;">
                <h3><?php echo $title ?></h3>

                <ul style="list-style: none; margin: 0; padding: 0;">
                    <?php foreach ( $files as $file ) :
                        $pathInfo = pathinfo( $filePath.'/'.$file );
                        $extension = $pathInfo['extension'];
                        $title = $file;
                        switch ( $file ) {
                            case strpos( $file, 'Simple PDF' ) !== false:
                                $title = 'Price and Inventory PDF';
                                break;
                            case strpos( $file, 'Simple Excel' ) !== false:
                                $title = 'Price and Inventory Excel';
                                break;
                            case strpos( $file, 'PO Form - ' ) !== false:
                                $title = 'Price and Inventory PO Form';
                                break;
                            case strpos( $file, 'Weight Calculator' ) !== false:
                                $title = 'Price and Inventory PO Form and Weight Calculator';
                                break;
                        }
                        ?>
                        <li class="file">
                            <div class="filetype-icon-container">
                                <?php if ( $extension === 'xlsx' ) : ?>
                                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0zM9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1zM5.884 6.68 8 9.219l2.116-2.54a.5.5 0 1 1 .768.641L8.651 10l2.233 2.68a.5.5 0 0 1-.768.64L8 10.781l-2.116 2.54a.5.5 0 0 1-.768-.641L7.349 10 5.116 7.32a.5.5 0 1 1 .768-.64z"/>
                                    </svg>
                                <?php elseif ( $extension === 'pdf' ) : ?>
                                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M5.523 12.424c.14-.082.293-.162.459-.238a7.878 7.878 0 0 1-.45.606c-.28.337-.498.516-.635.572a.266.266 0 0 1-.035.012.282.282 0 0 1-.026-.044c-.056-.11-.054-.216.04-.36.106-.165.319-.354.647-.548zm2.455-1.647c-.119.025-.237.05-.356.078a21.148 21.148 0 0 0 .5-1.05 12.045 12.045 0 0 0 .51.858c-.217.032-.436.07-.654.114zm2.525.939a3.881 3.881 0 0 1-.435-.41c.228.005.434.022.612.054.317.057.466.147.518.209a.095.095 0 0 1 .026.064.436.436 0 0 1-.06.2.307.307 0 0 1-.094.124.107.107 0 0 1-.069.015c-.09-.003-.258-.066-.498-.256zM8.278 6.97c-.04.244-.108.524-.2.829a4.86 4.86 0 0 1-.089-.346c-.076-.353-.087-.63-.046-.822.038-.177.11-.248.196-.283a.517.517 0 0 1 .145-.04c.013.03.028.092.032.198.005.122-.007.277-.038.465z"/>
                                        <path fill-rule="evenodd" d="M4 0h5.293A1 1 0 0 1 10 .293L13.707 4a1 1 0 0 1 .293.707V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2zm5.5 1.5v2a1 1 0 0 0 1 1h2l-3-3zM4.165 13.668c.09.18.23.343.438.419.207.075.412.04.58-.03.318-.13.635-.436.926-.786.333-.401.683-.927 1.021-1.51a11.651 11.651 0 0 1 1.997-.406c.3.383.61.713.91.95.28.22.603.403.934.417a.856.856 0 0 0 .51-.138c.155-.101.27-.247.354-.416.09-.181.145-.37.138-.563a.844.844 0 0 0-.2-.518c-.226-.27-.596-.4-.96-.465a5.76 5.76 0 0 0-1.335-.05 10.954 10.954 0 0 1-.98-1.686c.25-.66.437-1.284.52-1.794.036-.218.055-.426.048-.614a1.238 1.238 0 0 0-.127-.538.7.7 0 0 0-.477-.365c-.202-.043-.41 0-.601.077-.377.15-.576.47-.651.823-.073.34-.04.736.046 1.136.088.406.238.848.43 1.295a19.697 19.697 0 0 1-1.062 2.227 7.662 7.662 0 0 0-1.482.645c-.37.22-.699.48-.897.787-.21.326-.275.714-.08 1.103z"/>
                                    </svg>
                                <?php endif; ?>
                            </div>
                            <a href="<?php echo get_template_directory_uri().'/includes/sapReports/SAP Reports/'.$file ?>" target="_blank">
                                <?php echo $title ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php } ?>

    <div class="container my-5">

        <h1 class="text-center mb-5">Pricing & Inventory</h1>

        <p class="text-center mb-5">
            <a href="http://usi.local/wp-content/uploads/2022/12/Helpful-Features-in-the-USI-Price-and-Inventory-Sheet.pdf" target="_blank">
                <span style="vertical-align: middle;">Helpful Features</span>
                <svg style="margin-left: 5px;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M8.636 3.5a.5.5 0 0 0-.5-.5H1.5A1.5 1.5 0 0 0 0 4.5v10A1.5 1.5 0 0 0 1.5 16h10a1.5 1.5 0 0 0 1.5-1.5V7.864a.5.5 0 0 0-1 0V14.5a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h6.636a.5.5 0 0 0 .5-.5z"/>
                    <path fill-rule="evenodd" d="M16 .5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793L6.146 9.146a.5.5 0 1 0 .708.708L15 1.707V5.5a.5.5 0 0 0 1 0v-5z"/>
                </svg>
            </a>
        </p>

        <div class="row">
            <?php if ( count(array_intersect( array( 'um_authorized-dealer', 'administrator' ), $user->roles )) > 0 ) :
                displayFiles( 'East Coast', $sortedFiles['east_coast'] );
            endif; ?>

            <?php if ( count(array_intersect( array( 'um_authorized-dealer-west', 'administrator' ), $user->roles )) > 0 ) :
                displayFiles( 'West Coast', $sortedFiles['west_coast'] );
            endif; ?>

            <?php if ( count(array_intersect( array( 'um_s-1', 'administrator' ), $user->roles )) > 0 ) :
                displayFiles( 'SiteOne East Coast', $sortedFiles['so_east_coast'] );
            endif; ?>

            <?php if ( count(array_intersect( array( 'um_s1-west', 'administrator' ), $user->roles )) > 0 ) :
                displayFiles( 'SiteOne West Coast', $sortedFiles['so_west_coast'] );
            endif; ?>

            <?php if ( count(array_intersect( array( 'um_authorized-ns-dealer', 'administrator' ), $user->roles )) > 0 ) :
                displayFiles( 'No Stock Dealer', $sortedFiles['nsd'] );
            endif; ?>
        </div>

    </div>

<?php } ?>

<?php get_footer(); ?>
