<?php
class Customify_Sites_Ajax {
    function __construct()
    {
        // Install Plugin
        add_action( 'wp_ajax_cs_install_plugin', array( Customify_Sites_Plugin::get_instance(), 'ajax' ) );
        // Active Plugin
        add_action( 'wp_ajax_cs_active_plugin', array( Customify_Sites_Plugin::get_instance(), 'ajax' ) );

        // Import Content
        add_action( 'wp_ajax_cs_import_content', array( $this, 'ajax_import_content' ) );
        add_action( 'wp_ajax_cs_import_options', array( $this, 'ajax_import_options' ) );


    }

    function ajax_import_content(){

        die( 'content_imported' );

    }

    function ajax_import_options(){

        die( 'ajax_import_options' );

    }


}

new Customify_Sites_Ajax();