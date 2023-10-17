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
  var smnPinaSheet = ss.getSheetByName("smnPina2023");
  getAndSetSMN2023pinaForecastData(ss,smnPinaSheet);
  smnForecastBuildOneLineInfo(ss,smnPinaSheet);
  //generateOneLineTextResume(ss,smnPinaSheet);
  //getAndSetSMNpinaForecastData(ss,smnPinaSheet);
  //getAndSetSMNpinaUVforecastData(ss,smnPinaSheet);
}

function generateOneLineTextResume(ss,smnPinaSheet){
  if (ss==null){
     var ss = SpreadsheetApp.getActiveSpreadsheet();
   }
   if (smnPinaSheet==null){
     var smnPinaSheet = ss.getSheetByName("smnPina");
   }
   try {
     
   } catch (e) {
      var now = new Date();
      var errorInfo="Time: " + now.toLocaleDateString() + ", " + now.toLocaleTimeString() + "\r\nMessage: " + e.message + "\r\nFile: " + e.fileName + "\r\nLine: " + e.lineNumber;
      MailApp.sendEmail("elpresti@gmail.com", "getAndSetSMNpinaForecastData() Error report", e.message);
      smnPinaSheet.getRangeByName('errorMsgSmn').setValue(errorInfo);
      clearSMNpinaForecastData(smnPinaSheet);
   }
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
     var urlSMNpinaForecastWS = "http://radiopower.com.ar/powerhd/webroot/widgets/weather/util/getSMNinfo.php?prov=Buenos%20Aires&action=getforecast&city=Pinamar";
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

function clearSMN2023pinaForecastData(smn2023PinaSheet){
  var blankArray = new Array(35);
   for (var i = 0; i < 35; i++) {
     blankArray[i] = new Array(6);
     for (var w = 0; w < 6; w++) {
       blankArray[i][w]="NULL";
     }
   }
   smn2023PinaSheet.getRange(2,2,35,6).setValues(blankArray);
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
  //var smnPinaSheet = ss.getSheetByName("smnPina");
  var smnPinaSheet = ss.getSheetByName("smnPina2");
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
     //var urlSMNpinaCurrentWeatherWS = "http://radiopower.com.ar/powerhd/webroot/widgets/weather/util/getSMNinfo.php?action=getcurrentweather&stationId=87663";
     var urlSMNpinaCurrentWeatherWS = "http://radiopower.com.ar/powerhd/webroot/widgets/weather/util/getSMNinfo.php?action=getcurrentweather&stationId=87663";
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

function getAndSetSMN2023pinaForecastData(ss,smnPinaSheet){
   if (ss==null){
     var ss = SpreadsheetApp.getActiveSpreadsheet();
   }
   if (smnPinaSheet==null){
     var smnPinaSheet = ss.getSheetByName("smnPina2023");
   }
   //Browser.msgBox(forecastData);
   logSmn2023PinaTime(lastTry=true,ss);
   
   try {
     var urlSMNpinaForecastWS = "http://radiopower.com.ar/powerhd/webroot/widgets/weather/util/getSMNinfo2023.php?action=getforecast&locationId=4298";
     var response = UrlFetchApp.fetch(urlSMNpinaForecastWS);
     var json = response.getContentText();
     //Logger.log(json);
     var data = JSON.parse(json);
     var forecastData = data.outData;
     if (forecastData != null  &&  forecastData.length>30){
       clearSMN2023pinaForecastData(smnPinaSheet);
       var forecDays = JSON.parse(forecastData);
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
       smnPinaSheet.getRangeByName('errorMsgSmn2023').setValue("getAndSetSMN2023pinaForecastData() ERROR! Response of WS was not the expected");//set error message
     }
   } catch (e) {
      var now = new Date();
      var errorInfo="Time: " + now.toLocaleDateString() + ", " + now.toLocaleTimeString() + "\r\nMessage: " + e.message + "\r\nFile: " + e.fileName + "\r\nLine: " + e.lineNumber;
      //MailApp.sendEmail("elpresti@gmail.com", "getAndSetSMN2023pinaForecastData() Error report", e.message);
      smnPinaSheet.getRangeByName('errorMsgSmn2023').setValue(errorInfo);
      clearSMN2023pinaForecastData(smnPinaSheet);
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
     var urlSMNpinaUVforecastWS = "http://radiopower.com.ar/powerhd/webroot/widgets/weather/util/getSMNinfo.php?city=Pinamar&action=getuvforecast";
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

/* ---------------------------------------------- */
function clearNewsData(ss,newsDataSheet,newsSection){
  var blankArray = new Array(8);
   for (var i = 0; i < 8; i++) {
     blankArray[i] = new Array(7);
     for (var w = 0; w < 7; w++) {
       blankArray[i][w]="NULL";
     }
   }
   if (newsSection == 'geteconomiapoliticamasleidas') {
     var colIndex=ss.getRangeByName('mainCellSection1').getColumn();
     var rowIndex=ss.getRangeByName('mainCellSection1').getRow();
   }
   if (newsSection == 'getfinanzasmercadosmasleidas') {
     var colIndex=ss.getRangeByName('mainCellSection2').getColumn();
     var rowIndex=ss.getRangeByName('mainCellSection2').getRow();
   }
   if (newsSection == 'getrecentnews') {
     var colIndex=ss.getRangeByName('mainCellSection3').getColumn();
     var rowIndex=ss.getRangeByName('mainCellSection3').getRow();
   }
   var amountOfRows=8;
   var amountOfColumns=7;

   newsDataSheet.getRange(rowIndex,colIndex,amountOfRows,amountOfColumns).setValues(blankArray);
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
          if ((weatherAttr == "pressure")  &&  (currentInfo[weatherAttr]<900)){
              Logger.log("Stange weather pressure of "+currentInfo[weatherAttr]+"hPa reported. I should use a pressure value from other weather provider (PENDING. I have no alternative provider)");
          }
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
      //MailApp.sendEmail("elpresti@gmail.com", "getAndSetTelpinCurrentWeatherData() Error report", errorInfo);
      if (telpinSheet==null){
       var telpinSheet = ss.getSheetByName("telpin");
      }
      telpinSheet.getRangeByName('errorMsgTelpin').setValue(errorInfo);
      clearTelpinCurrentWeatherData(telpinSheet);
   }
}

function getAndSetElCronistaNews(ss,newsDataSheet,newsSection){
   if (ss==null){
     var ss = SpreadsheetApp.getActiveSpreadsheet();
   }
   if (newsDataSheet==null){
     var newsDataSheet = ss.getSheetByName("newsData");
   }
   if (newsSection==null){
     Logger.log('error! newsSection is null');
     return;
   }
   //Browser.msgBox(forecastData);
   logNewsDataTime(lastTry=true,ss);
   
   try {
     var urlCronistaSectionWS = "https://www.radiopower.com.ar/powerhd/webroot/snippets/getNewsCronista.php?action="+newsSection;
     var response = UrlFetchApp.fetch(urlCronistaSectionWS);
     var json = response.getContentText();
     //Logger.log(json);
     var data = JSON.parse(json);
     var cronistaNewsData = data.outData;
     if (cronistaNewsData != null  &&  cronistaNewsData.length>20){
       clearNewsData(ss,newsDataSheet,newsSection);
       var cronistaNewsReceived = JSON.parse(cronistaNewsData);
       if (newsSection == 'geteconomiapoliticamasleidas') {
         var colNum=ss.getRangeByName('mainCellSection1').getColumn();//initial col number
         var rowNum=ss.getRangeByName('mainCellSection1').getRow();//initial row number
         var columnTitlesRowNumber=rowNum-1;
       }
       if (newsSection == 'getfinanzasmercadosmasleidas') {
         var colNum=ss.getRangeByName('mainCellSection2').getColumn();//initial col number
         var rowNum=ss.getRangeByName('mainCellSection2').getRow();//initial row number
         var columnTitlesRowNumber=rowNum-1;
       }
       var rowNumPointer = rowNum;
       for(var newsIndex in cronistaNewsReceived){
          var colNumPointer = colNum;
          for(var newsAttr in cronistaNewsReceived[newsIndex]){
              newsDataSheet.getRange(columnTitlesRowNumber,colNumPointer).setValue(newsAttr);
              newsDataSheet.getRange(rowNumPointer,colNumPointer).setValue(cronistaNewsReceived[newsIndex][newsAttr]);
              colNumPointer++;
          }
          rowNumPointer++;
       }
       logNewsDataTime(lastTry=false,ss);
     }else{
       clearNewsData(ss,newsDataSheet,newsSection);
       newsDataSheet.getRangeByName('errorMsgCronista').setValue("getAndSetElCronistaNews() ERROR! Response of WS was not the expected");//set error message
     }
   } catch (e) {
      var now = new Date();
      var errorInfo="getAndSetElCronistaNews() Time: " + now.toLocaleDateString() + ", " + now.toLocaleTimeString() + "\r\nMessage: " + e.message + "\r\nFile: " + e.fileName + "\r\nLine: " + e.lineNumber;
      //MailApp.sendEmail("elpresti@gmail.com", "getAndSetElCronistaNews() Error report", errorInfo);
      if (newsDataSheet==null){
       var newsDataSheet = ss.getSheetByName("newsData");
      }
      newsDataSheet.getRangeByName('errorMsgCronista').setValue(errorInfo);
      clearNewsData(ss,newsDataSheet,newsSection);
   }
}

function getAndSetMensajeroDLCNews(ss,newsDataSheet,newsSection){
   if (ss==null){
     var ss = SpreadsheetApp.getActiveSpreadsheet();
   }
   if (newsDataSheet==null){
     var newsDataSheet = ss.getSheetByName("newsData");
   }
   if (newsSection==null){
     Logger.log('error! newsSection is null');
     return;
   }
   //Browser.msgBox(forecastData);
   logNewsDataTime(lastTry=true,ss);
   try {
     var urlMensajeroSectionWS = "https://www.radiopower.com.ar/powerhd/webroot/snippets/getNewsElMensajeroDLC.php?action="+newsSection;
     var response = UrlFetchApp.fetch(urlMensajeroSectionWS);
     var json = response.getContentText();
     //Logger.log(json);
     var data = JSON.parse(json);
     var mensajeroNewsData = data.outData;
     if (mensajeroNewsData != null  &&  mensajeroNewsData.length>20){
       clearNewsData(ss,newsDataSheet,newsSection);
       var mensajeroNewsReceived = JSON.parse(mensajeroNewsData);
       if (newsSection == 'getrecentnews') {
         var colNum=ss.getRangeByName('mainCellSection3').getColumn();//initial col number
         var rowNum=ss.getRangeByName('mainCellSection3').getRow();//initial row number
         var columnTitlesRowNumber=rowNum-1;
       }
       var rowNumPointer = rowNum;
       for(var newsIndex in mensajeroNewsReceived){
          var colNumPointer = colNum;
          for(var newsAttr in mensajeroNewsReceived[newsIndex]){
              newsDataSheet.getRange(columnTitlesRowNumber,colNumPointer).setValue(newsAttr);
              newsDataSheet.getRange(rowNumPointer,colNumPointer).setValue(mensajeroNewsReceived[newsIndex][newsAttr]);
              colNumPointer++;
          }
          rowNumPointer++;
       }
       logNewsDataTime(lastTry=false,ss);
     }else{
       clearNewsData(ss,newsDataSheet,newsSection);
       newsDataSheet.getRangeByName('errorMsgCronista').setValue("getAndSetMensajeroDLCNews() ERROR! Response of WS was not the expected");//set error message
     }
   } catch (e) {
      var now = new Date();
      var errorInfo="getAndSetMensajeroDLCNews() Time: " + now.toLocaleDateString() + ", " + now.toLocaleTimeString() + "\r\nMessage: " + e.message + "\r\nFile: " + e.fileName + "\r\nLine: " + e.lineNumber;
      //MailApp.sendEmail("elpresti@gmail.com", "getAndSetMensajeroDLCNews() Error report", errorInfo);
      if (newsDataSheet==null){
       var newsDataSheet = ss.getSheetByName("newsData");
      }
      ss.getRangeByName("errorMsgCronista").setValue(errorInfo);
      clearNewsData(ss,newsDataSheet,newsSection);
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

function logNewsDataTime(lastTry,ss){
  if (ss==null){
     return;
   }
   var now = new Date();
   //var twoHoursFromNow = new Date(now.getTime() + (2 * 60 * 60 * 1000));
   //----windSheet.getRange(24,2).setValue(now.getTime()); //set last try
   var timeStr=now.toLocaleDateString() + ", " + now.toLocaleTimeString(); //now.toJSON().slice(0,10)
   if (lastTry != null){
      if (lastTry == true){
         var dateVar = ss.getRangeByName('lastTryCronistaDateString'); //another readable date format
         if (dateVar != null) {
           dateVar.setValue(timeStr);
         }
         dateVar = ss.getRangeByName('lastTryCronistaDateLong'); //another readable date format
         if (dateVar != null){
           dateVar.setValue(now.getTime());
         }
      }else{
        var dateVar = ss.getRangeByName('lastSuccessCronistaDateString'); //another readable date format
        if (dateVar != null) {
          dateVar.setValue(timeStr);
        }
        dateVar = ss.getRangeByName('lastSuccessCronistaDateLong'); //another readable date format
        if (dateVar != null) {
          dateVar.setValue(now.getTime());
        }
      }
   }
}

function updateGptCell1() {
  var ss = SpreadsheetApp.getActiveSpreadsheet();
  var shortFlashSheet = ss.getSheetByName("shortFlash");
  var gptCell = shortFlashSheet.getRange('B1');
  gptCell.setFormula('=GPT("Escribe un texto de 30 palabras que hable sobre alguna efeméride musical correspondiente al día de hoy")');
}

function adminGetNews(){
  var ss = SpreadsheetApp.getActiveSpreadsheet();
  var newsDataSheet = ss.getSheetByName("newsData");
  getAndSetMensajeroDLCNews(ss,newsDataSheet,'getrecentnews');
  getAndSetElCronistaNews(ss,newsDataSheet,'geteconomiapoliticamasleidas');
  getAndSetElCronistaNews(ss,newsDataSheet,'getfinanzasmercadosmasleidas');
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
