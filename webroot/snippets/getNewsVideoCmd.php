<?php
/* CMD EXAMPLE 1:
//"D:\Software\RoboTask\Tasks\apps\php-7.4.32-nts-Win32-vc15-x64\php.exe" -c "D:\Software\RoboTask\Tasks\apps\php-7.4.32-nts-Win32-vc15-x64\php.ini" -e "D:\Software\RoboTask\Tasks\apps\notiFlashIA\getNewsVideoCmd.php"
//--ffmpegpath="D:\Software\ffmpeg\ffmpeg-2023-07-19-git-efa6cec759-full_build\bin\ffmpeg.exe" --mediainputfiles="D:\Software\trash\img_1.jpg,D:\Software\trash\img_2.png,D:\Software\trash\img_3.jpg"
//--audiofile="D:\Software\trash\flash17notiMensajero.mp3" --mainvideototaltime="46" --eachmediainputtime="6" --resultantfileslocation="D:\\Software\\trash\\"
//--maintextcontainerbgcolor="#DDDBDD" --maintextcolor="#3A1E04" --alltextcontainerspositionx="150" --minitextcontainergbcolor="#00223F"  --minitextcolor="#FFFFFF"
//--videowidth="1920" --videoheight="1080" --maintextcontainerwidth="1600" --maintextcontainerpositiony="700" --minititlecontainerpositiony="620"
//--fontsdir="D\\:\\\\Software\\\\trash\\\\fonts\\\\" --minititlefontsize="30" --titlefontsize="36" --bodyfontsize="48" --ffmpegfinalcmdfilename="ffmpegfinalcmd.bat"
//--newscontentminititle="ECOLOGÍA EN PINAMAR" --newscontenttitle="TRATAMIENTO DE LA FAUNA MARINA EN PINAMAR"
//--newscontentbody="LA FUNDACIÓN ECOLÓGICA PINAMAR SE REUNIÓ CON CONCEJALES PARA TRATAR EL TEMA DEL TRATAMIENTO DE LA FAUNA MARINA. LOS REPRESENTANTES DE LA FUNDACIÓN COMENTARON QUE DESDE HACE 20 AÑOS HAY UN CONVENIO CON FUNDACIÓN MUNDO MARINO PARA EL RESCATE DE LOS ANIMALES, PERO QUE MUCHAS VECES NO TIENEN CAMIONETAS DISPONIBLES. SE PROPUSO GENERAR UNA REUNIÓN CON EL DEPARTAMENTO DE ECOLOGÍA, PREFECTURA, SEGURIDAD EN PLAYA Y FUNDACIÓN ECOLÓGICA PARA TRANSFORMAR LO DIALOGADO EN UNA NORMATIVA O EN UN PROGRAMA. LA REUNIÓN SE LLEVARÁ A CABO EL MARTES 18 DE JULIO A LAS 11 DE LA MAÑANA."

/* EXAMPLE 2 (RECEPCION): WIth less params, using many default values:
"D:\Software\RoboTask\Tasks\apps\php-7.4.32-nts-Win32-vc15-x64\php.exe" -c  "D:\Software\RoboTask\Tasks\apps\php-7.4.32-nts-Win32-vc15-x64\php.ini" -e "D:\Software\MarIA\getNewsVideoCmd.php"  --ffmpegpath="D:\Software\ffmpeg\ffmpeg-2023-07-19-git-efa6cec759-full_build\bin\ffmpeg.exe"  --mediainputfiles="D:\Software\trash\img_1.jpg,D:\Software\trash\img_2.png,D:\Software\trash\img_3.jpg" --audiofile="D:\Software\trash\flash17notiMensajero.mp3" --mainvideototaltime="46" --eachmediainputtime="6" --resultantfileslocation="D:\Software\trash" --fontsdir="D:\Software\trash\fonts" --ffmpegfinalcmdfilename="ffmpegfinalcmd.bat" --newscontentminititle="ECOLOGÍA EN PINAMAR" --newscontenttitle="TRATAMIENTO DE LA FAUNA MARINA EN PINAMAR" --newscontentbody="LA FUNDACIÓN ECOLÓGICA PINAMAR SE REUNIÓ CON CONCEJALES PARA TRATAR EL TEMA DEL TRATAMIENTO DE LA FAUNA MARINA.LOS REPRESENTANTES DE LA FUNDACIÓN COMENTARON QUE DESDE HACE 20 AÑOS HAY UN CONVENIO CON FUNDACIÓN MUNDO MARINO PARA EL RESCATE DE LOS ANIMALES, PERO QUE MUCHAS VECES NO TIENEN CAMIONETAS DISPONIBLES. SE PROPUSO GENERAR UNA REUNIÓN CON EL DEPARTAMENTO DE ECOLOGÍA, PREFECTURA, SEGURIDAD EN PLAYA Y FUNDACIÓN ECOLÓGICA PARA TRANSFORMAR LO DIALOGADO EN UNA NORMATIVA O EN UN PROGRAMA. LA REUNIÓN SE LLEVARÁ A CABO EL MARTES 18 DE JULIO A LAS 11 DE LA MAÑANA."

EXAMPLE 3 (AIRE): "D:\Software\RoboTask\Tasks\apps\php-7.4.32-nts-Win32-vc15-x64\php.exe" -c  "D:\Software\RoboTask\Tasks\apps\php-7.4.32-nts-Win32-vc15-x64\php.ini" -e "D:\Software\RoboTask\Tasks\apps\notiFlashIA\getNewsVideoCmd.php"  --ffmpegpath="D:\Software\ffmpeg\ffmpeg-2023-07-19-git-efa6cec759-full_build\bin\ffmpeg.exe"  --mediainputfiles="D:\InsoftRadio\media\V-WEBCONT\flashiaContent\flash1_notiEconoPolitica-news_images_img1.jpg,D:\InsoftRadio\media\V-WEBCONT\flashiaContent\flash1_notiEconoPolitica-news_images_img2.jpg,D:\InsoftRadio\media\V-WEBCONT\flashiaContent\flash1_notiEconoPolitica-news_images_img3.jpg" --audiofile="D:\InsoftRadio\media\V-WEBCONT\flashiaContent\output1.mp3" --eachmediainputtime="6" --resultantfileslocation="D:\InsoftRadio\media\V-WEBCONT\flashiaContent" --fontsdir="D:\Software\RoboTask\Tasks\apps\notiFlashIA\videoFonts" --ffmpegfinalcmdfilename="ffmpegfinalcmd.bat" --newscontentminititle="ECOLOGÍA EN PINAMAR" --newscontenttitle="TRATAMIENTO DE LA FAUNA MARINA EN PINAMAR" --newscontentbody="LA FUNDACIÓN ECOLÓGICA PINAMAR SE REUNIÓ CON CONCEJALES PARA TRATAR EL TEMA DEL TRATAMIENTO DE LA FAUNA MARINA.LOS REPRESENTANTES DE LA FUNDACIÓN COMENTARON QUE DESDE HACE 20 AÑOS HAY UN CONVENIO CON FUNDACIÓN MUNDO MARINO PARA EL RESCATE DE LOS ANIMALES, PERO QUE MUCHAS VECES NO TIENEN CAMIONETAS DISPONIBLES. SE PROPUSO GENERAR UNA REUNIÓN CON EL DEPARTAMENTO DE ECOLOGÍA, PREFECTURA, SEGURIDAD EN PLAYA Y FUNDACIÓN ECOLÓGICA PARA TRANSFORMAR LO DIALOGADO EN UNA NORMATIVA O EN UN PROGRAMA. LA REUNIÓN SE LLEVARÁ A CABO EL MARTES 18 DE JULIO A LAS 11 DE LA MAÑANA."
D:\Software\RoboTask\Tasks\apps\notiFlashIA\execBatWithLowPriority.exe D:\InsoftRadio\media\V-WEBCONT\flashiaContent\flash1_infobaeDeportes_ffmpegcmd.bat

*/

