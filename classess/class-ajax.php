<?php
class Customify_Sites_Ajax {
    protected $mapping = array();
    function __construct()
    {
        // Install Plugin
        add_action( 'wp_ajax_cs_install_plugin', array( Customify_Sites_Plugin::get_instance(), 'ajax' ) );
        // Active Plugin
        add_action( 'wp_ajax_cs_active_plugin', array( Customify_Sites_Plugin::get_instance(), 'ajax' ) );

        // Import Content
        add_action( 'wp_ajax_cs_import_content', array( $this, 'ajax_import_content' ) );
        add_action( 'wp_ajax_cs_import_options', array( $this, 'ajax_import_options' ) );

        // Download files
        add_action( 'wp_ajax_cs_download_files', array( $this, 'ajax_download_files' ) );

        add_action( 'wp_ajax_cs_export', array( $this, 'ajax_export' ) );

        add_filter( 'upload_mimes', array( $this, 'add_mime_type_xml_json' ) );

        add_filter( 'rss2_head', array( $this, 'export_remove_rss_title' ), 95 );

    }

    function export_remove_rss_title( ) {
        if ( isset( $_GET['download'], $_GET['download'] ) ) {
            remove_filter( 'the_title_rss','strip_tags' );
            remove_filter( 'the_title_rss','ent2ncr', 8 );
            remove_filter( 'the_title_rss','esc_html' );

           // apply_filters( 'the_title_rss', $post->post_title );
            add_filter( 'the_title_rss', array( $this, 'the_title_rss' ) );
        }
    }

    function the_title_rss( $title ){
        if ( function_exists( 'wxr_cdata' ) ) {
            return wxr_cdata( $title );
        }
        return $title;
    }

    /**
     * Available widgets
     *
     * Gather site's widgets into array with ID base, name, etc.
     * Used by export and import functions.
     *
     * @since 0.4
     * @global array $wp_registered_widget_updates
     * @return array Widget information
     */
     function _get_available_widgets() {

        global $wp_registered_widget_controls;

        $widget_controls = $wp_registered_widget_controls;

        $available_widgets = array();

        foreach ( $widget_controls as $widget ) {

            if ( ! empty( $widget['id_base'] ) && ! isset( $available_widgets[$widget['id_base']] ) ) { // no dupes

                $available_widgets[$widget['id_base']]['id_base'] = $widget['id_base'];
                $available_widgets[$widget['id_base']]['name'] = $widget['name'];

            }

        }

        return $available_widgets;

    }

    /**
     * Generate Widgets export data
     *
     * @since 0.1
     * @return string Export file contents
     */
     function _get_widgets_export_data() {

        // Get all available widgets site supports
        $available_widgets = $this->_get_available_widgets();

        // Get all widget instances for each widget
        $widget_instances = array();
        foreach ( $available_widgets as $widget_data ) {

            // Get all instances for this ID base
            $instances = get_option( 'widget_' . $widget_data['id_base'] );

            // Have instances
            if ( ! empty( $instances ) ) {

                // Loop instances
                foreach ( $instances as $instance_id => $instance_data ) {

                    // Key is ID (not _multiwidget)
                    if ( is_numeric( $instance_id ) ) {
                        $unique_instance_id = $widget_data['id_base'] . '-' . $instance_id;
                        $widget_instances[$unique_instance_id] = $instance_data;
                    }
                }
            }
        }

        // Gather sidebars with their widget instances
        $sidebars_widgets = get_option( 'sidebars_widgets' ); // get sidebars and their unique widgets IDs
        $sidebars_widget_instances = array();
        foreach ( $sidebars_widgets as $sidebar_id => $widget_ids ) {

            // Skip inactive widgets
            if ( 'wp_inactive_widgets' == $sidebar_id ) {
                continue;
            }

            // Skip if no data or not an array (array_version)
            if ( ! is_array( $widget_ids ) || empty( $widget_ids ) ) {
                continue;
            }

            // Loop widget IDs for this sidebar
            foreach ( $widget_ids as $widget_id ) {

                // Is there an instance for this widget ID?
                if ( isset( $widget_instances[$widget_id] ) ) {
                    // Add to array
                    $sidebars_widget_instances[$sidebar_id][$widget_id] = $widget_instances[$widget_id];
                }

            }

        }

        // Filter pre-encoded data
        $data = apply_filters( 'customify_sites_export_widgets_data', $sidebars_widget_instances );

        // Encode the data for file contents
        return $data;

    }

