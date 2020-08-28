<?php

namespace BIWS\EventManager\cpt;

use BIWS\EventManager\Scripts;

defined('ABSPATH') or die('Nope!');

class CustomPostType
{
    private $slug;

    private $args;

    private $taxonomies;

    private $meta_boxes;

    public function __construct($slug, $args, $taxonomies, $meta_boxes)
    {
        $this->slug = $slug;
        $this->args = $args;
        $this->taxonomies = $taxonomies;
        $this->meta_boxes = $meta_boxes;
    }

    public function init()
    {
        // basic initialisation
        add_action('init', [$this, 'register']);
        foreach ($this->taxonomies as $taxonomy) {
            $taxonomy->init($this->slug);
        }
        foreach ($this->meta_boxes as $meta_box) {
            $meta_box->init($this->slug);
        }

        if ($this->meta_boxes) {
            Scripts::enqueueMetaboxesScript($this->slug, $this->meta_boxes);
        }

        // event duplication
        add_action('admin_action_duplicateAsDraft', array($this, 'duplicateAsDraft'));
        add_filter('page_row_actions', array($this, 'addDuplicateAction'), 10, 2);
    }

    public function register()
    {
        register_post_type($this->slug, $this->args);
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
}