function getTextWidthPx($text, $fontFilePath, $fontSize) {
    // Obtener las dimensiones del texto
	//echo "\n fontFilePath=" . $fontFilePath . ", fontSize=" . $fontSize . "\n" . " text=" . $text . "\n";
    $bbox = imagettfbbox($fontSize, 0, $fontFilePath, $text);
    
    // Calcular el ancho del texto en píxeles
    $width = $bbox[2] - $bbox[0];
	//echo "\n width=" . $width . "px \n";
    
    // Devolver el ancho del texto en píxeles
    return $width;
}

function makeTextBlock($text, $fontfile, $fontsize, $width) {    
    $fixedFontSize = round(($fontsize * 0.56), 0, PHP_ROUND_HALF_UP);
	$words = explode(' ', $text);
    $lines = array($words[0]);
    $currentLine = 0;
    for($i = 1; $i < count($words); $i++) {
        $lineSize = imagettfbbox($fixedFontSize, 0, $fontfile, $lines[$currentLine] . ' ' . $words[$i]);
        if($lineSize[2] - $lineSize[0] < $width) {
            $lines[$currentLine] .= ' ' . $words[$i];
        } else {
            $currentLine++;
            $lines[$currentLine] = $words[$i];
        }
    }
    return implode("\n", $lines);
}

function getTextHeightPx($text, $fontFilePath, $fontSize, $textContainerWidth) {
    $textLineHeight = round($fontSize * 1.25);
	//Alternative 1 to calculate text height:
	//$avgFontWidth = round(($fontSize / 2) * 0.8);
	$avgFontWidth = round(($fontSize / 2));
	$avgCharsPerTextLine = round(($textContainerWidth / $avgFontWidth), 0, PHP_ROUND_HALF_UP);
	$amountOfTextLines = round((strlen($text) / $avgCharsPerTextLine), 0, PHP_ROUND_HALF_UP); //$amountOfTextLines should be 9, for fontSize=48 and textContainerWidth=440
	$textTotalHeight = ($fontSize * $amountOfTextLines)-9;
	
	//Alternative 2 to calculate text height:
	$multilineText = makeTextBlock($text, $fontFilePath, $fontSize, $textContainerWidth);
	echo "\n  fontFilePath=" . $fontFilePath . ", fontSize=" . $fontSize . "\n" . " \n textContainerWidth=" . $textContainerWidth . " \n textLineHeight=" . $textLineHeight . "\n text=" . $text . "\n multilineText=" . $multilineText . "\n";

	// Crear una imagen en blanco
    //$img = imagecreatetruecolor($textContainerWidth, 1);

    // Obtener las dimensiones del texto
    $bbox = imagettfbbox($fontSize, 0, $fontFilePath, $multilineText);
	//$bbox = imagettfbbox($fontSize, 0, $fontFilePath, $text);
    
    // Calcular el alto total del texto
    $textHeight = $bbox[1] - $bbox[7] + $textLineHeight;

    // Liberar la memoria
    //imagedestroy($img);
    
	echo "\n textHeight1=" . $textTotalHeight . ", textHeight2=" . $textHeight . "px \n";
    
	// Devolver el alto total del texto
    return $textHeight;
	//return $textTotalHeight;
}

