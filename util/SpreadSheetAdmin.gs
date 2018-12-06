function clearWindguruData(windSheet){
   //var ss = SpreadsheetApp.getActiveSpreadsheet();
   //var windSheet = ss.getSheetByName("windguru");
   var blankArray = new Array(18);
   for (var i = 0; i < 18; i++) {
     blankArray[i] = new Array(81);
     for (var w = 0; w < 81; w++) {
       blankArray[i][w]="NULL";
     }
   }
   windSheet.getRange(3,2,18,81).setValues(blankArray);
}

function logWindguruTime(lastTry,ss){
   if (ss==null){
     return;
   }
   var now = new Date();
   //var twoHoursFromNow = new Date(now.getTime() + (2 * 60 * 60 * 1000));
   //----windSheet.getRange(24,2).setValue(now.getTime()); //set last try
   var timeStr=now.toLocaleDateString() + ", " + now.toLocaleTimeString(); //now.toJSON().slice(0,10)
   if (lastTry != null){
      if (lastTry == true){
         var dateVar = ss.getRangeByName('lastTryDateString'); //another readable date format
         if (dateVar != null) {
           dateVar.setValue(timeStr);
         }
         dateVar = ss.getRangeByName('lastTryDateLong'); //another readable date format
         if (dateVar != null) {
           dateVar.setValue(now.getTime());
         }
      }else{
        var dateVar = ss.getRangeByName('lastSuccessDateString'); //another readable date format
        if (dateVar != null) {
          dateVar.setValue(timeStr);
        }
        dateVar = ss.getRangeByName('lastSuccessDateLong'); //another readable date format
        if (dateVar != null) {
          dateVar.setValue(now.getTime());
        }
      }
   }
} 

function getAndSetWindguruData(ss,windSheet){
   if (ss==null){
     var ss = SpreadsheetApp.getActiveSpreadsheet();
   }
   if (windSheet==null){
     var windSheet = ss.getSheetByName("windguru");
   }
   //Browser.msgBox(forecastData);
   logWindguruTime(lastTry=true,ss);
   
   try {
     var urlWindguruWS = "http://radiopower.com.ar/powerhd/webroot/widgets/weather/util/getWindguruInfo.php?sc=8158";
     var response = UrlFetchApp.fetch(urlWindguruWS);
     var json = response.getContentText();
     //Logger.log(json);
     var data = JSON.parse(json);
     var forecastData = data.outData;
     if (forecastData != null  &&  forecastData.length>30){
       clearWindguruData(windSheet);
       var forecDays = JSON.parse(forecastData);
       var initColNumber=2;
       var initRowNumber=2;
       var colNum=initColNumber;
       var rowNum=initRowNumber;
       for(var dayNumKey in forecDays){
          //Logger.log("key="+dayKey+", value="+forecDays[dayKey]);
          windSheet.getRange(rowNum,colNum).setValue(dayNumKey);
          rowNum++;
          for(var dayDataKey in forecDays[dayNumKey]){
            if (colNum<=3){
              windSheet.getRange(rowNum,1).setValue(dayDataKey);
            }
            if (dayDataKey=="forecastBy3Hours"){
              var i = 0;
              for(var dayHourKey in forecDays[dayNumKey][dayDataKey]){
                windSheet.getRange(6,colNum).setValue(dayHourKey);
                if (i!=0){
                  windSheet.getRange(rowNum-1,colNum).setValue(windSheet.getRange(rowNum-1,colNum-1).getValue());
                  windSheet.getRange(rowNum-2,colNum).setValue(windSheet.getRange(rowNum-2,colNum-1).getValue());
                  windSheet.getRange(rowNum-3,colNum).setValue(windSheet.getRange(rowNum-3,colNum-1).getValue());
                  windSheet.getRange(rowNum-4,colNum).setValue(windSheet.getRange(rowNum-4,colNum-1).getValue());
                }
                rowNum++;
                for(var weatherAttKey in forecDays[dayNumKey][dayDataKey][dayHourKey]){
                  if (colNum<=3){
                    windSheet.getRange(rowNum,1).setValue(weatherAttKey);
                  }
                  windSheet.getRange(rowNum,colNum).setValue(forecDays[dayNumKey][dayDataKey][dayHourKey][weatherAttKey]);
                  rowNum++;
                }
                colNum++;
                rowNum=initRowNumber+4;
                i++;
              }
              rowNum=initRowNumber+1;
            }else{
              windSheet.getRange(rowNum,colNum).setValue(forecDays[dayNumKey][dayDataKey]);
            }
            rowNum++;
          }
          rowNum=initRowNumber;
       }
       logWindguruTime(lastTry=false,ss);
     }else{
       clearWindguruData(windSheet);
       windSheet.getRangeByName('errorMsgWindguru').setValue("getAndSetWindguruData() ERROR! Response of WS was not the expected");//set error message
     }
   } catch (e) {
      var now = new Date();
      var errorInfo="getAndSetWindguruData()\r\nTime: " + now.toLocaleDateString() + ", " + now.toLocaleTimeString() + "\r\nMessage: " + e.message + "\r\nFile: " + e.fileName + "\r\nLine: " + e.lineNumber;
      //MailApp.sendEmail("you@example.com", "Error report", e.message);
      windSheet.getRangeByName('errorMsgWindguru').setValue(errorInfo);
      clearWindguruData(windSheet);
   }
}


