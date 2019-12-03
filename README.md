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
add_filter( kirki_upload_allowed_fonts_mimes', function( $mimes ) {
    return array_merge( $mimes, array(
        'ttf' => 'application/font-ttf'
    ) );
});
```
