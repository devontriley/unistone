class primaryHeader {
    constructor() {
        this.hamburger = jQuery('.primary_menu .hamburger')

        if(this.hamburger.length)
        {
            this.hamburger.on('click', function(e) {
                const parent = e.target.closest('.hamburger')
                jQuery(parent).toggleClass('is-active')
            })
        }
    }
}

export default new primaryHeader()