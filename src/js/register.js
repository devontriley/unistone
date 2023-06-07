function checkPasswordStrength( $pass1, $pass2, $strengthResult, $submitButton, blacklistArray )
{
    let pass1 = $pass1.val();
    let pass2 = $pass2.val();

    // Reset the form & meter
    $submitButton.attr( 'disabled', 'disabled' );
    $strengthResult.removeClass('short bad good strong active');

    // Extend our blacklist array with those from the inputs & site data
    blacklistArray = blacklistArray.concat( wp.passwordStrength.userInputDisallowedList() )

    // Get the password strength
    let strength = wp.passwordStrength.meter( pass1, blacklistArray, pass2 );

    // Add the strength meter results
    switch ( strength ) {
        case 2:
            $strengthResult.addClass('bad active').html( pwsL10n.bad );
            break;

        case 3:
            $strengthResult.addClass('good active').html( pwsL10n.good );
            break;

        case 4:
            $strengthResult.addClass('strong active').html( pwsL10n.strong );
            break;

        case 5:
            $strengthResult.addClass('short active').html( pwsL10n.mismatch );
            break;

        default:
            $strengthResult.addClass('short active').html( pwsL10n.short );

    }

    // The meter function returns a result even if pass2 is empty,
    // enable only the submit button if the password is strong and
    // both passwords are filled up
    if ( (4 === strength || 3 === strength) && '' !== pass2.trim() ) {
        $submitButton.removeAttr( 'disabled' );
    }

    return strength;
}

class USIRegistrationForm {
    constructor() {
        this.form = jQuery('#usiRegistrationForm')
        this.formMessage = jQuery('.formMessage', this.form)
        this.submitBtn = jQuery('#registrationSubmit', this.form)

        this.form.on('submit', e => {
            const formValues = jQuery(e.target).serializeArray()
            this.handleSubmit(formValues)
            e.preventDefault()
        })

        // Binding to trigger checkPasswordStrength
        jQuery('body').on('keyup', 'input[name=password], input[name=confirmPassword]', function(e) {
            checkPasswordStrength(
                jQuery('input[name=password]'),         // First password field
                jQuery('input[name=confirmPassword]'), // Second password field
                jQuery('#password-strength'),           // Strength meter
                jQuery('#registrationSubmit'),           // Submit button
                []        // Blacklisted words
            );
        });
    }

    handleSubmit(formValues)
    {
        this.submitBtn.prop('disabled', true)

        // Clear all error messages
        jQuery('.errorMessage').text('').removeClass('active')
        this.formMessage.text('').removeClass('error success active')

        jQuery.ajax({
            url: ajaxurl,
            method: 'post',
            data: {
                action: 'usiRegisterSubmission',
                formValues: formValues
            },
            success: response => {
                let data = response.data

                console.log(data)

                // Check for errors and add error messages to html
                if(!jQuery.isEmptyObject(data.errors)) {
                    jQuery.each(data.errors, (index, value) => {
                        jQuery('input[name="'+index+'"]').siblings('.errorMessage').text(value.error).addClass('active')
                    })
                    this.submitBtn.prop('disabled', false)
                } else {
                    if(data.userExists) {
                        this.formMessage.text('A user with this email already exists').addClass('error active')
                        this.submitBtn.prop('disabled', false)
                        return
                    }

                    // Add new user
                    this.addNewUser(data.formValues)
                }

            },
            error: err => {
                console.log(err)
            }
        })
    }

    addNewUser(formValues)
    {
        const userData = {}

        jQuery.each(formValues, (index, value) => {
            userData[value['name']] = value['value']
        })

        jQuery.ajax({
            url: ajaxurl,
            method: 'post',
            data: {
                action: 'usiAddNewUser',
                formValues: userData
            },
            success: response => {
                console.log('add new user')
                console.log(response)

                this.submitBtn.prop('disabled', false)
                this.formMessage.text('Your account is pending review. You will receive an email once it is confirmed.').addClass('success active')
            },
            error: err => {
                console.log(err)
            }
        })
    }
}

export default USIRegistrationForm