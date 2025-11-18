<?php
//this script runs in a local PC
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$baseUrl = "http://eltiempo.telpin.com.ar/";
$url = $baseUrl . "infocel.htm";
$targetUrl = "https://radiopower.com.ar/powerhd/webroot/widgets/weather/util/getTelpinInfo.php?action=save_html_info";

// Headers personalizados
$options = [
    "http" => [
        "method" => "GET",
        "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36\r\n" .
                    "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8\r\n" .
                    "Accept-Language: es-ES,es;q=0.9\r\n" .
                    "Referer: " . $baseUrl . "\r\n" .
					"Connection: close\r\n"
    ]
];

$context = stream_context_create($options);

$contents = file_get_contents($url, false, $context);

if ($contents === false) {
    echo "Error al obtener el contenido.";
	exit;
}

// Convertir el contenido a base64 para evitar problemas de codificación
$encodedContents = base64_encode($contents);

// Enviar datos a la URL de destino
$postData = http_build_query(["html_data" => $encodedContents]);

$httpOptions = [
    "http" => [
        "method" => "POST",
        "header" => "Content-Type: application/x-www-form-urlencoded\r\n" .
                    "Content-Length: " . strlen($postData) . "\r\n",
        "content" => $postData
    ]
];

$contextPost = stream_context_create($httpOptions);
$response = file_get_contents($targetUrl, false, $contextPost);

if ($response === false) {
    echo "Error al enviar los datos.";
} else {
    echo "Datos enviados correctamente.";
}

?>
