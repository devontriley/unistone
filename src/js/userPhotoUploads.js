let $ = jQuery

class UserPhotoUploads {
    constructor() {
        this.form = $('#user-photo-uploads')
        this.input = $('#user-photos')
        this.preview = $('#user-photo-preview')
        this.submit = $('button[type="submit"]', this.form)
        this.loader = $('.loader')
        this.fileTypes = [
            'image/jpg',
            'image/jpeg',
            'image/png'
        ]
        this.message = $('#form-message')

        this.input.on('change', e => {
            e.preventDefault()
            this.onChange()
        })

        this.form.on('submit', e => {
            this.onSubmit(e)
        })

        $('body').on('click', '.remove-photo', (e) => {
            this.removePhoto(e)
        })
    }

    validFileType(file) {
        return this.fileTypes.includes(file.type)
    }

    returnFileSize(number) {
        if (number < 1024) {
            return `${number} bytes`;
        } else if (number >= 1024 && number < 1048576) {
            return `${(number / 1024).toFixed(1)} KB`;
        } else if (number >= 1048576) {
            return `${(number / 1048576).toFixed(1)} MB`;
        }
    }

    removePhoto(e) {
        e.preventDefault()

        const index = parseInt(e.currentTarget.dataset.fileIndex)
        const dt = new DataTransfer()
        const { files } = this.input[0]

        for (let i = 0; i < files.length; i++) {
            const file = files[i]
            if (index !== i)
                dt.items.add(file) // here you exclude the file. thus removing it.
        }

        console.log(dt)
        console.log(dt.files)

        this.input[0].files = dt.files // Assign the updates list

        this.onChange()
    }

    clearFiles() {
        const dt = new DataTransfer()
        // Clear selected files
        this.input[0].files = dt.files
        // Clear selected file preview box
        this.onChange(true)
    }

    onChange( reset = false ) {
        // Empty preview div contents
        this.preview.empty()

        console.log('test')

        // Store selected files in variable
        const curFiles = reset ? [] : this.input[0].files;

        // If there are no files, return an error message
        if (curFiles.length === 0) {
            this.preview.html('<p>No photos currently selected</p>');
            this.submit.prop('disabled', true)
        // If there are files, continue to outputting data about each one
        } else {
            let html = '<ul>'

            let i = 0
            for(const file of curFiles) {
                if(this.validFileType(file)) {
                    html += '<li>'
                    html += ``
                    html += `<div>`
                    html += `<img src="${URL.createObjectURL(file)}" />`
                    html += `<div>`
                    html += `<span class="filesize">`
                    html += `${file.name}<br />`
                    html += `${this.returnFileSize(file.size)}`
                    html += `</span>`
                    html += `<button class="remove-photo" type="button" data-file-index="${i}">`
                    html += `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/><path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/></svg>`
                    html += `</button>`
                    html += `</div>`
                    html += `</div>`
                } else {
                    html += '<li class="invalid-filetype">'
                    html += `${file.name} (${this.returnFileSize(file.size)})<br />`
                    html += `Invalid file type`
                }

                html += '</li>'

                i++
            }

            html += '</ul>'

            this.preview.html(html)
            this.submit.prop('disabled', false)
        }
    }

    onSubmit(e) {
        e.preventDefault()

        this.submit.prop('disabled', true)
        this.loader.removeClass('d-none')
        this.message.removeClass('success error').html('')

        let formData = new FormData(this.form[0])

        // Validate logged in user
        if (formData.get('userEmail') === '') {
            console.log('Must be logged in')
            return
        }

        let length = document.getElementById('user-photos').files.length;
        for (let x = 0; x < length; x++) {
            formData.append("filesToUpload[]", document.getElementById('user-photos').files[x]);
        }

        console.log(...formData)

        $.ajax({
            url: ajaxurl,
            type: 'post',
            dataType: 'json',
            data: formData,
            processData: false,
            contentType: false,
            success: response => {
                this.handleSuccess(response)
            },
            error: error => {
                this.handleError(error)
            }
        })
    }

    handleSuccess(response) {
        console.log(response)
        const { data } = response
        this.clearFiles()
        this.loader.addClass('d-none')
        this.message.addClass('success').html('Thanks for sending us your installation photos!')
    }

    handleError(error) {
        console.log(error)
        this.loader.addClass('d-none')
        this.message.addClass('error').html('There was an error uploading your photos. Check the file type and size and try again.')
        this.submit.prop('disabled', false)
    }
}

export default UserPhotoUploads