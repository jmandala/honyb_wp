/*
 * Copyright © 2012 Mandala Designs LLC. All Rights Reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification, are permitted
 *
 * provided that the following conditions are met:
 *
 * 1. Redistributions of source code must retain the above copyright notice, this list of conditions
 * and the following disclaimer.
 *
 * 2. Redistributions in binary form must reproduce the above copyright notice, this list of conditions
 * and the following disclaimer in the documentation and/or other materials provided with the
 * distribution.
 *
 * 3. The name of the author may not be used to endorse or promote products derived from this software
 * without specific prior written permission.
 * THIS SOFTWARE IS PROVIDED BY MANDALA DESIGNS LLC "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES,
 * INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 * NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * HONYB INTEGRATION LIBRARY
 *
 * Version 0.1.0
 *
 * Provides easy hooks for integrating with the Honyb.com bookstore service.
 */
;
(function ($, window, document, undefined) {

    // undefined is used here as the undefined global variable in ECMAScript 3 is
    // mutable (ie. it can be changed by someone else). undefined isn't really being
    // passed in so we can ensure the value of it is truly undefined. In ES5, undefined
    // can no longer be modified.

    // window and document are passed through as local variables rather than globals
    // as this (slightly) quickens the resolution process and can be more efficiently
    // minified (especially when both are regularly referenced in your plugin).

    // Create the defaults once
    var pluginName = 'honyb',
            defaults = {
                affiliate_key:'honyb',
                width:"90%",
                height:"90%",
                opacity:.5,
                image_size:'small' // small, medium, product, large,
            };

    // The actual plugin constructor
    function Plugin(element, options) {
        this.element = element;
        this.$element = $(element);

        // jQuery has an extend method which merges the contents of two or
        // more objects, storing the result in the first object. The first object
        // is generally empty as we don't want to alter the default options for
        // future instances of the plugin
        this.options = $.extend({}, defaults, options);

        //noinspection JSUnusedGlobalSymbols
        this._defaults = defaults;
        //noinspection JSUnusedGlobalSymbols
        this._name = pluginName;

        this.init(this.options);
    }


    function empty_contents(element) {
        element.html('');
    }

    function build_products(element, options) {
        var skus = (element.data('sku') + '').split(',');
        var view = element.data('view');
        var template = product_brief;

        empty_contents(element);

        if (element.is('DIV')) {
            if (view == 'cover-buy') {
                template = honyb_template(cover_buy);
            } else if (view == 'cover') {
                template = honyb_template(cover);
            } else if (view == 'mosaic') {
                template = honyb_template(cover);
                element.addClass('mosaic');
            } else {
                template = honyb_template(product_brief);
            }
        } else {
            template = inline;
        }

        for (var i = 0; i < skus.length; i++) {
            var sku = $.trim(skus[i]);
            $.getJSON("http://honyb.com/products/" + sku + ".jsonp?callback=?", {},
                    function (data) {
                        var product = data.product;
                        product['image_size'] = options.image_size;
                        product['author'] = get_author(product);
                        product['subtitle'] = get_subtitle(product);
                        product['specs'] = get_specs(product);
                        product['price'] = get_price(product);
                        element.append($.mustache(template, product)).show();
                        $('.price').formatCurrency();

                    });
        }


    }

    function get_price(product) {
        return product['master']['price'];
    }

    function get_author(product) {
        return get_property_value_by_name(product, 'Author Statement');
    }

    function get_subtitle(product) {
        return get_property_value_by_name(product, 'Subtitle');
    }

    function get_specs(product) {
        return get_property_value_by_name(product, 'Product Details');
    }

    function get_property_value_by_name(product, name) {
        var property = get_property_key_by_name(product.properties, name);
        return get_property_value_by_property(product.product_properties, property);
    }

    function get_property_value_by_property(properties, property) {
        for (var i = 0; i < properties.length; i++) {
            if (properties[i].property_id == property.id) {
                return properties[i].value;
            }
        }
        return false;

    }

    function get_property_key_by_name(properties, name) {
        for (var i = 0; i < properties.length; i++) {
            if (properties[i].name == name) {
                return properties[i];
            }
        }
        return false;

    }


    function honyb_template(template) {
        return '<div class="honyb">' +
                template +
                '</div>';

    }

    var inline = '<span class="honyb-popup" data-sku="{{master.sku}}">{{name}}</span>';

    var cover = '<div class="cover">' +
            '<div class="product-image">' +
            '{{#images}}' +
            '<img src="http://www.honyb.com/spree/products/{{id}}/{{image_size}}/{{attachment_file_name}}" class="honyb-popup" data-sku="{{master.sku}}" alt="{{name}}" />' +
            '{{/images}}' +
            '</div>' +
            '</div>';

    var cover_buy = '<div class="cover">' +
            '<div class="product-image">' +
            '{{#images}}' +
            '<img src="http://www.honyb.com/spree/products/{{id}}/{{image_size}}/{{attachment_file_name}}" class="honyb-popup" data-sku="{{master.sku}}" alt="{{name}}" />' +
            '{{/images}}' +
            '</div>' +
            '<div class="honyb-popup button orange" data-sku="{{master.sku}}">buy now</div>' +
            '</div>';

    var product_brief = '<div class="book">' +
            cover_buy +
            '<div class="info">' +
            '<div class="title-and-subtitle">' +
            '<div class="honyb-popup title" data-sku="{{master.sku}}">{{name}}</div>' +
            '{{#subtitle}}<div class="subtitle">{{subtitle}}</div>{{/subtitle}}' +
            '</div>' +
            '{{#author}}<div class="author">{{author}}</div>{{/author}}' +
            '<div class="price-and-shipping"><span class="price">{{price}}</span>, <span class="free-shipping">free shipping</span></div>' +
            '{{#specs}}<div class="specs">{{specs}}</div>{{/specs}}' +
            '<div class="description">{{description}}</div>' +
            '</div><div class="clear"></div></div>';

    var inline_link = '<div class="buy-text">Buy online <span class="honyb-popup inline" data-sku="{{master.sku}}">from the Boston Review Bookstore.</span></div>';

    function activate_popups(options) {
        $('body').on('click', '.honyb-popup', function () {
            var popup = jQuery(this);
            var honyb_proxy = 'http://www.honyb.com/easyxdm/proxy.html';
            var honyb_url = '/embed/h-' + options.affiliate_key +'/products/';
            if (popup.data('sku')) {
                honyb_url = honyb_url + popup.data('sku');
                $(this).colorbox({
                    xdm_iframe:true,
                    xdm_proxy:honyb_proxy,
                    innerWidth:options.width,
                    innerHeight:options.height,
                    href:honyb_url,
                    opacity:options.opacity,
                    overlayClose:false,
                    scrolling:false
                });
                popup.css('cursor', 'pointer');
            }
        });
    }

    /**
     * Builds products where they are referenced
     */
    Plugin.prototype.init = function (options) {
        this.$element.each(function () {
            $(build_products($(this), options));
        });
    };

    /**
     * A really lightweight plugin wrapper around the constructor,
     *
     */
    $.fn[pluginName] = function (options) {

        var count = this.length;

        return this.each(function () {
            var plugin_name = 'plugin_' + pluginName;
            if (!$.data(this, plugin_name)) {
                $.data(this, plugin_name, new Plugin(this, options));
            }

            // last time through, initialize the popus
            if (--count == 0) {
                activate_popups($.data(this, plugin_name).options);
            }
        });
    }

})(jQuery, window, document, undefined);