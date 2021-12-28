<?php
/* 

Free PHP File Directory Listing Script - Version 1.10

The MIT License (MIT)

Copyright (c) 2015 Hal Gatewood

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.


*** OPTIONS ***/

$settings = array(
    "title" => "Dev Site | playTerra.de",
    "ignore_file_list" => array(".htaccess", ".htpasswd", "index.php"),
    "ignore_ext_list" => array()
);
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $title; ?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,height=device-height,initial-scale=1.0,maximum-scale=1.0, viewport-fit=cover">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
	<link href="//fonts.googleapis.com/css?family=Lato:400,900" rel="stylesheet" type="text/css" />
	<style>
		*,
		*:before,
		*:after {
		    -moz-box-sizing: border-box;
		    -webkit-box-sizing: border-box;
		    box-sizing: border-box;
		}

		body {
		    background: #1d1c1c;
		    font-family: "Lato", "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
		    font-weight: 400;
		    font-size: 14px;
		    line-height: 18px;
		    padding: 0;
		    margin: 0;
		    text-align: center;
		    color: #fff;
		}

		.wrap {
		    max-width: 100%;
		    width: 500px;
		    margin: 20px auto;
		    background: #2b2a2a;
		    padding: 40px;
		    border-radius: 3px;
		    text-align: left;
		}

		@media only screen and (max-width: 700px) {
		    .wrap {
			padding: 15px;
		    }
		}

		h1 {
		    text-align: center;
		    margin: 40px 0;
		    font-size: 22px;
		    font-weight: bold;
		    color: #fff;
		}

		a {
		    color: #399ae5;
		    text-decoration: none;
		}

		a:hover {
		    color: #206ba4;
		    text-decoration: none;
		}

		.note {
		    padding: 0 5px 25px 0;
		    font-size: 80%;
		    line-height: 18px;
		    color: #fff;
		}

		.block {
		    clear: both;
		    min-height: 50px;
		    border-top: solid 1px #666;
				height: 95px;
		}

		.block:first-child {
		    border: none;
		}

		.block .icon {
				width: 50px;
				height: 55px;
				display: block;
				float: left;
				margin-right: 10px;
				font-size: 40px;
				line-height: 55px;
				text-align: center;
			}

		.block .file {
		    padding-bottom: 5px;
		}

		body .block .data {
		    line-height: 1.3em;
		    color: #fff;
		}

		.block a {
		    display: block;
		    padding: 20px;
		    transition: all 0.35s;
				height: 95px
		}

		body .block a:hover,
		body .block a.open {
		    text-decoration: none;
		    background: rgba(255, 255, 255, .05);
		}

		.bold {
		    font-weight: 900;
		}

		.upper {
		    text-transform: uppercase;
		}

		.fs-1-2 {
		    font-size: 1.2em;
		}

		.fs-0-8 {
		    font-size: 0.8em;
		}

		.fs-0-7 {
		    font-size: 0.7em;
		}

		.sub {
		    margin-left: 20px;
		    display: none;
		    border-left: solid 5px #666;
		}
	</style>
</head>
<body class="<?php echo $color; ?>">
<h1><?php echo $title ?></h1>
<div class="wrap">
<?php

// FUNCTIONS TO MAKE THE MAGIC HAPPEN, BEST TO LEAVE THESE ALONE
function clean_title($title)
{
	return ucwords( str_replace( array("-", "_"), " ", $title) );
}

function ext($filename) 
{
	return substr( strrchr( $filename,'.' ),1 );
}

function display_size($bytes, $precision = 2) 
{
	$units = array('B', 'KB', 'MB', 'GB', 'TB');
    $bytes = max($bytes, 0); 
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
    $pow = min($pow, count($units) - 1); 
    $bytes /= (1 << (10 * $pow)); 
	return round($bytes, $precision) . '<span class="fs-0-8 bold">' . $units[$pow] . "</span>";
}

function count_dir_files( $dir)
{
	$fi = new FilesystemIterator(__DIR__ . "/" . $dir, FilesystemIterator::SKIP_DOTS);
	return iterator_count($fi);
}

function get_directory_size($path)
{
    $bytestotal = 0;
    $path = realpath($path);
    if($path!==false && $path!='' && file_exists($path))
    {
        foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)) as $object)
        {
            $bytestotal += $object->getSize();
        }
    }
    
    return display_size($bytestotal);
}


