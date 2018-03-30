<?php
class Customify_Sites_Frontend {
    function __construct()
    {
        add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
    }
    function scripts(){
        wp_localize_script()
        wp_enqueue_style('customify-sites', CUSTOMIFY_SITES_URL.'/assets/js/frontend.js' );
        wp_enqueue_style('customify-sites', CUSTOMIFY_SITES_URL.'/assets/js/frontend.js' );
    }
}