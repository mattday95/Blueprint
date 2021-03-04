<?php

/**
 * @package Blueprint
 */

namespace Inc;

use StoutLogic\AcfBuilder\FieldsBuilder;

class Backend
{
    private $showAcfUi;

    public function __construct() {
        $this->showAcfUI = getenv('WP_ENV') == "development" || getenv('ENABLE_ACF_UI') == true;
    }

    public function loadCustomFields()
    {
        $fields = $this->collectFields();

        if(count($fields) > 0):
            $this->initialiseFields($fields);
        endif;
    }

    private function initialiseFields( $fields ) {

        array_map(function ($field) {
            if ($field instanceof FieldsBuilder) {
                \acf_add_local_field_group($field->build());
            }
        }, $fields);

    }

    public function displayLoginMessage()
    {
        if (getenv('WP_ENV') != 'staging') {
            return false;
        }

        require_once(__DIR__ . '/partials/admin/login_message.php');
    }

    public function showAcfUI() {
        return $this->showAcfUI;
    }

    public function enqueueLoginStyling() {
        wp_enqueue_style('blueprint-custom-login', get_stylesheet_directory_uri() . '/dist/css/app.css');
    }

    public function addOptionsPage() {

        acf_add_options_page(array(
            'page_title' => 'Theme Settings',
            'menu_title' => 'Theme Settings',
            'menu_slug' => 'theme-settings',
            'capability' => 'edit_posts',
            'redirect' => false,
        ));

    }

    public function removeLoginShake()
    {
        remove_action('login_footer', 'wp_shake_js', 12);
    }

    public function removeAdditionalCssOption($wp_customize)
    {
        $wp_customize->remove_section('custom_css');
    }

    public function addAdminBarMessage()
    {
        global $wp_admin_bar;
    
        if(getenv('WP_ENV') != 'staging') {
            return false;
        }
    
        $wp_admin_bar->add_node([
            'id'    => 'env_notification',
            'title' => require_once(__DIR__ . '/partials/admin/env_notification.php')
        ]);
    }

    public function removeDashboardWidgets()
    {
        remove_meta_box('dashboard_recent_comments', 'dashboard', 'core');
        remove_meta_box('dashboard_incoming_links', 'dashboard', 'core');
        remove_meta_box('dashboard_plugins', 'dashboard', 'core');
        remove_meta_box('dashboard_quick_press', 'dashboard', 'core');
        remove_meta_box('dashboard_recent_drafts', 'dashboard', 'core');
        remove_meta_box('dashboard_primary', 'dashboard', 'core');    
        remove_meta_box('dashboard_secondary', 'dashboard', 'core');
        remove_meta_box('yoast_db_widget', 'dashboard', 'normal');  
    }

    public function hideAdminThankyou()
    {
        return '';
    }

    private function collectFields( ) {

        $fields = [];
        $dirs = array_filter(glob(__DIR__ . '/fields/*'), 'is_dir');

        foreach ($dirs as $dir) {

            $collectedFields = array_map(function ($component) {
                return require_once ($component);
            }, glob($dir.'/*.php'));

            $fields = array_merge($fields, $collectedFields);
        }

        return $fields;
    }
}