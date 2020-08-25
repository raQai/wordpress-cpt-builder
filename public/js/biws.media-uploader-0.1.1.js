; (function (biws) {
    /**
     * @param containerSelector the selector of the container to apply the action to
     * @param inputSelector the input element to put the value in
     * @param imageContainerSelector the container where to put the image. Will overwrite all content html
     * @param setImageLinkSelector the trigger to show the media frame
     * @param removeImageLinkSelector the trigger to remove the value from the input
     */
    biws.customMediaUploader = options => {
        const defaults = {
            containerSelector: null,
            inputSelector: null,
            imageContainerSelector: null,
            setImageLinkSelector: null,
            removeImageLinkSelector: null
        };

        let settings = Object.assign({}, defaults, options),
            frame;

        /**
         * error if selectors are missing
         */
        if (!settings.containerSelector ||
            !settings.inputSelector ||
            !settings.imageContainerSelector ||
            !settings.setImageLinkSelector ||
            !settings.removeImageLinkSelector) {
            throw new Error('customMediaUploader called with invalid arguments. All settings values must be specified. ' + JSON.stringify(settings));
        }

        let container = document.querySelector(settings.containerSelector);

        if (!container) {
            throw new Error('customMediaUploader called for invalid containerSelector ' + settings.containerSelector);
        }

        let input = container.querySelectorAll(settings.inputSelector),
            imageContainer = container.querySelectorAll(settings.imageContainerSelector),
            setImageLink = container.querySelectorAll(settings.setImageLinkSelector),
            removeImageLink = container.querySelectorAll(settings.removeImageLinkSelector);

        let hide = element => element.classList.add('hidden'),
            show = element => element.classList.remove('hidden'),
            imgHTML = url => `<img src="${url}" alt="" style="max-width:100%;"/>`;

        let setImage = frame => {
            // If the media frame already exists, reopen it.
            if (frame) {
                frame.open();
                return;
            }

            // Create a new media frame
            frame = wp.media({
                title: 'Select or Upload Media',
                button: {
                    text: 'Use this media'
                },
                multiple: false // Set to true to allow multiple files to be selected
            });

            // When an image is selected in the media frame...
            frame.on('select', () => {
                // Get media attachment details from the frame state
                let attachment = frame.state().get('selection').first().toJSON();
                // Set the attachment id to our input
                input.forEach(element => element.value = attachment.id);
                // set the attachment thmbnail preview to the image container
                imageContainer.forEach(element => element.innerHTML = imgHTML(attachment.sizes.thumbnail.url));
                // Hide the set image link trigger
                setImageLink.forEach(element => hide(element))
                // Show the remove image link trigger
                removeImageLink.forEach(element => show(element))
            });

            // Finally, open the modal on click
            frame.open();
        }

        let removeImage = () => {
            // Delete the image id from the input
            input.forEach(element => element.value = '');
            // Clear image preview
            imageContainer.forEach(element => element.innerHTML = '');
            // Show the set image link trigger
            setImageLink.forEach(element => show(element))
            // Hide the remove image link trigger
            removeImageLink.forEach(element => hide(element))
        };

        setImageLink.forEach(trigger => {
            trigger.addEventListener('click', event => {
                if (!event.target.matches(settings.setImageLinkSelector)) {
                    return;
                }
                event.preventDefault();
                setImage(frame);
            })
        });

        imageContainer.forEach(trigger => {
            trigger.addEventListener('click', event => {
                if (!event.target.matches(`${settings.imageContainerSelector} > *`)) {
                    return;
                }
                event.preventDefault();
                setImage(frame);
            })
        });

        removeImageLink.forEach(trigger => {
            trigger.addEventListener('click', event => {
                if (!event.target.matches(settings.removeImageLinkSelector)) {
                    return;
                }
                event.preventDefault();
                removeImage();
            })
        });
    }

}(window.biws = window.biws || {}))