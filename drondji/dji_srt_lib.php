<?php

function dji_read_srt($file)
{
    $content = file_get_contents($file);
    return preg_split('/\R{2,}/', trim($content));
}

function dji_reduce_to_1s($blocks)
{
    $perSecond = [];

    foreach ($blocks as $block) {

        $lines = explode("\n", trim($block));

        if (count($lines) < 3) continue;

        $time = $lines[1];

        if (!preg_match('/(\d\d:\d\d:\d\d),(\d\d\d)/', $time, $m)) continue;

        $secKey = $m[1];

        if (!isset($perSecond[$secKey])) {
            $perSecond[$secKey] = $block;
        }
    }

    $out = "";
    $i = 1;

    foreach ($perSecond as $sec => $block) {

        $lines = explode("\n", trim($block));

        $start = $sec . ",000";

        $t = explode(":", $sec);

        $next = sprintf("%02d:%02d:%02d",
            $t[0],
            $t[1],
            $t[2] + 1
        );

        $end = $next . ",000";

        $lines[1] = "$start --> $end";
        $lines[0] = $i++;

        $out .= implode("\n", $lines) . "\n\n";
    }

    return $out;
}

function dji_detect_vars($srtContent)
{
    $firstBlock = explode("\n\n", trim($srtContent))[0];
    $lines = explode("\n", $firstBlock);

    $data = implode(" ", array_slice($lines,2));

    $vars = [];

    if (preg_match('/FrameCnt:\s*([0-9]+)/', $data))
        $vars['FrameCnt']='/FrameCnt:\s*([0-9]+)/';

    if (preg_match('/DiffTime:\s*([0-9]+)/', $data))
        $vars['DiffTime']='/DiffTime:\s*([0-9]+)/';

    if (preg_match('/(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\.\d+/', $data))
        $vars['timestamp']='/(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\.\d+/';

    preg_match_all('/\[(.*?)\]/',$data,$matches);

    foreach($matches[1] as $m){

        if(strpos($m,':')!==false){

            list($k,$v)=explode(':',$m,2);
            $k=trim($k);

            $vars[$k]='/\['.$k.':\s*([^\]\s]+)/';
        }
    }

    return $vars;
}

function dji_generate_filtered($srt,$selected)
{
    $blocks = preg_split('/\R{2,}/', trim($srt));
    $out="";

    foreach($blocks as $block){

        $lines=explode("\n",$block);

        $text=implode(" ",array_slice($lines,2));

        $values=[];

        foreach($selected as $name=>$cfg){

            if(preg_match($cfg['regex'],$text,$m)){

                $v=$m[1];

                if($name==="rel_alt"){

                    if(strpos($v,'.')!==false){
                        $p=explode('.',$v);
                        $v=$p[0].'.'.substr($p[1],0,1);
                    }

                }

                $values[]=$cfg['prefix'].$v.$cfg['suffix'];
            }
        }

        $lines=array_slice($lines,0,2);

        $lines[]='<font size="28">'.implode(" ",$values).'</font>';

        $out.=implode("\n",$lines)."\n\n";
    }

    return $out;
}