function adminWindguruData(){
  var ss = SpreadsheetApp.getActiveSpreadsheet();
  var windSheet = ss.getSheetByName("windguru");
  getAndSetWindguruData(ss,windSheet);
}

function adminSMNpinaForecastData(){
  var ss = SpreadsheetApp.getActiveSpreadsheet();
  var smnPinaSheet = ss.getSheetByName("smnPina");
  getAndSetSMNpinaForecastData(ss,smnPinaSheet);
  getAndSetSMNpinaUVforecastData(ss,smnPinaSheet);
}

function getAndSetSMNpinaForecastData(ss,smnPinaSheet){
   if (ss==null){
     var ss = SpreadsheetApp.getActiveSpreadsheet();
   }
   if (smnPinaSheet==null){
     var smnPinaSheet = ss.getSheetByName("smnPina");
   }
   //Browser.msgBox(forecastData);
   logSmnPinaTime(lastTry=true,ss);
   
   try {
     var urlSMNpinaForecastWS = "http://radiopower.com.ar/powerhd/webroot/widgets/weather/util/getSMNInfo.php?prov=Buenos%20Aires&action=getforecast&city=Pinamar";
     var response = UrlFetchApp.fetch(urlSMNpinaForecastWS);
     var json = response.getContentText();
     //Logger.log(json);
     var data = JSON.parse(json);
     var forecastData = data.outData;
     if (forecastData != null  &&  forecastData.length>30){
       clearSMNpinaForecastData(smnPinaSheet);
       var forecDays = JSON.parse(forecastData);
       var initColNumber=2;
       var initRowNumber=2;
       var colNum=initColNumber;
       var rowNum=initRowNumber;
       for(var dayNumKey in forecDays){
          //Logger.log("key="+dayKey+", value="+forecDays[dayKey]);
          smnPinaSheet.getRange(rowNum,colNum).setValue(dayNumKey);
          rowNum++;
          for(var dayDataKey in forecDays[dayNumKey]){
             smnPinaSheet.getRange(rowNum,1).setValue(dayDataKey);
             smnPinaSheet.getRange(rowNum,colNum).setValue(forecDays[dayNumKey][dayDataKey]);
             rowNum++;
          }
          rowNum=initRowNumber;
          colNum++;
       }
       logSmnPinaTime(lastTry=false,ss);
     }else{
       clearSMNpinaForecastData(smnPinaSheet);
       smnPinaSheet.getRangeByName('errorMsgSmn').setValue("getAndSetSMNpinaForecastData() ERROR! Response of WS was not the expected");//set error message
     }
   } catch (e) {
      var now = new Date();
      var errorInfo="Time: " + now.toLocaleDateString() + ", " + now.toLocaleTimeString() + "\r\nMessage: " + e.message + "\r\nFile: " + e.fileName + "\r\nLine: " + e.lineNumber;
      MailApp.sendEmail("elpresti@gmail.com", "getAndSetSMNpinaForecastData() Error report", e.message);
      smnPinaSheet.getRangeByName('errorMsgSmn').setValue(errorInfo);
      clearSMNpinaForecastData(smnPinaSheet);
   }
}

