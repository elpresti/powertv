<?php

require_once "dji_srt_lib.php";

$options = getopt("", ["srt_input:"]);

if(!isset($options['srt_input']))
    die("Uso: php dji_srt_sanitizer.php --srt_input=\"archivo.srt\"\n");

$input=$options['srt_input'];

$dir=dirname($input);
$name=pathinfo($input,PATHINFO_FILENAME);

$reducedFile=$dir."/".$name."-1sub_per_second.srt";
$finalFile=$dir."/".$name."-1sub_per_second-selected_vars.srt";

echo "Leyendo SRT...\n";

$blocks=dji_read_srt($input);

$srtReduced=dji_reduce_to_1s($blocks);

file_put_contents($reducedFile,$srtReduced);

echo "Archivo generado:\n$reducedFile\n";

echo "\nAnalizando variables...\n";

$vars=dji_detect_vars($srtReduced);

$i=1;
$map=[];

foreach($vars as $k=>$v){

    echo "$i) $k\n";
    $map[$i]=$k;
    $i++;
}

echo "\nElegí números separados por coma: ";

$choice=trim(fgets(STDIN));

$ids=explode(',',$choice);

$selected=[];

foreach($ids as $id){

    $nameVar=$map[trim($id)];

    echo "Prefijo para $nameVar: ";
    $p=trim(fgets(STDIN));

    echo "Sufijo para $nameVar: ";
    $s=trim(fgets(STDIN));

    $selected[$nameVar]=[
        'regex'=>$vars[$nameVar],
        'prefix'=>$p,
        'suffix'=>$s
    ];
}

$result=dji_generate_filtered($srtReduced,$selected);

file_put_contents($finalFile,$result);

echo "\nArchivo generado:\n$finalFile\n";