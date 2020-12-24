<?php 
/* 
Displays image on JS-generated window
JS is needed to generate the pop-up window of the appropraite size
images are inaccesible wihtout this since they're store outside the web directory using the presented file structure
Can't use either of the following:
http://wwww.example.com/uploads/image.jpg (not a real link)
<img src="img.png">
This allows you to safeguard content
You need this proxy script to display the image, providing the file only when requested
*/

$name = FALSE; // Flag variable, indiciates all validation passed prior to sending data to broswer

// Check for image name in URL
if (isset($_GET['image'])) {

	// Ensure it has an extension
	$ext = strtolower ( substr ($_GET['image'], -4));

	if (($ext == '.jpg') OR ($ext == 'jpeg') OR ($ext == '.png')) {

		// Full image path
		$image = "../uploads/{$_GET['image']}";

		// Ensure image exists & is a file
		if (file_exists($image) && (is_file($image))) {

			// Set name as this image
			$name = $_GET['image'];

		} // End file_exists() IF

	} // End $ext IF

} // End isset($_GET['image']) IF

/* If there was a problem, use default image
 No default image set but you can set on up by creating and images folders and ensuring the variables and image information match up
 The default image CANNOT be in the upload directory, must be atleast within the same web directory as this script
*/

 if (!$name) {
	$image = 'images/unavailable.png';
	$name = 'unavailable.png';
}

/* 
Retrieve image information
 getimagesize() can be used to get filetype, height/width text string
 Because $ image represents both available/unavailable images, it'll work on both
 */

$info = getimagesize($image); // Get filetype 
$fs = filesize($image); // Filesize (bytes)

/* Send the content information
 Header() functions used to redirect browser, must be called before sending anything to the browser (includes HTML, echo, print, blanks space outside of PHP)
 First is content-type, indicates what type of data is to follow
 Second is content-dispoition, how to treat data. Inline has browser display the data
 Third is content-length, amount of data to be sent (bytes)
 When using mulitple header functions, ensure they each end with a new line (\n)
 */

header ("Content-Type: {$info['mime']}\n");
header ("Content-Disposition: inline; filename=\"$name\"\n");
header ("Content-Length: $fs\n");

/* File itself is sent using the readfile() function
 This function reads the fie and send the content to the browser
 No closing PHP tag, acceptable since all coding is in PHP and preferred since having an extra space or content could result in a error
 Even an included file that has a blank line after the PHP while make the header() function unusable
 A possible solution is to call a header_set() function first to ensure their being sent to browser:

 if (!headers_sent()) {
     User header() function
 } else {
     Do something else
 }

 Header_sent() function returns a boolean indicating whether something has already been sent to the browser.
 */
readfile($image);
