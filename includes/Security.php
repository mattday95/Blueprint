<?php

/**
 * @package Blueprint
 */

namespace Inc;

class Security
{
    protected $actions;
    protected $filters;

    public function __construct()
    {
        $this->init();
    }

    private function init()
    {
        $this->cleanHead();
        $this->preventAdminSniffing();
    }

    private function cleanHead()
    {
        // Remove unnecessary elements from wp_head
        remove_action('wp_head','rsd_link');
        remove_action('wp_head','wp_generator');
        remove_action('wp_head','feed_links', 2);
        remove_action('wp_head','index_rel_link');
        remove_action('wp_head','wlwmanifest_link');
        remove_action('wp_head','feed_links_extra', 3);
        remove_action('wp_head','start_post_rel_link', 10, 0);
        remove_action('wp_head','parent_post_rel_link', 10, 0);
        remove_action('wp_head','adjacent_posts_rel_link', 10, 0);
    }

    private function preventAdminSniffing()
    {
        // Prevent admin username sniffing
        if(isset($_GET['author'])) { header('Location: '.get_site_url()); die(); }
    }

    public function disableEmojicons()
    {
        // Disable all actions related to emojis
        remove_action('admin_print_styles', 'print_emoji_styles');
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');
    }

    public function disableEmojiconsTinymce( $plugins )
    {
        if (is_array($plugins)) {
            return array_diff($plugins, array('wpemoji'));
        } else {
            return array();
        }
    }

    public function removeWPVersion()
    {
        return '';
    }

    public function enableXMLRPCAuth()
    {
        return false;
    }

    public function removeAssetVersion( $src ) {
        if( strpos( $src, '?ver=' ) )
            $src = remove_query_arg( 'ver', $src );
        return $src;
    }
}