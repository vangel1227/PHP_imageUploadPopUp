<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Images</title>
    <!-- Includes Javascript -->
	<script charset="utf-8" src="js/function.js"></script>
</head>
<body>
<p>Click on an image to view it in a separate window.</p>
<ul>
<?php // Script lists images in uploads directory, shows file size and uploaded date & time

/*
getimagesize() array
Element     Value                   Exmaple
0           width(px)               234
1           height(px)              456
2           image type              2(JPG)
3           HTML img tag data       height="456" width="234"
mime        MIME type               image/png
*/

// Set default timezone, to change check PHP manual for accepted string arguements
date_default_timezone_set('America/New_York');

$dir = '../uploads'; // Define directory as variabele

// Read all images into array using scandir() function and stores into $files array
$files = scandir($dir); 

// Display each image caption as a link
foreach ($files as $image) {

    // On windows, hidden files and current directory referred to with single period
    // Parent directories reffered to with double periods
    // Conditoional weeds them out
	if (substr($image, 0, 1) != '.') { 

        // Get image's size (px) returns array for height & width
        // getsimagesize() one of PHP's many functions
		$image_size = getimagesize("$dir/$image");

		// Calculate image's size (kb)
		$file_size = round( (filesize("$dir/$image")) / 1024) . "kb";

		// Determine upload date & time, change the format as needed using the various PHP arguments available
		$image_date = date("F d, Y H:i:s", filemtime("$dir/$image"));

        // Make image's name URL-safe in case is has unfirendly characters
        // urlencode() function makes string safe to pass as URL
		$image_name = urlencode($image);

        // Print info and image link
        // Calls function.js create_window function
        // To execute JS function form HTML, must preface with "javacsript"
        echo "<li><a href=\"javascript:create_window('$image_name',$image_size[0],$image_size[1])\">$image</a> $file_size ($image_date)</li>\n";
        
        // View source coide to see dynamically generated links
        // Different broswers handle sizing differently, can use CSS/JS to adjust and account for that
        // Some versions of Windows create a Thumbs.db file in images folder. You can weed it out using the following:
        // if ( (substr($image, 0, 1) != '. ') && ($image != 'Thumbs.db')){ }
	}
} 
?>
</ul>
</body>
</html>