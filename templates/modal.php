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
                        <li data-step="install_plugins"><?php _e( 'Install Plugins' ); ?></li>
                        <li data-step="import_content"><?php _e( 'Import Content' ); ?></li>
                        <li data-step="import_options"><?php _e( 'Import Options' ); ?></li>
                    </ul>

                    <div class="cs-steps owl-carousel">
                        <div class="cs-step cs-step-start">
                            <div class="cs-step-img">
                                <img src="<?php echo CUSTOMIFY_SITES_URL; ?>/assets/images/welcome.svg">
                                <svg class="icon icon--checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                                    <circle class="icon--checkmark__circle" cx="26" cy="26" r="25" fill="none"></circle><path class="icon--checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"></path>
                                </svg>
                            </div>

                            <div class="cs-text-center">
                               <h3><?php _e( 'Hi! Welcome back' ) ?></h3>
                                <p><?php _e( 'You may have already run this theme setup wizard. If you would like to proceed anyway, click on the "Start" button below.' ); ?></p>
                            </div>

                        </div>
                        <div class="cs-step cs-step-install_plugins">
                            <div class="cs-step-img">
                                <img src="<?php echo CUSTOMIFY_SITES_URL; ?>/assets/images/plugins.svg">
                                <svg class="icon icon--checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                                    <circle class="icon--checkmark__circle" cx="26" cy="26" r="25" fill="none"></circle><path class="icon--checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"></path>
                                </svg>
                            </div>
                            <p class="cs-text-center">
                               <?php _e( 'Let\'s install some essential WordPress plugins to get your site up to speed.', 'customify-sites' ); ?>
                            </p>
                            <ul class="cs-installing-plugins cs-list-plugins"></ul>
                            <# if ( ! _.isEmpty( data.plugins ) || ! _.isEmpty( data.manual_plugins ) ){  #>
                                <div class="cs-install-manual-plugins">
                                    <p class="cs-text-center"><?php _e( 'The following plugins need to be installed and activated manually.', 'customify-sites' ); ?></p>
                                    <ul class="cs-list-plugins"></ul>
                                </div>
                            <# } #>
                        </div>
                        <div class="cs-step cs-step-import_content">
                            <div class="cs-step-img">
                                <img src="<?php echo CUSTOMIFY_SITES_URL; ?>/assets/images/content.svg">
                                <svg class="icon icon--checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                                    <circle class="icon--checkmark__circle" cx="26" cy="26" r="25" fill="none"></circle><path class="icon--checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"></path>
                                </svg>
                            </div>
                            <div>
                                <table class="import-status">
                                    <thead>
                                    <tr>
                                        <th>Import Summary</th>
                                        <th>Completed</th>
                                        <th>Progress</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>
                                            <span class="dashicons dashicons-admin-post"></span>
                                            114 posts (including CPTs)					</td>
                                        <td>
                                            <span id="completed-posts" class="completed">27/114</span>
                                        </td>
                                        <td>
                                            <progress id="progressbar-posts" max="100" value="23.6842"></progress>
                                            <span id="progress-posts" class="progress">24%</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class="dashicons dashicons-admin-media"></span>
                                            74 media items					</td>
                                        <td>
                                            <span id="completed-media" class="completed">74/74</span>
                                        </td>
                                        <td>
                                            <progress id="progressbar-media" max="100" value="100"></progress>
                                            <span id="progress-media" class="progress">100%</span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <span class="dashicons dashicons-admin-users"></span>
                                            1 user					</td>
                                        <td>
                                            <span id="completed-users" class="completed">1/1</span>
                                        </td>
                                        <td>
                                            <progress id="progressbar-users" max="100" value="100"></progress>
                                            <span id="progress-users" class="progress">100%</span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <span class="dashicons dashicons-admin-comments"></span>
                                            136 comments					</td>
                                        <td>
                                            <span id="completed-comments" class="completed">16/136</span>
                                        </td>
                                        <td>
                                            <progress id="progressbar-comments" max="100" value="11.7647"></progress>
                                            <span id="progress-comments" class="progress">12%</span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <span class="dashicons dashicons-category"></span>
                                            39 terms					</td>
                                        <td>
                                            <span id="completed-terms" class="completed">39/39</span>
                                        </td>
                                        <td>
                                            <progress id="progressbar-terms" max="100" value="100"></progress>
                                            <span id="progress-terms" class="progress">100%</span>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="cs-step cs-step-import_options">
                            <div class="cs-step-img">
                                <img src="<?php echo CUSTOMIFY_SITES_URL; ?>/assets/images/options.svg">
                                <svg class="icon icon--checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                                    <circle class="icon--checkmark__circle" cx="26" cy="26" r="25" fill="none"></circle><path class="icon--checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"></path>
                                </svg>
                            </div>
                            <p class="cs-text-center">
                                <?php _e( 'This step will import the theme options, menus and widgets' ); ?>
                            </p>
                            <ul class="cs-text-center">
                                <li><?php _e( 'Customize Options' ); ?></li>
                                <li><?php _e( 'Widgets' ); ?></li>
                                <li><?php _e( 'Options' ); ?></li>
                            </ul>

                        </div>
                        <div class="cs-step cs-step-view_site">
                            <div class="cs-step-img">
                                <img src="<?php echo CUSTOMIFY_SITES_URL; ?>/assets/images/done.svg">
                                <svg class="icon icon--checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                                    <circle class="icon--checkmark__circle" cx="26" cy="26" r="25" fill="none"></circle><path class="icon--checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"></path>
                                </svg>
                            </div>
                            <div class="cs-text-center">
                                <h3><?php _e( 'All done. Have fun!' ); ?></h3>
                                <p><?php _e( 'Your theme has been all set up. Enjoy your new theme by the WordPress team.' ); ?></p>
                                <ul>
                                    <li><a target="_blank" href="https://wordpress.org/support/"><?php _e( 'Explore WordPress' ); ?></a></li>
                                    <li><a target="_blank" href="https://wpcustomify.com"><?php _e( 'Get Theme Support' ); ?></a></li>
                                    <li><a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>"><?php _e( 'Start Customizing' ); ?></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="cs-actions">
                        <a href="#" class="cs-skip cs-hide button-secondary"><?php _e( 'Skip' ); ?></a>
                        <span class="cs-action-buttons">
                            <a href="#" data-step="0" class="cs-right cs-do-start current cs-btn-circle-btn"><span class="cs-btn-circle"></span><span class="cs-btn-circle-text"><?php _e( 'Start Import' ); ?></span></a>
                            <a href="#" data-step="1" class="cs-right cs-do-install-plugins cs-btn-circle-btn"><span class="cs-btn-circle"></span><span class="cs-btn-circle-text"><?php _e( 'Install Plugins' ); ?></span></a>
                            <a href="#" data-step="2" class="cs-right cs-do-import-content cs-btn-circle-btn"><span class="cs-btn-circle"></span><span class="cs-btn-circle-text"><?php _e( 'Import Content' ); ?></span></a>
                            <a href="#" data-step="3" class="cs-right cs-do-import-options cs-btn-circle-btn"><span class="cs-btn-circle"></span><span class="cs-btn-circle-text"><?php _e( 'Import Options' ); ?></span></a>
                            <a href="<?php echo home_url('/'); ?>" data-step="4" target="_blank" class="cs-right cs-do-view-site button-primary"><?php _e( 'View Your Website' ); ?></a>
                        </span>
                    </div>

                </div>

            </div>
        </div>
    </div>
</script>