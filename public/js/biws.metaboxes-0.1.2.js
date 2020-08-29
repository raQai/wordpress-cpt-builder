; (function (biws, metaboxes) {
    const { registerPlugin } = wp.plugins;
    const { createElement, Fragment } = wp.element;
    const { PluginDocumentSettingPanel } = wp.editPost;
    const { compose } = wp.compose;
    const { withSelect, withDispatch } = wp.data;
    const { TextControl, CheckboxControl } = wp.components;

    function BasicField(elementProps, control = TextControl) {
        this.controlProps = props => {
            const copy = Object.assign({}, props);

            // clean up object to avoid errors
            delete copy.setMetaFieldValue;
            delete copy.metaFieldValue;

            copy['value'] = props.metaFieldValue;
            copy['onChange'] = content => props.setMetaFieldValue(content);

            return copy;
        }

        this.dispatchFunction = (dispatch, props) => {
            return {
                setMetaFieldValue: value => dispatch('core/editor')
                    .editPost({ meta: { [props.name]: value } }),
            }
        }

        this.selectFunction = (select, props) => {
            return {
                metaFieldValue: select('core/editor')
                    .getEditedPostAttribute('meta')[props.name],
            }
        }

        let composeElement = compose(
            withDispatch(this.dispatchFunction),
            withSelect(this.selectFunction)
        )(props => {
            return createElement(control, this.controlProps(props));
        });

        this.toComponent = () => {
            return createElement(composeElement, elementProps);
        }
    }

    function NumberField(name, label) {
        BasicField.call(this, {
            name: name,
            label: label,
            type: 'number',
        });
    }

    function TextField(name, label, placeholder) {
        BasicField.call(this, {
            name: name,
            label: label,
            placeholder: placeholder,
        });
    };

    function EmailField(name, label, placeholder) {
        BasicField.call(this, {
            name: name,
            label: label,
            placeholder: placeholder,
            type: 'email',
        });
    };

    function CheckBoxField(name, label, checked = false) {
        BasicField.call(this, {
            name: name,
            label: label,
            checked: checked,
        }, CheckboxControl);

        this.controlProps = props => {
            const copy = Object.assign({}, props);

            // clean up object to avoid errors
            delete copy.setMetaFieldValue;
            delete copy.metaFieldValue;

            copy.checked = props.metaFieldValue;
            copy.onChange = content => props.setMetaFieldValue(content);

            return copy;
        }
    };

    function DateField(name, label) {
        BasicField.call(this, {
            name: name,
            label: label,
            type: 'date',
            placeholder: 'YYYY-mm-dd',
            pattern: '\d{4}-\d{2}-\d{2}',
        });
    };

    function TimeField(name, label) {
        BasicField.call(this, {
            name: name,
            label: label,
            type: 'time',
            placeholder: 'hh:mm',
            pattern: '\d{2}:\d{2}',
        });
    };

    function Metabox(name, title) {
        this.fields = [];

        this.fieldComponents = () => {
            return this.fields.map(field => field.toComponent());
        }

        this.toComponent = () => createElement(PluginDocumentSettingPanel,
            {
                name: name,
                title: title,
                icon: ' '
            },
            ...this.fieldComponents());
    }

    function Builder(name) {
        this.metaboxes = [];
        this.name = name;

        this.pushField = field => {
            if (!this.metaboxes) {
                throw Error('cannot add field if no matabox was specified before');
            }
            this.metaboxes[this.metaboxes.length - 1].fields.push(field);
        }

        this.addMetabox = (name, title) => {
            this.metaboxes.push(new Metabox(name, title));
            return this;
        }

        this.addNumberField = (name, label) => {
            this.pushField(new NumberField(name, label));
            return this;
        }

        this.addTextField = (name, label, placeholder) => {
            this.pushField(new TextField(name, label, placeholder));
            return this;
        }

        this.addEmailField = (name, label, placeholder) => {
            this.pushField(new EmailField(name, label, placeholder));
            return this;
        }

        this.addCheckBoxField = (name, label) => {
            this.pushField(new CheckBoxField(name, label));
            return this;
        }

        this.addDateField = (name, label) => {
            this.pushField(new DateField(name, label));
            return this;
        }

        this.addTimeField = (name, label) => {
            this.pushField(new TimeField(name, label));
            return this;
        }

        this.build = () => {
            return plugin(this);
        }
    }

    metaboxes.builder = name => new Builder(name);

    function plugin(builder) {
        metaboxComponents = () => {
            return builder.metaboxes.map(metabox => metabox.toComponent());
        }

        return registerPlugin(builder.name, {
            render: () => createElement(Fragment, {}, ...metaboxComponents())
        })
    };
}(window.biws = window.biws || {},
    window.biws.metaboxes = window.biws.metaboxes || {}));