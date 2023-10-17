
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
