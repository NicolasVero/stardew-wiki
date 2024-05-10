<?php 

function is_file_secure(mixed $file):bool {
    $file_content = (array) simplexml_load_file($file['tmp_name']);
    
    $check_file_error  = $file['error'] == 0;
    $check_file_size   = $file['size'] > 0 && $file['size'] < in_bytes_conversion("40Mo"); 
    $is_a_stardew_save = 
        array_key_exists('player', $file_content) && 
        array_key_exists('uniqueIDForThisGame', $file_content) &&
        array_key_exists('gameVersion', $file_content)
    ; 

    return $check_file_error && $check_file_size && $is_a_stardew_save;
}