    function _get_elementor_settings(){
         global $wpdb;
         $rows = $wpdb->get_results( "SELECT * FROM `{$wpdb->options}` WHERE `option_name` LIKE 'elementor_scheme_%'", ARRAY_A );
         $data = array();
         foreach( ( array ) $rows as $row ) {
             $data[ $row['option_name'] ] = get_option( $row['option_name'] );
        }

        return $data;
    }

    function ajax_export(){

        ob_start();
        ob_end_clean();
        ob_flush();

        $sitename = sanitize_key( get_bloginfo( 'name' ) );
        if ( ! empty( $sitename ) ) {
            $sitename .= '.';
        }
        $date = date( 'Y-m-d' );
        $filename = $sitename . 'wordpress.' . $date . '.json';

        header( 'Content-Description: File Transfer' );
        header( 'Content-Disposition: attachment; filename=' . $filename );
        header( 'Content-Type: application/xml; charset=' . get_option( 'blog_charset' ), true );

        $nav_menu_locations = get_theme_mod( 'nav_menu_locations' );

        $options = $this->_get_elementor_settings();
        $options['show_on_front'] = get_option( 'show_on_front' );

        $config = array(
            'home_url' => home_url('/'),
            'menus' => $nav_menu_locations,
            'pages' => array(
                'page_on_front'  => get_option( 'page_on_front' ),
                'page_for_posts' => get_option( 'page_for_posts' ),
            ),
            'options' => $options,
            'theme_mods' => get_theme_mods(),
            'widgets'  => $this->_get_widgets_export_data(),
        );

        echo wp_json_encode( $config );
        die();
    }

    function install_theme(){
        /**
         * @see app/public/wp-admin/update.php L215
         *
         * https://beacon.dev/wp-admin/admin-ajax.ph
         slug: switty
        action: install-theme
        _ajax_nonce: 647d5a7e33
         */
    }

    /**
     * Add .xml files as supported format in the uploader.
     *
     * @param array $mimes Already supported mime types.
     */
    public function add_mime_type_xml_json( $mimes ) {
        $mimes = array_merge( $mimes, array(
            'xml' => 'application/xml',
            'json' => 'application/json'
        ) );
        return $mimes;
    }

    function user_can(){
        if ( ! current_user_can( 'manage_options' ) ) {
            die( 'access_denied' );
        }
    }

    function ajax_import_content(){

        $this->user_can();

        $import_ui = new Customify_Sites_WXR_Import_UI();
        $import_ui->import();

        die( 'content_imported' );

    }



    function is_url( $url ){
        $result = ( false !== filter_var( $url, FILTER_VALIDATE_URL ) );
        return $result;
    }

    function ajax_download_files(){
        $this->user_can();

        // try to get files exists

        $slug = sanitize_text_field( wp_unslash( $_REQUEST['site_slug'] ) );
        $xml_url = sanitize_text_field( wp_unslash(  $_REQUEST['xml_url'] ) );
        $json_url = sanitize_text_field( wp_unslash( $_REQUEST['json_url'] ) );


        $return = array(
            'xml_id' => 0,
            'json_id' => 0,
            'summary' => array(),
            'texts' => array()
        );

        if ( ! $slug ) {
            return $return;
        }

        $xml_file_name = $slug.'-demo-content';
        $json_file_name = $slug.'-demo-config';

        $xml_file_exists = get_page_by_path( $xml_file_name, OBJECT, 'attachment' );
        $json_file_exists = get_page_by_path( $json_file_name, OBJECT, 'attachment' );
        if ( $xml_file_exists ) {
            $return['xml_id'] = $xml_file_exists->ID;
        } else {
            $return['xml_id'] = Customify_Sites_Ajax::download_file( $xml_url, $xml_file_name.'.xml' );
        }

        if ( $json_file_exists ) {
            $return['json_id'] = $json_file_exists->ID;
        } else {
            $return['json_id'] = Customify_Sites_Ajax::download_file( $json_url, $json_file_name.'.json' );
        }

        $import_ui = new Customify_Sites_WXR_Import_UI();
        $return['summary'] = $import_ui->get_data_for_attachment( $return['xml_id'] );

        $return['summary'] = ( array ) $return['summary'];
        if ( ! is_array( $return['summary'] ) ) {
            $return['summary'] = array();
        }

        $return['summary']  = wp_parse_args( $return['summary'], array(
            'post_count' => 0,
            'media_count' => 0,
            'user_count' => 0,
            'term_count' => 0,
            'comment_count' => 0,
            'users' => 0,
        ) );

        if ( isset( $return['summary']['users'] ) ) {
            $return['summary']['user_count'] = count( $return['summary']['users'] );
        }

        $return['texts']['post_count'] = sprintf( _n( '%d post (including CPT)', '%d posts (including CPTs)', $return['summary']['post_count'], 'customify-sites' ), $return['summary']['post_count'] );
        $return['texts']['media_count'] = sprintf( _n( '%d media item', '%d media item', $return['summary']['media_count'], 'customify-sites' ), $return['summary']['media_count'] );
        $return['texts']['user_count'] = sprintf( _n( '%d user', '%d users', $return['summary']['user_count'], 'customify-sites' ), $return['summary']['user_count'] );
        $return['texts']['term_count'] = sprintf( _n( '%d term', '%d terms', $return['summary']['term_count'], 'customify-sites' ), $return['summary']['term_count'] );
        $return['texts']['comment_count'] = sprintf( _n( '%d comment', '%d comments', $return['summary']['comment_count'], 'customify-sites' ), $return['summary']['comment_count'] );
        wp_send_json( $return );
    }

