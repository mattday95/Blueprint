<?php
use Inc\Theme;
// Import composer autoload functionality

if( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' )):
    require_once( dirname( __FILE__ ) . '/vendor/autoload.php' );
endif;

if( class_exists( "Inc\Theme" ) ):
    $theme = new Theme();
    $theme->run();
endif;