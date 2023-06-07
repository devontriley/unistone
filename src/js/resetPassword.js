class USIResetPasswordForm {
    constructor() {
        console.log('new usi reset password form')
        this.form = jQuery('#usiResetPasswordForm')
        this.formMessage = jQuery('.formMessage', this.form)

        this.form.on('submit', e => {
            e.preventDefault()
            const email = jQuery('input[name=email]').val()
            const nonce = jQuery('input[name="_wpnonce"]').val()
            this.handleSubmit(email, nonce)
        })
    }

    handleSubmit(email, nonce) {
        this.formMessage.text('').removeClass('error success active')

        jQuery.ajax({
            url: ajaxurl,
            method: 'post',
            data: {
                action: 'sendPasswordResetEmail',
                email: email,
                security: nonce
            },
            success: response => {
                const data = response.data

                if(!data) {
                    console.log('no data returned')
                    return
                }

                console.log(response)

                // Handle errors and display message
                if(!response.success) {
                    const message = data['error_message']
                    this.formMessage.html('<p class="fs-6"><i>'+message+'</i></p>').addClass('active error')
                    return
                }

                const message = 'Please check your email for a password reset link.'
                this.formMessage.html('<p class="fs-6"><i>'+message+'</i></p>').addClass('active success')
            },
            error: err => {
                console.log(err)
            }
        })
    }
}

export default USIResetPasswordForm