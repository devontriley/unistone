import GLightbox from 'glightbox'
class productsPage
{
    constructor() {
        this.lightbox = GLightbox()
        this.urlParams = null
        this.productType = null
        this.productID = null
        this.productSlug = null
        this.isColor = null
        this.variant = null

        this.productsContainer = jQuery('.products-page-products')
        this.lis = jQuery('.products-page-nav li')
        this.activeLinkType = ''
        this.productLoader = jQuery('.product-loader')

        // Handles clicking between top level product pages (Hardscape, Veneer, Colors)
        jQuery('body').on('click', '.products-page-nav button', e => {
            e.preventDefault()
            jQuery(window).scrollTop(0)
            const name = jQuery(e.target).attr('name')
            // const parentLi = jQuery(e.target).parent('li')
            this.isColor = (name === 'color')

            this.handleButtonClick(name)
        })

        // Handles clicking on a product thumbnail to view the PDP
        jQuery('body').on('click', '.product-thumbnail-link', e => {
            e.preventDefault()
            jQuery(window).scrollTop(0)
            const parent = e.target.closest('a')
            const id = jQuery(parent).data('id')
            const slug = jQuery(parent).data('slug')
            const name = jQuery(parent).data('name')
            const child = jQuery(parent).data('child')

            if (child) {
                this.handleButtonClick(name)
            } else {
                this.handleProductClick(id, name, slug)
            }
        })

        // Handle clicking a PDP color swatch to view a different color
        jQuery('body').on('click', '.product-colors .color', e => {
            e.preventDefault()
            const parent = e.target.closest('button')
            const id = jQuery(parent).data('id')
            const colorName = jQuery('h2').data('name')
            const name = jQuery(parent).data('name')
            const slug = jQuery(parent).data('slug')
            this.handleColorClick(id, name, colorName, slug)
        })

        // Handle PDP color swatch hover to show the color name
        jQuery('body').on('mouseover', '.product-colors .color', e => {
            const parent = e.target.closest('button')
            const name = jQuery(parent).data('name')
            jQuery(parent).append('<span class="color-name">'+name+'</span>')
        })

        // Handles PDP color swatch hover off to hide the color name
        jQuery('body').on('mouseleave', '.product-colors .color', e => {
            const parent = e.target.closest('button')
            const colorName = jQuery('.color-name', parent)
            colorName.remove()
        })

        // Update page when using back/forward browser buttons
        jQuery(window).on('popstate', e => {
            if(window.location.pathname.substr('all-products')) {
                this.loadPage()
            }
        });

        // Load product on page load
        this.loadPage()
    }

    async loadPage()
    {
        await this.updateURLParams()

        if(this.urlParams.has('product')) {
            this.fetchProduct(this.productID, this.isColor, null, this.productSlug, this.variant)
        } else {
            this.fetchProducts(this.productType)
        }
    }

    fetchTermData()
    {
        this.urlParams = new URLSearchParams(window.location.search)

        return new Promise((resolve, reject) => {
            jQuery.post({
                url: ajaxurl,
                method: 'POST',
                'data': {
                    action: 'fetch_term_data',
                    productType: this.urlParams.get('productType'),
                    product: this.urlParams.get('product')
                },
                success: response => {
                    const data = response.data

                    console.log('fetchTermData')
                    console.log(data)

                    this.productType = data.productType

                    let type = this.productType

                    if(data.typeParentTermObject) type = data.typeParentTermObject.slug

                    this.updateActiveNavLink(type)

                    resolve()
                },
                error: error => {
                    reject(error)
                }
            })
        })
    }

    async updateURLParams()
    {
        this.urlParams = new URLSearchParams(window.location.search)
        this.productSlug = this.urlParams.get('product')
        this.productID = this.urlParams.get('id')
        this.isColor = this.urlParams.get('productType') === 'color'
        this.variant = this.urlParams.get('variant')

        await this.fetchTermData()
    }

    updateActiveNavLink(type)
    {
        if (this.activeLinkType !== type) {
            this.activeLinkType = type

            // Reset all nav links
            this.lis.removeClass('active')

            // Set active nav link based on parentType
            this.lis.find('button[name="'+ type +'"]').parent('li').addClass('active')
        }
    }

    handleButtonClick(productType)
    {
        // Update current productType
        this.productType = productType

        // Update url with new productType
        let url = new URL(window.location)
        url.searchParams.set('productType', productType)
        url.searchParams.delete('product')
        url.searchParams.delete('variant')
        window.history.pushState({}, '', url)

        // Fetch products
        this.fetchProducts(productType)
    }

    handleProductClick(id, name, slug)
    {
        // Update url with product ID
        let url = new URL(window.location)
        const isColor = url.searchParams.get('productType') === 'color'
        url.searchParams.set('product', slug)
        if(!isColor) url.searchParams.delete('productType')
        url.searchParams.delete('variant')
        window.history.pushState({}, '', url)

        // Fetch product
        this.fetchProduct(id, isColor, name, slug)
    }

    handleColorClick(id, name, colorName, slug)
    {
        // Update url with color name
        let url = new URL(window.location)
        if(slug) {
            url.searchParams.set('variant', slug)
        } else {
            url.searchParams.delete('variant')
        }
        window.history.pushState({}, '', url)

        // Update image
        jQuery('.product-image img').removeClass('active');
        jQuery('.product-image img[data-id="'+id+'"]').addClass('active');

        // Update thumbnail
        jQuery('.product-colors .color').removeClass('active')
        jQuery('.product-colors .color[data-id="'+id+'"]').addClass('active')

        // Update description
        jQuery('.product-description').removeClass('active')
        jQuery('.product-description[data-id="'+id+'"]').addClass('active')

        // Update h2
        jQuery('.product-details .product-color-name').empty()
        if(colorName !== name) {
            const text = (this.isColor) ? ' - ' + name : name;
            jQuery('.product-details .product-color-name').text( text)
        }

        // Update install photos
        jQuery('.product-install-photos').removeClass('active')
        jQuery('.product-install-photos[data-install="'+id+'"]').addClass('active')
    }

    fetchProducts(productType, termID)
    {
        this.productsContainer.removeClass('active')
        this.productLoader.addClass('active')

        jQuery.post({
            url: ajaxurl,
            method: 'POST',
            'data': {
                action: 'fetch_products',
                productType: productType,
                termID: termID
            },
            success: response => {
                const data = response.data

                console.log('fetchProducts')
                console.log(data)

                this.productLoader.removeClass('active')
                this.productsContainer.empty().append(data.html).addClass('active')
                this.updateURLParams()
            }
        })
    }

    fetchProduct(id, isColor = false, name, slug, variant = false)
    {
        this.productsContainer.removeClass('active')
        this.productLoader.addClass('active')

        jQuery.post({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'fetch_product',
                id: id,
                slug: slug,
                isColor: isColor,
                name: name,
                variant: variant
            },
            success: response => {
                const data = response.data

                this.productLoader.removeClass('active')
                this.productsContainer.empty().append(data.html).addClass('active')

                this.updateURLParams()

                // Initialize lightbox on install photos
                this.lightbox.reload()
            }
        })
    }
}

export default new productsPage()