honyb_wp
========

Provides easy honyb integration and shortcodes for referencing products.

Install
=======

After you activate the plugin, go to Settings -> Honyb Settings to enter your affiliate key.

Usage
=====

Display a single product with cover, and description.

    [honyb sku="9780307887894"]

Display a single product, cover only.

    [honyb sku="9780307887894" view="cover"]

All View options:
- product-brief: [default] displays the cover, buy button, title, author, specs and description.
- cover: displays the cover only.
- cover-buy: displays the cover and buy button.
- mosaic: displays covers all floated left into a mosaic grid.

Float will wrap left or right.

    [honyb sku="" float="left|right"]

Enter multiple SKUs to display a product list.

    [honyb sku="9780307887894, 9780307269935"]