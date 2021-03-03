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