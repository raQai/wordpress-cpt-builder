<?php

namespace BIWS\EventManager\cpt;

use BIWS\EventManager\Scripts;
use WP_Error;
use WP_Query;
use WP_REST_Response;

defined('ABSPATH') or die('Nope!');

class CustomPostType
{
    private $slug;

    private $args;

    private $taxonomies;

    private $meta_boxes;

    private $child_cpts;

    private $unset_columns;

    private $rest_props;

    public function __construct(
        $slug,
        $args,
        $taxonomies,
        $meta_boxes,
        $child_cpts,
        $unset_columns,
        $rest_props
    ) {
        $this->slug = $slug;
        $this->args = $args;
        $this->taxonomies = $taxonomies;
        $this->meta_boxes = $meta_boxes;
        $this->child_cpts = $child_cpts;
        $this->unset_columns = $unset_columns;
        $this->rest_props = $rest_props;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function init()
    {
        // basic initialisation
        add_action('init', function () {
            register_post_type($this->slug, $this->args);
        });
        foreach ($this->taxonomies as $taxonomy) {
            $taxonomy->init($this->slug);
        }
        foreach ($this->meta_boxes as $meta_box) {
            $meta_box->init($this->slug);
        }

        if ($this->meta_boxes) {
            Scripts::enqueueMetaboxesScript($this->slug, $this->meta_boxes);
        }

        foreach ($this->child_cpts as $child_cpt) {
            foreach ($child_cpt->taxonomies as $taxonomy) {
                // add child taxonomies to parent navigation
                add_action('admin_menu', function () use ($child_cpt, $taxonomy) {
                    add_submenu_page(
                        "edit.php?post_type={$this->getSlug()}",
                        $taxonomy->getName(),
                        "- {$taxonomy->getName()}",
                        'manage_options',
                        "edit-tags.php?taxonomy={$taxonomy->getSlug()}&post_type={$child_cpt->getSlug()}"
                    );
                });
                // fix parent file of taxonomies to allow proper navigation handling
                add_filter('parent_file', function ($parent_file) use ($child_cpt, $taxonomy) {
                    global $submenu_file, $current_screen, $pagenow;
                    if (
                        $current_screen->post_type == $child_cpt->getSlug() &&
                        $pagenow == 'edit-tags.php' &&
                        stripos($submenu_file, $taxonomy->getSlug()) !== false
                    ) {
                        $submenu_file = "edit-tags.php?taxonomy={$taxonomy->getSlug()}&post_type={$current_screen->post_type}";
                        $parent_file = "edit.php?post_type={$this->getSlug()}";
                    }
                    return $parent_file;
                });
            }
        }

        if ($this->unset_columns) {
            add_filter("manage_{$this->slug}_posts_columns", function ($columns) {
                foreach ($this->unset_columns as $column) {
                    unset($columns[$column]);
                    return $columns;
                }
            });
        }

        // event duplication
        add_action('admin_action_duplicateAsDraft', array($this, 'duplicateAsDraft'));
        add_filter('post_row_actions', array($this, 'addDuplicateAction'), 10, 2);

        // register rest route
        if ($this->rest_props) {
            add_action('rest_api_init', function () {
                $route = $this->rest_props['route'] ? $this->rest_props['route'] : $this->slug;
                register_rest_route($this->rest_props['namespace'], $route, array(
                    'methods' => 'GET',
                    'callback' => array($this, 'getRestCallback'),
                    'permission_callback' => $this->rest_props['permission_callback'],
                ));
            });
        }
    }

    /**
     * Function for post duplication. Dups appear as drafts. User is redirected to the edit screen
     * credits: https://www.hostinger.com/tutorials/how-to-duplicate-wordpress-page-or-post
     */
    function duplicateAsDraft()
    {
        global $wpdb;
        if (!(isset($_GET['post']) || isset($_POST['post']) || (isset($_REQUEST['action']) && 'duplicateAsDraft' == $_REQUEST['action']))) {
            wp_die('No post to duplicate has been supplied!');
        }

        /**
         * Nonce verification
         */
        if (!isset($_GET['duplicate_nonce']) || !wp_verify_nonce($_GET['duplicate_nonce'], basename(__FILE__)))
            return;

        /**
         * get the original post id
         */
        $post_id = (isset($_GET['post']) ? absint($_GET['post']) : absint($_POST['post']));
        /**
         * and all the original post data then
         */
        $post = get_post($post_id);

        /**
         * if you don't want current user to be the new post author,
         * then change next couple of lines to this: $new_post_author = $post->post_author;
         */
        $current_user = wp_get_current_user();
        $new_post_author = $current_user->ID;

        /**
         * if post data exists, create the post duplicate
         */
        if (isset($post) && $post != null) {

            /**
             * new post data array
             */
            $args = array(
                'comment_status' => $post->comment_status,
                'ping_status'    => $post->ping_status,
                'post_author'    => $new_post_author,
                'post_content'   => $post->post_content,
                'post_excerpt'   => $post->post_excerpt,
                'post_name'      => $post->post_name,
                'post_parent'    => $post->post_parent,
                'post_password'  => $post->post_password,
                'post_status'    => 'draft',
                'post_title'     => $post->post_title,
                'post_type'      => $post->post_type,
                'to_ping'        => $post->to_ping,
                'menu_order'     => $post->menu_order
            );

            /**
             * insert the post by wp_insert_post() function
             */
            $new_post_id = wp_insert_post($args);

            /**
             * get all current post terms ad set them to the new post draft
             * returns array of taxonomy names for post type, ex array("category", "post_tag");
             */
            $taxonomies = get_object_taxonomies($post->post_type);
            foreach ($taxonomies as $taxonomy) {
                $post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
                wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
            }

            /**
             * duplicate all post meta just in two SQL queries
             */
            $post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
            if (count($post_meta_infos) != 0) {
                $sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
                foreach ($post_meta_infos as $meta_info) {
                    $meta_key = $meta_info->meta_key;
                    if ($meta_key == '_wp_old_slug') continue;
                    $meta_value = addslashes($meta_info->meta_value);
                    $sql_query_sel[] = "SELECT $new_post_id, '$meta_key', '$meta_value'";
                }
                $sql_query .= implode(" UNION ALL ", $sql_query_sel);
                $wpdb->query($sql_query);
            }


            /**
             * finally, redirect to the edit post screen for the new draft
             */
            wp_redirect(admin_url('post.php?action=edit&post=' . $new_post_id));
            exit;
        } else {
            // FIXME i18n
            wp_die('Post creation failed, could not find original post: ' . $post_id);
        }
    }

    /**
     * Add the duplicate link to action list for post_row_actions
     */
    function addDuplicateAction($actions, $post)
    {
        if (current_user_can('edit_posts') && $post->post_type === $this->slug) {
            $actionURL = 'admin.php?action=duplicateAsDraft&post=' . $post->ID;
            $nonce = wp_nonce_url($actionURL, basename(__FILE__), 'duplicate_nonce');
            $format = '<a href="%s" title="%s" rel="permalink">%s</a>';
            // FIXME i18n
            $action = sprintf(
                $format,
                $nonce,
                'Duplicate this item',
                'Duplicate'
            );
            $actions['duplicate'] = $action;
        }
        return $actions;
    }

    function prepareQueryParams($requestParams) {
        $params = $this->rest_props['params'];
        $params['post_type'] = $this->slug;
        return array_merge($params, $requestParams);
    }

    function prepareRestQuery($params) {
        $query = new WP_Query($params);
        return $query;
    }

    function collectRestResponseData($posts) {
        $data = [];
        foreach ($posts as $event) {
            $event_data = array(
                'title' => $event->post_title,
                'link' => get_permalink($event),
                'content' => apply_filters('the_content', $event->post_content),
                'slug' => $event->post_name,
                'status' => get_post_status($event),
            );
            foreach ($this->taxonomies as $taxonomy) {
                $term_data = $taxonomy->collectRestResponseData($event->ID);
                if (!$term_data) {
                    continue;
                }
                $event_data[$taxonomy->getSlug()] = $term_data;
            }
            foreach ($this->meta_boxes as $meta_box) {
                $meta_box_data = $meta_box->collectRestResponseData($event->ID);
                if (!$meta_box_data) {
                    continue;
                }
                $event_data[$meta_box->getId()] = $meta_box_data;
            }

            $data[] = $event_data;
        }
        return $data;
    }

    function getRestResponse($query)
    {
        $posts = $query->posts;

        if (empty($posts)) {
            return new WP_Error(
                'biws__no_posts',
                __('No posts found'),
                array('status' => 404)
            );
        }

        $data = $this->collectRestResponseData($posts);
        $max_pages = $query->max_num_pages;
        $total = $query->found_posts;

        $response = new WP_REST_Response($data, 200);

        $response->header('X-WP-Total', $total);
        $response->header('X-WP-TotalPAges', $max_pages);

        return $response;
    }

    function getRestCallback($request) {
        $params = $this->prepareQueryParams($request->get_params());
        $query = $this->prepareRestQuery($params);

        return $this->getRestResponse($query);
    }
}
