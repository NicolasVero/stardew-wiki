<?php 

function is_file_secure(mixed $file, bool $manual_error = false, string $manual_error_type):bool {

	if($manual_error) {
		switch($manual_error_type) {
			case 'SizeException' :
				throw new Exception('Invalid file size.');
			default :
				return true;
		}
	}

    if($file['size'] < 0 || $file['size'] > in_bytes_conversion("35Mo")) {
        throw new Exception('Invalid file size.');
    }
	
    if(!in_array($file['type'], array('application/xml', 'text/xml', 'application/octet-stream'))) {
        throw new Exception('The file is not in xml format.');
    }
    
    if(!array_keys_exists(array('player', 'uniqueIDForThisGame', 'gameVersion'), (array) simplexml_load_file($file['tmp_name']))) {
        throw new Exception('File not conforming to a Stardew Valley save.');
    }

    if(!is_uploaded_file($file['tmp_name'])) {
        throw new Exception('Error loading file.');
    }

    if($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Error downloading file.');
    }

    return true;
}