function getAssFileContent($textToShow, $fontSize, $fontName, $fontColor, $totalTime, $textContainerWidth, $textContainerHeight, $textFontFilePath = null) {
    //$textToShow = iconv(mb_detect_encoding($textToShow, mb_detect_order(), true), "UTF-8", $textToShow);
    $textToShow = iconv("UTF-8", "Windows-1252", $textToShow);
    $textLineHeight = round(($textContainerHeight + $fontSize) / 2);
   
    $fontColor = substr($fontColor, 1);//remove first char "#"
    $minCharsToScroll = 200;
    $staticTextDuration = 10;
    //con fontSize=48 los caracteres mas anchos tienen 15px de ancho, incluyendo espacio, y los mas finos no se, pero andaran en 5px.
    //Entonces en un width=440 entrarian 30 caracteres anchos aprox.
    // Build ASS file Content
    $assFileContent = "[Script Info]
Title: News content
ScriptType: v4.00
Collisions: Normal
PlayResX: ".$textContainerWidth."
PlayResY: ".$textContainerHeight."\n
[V4+ Styles]
Format: Name, Fontname, Fontsize, PrimaryColour, SecondaryColour, TertiaryColour, BackColour, Bold, Italic, Underline, StrikeOut, ScaleX, ScaleY, Spacing, Angle, BorderStyle, Outline, Shadow, Alignment, MarginL, MarginR, MarginV, Encoding, WrapStyle
Style: Default, ".$fontName.", ".$fontSize.", &H00".$fontColor.", &HFF000000, &HFF000000, &HFF000000, 0, 0, 0, 0, 100, 100, 0, 0, 1, 0, 0, 2, 10, 10, 10, 1, 0\n
[Events]
Format: Layer, Start, End, Style, Name, MarginL, MarginR, MarginV, Effect, Text\n";
    if (strlen($textToShow) > $minCharsToScroll) {
        //if the text is too long, add two Dialogue layers, the first static, and the second one should be the same but with the move-FX to scroll the text
	    //$textLineHeight = round($fontSize * 1.2);//TODO uncomment this, that should be used
		$textTotalHeight = getTextHeightPx($textToShow, $textFontFilePath, $fontSize, $textContainerWidth);
        $bodyTextFixVar = 0.3958;
		$textTotalHeightFixed = round((int)$textTotalHeight * $bodyTextFixVar);
		$fadeOutAt = $totalTime - 5;
        $assFileContent .= 'Dialogue: 0,0:00:00.00,0:00:'.$staticTextDuration.'.00,Default,,0,0,0,,{\an1}{\t(0,'.$staticTextDuration.'000,\fad(1000,0)\move(0,'.$textTotalHeightFixed.',0,'.$textTotalHeightFixed.'))}'.$textToShow."\n";//432 para fontsize=48
        $assFileContent .= 'Dialogue: 0,0:00:'.$staticTextDuration.'.00,0:00:'.$totalTime.'.00,Default,,0,0,0,,{\an1}{\\t(0,'.$fadeOutAt.'000,\move(0,'.$textTotalHeightFixed.',0,0)\\fad(0,1000))}'.$textToShow."\n";
//        $assFileContent .= 'Dialogue: 0,0:00:00.00,0:00:'.$totalTime.'.00,Default,,0,0,0,Scroll up;-150;0;50;fadeawayheight,{\an1}'.$textToShow."\n";
    } else {
        $assFileContent .= "Dialogue: 0,0:00:00.00,0:00:".$totalTime.".00,Default,,0,0,0,,{\an1}{\\t(0,".$totalTime."000,\\fad(2000,0)\\move(0,".$textLineHeight.",0,".$textLineHeight.")\\fad(0,2000))}$textToShow\n";
    }
    return utf8_encode($assFileContent);
}

/*
function getFfmpegCmdToGenerateMainVideo($params, $outCmdExecutableFilename) {
    $full_ffmpeg_cmd = "{$params['ffmpegpath']} -y -threads 1 -loop 1 -t 10 -framerate 25 ";
    $mediaInputFiles = $params['mediainputfiles']; //comma separated list of image files
    $full_ffmpeg_cmd .= "-i img1.jpg ";//-loop 1 -t 10 -framerate 25 ";
    $full_ffmpeg_cmd .= "-i img2.jpg ";//-loop 1 -t 10 -framerate 25 ";
    $full_ffmpeg_cmd .= "-i img3.jpg ";
    $full_ffmpeg_cmd .= "-i img1.jpg ";
    $full_ffmpeg_cmd .= "-filter_complex ";
    $full_ffmpeg_cmd .= "\"[0]scale=8000:-1,zoompan=z='zoom+0.001':x=iw/2-(iw/zoom/2):y=ih/2-(ih/zoom/2):d=11*25:s=1920x1080:fps=25[s0];";
    $full_ffmpeg_cmd .= "[1]scale=8000:-1,zoompan=z='zoom+0.001':x=iw/2-(iw/zoom/2):y=ih/2-(ih/zoom/2):d=11*25:s=1920x1080:fps=25[s1];";
    $full_ffmpeg_cmd .= "[2]scale=8000:-1,zoompan=z='zoom+0.001':x=iw/2-(iw/zoom/2):y=ih/2-(ih/zoom/2):d=11*25:s=1920x1080:fps=25[s2];";
    $full_ffmpeg_cmd .= "[3]scale=8000:-1,zoompan=z='zoom+0.001':x=iw/2-(iw/zoom/2):y=ih/2-(ih/zoom/2):d=11*25:s=1920x1080:fps=25[s3];";
    $full_ffmpeg_cmd .= "[s0][s1]xfade=transition=hblur:duration=0.5:offset=9[f0];";
    $full_ffmpeg_cmd .= "[f0][s2]xfade=transition=hblur:duration=0.5:offset=19[f1];";
    $full_ffmpeg_cmd .= "[f1][s3]xfade=transition=hblur:duration=0.5:offset=29[f2];\" -map [f2] -t 39 -c:v libx264 -pix_fmt yuv420p -y " . substr($outCmdExecutableFilename, 0, -3) . "mp4";

    $ffmpegLogFilename = substr($outCmdExecutableFilename, 0, -3) . "log";
    $full_ffmpeg_cmd .= " ^1^> {$ffmpegLogFilename} ^2^>^&^1";
    $ffmpegCmd = "start /low /MIN cmd /c {$full_ffmpeg_cmd} ";
    return $ffmpegCmd;
}
*/

