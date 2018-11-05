/**
 * This for load book list data on page/post
 *
 */
(function( $, wp ) {
    'use strict';

    /**
     * Markup @'public/partials/book-list-table.php
     */


    window.booksObj = {

        init: function () {
            var book_price = $( '.book-price' );
            var min_price = book_price.attr('min');
            var max_price = book_price.attr('max');
            var _that = this;

            $( "#book-price" ).slider({
                range:true,
                min: 2,
                max: 150,
                values: [ 2, 150 ],
                slide: function( event, ui ) {
                    $('.price-range').text('$'+ui.values[0]+' - $'+ui.values[1]);
                    book_price.attr('data-min',ui.values[0]);
                    book_price.attr('data-max',ui.values[1]);
                }
            });

            $( document ).on('click', '.btn-book-search', function () {
                _that.searchBtn();
            });

            $( document ).on('click', '.book-pagination .page-numbers', function () {
                var page_link = $(this).attr( 'href' );
                _that.paginationBook( page_link );
                return false;
            });
        },

        searchBook: function ( book_page ) {

            var book_name = $( '.book-name' ).val();
            var book_author = $( '.book-author' ).val();
            var book_publisher = $( '.book-publisher' ).val();
            var book_rating = $( '.book-rating' ).val();
            var book_price = $( '.book-price' );
            var book_price_min = book_price.attr('data-min');
            var book_price_max = book_price.attr('data-max');

            var params = {
                'security': '1234',
                'book_title': book_name,
                'author': book_author,
                'publisher': book_publisher,
                'rating': book_rating,
                'price_min': book_price_min,
                'price_max': book_price_max,
                'paged': book_page,
            };

            // Ajax request to create account
            var request = wp.ajax.post( 'search_book', params );

            // Check ajax response
            request.done( function( book_data ) {
                $( '#book-list tbody' ).html( book_data.book_list );
            } );

            request.fail( function( book_data ) {
                alert( 'Something wrong try after some time!' );
            } );
        },

        paginationBook: function ( page_link ) {
            var book_page = page_link.replace("http://", "");
            this.searchBook( book_page );
        },

        searchBtn: function () {
            $('.book-list').addClass('active');
            this.searchBook( 1 );
        }

    };

    $( document ).ready(function() {
        booksObj.init();
    });

})( jQuery, window.wp );
