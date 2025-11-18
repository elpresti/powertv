
function adminSMNpinaForecastData(){
  var ss = SpreadsheetApp.getActiveSpreadsheet();
  var smnPinaSheet = ss.getSheetByName("smnPina2023");
  getAndSetSMN2024pinaForecastData(ss,smnPinaSheet);
  smnForecastBuildOneLineInfo(ss,smnPinaSheet);
}

function logSmn2023PinaTime(lastTry,ss){
  if (ss==null){
     return;
   }
   var now = new Date();
   //var twoHoursFromNow = new Date(now.getTime() + (2 * 60 * 60 * 1000));
   //----windSheet.getRange(24,2).setValue(now.getTime()); //set last try
   var timeStr=now.toLocaleDateString() + ", " + now.toLocaleTimeString(); //now.toJSON().slice(0,10)
   if (lastTry != null){
      if (lastTry == true){
         var dateVar = ss.getRangeByName('lastTrySMN2023dateString'); //another readable date format
         if (dateVar != null) {
           dateVar.setValue(timeStr);
         }
         dateVar = ss.getRangeByName('lastTrySMN2023dateLong'); //another readable date format
         if (dateVar != null){
           dateVar.setValue(now.getTime());
         }
      }else{
        var dateVar = ss.getRangeByName('lastSuccessSMN2023dateString'); //another readable date format
        if (dateVar != null) {
          dateVar.setValue(timeStr);
        }
        dateVar = ss.getRangeByName('lastSuccessSMN2023dateLong'); //another readable date format
        if (dateVar != null) {
          dateVar.setValue(now.getTime());
        }
      }
   }
}

function clearSMN2023pinaForecastData(smn2023PinaSheet){
  var blankArray = new Array(35);
   for (var i = 0; i < 35; i++) {
     blankArray[i] = new Array(8);
     for (var w = 0; w < 6; w++) {
       blankArray[i][w]="NULL";
     }
   }
   smn2023PinaSheet.getRange(2,2,35,8).setValues(blankArray);
}

function getAndSetSMN2024pinaForecastData(ss,smnPinaSheet){
   if (ss==null){
     var ss = SpreadsheetApp.getActiveSpreadsheet();
   }
   if (smnPinaSheet==null){
     var smnPinaSheet = ss.getSheetByName("smnPina2023");
   }
   //Browser.msgBox(forecastData);
   logSmn2023PinaTime(lastTry=true,ss);
   
   try {
     //var urlSMNpinaForecastWS = "http://radiopower.com.ar/powerhd/webroot/widgets/weather/util/getSMNinfo2023.php?action=getforecast&locationId=4298";
     var urlSMNpinaForecastWS = "https://radiopower.com.ar/powerhd/webroot/widgets/weather/util/getSMN2024server.php?action=get";
     var response = UrlFetchApp.fetch(urlSMNpinaForecastWS);
     var json = response.getContentText();
     //Logger.log(json);
     var data = JSON.parse(json);
     var forecDays = data.outData;
     if (forecDays != null  &&  forecDays.length>3){
       clearSMN2023pinaForecastData(smnPinaSheet);
       var initColNumber=2;
       var initRowNumber=1;//era 2
       var colNum=initColNumber;
       var rowNum=initRowNumber;
       for(var dayNumKey in forecDays){
          //Logger.log("key="+dayKey+", value="+forecDays[dayKey]);
          //smnPinaSheet.getRange(rowNum,colNum).setValue(dayNumKey+1);//esto estaba descomentado
          rowNum++;
          for(var dayDataKey in forecDays[dayNumKey]){
             smnPinaSheet.getRange(rowNum,1).setValue(dayDataKey);
             smnPinaSheet.getRange(rowNum,colNum).setValue(forecDays[dayNumKey][dayDataKey]);
             rowNum++;
          }
          rowNum=initRowNumber;
          colNum++;
       }
       logSmn2023PinaTime(lastTry=false,ss);
     }else{
       clearSMN2023pinaForecastData(smnPinaSheet);
       smnPinaSheet.getRangeByName('errorMsgSmn2023').setValue("getAndSetSMN2024pinaForecastData() ERROR! Response of WS was not the expected");//set error message
     }
   } catch (e) {
      var now = new Date();
      var errorInfo="Time: " + now.toLocaleDateString() + ", " + now.toLocaleTimeString() + "\r\nMessage: " + e.message + "\r\nFile: " + e.fileName + "\r\nLine: " + e.lineNumber;
      //MailApp.sendEmail("elpresti@gmail.com", "getAndSetSMN2024pinaForecastData() Error report", e.message);
      smnPinaSheet.getRangeByName('errorMsgSmn2023').setValue(errorInfo);
      clearSMN2023pinaForecastData(smnPinaSheet);
   }
}