function logSmnPinaTime(lastTry,ss){
  if (ss==null){
     return;
   }
   var now = new Date();
   //var twoHoursFromNow = new Date(now.getTime() + (2 * 60 * 60 * 1000));
   //----windSheet.getRange(24,2).setValue(now.getTime()); //set last try
   var timeStr=now.toLocaleDateString() + ", " + now.toLocaleTimeString(); //now.toJSON().slice(0,10)
   if (lastTry != null){
      if (lastTry == true){
         var dateVar = ss.getRangeByName('lastTrySMNdateString'); //another readable date format
         if (dateVar != null) {
           dateVar.setValue(timeStr);
         }
         dateVar = ss.getRangeByName('lastTrySMNdateLong'); //another readable date format
         if (dateVar != null){
           dateVar.setValue(now.getTime());
         }
      }else{
        var dateVar = ss.getRangeByName('lastSuccessSMNdateString'); //another readable date format
        if (dateVar != null) {
          dateVar.setValue(timeStr);
        }
        dateVar = ss.getRangeByName('lastSuccessSMNdateLong'); //another readable date format
        if (dateVar != null) {
          dateVar.setValue(now.getTime());
        }
      }
   }
}

function clearSMNpinaForecastData(smnPinaSheet){
  var blankArray = new Array(9);
   for (var i = 0; i < 9; i++) {
     blankArray[i] = new Array(6);
     for (var w = 0; w < 6; w++) {
       blankArray[i][w]="NULL";
     }
   }
   smnPinaSheet.getRange(3,2,9,6).setValues(blankArray);
}

function clearSMNpinaUVforecastData(smnPinaSheet){
  var blankArray = new Array(4);
   for (var i = 0; i < 4; i++) {
     blankArray[i] = new Array(1);
     for (var w = 0; w < 1; w++) {
       blankArray[i][w]="NULL";
     }
   }
   smnPinaSheet.getRange(26,2,4,1).setValues(blankArray);
}

function clearSMNpinaCurrentWeatherData(smnPinaSheet){
  var blankArray = new Array(10);
   for (var i = 0; i < 10; i++) {
     blankArray[i] = new Array(1);
     for (var w = 0; w < 1; w++) {
       blankArray[i][w]="NULL";
     }
   }
   smnPinaSheet.getRange(14,2,10,1).setValues(blankArray);
}

function adminSMNpinaCurrentWeatherData(){
  var ss = SpreadsheetApp.getActiveSpreadsheet();
  var smnPinaSheet = ss.getSheetByName("smnPina");
  getAndSetSMNpinaCurrentWeatherData(ss,smnPinaSheet);
}

