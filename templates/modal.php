<script type="text/html" id="tpl-cs-item-modal">
    <div id="cs-modal-site--{{ data.slug }}" class="cs-modal-site customify-sites-modal-wrapper">
        <div class="cs-modal-outer">
            <a href="#"><?php _e( 'Back to site library' ); ?></a>
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

                            <h3 class="cs-clear">Recommended Plugins</h3>
                            <div class="cs-install-plugins">
                                <p>The following plugins can be installed and activated automatically.</p>
                                <ul>
                                    <li>Plugin name</li>
                                </ul>
                            </div>

                            <div class="cs-installed-plugins">
                                <p>The following plugins are already installed.</p>
                                <ul class="">
                                    <li>Plugin name</li>
                                </ul>
                            </div>

                            <div class="cs-install-manual-plugins">
                                <p>The following plugins need to be installed and activated manually.</p>
                                <ul class="">
                                    <li>Plugin name</li>
                                </ul>
                            </div>


                        </div>
                        <div class="cs-step cs-step-install-plugins">
                            plugins The following plugins can be installed and activated automatically.
                            <p>overview The following plugins can be installed and activated automatically.</p>
                            <p>overview The following plugins can be installed and activated automatically.</p>
                            <p>overview The following plugins can be installed and activated automatically.</p>
                            <p>overview The following plugins can be installed and activated automatically.</p>
                            <p>overview The following plugins can be installed and activated automatically.</p>
                            <p>overview The following plugins can be installed and activated automatically.</p>
                            <p>overview The following plugins can be installed and activated automatically.</p>
                            <p>overview The following plugins can be installed and activated automatically.</p>
                            <p>overview The following plugins can be installed and activated automatically.</p>
                            <p>overview The following plugins can be installed and activated automatically.</p>
                            <p>overview The following plugins can be installed and activated automatically.</p>
                            <p>overview The following plugins can be installed and activated automatically.</p>
                            <p>overview The following plugins can be installed and activated automatically.</p>
                            <p>overview The following plugins can be installed and activated automatically.</p>
                            <p>overview The following plugins can be installed and activated automatically.</p>
                        </div>
                        <div class="cs-step cs-step-import-content">
                            content The following plugins can be installed and activated automatically.
                        </div>
                        <div class="cs-step cs-step-import-options">
                            options The following plugins can be installed and activated automatically.
                        </div>
                    </div>

                    <div class="cs-actions">
                        <a href="#" class="button-secondary"><?php _e( 'Skip' ); ?></a>
                        <a href="#" class="cs-right button-primary"><?php _e( 'Import' ); ?></a>
                    </div>

                </div>

            </div>
        </div>
    </div>
</script>