<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

	include('util.php');
	parse_str($_SERVER['QUERY_STRING'], $params);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <title></title>
  	 <link rel="stylesheet" href="styles.css">
  	 <script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.js"></script>
  	 <script type='text/javascript' src='scripts.js'></script>
</head>
<body>
<?php

$data = getFilledWeatherDataObject();

?>
  <div class="veryMainContainer">
	<div class="widgetMainContainer">
	  <div class="currentInfoMainContainer">
		<div class="currentDataMask"></div>
		<img class="currentDataBgImg" src="<?php echo $data['currentDataBgImg']; ?>" />
		<div class="currentDataContainer">
		  <p class="cityName"><?php echo $data['cityName']; ?></p>
		  <p class="nowDatetime"><?php echo $data['nowDatetime']; ?></p>
		  <div class="currentInfoContainer">
			<div class="currentTempValuesContainer">
			  <p class="currentTemp"><?php echo $data['currentTemp']; ?><span class="currentTemp currentTempDegreeSymb">°</span></p>
			  <p class="currentRealFeel"><?php echo $data['currentRealFeel']; ?></p>
			</div>
			<div class="currentWeatherIconContainer"> <img class="currentWeatherIcon" src="<?php echo $data['currentWeatherIcon']; ?>" width="200" height="200" /> </div>
			<div class="currentExtraInfoContainer">
			  <div class="currentRainAmountContainer"> <img class="currentRainIcon" src="http://radiopower.com.ar/seba/Thermometer_Three_Quarter_Full-128.png" width="40" height="38" />
				<p class="currentRainAmountValue"><?php echo $data['currentRainAmountValue']; ?></p>
				<p class="currentRainAmountUnits">mm Hg</p>
			  </div>
			  <div class="currentHumidityAmountContainer"> <img class="currentHumidityIcon" src="http://radiopower.com.ar/seba/Measurement-Units-Humidity-icon_2.png" width="40" height="38" />
				<p class="currentHumidityAmountValue"><?php echo $data['currentHumidityAmountValue']; ?></p>
				<p class="currentHumidityAmountUnits">% humidity</p>
			  </div>
			  <div class="currentWindAmountContainer"> <img class="currentWindIcon" src="http://radiopower.com.ar/seba/Industry-Wind-Turbine-icon_2.png" width="40" height="38" />
				<p class="currentWindAmountValue"><?php echo $data['currentWindAmountValue']; ?></p>
				<p class="currentWindAmountUnits">km/h</p>
				<p class="currentWindDirection"><?php echo $data['currentWindDirection']; ?></p>
			  </div>
			  <div class="currentAtmPressureContainer"> <img class="currentPressureIcon" src="http://radiopower.com.ar/seba/barometer_v2.png" width="38" height="38" />
				<p class="currentAtmPressureValue"><?php echo $data['currentAtmPressureValue']; ?></p>
				<p class="currentAtmPressureUnits">hPa</p>
			  </div>
			</div>
			<div class="currentWeatherTextContainer">
			  <p class="currentWeatherText"><?php echo $data['currentWeatherText']; ?></p>
			</div>
		  </div>
		</div>
	  </div>
	  <div class="windForecastByHourContainer">
	  <?php foreach($data['windForecastByHour'] as $key => $periodValues){
		  echo '<div class="forecastOneDayContainer" style="border-radius:0px" alt="310">
			<p class="forecastDateAndTime">'.$periodValues['dateAndTime'].'</p>
			<img class="forecastWindDirIcon" src="http://radiopower.com.ar/seba/arrow-zero.png" width="50" height="50" style="transform:rotate('.$periodValues['windDirDegr'].'deg);-moz-transform:rotate('.$periodValues['windDirDegr'].'deg);-webkit-transform:rotate('.$periodValues['windDirDegr'].'deg);-o-transform:rotate('.$periodValues['windDirDegr'].'deg);-ms-transform:rotate('.$periodValues['windDirDegr'].'deg);" alt="'.$periodValues['windDirDegr'].'"/>
			<p class="forecastByHourWindDir">'.$periodValues['windDirTxt'].'</p>
			<p class="forecastByHourWindSpeed">'.$periodValues['windSpeed'].' km/h</p>
			<p class="forecastByHourTemp">'.$periodValues['temp'].'°</p>
		  </div>';
	  }
	?>
	  </div>
	  <div class="forecastByDayContainer">
	  <?php $i=1;
		foreach($data['forecastByDay'] as $key => $dayValues){
		   echo '
		   <div class="forecastOneDayContainer">
			 <div class="separationBarCol'.$i.'"></div>
			 <p class="forecastDayName">'.$dayValues['dayName'].'</p>
			 <img class="forecastDayIcon" src="'.$dayValues['dayIcon'].'" width="50" height="50"/>
			 <p class="forecastDayMaxTemp">'.$dayValues['dayMaxTemp'].'°</p>
			 <p class="forecastDayMinTemp">'.$dayValues['dayMinTemp'].'°</p>
		   </div>';
		   $i++;
		}
		?>
		</div>
	</div>
  </div>
</body>
</html>