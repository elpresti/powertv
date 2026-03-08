<?php

require_once "../dji_srt_lib.php";

session_start();

$srt = $_SESSION['srt'];
$vars = $_SESSION['vars'];
$filename = $_SESSION['filename'];

$base = pathinfo($filename, PATHINFO_FILENAME);

$datetime = date("Ymd_His");

$outputName = $base . "_cleaned_" . $datetime . ".srt";

$selected = [];

if (!empty($_POST['vars'])) {

foreach ($_POST['vars'] as $v) {

$selected[$v] = [

'regex' => $vars[$v],
'prefix' => $_POST["prefix_$v"] ?? '',
'suffix' => $_POST["suffix_$v"] ?? ''

];

}

}

$result = dji_generate_filtered($srt, $selected);

header("Content-Type: application/x-subrip");
header("Content-Disposition: attachment; filename=\"$outputName\"");

echo $result;
exit;
