(function ($) {
    $.fn.biws_customMediaUploader = function (options) {
        let settings = $.extend({
            id: null, // should be input field id
            imageContainerId: null, // should be container where to put the image
            setImageLinkId: null, // trigger to toggle media selection
            removeImageLinkId: null // trigger to remove media for selection
        }, options);

        if (!settings.id ||
            !settings.imageContainerId ||
            !settings.setImageLinkId ||
            !settings.removeImageLinkId)
            return this;

        let setImage = (frame, inputField, imageContainer, setImageLink, removeImageLink) => {
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
                multiple: false
            });

            // When an image is selected in the media frame...
            frame.on('select', () => {
                let attachment = frame.state().get('selection').first().toJSON();
                inputField.val(attachment.id);
                imageContainer.html('');
                imageContainer.append('<img src="' + attachment.sizes.thumbnail.url + '" alt="" style="max-width:100%;"/>');
                setImageLink.addClass('hidden');
                removeImageLink.removeClass('hidden');
            });

            // Finally, open the modal on click
            frame.open();
        }

        let removeImage = (inputField, imageContainer, setImageLink, removeImageLink) => {
            inputField.val('');
            imageContainer.html('');
            setImageLink.removeClass('hidden');
            removeImageLink.addClass('hidden');
        };

        return this.each(() => {
            const mediaSelector = $(this),
                inputField = mediaSelector.find(settings.id),
                imageContainer = mediaSelector.find(settings.imageContainerId),
                setImageLink = mediaSelector.find(settings.setImageLinkId),
                removeImageLink = mediaSelector.find(settings.removeImageLinkId);
            let frame;

            setImageLink.on('click', event => {
                event.preventDefault();
                setImage(frame, inputField, imageContainer, setImageLink, removeImageLink);

            });

            imageContainer.on('click', event => {
                event.preventDefault();
                setImage(frame, inputField, imageContainer, setImageLink, removeImageLink);
            });

            removeImageLink.on('click', event => {
                event.preventDefault();
                removeImage(inputField, imageContainer, setImageLink, removeImageLink);
            })
        })
    }
}(jQuery));