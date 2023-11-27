<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

//example: https://radiopower.com.ar/powerhd/webroot/snippets/getNewsInfobae.php?action=getinfobaenews&sections=economia,deportes&imagesmin=2&maxage=24
	header('Content-Type: text/html; charset=utf-8');
	header("Access-Control-Allow-Origin: *");
	include("simple_html_dom_v1.9.1.php");

	date_default_timezone_set('America/Argentina/Buenos_Aires');

	$outMsg = "NO MESSAGE";
	$outStatusCode = 500;
	$outData = null;

	function isNewsWithinMaxAge($newsDate, $maxAge) {
		// Convert news date to timestamp
		$newsTimestamp = strtotime($newsDate);

		// Calculate current timestamp
		$currentTimestamp = time();

		// Calculate age in hours
		$ageInHours = ($currentTimestamp - $newsTimestamp) / 3600;

		// Check if the age is within the specified max age
		return $ageInHours <= $maxAge;
	}

	function printResultInJson() {
		global $outMsg, $outStatusCode, $outData;
		$arr = array(
			'statusCode' => $outStatusCode,
			'msg' => utf8_encode($outMsg),
			'outData' => json_encode($outData)
		); //json_encode() will convert to null any non-utf8 String
		$out = json_encode($arr);
		//$out = str_replace("\\\\\\", "", $out);
		echo $out;
		//echo json_encode($outData);
	}

	parse_str($_SERVER['QUERY_STRING'], $params);

	function cleanNewsDescription($encodedContent) {
		// Remove HTML tags from content
		$newsFullDescripcion = strip_tags($encodedContent);
					
		// Identify and store CSS classes in the content
		$cssClasses = [];
		if (preg_match_all('/\.\s*([a-zA-Z_-][a-zA-Z\d_-]*)\s*\{/i', $newsFullDescripcion, $matches)) {
			$cssClasses = $matches[1];
		}

		// Remove identified CSS classes
		foreach ($cssClasses as $cssClass) {
			$newsFullDescripcion = preg_replace('/\.' . preg_quote($cssClass, '/') . '\s*{[^}]+}/i', '', $newsFullDescripcion);
		}

		// Remover saltos de línea múltiples y dejar solo uno si hay más de 3
		$newsFullDescripcion = preg_replace('/\n{3,}/', "\n", $newsFullDescripcion);

		// Remover URLs relativas
		$newsFullDescripcion = preg_replace('/\/[a-zA-Z\d\/_-]+/', '', $newsFullDescripcion);

		return $newsFullDescripcion;
	}

	function getRecentNews($rssUrl, $sections, $imagesMin, $maxAge) {
		$rssContent = file_get_contents($rssUrl);
		$rss = simplexml_load_string($rssContent);

		$filteredNews = [];

		foreach ($rss->channel->item as $item) {
			$link = (string) $item->link;
			$encodedContent = (string) $item->children('content', true)->encoded;
			$newsDate = dateConvert((string) $item->pubDate);
			
			// Check if the news is within the specified max age
			if (isNewsWithinMaxAge($newsDate, $maxAge)) {
				foreach ($sections as $section) {
					if (strpos($link, "/" . $section . "/") !== false) {
						$dom = new simple_html_dom();
						$dom->load($encodedContent);

						// Extract image URLs
						$newsImages = [];
						foreach ($dom->find('img') as $img) {
							$newsImages[] = $img->src;
						}

						// Aplicar la limpieza del contenido
						$newsFullDescripcion = cleanNewsDescription($encodedContent);

						// Check if the number of images meets the minimum requirement
						if (count($newsImages) >= $imagesMin) {
							$filteredNews[] = [
								'news_date' => dateConvert((string) $item->pubDate),
								'news_category' => strtoupper($section),
								'news_title' => (string) $item->title,
								'news_images_urls' => $newsImages,
								'news_short_descripcion' => (string) $item->description,
								'news_full_descripcion' => trim(html_entity_decode($newsFullDescripcion)),
								'news_link' => $link
							];
						}
					}
				}
			}
		}

		return $filteredNews;
	}

	function dateConvert($originalDate) {
		// Convert the date format
		$newDate = date('Y-m-d H:i:s', strtotime($originalDate));
		return $newDate;
	}

	$action = $_GET['action'];

	if ($action == 'getinfobaenews' && isset($_GET['sections']) && isset($_GET['imagesmin']) && isset($_GET['maxage'])) {
		$rssUrl = "https://www.infobae.com/argentina-rss.xml";

		$sections = explode(',', $_GET['sections']);
		$imagesMin = intval($_GET['imagesmin']);
		$maxAge = intval($_GET['maxage']);
		$outData = getRecentNews($rssUrl, $sections, $imagesMin, $maxAge);

		if ($outData) {
			$outStatusCode = "200";
			$outMsg = "DONE!";
		} else {
			$outMsg = 'No matching news found for the specified sections or imagesmin criteria.';
		}

		printResultInJson();
	} else {
		$outMsg = 'Invalid action, missing sections, or imagesmin parameter.';
		printResultInJson();
	}
?>
