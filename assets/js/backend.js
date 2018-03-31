console.log( 'loaded' );

jQuery( document ).ready( function( $ ){


    var Customify_Site = {
        data: {},
        filter_data: {},
        skip_render_filter: false,
        xhr: null,
        getTemplate: _.memoize(function () {

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

        }),
        
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
                $( '#customify-sites-listing' ).append( html );
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
            $( 'body' ).addClass( 'customify-sites-show-modal' );
            $( document ).on( 'click', '#customify-sites-listing .theme', function( e ){
                e.preventDefault();

            } );
        },

        init: function(){
            var that = this;
            that.filter_data = {};
            that.load_sites();
            that.filter();
            that.view_details();
        }
    };


    Customify_Site.init();

} );