function getFfmpegCmdToGenerateMainVideo($params, $outputVideoFilename, $defaultValues) {
    $defaultFramerate = $defaultValues['videoframerate'];
    $mediaInputFiles = explode(',', $params['mediainputfiles']);

    $totalVideoDurationRequested = (int)$params['mainvideototaltime'] + 3;//add some extra time to ensure it will never be shorter than requested. No problem if it is larger than requested

    $eachInputDuration = $params['eachmediainputtime'];
    if ($eachInputDuration == 0) { //request to divide time proportionally
        $eachInputDuration = round(($totalVideoDurationRequested) / sizeof($mediaInputFiles));
        if (($eachInputDuration > 20) || ($eachInputDuration < 5)) {
            $eachInputDuration = $defaultValues['eachmediainputtime'];//if it is too short or too large, we use a default time
        }
    }

    $inputsTotalDuration = sizeof($mediaInputFiles) * $eachInputDuration;
    if ($inputsTotalDuration < $totalVideoDurationRequested) {
        $amountOfInputsRequired = round(($totalVideoDurationRequested / $eachInputDuration), 0, PHP_ROUND_HALF_UP);
        $amountOfInputsToAdd = $amountOfInputsRequired - sizeof($mediaInputFiles);
        $inputsAdded = 0;
        $mediaInputFilesClon = $mediaInputFiles;
        while ($inputsAdded < $amountOfInputsToAdd) {
            foreach ($mediaInputFilesClon as $mediaInputFile) {
                if ($inputsAdded < $amountOfInputsToAdd) {
                    $mediaInputFiles[]= $mediaInputFile;
                    $inputsAdded++;
                } else {
                    break;
                }
            }
        }
    }

    $full_ffmpeg_cmd = "{$params['ffmpegpath']} -y -threads 1 -framerate " . $defaultFramerate . " ";
    $filter_complex = '';
    $filter_map = '';

    foreach ($mediaInputFiles as $index => $inputFile) {
        $filter_complex .= "[$index]scale=8000:-1,zoompan=z='zoom+0.001':x=iw/2-(iw/zoom/2):y=ih/2-(ih/zoom/2):d=" .
            ($eachInputDuration + 1) . "*" . $defaultFramerate . ":s={$params['videowidth']}x{$params['videoheight']}:fps=" .
            $defaultFramerate . "[s$index];";
        $filter_map .= "[s$index]";
        $inputPath = trim($inputFile);
        $full_ffmpeg_cmd .= "-i $inputPath ";
    }

    $xfade_commands = '';
    for ($i = 0; $i < count($mediaInputFiles) - 1; $i++) {
        if ($i == 0) {
            $xfade_commands .= "[s$i][s" . ($i + 1) . "]xfade=transition=hblur:duration=0.5:offset=" . (($i * $eachInputDuration) + ($eachInputDuration - 1)) . "[f" . ($i + 1) . "];";
            continue;
        }
        $xfade_commands .= "[f$i][s" . ($i + 1) . "]xfade=transition=hblur:duration=0.5:offset=" . (($i * $eachInputDuration) + ($eachInputDuration - 1)) . "[f" . ($i + 1) . "];";
    }

    $full_ffmpeg_cmd .= "-filter_complex \"$filter_complex$xfade_commands\" ";
    $full_ffmpeg_cmd .= "-map [f" . (count($mediaInputFiles) - 1) . "] -t " . ((count($mediaInputFiles) * $eachInputDuration) - 1)
        . " -c:v libx264 -pix_fmt yuv420p " . substr($outputVideoFilename, 0, -3) . "mp4";
    return $full_ffmpeg_cmd;
/*
    $ffmpegLogFilename = substr($outCmdExecutableFilename, 0, -3) . "log";
    $full_ffmpeg_cmd .= " ^1^> {$ffmpegLogFilename} ^2^>^&^1";
    $ffmpegCmd = "start /low /MIN cmd /c {$full_ffmpeg_cmd} ";
    return $ffmpegCmd;
*/
}

function getCmdToExecuteAllFfmpegCmds($params, $ffmpegCmds, $batchFilename) {
	$executionLogFilename = substr($batchFilename, 0, -3) . "log";
	$ffmpegCmdsString = "";
    foreach ($ffmpegCmds as $index => $ffmpegCmd) {
		$ffmpegCmdsString .= 'echo. ' . ' >> "' . $executionLogFilename . '"'. PHP_EOL;
		$ffmpegCmdsString .= 'for /F "usebackq tokens=1,2,3,4,5,6 delims=/: " %%i in (`echo %DATE% %TIME%`) do set CURRENT_DATETIME=[%%k-%%j-%%i %%l:%%m:%%n]'. PHP_EOL;
		$ffmpegCmdsString .= 'echo %CURRENT_DATETIME% - Starting execution of the next command: >> "' . $executionLogFilename . '"'. PHP_EOL;
		$ffmpegCmdsString .= 'echo. ' . ' >> "' . $executionLogFilename . '"'. PHP_EOL;
		$ffmpegCmdsString .= 'echo ' . $ffmpegCmd . ' >> "' . $executionLogFilename . '"'. PHP_EOL;
		$ffmpegCmdsString .= 'echo. ' . ' >> "' . $executionLogFilename . '"'. PHP_EOL;
		//$ffmpegCmdsString .= 'call ' . $ffmpegCmd . ' >> "' . $executionLogFilename . '" 2>&1';
		$ffmpegCmdsString .= 'wmic process where name="ffmpeg.exe" CALL setpriority "below normal" >nul & ' . $ffmpegCmd . ' >> "' . $executionLogFilename . '" 2>&1';

		//$ffmpegCmdsString .= 'start /low ' . $ffmpegCmd . ' >> "' . $executionLogFilename . '" 2>&1';
		//$ffmpegCmdsString .= 'start /low /MIN cmd /c ' . $ffmpegCmd . " ^1^> {$executionLogFilename} ^2^>^&^1";

		//if it is not the last one, add "&"
		if ($index < count($ffmpegCmds) - 1) {
            $ffmpegCmdsString .= " & ";
        }
    }

	// Construir el contenido del archivo BAT
    $batchFileContent = '@echo off' . PHP_EOL;
    $batchFileContent .= 'type nul > "' . $executionLogFilename . '"'. PHP_EOL; // Limpiar el archivo de log antes de comenzar la ejecución
    $batchFileContent .= 'echo Ejecutando comandos de FFmpeg...  >> "' . $executionLogFilename . '"'. PHP_EOL;
    $batchFileContent .= 'echo. ' . ' >> "' . $executionLogFilename . '"'. PHP_EOL;
	$batchFileContent .= $ffmpegCmdsString . PHP_EOL;
    $batchFileContent .= 'echo Completado. ' . ' >> "' . $executionLogFilename . '"'. PHP_EOL;

    return $batchFileContent;
}

