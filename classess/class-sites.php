<?php

Class Customify_Starter_Sites {
    static $_instance = null;
    const THEME_NAME = 'customify';


    function admin_scripts( $id ){
        if( $id == 'appearance_page_customify-starter-sites' ){

            if( ! function_exists('get_plugin_data') ){
                require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
            }
            $plugin_data = get_plugin_data( __FILE__ );


            wp_localize_script('jquery', 'Customify_Starter_Sites',  $this->get_localize_script() );
            wp_enqueue_style('owl.carousel', CUSTOMIFY_STARTER_SITES_URL.'/assets/css/owl.carousel.css', [], $plugin_data['Version'] );
            wp_enqueue_style('owl.theme.default', CUSTOMIFY_STARTER_SITES_URL.'/assets/css/owl.theme.default.css', [], $plugin_data['Version'] );
            wp_enqueue_style("customify-starter-sites", CUSTOMIFY_STARTER_SITES_URL.'/assets/css/customify-sites.css', [], $plugin_data['Version'] );

            wp_enqueue_script('owl.carousel', CUSTOMIFY_STARTER_SITES_URL.'/assets/js/owl.carousel.min.js',  array( 'jquery' ), $plugin_data['Version'], true );
            wp_enqueue_script("customify-starter-sites", CUSTOMIFY_STARTER_SITES_URL.'/assets/js/backend.js',  array( 'jquery', 'underscore' ), $plugin_data['Version'], true );
        }
    }

    static function get_instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
            add_action( 'admin_menu', array( self::$_instance, 'add_menu' ), 50 );
            add_action( 'admin_enqueue_scripts', array( self::$_instance, 'admin_scripts' ) );
            add_action( 'admin_notices', array( self::$_instance, 'admin_notice' ) );
        }
        return self::$_instance;
    }

    function admin_notice( $hook ) {
        $screen = get_current_screen();
        if( $screen->id != 'appearance_page_customify-sites' && $screen->id != 'themes' ) {
            return '';
        }

        if( get_template() == self::THEME_NAME  ) {
            return '';
        }

        $themes = wp_get_themes();
        if ( isset( $themes[ self::THEME_NAME ] ) ) {
            $url = esc_url( 'themes.php?theme='.self::THEME_NAME );
        } else {
            $url = esc_url( 'theme-install.php?search='.self::THEME_NAME );
        }

        $html = sprintf( '<strong>Customify Site Library</strong> requires <strong>Customify</strong> theme to be activated to work. <a href="%1$s">Install &amp; Activate Now</a>', $url );
        ?>
        <div class="notice notice-warning is-dismissible">
            <p>
                <?php echo $html; ?>
            </p>
        </div>
        <?php
    }

    static function get_api_url(){
        return apply_filters( 'customify_sites/api_url', 'https://customifysites.com/wp-json/wp/v2.1/sites/' );
    }

    function add_menu() {
        add_theme_page(__( 'Customify Sites', "customify-starter-sites", 'customify-sites' ), __( 'Customify Sites', "customify-starter-sites", 'customify-sites' ), 'edit_theme_options', "customify-starter-sites", array( $this, 'page' ));
    }

    function page(){
        echo '<div class="wrap">';
        echo '<h1 class="wp-heading-inline">'.__( 'Customify Site Library', "customify-starter-sites", 'customify-sites' ).'</h1><hr class="wp-header-end">';
        require_once CUSTOMIFY_STARTER_SITES_PATH.'/templates/dashboard.php';
        require_once CUSTOMIFY_STARTER_SITES_PATH.'/templates/modal.php';
        echo '</div>';
        require_once CUSTOMIFY_STARTER_SITES_PATH.'/templates/preview.php';
    }

    function get_installed_plugins(){
        // Check if get_plugins() function exists. This is required on the front end of the
        // site, since it is in a file that is normally only loaded in the admin.
        if ( ! function_exists( 'get_plugins' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        $all_plugins = get_plugins();
        if ( ! is_array( $all_plugins ) ) {
            $all_plugins = array();
        }

        $plugins = array();
        foreach ( $all_plugins as $file => $info ) {
            $slug = dirname( $file );
            $plugins[ $slug ] = $info['Name'];
        }

        return $plugins;
    }

    function get_activated_plugins(){
        $activated_plugins = array();
        foreach( ( array ) get_option('active_plugins') as $plugin_file ) {
            $plugin_file = dirname( $plugin_file );
            $activated_plugins[ $plugin_file ] = $plugin_file;
        }
        return $activated_plugins;
    }

    function get_support_plugins(){
        $plugins = array(
            'customify-pro' => _x( 'Customify Pro', 'plugin-name', "customify-starter-sites", 'customify-sites' ),

            'elementor' => _x( 'Elementor', 'plugin-name', "customify-starter-sites", 'customify-sites' ),
            'elementor-pro' => _x( 'Elementor Pro', 'plugin-name', "customify-starter-sites", 'customify-sites' ),
            'beaver-builder-lite-version' => _x( 'Beaver Builder', 'plugin-name', "customify-starter-sites", 'customify-sites' ),
            'contact-form-7' => _x( 'Contact Form 7', 'plugin-name', "customify-starter-sites", 'customify-sites' ),

            'breadcrumb-navxt' => _x( 'Breadcrumb NavXT', 'plugin-name', "customify-starter-sites", 'customify-sites' ),
            'jetpack' => _x( 'JetPack', 'plugin-name', "customify-starter-sites", 'customify-sites' ),
            'easymega' => _x( 'Mega menu', 'plugin-name', "customify-starter-sites", 'customify-sites' ),
            'polylang' => _x( 'Polylang', 'plugin-name', "customify-starter-sites", 'customify-sites' ),
            'woocommerce' => _x( 'WooCommerce', 'plugin-name', "customify-starter-sites", 'customify-sites' ),
            'give' => _x( 'Give – Donation Plugin and Fundraising Platform', 'plugin-name', "customify-starter-sites", 'customify-sites' ),
        );

        return $plugins;
    }

    function is_license_valid(){

        if ( ! class_exists('Customify_Pro' ) ) {
            return false;
        }
	    $pro_data = get_option('customify_pro_license_data');
	    if ( ! is_array( $pro_data ) ) {
	        return false;
        }
	    if ( ! isset( $pro_data['license'] ) ) {
		    return false;
	    }

	    if ( ! isset( $pro_data['data'] ) || ! is_array( $pro_data['data'] ) ) {
		    return false;
	    }

	    if ( isset( $pro_data['data']['license'] ) && $pro_data['data']['license'] == 'valid' &&  $pro_data['data']['success'] ) {
            return true;
        }

        return false;
    }

    function get_localize_script(){

        $args = array(
            'api_url' => self::get_api_url(),
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'is_admin' => is_admin(),
            'try_again' => __( 'Try Again', "customify-starter-sites", 'customify-sites' ),
            'pro_text' => __( 'Pro only', "customify-starter-sites", 'customify-sites' ),
            'activated_plugins' => $this->get_activated_plugins(),
            'installed_plugins' => $this->get_installed_plugins(),
            'support_plugins' => $this->get_support_plugins(),
            'license_valid' =>   $this->is_license_valid(),
        );

        $args['elementor_clear_cache_nonce'] = wp_create_nonce( 'elementor_clear_cache' );
        $args['elementor_reset_library_nonce'] = wp_create_nonce( 'elementor_reset_library' );

        return $args;
    }

}
