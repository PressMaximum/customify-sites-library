<?php
//var_dump( $this->get_activated_plugins() );

//Customify_Sites_Ajax::download_file('https://raw.githubusercontent.com/FameThemes/famethemes-xml-demos/master/boston/config.json');

if( isset( $_REQUEST['dev'] ) ){
?>
<a class="page-title-action" href="<?php echo esc_url(admin_url('export.php?content=all&download=true')); ?>"><?php _e('Export XML', 'customify-sites'); ?></a>
<a class="page-title-action" href="<?php echo esc_url(admin_url('admin-ajax.php?action=cs_export')); ?>"><?php _e('Export Config', 'customify-sites'); ?></a>
<?php
}
?>
<div id="customify-sites-filter" class="wp-filter hide-if-no-js">
    <div class="filter-count">
        <span id="customify-sites-filter-count" class="count theme-count">&#45;</span>
    </div>
    <ul id="customify-sites-filter-cat" class="filter-links">
        <li><a href="#" data-slug="all" class="current"><?php _e( 'All', 'customify-sites' ); ?></a></li>
    </ul>
    <form class="search-form">
        <label class="screen-reader-text" for="wp-filter-search-input"><?php _e( 'Search Themes', 'customify-sites' ); ?></label><input placeholder="<?php esc_attr_e( 'Search demos...', 'customify-sites' ); ?>" type="search" aria-describedby="live-search-desc" id="customify-sites-search-input" class="wp-filter-search">
    </form>
    <ul id="customify-sites-filter-tag"  class="filter-links float-right" style="float: right;"></ul>
</div>

<hr class="wp-header-end">

<script id="customify-site-item-html" type="text/html">
    <div class="theme" title="{{ data.title }}" tabindex="0" aria-describedby="" data-slug="{{ data.slug }}">
        <div class="theme-screenshot">
            <img src="{{ data.thumbnail_url }}" alt="">
        </div>
        <div class="theme-id-container">
            <h2 class="theme-name" id="{{ data.slug }}-name">{{ data.title }}</h2>
            <div class="theme-actions">
                <a class="cs-open-preview button button-secondary  hide-if-no-customize" data-slug="{{ data.slug }}" href="#"><?php _e( 'Preview', 'customify-sites' ); ?></a>
                <a class="cs-open-modal button button-primary  hide-if-no-customize" href="#"><?php _e( 'Details', 'customify-sites' ); ?></a>
            </div>
        </div>
    </div>
</script>


<div id="customify-sites-listing-wrapper" class="theme-browser rendered">
    <div id="customify-sites-listing" class="themes wp-clearfix">
    </div>
</div>
<p  id="customify-sites-no-demos"  class="no-themes"><?php _e( 'No sites found. Try a different search.', 'customify-sites' ); ?></p>
<span class="spinner"></span>


