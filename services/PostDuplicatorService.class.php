<?php

/**
 * Copyright Patrick Bogdan. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author     Patrick Bogdan
 * @copyright  2020 Patrick Bogdan
 * @license    https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3 or later
 *
 * @link credits: https://www.hostinger.com/tutorials/how-to-duplicate-wordpress-page-or-post
 */

namespace BIWS\CPTBuilder\services;

use BIWS\CPTBuilder\models\CustomPostType;
use WP_Post;

/**
 * Post duplicator service implementation
 *
 * Allows registering created custom post types for post duplication. This will
 * add a duplication link to the quick actions in the admin list views.
 *
 * @since 1.0.0
 *
 * @see Service
 *
 * @package    BIWS\CPTBuilder
 * @subpackage services
 */
class PostDuplicatorService extends Service
{
    /**
     * Posts registered for the duplication action.
     *
     * @since 1.0.0
     * @access private
     *
     * @var string[] $registered_cpt_slugs Array of registered posts' slugs.
     */
    private array $registered_cpt_slugs = array();

    /**
     * Adds the necessary actions and filters for the page/post row actions
     * on construction but only enables the action for registered posts.
     *
     * @since 1.0.0
     *
     * @see PostDuplicatorService::addDuplicateAction()
     *
     * @link https://developer.wordpress.org/reference/hooks/page_row_actions/
     * @link https://developer.wordpress.org/reference/hooks/post_row_actions/
     */
    protected function __construct()
    {
        add_action(
            'admin_action_duplicateAsDraft',
            array($this, 'duplicateAsDraft')
        );

        add_filter(
            'page_row_actions',
            array($this, 'addDuplicateAction'),
            10,
            2
        );

        add_filter(
            'post_row_actions',
            array($this, 'addDuplicateAction'),
            10,
            2
        );
    }

    /**
     * Registers a given CustomPostType for duplication action by slug.
     *
     * @since 1.0.0
     *
     * @param CustomPostType $cpt The post to be registered.
     */
    public function register(CustomPostType $cpt): void
    {
        $this->registered_cpt_slugs[] = $cpt->getSlug();
    }

    /**
     * Duplicates a post as draft
     * 
     * Function for post duplication. Dups appear as drafts.
     * User is redirected to the edit screen.
     * 
     * @global wpdb $wpdb Used to query the database for the needed data
     */
    public function duplicateAsDraft(): void
    {
        global $wpdb;
        if (!(isset($_GET['post']) ||
            isset($_POST['post']) ||
            (isset($_REQUEST['action']) &&
                'duplicateAsDraft' == $_REQUEST['action']))) {
            wp_die('No post to duplicate has been supplied!');
        }

        /**
         * Nonce verification
         */
        if (
            !isset($_GET['duplicate_nonce']) ||
            !wp_verify_nonce($_GET['duplicate_nonce'], basename(__FILE__))
        )
            return;

        /**
         * get the original post id
         */
        $post_id = isset($_GET['post']) ? absint($_GET['post']) : absint($_POST['post']);

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
        } else {
            // FIXME i18n
            wp_die('Post creation failed, could not find original post: ' . $post_id);
        }
    }

    /**
     * Adds the duplicate link to the posts quick action list.
     *
     * @since 1.0.0
     */
    public function addDuplicateAction(array $actions, WP_Post $post): array
    {
        if (
            current_user_can('edit_posts') &&
            in_array($post->post_type, $this->registered_cpt_slugs)
        ) {
            $actionURL = 'admin.php?action=duplicateAsDraft&post=' . $post->ID;
            $nonce = wp_nonce_url($actionURL, basename(__FILE__), 'duplicate_nonce');
            $format = '<a href="%s" title="%s" rel="permalink">%s</a>';
            // FIXME i18n
            $action = sprintf(
                $format,
                $nonce,
                'Eintrag duplizieren',
                'Duplizieren'
            );
            $actions['duplicate'] = $action;
        }
        return $actions;
    }
}
