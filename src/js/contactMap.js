class ContactMap {
    constructor() {
        this.changeMapButtons = jQuery('.change_map')
        this.mapIframe = jQuery('.contact_map iframe')

        this.changeMapButtons.on('click', e => {
            e.preventDefault()
            this.handleChange(e)
        })
    }

    handleChange(e) {
        const src = jQuery(e.target).attr('data-map-src')
        this.changeMapButtons.removeClass('active')
        jQuery(e.target).addClass('active')
        this.mapIframe.attr('src', src)
    }
}

export default ContactMap