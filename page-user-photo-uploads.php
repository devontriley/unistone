<?php get_header(); ?>

<?php
$user = wp_get_current_user();
?>

<div class="container user-photo-uploads-container my-5">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="user-photo-uploads-overlay">
                <h1 class="h2">Send us your installation photos</h1>
                <p>
                    Upload your Universal Stone installation photos. By uploading your photos you consent to Universal Stone Imports using them in marketing campaigns. Uploading photos not depicting Universal Stone installations could result in action being taken against your account.
                </p>
                <p>
                    Please follow this file naming format: <span class="fst-italic">{Color}_{Product Name}.jpg</span>
                </p>
                <p class="fst-italic">
                    Photos must be jpg or png filetype and no larger than 40MB in size.
                </p>

                <form id="user-photo-uploads" class="user-photo-uploads" method="post" enctype="multipart/form-data">
                    <input name="action" value="upload_user_photos_to_dropbox" type="hidden"/>
                    <input name="userEmail" value="<?php echo $user->get('user_email'); ?>" type="hidden" />

                    <div class="field-group">
                        <label for="user-photos" class="btn btn-secondary">Choose photos to upload</label>
                        <input
                                id="user-photos"
                                class="form-control visually-hidden"
                                name="userPhotos"
                                type="file"
                                accept=".jpg, .jpeg, .png"
                                multiple="true"
                        />
                    </div>

                    <div id="user-photo-preview" class="user-photo-preview">
                        No photos currently selected
                    </div>

                    <button disabled type="submit" class="btn btn-primary">Upload Photos</button>
                </form>

                <div id="form-message"></div>
            </div>

            <div class="loader d-none spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
