<?php
//"D:\Software\RoboTask\Tasks\apps\php-7.4.32-nts-Win32-vc15-x64\php.exe" -c "D:\Software\RoboTask\Tasks\apps\php-7.4.32-nts-Win32-vc15-x64\php.ini" -e "D:\Software\RoboTask\Tasks\apps\notiFlashIA\getNewsVideoCmd.php"
//--ffmpegpath="D:\Software\ffmpeg\ffmpeg-2023-07-19-git-efa6cec759-full_build\bin\ffmpeg.exe" --mediainputfiles="D:\Software\trash\img_1.jpg,D:\Software\trash\img_2.png,D:\Software\trash\img_3.jpg"
//--audiofile="D:\Software\trash\flash17notiMensajero.mp3" --mainvideototaltime="46" --eachmediainputtime="6" --resultantfileslocation="D:\\Software\\trash\\"
//--maintextcontainerbgcolor="#DDDBDD" --maintextcolor="#3A1E04" --alltextcontainerspositionx="150" --minitextcontainergbcolor="#00223F"  --minitextcolor="#FFFFFF"
//--videowidth="1920" --videoheight="1080" --maintextcontainerwidth="1600" --maintextcontainerpositiony="700" --minititlecontainerpositiony="620"
//--fontsdir="D\\:\\\\Software\\\\trash\\\\fonts\\\\" --minititlefontsize="30" --titlefontsize="36" --bodyfontsize="48" --ffmpegfinalcmdfilename="ffmpegfinalcmd.bat"
//--newscontentminititle="ECOLOGÍA EN PINAMAR" --newscontenttitle="TRATAMIENTO DE LA FAUNA MARINA EN PINAMAR"
//--newscontentbody="LA FUNDACIÓN ECOLÓGICA PINAMAR SE REUNIÓ CON CONCEJALES PARA TRATAR EL TEMA DEL TRATAMIENTO DE LA FAUNA MARINA. LOS REPRESENTANTES DE LA FUNDACIÓN COMENTARON QUE DESDE HACE 20 AÑOS HAY UN CONVENIO CON FUNDACIÓN MUNDO MARINO PARA EL RESCATE DE LOS ANIMALES, PERO QUE MUCHAS VECES NO TIENEN CAMIONETAS DISPONIBLES. SE PROPUSO GENERAR UNA REUNIÓN CON EL DEPARTAMENTO DE ECOLOGÍA, PREFECTURA, SEGURIDAD EN PLAYA Y FUNDACIÓN ECOLÓGICA PARA TRANSFORMAR LO DIALOGADO EN UNA NORMATIVA O EN UN PROGRAMA. LA REUNIÓN SE LLEVARÁ A CABO EL MARTES 18 DE JULIO A LAS 11 DE LA MAÑANA."

