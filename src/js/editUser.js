class EditUserProfile {
    constructor() {
        this.form = jQuery('#editUserProfileForm')
        this.formMessage = jQuery('.formMessage', this.form)
        this.submitBtn = jQuery('#editUserProfileSubmit', this.form)

        this.form.on('submit', e => {
            const formValues = jQuery(e.target).serializeArray()
            const email = jQuery('input[name=email]').val()
            const nonce = jQuery('input[name="_wpnonce"]').val()
            this.handleSubmit(formValues, email, nonce)
            e.preventDefault()
        })
    }

    handleSubmit(formValues, email, nonce)
    {
        this.submitBtn.prop('disabled', true)

        this.formMessage.text('').removeClass('error success active')

        jQuery.ajax({
            url: ajaxurl,
            method: 'post',
            data: {
                action: 'usiEditProfileSubmission',
                formValues: formValues,
                email: email,
                nonce: nonce
            },
            success: response => {
                const data = response.data

                console.log(response)

                this.submitBtn.prop('disabled', false)

                // Handle errors and display message
                if(!response.success) {
                    this.formMessage.text(data.error).addClass('active')
                    return
                }

                if(data.redirect) {
                    window.location.replace(data.redirect)
                }
            },
            error: err => {
                console.log(err)
            }
        })
    }
}

export default EditUserProfile