function getFfmpegCmdToAddAudioToVideo($params, $outputVideoFilename) {
    //ffmpeg -i myVideo.mp4 --normalization-type peak --target-level 0  -c:a aac -b:a 192k -ext mp4
    $full_ffmpeg_cmd = "{$params['ffmpegpath']} -y -threads 1 ";
    $full_ffmpeg_cmd .= "-i " . $params['videowithtextsfilename'] . " ";
    $full_ffmpeg_cmd .= "-i " . $params['audiofile'] . " ";
    $full_ffmpeg_cmd .= "-c:v copy -c:a aac -strict experimental -map 0 -map 1 -shortest ";
    $full_ffmpeg_cmd .= "-af \"adelay=" . $params['audiointrolength'] . "000|" . $params['audiointrolength'] . "000,dynaudnorm\" ";
    $full_ffmpeg_cmd .= substr($outputVideoFilename, 0, -3) . "mp4";
    return $full_ffmpeg_cmd;
 //   $ffmpegLogFilename = substr($outCmdExecutableFilename, 0, -3) . "log";
 //   $full_ffmpeg_cmd .= " ^1^> {$ffmpegLogFilename} ^2^>^&^1";
 //   $ffmpegCmd = "start /low /MIN cmd /c {$full_ffmpeg_cmd} ";
 //   return $ffmpegCmd;
}