function getAssFileContent($textToShow, $fontSize, $fontName, $fontColor, $totalTime, $textContainerWidth, $textContainerHeight) {
    //$textToShow = iconv(mb_detect_encoding($textToShow, mb_detect_order(), true), "UTF-8", $textToShow);
    $textToShow = iconv("UTF-8", "Windows-1252", $textToShow);

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
        $avgFontWidth = round($fontSize / 2);
        $avgCharsPerTextLine = round(($textContainerWidth / $avgFontWidth), 0, PHP_ROUND_HALF_UP);
        $amountOfTextLines = round((strlen($textToShow) / $avgCharsPerTextLine), 0, PHP_ROUND_HALF_UP); //$amountOfTextLines should be 9, for fontSize=48 and textContainerWidth=440
        $textTotalHeight = ($fontSize * $amountOfTextLines)-9;
        $fadeOutAt = $totalTime - 5;
        $assFileContent .= 'Dialogue: 0,0:00:00.00,0:00:'.$staticTextDuration.'.00,Default,,0,0,0,,{\an1}{\t(0,'.$staticTextDuration.'000,\fad(1000,0)\move(0,'.$textTotalHeight.',0,'.$textTotalHeight.'))}'.$textToShow."\n";//432 para fontsize=48
        $assFileContent .= 'Dialogue: 0,0:00:'.$staticTextDuration.'.00,0:00:'.$totalTime.'.00,Default,,0,0,0,,{\an1}{\\t(0,'.$fadeOutAt.'000,\move(0,'.$textTotalHeight.',0,0)\\fad(0,1000))}'.$textToShow."\n";
//        $assFileContent .= 'Dialogue: 0,0:00:00.00,0:00:'.$totalTime.'.00,Default,,0,0,0,Scroll up;-150;0;50;fadeawayheight,{\an1}'.$textToShow."\n";
    } else {
        $lineHeight = round(($textContainerHeight + $fontSize) / 2);
        $assFileContent .= "Dialogue: 0,0:00:00.00,0:00:".$totalTime.".00,Default,,0,0,0,,{\an1}{\\t(0,".$totalTime."000,\\fad(2000,0)\\move(0,".$lineHeight.",0,".$lineHeight.")\\fad(0,2000))}$textToShow\n";
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

function getFfmpegCmdToGenerateMainVideo($params, $outCmdExecutableFilename, $defaultValues) {
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
        . " -c:v libx264 -pix_fmt yuv420p " . substr($outCmdExecutableFilename, 0, -3) . "mp4";

    $ffmpegLogFilename = substr($outCmdExecutableFilename, 0, -3) . "log";
    $full_ffmpeg_cmd .= " ^1^> {$ffmpegLogFilename} ^2^>^&^1";
    $ffmpegCmd = "start /low /MIN cmd /c {$full_ffmpeg_cmd} ";
    return $ffmpegCmd;
}

function getFfmpegCmdToAddAudioToVideo($params, $outCmdExecutableFilename) {
    //ffmpeg -i myVideo.mp4 --normalization-type peak --target-level 0  -c:a aac -b:a 192k -ext mp4
    $full_ffmpeg_cmd = "{$params['ffmpegpath']} -y -threads 1 ";
    $full_ffmpeg_cmd .= "-i " . $params['videowithtextsfilename'] . " ";
    $full_ffmpeg_cmd .= "-i " . $params['audiofile'] . " ";
    $full_ffmpeg_cmd .= "-c:v copy -c:a aac -strict experimental -map 0 -map 1 -shortest ";
    $full_ffmpeg_cmd .= "-af \"adelay=" . $params['audiointrolength'] . "000|" . $params['audiointrolength'] . "000,dynaudnorm\" ";
    $full_ffmpeg_cmd .= substr($outCmdExecutableFilename, 0, -3) . "mp4";
    $ffmpegLogFilename = substr($outCmdExecutableFilename, 0, -3) . "log";
    $full_ffmpeg_cmd .= " ^1^> {$ffmpegLogFilename} ^2^>^&^1";
    $ffmpegCmd = "start /low /MIN cmd /c {$full_ffmpeg_cmd} ";
    return $ffmpegCmd;
}

function getFfmpegCmdToAddTextsAndAudioToVideo($params, $outCmdExecutableFilename) {
    //$textsTotalTime = $params['mainvideototaltime'] - 4;
    $textsTotalTime = $params['mainvideototaltime'];

    // Build given texts into subtitle files
    // 1) Build minititle subtitle file. //TODO: make this optional, to only build it if mini-title text is present
    $miniTitleFileName = "news-mini-title.ass";
    $miniTitleSubtitleFilePath = $params['resultantfileslocation'] . $miniTitleFileName;
    $minititleFontName = "Rockford Sans Light";
    $minititleFontColor = $params['minitextcolor'];
    //$minititleSubtitleTotalTime = 30;
    $minititleSubtitleTotalTime = $textsTotalTime;
    //$minitextBgWidth = "440";
    $minitextBgWidth = round((round($params['minititlefontsize'] / 2) * strlen($params['newscontentminititle'])) * 1.20);
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
    $titleSubtitleFileName = $params['resultantfileslocation']."news-content-title.ass";
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
    $newsBodySubtitleFileName = $params['resultantfileslocation']."news-content-body.ass";
    $newsBodyFontName = "Rockford Sans Light";
    $newsBodyFontColor = $params['maintextcolor'];
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
        $bodyTextSubtitleHeight
        );
    file_put_contents($newsBodySubtitleFileName, $assFileContent);
    echo "File $newsBodySubtitleFileName created successfully.\n";

    $filterComplex = "";
    // Building $filterComplex layer by layer

    // Rounding edges of the news mini-title container
    $filterComplex .= " [2]format=yuva420p,geq=lum='p(X,Y)':a='if(gt(abs(W/2-X),W/2-18)*gt(abs(H/2-Y),H/2-18),if(lte(hypot(18-(W/2-abs(W/2-X)),18-(H/2-abs(H/2-Y))),18),255,0),255)'[minititlecontainer];";

    // Rounding edges of the news main-text container
    $filterComplex .= " [1]format=yuva420p,geq=lum='p(X,Y)':a='if(gt(abs(W/2-X),W/2-26)*gt(abs(H/2-Y),H/2-26),if(lte(hypot(26-(W/2-abs(W/2-X)),26-(H/2-abs(H/2-Y))),26),255,0),255)'[newsbodycontainer];";

    echo "\n minitextSubtitleWidth=".$minitextSubtitleWidth.",minitextSubtitleHeight=".$minitextSubtitleHeight."\n";

    //contenedor de news-mini-title: (tiene q tener 64px de altura)
    $filterComplex .= " color=c={$params['minitextcontainergbcolor']}@1:s={$minitextSubtitleWidth}x{$minitextSubtitleHeight}:duration={$minititleSubtitleTotalTime},subtitles=" . $miniTitleFileName . ":fontsdir={$params['fontsdir']}/[newsminititle];";

    // Creates news Title text layer
    $filterComplex .= " color=c={$params['maintextcontainerbgcolor']}@1:s={$titleAndBodySubtitleWidth}x{$titleLayerHeight}:duration={$titleSubtitleTotalTime},subtitles=news-content-title.ass:fontsdir={$params['fontsdir']}/[newstitle];";

    // Creates news Body text layer
    $filterComplex .= " color=c={$params['maintextcontainerbgcolor']}@1:s={$titleAndBodySubtitleWidth}x{$bodyTextSubtitleHeight}:duration={$newsBodySubtitleTotalTime},subtitles=news-content-body.ass:fontsdir={$params['fontsdir']}/[newsbody];";

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
    $ffmpegLogFilename = substr($outCmdExecutableFilename, 0, -3) . "log";
    $full_ffmpeg_cmd .= " ^1^> {$ffmpegLogFilename} ^2^>^&^1";
    $ffmpegCmd = "start /low /MIN cmd /c {$full_ffmpeg_cmd} ";
    return $ffmpegCmd;
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
    "audiooutrolength::"
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
    "alltextcontainerspositionx" => '380',
    "maintextcontainerwidth" => '990',
    "maintextcontainerpositiony" => '700',
    "ffmpegfinalcmdfilename" => "ffmpegfinalcmd.bat",
    "minititlefontsize" => 30,
    "newsbodyfontsize" => 48,
    "titlefontsize" => 36,
    "mainvideototaltime" => 40,
    "eachmediainputtime" => 10,
    "videoframerate" => 25,
    "audiointrolength" => 3,
    "audiooutrolength" => 4
];