    /**
     * Handles a side-loaded file in the same way as an uploaded file is handled by media_handle_upload().
     *
     * @since 2.6.0
     *
     * @param array  $file_array Array similar to a `$_FILES` upload array.
     * @param int    $post_id    The post ID the media is associated with.
     * @param string $desc       Optional. Description of the side-loaded file. Default null.
     * @param array  $post_data  Optional. Post data to override. Default empty array.
     * @return int|object The ID of the attachment or a WP_Error on failure.
     */
    static function media_handle_sideload( $file_array, $post_id, $desc = null, $post_data = array(), $save_attachment = true ) {
        $overrides = array(
            'test_form'=>false,
            'test_type'=>false
        );

        $time = current_time( 'mysql' );
        if ( $post = get_post( $post_id ) ) {
            if ( substr( $post->post_date, 0, 4 ) > 0 )
                $time = $post->post_date;
        }

        $file = wp_handle_sideload( $file_array, $overrides, $time );
        if ( isset($file['error']) )
            return new WP_Error( 'upload_error', $file['error'] );

        $url = $file['url'];
        $type = $file['type'];
        $file = $file['file'];
        $title = preg_replace('/\.[^.]+$/', '', basename($file));
        $content = '';

        if ( $save_attachment ) {
            if (isset($desc)) {
                $title = $desc;
            }

            // Construct the attachment array.
            $attachment = array_merge(array(
                'post_mime_type' => $type,
                'guid' => $url,
                'post_parent' => $post_id,
                'post_title' => $title,
                'post_content' => $content,
            ), $post_data);

            // This should never be set as it would then overwrite an existing attachment.
            unset($attachment['ID']);

            // Save the attachment metadata
            $id = wp_insert_attachment($attachment, $file, $post_id);

            return $id;
        } else {
            return $file;
        }
    }

    function ajax_import_options(){
        $this->user_can();
        $id = wp_unslash( (int) $_REQUEST['id'] );
        $xml_id = wp_unslash( (int) $_REQUEST['xml_id'] );
        $file = get_attached_file( $id );

        if ( $file ) {

            $this->mapping = get_post_meta( $xml_id, '_wxr_importer_mapping', true );
            if ( ! is_array( $this->mapping ) ) {
                $this->mapping = array();
            }
            global $wp_filesystem;
            WP_Filesystem();

            if (file_exists($file)) {
                $file_contents = $wp_filesystem->get_contents($file);
                $customize_data = json_decode($file_contents, true);
                if (null === $customize_data) {
                    $customize_data = maybe_unserialize($file_contents);
                }
            } else {
                $customize_data = array();
            }

            if ( isset( $customize_data['options'] ) ) {
                $this->_import_options( $customize_data['options'] );
            }

            if ( isset( $customize_data['pages'] ) ) {
                $this->_import_options( $customize_data['pages'], true );
            }

            if ( isset( $customize_data['theme_mods'] ) ) {
                $this->_import_theme_mod( $customize_data['theme_mods'] );
            }

            if ( isset( $customize_data['widgets'] ) ) {
                $this->_import_widgets( $customize_data['widgets'] );
            }
        }

        die( 'ajax_import_options' );
    }