function getFfmpegCmdToAddTextsAndAudioToVideo($params, $outputVideoFilename) {
    //$textsTotalTime = $params['mainvideototaltime'] - 4;
    $textsTotalTime = $params['mainvideototaltime'];

    // Build given texts into subtitle files
    // 1) Build minititle subtitle file. //TODO: make this optional, to only build it if mini-title text is present
    $ffmpegfinalcmdfilenameExtensionChars = 4;
	$ffmpegfinalcmdfilenameInput = substr($params['ffmpegfinalcmdfilename'], 0, -$ffmpegfinalcmdfilenameExtensionChars);
	$miniTitleFileName = $ffmpegfinalcmdfilenameInput . "_news-mini-title.ass";
    $miniTitleSubtitleFilePath = $params['resultantfileslocation'] . $miniTitleFileName;
    $minititleFontName = "Rockford Sans Light";
    $minititleFontColor = $params['minitextcolor'];
    $miniTitleFontFilePath = $params['minitextfontfile']; 
	//$minititleSubtitleTotalTime = 30;
    $minititleSubtitleTotalTime = $textsTotalTime;
    //$minitextBgWidth = "440";
    //$minitextBgWidth = round((round($params['minititlefontsize'] / 2) * strlen($params['newscontentminititle'])) * 1.20);
    $minitextBgWidth = round(getTextWidthPx($params['newscontentminititle'], $miniTitleFontFilePath, $params['minititlefontsize']) * 0.83);//big error in measuring. Fix -17%
	$minitextContainerWidth = round($minitextBgWidth * 1.10);

    // Create news Mini-title text layer
    $minitextBgHeight = round($params['minititlefontsize'] * 1.3);
    $minitextSubtitleHeight = round($minitextBgHeight * 1.3);
    $minitextSubtitleWidth = $minitextBgWidth + 10;
    $minitextContainerHeight = $minitextSubtitleHeight + 10;

    $assFileContent = getAssFileContent(
        $params['newscontentminititle'],
        $params['minititlefontsize'],
        $minititleFontName,
        $minititleFontColor,
        $minititleSubtitleTotalTime,
        $minitextBgWidth,
        $minitextBgHeight
    );
    file_put_contents($miniTitleSubtitleFilePath, $assFileContent);
    echo "File $miniTitleSubtitleFilePath created successfully.\n";

    // 2) Build title subtitle file
    $titleSubtitleFileName = $params['resultantfileslocation'] . $ffmpegfinalcmdfilenameInput . "_news-content-title.ass";
    $titleFontName = "Galano Grotesque Alt SemiBold";
    $titleFontColor = $params['maintextcolor'];
    //$titleSubtitleTotalTime = 30;
    $titleSubtitleTotalTime = $textsTotalTime;
    $titleSubtitlePaddingTop = 10;
    $bodyAndTitleTextsLayersPaddingLeft = 20;
    $bodyAndTitleTextsLayersPaddingRight = 20;
    $titleAndBodySubtitleWidth = $params['maintextcontainerwidth'] - $bodyAndTitleTextsLayersPaddingLeft - $bodyAndTitleTextsLayersPaddingRight;
    $titleAndBodyBgWidth = $titleAndBodySubtitleWidth;

    $titleSubtitleHeight = round(($params['titlefontsize'] * 1.10), 0, PHP_ROUND_HALF_UP);//textContainerHeight
    $titleLayerHeight = $titleSubtitleHeight + 20; //round($titleSubtitleHeight * 1.3, 0, PHP_ROUND_HALF_UP);
    $assFileContent =  getAssFileContent(
        $params['newscontenttitle'],
        $params['titlefontsize'],
        $titleFontName,
        $titleFontColor,
        $titleSubtitleTotalTime,
        $titleAndBodyBgWidth,
        $titleSubtitleHeight
    );
    file_put_contents($titleSubtitleFileName, $assFileContent);
    echo "File $titleSubtitleFileName created successfully.\n";

    // 3) Build news body text subtitle file
    $newsBodySubtitleFileName = $params['resultantfileslocation'] . $ffmpegfinalcmdfilenameInput . "_news-content-body.ass";
    $newsBodyFontName = "Rockford Sans Light";
    $newsBodyFontColor = $params['maintextcolor'];
	$bodyFontFilePath = $params['bodytextfontfile'];
    //$newsBodySubtitleTotalTime = 30;
    $newsBodySubtitleTotalTime = $textsTotalTime;
    $bodyTextSubtitleHeight = 150;//height of the box in which the body text will scroll
    $bodySubtitlePaddingTop = 10;

    $assFileContent =  getAssFileContent(
        $params['newscontentbody'],
        $params['newsbodyfontsize'],
        $newsBodyFontName,
        $newsBodyFontColor,
        $newsBodySubtitleTotalTime,
        $titleAndBodyBgWidth,
        $bodyTextSubtitleHeight,
		$bodyFontFilePath 
    );
    file_put_contents($newsBodySubtitleFileName, $assFileContent);
    echo "File $newsBodySubtitleFileName created successfully.\n";

    //double scape subtitle files in final ffmpeg command:
    $miniTitleSubtitleFilePathDoubleScaped = str_replace("\\\\", "\\\\\\\\", $miniTitleSubtitleFilePath);
    $miniTitleSubtitleFilePathDoubleScaped = str_replace(":", "\\\\:", $miniTitleSubtitleFilePathDoubleScaped);

    $titleSubtitleFilePathDoubleScaped = str_replace("\\\\", "\\\\\\\\", $titleSubtitleFileName);
    $titleSubtitleFilePathDoubleScaped = str_replace(":", "\\\\:", $titleSubtitleFilePathDoubleScaped);

    $bodySubtitleFilePathDoubleScaped = str_replace("\\\\", "\\\\\\\\", $newsBodySubtitleFileName);
    $bodySubtitleFilePathDoubleScaped = str_replace(":", "\\\\:", $bodySubtitleFilePathDoubleScaped);

    $filterComplex = "";
    // Building $filterComplex layer by layer

    // Rounding edges of the news mini-title container
    $filterComplex .= " [2]format=yuva420p,geq=lum='p(X,Y)':a='if(gt(abs(W/2-X),W/2-18)*gt(abs(H/2-Y),H/2-18),if(lte(hypot(18-(W/2-abs(W/2-X)),18-(H/2-abs(H/2-Y))),18),255,0),255)'[minititlecontainer];";

    // Rounding edges of the news main-text container
    $filterComplex .= " [1]format=yuva420p,geq=lum='p(X,Y)':a='if(gt(abs(W/2-X),W/2-26)*gt(abs(H/2-Y),H/2-26),if(lte(hypot(26-(W/2-abs(W/2-X)),26-(H/2-abs(H/2-Y))),26),255,0),255)'[newsbodycontainer];";

    echo "\n minitextSubtitleWidth=".$minitextSubtitleWidth.",minitextSubtitleHeight=".$minitextSubtitleHeight."\n";

    //contenedor de news-mini-title: (tiene q tener 64px de altura)
    $filterComplex .= " color=c={$params['minitextcontainergbcolor']}@1:s={$minitextSubtitleWidth}x{$minitextSubtitleHeight}:duration={$minititleSubtitleTotalTime},subtitles=" . $miniTitleSubtitleFilePathDoubleScaped . ":fontsdir={$params['fontsdir']}/[newsminititle];";

    // Creates news Title text layer
    $filterComplex .= " color=c={$params['maintextcontainerbgcolor']}@1:s={$titleAndBodySubtitleWidth}x{$titleLayerHeight}:duration={$titleSubtitleTotalTime},subtitles=" . $titleSubtitleFilePathDoubleScaped . ":fontsdir={$params['fontsdir']}/[newstitle];";

    // Creates news Body text layer
    $filterComplex .= " color=c={$params['maintextcontainerbgcolor']}@1:s={$titleAndBodySubtitleWidth}x{$bodyTextSubtitleHeight}:duration={$newsBodySubtitleTotalTime},subtitles=" . $bodySubtitleFilePathDoubleScaped . ":fontsdir={$params['fontsdir']}/[newsbody];";

    // Overlaps the news mini-title text layer over the mini-title container layer
    $filterComplex .= " [minititlecontainer][newsminititle]overlay=eval=init:x=20:y=05[newsminititlefull];";

    // Crops the final mini-title layer to isolate it from its background
    $filterComplex .= " [newsminititlefull]crop={$minitextContainerWidth}:{$minitextContainerHeight}:0:0[newsminititlefull];";

    // Overlaps the news body text layer over the main-text container
    $bodyTextLayerPositionY = $titleSubtitlePaddingTop + $titleLayerHeight + $bodySubtitlePaddingTop;
    $filterComplex .= " [newsbodycontainer][newsbody]overlay=eval=init:x={$bodyAndTitleTextsLayersPaddingLeft}:y={$bodyTextLayerPositionY}[newsbodyfull];";

    // Overlaps the news Title text layer over the main-text container
    $filterComplex .= " [newsbodyfull][newstitle]overlay=eval=init:x={$bodyAndTitleTextsLayersPaddingLeft}:y={$titleSubtitlePaddingTop}[newstitleandbodywithcontainer];";

    // Crops the final news main-text layer to isolate it from its background
    $filterComplex .= " [newstitleandbodywithcontainer]crop={$params['maintextcontainerwidth']}:250:0:0[newstitleandbodywithcontainer];";

    // Show and then hide the mini-title layer, using fade FX
    $filterComplex .= " [newsminititlefull]format=yuva420p,fade=t=in:st=4:d=1:alpha=1,fade=t=out:st=25:d=1:alpha=1[newsminititlefullwithfx];";

    // Show and then hide the main-text layer, using fade FX
    $fadeInAt = 3;
    $fadeOutAt = $textsTotalTime - $params['audiooutrolength'];
    $filterComplex .= " [newstitleandbodywithcontainer]format=yuva420p,fade=t=in:st={$fadeInAt}:d=1:alpha=1,fade=t=out:st={$fadeOutAt}:d=1:alpha=1[newstitleandbodywithcontainerwithfx];";

    // Use a new empty and transparent full screen layer, to add there there texts with its FXs. Start it by overlaping to it the final mini-title layer
    $minititleDistanceOverMainText = 15;
    $miniTitlePositionY = $params['maintextcontainerpositiony'] - $minitextContainerHeight - $minititleDistanceOverMainText;
    $filterComplex .= " [3][newsminititlefullwithfx]overlay=eval=init:x={$params['alltextcontainerspositionx']}:y={$miniTitlePositionY}[textsmaincontainer];";

    // Now overlap to it the final main-text layer
    $filterComplex .= " [textsmaincontainer][newstitleandbodywithcontainerwithfx]overlay=eval=init:x={$params['alltextcontainerspositionx']}:y={$params['maintextcontainerpositiony']}[textsmaincontainer];";

    // Now overlap the full-texts layer to the input video
    $filterComplex .= " [0][textsmaincontainer]overlay=eval=init:x=0:y=0[videoout];";

    //4) if audio is present, add the filters to the audio input
    if (!empty($params['audiofile'])) {
        $filterComplex .= " [4]adelay=3000|3000,dynaudnorm[audioout];";
    }

    $full_ffmpeg_cmd = "{$params['ffmpegpath']} -y -threads 1 ";
    $full_ffmpeg_cmd .= " -i \"{$params['backgroundvideofile']}\" ";
    $full_ffmpeg_cmd .= " -f lavfi -i color={$params['maintextcontainerbgcolor']}:size={$params['maintextcontainerwidth']}x250 ";
    $full_ffmpeg_cmd .= " -f lavfi -i color={$params['minitextcontainergbcolor']}:size={$minitextContainerWidth}x{$minitextContainerHeight} ";
    $full_ffmpeg_cmd .= " -f lavfi -i \"color=color=black@0.0:size={$params['videowidth']}x{$params['videoheight']},format=rgba\" ";

    //4) if audio is present, add the input audio file
    if (!empty($params['audiofile'])) {
        $full_ffmpeg_cmd .= "-i " . $params['audiofile'] . " ";
    }

    $full_ffmpeg_cmd .= "  -filter_complex \"{$filterComplex}\" ";
    $full_ffmpeg_cmd .= " -c:v libx264 -map [videoout] "; //-b:v 5M

    //4) if audio is present, add the mapping from the audio filters to the audio output
    if (!empty($params['audiofile'])) {
        $full_ffmpeg_cmd .= " -map [audioout] -c:a aac -strict experimental "; //-b:a 128k
    }

    $full_ffmpeg_cmd .= " -preset ultrafast -t {$textsTotalTime} {$params['videowithtextsfilename']} ";
	return $full_ffmpeg_cmd;
    //$ffmpegLogFilename = substr($outCmdExecutableFilename, 0, -3) . "log";
    //$full_ffmpeg_cmd .= " ^1^> {$ffmpegLogFilename} ^2^>^&^1";
    //$ffmpegCmd = "start /low /MIN cmd /c {$full_ffmpeg_cmd} ";
    //return $ffmpegCmd;
}