$ffmpegfinalcmdfilenameExtensionChars = 4;
$ffmpegfinalcmdfilenamePart1 = substr($params['ffmpegfinalcmdfilename'], 0, -$ffmpegfinalcmdfilenameExtensionChars);
$ffmpegfinalcmdfilenamePart2 = substr($params['ffmpegfinalcmdfilename'], -$ffmpegfinalcmdfilenameExtensionChars);
$execFileWithFfmpegCmdToGenerateMainVideo = $params['resultantfileslocation'] . $ffmpegfinalcmdfilenamePart1 . "1" . $ffmpegfinalcmdfilenamePart2;
$execFileWithFfmpegCmdForAddingTexts = $params['resultantfileslocation'] . $ffmpegfinalcmdfilenamePart1 . "2" . $ffmpegfinalcmdfilenamePart2;
$execFileWithFfmpegCmdForAddingAudio = $params['resultantfileslocation'] . $ffmpegfinalcmdfilenamePart1 . "3" . $ffmpegfinalcmdfilenamePart2;

$defaultValues["backgroundvideofile"] = substr($execFileWithFfmpegCmdToGenerateMainVideo, 0, -3) . "mp4";
$defaultValues["videowithtextsfilename"] = substr($execFileWithFfmpegCmdForAddingTexts, 0, -3) . "mp4";

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

//step 2: generate video
$ffmpegCmdToGenerateMainVideo = getFfmpegCmdToGenerateMainVideo($params, $execFileWithFfmpegCmdToGenerateMainVideo, $defaultValues);
file_put_contents($execFileWithFfmpegCmdToGenerateMainVideo, $ffmpegCmdToGenerateMainVideo);
echo "\n File '".$execFileWithFfmpegCmdToGenerateMainVideo."' successfully generated. File content: ".$ffmpegCmdToGenerateMainVideo."\n";

//step 3: add texts to the video
$ffmpegCmdForAddingTexts = getFfmpegCmdToAddTextsAndAudioToVideo($params, $execFileWithFfmpegCmdForAddingTexts);
file_put_contents($execFileWithFfmpegCmdForAddingTexts, $ffmpegCmdForAddingTexts);
echo "\n File '" . $execFileWithFfmpegCmdForAddingTexts . "' successfully generated. File content: " . $ffmpegCmdForAddingTexts . "\n";

//step 4: if audio was provided, add normalized audio to final video
if (!empty($params['audiofile'])) {
    $ffmpegCmdForAddingAudio = getFfmpegCmdToAddAudioToVideo($params, $execFileWithFfmpegCmdForAddingAudio);
    file_put_contents($execFileWithFfmpegCmdForAddingAudio, $ffmpegCmdForAddingAudio);
    echo "\n File '" . $execFileWithFfmpegCmdForAddingAudio . "' successfully generated. File content: " . $ffmpegCmdForAddingAudio . "\n";
}

?>
