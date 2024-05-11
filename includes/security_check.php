<?php 

function is_file_secure(mixed $file): bool {

    if(!is_uploaded_file($file['tmp_name'])) {
        throw new Exception('Error loading file.');
    }

    if($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Error downloading file.');
    }

    if(!($file['size'] > 0 && $file['size'] < in_bytes_conversion("40Mo"))) {
        throw new Exception('Invalid file size.');
    }

    $file_content = (array) simplexml_load_file($file['tmp_name']);
    if(!(
        array_key_exists('player', $file_content) &&
        array_key_exists('uniqueIDForThisGame', $file_content) &&
        array_key_exists('gameVersion', $file_content)
    )) {
        throw new Exception('File not conforming to a Stardew Valley save.');
    }

    return true;
}
