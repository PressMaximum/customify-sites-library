<script type="text/html" id="tpl-cs-item-modal">
    <div id="cs-modal-site--{{ data.slug }}" class="cs-modal-site customify-sites-modal-wrapper">
        <div class="cs-modal-outer">
            <a href="#" class="cs-back-to-list button-secondary"><?php _e( 'Back to site library' ); ?></a>
            <div class="cs-modal">
                <div class="cs-info">
                    <div class="cs-thumbnail">
                        <img src="{{ data.thumbnail_url }}" alt="">
                    </div>
                    <div class="cs-name">{{ data.title }}</div>
                    <div class="cs-desc">{{{ data.desc }}}</div>
                </div>

                <div class="cs-main">
                    <ul class="cs-breadcrumb">
                        <li data-step="overview" class="current"><?php _e( 'Import Overview' ); ?></li>
                        <li data-step="install-plugins"><?php _e( 'Install Plugins' ); ?></li>
                        <li data-step="import-content"><?php _e( 'Import Content' ); ?></li>
                        <li data-step="import-options"><?php _e( 'Import Options' ); ?></li>
                    </ul>

                    <div class="cs-steps owl-carousel">
                        <div class="cs-step cs-step-overview">
                            <div class="cs-content-summary cs-50 cs-left">
                                <h3>Content Summary</h3>
                                <p><span class="dashicons dashicons-admin-post"></span> 114 posts (including CPTs)</p>
                                <p><span class="dashicons dashicons-admin-media"></span> 74 media items</p>
                                <p><span class="dashicons dashicons-admin-users"></span> 1 User</p>
                                <p><span class="dashicons dashicons-admin-comments"></span> 123 comments</p>
                                <p><span class="dashicons dashicons-category"></span> 36 Terms</p>
                            </div>
                            <div class="cs-option-summary cs-50 cs-right">
                                <h3>Options Summary</h3>
                                <p>Widgets</p>
                                <p>Menus</p>
                                <p>Customize Options</p>
                            </div>

                            <# if ( ! _.isEmpty( data.plugins ) || ! _.isEmpty( data.manual_plugins ) ){  #>
                            <h3 class="cs-clear"><?php _e( 'Recommended Plugins', 'customify-sites' ); ?></h3>
                            <div class="cs-installed-plugins">
                                <p><?php _e( 'The following plugins are already installed.', 'customify-sites' ); ?></p>
                                <ul></ul>
                            </div>
                            <div class="cs-install-plugins">
                                <p><?php _e( 'The following plugins can be installed and activated automatically.', 'customify-sites' ); ?></p>
                                <ul></ul>
                            </div>
                            <div class="cs-install-manual-plugins">
                                <p><?php _e( 'The following plugins need to be installed and activated manually.', 'customify-sites' ); ?></p>
                                <ul></ul>
                            </div>
                            <# } #>

                        </div>
                        <div class="cs-step cs-step-install-plugins">
                            <div class="cs-step-img">
                                <img src="<?php echo CUSTOMIFY_SITES_URL; ?>/assets/images/plugins.svg">
                            </div>
                            <ul class="cs-installing-plugins"></ul>
                        </div>
                        <div class="cs-step cs-step-import-content">
                            <div class="cs-step-img">
                                <img src="<?php echo CUSTOMIFY_SITES_URL; ?>/assets/images/content.svg">
                            </div>
                            content The following plugins can be installed and activated automatically.
                        </div>
                        <div class="cs-step cs-step-import-options">
                            <div class="cs-step-img">
                                <img src="<?php echo CUSTOMIFY_SITES_URL; ?>/assets/images/done.svg">
                            </div>
                            options The following plugins can be installed and activated automatically.
                        </div>
                    </div>

                    <div class="cs-actions">
                        <a href="#" class="cs-skip cs-hide  button-secondary"><?php _e( 'Skip' ); ?></a>
                        <span class="cs-action-buttons">
                            <a href="#" data-step="0" class="cs-right cs-do-start-import current button-primary"><?php _e( 'Start Import' ); ?></a>
                            <a href="#" data-step="1" class="cs-right cs-do-install-plugins button-primary"><?php _e( 'Install Plugins' ); ?></a>
                            <a href="#" data-step="2" class="cs-right cs-do-import-content button-primary"><?php _e( 'Import Content' ); ?></a>
                            <a href="#" data-step="3" class="cs-right cs-do-import-options button-primary"><?php _e( 'Import Options' ); ?></a>
                        </span>
                    </div>

                </div>

            </div>
        </div>
    </div>
</script>