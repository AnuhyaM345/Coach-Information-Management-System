<?php
require_once 'config.php';

$fileName = isset($_GET['file']) ? $_GET['file'] : '';
$response = ['exists' => false];

if ($fileName && file_exists($config['upload_dir'] . $fileName)) {
    $response['exists'] = true;
}

echo json_encode($response);
?>