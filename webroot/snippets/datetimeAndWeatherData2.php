<?php
	header('Content-Type: text/html; charset=utf-8');

	header("Access-Control-Allow-Origin: *");
	//header("Access-Control-Allow-Origin: http://powerhd.com.ar");
	//$_SERVER['QUERY_STRING'] = "&action=getcurrentweatherdata&style=font:%20bold%2036px%20monospace;%20color:%20lightgray;%20display:%20inline-block;%20border-radius:%205px;%20padding:%202px%205px;background-color:%20rgba(0,0,0,0.5);&format=DD/MM/YYYY%20HH:mm";

	include('../widgets/weather/util.php');

	$outMsg="NO MESSAGE";
	$outStatusCode=500;
	$outData=null;

	function printResultInJson(){
		global $outMsg, $outStatusCode, $outData;
		$arr = array('statusCode' => $outStatusCode, 'msg' => utf8_encode($outMsg), 'outData' => json_encode($outData)); //json_encode() will convert to null any non-utf8 String
		$out = json_encode($arr);
		$out = str_replace("\\\\\\", "", $out);
		echo $out;
	}

	parse_str($_SERVER['QUERY_STRING'], $params);

	$action=isset($_GET['action']) ? $_GET['action'] : $params['action'];

	if ($action=='getcurrentweatherdata' ) {
		$outData = getSpreadSheetDataIntoArray('https://script.google.com/macros/s/AKfycbw9_xKBnH8kOmexyvmo4kXNIOYnPCKtuisTgJ7VkFYyCaRWQGf1XqiFFF6FIqaxHQwv/exec');
		if ($outData){
			$nullItems=0;
			foreach($outData as $item){
				if ($item==null){
					$nullItems++;
				}
			}
			if ($nullItems<4){
				$outStatusCode="200";
				$outMsg="DONE!";
			}else{
				$outMsg="Many null items, something went wrong";
			}
		}else{
			$outMsg='getCurrentWeatherData() returned null';
		}
		//echo json_encode($outData);die();
		printResultInJson();
		die();
	}

?>

<html>
<head>
  <meta charset="UTF-8">
  <title>Power - Clock and Weather Data</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js" ></script>
  <script src="https://code.jquery.com/jquery-3.3.1.min.js" ></script>
  <script src="../js/vendor/jQuery.scrollText.js" ></script>
  <style>
	#output,#weatherDataContainer{
		float:left;
		letter-spacing: -1.5px;
	}
	.weatherDataContainer{
		width: 270px;
		height: 42px;
		line-height: 42px;
		overflow: Hidden;
	}
	.weatherDataContainer ul li {
		list-style-type: none;
	}
	.weatherDataContainer ul{
		margin-left:-20px;
	}
	.currentWeatherDataItem{

	}
	.weatherDataScrolling {
	 width:150px;
	 height:32px;
	 overflow: hidden;
	 position: relative;
	}
  </style>
</head>

<body translate="no">
<div id="mainContainer">
	<div id="output">29/01/20XX 16:58:02 </div>
	<div style="float:left">&nbsp;|</div>
	<div id="weatherDataContainer" class="weatherDataContainer">
		  <ul>
			<li>T:21°</li>
			<li>ST:24.3°</li>
			<li>H:71%</li>
			<li>V:24km/h S</li>
			<li>P:1024 hPA</li>
			<li>L:12mm</li>
		  </ul>
		</div>
	</div>
</div>
  <script>
// https://stackoverflow.com/questions/901115/how-can-i-get-query-string-values-in-javascript
/* >>> get time data: */
var urlParams;
(function () {
    var match,
        pl     = /\+/g,  // Regex for replacing addition symbol with a space
        search = /([^&=]+)=?([^&]*)/g,
        decode = function (s) { return decodeURIComponent(s.replace(pl, " ")); },
        query  = window.location.search.substring(1);

    urlParams = {};
    while (match = search.exec(query))
       urlParams[decode(match[1])] = decode(match[2]);
})();

var output = document.getElementById("output");
var mainContainer = document.getElementById("mainContainer");
if (urlParams["style"]) mainContainer.setAttribute("style", urlParams["style"]);
if (urlParams["bodyStyle"]) document.body.setAttribute("style", urlParams["bodyStyle"]);

var c;
setInterval(
c = function() {
    output.innerText = moment().format(urlParams["format"] || '');
}, 1000);
c();

/* >>> get weather data: */
function getWeatherInfo() {
	var weatherString="T:21° ST:22°";
  $.ajax( "datetimeAndWeatherData2.php?action=getcurrentweatherdata" )
  .done(function(data) {
	  var obj = JSON.parse(data);
	  if (obj.statusCode == "200"){
		  var currentweatherObj = JSON.parse(obj.outData);
		  weatherString = "";
		  weatherString += "<li class=\"currentWeatherDataItem\">T:"+currentweatherObj.temperature+"°</li>";
		  weatherString += "<li>ST:"+currentweatherObj.realfeel+"°</li>";
		  weatherString += "<li>H:"+currentweatherObj.humedity+"%</li>";
		  weatherString += "<li>V:"+Math.round(currentweatherObj.windSpeed)+" km/h "+currentweatherObj.windDirection+"</li>";
		  weatherString += "<li>P:"+Math.round(currentweatherObj.pressure)+" hPa</li>";
		  weatherString += "<li>L:"+currentweatherObj.fallenRain+" mm</li>";
		  $('#weatherDataContainer ul').eq(0).html(weatherString);
		  $('#weatherDataContainer ul').eq(1).html(weatherString);
	  }else{
		  weatherString = "<li>EN VIVO</li>";
		  $('#weatherDataContainer ul').eq(0).html(weatherString);
		  $('#weatherDataContainer ul').eq(1).html(weatherString);
	  }
  })
  .fail(function(e) {
	weatherString = "<li>EN VIVO!</li>";
	$('#weatherDataContainer ul').eq(0).html(weatherString);
	$('#weatherDataContainer ul').eq(1).html(weatherString);
	console.log("ERROR! Details:");
	console.log(e);
  })
  .always(function() {
	// schedule the next request *only* when the current one is complete:
    setTimeout(getWeatherInfo, 900000);//900000=900seg=15min
  });
}

// schedule the first invocation:
setTimeout(getWeatherInfo, 5000);

//text scroller init
$(".weatherDataContainer").scrollText({
  'container': '.weatherDataContainer',
  'sections': 'li', // child elements
  'duration': 6000,
  'loop': true,
  'currentClass': 'currentWeatherDataItem',// CSS appended to the current item
  'direction': 'up'
});

  </script>


</body></html>
