<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Upload an Image</title>
	<style>
	.error {
		font-weight: bold;
		color: #C00;
	}
	</style>
</head>
<body>
<?php 

/* Ensure the following prior to use
PHP is on the correct settings. Using XAAMP/web host should alleviate these issues
Temporary & final storage directories must exist with the correct permissions:

Settings in phpinfo():
file_uploads: On
upload_max_size: adjust as you need, default could vary depending on version (M short for megabytes)
post_max_size: adjsut as necessary, default could vary depending on version
upload_tmp_dir: check to see if it has value (may be issue for windows)

If seeting need to be changed, look for the php.ini file whicch can be easily found in the phpinfo() page under (Loaded) Configuration File (only works if you have permissions to amend)
Look for these lines:
file_uploads = On
;upload_tmp_dir =    (you'll need to create a temporary directive named C:\tmp and make sure the line is NOT preceeded with a smeicolon, save changes, restart PHP, confirm changes)
upload_max_filesize =  2M

Then make sure you add a tmp folder within C:\ and that everyone can read/write to that folder and permissions enable (within the folder's Properties -> Security) 

Then create the permanent directory ('uploads') outside the web directory
               
project folder  - uploads (folder)
                - htdocs(folder) - js (folder)
                                 - .php files
                                 - includes (folder)

PHP file upload configurations explanations
Setting                 Value Type      Meaning
file_uploads            Boolean         Enables PHP file upload support
max_input_time          Integer         How long the PHP code is allowed to run
post_max_size           Integer         Total allowed POST data (bytes)
upload_max_filesize     Integer         Largest possible file upload (bytes)
upload_tmp_dir          String          Where files should be temporarily stored

Once file receive3d fromPHP, moveu_upload_file() can be used to move it from tmp to permanent location

*/

// To check PHP settings, comment/ comment out as needed
// phpinfo();

// Check form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	// Check for upload
	if (isset($_FILES['upload'])) {

		// Validate MIME type (JPEG/PNG & variants)
		$allowed = ['image/pjpeg', 'image/jpeg', 'image/JPG', 'image/X-PNG', 'image/PNG', 'image/png', 'image/x-png'];
		if (in_array($_FILES['upload']['type'], $allowed)) {

			// Move file over form tmp to permanent location, file retains original name (Generally best to rename them for security, but requires DB to track original & new names)
			if (move_uploaded_file ($_FILES['upload']['tmp_name'], "../uploads/{$_FILES['upload']['name']}")) {
				echo '<p><em>The file has been uploaded!</em></p>';
			} 

		} else { // Invalid type
			echo '<p class="error">Invalid image type. Please upload a JPEG or PNG image.</p>';
		}
	} // end if

	// Check for error
	if ($_FILES['upload']['error'] > 0) {
		echo '<p class="error">The file could not be uploaded because: <strong>';

		// Print error based on type
		switch ($_FILES['upload']['error']) {
			case 1:
				print 'The file exceeds upload_max_filesize setting in php.ini.';
				break;
			case 2:
				print 'The file exceeds MAX_FILE_SIZE setting in HTML form.';
				break;
			case 3:
				print 'File was only partially uploaded.';
				break;
			case 4:
				print 'No file uploaded.';
				break;
			case 6:
				print 'Temporary folder unavailable.';
				break;
			case 7:
				print 'Unable to write to disk.';
				break;
			case 8:
				print 'File upload stopped.';
				break;
				
			// Default error message if unable to decipher/future support
			default:
				print 'A system error occurred.';
				break;
		} // End switch
		print '</strong></p>';
	} // End if

	// Delete file if it still exists in tmp folder in server, Unlink function will delete it
	// conditional checks if the file exists and confirms that it is the intended file 
	if (file_exists ($_FILES['upload']['tmp_name']) && is_file($_FILES['upload']['tmp_name']) ) {
		unlink ($_FILES['upload']['tmp_name']);
	}
} 

/*
Existence up upload can be verified with is_uploaded_file() function.
Use / OR \\ to refer to directories (C:\\ or C:/).
move_upload_file() will overwite existing file without warning if new/exisiting file have the same name
MAX_FILE_SIZE in HTML form is a restriction on upload size. Not all browsers abide and PHP has its own rerstrictions
*/
?>

<!-- HTML form, must use POST method  
enctype - attribute that enables form to hanlde different type of data including files (will fail without it)

Uploaded files can be accessed via $_FILES superglobal, its an array with the following indexes:

Index		Meaning
name		orignal name (as was in user's compputer)
type		MIME type of file, provided form browser
size		size fo file (bytes)
tmp_name	temporary filename as stored in server
error		error code(s) associated with any issues

-->
<form enctype="multipart/form-data" action="upload_image.php" method="post">
	<input type="hidden" name="MAX_FILE_SIZE" value="524288">
		<fieldset><legend>Select a JPEG or PNG image of 512KB or smaller to be uploaded:</legend>
			<p><strong>File:</strong> <input type="file" name="upload"></p>
		</fieldset>
	<div align="center"><input type="submit" name="submit" value="Submit"></div>
</form>
</body>
</html>
