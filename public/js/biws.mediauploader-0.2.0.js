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
     * @param noImgHTMLCallback determine what to display if no image was selected
     * @param imgHTMLCallback requires url and alt parameters to render the image in the set imageContainer
     */
    biws.customMediaUploader = ({
        containerSelector, // required
        inputSelector, // requred
        imageContainerSelector, // required
        setImageLinkSelector, // required
        removeImageLinkSelector, // required
        hideTriggers = true,
        hide = element => element.classList.add('hidden'),
        show = element => element.classList.remove('hidden'),
        noImgHTMLCallback = () => undefined,
        imgHTMLCallback = ({ url, alt } = {}) => undefined,
    } = {}) => {
        let wpMediaFrame;
        const
            getNoImgHTML = noImgHTMLCallback instanceof Function && noImgHTMLCallback() != null
                ? noImgHTMLCallback
                : () => '',
            getImgHTML = imgHTMLCallback instanceof Function && imgHTMLCallback() != null
                ? imgHTMLCallback
                : ({ url = '', alt = '' } = {}) => {
                    if (!url) return getNoImgHTML();
                    return `<img src="${url}" ${alt ? `alt="${alt}"` : ''} style="max-width:100%" />`;
                };

        /**
         * error if selectors are missing
         */
        if (!containerSelector ||
            !inputSelector ||
            !imageContainerSelector ||
            !setImageLinkSelector ||
            !removeImageLinkSelector) {
            console.error('customMediaUploader called with invalid arguments. All selectors must be specified.');
            console.groupCollapsed('Selectors');
            console.log('containerSelector:', containerSelector);
            console.log('inputSelector:', inputSelector);
            console.log('imageContainerSelector:', imageContainerSelector);
            console.log('setImageLinkSelector:', setImageLinkSelector);
            console.log('removeImageLinkSelector:', removeImageLinkSelector);
            console.groupEnd();
            return;
        }

        const container = document.querySelector(containerSelector);

        /**
         * error if container selector invalid
         */
        if (!container) {
            console.error('customMediaUploader called for invalid containerSelector:', containerSelector);
            return;
        }

        const input = container.querySelectorAll(inputSelector),
            imageContainer = container.querySelectorAll(imageContainerSelector),
            setImageLink = container.querySelectorAll(setImageLinkSelector),
            removeImageLink = container.querySelectorAll(removeImageLinkSelector),

            getVisibilityFunction = (setVisible = false) => {
                return setVisible
                    ? show instanceof Function ? show : _ => _
                    : hide instanceof Function ? hide : _ => _;
            },

            setTriggerVisibility = (isMediaSet = false) => {
                // show the set image link trigger if isMediaSet, hide it otherwise
                setImageLink.forEach(getVisibilityFunction(!isMediaSet))
                // show/hide the remove image link trigger accordingly
                removeImageLink.forEach(getVisibilityFunction(isMediaSet))
            },

            setAttachment = ({ value = '', imgURL = '' } = {}) => {
                const elementValue = value || '',
                    imgHTML = getImgHTML({ url: imgURL });

                // set image id if present
                input.forEach(element => element.value = elementValue);
                // set image preview if present
                imageContainer.forEach(element => element.innerHTML = imgHTML);

                if (!hideTriggers) {
                    return value;
                }

                setTriggerVisibility(value);

                return value;
            },

            setImage = frame => {
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
                    if (attachment == null) {
                        return setAttachment();
                    }
                    return setAttachment({ value: attachment.id, imgURL: attachment.sizes.thumbnail.url });
                });

                // Finally, open the modal on click
                frame.open();
            },

            removeImage = () => setAttachment();

        // hide triggers depending on the initial selection state of an image
        // FIXME handle img url
        // currently the image html is dereived within php, this should actually move here
        if (Array.from(input).some(element => element.value)) {
            // setAttachment({ value: input[0].value, imgURL: '' })
            setTriggerVisibility(true)
        }

        // register listeners
        setImageLink.forEach(trigger => {
            trigger.addEventListener('click', event => {
                if (!event.target.matches(setImageLinkSelector)) {
                    return;
                }
                event.preventDefault();
                setImage(wpMediaFrame);
            })
        });

        imageContainer.forEach(trigger => {
            trigger.addEventListener('click', event => {
                if (!event.target.matches(`${imageContainerSelector} > *`)) {
                    return;
                }
                event.preventDefault();
                setImage(wpMediaFrame);
            })
        });

        removeImageLink.forEach(trigger => {
            trigger.addEventListener('click', event => {
                if (!event.target.matches(removeImageLinkSelector)) {
                    return;
                }
                event.preventDefault();
                removeImage();
            })
        });
    }

}(window.biws = window.biws || {}))