# PHP File Directory Listing Script 

Past versions of this script from the original author can be found here: https://halgatewood.com/free/file-directory-list/

## Changes to the original version

- Removed light mode option -> always dark
- Removed sorting option -> always sorted by name
- Removed icon url option -> now using fontawesome icons (https://fontawesome.com/)
- Removed toggle sub folders option -> always on
- Removed force download option -> on click always redirect to file
- Removed ignore empty folders option -> always false
- Deleted unused css
- Deleted light mode css (no need anymore)
- Added height attributes to some elements to fix the animation bug (animation bug: sildes down more than needed and then jumps back up)

## Options 

At the top of the `index.php` file you have a few settings you can change:

--
`title > "List of Files";`

This will be the title of your page and also is set to the meta mitle of the document.

--
`ignore_file_list > array();`

Create an array of files that you do not want to appear in the listing, for example: array('.htacces','.htpasswd','index.php')

--
`ignore_ext_list > array();`

You can create an array of extensions not to show, for example: array('jpg','png','gif','pdf')
