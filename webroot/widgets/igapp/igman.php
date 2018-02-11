<?php

set_time_limit(0);
date_default_timezone_set('UTC');

require __DIR__.'/../vendor/autoload.php';

// "C:\Software\wamp64\bin\php\php7.0.23\php.exe" -e "C:\Software\wamp64\www\igapp\igman.php" -u usename -p pass --videofilename "C:\Temp\video.mp4" --captiontext "#Text of my post at @myaccount"

/////// CONFIG ///////

$shortparams="u:p:";
$longparams=array("videofilename:","captiontext:");
$opts = getopt($shortparams,$longparams);
//var_dump($opts);

if (array_key_exists('u',$opts) &&  !empty($opts['u'])){
	$username=$opts['u'];
}else{
	echo "ERROR--EMPTY_USERNAME Field name: u";
	die();
}

if (array_key_exists('p',$opts) &&  !empty($opts['p'])){
	$password=$opts['p'];
}else{
	echo "ERROR--EMPTY_PASSWORD Field name: p";
	die();
}

if (array_key_exists('videofilename',$opts) &&  !empty($opts['videofilename'])){
	$videoFilename=$opts['videofilename'];
}else{
	echo "ERROR--EMPTY_VIDEO_FILENAME Field name: videofilename";
	die();
}

if (array_key_exists('captiontext',$opts) &&  !empty($opts['captiontext'])){
	$captionText=utf8_encode($opts['captiontext']);
}else{
	echo "ERROR--EMPTY_VIDEO_CAPTION_TEXT Field name: captiontext";
	die();
}

//$username = '';
//$password = '';
$debug = true;
$truncatedDebug = false;
//////////////////////

/////// MEDIA ////////
//$videoFilename = '';
//$captionText = '';
//////////////////////

$ig = new \InstagramAPI\Instagram($debug, $truncatedDebug);

try {
    $ig->login($username, $password);
} catch (\Exception $e) {
    echo 'Something went wrong: '.$e->getMessage()."\n";
    exit(0);
}

try {
    // Note that all video upload functions perform some automatic chunk upload
    // retries, in case of failing to upload all video chunks to Instagram's
    // server! Uploads therefore take longer when their server is overloaded.
    $ig->timeline->uploadVideo($videoFilename, ['caption' => $captionText]);
} catch (\Exception $e) {
    echo 'Something went wrong: '.$e->getMessage()."\n";
}
