class USILoginForm {
    constructor() {
        console.log('new usi login form')
        this.form = jQuery('#usiLoginForm')
        this.formMessage = jQuery('.formMessage', this.form)

        this.form.on('submit', e => {
            e.preventDefault()
            const email = jQuery('input[name=email]').val()
            const password = jQuery('input[name=password]').val()
            //const nonce = jQuery('input[name="_wpnonce"]').val()
            this.handleSubmit(email, password)
        })
    }

    handleSubmit(email, password) {
        this.formMessage.text('').removeClass('error success active')

        grecaptcha.ready(() => {
            grecaptcha.execute('6Leny8chAAAAAG9VA9cEjcPpBrtrxfzBfMouIb2o', {action: 'login'})
                .then((token) => {

                    jQuery.ajax({
                        url: ajaxurl,
                        method: 'post',
                        data: {
                            action: 'usiLoginSubmission',
                            email: email,
                            password: password,
                            recaptchaToken: token
                            //nonce: nonce
                        },
                        success: response => {
                            const data = response.data

                            console.log(response)

                            // Handle errors and display message
                            if(!response.success) {
                                const message = data['error_message']
                                this.formMessage.html('<p class="fs-6"><i>'+message+'</i></p>').addClass('active error')
                                return
                            }

                            // Redirect user to appropriate page
                            if(data.redirect) {
                                window.location.replace(data.redirect)
                            }
                        },
                        error: err => {
                            console.log(err)
                        }
                    })
                }
            );
        });
    }
}

export default USILoginForm