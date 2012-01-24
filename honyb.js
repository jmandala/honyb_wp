jQuery(document).ready(function() {
    jQuery('.honyb-popup').each(function () {
        var popup = jQuery(this);
        var honyb_url = 'http://www.honyb.com/embed/products/';
        if (popup.data('sku')) {
            honyb_url =  honyb_url + popup.data('sku');
            jQuery(this).colorbox({iframe:true, width:'80%', height:'80%', href:honyb_url});
        }
});
});



