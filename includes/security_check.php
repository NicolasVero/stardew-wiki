<?php 

function is_file_secure(mixed $file): bool {

    if(!is_uploaded_file($file['tmp_name'])) {
        throw new Exception('Erreur lors du chargement du fichier.');
    }

    if($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Erreur lors du téléchargement du fichier.');
    }

    if(!($file['size'] > 0 && $file['size'] < in_bytes_conversion("40Mo"))) {
        throw new Exception('Taille de fichier invalide.');
    }

    $file_content = (array) simplexml_load_file($file['tmp_name']);
    if(!(
        array_key_exists('player', $file_content) &&
        array_key_exists('uniqueIDForThisGame', $file_content) &&
        array_key_exists('gameVersion', $file_content)
    )) {
        throw new Exception('Fichier non conforme à une sauvegarde Stardew Valley.');
    }

    return true;
}
