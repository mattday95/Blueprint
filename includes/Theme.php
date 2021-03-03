<?php
/**
 * @package Blueprint
*/
namespace Inc;

use Inc\Loader;
use Inc\Frontend;
use Inc\Backend;

class Theme
{
    private $frontend;
    private $backend;
    private $loader;
    private $acfInstalled;

    public function __construct() {
        $this->loader = new Loader();
        $this->acfInstalled = class_exists('ACF');
        $this->initTheme();
    }

    private function initTheme() {
        $this->initFrontend();
        $this->initBackend();
        $this->addThemeSupports();
    }

    private function initFrontend() {
        // Initialise the front end of our site
        $this->frontend = new Frontend();
        $this->loader->add_action('wp_enqueue_scripts', $this->frontend, 'enqueueScripts');
        $this->loader->add_filter('show_admin_bar', $this->frontend, 'showAdminBar');
    }

    private function initBackend() {
        // Initialise the back end of our site
        $this->backend = new Backend();

        // Add core actions and filters
        $this->loader->add_action( 'login_enqueue_scripts', $this->backend, 'enqueueLoginStyling' );
        $this->loader->add_filter('login_headerurl', $this, 'getHomeUrl');
        $this->loader->add_filter('login_headertext', $this, 'getBlogInfo');
        $this->loader->add_filter('login_message', $this->backend, 'displayLoginMessage');
        
        // Add acf specific actions and filters
        if($this->acfInstalled):
            $this->loader->add_filter('acf/settings/show_admin', $this->backend, 'showAcfUI');
            $this->loader->add_action('init', $this->backend, 'loadCustomFields');
            $this->backend->addOptionsPage();
        endif;
    }

    public function getBlogInfo()
    {
        return \get_bloginfo();
    }

    public function getHomeUrl()
    {
        return \home_url();
    }

    private function addThemeSupports() {
        add_theme_support('post-thumbnails');
    }

    public function run() {
        $this->loader->run();
    }
}