function getAndSetSMNpinaCurrentWeatherData(ss,smnPinaSheet){
   if (ss==null){
     var ss = SpreadsheetApp.getActiveSpreadsheet();
   }
   if (smnPinaSheet==null){
     var smnPinaSheet = ss.getSheetByName("smnPina");
   }
   //Browser.msgBox(forecastData);
   //logSmnPinaTime(lastTry=true,ss);
   
   try {
     //var urlSMNpinaCurrentWeatherWS = "http://radiopower.com.ar/powerhd/webroot/widgets/weather/util/getSMNInfo.php?action=getcurrentweather&stationId=87663";
     var urlSMNpinaCurrentWeatherWS = "http://radiopower.com.ar/powerhd/webroot/widgets/weather/util/getSMNInfo.php?action=getcurrentweather&stationId=87663";
     var response = UrlFetchApp.fetch(urlSMNpinaCurrentWeatherWS);
     var json = response.getContentText();
     //Logger.log(json);
     var data = JSON.parse(json);
     var currentWeatherData = data.outData;
     if (currentWeatherData != null  &&  currentWeatherData.length>20){
       clearSMNpinaCurrentWeatherData(smnPinaSheet);
       var currentInfo = JSON.parse(currentWeatherData);
       var initColNumber=2;
       var initRowNumber=14;
       var colNum=initColNumber;
       var rowNum=initRowNumber;
       for(var weatherAttr in currentInfo){
          //Logger.log("key="+dayKey+", value="+forecDays[dayKey]);
          //smnPinaSheet.getRange(rowNum,colNum).setValue(weatherAttr);
          //rowNum++;
          smnPinaSheet.getRange(rowNum,1).setValue(weatherAttr);
          smnPinaSheet.getRange(rowNum,colNum).setValue(currentInfo[weatherAttr]);
          rowNum++;
       }
       //logSmnPinaTime(lastTry=false,ss);
     }else{
       clearSMNpinaCurrentWeatherData(smnPinaSheet);
       smnPinaSheet.getRangeByName('errorMsgSmn').setValue("ERROR! Response of WS was not the expected");//set error message
     }
   } catch (e) {
      var now = new Date();
      var errorInfo="Time: " + now.toLocaleDateString() + ", " + now.toLocaleTimeString() + "\r\nMessage: " + e.message + "\r\nFile: " + e.fileName + "\r\nLine: " + e.lineNumber;
      MailApp.sendEmail("elpresti@gmail.com", "getAndSetSMNpinaForecastData() Error report", e.message);
      smnPinaSheet.getRangeByName('errorMsgSmn').setValue(errorInfo);
      clearSMNpinaForecastData(smnPinaSheet);
   }
}

function getAndSetSMNpinaUVforecastData(ss,smnPinaSheet){
   if (ss==null){
     var ss = SpreadsheetApp.getActiveSpreadsheet();
   }
   if (smnPinaSheet==null){
     var smnPinaSheet = ss.getSheetByName("smnPina");
   }
   //Browser.msgBox(forecastData);
   //logSmnPinaTime(lastTry=true,ss);
   
   try {
     var urlSMNpinaUVforecastWS = "http://radiopower.com.ar/powerhd/webroot/widgets/weather/util/getSMNInfo.php?city=Pinamar&action=getuvforecast";
     var response = UrlFetchApp.fetch(urlSMNpinaUVforecastWS);
     var json = response.getContentText();
     //Logger.log(json);
     var data = JSON.parse(json);
     var UVforecastData = data.outData;
     if (UVforecastData != null  &&  UVforecastData.length>20){
       clearSMNpinaUVforecastData(smnPinaSheet);
       var uvForecastInfo = JSON.parse(UVforecastData);
       var initColNumber=2;
       var initRowNumber=26;
       var colNum=initColNumber;
       var rowNum=initRowNumber;
       for(var weatherAttr in uvForecastInfo){
          //Logger.log("key="+dayKey+", value="+forecDays[dayKey]);
          //smnPinaSheet.getRange(rowNum,colNum).setValue(weatherAttr);
          //rowNum++;
          smnPinaSheet.getRange(rowNum,1).setValue(weatherAttr);
          smnPinaSheet.getRange(rowNum,colNum).setValue(uvForecastInfo[weatherAttr]);
          rowNum++;
       }
       //logSmnPinaTime(lastTry=false,ss);
     }else{
       clearSMNpinaUVforecastData(smnPinaSheet);
       smnPinaSheet.getRangeByName('errorMsgSmn').setValue("ERROR! Response of WS was not the expected");//set error message
     }
   } catch (e) {
      var now = new Date();
      var errorInfo="getAndSetSMNpinaUVforecastData()\r\nTime: " + now.toLocaleDateString() + ", " + now.toLocaleTimeString() + "\r\nMessage: " + e.message + "\r\nFile: " + e.fileName + "\r\nLine: " + e.lineNumber;
      MailApp.sendEmail("elpresti@gmail.com", "getAndSetSMNpinaUVforecastData() Error report", e.message);
      smnPinaSheet.getRangeByName('errorMsgSmn').setValue(errorInfo);
      clearSMNpinaUVforecastData(smnPinaSheet);
   }
}


/* ---------------------------------------------- */
function clearTelpinCurrentWeatherData(telpinSheet){
  var blankArray = new Array(8);
   for (var i = 0; i < 8; i++) {
     blankArray[i] = new Array(1);
     for (var w = 0; w < 1; w++) {
       blankArray[i][w]="NULL";
     }
   }
   telpinSheet.getRange(3,2,8,1).setValues(blankArray);
}

