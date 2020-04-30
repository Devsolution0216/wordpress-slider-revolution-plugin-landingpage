/*!
 * jquery.scrollto.js 0.0.1 - https://github.com/yckart/jquery.scrollto.js
 * Scroll smooth to any element in your DOM.
 *
 * Copyright (c) 2012 Yannick Albert (http://yckart.com)
 * Licensed under the MIT license (http://www.opensource.org/licenses/mit-license.php).
 * 2013/02/17
 **/
jQuery.scrollTo = jQuery.fn.scrollTo = function(x, y, options){
    if (!(this instanceof $)) return jQuery.fn.scrollTo.apply($('html, body'), arguments);

    options = jQuery.extend({}, {
        gap: {
            x: 0,
            y: 0
        },
        animation: {
            easing: 'easeInSine',
            duration: 'slow',
            complete: $.noop,
            step: $.noop
        }
    }, options);

    return this.each(function(){
        var elem = jQuery(this);
        elem.stop().animate({
            scrollLeft: !isNaN(Number(x)) ? x : jQuery(y).offset().left + options.gap.x,
            scrollTop: !isNaN(Number(y)) ? y : jQuery(y).offset().top + options.gap.y - 69 // *edited
        }, options.animation);
    });
};