function smnForecastBuildOneLineInfo(spreadsheet, sheet) {
  var paramsLastRow = spreadsheet.getRangeByName("paramsLastRow").getRow();
  var dataRange = sheet.getRange("A1:H" + paramsLastRow);
  var data = dataRange.getValues();

  var dayWeatherOneLineRow = smnForecastBuildOneLineFindRowIndex(data, "dayWeatherOneLine");//aca a veces falla. Tal vez xq le pasa un array en vez de un objeto. Habria q contemplar el caso
  var earlyMorningWeatherOneLineRow = smnForecastBuildOneLineFindRowIndex(data, "earlyMorningWeatherOneLine");
  var morningWeatherOneLineRow = smnForecastBuildOneLineFindRowIndex(data, "morningWeatherOneLine");
  var afternoonWeatherOneLineRow = smnForecastBuildOneLineFindRowIndex(data, "afternoonWeatherOneLine");
  var nightWeatherOneLineRow = smnForecastBuildOneLineFindRowIndex(data, "nightWeatherOneLine");

  for (var i = 1; i < data[0].length; i++) {
    var dayWeatherOneLine = smnForecastBuildWeatherOneLineString(data, i, ["date", "dayweekName", "minTemp", "maxTemp", "minHumidity"]);
    var earlyMorningWeatherOneLine = smnForecastBuildWeatherOneLineString(data, i, ["earlyMorningDescription", "earlyMorningRainProbRange", "earlyMorningWindDirection", "earlyMorningSpeedRange", "earlyMorningRain06h"]);
    var morningWeatherOneLine = smnForecastBuildWeatherOneLineString(data, i, ["morningDescription", "morningRainProbRange", "morningWindDirection", "morningSpeedRange", "morningRain06h"]);
    var afternoonWeatherOneLine = smnForecastBuildWeatherOneLineString(data, i, ["afternoonDescription", "afternoonRainProbRange", "afternoonWindDirection", "afternoonSpeedRange", "afternoonRain06h"]);
    var nightWeatherOneLine = smnForecastBuildWeatherOneLineString(data, i, ["nightDescription", "nightRainProbRange", "nightWindDirection", "nightSpeedRange", "nightRain06h"]);

    if (dayWeatherOneLineRow !== -1) {
      sheet.getRange(dayWeatherOneLineRow + 1, i + 1, 1, 1).setValue(dayWeatherOneLine);
    }
    if (earlyMorningWeatherOneLineRow !== -1) {
      sheet.getRange(earlyMorningWeatherOneLineRow + 1, i + 1, 1, 1).setValue(earlyMorningWeatherOneLine);
    }
    if (morningWeatherOneLineRow !== -1) {
      sheet.getRange(morningWeatherOneLineRow + 1, i + 1, 1, 1).setValue(morningWeatherOneLine);
    }
    if (afternoonWeatherOneLineRow !== -1) {
      sheet.getRange(afternoonWeatherOneLineRow + 1, i + 1, 1, 1).setValue(afternoonWeatherOneLine);
    }
    if (nightWeatherOneLineRow !== -1) {
      sheet.getRange(nightWeatherOneLineRow + 1, i + 1, 1, 1).setValue(nightWeatherOneLine);
    }
  }
}

function smnForecastBuildOneLineFindRowIndex(data, searchValue) {
  var outputRowIndex = -1;
  for (var currentRowIndex in data) {
    var currentElement = data[currentRowIndex];
    if (currentElement[0] === searchValue) {
      outputRowIndex = currentRowIndex;
      break;
    }
  }
  return parseInt(outputRowIndex);
}

function smnForecastBuildWeatherOneLineString(data, index, fields) {
  var rowResult = "";
  fields.forEach(function (field, fieldIndex) {
    var rowIndex = smnForecastBuildOneLineFindRowIndex(data, field);
    var value = rowIndex !== -1 ? data[rowIndex][index] : null;
    if (value !== null && value.toString().length > 0) {
      switch (field) {
        case "minTemp":
        case "maxTemp":
          value += "°C";
          break;
        case "minHumidity":
          var maxHumidityRow = smnForecastBuildOneLineFindRowIndex(data, "maxHumidity");
          if (maxHumidityRow !== -1 && value.toString.length > 0) {
            value += "-" + data[maxHumidityRow][index] + "%";
          } else {
            value += "%";
          }
          field = "humidityRange";
          break;
        case "nightRainProbRange":
        case "afternoonRainProbRange":          
        case "morningRainProbRange":
        case "earlyMorningRainProbRange":
          value += "%";
          break;
        case "nightSpeedRange":
        case "afternoonSpeedRange":
        case "morningSpeedRange":
        case "earlyMorningSpeedRange":
          value += "km/h";
          break;
        case "nightRain06h":
        case "afternoonRain06h":
        case "morningRain06h":
        case "earlyMorningRain06h":
          value += "mm";
          break;
      }
      rowResult += field + ": " + value;
      if (fieldIndex !== fields.length - 1) {
        rowResult += ", ";
      }
    }
  });
  return rowResult;
}
