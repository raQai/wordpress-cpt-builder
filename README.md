# BIWS CPT Builder

## Table of Contents
1. [Introduction](#intro)
    - [Who this is not for](#who_not)
1. [Creating and Registering Custom Post Types](#cpts)
1. [Custom Fields](#fields)
1. [Taxonomies](#taxonomies)
1. [Meta Boxes and Post Meta](#meta_boxes)
1. [Creating and Overwriting Views](#views)
    - [Default View Templates](#templates)
1. [Full Example](#example)


<a name="intro"></a>
## Introduction

**BIWS CPT Builder** is a [WordPress](https://www.wordpress.org) plugin used to
simplify the generation of
[custom post types](https://wordpress.org/support/article/post-types/#custom-post-types)
in Wordpress.

It allows creating customized views by implementing the corresponding interface
[IView](./views/IView.class.php) and registering it using the
[RenderService](./views/RenderService.class.php).

A full example can be found at the bottom of this readme.

Alternatively you can check out the
[TaKi Event Manager](https://github.com/raQai/wordpress-taki-event-manager)
plugin which is using this base plugin. It contains additional views e.g. custom
rest integration to create a front end list view for events implemented with
[Svelte](https://svelte.dev/).

This plugin is by far not complete and may be extended from time to time.


<a name="intro"></a>
### Who this is not for

If you are not a developer, this plugin is most likely not for you.

This plugin is meant to be a core plugin to help extending WordPress with
customized and self created plugins. It will not install any post types or
views to build new post types with an interface. It is meant to be extended
by third party plugins allowing developers to build a simple code base for
better productivity.


<a name="cpts"></a>
## Creating and Registering Custom Post Types
Creating custom post types is fairly simple. All you have to do is calling the
corresponding constructor and creating a new object. 

```php
$cpt = new CustomPostType(
        'biws__cpt_sample',
        array(
            'label' => 'Sample CPT',
            // supports ['editor'] required for gutenberg.
            // supports ['custom-fields'] required if metaboxes are added
            'supports' => array('title', 'editor', 'custom-fields'),
            'has_archive' => true,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            // show_in_rest required for gutenberg
            'show_in_rest' => true,
            'menu_position' => 5,
        ),
        array(
            // Taxonomies if any
        ),
        array(
            // Meta boxes if any
        )
    );
```

You can register the created custom post type using the
[CPTService](./services/CPTService.class.php).
```php
$service = CPTService::getInstance();
$service->registerAndInit($cpt);
```

You can allow duplication of your registered custom post type with the
[PostDuplicatorService](./services/PostDuplicatorService.class.php). This will
add a quick action to your posts in the custom post type view.

```php
$post_duplicator_service = PostDuplicatorService::getInstance();
$post_duplicator_service->register($cpt);
```

<a name="fields"></a>
## Custom Fields
Custom fields are defined by the [IField](./models/fields/IField.class.php)
interface and used to add additional metadata to [taxonomies](#taxonomies) and
[meta boxes](#meta_boxes).

Basic implamentations within this plugin are
[SimpleField](./models/fields/SimpleField.class.php) and
[PlaceholderField](./models/fields/PlaceholderField.class.php).

You can extend your [taxonomies](#taxonomies) and [meta boxes](#meta_boxes)
with all fields defined in [FieldType](./models/fields/FieldType.class.php).
The image fields automatically enqueue the necessary javascript file to allow
using the wordpress media library.


<a name="taxonomies"></a>
## Taxonomies
You can create custom taxonomies with the above mentioned [fields](#fields).

[Taxonomies](./models/Taxonomy.class.php) can be added to custom post types,
by adding them to the corresponding custom post type parameter.

```php
$taxonomy = new Taxonomy(
        'biws__tax_sample', // taxonomy id/slug
        array( // args
            'hierarchical' => true,
            'label' => 'Taxonomy',
            'labels' => array( // 'labels' > 'name' required
                'name' => 'Taxonomy',
            ),
            'show_ui' => true,
            'show_admin_column' => true,
            'show_tag_cloud' => false,
            'show_in_rest' => true,
            'query_var' => true,
        ),
        array( // fields
            new SimpleField(
                FieldType::IMAGE,
                'tax_image',
                'Image'
            ),
            new PlaceholderField(
                FieldType::TEXT,
                'tax_text',
                'Text',
                'text placeholder'
            ),
        )
    );
```


<a name="meta_boxes"></a>
## Meta Boxes and Post Meta
Like taxonomies, meta boxes can simply be added using
[MetaBox](./models/MetaBox.class.php). Although it is to be noted that image
and color fields are currently not supported.

```php
$meta_box = new MetaBox(
        'biws__meta_sample', // meta box id
        'MetaBox Sample', // Title to display in gutenberg
        array( // fields
            new SimpleField(
                FieldType::CHECKBOX,
                'meta_cb',
                'CheckBox'
            ),
            new PlaceholderField(
                FieldType::DATE,
                'meta_date',
                'Date',
                'yyyy-mm-dd'
            ),
            new PlaceholderField(
                FieldType::TIME,
                'meta_time',
                'Time',
                'HH:MM'
            ),
            /* currently unsupported
            new SimpleField(
                FieldType::IMAGE,
                'tax_image',
                'Image'
            ),
            new SimpleField(
                FieldType::COLOR,
                'tax_color',
                'Color'
            ),
            */
        )
    );
```


<a name="views"></a>
## Creating and Overwriting Views

By default, no views will be registered.

This means, even though you can create taxonomies, meta boxes and custom post
types, the form fields will not be shown.

You can register the default views which allow basic editing of the fields by
calling the [RenderService](./views/RenderService.class.php).

```php
$render_service = RenderService::getInstance();
$render_service->registerDefaults($cpt);
```

This will register the following views:
* Column view for custom post types including all meta box fields
* Meta box view allowing to edit meta box fields (currently only gutenberg)
* Column view for Taoxnomies showing all specified fields
* Taxonomy views to create and edit taxonomies with all specified fields

You can also overwrite and register new custom views for your own and already
defined posts and taxonomies using the post slugs and taxonomy name/slug/id,
however you want to call it (WordPress documentation seems to not really agree
on a definition, here obtained by
[Taxonomy::getId()](./models/Taxonomy.class.php)).

*Note that this has to be the unique string representation of the post and
taxonomy name. In most cases referenced as slug.*

Registering a new view will only apply the changes to the provided elements.
Global changes are not supported (yet) so you would have to register everything
manually if you do not like the default views.

```php
// remove date column in cpt
$render_service->registerPost(
    $cpt->getSlug(),
    RenderType::CPT_COLUMN,
    new CPTColumnView((new CPTColumnViewController($cpt))
        ->removeColumn("date"))
);

// remove description and slug columns in taxonomy
$render_service->registerTaxonomy(
    $cpt->getSlug(),
    $taxonomy->getId(),
    RenderType::TAXONOMY_COLUMN,
    new TaxonomyColumnView((new TaxonomyColumnViewController($taxonomy))
        ->removeColumn("description")
        ->removeColumn("slug"))
);
```

<a name="templates"></a>
### Default View Templates

The predefined default views use templates provided by the
[TemplateService](./services/TemplateService.class.php). You can extend the
templates instead of overwriting views by simply adding them to the service. 

Be aware though, that you cannot overwrite default templates globally.
You will have to add the post/taxonomy slug as `$key` and only those will be
overwritten.

```php
$template_service = TemplateService::getInstance();
$tempalte_service->register(
    "custom/input/template",
    TemplateType::TAXONOMY,
    "form_field_input",
    $taxonomy->getId()
);
```

This will overwrite the default `"form_field_input"` template for the previously
created `$taxonomy`.


<a name="example"></a>
## Full Example

```php
/**
 * Sample CPT creation with taxonomy and meta box
 */
$cpt = new CustomPostType(
    'biws__cpt_sample',
    array(
        'label' => 'Sample CPT',
        // supports ['editor'] required for gutenberg.
        // supports ['custom-fields'] required if metaboxes are added
        'supports' => array('title', 'editor', 'custom-fields'),
        'has_archive' => true,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        // show_in_rest required for gutenberg
        'show_in_rest' => true,
        'menu_position' => 5,
    ),
    array(
        new Taxonomy(
            'biws__tax_sample', // taxonomy id/slug
            array( // args
                'hierarchical' => true,
                'label' => 'Taxonomy',
                'labels' => array( // 'labels' > 'name' required
                    'name' => 'Taxonomy',
                ),
                'show_ui' => true,
                'show_admin_column' => true,
                'show_tag_cloud' => false,
                'show_in_rest' => true,
                'query_var' => true,
            ),
            array( // fields
                new SimpleField(
                    FieldType::IMAGE,
                    'tax_image',
                    'Image'
                ),
                new SimpleField(
                    FieldType::COLOR,
                    'tax_color',
                    'Color'
                ),
                new SimpleField(
                    FieldType::CHECKBOX,
                    'tax_cb',
                    'CheckBox'
                ),
                new PlaceholderField(
                    FieldType::NUMBER,
                    'tax_number',
                    'Number',
                    '0123'
                ),
                new PlaceholderField(
                    FieldType::TEXT,
                    'tax_text',
                    'Text',
                    'text placeholder'
                ),
                new PlaceholderField(
                    FieldType::EMAIL,
                    'tax_email',
                    'E-Mail',
                    'text placeholder'
                ),
                new PlaceholderField(
                    FieldType::DATE,
                    'tax_date',
                    'Date',
                    'yyyy-mm-dd'
                ),
                new PlaceholderField(
                    FieldType::TIME,
                    'tax_time',
                    'Time',
                    'HH:MM'
                ),
            )
        ),
    ),
    array(
        new MetaBox(
            'biws__meta_sample', // meta box id
            'MetaBox Sample', // Title to display in gutenberg
            array( // fields
                new SimpleField(
                    FieldType::CHECKBOX,
                    'meta_cb',
                    'CheckBox'
                ),
                new PlaceholderField(
                    FieldType::NUMBER,
                    'meta_number',
                    'Number',
                    '0123'
                ),
                new PlaceholderField(
                    FieldType::TEXT,
                    'meta_text',
                    'Text',
                    'text placeholder'
                ),
                new PlaceholderField(
                    FieldType::EMAIL,
                    'meta_email',
                    'E-Mail',
                    'text placeholder'
                ),
                new PlaceholderField(
                    FieldType::DATE,
                    'meta_date',
                    'Date',
                    'yyyy-mm-dd'
                ),
                new PlaceholderField(
                    FieldType::TIME,
                    'meta_time',
                    'Time',
                    'HH:MM'
                ),
                /* currently unsupported
                new SimpleField(
                    FieldType::IMAGE,
                    'tax_image',
                    'Image'
                ),
                new SimpleField(
                    FieldType::COLOR,
                    'tax_color',
                    'Color'
                ),
                */
            )
        )
    )
);

/**
 * Sample render overwrite
 */
$render_service = RenderService::getInstance();
$render_service->registerDefaults($cpt);

if ($render_service instanceof RenderService) {
    // remove date column in post type
    $render_service->registerPost(
        $cpt->getSlug(),
        RenderType::CPT_COLUMN,
        new CPTColumnView((new CPTColumnViewController($cpt))
            ->removeColumn("date"))
    );

    foreach ($cpt->getTaxonomies() as $taxonomy) {
        // remove description and slug columns from custom taxonomies
        $render_service->registerTaxonomy(
            $cpt->getSlug(),
            $taxonomy->getId(),
            RenderType::TAXONOMY_COLUMN,
            new TaxonomyColumnView((new TaxonomyColumnViewController($taxonomy))
                ->removeColumn("description")
                ->removeColumn("slug"))
        );
    }
}

/**
 * register the created $cpt
 */
$service = CPTService::getInstance();
$service->registerAndInit($cpt);

/**
 * register the created $cpt for post duplications
 */
$post_duplicator_service = PostDuplicatorService::getInstance();
if ($post_duplicator_service instanceof PostDuplicatorService) {
    $post_duplicator_service->register($cpt);
}
```
