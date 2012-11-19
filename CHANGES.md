phpwcms Changelog
=======

### Version 1.6.516 – November 19, 2012
- Remove leading dashes while copy structure name as article title
- Add missing fields to default SQL
- Fix CP Text with image title and subtitle in case there is no image
- CP Code does not replace spaces, tabs and line breaks by HTML equivalent when \<pre\> detected in template
- CP News template enhanced by {ID}
- Several html_entities() replaced by html_specialchars() for better special char support
- Add Confirm message for deleting template

### Version 1.6.515 – November 18, 2012
- Fix var index ("href" instead of "url")

### Version 1.6.514 – November 18, 2012
- Issue 342: CP Plain Text listing encode HTML special chars
- Issue 343: click on label "article title" will set article title to the selected category
- Unused and/or deprecated functions, libraries, files removed
- Resized image cache mechanism removed
- Issue 144: CP Image with Text without tables and additional template settings
- File Manager: several optimizations, some fixes
- Issue 190: users with admin permissions can edit all files/directories
- File Manager: click on file starts edit mode instead opening in new window
- File Manager: select menu to switch where to place the directory
- File Manager: larger preview images in edit mode
- File Manager: FancyUpload replaced by Fine Uploader (drop capable)
- File Manager: multiple file upload enhanced by option to delete files
- Issue 54: File browser enhanced by upload option
- News template enhanced by {IMAGE_EXT}
- Implemented simple backend search linking against backend home
- Print to PDF requires installed wkhtmltopdf (experimental), new setting $phpwcms['wkhtmltopdf_path']
- New config $phpwcms['render_clean_html'] to enable some HTML source cleanup (remove comments, white spaces)
- New config $phpwcms['browser_check'] to enable Browser Update check
- CKEditor updated to v3.6.5
- jQuery updated to v1.8.3