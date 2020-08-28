; (function (biws) {
    /**
     * @param containerSelector the selector of the container to apply the action to
     * @param inputSelector the input element to put the value in
     * @param imageContainerSelector the container where to put the image. Will overwrite all content html
     * @param setImageLinkSelector the trigger to show the media frame
     * @param removeImageLinkSelector the trigger to remove the value from the input
     * @param hideTriggers determine whether to hide the triggers corresponding to the image state.
     *                     Image loaded = setImageLink hidden, No image loaded = removeImageLink hidden
     * @param hide will be applied to setImageLink and removeImageLink elements depending on the state
     * @param show will be applied to setImageLink and removeImageLink elements depending on the state
     * @param imgHTML requires url and alt parameters to render the image in the set imageContainer
     * @param noImgHTML determine what to display if no image was selected
     */
    biws.customMediaUploader = options => {
        const defaults = {
            containerSelector: null,
            inputSelector: null,
            imageContainerSelector: null,
            setImageLinkSelector: null,
            removeImageLinkSelector: null,
            hideTriggers: true,
            hide: element => element.classList.add('hidden'),
            show: element => element.classList.remove('hidden'),
            imgHTML: (url, alt) => `<img src="${url}" alt="${alt ? alt : ""}" style="max-width:100%" />`,
            noImgHTML: ''
        };

        const settings = Object.assign({}, defaults, options);
        var frame;

        /**
         * error if selectors are missing
         */
        if (!settings.containerSelector ||
            !settings.inputSelector ||
            !settings.imageContainerSelector ||
            !settings.setImageLinkSelector ||
            !settings.removeImageLinkSelector) {
            console.error('customMediaUploader called with invalid arguments. All selectors must be specified.');
            console.groupCollapsed('Selectors');
            console.log('containerSelector:', settings.containerSelector);
            console.log('inputSelector:', settings.inputSelector);
            console.log('imageContainerSelector:', settings.imageContainerSelector);
            console.log('setImageLinkSelector:', settings.setImageLinkSelector);
            console.log('removeImageLinkSelector:', settings.removeImageLinkSelector);
            console.groupEnd();
            return -1;
        }

        const container = document.querySelector(settings.containerSelector);

        /**
         * error if container selector invalid
         */
        if (!container) {
            console.error('customMediaUploader called for invalid containerSelector:', settings.containerSelector);
            return -2;
        }

        const input = container.querySelectorAll(settings.inputSelector),
            imageContainer = container.querySelectorAll(settings.imageContainerSelector),
            setImageLink = container.querySelectorAll(settings.setImageLinkSelector),
            removeImageLink = container.querySelectorAll(settings.removeImageLinkSelector);

        // hide triggers depending on the selection state of an image
        if (Array.from(input).some(element => element.value)) {
            if (settings.hideTriggers) {
                setImageLink.forEach(element => settings.hide(element));
                removeImageLink.forEach(element => settings.show(element));
            } else {
                setImageLink.forEach(element => settings.show(element));
                removeImageLink.forEach(element => settings.hide(element));
            }
        }

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
                imageContainer.forEach(element => element.innerHTML = settings.imgHTML(attachment.sizes.thumbnail.url));
                if (settings.hideTriggers) {
                    // Hide the set image link trigger
                    setImageLink.forEach(element => settings.hide(element))
                    // Show the remove image link trigger
                    removeImageLink.forEach(element => settings.show(element))
                }
            });

            // Finally, open the modal on click
            frame.open();
        }

        let removeImage = () => {
            // Delete the image id from the input
            input.forEach(element => element.value = '');
            // Clear image preview
            imageContainer.forEach(element => element.innerHTML = settings.noImgHTML);
            if (settings.hideTriggers) {
                // Show the set image link trigger
                setImageLink.forEach(element => settings.show(element))
                // Hide the remove image link trigger
                removeImageLink.forEach(element => settings.hide(element))
            }
        };

        // register listeners
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