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
            add_modal: function () {
                var that = this;
                var template = that.getTemplate();
                that.data = data;
                var html = template( data, 'tpl-cs-item-modal' );
                that.modal = $( html );
                $( '#wpbody-content' ).append( that.modal );
                that.init();

            },
            _open: function(){
                var that = this;
                that.item.on( 'click', '.cs-open-modal', function ( e ) {
                    e.preventDefault();
                    $( 'body' ).addClass( 'customify-sites-show-modal' );
                    that.modal.addClass( 'cs-show' );
                } );
            },
            _install_plugins_notice: function (){
                //.cs-install-plugins
                var that = this;
                console.log( 'that.data.plugins', that.data.plugins );

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
                    if ( ! _.isUndefined( Customify_Sites.activated_plugins[ plugin_file ] ) ) {
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
                    if ( _.isUndefined( Customify_Sites.activated_plugins[ plugin_file ] ) ) {
                        manual_plugins += '<li>'+plugin_name+'</li>';
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
                    $( '.cs-install-manual-plugins ul', that.modal ).html( installed_plugins );
                } else {
                    $( '.cs-install-manual-plugins', that.modal ).hide();
                }






            },
            init: function(){
                var that = this;

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

                that.owl.on( 'changed.owl.carousel', function( e, a  ) {
                    that.current_step = e.page.index;
                    that._make_steps_clickable();
                });

                that.modal.on( 'click', '.button-secondary', function( e  ) {
                    e.preventDefault();
                    that.skip();
                } );
                that._breadcrumb_actions();
                that._install_plugins_notice();
                that._open();
            },

            /**
             * Skip this step and next
             */
            skip: function(){
                this.owl.trigger('next.owl.carousel');
            },

            _make_steps_clickable: function(){
                var that = this;
                that.breadcrumb.removeClass( 'cs-clickable' );
                for( var i = 0; i <= this.current_step; i++ ) {
                    that.breadcrumb.eq( i ).addClass( 'cs-clickable' );
                }

            },

            _breadcrumb_actions: function(){
                var that = this;
                that.breadcrumb.on( 'click', function( e ){
                    e.preventDefault();
                    var index = $( this ).index();
                    if ( $( this ).hasClass( 'cs-clickable' ) && index !== that.current_step ) {

                        that.current_step = index;
                        that.owl.trigger( 'to.owl.carousel', [ index ] );
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