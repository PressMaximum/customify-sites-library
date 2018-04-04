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

        // Download files
        add_action( 'wp_ajax_cs_download_files', array( $this, 'ajax_download_files' ) );

        // Download files
        add_action( 'wp_ajax_css_import_content', array( $this, 'ajax_import_content' ) );

        add_filter( 'upload_mimes', array( $this, 'add_mime_type_xml_json' ) );

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

    function ajax_import_options(){
        $this->user_can();


        die( 'ajax_import_options' );
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
            'json_id' => 0
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