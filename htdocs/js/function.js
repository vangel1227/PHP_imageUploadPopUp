// JavaScript that works with PHP to present Make a pop-up window function:
function create_window(image, width, height) {

	// Add pixels to width & height
	width = width + 20;
	height = height + 20;

	// If window already open, resize to new dimensions
	if (window.popup && !window.popup.closed) {
		window.popup.resizeTo(width, height);
	}

	// Set window properties
	var specs = "location=no,scrollbars=no,menubar=no,toolbar=no,resizable=yes,left=0,top=0,width=" + width + ",height=" + height;

	// Set URL
	var url = "show_image.php?image=" + image;

	// Create pop-up window
	popup = window.open(url, "ImageWindow", specs);
	popup.focus();
} 
