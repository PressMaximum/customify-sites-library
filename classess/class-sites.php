<?php

Class Customify_Sites {
    static $_instance = null;

    function scripts(){
        wp_localize_script('jquery', 'Customify_Sites',  $this->get_localize_script() );
        wp_enqueue_script('customify-sites', CUSTOMIFY_SITES_URL.'/assets/js/frontend.js' );
        wp_enqueue_style('customify-sites', CUSTOMIFY_SITES_URL.'/assets/js/frontend.css' );
    }

    function admin_scripts( $id ){
        if( $id == 'appearance_page_customify-sites' ){
            wp_localize_script('jquery', 'Customify_Sites',  $this->get_localize_script() );
            wp_enqueue_style('customify-sites', CUSTOMIFY_SITES_URL.'/assets/css/customify-sites.css' );
            wp_enqueue_script('customify-sites', CUSTOMIFY_SITES_URL.'/assets/js/backend.js',  array( 'jquery' ), false, true );
        }
    }

    static function get_instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
            add_action( 'wp_enqueue_scripts', array( self::$_instance, 'scripts' ) );
            add_action('admin_menu', array( self::$_instance, 'add_menu' ), 50 );
            add_action( 'admin_enqueue_scripts', array( self::$_instance, 'admin_scripts' ) );
        }
        return self::$_instance;
    }

    static function get_api_url(){
        return apply_filters( 'customify_sites/api_url', 'https://customifysites.com/wp-json/wp/v2/sites/' );
    }

    function add_menu() {
        add_theme_page(__( 'Customify Sites', 'customify-sites' ), __( 'Customify Sites', 'customify-sites' ), 'edit_theme_options', 'customify-sites', array( $this, 'page' ));
    }

    function page(){
        echo '<div class="wrap">';
        echo '<h1 class="wp-heading-inline">'.__( 'Customify Sites', 'customify-sites' ).'</h1>';
        require_once CUSTOMIFY_SITES_PATH.'/templates/dashboard.php';
        require_once CUSTOMIFY_SITES_PATH.'/templates/modal.php';
        echo '</div>';
    }

    function get_localize_script(){
        $args = array(
            'api_url' => self::get_api_url(),
            'is_admin' => is_admin(),
        );
        return $args;
    }

}

Customify_Sites::get_instance();