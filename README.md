# Free Super Clean PHP File Directory Listing Script

Easily display files and folders in a mobile friendly, clean and cool way. Just drop the `index.php` in your folder and you are ready to go. Past versions of this script can be found here: https://halgatewood.com/free/file-directory-list/

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