function adminTelpinCurrentWeatherData(){
  var ss = SpreadsheetApp.getActiveSpreadsheet();
  var telpinSheet = ss.getSheetByName("telpin");
  getAndSetTelpinCurrentWeatherData(ss,telpinSheet);
}

function getAndSetTelpinCurrentWeatherData(ss,telpinSheet){
   if (ss==null){
     var ss = SpreadsheetApp.getActiveSpreadsheet();
   }
   if (telpinSheet==null){
     var telpinSheet = ss.getSheetByName("telpin");
   }
   //Browser.msgBox(forecastData);
   logTelpinTime(lastTry=true,ss);
   
   try {
     var urlTelpinCurrentWeatherWS = "http://radiopower.com.ar/powerhd/webroot/widgets/weather/util/getTelpinInfo.php?action=getcurrentweather";
     var response = UrlFetchApp.fetch(urlTelpinCurrentWeatherWS);
     var json = response.getContentText();
     //Logger.log(json);
     var data = JSON.parse(json);
     var currentWeatherData = data.outData;
     if (currentWeatherData != null  &&  currentWeatherData.length>20){
       clearTelpinCurrentWeatherData(telpinSheet);
       var currentInfo = JSON.parse(currentWeatherData);
       var initColNumber=2;
       var initRowNumber=3;
       var colNum=initColNumber;
       var rowNum=initRowNumber;
       for(var weatherAttr in currentInfo){
          //Logger.log("key="+dayKey+", value="+forecDays[dayKey]);
          //smnPinaSheet.getRange(rowNum,colNum).setValue(weatherAttr);
          //rowNum++;
          telpinSheet.getRange(rowNum,1).setValue(weatherAttr);
          telpinSheet.getRange(rowNum,colNum).setValue(currentInfo[weatherAttr]);
          rowNum++;
       }
       logTelpinTime(lastTry=false,ss);
     }else{
       clearTelpinCurrentWeatherData(telpinSheet);
       telpinSheet.getRangeByName('errorMsgTelpin').setValue("getAndSetTelpinCurrentWeatherData() ERROR! Response of WS was not the expected");//set error message
     }
   } catch (e) {
      var now = new Date();
      var errorInfo="getAndSetTelpinCurrentWeatherData() Time: " + now.toLocaleDateString() + ", " + now.toLocaleTimeString() + "\r\nMessage: " + e.message + "\r\nFile: " + e.fileName + "\r\nLine: " + e.lineNumber;
      MailApp.sendEmail("elpresti@gmail.com", "getAndSetTelpinCurrentWeatherData() Error report", errorInfo);
      telpinSheet.getRangeByName('errorMsgTelpin').setValue(errorInfo);
      clearTelpinCurrentWeatherData(telpinSheet);
   }
}

function logTelpinTime(lastTry,ss){
  if (ss==null){
     return;
   }
   var now = new Date();
   //var twoHoursFromNow = new Date(now.getTime() + (2 * 60 * 60 * 1000));
   //----windSheet.getRange(24,2).setValue(now.getTime()); //set last try
   var timeStr=now.toLocaleDateString() + ", " + now.toLocaleTimeString(); //now.toJSON().slice(0,10)
   if (lastTry != null){
      if (lastTry == true){
         var dateVar = ss.getRangeByName('lastTryTelpinDateString'); //another readable date format
         if (dateVar != null) {
           dateVar.setValue(timeStr);
         }
         dateVar = ss.getRangeByName('lastTryTelpinDateLong'); //another readable date format
         if (dateVar != null){
           dateVar.setValue(now.getTime());
         }
      }else{
        var dateVar = ss.getRangeByName('lastSuccessTelpinDateString'); //another readable date format
        if (dateVar != null) {
          dateVar.setValue(timeStr);
        }
        dateVar = ss.getRangeByName('lastSuccessTelpinDateLong'); //another readable date format
        if (dateVar != null) {
          dateVar.setValue(now.getTime());
        }
      }
   }
}
