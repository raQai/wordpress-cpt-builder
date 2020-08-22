(function ($) {
    $.fn.biws = function () {
        const jq = this;
        return {
            customMediaUploader: function (options) {
                /**
                 * @param inputSelector the input element to put the value in
                 * @param imageContainerSelector the container where to put the image. Will overwrite all content html
                 * @param setImageLinkSelector the trigger to show the media frame
                 * @param removeImageLinkSelector the trigger to remove the value from the input
                 */
                let settings = $.extend({
                    inputSelector: null,
                    imageContainerSelector: null,
                    setImageLinkSelector: null,
                    removeImageLinkSelector: null
                }, options);

                /**
                 * no-op if any necessary selectors are missing
                 */
                if (!settings.inputSelector ||
                    !settings.imageContainerSelector ||
                    !settings.setImageLinkSelector ||
                    !settings.removeImageLinkSelector)
                    return;

                /**
                 * Open media workflow and append image to specified settings.imageContainerSelector
                 * @param {wp.media.view.MediaFrame} frame WordPress media workflow frame
                 */
                let setImage = (frame) => {
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
                        settings.inputSelector.val(attachment.id);
                        // Clear image container html to allow using this on already set values
                        settings.imageContainerSelector.html('');
                        // Append the attachment thmbnail preview to the image container
                        settings.imageContainerSelector.append('<img src="' + attachment.sizes.thumbnail.url + '" alt="" style="max-width:100%;"/>');
                        // Hide the set image link trigger
                        settings.setImageLinkSelector.addClass('hidden');
                        // Show the remove image link trigger
                        settings.removeImageLinkSelector.removeClass('hidden');
                    });

                    // Finally, open the modal on click
                    frame.open();
                }

                let removeImage = () => {
                    // Delete the image id from the input
                    settings.inputSelector.val('');
                    // Clear image preview
                    settings.imageContainerSelector.html('');
                    // Show the set image link trigger
                    settings.setImageLinkSelector.removeClass('hidden');
                    // Hide the remove image link trigger
                    settings.removeImageLinkSelector.addClass('hidden');
                };

                return jq.each(() => {
                    let frame;

                    // Add action to set image to the set image trigger
                    settings.setImageLinkSelector.on('click', event => {
                        event.preventDefault();
                        setImage(frame);
                    });

                    // Add action to set image to the image container allowing to re-set the value when clicking on the image
                    settings.imageContainerSelector.on('click', event => {
                        event.preventDefault();
                        setImage(frame);
                    });

                    // Add action to remove image to the remove image trigger
                    settings.removeImageLinkSelector.on('click', event => {
                        event.preventDefault();
                        removeImage();
                    })
                })
            }
        }
    }
}(jQuery));