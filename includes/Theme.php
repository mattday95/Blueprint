<?php
/**
 * @package Blueprint
*/
namespace Inc;

use Inc\Loader;
use Inc\Frontend;
use Inc\Backend;
use Inc\Security;

class Theme
{
    private $frontend;
    private $backend;
    private $loader;
    private $security;
    private $acfInstalled;

    public function __construct() {
        $this->loader = new Loader();
        $this->acfInstalled = class_exists('ACF');
        $this->initTheme();
    }

    private function initTheme() {
        $this->initFrontend();
        $this->initBackend();
        $this->secureTheme();
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

    private function secureTheme()
    {
        $this->security = new Security();

        // Add security related filters
        $this->loader->add_filter( 'xmlrpc_enabled', $this->security, 'enableXMLRPCAuth' );
        $this->loader->add_filter( 'the_generator', $this->security, 'removeWPVersion' );
        $this->loader->add_filter( 'style_loader_src', $this->security, 'removeAssetVersion', 9999 );
        $this->loader->add_filter( 'script_loader_src', $this->security, 'removeAssetVersion', 9999 );
        $this->loader->add_filter('tiny_mce_plugins', $this->security, 'disableEmojiconsTinymce');

        // Add security related action hooks
        $this->loader->add_action('init', $this->security, 'disableEmojicons');
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