    function _import_options( $options, $re_mapping_posts = false ){
        if ( empty( $options ) ) {
            return ;
        }
        $processed_posts = isset( $this->mapping['post'] ) ? $this->mapping['post'] : array();
        if ( $re_mapping_posts ) {
            foreach ( $options as $option_name => $ops ) {
                if ( isset( $processed_posts[ $ops ] ) ) {
                    $ops = $processed_posts[ $ops ];
                }
                update_option( $option_name, $ops );
            }
        } else {
            foreach ( $options as $option_name => $ops ) {
                update_option( $option_name, $ops );
            }
        }

    }

    /**
     * Import widgets
     */
    function _import_widgets( $data, $widgets_config = array() ) {
        global $wp_filesystem, $wp_registered_widget_controls, $wp_registered_sidebars;

        //add_filter( 'sidebars_widgets', array( $this, '_unset_sidebar_widget' ) );
        if ( empty( $data ) || ! is_array( $data ) ) {
            return ;
        }
        if ( ! is_array( $widgets_config ) ) {
            $widgets_config = array();
        }

        $valid_sidebar = false;
        $widget_instances = array();
        $imported_terms = isset( $this->mapping['term_id'] ) ? $this->mapping['term_id'] : array();
        $imported_posts = isset( $this->mapping['post'] ) ? $this->mapping['post'] : array();

        if ( ! is_array( $imported_terms ) ) {
            $imported_terms = array();
        }

        foreach ( $wp_registered_widget_controls as $widget_id => $widget ) {
            $base_id = isset($widget['id_base']) ? $widget['id_base'] : null;
            if (!empty($base_id) && !isset($widget_instances[$base_id])) {
                $widget_instances[$base_id] = get_option('widget_' . $base_id);
            }
        }

        // Delete old widgets
        update_option('sidebars_widgets', array() );

        foreach ( $data as $sidebar_id => $widgets ) {
            if ('wp_inactive_widgets' === $sidebar_id) {
                continue;
            }
            if (isset($wp_registered_sidebars[$sidebar_id])) {
                $valid_sidebar = true;
                $_sidebar_id = $sidebar_id;
            } else {
                $_sidebar_id = 'wp_inactive_widgets';
            }
            foreach ($widgets as $widget_instance_id => $widget) {
                if (false !== strpos($widget_instance_id, 'nav_menu') && !empty($widget['nav_menu'])) {
                    $widget['nav_menu'] = isset($imported_terms[$widget['nav_menu']]) ? $imported_terms[$widget['nav_menu']] : 0;
                }


                // Media gallery widget
                if (false !== strpos($widget_instance_id, 'media_gallery') && !empty($widget['media_gallery'])) {
                    foreach( ( array ) $widget['ids'] as $k => $v ) {
                        $widget[ $k ] = isset( $imported_posts[ $v ] ) ? $imported_posts[ $v ] : 0;
                    }
                }


                $base_id = preg_replace('/-[0-9]+$/', '', $widget_instance_id);
                if (isset($widget_instances[$base_id])) {
                    $single_widget_instances = get_option('widget_' . $base_id);
                    $single_widget_instances = !empty($single_widget_instances) ? $single_widget_instances : array('_multiwidget' => 1);

                    $single_widget_instances[] = apply_filters( 'customify_sites_import_widget_data', $widget, $widget_instances[$base_id], $base_id );
                    end($single_widget_instances);
                    $new_instance_id_number = key($single_widget_instances);
                    if ('0' === strval($new_instance_id_number)) {
                        $new_instance_id_number = 1;
                        $single_widget_instances[$new_instance_id_number] = $single_widget_instances[0];
                        unset($single_widget_instances[0]);
                    }
                    if (isset($single_widget_instances['_multiwidget'])) {
                        $multiwidget = $single_widget_instances['_multiwidget'];
                        unset($single_widget_instances['_multiwidget']);
                        $single_widget_instances['_multiwidget'] = $multiwidget;
                    }
                    $updated = update_option('widget_' . $base_id, $single_widget_instances);
                    $sidebars_widgets = get_option('sidebars_widgets');
                    $sidebars_widgets[$_sidebar_id][] = $base_id . '-' . $new_instance_id_number;
                    update_option('sidebars_widgets', $sidebars_widgets);
                }
            }
        }

    }


