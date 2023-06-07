import GLightbox from 'glightbox'

class Gallery {
    constructor() {
        this.lightbox = GLightbox()
        this.isPreview = window.galleryModulePreview;
        this.galleryImagesContainer = document.getElementById('gallery_images_container')
        this.galleryLoadMore = document.getElementById('gallery_load_more')
        this.clearGalleryFilters = document.getElementById('clear_gallery_filters')
        this.currentPage = 1

        // Gallery filter button click
        jQuery('body').on('click', e => {
            jQuery('.gallery_filter').removeClass('active')
            const parent = e.target.closest('.gallery_filter')
            if(parent) {
                jQuery(parent).toggleClass('active')
            }
        })

        jQuery('body').on('change', '#gallery_filters input', e => {
            jQuery(e.target).parents('.form-check').siblings('.form-check').find('input').prop('checked', false)
            this.currentPage = 1
            this.fetchGalleryImages()
        })

        // Fetch gallery images on page load
        this.fetchGalleryImages(false, this.isPreview)

        if(this.clearGalleryFilters)
        {
            this.clearGalleryFilters.addEventListener('click', e => {
                this.currentPage = 1
                jQuery('#gallery_filters input').prop('checked', false)
                jQuery(this.galleryLoadMore).removeClass('d-none')
                this.fetchGalleryImages()
            })
        }

        jQuery(this.galleryLoadMore).on('click', () => {
            this.currentPage++
            this.fetchGalleryImages(true)
        })
    }

    fetchGalleryImages(loadMore = false, preview = false)
    {
        let colorsValues = []
        let applicationValues = []

        const colorFilters = document.querySelectorAll('input[name="color_filter"]:checked')
        const colorFiltersArray = Array.from(colorFilters)
        colorsValues = colorFiltersArray.map(e => e.value)

        const applicationFilters = document.querySelectorAll('input[name="application_filter"]:checked')
        const applicationFiltersArray = Array.from(applicationFilters)
        applicationValues = applicationFiltersArray.map(e => e.value)

        jQuery.post({
            url: ajaxurl,
            method: 'POST',
            'data': {
                action: 'fetch_gallery_images',
                applicationValues: applicationValues,
                colorsValues: colorsValues,
                currentPage: this.currentPage,
                isPreview: this.isPreview
            },
            success: response => {
                const data = response.data

                if(!loadMore) jQuery(this.galleryImagesContainer).empty()

                if(this.currentPage === data.pages || data.found_images === 0 || this.isPreview) {
                    jQuery(this.galleryLoadMore).addClass('d-none')
                } else {
                    jQuery(this.galleryLoadMore).removeClass('d-none')
                }

                jQuery(this.galleryImagesContainer).append(data.html)

                this.lightbox.reload()
            }
        })
    }
}

export default new Gallery()