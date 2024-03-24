<?php

// Root de la aplicación a partir de http://localhost/
define("APP_ROOT", "[Práctica%2006]%20File%20Manager/");

// Ruta física de la aplicación
define("APP_PATH", "C:\\xampp\\htdocs\\[Práctica 06] File Manager");

// Directorio donde se van a subir los archivos
define("DIR_UPLOAD", "C:/xampp/htdocs/[Práctica 06] File Manager/archivos_subidos/");

// Extensiones de archivos con su correspondiente content-type.
$CONTENT_TYPES_EXT = [
    "jpg" => "image/jpeg",
    "jpeg" => "image/jpeg",
    "gif" => "image/gif",
    "png" => "image/png",
    "json" => "application/json",
    "pdf" => "application/pdf",
    "bin" => "application/octet-stream"
];

// Inicializar el array de archivos
$archivos = array();

// Leer los archivos del directorio de subida
if ($handle = opendir(DIR_UPLOAD)) {
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") {
            $archivos[] = $entry;
        }
    }
    closedir($handle);
}