// SHOW THE MEDIA BLOCK
function display_block( $file )
{
	global $ignore_file_list, $ignore_ext_list, $force_download;
	
	$file_ext = ext($file);
	if( !$file_ext AND is_dir($file)) $file_ext = "dir";
	if(in_array($file, $ignore_file_list)) return;
	if(in_array($file_ext, $ignore_ext_list)) return;
	
	$download_att = ($force_download AND $file_ext != "dir" ) ? " download='" . basename($file) . "'" : "";
	
	$rtn = "<div class=\"block\">";
	$rtn .= "<a href=\"$file\" class=\"$file_ext\"{$download_att}>";
	$rtn .= "	<div class=\"img $file_ext\"></div>";
	$rtn .= "	<div class=\"name\">";
	
	if ($file_ext === "dir") 
	{
		$rtn .= "		<div class=\"file fs-1-2 bold\">" . basename($file) . "</div>";
		$rtn .= "		<div class=\"data upper size fs-0-7\"><span class=\"bold\">" . count_dir_files($file) . "</span> files</div>";
		$rtn .= "		<div class=\"data upper size fs-0-7\"><span class=\"bold\">Size:</span> " . get_directory_size($file) . "</div>";
		
	}
	else
	{
		$rtn .= "		<div class=\"file fs-1-2 bold\">" . basename($file) . "</div>";
		$rtn .= "		<div class=\"data upper size fs-0-7\"><span class=\"bold\">Size:</span> " . display_size(filesize($file)) . "</div>";
		$rtn .= "		<div class=\"data upper modified fs-0-7\"><span class=\"bold\">Last modified:</span> " .  date("D. F jS, Y - h:ia", filemtime($file)) . "</div>";	
	}

	$rtn .= "	</div>";
	$rtn .= "	</a>";
	$rtn .= "</div>";
	return $rtn;
}


// RECURSIVE FUNCTION TO BUILD THE BLOCKS
function build_blocks( $items, $folder )
{
	global $ignore_file_list, $ignore_ext_list, $sort_by, $toggle_sub_folders, $ignore_empty_folders;
	
	$objects = array();
	$objects['directories'] = array();
	$objects['files'] = array();
	
	foreach($items as $c => $item)
	{
		if( $item == ".." OR $item == ".") continue;
	
		// IGNORE FILE
		if(in_array($item, $ignore_file_list)) { continue; }
	
		if( $folder && $item )
		{
			$item = "$folder/$item";
		}

		$file_ext = ext($item);
		
		// IGNORE EXT
		if(in_array($file_ext, $ignore_ext_list)) { continue; }
		
		// DIRECTORIES
		if( is_dir($item) ) 
		{
			$objects['directories'][] = $item; 
			continue;
		}
		
		// FILE DATE
		$file_time = date("U", filemtime($item));
		
		// FILES
		if( $item )
		{
			$objects['files'][$file_time . "-" . $item] = $item;
		}
	}
	
	foreach($objects['directories'] as $c => $file)
	{
		$sub_items = (array) scandir( $file );
		
		if( $ignore_empty_folders )
		{
			$has_sub_items = false;
			foreach( $sub_items as $sub_item )
			{
				$sub_fileExt = ext( $sub_item );
				if( $sub_item == ".." OR $sub_item == ".") continue;
				if(in_array($sub_item, $ignore_file_list)) continue;
				if(in_array($sub_fileExt, $ignore_ext_list)) continue;
			
				$has_sub_items = true;
				break;	
			}
			
			if( $has_sub_items ) echo display_block( $file );
		}
		else
		{
			echo display_block( $file );
		}
		
		if( $toggle_sub_folders )
		{
			if( $sub_items )
			{
				echo "<div class='sub' data-folder=\"$file\">";
				build_blocks( $sub_items, $file );
				echo "</div>";
			}
		}
	}
	
	// SORT BEFORE LOOP
	if( $sort_by == "date_asc" ) { ksort($objects['files']); }
	elseif( $sort_by == "date_desc" ) { krsort($objects['files']); }
	elseif( $sort_by == "name_asc" ) { natsort($objects['files']); }
	elseif( $sort_by == "name_desc" ) { arsort($objects['files']); }
	
	foreach($objects['files'] as $t => $file)
	{
		$fileExt = ext($file);
		if(in_array($file, $ignore_file_list)) { continue; }
		if(in_array($fileExt, $ignore_ext_list)) { continue; }
		echo display_block( $file );
	}
}

// GET THE BLOCKS STARTED, FALSE TO INDICATE MAIN FOLDER
$items = scandir( dirname(__FILE__) );
build_blocks( $items, false );
?>

<?php if($toggle_sub_folders) { ?>
<script type="text/javascript">
	$(document).ready(function() 
	{
		$("a.dir").click(function(e)
		{
			$(this).toggleClass('open');
		 	$('.sub[data-folder="' + $(this).attr('href') + '"]').slideToggle();
			e.preventDefault();
		});
	});
</script>
<?php } ?>
</div>
<div style="padding: 10px 10px 40px 10px;"><a href="https://halgatewood.com/free/file-directory-list/">Free PHP File Directory Script</a> (<a href="https://github.com/halgatewood/file-directory-list/">GitHub</a>)</div>
</body>
</html>
