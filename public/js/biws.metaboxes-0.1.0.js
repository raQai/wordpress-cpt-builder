; (function (biws, metaboxes) {
    const { createElement, Fragment, useState } = wp.element;
    const { PluginDocumentSettingPanel } = wp.editPost;
    const { compose } = wp.compose;
    const { withSelect, withDispatch } = wp.data;
    const { TextControl, CheckboxControl } = wp.components;

    const BasicField = class {
        control = TextControl;

        constructor(name, label) {
            this.name = name;
            this.label = label;
        }

        elementProps = () => {
            return {
                name: this.name,
                label: this.label,
            }
        }

        controlProps = props => {
            const copy = Object.assign({}, props);

            // clean up object to avoid errors
            delete copy.setMetaFieldValue;
            delete copy.metaFieldValue;

            copy['value'] = props.metaFieldValue;
            copy['onChange'] = content => props.setMetaFieldValue(content);

            return copy;
        }

        dispatchFunction = (dispatch, props) => {
            return {
                setMetaFieldValue: value => dispatch('core/editor')
                    .editPost({ meta: { [props.name]: value } }),
            }
        }

        selectFunction = (select, props) => {
            return {
                metaFieldValue: select('core/editor')
                    .getEditedPostAttribute('meta')[props.name],
            }
        }

        composeElement = () => compose(
            withDispatch(this.dispatchFunction),
            withSelect(this.selectFunction)
        )(props => {
            return createElement(this.control, this.controlProps(props));
        });

        toComponent = () => {
            return createElement(this.composeElement(), this.elementProps());
        }
    }

    const NumberField = class extends BasicField {
        elementProps = () => {
            return {
                name: this.name,
                label: this.label,
                type: 'number',
            }
        }
    };

    const TextField = class extends BasicField {
        constructor(name, label, placeholder) {
            super(name, label);
            this.placeholder = placeholder;
        }

        elementProps = () => {
            return {
                name: this.name,
                label: this.label,
                placeholder: this.placeholder,
            }
        }
    };

    const CheckBoxField = class extends BasicField {
        constructor(name, label) {
            super(name, label);
            this.control = CheckboxControl;
        }

        elementProps = () => {
            return {
                name: this.name,
                label: this.label,
                checked: this.checked,
            }
        }

        controlProps = props => {
            const copy = Object.assign({}, props);

            // clean up object to avoid errors
            delete copy.setMetaFieldValue;
            delete copy.isChecked;

            copy.checked = props.isChecked;
            copy['onChange'] = content => props.setMetaFieldValue(content);

            return copy;
        }

        selectFunction = (select, props) => {
            return {
                isChecked: select('core/editor')
                    .getEditedPostAttribute('meta')[props.name] ? true : false,
            }
        }

    };

    const DateField = class extends BasicField {
        elementProps = () => {
            return {
                name: this.name,
                label: this.label,
                type: 'date',
                placeholder: 'YYYY-mm-dd',
                pattern: '\d{4}-\d{2}-\d{2}',

            }
        }
    };

    const TimeField = class extends BasicField {
        elementProps = () => {
            return {
                name: this.name,
                label: this.label,
                type: 'time',
                placeholder: 'hh:mm',
                pattern: '\d{2}:\d{2}',
            }
        }
    };

    const Metabox = class {
        constructor(name, title) {
            this.name = name;
            this.title = title;
            this.fields = [];
        }

        fieldComponents = () => {
            return this.fields.map(field => field.toComponent());
        }

        toComponent = () => createElement(PluginDocumentSettingPanel,
            {
                name: this.name,
                title: this.title,
            },
            ...this.fieldComponents());
    }

    const Builder = class {
        metaboxes = [];

        constructor(name) {
            this.name = name;
        }

        pushField = field => {
            if (!this.metaboxes) {
                throw Error('cannot add field if no matabox was specified before');
            }
            this.metaboxes[this.metaboxes.length - 1].fields.push(field);
        }

        addMetabox = (name, title) => {
            this.metaboxes.push(new Metabox(name, title));
            return this;
        }

        addNumberField = (name, label) => {
            this.pushField(new NumberField(name, label));
            return this;
        }

        addTextField = (name, label, placeholder) => {
            this.pushField(new TextField(name, label, placeholder));
            return this;
        }

        addCheckBoxField = (name, label, placeholder) => {
            this.pushField(new CheckBoxField(name, label, placeholder));
            return this;
        }

        addDateField = (name, label) => {
            this.pushField(new DateField(name, label));
            return this;
        }

        addTimeField = (name, label) => {
            this.pushField(new TimeField(name, label));
            return this;
        }

        build = () => {
            return plugin(this);
        }
    }

    metaboxes.builder = name => new Builder(name);

    const plugin = builder => {
        const { registerPlugin } = wp.plugins;

        console.log(builder);

        metaboxComponents = () => {
            return builder.metaboxes.map(metabox => metabox.toComponent());
        }

        return registerPlugin(builder.name, {
            render: () => createElement(Fragment, {}, ...metaboxComponents())
        })
    };
}(window.biws = window.biws || {},
    window.biws.metaboxes = window.biws.metaboxes || {}));