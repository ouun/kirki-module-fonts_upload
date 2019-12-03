# Kirki Font Uploads
This module allows uploading (woff, otf) fonts via the regular WP Media Upload.
Simply upload the font and it will be available in Typography Fields as Standard Font. 
Additionally the `@font-face` CSS is added to Kirki CSS Module output.

## Requirements
- Kirki Framework >=4.0
- WordPress >= 4.6

## Font Formats
By default the upload of  `.woff` and `.woff2` file is supported. You can use a filter to add other font mimes:

````
add_filter( 'kirki_upload_fonts_allowed_mimes', function( $mimes ) {
    return array_merge( $mimes, array(
        'ttf' => 'application/font-ttf'
    ) );
});
````

## Additional Resources
You can add additional fonts (e.g. from your theme folder) by using the filter `kirki_upload_fonts_available`:

````
add_filter( 'kirki_upload_fonts_available', function( $fonts ) {
    return array_merge( $fonts, array(
        'my_font_name' => array(
            'name' => 'My Font Name,
            'type' => 'woff',
            'url'  => 'http://example.com/my_font.woff',
        }
    ) );
});
````