/**
 * Get duration in seconds of media file from ffmpeg
 * @param $file
 * @return bool|string
 */
function getDurationSeconds($ffmpegFullPath, $mediaFile){
    $dur = shell_exec($ffmpegFullPath . " -i ".$mediaFile." 2>&1");
    if(preg_match("/: Invalid /", $dur)){
        return false;
    }
    preg_match("/Duration: (.{2}):(.{2}):(.{2})/", $dur, $duration);
    if(!isset($duration[1])){
        return false;
    }
    $hours = $duration[1];
    $minutes = $duration[2];
    $seconds = $duration[3];
    return $seconds + ($minutes*60) + ($hours*60*60);
}

$params = getopt("", [
    "ffmpegpath::",
    "maintextcontainerbgcolor::",
    "maintextcolor::",
    "alltextcontainerspositionx::",
    "minitextcontainergbcolor::",
    "videowidth::",
    "videoheight::",
    "maintextcontainerwidth::",
    "maintextcontainerpositiony::",
    "minititlecontainerpositiony::",
    "fontsdir::",
    "minititlefontsize::",
    "titlefontsize::",
    "newsbodyfontsize::",
    "ffmpegpath::",
    "videowithtextsfilename::",
    "newscontentminititle::",
    "newscontenttitle::",
    "newscontentbody::",
    "backgroundvideofile::",
    "ffmpegfinalcmdfilename::",
    "resultantfileslocation::",
    "minitextcolor::",
    "mediainputfiles::",
    "mainvideototaltime::",
    "eachmediainputtime::",
    "audiofile::",
    "audiointrolength::",
    "audiooutrolength::",
	"minitextfontfile::",
	"bodytextfontfile::"
]);

$requiredParams = [
    "ffmpegpath",
    "newscontenttitle",
    "newscontentbody",
    "resultantfileslocation",
    "mediainputfiles"
];

// Validate mandatory params
foreach ($requiredParams as $param) {
    if (!isset($params[$param]) || strlen($params[$param]) === 0) {
        echo "Error: El parámetro '{$param}' es obligatorio y no se ha proporcionado o está vacío.\n";
        exit(1);
    }
}

$defaultValues = [
    "maintextcontainerbgcolor" => "#DDDBDD",
    "maintextcolor" => "#3A1E04",
    "minitextcolor" => "#FFFFFF",
    "minitextcontainergbcolor" => "#00223F",
	"minitextfontfile" => "RockfordSansLight.ttf",
	"bodytextfontfile" => "GalanoGrotesqueAltSemiBold.otf",
    "ffmpegfinalcmdfilename" => "ffmpegfinalcmd.bat",
	"minititlefontsize" => 30,
    "newsbodyfontsize" => 48,
//    "titlefontsize" => 36,
    "titlefontsize" => 30,
    "mainvideototaltime" => 40,
    "eachmediainputtime" => 10,
    "videoframerate" => 25,
    "audiointrolength" => 3,
    "audiooutrolength" => 4,
	"videowidth" => '1920',
	"videoheight" => '1080'
];
//$params['fontsdir']
$defaultValues["maintextcontainerwidth"] = round($defaultValues['videowidth'] * 0.83);
$defaultValues["maintextcontainerpositiony"] = round($defaultValues['videoheight'] * 0.65);
$defaultValues["alltextcontainerspositionx"] = round($defaultValues['videowidth'] * 0.078);
$defaultValues["minititlecontainerpositiony"] = round($defaultValues['videoheight'] * 0.574);

