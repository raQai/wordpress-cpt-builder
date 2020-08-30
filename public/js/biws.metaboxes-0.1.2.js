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
        const fields = [];

        this.addField = field => {
            fields.push(field);
        }

        const fieldComponents = () => {
            return fields.map(field => field.toComponent());
        }

        this.toComponent = () => createElement(PluginDocumentSettingPanel,
            {
                name: name,
                title: title,
                icon: ' '
            },
            ...fieldComponents());
    }

    function Builder(name) {
        const metaboxes = [],
            last = a => {
                if (!a) {
                    throw Error('cannot access empty array');
                }
                return a[a.length - 1];
            },
            pushField = field => {
                last(metaboxes).addField(field);
            }

        this.addMetabox = (name, title) => {
            metaboxes.push(new Metabox(name, title));
            return this;
        }

        this.addNumberField = (name, label) => {
            pushField(new NumberField(name, label));
            return this;
        }

        this.addTextField = (name, label, placeholder) => {
            pushField(new TextField(name, label, placeholder));
            return this;
        }

        this.addEmailField = (name, label, placeholder) => {
            pushField(new EmailField(name, label, placeholder));
            return this;
        }

        this.addCheckBoxField = (name, label) => {
            pushField(new CheckBoxField(name, label));
            return this;
        }

        this.addDateField = (name, label) => {
            pushField(new DateField(name, label));
            return this;
        }

        this.addTimeField = (name, label) => {
            pushField(new TimeField(name, label));
            return this;
        }

        this.build = () => {
            return plugin(name, metaboxes);
        }
    }

    metaboxes.builder = name => new Builder(name);

    function plugin(name, metaboxes) {
        const metaboxComponents = () => {
            return metaboxes.map(metabox => metabox.toComponent());
        }

        return registerPlugin(name, {
            render: () => createElement(Fragment, {}, ...metaboxComponents())
        })
    };
}(window.biws = window.biws || {},
    window.biws.metaboxes = window.biws.metaboxes || {}));