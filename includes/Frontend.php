<?php

/**
 * @package Blueprint
 */

namespace Inc;

class Frontend {

    private $displayAdminBar;

    public function __construct(){
        $this->displayAdminBar = getenv('DISPLAY_ADMIN_BAR') ? getenv('DISPLAY_ADMIN_BAR') : true;
    }

    public function enqueueScripts() {
         // Enqueue styles.
        wp_enqueue_style( 'blueprint-custom-styles', \get_theme_file_uri('dist/css/app.css') );
        // Enqueue scripts.
        wp_enqueue_script( 'blueprint-custom-scripts', \get_theme_file_uri('dist/js/app.js'));
    }

    public function showAdminBar()
    {
        // If current user isn't admin, don't display the admin bar. Ever.
        if( !current_user_can( 'manage_options' ) ){
            return false;
        }

        return $this->displayAdminBar;
    }
}