//sanitize some params which are used by ffmpeg and have strange issues with some chars:
// Reemplazar caracteres en $resultantfileslocation
// Agregar una barra invertida adicional al final solo si no está presente
if (!empty($params['resultantfileslocation']) && substr($params['resultantfileslocation'], -1) !== "\\") {
    $params['resultantfileslocation'] .= "\\";
}
$params['resultantfileslocation'] = str_replace("\\", "\\\\", $params['resultantfileslocation']);
$params['minitextfontfile'] = $params['fontsdir'] . "\\" . $defaultValues['minitextfontfile'];
$params['bodytextfontfile'] = $params['fontsdir'] . "\\" . $defaultValues['bodytextfontfile'];

// Reemplazar caracteres en $fontsdir
if (substr($params['fontsdir'], -1) !== "\\") {
    $params['fontsdir'] .= "\\";
}
$params['fontsdir'] = str_replace("\\", "\\\\\\\\", $params['fontsdir']);
$params['fontsdir'] = str_replace(":", "\\\\:", $params['fontsdir']);



//TODO improve esto porque no se entiende nada. como execFileWithFfmpegCmdToGenerateMainVideo no se usara mas, deberia ser suficiente con usar ffmpegfinalcmdfilenamePart1 para armar el filename de cada mp4
$ffmpegfinalcmdfilenameExtensionChars = 4;
$ffmpegfinalcmdfilenamePart1 = substr($params['ffmpegfinalcmdfilename'], 0, -$ffmpegfinalcmdfilenameExtensionChars);
$ffmpegfinalcmdfilenamePart2 = substr($params['ffmpegfinalcmdfilename'], -$ffmpegfinalcmdfilenameExtensionChars);
$execFileWithFfmpegCmdToGenerateMainVideo = $params['resultantfileslocation'] . $ffmpegfinalcmdfilenamePart1 . "1" . $ffmpegfinalcmdfilenamePart2;
$execFileWithFfmpegCmdForAddingTextsAndAudio = $params['resultantfileslocation'] . $ffmpegfinalcmdfilenamePart1 . "2" . $ffmpegfinalcmdfilenamePart2;
//$execFileWithFfmpegCmdForAddingAudio = $params['resultantfileslocation'] . $ffmpegfinalcmdfilenamePart1 . "3" . $ffmpegfinalcmdfilenamePart2;

$defaultValues["backgroundvideofile"] = substr($execFileWithFfmpegCmdToGenerateMainVideo, 0, -3) . "mp4";
$defaultValues["videowithtextsfilename"] = substr($execFileWithFfmpegCmdForAddingTextsAndAudio, 0, -3) . "mp4";

// Fill empty params with their default values
foreach ($defaultValues as $paramName => $paramDefaultValue) {
    if (!isset($params[$paramName]) || strlen($params[$paramName]) === 0) {
        echo "The param '{$paramName}' was not specified. Will use default value: '{$paramDefaultValue}'\n";
        $params[$paramName] = $paramDefaultValue;
    }
}

//step 1: get audiofile length and add intro and outro times to the video output file
if (!empty($params['audiofile'])) {
    $audioInputLength = getDurationSeconds($params['ffmpegpath'], $params['audiofile']);
    $params['mainvideototaltime'] = $params['audiointrolength'] + $audioInputLength + $params['audiooutrolength'];
}

echo "\n This is the full list of parameters and values that will be used to execute this script: \n";
foreach ($params as $paramName => $paramValue) {
    echo "\n '{$paramName}'='{$paramValue}'\n";
}

//step 2: generate video
$ffmpegCmdToGenerateMainVideo = getFfmpegCmdToGenerateMainVideo($params, $execFileWithFfmpegCmdToGenerateMainVideo, $defaultValues);
//file_put_contents($execFileWithFfmpegCmdToGenerateMainVideo, $ffmpegCmdToGenerateMainVideo);
//echo "\n File '".$execFileWithFfmpegCmdToGenerateMainVideo."' successfully generated. File content: ".$ffmpegCmdToGenerateMainVideo."\n";

//step 3: add texts and audio to the video
$ffmpegCmdForAddingTexts = getFfmpegCmdToAddTextsAndAudioToVideo($params, $execFileWithFfmpegCmdForAddingTextsAndAudio);
//file_put_contents($execFileWithFfmpegCmdForAddingTextsAndAudio, $ffmpegCmdForAddingTexts);
//echo "\n File '" . $execFileWithFfmpegCmdForAddingTextsAndAudio . "' successfully generated. File content: " . $ffmpegCmdForAddingTexts . "\n";

//step 4: generate the BAT file which will execute all FFMPEG commands sequencelly
$ffmpegCmds = array($ffmpegCmdToGenerateMainVideo,$ffmpegCmdForAddingTexts);
$batchFilename = $params['resultantfileslocation'] . $params['ffmpegfinalcmdfilename'];
$batchFileContent = getCmdToExecuteAllFfmpegCmds($params, $ffmpegCmds, $batchFilename);
file_put_contents($batchFilename, $batchFileContent);
echo "\n File '".$batchFilename."' successfully generated. File content: ".$batchFileContent."\n";

//TODO verify that step 4 is not neccessary and remove it.
//step 4: if audio was provided, add normalized audio to final video
/*
if (!empty($params['audiofile'])) {
    $ffmpegCmdForAddingAudio = getFfmpegCmdToAddAudioToVideo($params, $execFileWithFfmpegCmdForAddingAudio);
    file_put_contents($execFileWithFfmpegCmdForAddingAudio, $ffmpegCmdForAddingAudio);
    echo "\n File '" . $execFileWithFfmpegCmdForAddingAudio . "' successfully generated. File content: " . $ffmpegCmdForAddingAudio . "\n";
}
*/

?>
