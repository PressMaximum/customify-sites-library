console.log( 'loaded' );

jQuery( document ).ready( function( $ ){

    var modal_sites = {};

    var getTemplate = _.memoize(function () {

        var compiled,
            /*
             * Underscore's default ERB-style templates are incompatible with PHP
             * when asp_tags is enabled, so WordPress uses Mustache-inspired templating syntax.
             *
             * @see trac ticket #22344.
             */
            options = {
                evaluate: /<#([\s\S]+?)#>/g,
                interpolate: /\{\{\{([\s\S]+?)\}\}\}/g,
                escape: /\{\{([^\}]+?)\}\}(?!\})/g,
                variable: 'data'
            };

        return function (data, id, data_variable_name ) {
            if (_.isUndefined(id)) {
                id = 'customify-site-item-html';
            }
            if ( ! _.isUndefined( data_variable_name ) && _.isString( data_variable_name ) ) {
                options.variable = data_variable_name;
            } else {
                options.variable = 'data';
            }
            compiled = _.template($('#' + id).html(), null, options);
            return compiled(data);
        };

    });


    var Customify_Modal_Site = function( $item, data ){
        var m = this;
        var steps;
        steps = {
            modal: null,
            item: $item,
            owl: null,
            current_step: 0, // mean first step
            breadcrumb: null,
            getTemplate: getTemplate,
            data: {},
            buttons: {},
            last_step: 4,
            xml_id: 0,
            json_id: 0,
            doing: false,
            add_modal: function () {
                var that = this;
                var template = that.getTemplate();
                that.data = data;
                var html = template( data, 'tpl-cs-item-modal' );
                that.modal = $( html );
                $( '#wpbody-content' ).append( that.modal );
                that.init();
            },
            _reset: function(){
                this.doing = false;
                $( '.cs-breadcrumb', this.modal ).removeClass('cs-hide');
                $( '.cs-action-buttons a, .cs-step', this.modal ).removeClass('loading circle-loading completed cs-hide');
            },
            _open: function(){
                var that = this;
                that.item.on( 'click', '.cs-open-modal', function ( e ) {
                    e.preventDefault();
                    $( 'body' ).addClass( 'customify-sites-show-modal' );
                    that.modal.addClass( 'cs-show' );
                    if ( that.owl ) {
                        that.owl.trigger( 'to.owl.carousel', [ 0 ] );
                    }
                    that._reset();
                } );
            },
            _install_plugins_notice: function (){
                //.cs-install-plugins
                var that = this;
                var installed_plugins   = '';
                var install_plugins     = '';
                var manual_plugins      = '';

                if ( _.isEmpty( that.data.plugins ) ) {
                    that.data.plugins  = {};
                }

                if ( _.isEmpty( that.data.manual_plugins ) ) {
                    that.data.manual_plugins  = {};
                }

                _.each( that.data.plugins, function( plugin_file ){
                    var plugin_name = plugin_file;
                    if ( !_.isUndefined( Customify_Sites.support_plugins[ plugin_file ] ) ) {
                        plugin_name = Customify_Sites.support_plugins[ plugin_file ];
                    }
                    if ( ! _.isUndefined( Customify_Sites.installed_plugins[ plugin_file ] ) ) {
                        installed_plugins += '<li>'+plugin_name+'</li>';
                    } else {
                        install_plugins += '<li>'+plugin_name+'</li>';
                    }
                } );

                _.each( that.data.manual_plugins, function( plugin_file ){
                    var plugin_name = plugin_file;
                    if ( !_.isUndefined( Customify_Sites.support_plugins[ plugin_file ] ) ) {
                        plugin_name = Customify_Sites.support_plugins[ plugin_file ];
                    }
                    if ( _.isUndefined( Customify_Sites.installed_plugins[ plugin_file ] ) ) {
                        manual_plugins += '<li><div class="circle-loader "><div class="checkmark draw"></div></div><span class="cs-plugin-name">'+plugin_name+'</span></li>';
                    } else {
                        installed_plugins += '<li>'+plugin_name+'</li>';
                    }
                } );


                if ( install_plugins !==  '' ) {
                    $( '.cs-install-plugins', that.modal ).show();
                    $( '.cs-install-plugins ul', that.modal ).html( install_plugins );
                } else {
                    $( '.cs-install-plugins', that.modal ).hide();
                }

                if ( installed_plugins !==  '' ) {
                    $( '.cs-installed-plugins', that.modal ).show();
                    $( '.cs-installed-plugins ul', that.modal ).html( installed_plugins );
                } else {
                    $( '.cs-installed-plugins', that.modal ).hide();
                }

                if ( manual_plugins !==  '' ) {
                    $( '.cs-install-manual-plugins', that.modal ).show();
                    $( '.cs-install-manual-plugins ul', that.modal ).html( manual_plugins );
                } else {
                    $( '.cs-install-manual-plugins', that.modal ).hide();
                }

            },
            disable_button: function( button ){
                if ( !_.isUndefined( this.buttons[ button ] ) ) {
                    this.buttons[ button ].addClass( 'disabled' );
                }
            },
            loading_button: function( button ){
                if ( !_.isUndefined( this.buttons[ button ] ) ) {
                    this.buttons[ button ].addClass( 'loading circle-loading' );
                }
            },
            completed_button: function( button ){
                if ( !_.isUndefined( this.buttons[ button ] ) ) {
                    this.buttons[ button ].addClass( 'completed' );
                }
            },
            active_button: function( button ){
                if ( !_.isUndefined( this.buttons[ button ] ) ) {
                    this.buttons[ button ].removeClass( 'disabled' );
                }
            },
            init: function(){
                var that = this;

                that.buttons.skip = $( '.cs-skip', that.modal );
                that.buttons.start = $( '.cs-do-start', that.modal );
                that.buttons.install_plugins = $( '.cs-do-install-plugins', that.modal );
                that.buttons.import_content = $( '.cs-do-import-content', that.modal );
                that.buttons.import_options = $( '.cs-do-import-options', that.modal );
                that.buttons.view_site = $( '.cs-do-view-site', that.modal );

                if ( _.isEmpty( that.data.plugins ) && _.isEmpty( that.data.manual_plugins )  ) {
                    that.last_step = 3;
                    $( '.cs-breadcrumb li[data-step="install_plugins"]', that.modal ).remove();
                    $( '.cs-step-install_plugins', that.modal ).remove();
                    that.buttons.install_plugins.remove();
                }

                that.breadcrumb = $( '.cs-breadcrumb li', that.modal );

                /**
                 * @see https://owlcarousel2.github.io/OwlCarousel2/docs/api-events.html
                 * @type {jQuery}
                 */
                that.owl = $(".owl-carousel", that.modal ).owlCarousel({
                    items: 1,
                    loop: false,
                    mouseDrag: false,
                    touchDrag: false,
                    pullDrag: false,
                    freeDrag: false,
                    rewind: false,
                    autoHeight:true
                });

                that._make_steps_clickable();

                that.owl.on( 'initialize.owl.carousel', function( e, a  ) {
                    that._make_steps_clickable();
                });

                that.owl.on( 'changed.owl.carousel', function( e, a  ) {
                    that.current_step = e.page.index;
                    that._make_steps_clickable();
                    that.doing = false;
                });

                // back to list
                that.modal.on( 'click', '.cs-back-to-list', function( e  ) {
                    e.preventDefault();
                    $( 'body' ).removeClass( 'customify-sites-show-modal' );
                    that.modal.removeClass( 'cs-show' );
                } );

                that.modal.on( 'click', '.cs-skip', function( e  ) {
                    e.preventDefault();
                    if ( ! that.doing ) {
                        that.next_step();
                    }
                } );

                that._breadcrumb_actions();
                that._install_plugins_notice();
                that._setup_plugins();
                that._do_start_import();
                that._installing_plugins();
                that._importing_content();
                that._importing_options();
                that._open();
            },

            next_step: function(){
                this.owl.trigger('next.owl.carousel');
            },

            /**
             * Skip this step and next
             */
            skip: function(){
                this.owl.trigger('next.owl.carousel');
            },

            _make_steps_clickable: function(){
                var that = this;
                that._reset();
                that.breadcrumb.removeClass( 'cs-clickable' );
                for( var i = 0; i <= this.current_step; i++ ) {
                    that.breadcrumb.eq( i ).addClass( 'cs-clickable' );
                }

                if ( that.current_step === that.last_step ) {
                    that.buttons.skip.addClass( 'cs-hide' );
                    $('.cs-breadcrumb', that.modal ).addClass( 'cs-hide' );
                } else {
                    that.buttons.skip.removeClass( 'cs-hide' );
                    $('.cs-breadcrumb', that.modal ).removeClass( 'cs-hide' );
                }

                if ( that.current_step !== 0 && that.current_step !== that.last_step ) {
                    that.buttons.skip.removeClass( 'cs-hide' );
                } else {
                    that.buttons.skip.addClass( 'cs-hide' );
                }

                $( '.cs-action-buttons a', that.modal ).removeClass( 'current' );
                $( '.cs-action-buttons a', that.modal ).eq(that.current_step).addClass( 'current' );

            },

            _breadcrumb_actions: function(){
                var that = this;
                that.breadcrumb.on( 'click', function( e ){
                    e.preventDefault();
                    if ( ! that.doing ) {
                        var index = $(this).index();
                        if ($(this).hasClass('cs-clickable') && index !== that.current_step) {
                            that.current_step = index;
                            that.owl.trigger('to.owl.carousel', [index]);
                        }
                    }
                } );
            },

            _setup_plugins: function(){
                var that = this;
                if ( _.isEmpty( that.data.plugins ) ) {
                    that.data.plugins = {};
                }

                if ( _.isEmpty( that.data.manual_plugins ) ) {
                    that.data.manual_plugins = {};
                }

                _.each( that.data.plugins, function( slug ){
                    var html = '';
                    var name = slug;
                    if ( !_.isUndefined( Customify_Sites.support_plugins[ slug ] ) ) {
                        name = Customify_Sites.support_plugins[ slug ];
                    }
                    if ( ! _.isUndefined( Customify_Sites.activated_plugins[ slug ] ) ) {
                        html = '<li data-slug="'+slug+'" class="is-activated"><div class="circle-loader load-complete"><div class="checkmark draw"></div></div><span class="cs-plugin-name">'+name+'</span></li>';
                    } else if (  _.isUndefined( Customify_Sites.installed_plugins[ slug ] ) ) { // plugin not installed
                        html = '<li data-slug="'+slug+'" class="do-install-n-activate"><div class="circle-loader "><div class="checkmark draw"></div></div><span class="cs-plugin-name">'+name+'</span></li>';
                    } else { // Plugin install but not active
                        html = '<li data-slug="'+slug+'" class="do-activate"><div class="circle-loader"><div class="checkmark draw"></div></div><span class="cs-plugin-name">'+name+'</span></li>';
                    }

                    $( '.cs-installing-plugins', that.modal ).append( html );
                } );


            },

            is_activated: function( $plugin_slug ){
                return _.isUndefined( Customify_Sites.activated_plugins[ $plugin_slug ] ) ? false : true;
            },

            is_installed: function( $plugin_slug ){
                return _.isUndefined( Customify_Sites.installed_plugins[ $plugin_slug ] ) ? false : true;
            },

            step_completed: function( step ){
                var that = this;
                $( '.cs-step.cs-step-'+step, this.modal ).addClass('completed');
                this.completed_button( step );
                setTimeout( function(){
                    that.next_step();
                }, 2000 );
            },

            _do_start_import: function(){
                var that = this;
                // Skip or start import
                that.buttons.start.on( 'click', function( e  ) {
                    e.preventDefault();
                    if ( ! that.doing ) {
                        that.loading_button('start');
                        that.doing = true;
                        $.ajax({
                            url: Customify_Sites.ajax_url,
                            dataType: 'json',
                            data: {
                                action: 'cs_download_files',
                                xml_url: data.xml_url,
                                json_url: data.json_url,
                                site_slug: data.slug
                            },
                            success: function (res) {
                                that.xml_id = res.xml_id;
                                that.json_id = res.json_id;
                                if (that.xml_id <= 0) {
                                    that._reset();
                                    $('.cs-error-download-files', that.modal).removeClass('cs-hide');
                                    that.doing = false;
                                    that.buttons.start.find('.cs-btn-circle-text').text(Customify_Sites.try_again);
                                } else {
                                    _.each(res.texts, function (t, k) {
                                        $('.cs-' + k, that.modal).html(t);
                                    });
                                    that.step_completed('start');
                                }

                            }
                        });
                    } // end if doing
                } );
            },

            _installing_plugins: function() {
                var that = this;
                var n = this.data.plugins.length || 0;
                var n_plugin_installed = 0;
                if( n <= 0 ) {
                    return;
                }

                var ajax_install_plugin = function () {
                    that.doing = true;
                    var plugin_data = that.data.plugins[n_plugin_installed];
                    if ( that.is_activated( plugin_data ) ){ // this plugin already installed
                        n_plugin_installed++;
                        if( n_plugin_installed < n ) {
                            ajax_install_plugin();
                        } else {
                            that.step_completed( 'install_plugins' );
                        }
                    } else if( that.is_installed( plugin_data ) ) {
                        ajax_active_plugin();
                    } else {
                        $( '.cs-installing-plugins li[data-slug="'+plugin_data+'"] .circle-loader', that.modal ).removeClass('load-complete').addClass('circle-loading');
                        $.ajax({
                            url: Customify_Sites.ajax_url,
                            data: {
                                action: 'cs_install_plugin',
                                plugin: plugin_data
                            },
                            success: function (res) {
                                ajax_active_plugin();
                            }
                        });
                    }

                };

                var ajax_active_plugin = function () {
                    that.doing = true;
                    var plugin_data = that.data.plugins[n_plugin_installed];
                    $( '.cs-installing-plugins li[data-slug="'+plugin_data+'"] .circle-loader', that.modal ).removeClass('load-complete').addClass('circle-loading');
                    $.ajax({
                        url: Customify_Sites.ajax_url,
                        data: {
                            action: 'cs_active_plugin',
                            plugin: plugin_data
                        },
                        success: function (res) {
                            n_plugin_installed++;
                            $( '.cs-installing-plugins li[data-slug="'+plugin_data+'"] .circle-loader', that.modal ).removeClass('circle-loading').addClass( 'load-complete' );
                            if( n_plugin_installed < n ) {
                                ajax_install_plugin();
                            } else {
                                that.step_completed( 'install_plugins' );
                            }
                        }
                    });
                };

                $( '.cs-do-install-plugins', that.modal ).on( 'click', function( e ){
                    e.preventDefault();
                    if ( ! that.doing ) {
                        that.doing = true;
                        that.loading_button('install_plugins');
                        ajax_install_plugin();
                    }
                } );

            },

            _importing_content: function(){
                var that = this;

                $( '.cs-do-import-content', that.modal ).on( 'click', function( e ){
                    e.preventDefault();
                    if ( ! that.doing ) {
                        that.doing = true;
                        that.disable_button('import_content');
                        that.loading_button('import_content');
                        $('.cs-import-content-status .circle-loader', that.modal).addClass('circle-loading');
                        $.ajax({
                            url: Customify_Sites.ajax_url,
                            data: {
                                action: 'cs_import_content',
                                id: that.xml_id
                            },
                            success: function (res) {
                                console.log('Imported', res);
                                $('.cs-import-content-status .circle-loader', that.modal).removeClass('circle-loading').addClass('load-complete');
                                that.step_completed('import_content');
                            },
                            error: function (res) {
                                console.log('Imported Error', res);
                                $('.cs-import-content-status .circle-loader', that.modal).removeClass('circle-loading').addClass('load-complete');
                                that.step_completed('import_content');
                            }
                        });
                    } // end if doing
                } );
            },

            _importing_options: function(){
                var that = this;

                $( '.cs-do-import-options', that.modal ).on( 'click', function( e ){
                    e.preventDefault();
                    if ( ! that.doing ) {
                        that.doing = true;
                        that.disable_button('import_options');
                        that.loading_button('import_options');
                        $.ajax({
                            url: Customify_Sites.ajax_url,
                            data: {
                                action: 'cs_import_options',
                                id: that.json_id,
                                xml_id: that.xml_id
                            },
                            success: function (res) {
                                console.log('import_options', res);
                                that.step_completed('import_options');
                            }
                        });
                    }
                } );

            }




        };

        return steps;
    };



    var Customify_Site = {
        data: {},
        filter_data: {},
        skip_render_filter: false,
        xhr: null,
        getTemplate: getTemplate,
        
        load_sites: function ( cb ) {
            var that = this;
            if ( that.xhr ) {
                //kill the request
                that.xhr.abort();
                that.xhr = null;
            }
            $( 'body' ).addClass('loading-content');
            $( '#customify-sites-listing-wrapper' ).hide();
            that.filter_data['_t'] = new Date().getTime();
            that.xhr = $.ajax( {
                url: Customify_Sites.api_url,
                data: that.filter_data,
                type: 'GET',
                success: function( res ){
                    that.data = res;
                    $( '#customify-sites-filter-count' ).text( res.total );
                    that.render_items();
                    if ( ! that.skip_render_filter ) {
                        that.render_categories( );
                        that.render_tags( );
                    }
                    $( 'body' ).removeClass('loading-content');
                    that.view_details();

                    if ( typeof cb === 'function' ) {
                        cb( res );
                    }
                }
            } );
        },

        render_items: function(){
            var that = this;
            var template = that.getTemplate();
            if ( that.data.total <= 0 ) {
                $( '#customify-sites-listing-wrapper' ).hide();
                $( '#customify-sites-no-demos' ).show();
                $( 'body' ).addClass('no-results');
            } else {
                $( '#customify-sites-no-demos' ).hide();
                $( '#customify-sites-listing-wrapper' ).show();
                $( 'body' ).removeClass('no-results');
            }
            $( '#customify-sites-listing .theme' ).remove();

            _.each( that.data.posts, function( item ){
                var html = template( item );
                var $item_html = $( html );
                $( '#customify-sites-listing' ).append( $item_html );
                var s = new Customify_Modal_Site( $item_html, item );
                s.add_modal();
                modal_sites[ item.slug ] = s;

            } );
        },

        render_categories: function(){
            var that = this;
            _.each( that.data.categories, function( item ){
                var html = '<li><a href="#" data-slug="'+item.slug+'">'+item.name+'</a></li>';
                $( '#customify-sites-filter-cat' ).append( html );
            } );
        },

        render_tags: function(){
            var that = this;
            _.each( that.data.tags, function( item ){
                var html = '<li><a href="#" data-slug="'+item.slug+'">'+item.name+'</a></li>';
                $( '#customify-sites-filter-tag' ).append( html );
            } );
        },


        get_filter_data: function(){
            var cat = $( '#customify-sites-filter-cat a.current' ).eq(0).attr( 'data-slug' ) || '';
            var tag = $( '#customify-sites-filter-tag a.current' ).eq(0).attr( 'data-slug' ) || '';
            var s = $( '#customify-sites-search-input' ).val();
            if ( cat === 'all' ) {
                cat = '';
            }
            return {
                cat: cat,
                tag: tag,
                s: s
            }
        },

        filter: function(){
            var that = this;
            $( document ).on( 'click', '#customify-sites-filter-cat a', function( e ){
                e.preventDefault();
                if ( ! $( this ).hasClass( 'current' ) ) {
                    $('#customify-sites-filter-cat a').removeClass('current');
                    $('#customify-sites-filter-tag a').removeClass('current');
                    $(this).addClass('current');
                    that.filter_data = {};
                    that.filter_data = that.get_filter_data();
                    that.skip_render_filter = true;
                    that.load_sites();
                }
            } );

            $( document ).on( 'click', '#customify-sites-filter-tag a', function( e ){
                e.preventDefault();
                if ( ! $( this ).hasClass( 'current' ) ) {
                    $('#customify-sites-filter-tag a').removeClass('current');
                    $(this).addClass('current');
                    that.filter_data = that.get_filter_data();
                    that.skip_render_filter = true;
                    that.load_sites();
                }
            } );

            // Search demo
            $( document ).on( 'change keyup', '#customify-sites-search-input', function(){
                $( '#customify-sites-filter-cat a' ).removeClass( 'current' );
                $( '#customify-sites-filter-tag a' ).removeClass( 'current' );
                that.skip_render_filter = true;
                that.filter_data = that.get_filter_data();
                that.load_sites();

            } );

        },



        view_details: function(){
            // Test
            //$( 'body' ).addClass( 'customify-sites-show-modal' );
            /*
            $( document ).on( 'click', '#customify-sites-listing .theme', function( e ){
                e.preventDefault();

            } );

            $( '.cs-modal' ).each( function(){
                var s = new Customify_Modal_Site( $( this ) );
                s.init();
            } );
            */

        },

        init: function(){
            var that = this;
            that.filter_data = {};
            that.load_sites();
            that.filter();

        }
    };


    Customify_Site.init();

} );