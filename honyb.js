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

// get the current path
function honyb_root() {
    var this_script = 'honyb.js';
    var root = '';

    jQuery('script').each(function() {
        var src = this.src;
        if (src.match(this_script)) {
            root = src.replace(this_script, '');
            root = root.replace(/\?.*/, '');
        }
    });

    if (root == '') {
        return '.';
    }

    return root;
}

var honyb_root_path = honyb_root();

// add the css
jQuery('head').append('<link rel="stylesheet" type="text/css" href="' + honyb_root_path + 'honyb.css"/>');
jQuery('head').append('<link rel="stylesheet" type="text/css" href="' + honyb_root_path + 'colorbox/honyb/colorbox.css" />');
jQuery('head').append('<script type="text/javascript" language="javascript" src="' + honyb_root_path + 'easyxdm/easyXDM.debug.js"></script>');
jQuery('head').append('<script type="text/javascript" language="javascript" src="' + honyb_root_path + 'honybox/script/jquery.honybox.js"></script>');
jQuery('head').append('<script type="text/javascript" language="javascript" src="' + honyb_root_path + 'jquery.mustache.js"></script>');
jQuery('head').append('<script type="text/javascript" language="javascript" src="' + honyb_root_path + 'jquery.formatCurrency-1.4.0.pack.js"></script>');
jQuery('head').append('<script type="text/javascript" language="javascript" src="' + honyb_root_path + 'jquery.honyb.js"></script>');
