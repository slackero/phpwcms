phpwcms Changelog
=================


Version 1.6.531 – May 4, 2013
-----------------------------

- CKEDitor 4.1.1
- HTML5 Shiv 3.6.2
- jQuery 2.0.0
- Better handling for file uploads
- Better Rewrite support
- Better HTML5 support, HTML5 is default now
- More options for Sitemap
- Canonical setting
- Changed behavior for config $phpwcms['js_lib']
- Custom Opt-In eail template
- CP Text with Image fiexed rendering left/right column
- Fixes, changes and enhancements


Version 1.6.529 – February 15, 2013
-----------------------------------

- New content part type implemented — used especially for settings, usable via module only at the moment and when `$_module_fe_setting = true`. Disables several CP specific input fields like title, subtitle…
- jQuery 1.9.1
- jQuery Migrate 1.1.0
- Optimized version check
- Some phpwcms related classes now customizable via `$template_default['classes']` in `conf.template_default.inc.php`
- Some functions and replacers deprecated (like `{NAV_TABLE}`, `{NAV_ROW}`…), if needed set `$phpwcms['enable_deprecated'] = false;`
- behavior of `{BREADCRUMB}` changed – linked menu title added as last part of the breadcrumb menu, but only if in article detail mode and parental structure holds more than 1 article
- News enhanced by gallery option
- Load custom defaults in backend when defined for the category
- File actions in file center, quick move, delete, status change, owner change
- Module Content Part type "setting" implemented
- MooTools 1.4.5 and MooTools More updated (MooTools will be deprecated in the future)
- Lot more fixes, cleanup, optimizations


Version 1.6.528 – February 4, 2013
----------------------------------

- Missing ?
- Teaser enhanced by replace {IMAGE_NAME}
- CKEditor enhanced by sample custom config (partially commented out)
- New config setting to force 301 redirect for id/aid to alias
- New config setting to force 301 redirect to structure for topcount -1 and single article
- New option for structure level to disable article 301 redirect (only availabe when enabled in config)
- Extend `[download=ID,ID…]` by template option. No need to add `template=` by default.  
[download=1,2 template=myfiles.tmpl /]  
[download=1,2 template=myfiles.tmpl]  
  Download 1  
  Download 2  
[/download]
- Added count of CPs used for the specific type in the CP selector section of admin’s user profile edit.


Version 1.6.523 – December 4, 2012
----------------------------------

- WYSIWYG editors replaced by CKEditor 4 (only)
- CP Teaser enhanced by IMAGE_ALT/IMAGE_TITLE replacer, rendered also when there is no image
- Updated Polish translations (UTF-8), Thanks Bogdan!
- LofSlider JavaScript/CSS/images removed
- Create new shop category fixed


Version 1.6.522 – November 27, 2012
-----------------------------------

- Add .gitignore to empty folders
- More updated Polish translations (UTF-8), Thanks Bogdan!
- Updated some more file headers with updated copyright info
- Updated version info
- Remove sample MooTools based Tab templates
- Some more cleaning
- Frontend edit link for structure level (admin only) and news
- Implement user groups again (needs intensive tests and `$phpwcms['usergroup_support']=true;`)
- Extend user profile for selected editable content parts
- Admin can define allowed content parts per user. This is not strict, so user still can edit existing non-allowed content parts.
- Backend home lists more information about article and content part.
- Send expired cache header when frontend login is active


Version 1.6.521 – November 23, 2012
-----------------------------------

- Fix FCKeditor for use with Firefox 17+


Version 1.6.520 – November 23, 2012
-----------------------------------

- Fix backend file manager download
- Some more fixes for file management when logged in as admin user
- Updated Polish translations (UTF-8), Thanks Bogdan!
- Updated versions of editors under profile account data
- Removed mediabox from lib
- Updated some more file headers with updated copyright info


Version 1.6.519 – November 22, 2012
-----------------------------------

- News enhanced by new Autocompleter
- News language selection changed
- Some fixes


Version 1.6.518 – November 20, 2012
-----------------------------------

- Issue 345: check file upload size against php.ini values post_max_size and upload_max_filesize
- Cleanup deprecated functions, remove unused files
- CP Image with text useless text wrapper div removed
- News detail default template enhanced by {IMAGE_EXT} too


Version 1.6.517 – November 19, 2012
-----------------------------------

- Some fixes


Version 1.6.516 – November 19, 2012
-----------------------------------

- Remove leading dashes while copy structure name as article title
- Add missing fields to default SQL
- Fix CP Text with image title and subtitle in case there is no image
- CP Code does not replace spaces, tabs and line breaks by HTML equivalent when \<pre\> detected in template
- CP News template enhanced by {ID}
- Several html_entities() replaced by html_specialchars() for better special char support
- Add Confirm message for deleting template


Version 1.6.515 – November 18, 2012
-----------------------------------

- Fix var index ("href" instead of "url")


Version 1.6.514 – November 18, 2012
-----------------------------------

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