<?php
//var_dump( $this->get_activated_plugins() );

//Customify_Sites_Ajax::download_file('https://raw.githubusercontent.com/FameThemes/famethemes-xml-demos/master/boston/config.json');

?>
<div id="customify-sites-filter" class="wp-filter hide-if-no-js">
    <div class="filter-count">
        <span id="customify-sites-filter-count" class="count theme-count">&#45;</span>
    </div>
    <ul id="customify-sites-filter-cat" class="filter-links">
        <li><a href="#" data-slug="all" class="current"><?php _e( 'All', "customify-sites-library" ); ?></a></li>
    </ul>
    <form class="search-form">
        <label class="screen-reader-text" for="wp-filter-search-input"><?php _e( 'Search Themes', "customify-sites-library" ); ?></label><input placeholder="<?php esc_attr_e( 'Search sites...', "customify-sites-library" ); ?>" type="search" aria-describedby="live-search-desc" id="customify-sites-search-input" class="wp-filter-search">
    </form>
    <ul id="customify-sites-filter-tag"  class="filter-links float-right" style="float: right;"></ul>
</div>


<script id="customify-site-item-html" type="text/html">
    <div class="theme" title="{{ data.title }}" tabindex="0" aria-describedby="" data-slug="{{ data.slug }}">
        <div class="theme-screenshot">
            <img src="{{ data.thumbnail_url }}" alt="">
        </div>
        <#  if ( data.pro ) {  #>
        <span class="theme-pro-bubble"><?php _e( 'Pro', "customify-sites-library" ); ?></span>
        <# } #>
        <div class="theme-id-container">
            <h2 class="theme-name" id="{{ data.slug }}-name">{{ data.title }}</h2>
            <div class="theme-actions">
                <a class="cs-open-preview button button-secondary  hide-if-no-customize" data-slug="{{ data.slug }}" href="#"><?php _e( 'Preview', "customify-sites-library" ); ?></a>
                <a class="cs-open-modal button button-primary  hide-if-no-customize" href="#"><?php _e( 'Details', "customify-sites-library" ); ?></a>
            </div>
        </div>
    </div>
</script>


<div id="customify-sites-listing-wrapper" class="theme-browser rendered">
    <div id="customify-sites-listing" class="themes wp-clearfix">
    </div>
</div>
<p  id="customify-sites-no-demos"  class="no-themes"><?php _e( 'No sites found. Try a different search.', "customify-sites-library" ); ?></p>
<span class="spinner"></span>