    function down_load_image($file)
    {
        $data = new \stdClass();

        if ( !function_exists('media_handle_sideload') ) {
            require ABSPATH . 'wp-admin/includes/media.php';
            require ABSPATH . 'wp-admin/includes/file.php';
            require ABSPATH . 'wp-admin/includes/image.php';
        }

        if ( !empty($file) ) {
            preg_match('/[^\?]+\.(jpe?g|jpe|gif|png)\b/i', $file, $matches);
            $file_array = array();
            $file_array['name'] = basename($matches[0]);
            $file_array['tmp_name'] = download_url($file);
            if ( is_wp_error($file_array['tmp_name']) ) {
                return $file_array['tmp_name'];
            }
            $id = media_handle_sideload($file_array, 0);
            if ( is_wp_error($id) ) {
                unlink($file_array['tmp_name']);
                return $id;
            }
            $meta                = wp_get_attachment_metadata($id);
            $data->attachment_id = $id;
            $data->url           = wp_get_attachment_url($id);
            $data->thumbnail_url = wp_get_attachment_thumb_url($id);
            $data->height        = $meta['height'];
            $data->width         = $meta['width'];
        }

        return $data;
    }

    function _import_theme_mod( $customize_data = array() ) {
        global $wp_customize;


        if (!empty($customize_data)) {

            $imported_terms = isset( $this->mapping['term_id'] ) ? $this->mapping['term_id'] : array();
            $processed_posts = isset( $this->mapping['post'] ) ? $this->mapping['post'] : array();

            foreach ($customize_data as $mod_key => $mod_value) {
                if ( ! is_numeric( $mod_key ) ) {

                    if (is_string($mod_value) && preg_match('/\.(jpg|jpeg|png|gif)/i', $mod_value)) {
                        $attachment = $this->down_load_image($mod_value);
                        if (!is_wp_error($attachment)) {
                            $mod_value = $attachment->url;
                            $index_key = $mod_key . '_data';
                            if (isset($customize_data['mods'][$index_key])) {
                                $customize_data['mods'][$index_key] = $attachment;
                                update_post_meta($attachment->attachment_id, '_wp_attachment_is_custom_header', get_option('stylesheet'));
                            }
                        }
                    }

                    if ('nav_menu_locations' === $mod_key) {

                        if (!is_array($imported_terms)) {
                            $imported_terms = array();
                        }
                        foreach ($mod_value as $menu_location => $menu_term_id) {
                            $mod_value[$menu_location] = isset($imported_terms[$menu_term_id]) ? $imported_terms[$menu_term_id] : $menu_term_id;
                        }
                    }
                    if ('custom_logo' == $mod_key) {
                        if (!is_array($processed_posts)) {
                            $processed_posts = array();
                        }
                        $mod_value = isset($processed_posts[$mod_value]) ? $processed_posts[$mod_value] : $mod_value;
                    }

                    set_theme_mod($mod_key, $mod_value);
                }
            }
        }

    }


    /**
     * Download image form url
     *
     * @return bool
     */
    static function download_file( $url, $name = '', $save_attachment = true ){
        if ( ! $url || empty ( $url ) ) {
            return false;
        }
        // These files need to be included as dependencies when on the front end.
        require_once (ABSPATH . 'wp-admin/includes/image.php');
        require_once (ABSPATH . 'wp-admin/includes/file.php');
        require_once (ABSPATH . 'wp-admin/includes/media.php');
        $file_array = array();
        // Download file to temp location.
        $file_array['tmp_name'] = download_url( $url );

        // If error storing temporarily, return the error.
        if ( empty( $file_array['tmp_name'] ) || is_wp_error( $file_array['tmp_name'] ) ) {
            return false;
        }

        if ( $name ) {
            $file_array['name'] = $name;
        } else {
            $file_array['name'] = basename( $url );
        }
        // Do the validation and storage stuff.
        $file_path_or_id = self::media_handle_sideload( $file_array, 0, null, array(), $save_attachment );

        // If error storing permanently, unlink.
        if ( is_wp_error( $file_path_or_id ) ) {
            @unlink( $file_array['tmp_name'] );
            return false;
        }
        return $file_path_or_id;
    }

}