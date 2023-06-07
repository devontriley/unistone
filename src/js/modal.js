class USIModal {
    constructor() {
        this.modalID = null;
        this.dialog = null;
        this.dialogInner = null;
        this.dialogBody = null;
        this.dialogCloseButton = null;

        // Link click handler
        document.addEventListener('click', (e) => {
            if ( !e.target.hasAttribute( 'data-usi-modal-id' ) ) return;

            e.preventDefault();

            const link = e.target;
            this.modalID = link.getAttribute('data-usi-modal-id');

            if ( !this.modalID ) return

            this.createModal();
        });

        // Close button click handler
        document.addEventListener('click', (e) => {
            if ( !this.dialogCloseButton ) return;
            if ( e.target !== this.dialogCloseButton && !this.dialogCloseButton.contains( e.target ) ) return;

            e.preventDefault();

            this.closeModal();
        });

        // Dialog backdrop click handler
        document.addEventListener('click', (e) => {
            if ( e.target === this.dialog && e.target !== this.dialogInner ) {
                this.closeModal();
            }
        });
    }

    createModal() {
        this.dialog = document.createElement( 'dialog' );
        this.dialog.id = 'usi-modal';

        this.dialogInner = document.createElement( 'div' )
        this.dialogInner.classList.add( 'usi-dialog-inner' )

        const dialogHeader = document.createElement( 'div' );
        dialogHeader.classList.add( 'usi-modal-header' );

        this.dialogCloseButton = document.createElement( 'button' );
        this.dialogCloseButton.classList.add( 'usi-modal-close' );
        this.dialogCloseButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">\n' +
            '  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>\n' +
            '  <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>\n' +
            '</svg>';

        dialogHeader.appendChild( this.dialogCloseButton );

        this.dialogBody = document.createElement( 'div' );
        this.dialogBody.classList.add( 'usi-modal-body' );

        this.dialogInner.appendChild( dialogHeader );
        this.dialogInner.appendChild( this.dialogBody );

        this.dialog.appendChild( this.dialogInner )
        document.body.appendChild( this.dialog );

        this.fetchModalContent();
    }

    fetchModalContent() {
        // Ajax request to get modal content
        jQuery.ajax({
            url: ajaxurl,
            method: 'post',
            data: {
                action: 'fetch_modal_content',
                modalID: this.modalID
            }
        })
            .then(response => {
                if ( !response.success ) {
                    throw new Error( response.data[0].message )
                }

                const data = response.data

                this.appendModalContent( data.html )
            })
            .catch(error => {
                console.log( error )
            })
    }

    appendModalContent( html ) {
        this.dialogBody.innerHTML += html

        this.showModal()
    }

    showModal() {
        this.disableScroll();
        this.dialog.showModal();
    }

    closeModal() {
        this.dialog.close();
        this.enableScroll();
        this.destroyModal();
    }

    destroyModal() {
        this.dialog.remove();
        this.dialog = null;
        this.dialogInner = null;
        this.dialogBody = null;
        this.dialogCloseButton = null;
    }

    disableScroll() {
        document.body.style.overflow = 'hidden';
    }

    enableScroll() {
        document.body.style.overflow = '';
    }
}

export default USIModal