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

function updateGptCells() {
   var ss = SpreadsheetApp.getActiveSpreadsheet();
   var shortFlashSheet = ss.getSheetByName("shortFlashContent");
   var gptPromptCell = shortFlashSheet.getRange('B2');
   var gptResultCell = shortFlashSheet.getRange('C2');
  
   //gptResultCell.setFormula('=GPT("Escribe un texto de 30 palabras que hable sobre alguna efeméride musical correspondiente al día de hoy")');
   var temp = '=GPT("' + gptPromptCell.getValue() + '")';
   gptResultCell.setFormula('=GPT("' + gptPromptCell.getValue() + '")');
}

function adminGetNews(){
  var ss = SpreadsheetApp.getActiveSpreadsheet();
  var newsDataSheet = ss.getSheetByName("newsData");
  //getAndSetMensajeroDLCNews(ss,newsDataSheet,'getmensajerodlcrecentnews');
  getAndSetElCronistaNews(ss,newsDataSheet,'geteconomiapoliticamasleidas');
  getAndSetElCronistaNews(ss,newsDataSheet,'getfinanzasmercadosmasleidas');
  getAndSetInfobaeNews(ss,newsDataSheet,'getinfobaeeconomianews');
  getAndSetInfobaeNews(ss,newsDataSheet,'getinfobaedeportesnews');
  seleccionarNoticias(ss);
}

function getAndSetInfobaeNews(ss,newsDataSheet,newsSection){
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
   var amountOfRows=8;
   var amountOfColumns=8;
   //Browser.msgBox(forecastData);
   logNewsDataTime(lastTry=true,ss);
   try { 
     var rssUrl = "https://www.infobae.com/feeds/rss/"; //alternativa 2: https://www.infobae.com/argentina-rss.xml
     if (newsSection == "getinfobaeeconomianews") {
        var urlInfobaeSectionWS = "https://radiopower.com.ar/powerhd/webroot/snippets/getNewsInfobae.php?action=getinfobaenews&imagesmin=2&maxage=24&sections=economia&rssurl=" + encodeURIComponent(rssUrl);
        Logger.log('urlInfobaeSectionWS_ECONOMIA: ' + urlInfobaeSectionWS);
     } else {
       if (newsSection == "getinfobaedeportesnews") {
          var urlInfobaeSectionWS = "https://radiopower.com.ar/powerhd/webroot/snippets/getNewsInfobae.php?action=getinfobaenews&imagesmin=2&maxage=24&sections=deportes&rssurl=" + encodeURIComponent(rssUrl);
          Logger.log('urlInfobaeSectionWS_DEPORTES: ' + urlInfobaeSectionWS);
       } else {
          Logger.log('Error! newsSection is invalid');
          return;
       }
     }
     var response = UrlFetchApp.fetch(urlInfobaeSectionWS);
     var json = response.getContentText();
     //Logger.log(json);
     var data = JSON.parse(json);
     var infobaeNewsData = data.outData;
     if (infobaeNewsData != null  &&  infobaeNewsData.length>10){
       clearNewsData(ss,newsDataSheet,newsSection,amountOfRows,amountOfColumns);
       var infobaeNewsReceived = JSON.parse(infobaeNewsData);
       if (newsSection == 'getinfobaeeconomianews') {
         var varIndexCell = getVarCellsRange(newsDataSheet,"infobaeEconomia");
         var a1Notation = ss.getRange(varIndexCell).getA1Notation();// Obtener la notación A1 de la celda
         var colNum = ss.getRange(a1Notation).getColumn();// Obtener la columna y fila a partir de la notación A1
         var rowNum = ss.getRange(a1Notation).getRow();
         var columnTitlesRowNumber = rowNum - 1;
       }
       if (newsSection == "getinfobaedeportesnews") {
         var varIndexCell = getVarCellsRange(newsDataSheet,"infobaeDeportes");
         var a1Notation = ss.getRange(varIndexCell).getA1Notation();// Obtener la notación A1 de la celda
         var colNum = ss.getRange(a1Notation).getColumn();// Obtener la columna y fila a partir de la notación A1
         var rowNum = ss.getRange(a1Notation).getRow();
         var columnTitlesRowNumber = rowNum - 1;
       }
       var rowNumPointer = rowNum;
       for(var newsIndex in infobaeNewsReceived){
          if (rowNumPointer > (rowNumPointer + amountOfRows)) {
            break;
          }
          var colNumPointer = colNum;
          for(var newsAttr in infobaeNewsReceived[newsIndex]){
              newsDataSheet.getRange(columnTitlesRowNumber,colNumPointer).setValue(newsAttr);
              if (Array.isArray(infobaeNewsReceived[newsIndex][newsAttr])) {
                newsDataSheet.getRange(rowNumPointer,colNumPointer).setValue(infobaeNewsReceived[newsIndex][newsAttr].join(','));
              } else {
                newsDataSheet.getRange(rowNumPointer,colNumPointer).setValue(infobaeNewsReceived[newsIndex][newsAttr]);
              }
              colNumPointer++;
          }
          rowNumPointer++;
       }
       logNewsDataTime(lastTry=false,ss);
       if (newsSection == 'getinfobaedeportesnews') {
          var mainCellName = 'mainCellInfobaeDeportes';
       } else {
          var mainCellName = 'mainCellInfobaeEconomia';
       }
       markNewsAsPending(
          newsDataSheet,
          ss.getRangeByName(mainCellName).getColumn(),
          ss.getRangeByName(mainCellName).getRow(),
          amountOfRows,
          amountOfColumns
       );
     }else{
       clearNewsData(ss,newsDataSheet,newsSection,amountOfRows,amountOfColumns);
       ss.getRangeByName('errorMsgCronista').setValue(newsSection + "() ERROR! Response of WS was not the expected");//set error message
     }
   } catch (e) {
      var now = new Date();
      var errorInfo= newsSection + "() Time: " + now.toLocaleDateString() + ", " + now.toLocaleTimeString() + "\r\nMessage: " + e.message + "\r\nFile: " + e.fileName + "\r\nLine: " + e.lineNumber;
      //MailApp.sendEmail("elpresti@gmail.com", "getAndSetMensajeroDLCNews() Error report", errorInfo);
      if (newsDataSheet==null){
       var newsDataSheet = ss.getSheetByName("newsData");
      }
      ss.getRangeByName("errorMsgCronista").setValue(errorInfo);
      clearNewsData(ss,newsDataSheet,newsSection,amountOfRows,amountOfColumns);
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
   if (newsSection == 'geteconomiapoliticamasleidas') {
     var varIndexCell = getVarCellsRange(newsDataSheet,"notiEconoPolitica");
     var a1Notation = ss.getRange(varIndexCell).getA1Notation();

     var colNum = ss.getRange(a1Notation).getColumn();// Obtener la columna y fila a partir de la notación A1
     var rowNum = ss.getRange(a1Notation).getRow();

     var columnTitlesRowNumber=rowNum-1;
   }
   if (newsSection == 'getfinanzasmercadosmasleidas') {
     var varIndexCell = getVarCellsRange(newsDataSheet,"notiFinanzasMercados");
     var a1Notation = ss.getRange(varIndexCell).getA1Notation();// Obtener la notación A1 de la celda

     var colNum = ss.getRange(a1Notation).getColumn();// Obtener la columna y fila a partir de la notación A1
     var rowNum = ss.getRange(a1Notation).getRow();

     var columnTitlesRowNumber=rowNum-1;
   }
   var amountOfRows=8;
   var amountOfColumns=7;

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
       clearNewsData(ss,newsDataSheet,newsSection,amountOfRows,amountOfColumns);
       var cronistaNewsReceived = JSON.parse(cronistaNewsData);
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
       markNewsAsPending(
          newsDataSheet,
          colNum,
          rowNum,
          amountOfRows,
          amountOfColumns
       );
     }else{
       clearNewsData(ss,newsDataSheet,newsSection,amountOfRows,amountOfColumns);
       ss.getRangeByName('errorMsgCronista').setValue("getAndSetElCronistaNews() ERROR! Response of WS was not the expected");//set error message
     }
   } catch (e) {
      var now = new Date();
      var errorInfo="getAndSetElCronistaNews() Time: " + now.toLocaleDateString() + ", " + now.toLocaleTimeString() + "\r\nMessage: " + e.message + "\r\nFile: " + e.fileName + "\r\nLine: " + e.lineNumber;
      //MailApp.sendEmail("elpresti@gmail.com", "getAndSetElCronistaNews() Error report", errorInfo);
      if (newsDataSheet==null){
       var newsDataSheet = ss.getSheetByName("newsData");
      }
      ss.getRangeByName('errorMsgCronista').setValue(errorInfo);
      clearNewsData(ss,newsDataSheet,newsSection,amountOfRows,amountOfColumns);
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
   var amountOfRows=8;
   var amountOfColumns=8;
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
       clearNewsData(ss,newsDataSheet,newsSection,amountOfRows,amountOfColumns);
       var mensajeroNewsReceived = JSON.parse(mensajeroNewsData);
       if (newsSection == 'getmensajerodlcrecentnews') {
         var varIndexCell = getVarCellsRange(newsDataSheet,"notiMensajeroDLC");
         var a1Notation = ss.getRange(varIndexCell).getA1Notation();// Obtener la notación A1 de la celda

         var colNum = ss.getRange(a1Notation).getColumn();// Obtener la columna y fila a partir de la notación A1
         var rowNum = ss.getRange(a1Notation).getRow();
          
         var columnTitlesRowNumber=rowNum-1;
       }
       var rowNumPointer = rowNum;
       for(var newsIndex in mensajeroNewsReceived){
          var colNumPointer = colNum;
          for(var newsAttr in mensajeroNewsReceived[newsIndex]){
              newsDataSheet.getRange(columnTitlesRowNumber,colNumPointer).setValue(newsAttr);
              if (Array.isArray(mensajeroNewsReceived[newsIndex][newsAttr])) {
                newsDataSheet.getRange(rowNumPointer,colNumPointer).setValue(mensajeroNewsReceived[newsIndex][newsAttr].join(','));
              } else {
                newsDataSheet.getRange(rowNumPointer,colNumPointer).setValue(mensajeroNewsReceived[newsIndex][newsAttr]);
              }
              colNumPointer++;
          }
          rowNumPointer++;
       }
       logNewsDataTime(lastTry=false,ss);
       markNewsAsPending(
          newsDataSheet,
          ss.getRangeByName('mainCellSection3').getColumn(),
          ss.getRangeByName('mainCellSection3').getRow(),
          amountOfRows,
          amountOfColumns
       );
     }else{
       clearNewsData(ss,newsDataSheet,newsSection,amountOfRows,amountOfColumns);
       ss.getRangeByName('errorMsgCronista').setValue("getAndSetMensajeroDLCNews() ERROR! Response of WS was not the expected");//set error message
     }
   } catch (e) {
      var now = new Date();
      var errorInfo="getAndSetMensajeroDLCNews() Time: " + now.toLocaleDateString() + ", " + now.toLocaleTimeString() + "\r\nMessage: " + e.message + "\r\nFile: " + e.fileName + "\r\nLine: " + e.lineNumber;
      //MailApp.sendEmail("elpresti@gmail.com", "getAndSetMensajeroDLCNews() Error report", errorInfo);
      if (newsDataSheet==null){
       var newsDataSheet = ss.getSheetByName("newsData");
      }
      ss.getRangeByName("errorMsgCronista").setValue(errorInfo);
      clearNewsData(ss,newsDataSheet,newsSection,amountOfRows,amountOfColumns);
   }
}

function clearNewsData(ss,newsDataSheet,newsSection,amountOfRows,amountOfColumns){
   var blankArray = new Array(amountOfRows);
   for (var i = 0; i < amountOfRows; i++) {
      blankArray[i] = new Array(amountOfColumns);
      for (var w = 0; w < amountOfColumns; w++) {
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
   if (newsSection == 'getmensajerodlcrecentnews') {
     var colIndex=ss.getRangeByName('mainCellSection3').getColumn();
     var rowIndex=ss.getRangeByName('mainCellSection3').getRow();
   }
   if (newsSection == 'getinfobaeeconomianews') {
     var colIndex=ss.getRangeByName('mainCellInfobaeEconomia').getColumn();
     var rowIndex=ss.getRangeByName('mainCellInfobaeEconomia').getRow();
   }
   if (newsSection == 'getinfobaedeportesnews') {
     var colIndex=ss.getRangeByName('mainCellInfobaeDeportes').getColumn();
     var rowIndex=ss.getRangeByName('mainCellInfobaeDeportes').getRow();
   }
   newsDataSheet.getRange(rowIndex,colIndex,amountOfRows,amountOfColumns).setValues(blankArray);
}

function clearShortFlashSheet(ss,shortFlashDataSheet){
   var colIndex = ss.getRangeByName('mainCellShortFlash').getColumn();
   var rowIndex = ss.getRangeByName('mainCellShortFlash').getRow();
   var amountOfRows = 30;
   var amountOfColumns = 8;
   var blankArray = new Array(amountOfRows);
   for (var i = 0; i < amountOfRows; i++) {
      blankArray[i] = new Array(amountOfColumns);
      for (var w = 0; w < amountOfColumns; w++) {
        blankArray[i][w] = "NULL";
      }
   }
   shortFlashDataSheet.getRange(rowIndex,colIndex,amountOfRows,amountOfColumns).setValues(blankArray);
}

function markNewsAsPending(sheet,initColumn,initRow,amountOfRows,amountOfColumns) {
  // Obtener los datos
  initColumn = initColumn - 1;
  initRow = initRow - 1;
  var data = sheet.getDataRange().getValues();

  // iterate over rows of the given table
  for (var i = initRow; i < (initRow + amountOfRows); i++) {
    var newsDate = new Date(data[i][initColumn+0]);
    var newsCategory = data[i][initColumn+1];
    var newsTitle = data[i][initColumn+2];
    var newsDescription = data[i][initColumn+4];
    var newsImages = data[i][initColumn+3].split(",");
    var newsPriorityColumnNumber = initColumn+amountOfColumns;
    if (initColumn == 16) {
      newsDescription = data[i][initColumn+5];
    }
    var newsPriority = data[i][newsPriorityColumnNumber];
        
    // verify conditions
    if (
      newsCategory.length > 3 &&
      newsTitle.length > 3 &&
      newsDescription.length > 3 &&
      newsImages.length > 0 &&
      newsDate.getTime() >= (Date.now() - 172800000) // 172800000 milisec are 48 hs
    ) {
      // Set news_priority
      switch (newsImages.length) {
        case 2:
          sheet.getRange(i + 1, newsPriorityColumnNumber).setValue("MID");
          break;
        case 1:
          sheet.getRange(i + 1, newsPriorityColumnNumber).setValue("LOW");
          break;
        default:
          sheet.getRange(i + 1, newsPriorityColumnNumber).setValue("HIGH");
          break;
      }
    } else {
      sheet.getRange(i + 1, newsPriorityColumnNumber).setValue("AVOID");
    }
  }
}

function seleccionarNoticias(ss) {
  var ss = SpreadsheetApp.getActiveSpreadsheet();

  var newsDataSheet = ss.getSheetByName("newsData");
  var shortFlashContentSheet = ss.getSheetByName("shortFlashContent");

  // Obtener datos de la hoja "newsData" para flashX_notiEconoPolitica, flashX_notiMensajero y flashX_notiFinanzasMercados
  
  var varCellsRange = getVarCellsRange(newsDataSheet,"notiEconoPolitica");
  var rangoTablaNoticiasEconoPolitica = ss.getRange(varCellsRange).getA1Notation();
  
  var noticiasEconoPolitica = newsDataSheet.getRange(rangoTablaNoticiasEconoPolitica).getValues();
  var noticiasEconoPoliticaWithCoords = getTableDataWithCellCoordinates(noticiasEconoPolitica,newsDataSheet,rangoTablaNoticiasEconoPolitica);
  
  varCellsRange = getVarCellsRange(newsDataSheet,"notiMensajeroDLC");
  var rangoTablaNoticiasMensajero = ss.getRange(varCellsRange).getA1Notation();

  var noticiasMensajero = newsDataSheet.getRange(rangoTablaNoticiasMensajero).getValues();
  var noticiasMensajeroWithCoords = getTableDataWithCellCoordinates(noticiasMensajero,newsDataSheet,rangoTablaNoticiasMensajero);
  
  varCellsRange = getVarCellsRange(newsDataSheet,"notiFinanzasMercados");
  var rangoTablaNoticiasFinanzasMercados = ss.getRange(varCellsRange).getA1Notation();

  var noticiasFinanzasMercados = newsDataSheet.getRange(rangoTablaNoticiasFinanzasMercados).getValues();
  var noticiasFinanzasMercadosWithCoords = getTableDataWithCellCoordinates(noticiasFinanzasMercados,newsDataSheet,rangoTablaNoticiasFinanzasMercados);

  varCellsRange = getVarCellsRange(newsDataSheet,"infobaeEconomia");
  var rangoTablaInfobaeEconomia = ss.getRange(varCellsRange).getA1Notation();

  var noticiasInfobaeEconomia = newsDataSheet.getRange(rangoTablaInfobaeEconomia).getValues();
  var noticiasInfobaeEconomiaWithCoords = getTableDataWithCellCoordinates(noticiasInfobaeEconomia,newsDataSheet,rangoTablaInfobaeEconomia);

  varCellsRange = getVarCellsRange(newsDataSheet,"infobaeDeportes");
  var rangoTablaInfobaeDeportes = ss.getRange(varCellsRange).getA1Notation();

  var noticiasInfobaeDeportes = newsDataSheet.getRange(rangoTablaInfobaeDeportes).getValues();
  var noticiasInfobaeDeportesWithCoords = getTableDataWithCellCoordinates(noticiasInfobaeDeportes,newsDataSheet,rangoTablaInfobaeDeportes);

  // Filtrar noticias de alta y mediana prioridad
  var noticiasPrioridadAltaEconoPolitica = noticiasEconoPoliticaWithCoords.filter(function(row) {
    return row[6]['cellvalue'] === "HIGH";
  });

  var noticiasPrioridadMediaEconoPolitica = noticiasEconoPoliticaWithCoords.filter(function(row) {
    return row[6]['cellvalue'] === "MID";
  });

  var noticiasPrioridadAltaMensajero = noticiasMensajeroWithCoords.filter(function(row) {
    return row[7]['cellvalue'] === "HIGH";
  });

  var noticiasPrioridadMediaMensajero = noticiasMensajeroWithCoords.filter(function(row) {
    return row[7]['cellvalue'] === "MID";
  });

  var noticiasPrioridadAltaFinanzasMercados = noticiasFinanzasMercadosWithCoords.filter(function(row) {
    return row[6]['cellvalue'] === "HIGH";
  });

  var noticiasPrioridadMediaFinanzasMercados = noticiasFinanzasMercadosWithCoords.filter(function(row) {
    return row[6]['cellvalue'] === "MID";
  });

  var noticiasPrioridadAltaInfobaeEconomia = noticiasInfobaeEconomiaWithCoords.filter(function(row) {
    return row[7]['cellvalue'] === "HIGH";
  });

  var noticiasPrioridadMediaInfobaeEconomia = noticiasInfobaeEconomiaWithCoords.filter(function(row) {
    return row[7]['cellvalue'] === "MID";
  });

  var noticiasPrioridadAltaInfobaeDeportes = noticiasInfobaeDeportesWithCoords.filter(function(row) {
    return row[7]['cellvalue'] === "HIGH";
  });

  var noticiasPrioridadMediaInfobaeDeportes = noticiasInfobaeDeportesWithCoords.filter(function(row) {
    return row[7]['cellvalue'] === "MID";
  });

  // Unificar noticias de alta y mediana prioridad
  var noticiasEconoPolitica = noticiasPrioridadAltaEconoPolitica.concat(noticiasPrioridadMediaEconoPolitica);
  var noticiasMensajero = noticiasPrioridadAltaMensajero.concat(noticiasPrioridadMediaMensajero);
  var noticiasFinanzasMercados = noticiasPrioridadAltaFinanzasMercados.concat(noticiasPrioridadMediaFinanzasMercados);
  var noticiasInfobaeEconomia = noticiasPrioridadAltaInfobaeEconomia.concat(noticiasPrioridadMediaInfobaeEconomia);
  var noticiasInfobaeDeportes = noticiasPrioridadAltaInfobaeDeportes.concat(noticiasPrioridadMediaInfobaeDeportes);
  var flashNumber = 1;
  var targetRowNum = 1;

  clearShortFlashSheet(ss,shortFlashContentSheet);

  // Seleccionar la noticia de mayor prioridad y actualizar la hoja
  while (noticiasEconoPolitica.length > 0 || noticiasMensajero.length > 0 || noticiasFinanzasMercados.length > 0 || noticiasInfobaeEconomia.length > 0 || noticiasInfobaeDeportes.length > 0) {
    var noticiaEconoPolitica = noticiasEconoPolitica.shift();
    var noticiaMensajero = noticiasMensajero.shift();
    var noticiaInfobaeDeportes = noticiasInfobaeDeportes.shift();
    var newsGroup = {};
    
    if (noticiaEconoPolitica) {
      newsGroup['notiEconoPolitica'] = noticiaEconoPolitica;
    }
    if (noticiaMensajero) {
      newsGroup['notiMensajeroDLC'] = noticiaMensajero;
    }
    if (noticiaInfobaeDeportes) {
      newsGroup['infobaeDeportes'] = noticiaInfobaeDeportes;
    }
    if (!newsGroup.hasOwnProperty('notiEconoPolitica')){
      var noticiaInfobaeEconomia = noticiasInfobaeEconomia.shift();
      if (noticiaInfobaeEconomia) {
        newsGroup['infobaeEconomia'] = noticiaInfobaeEconomia;
      }
    }
    if (Object.keys(newsGroup).length < 3) {
      var noticiaFinanzasMercados = noticiasFinanzasMercados.shift();
      if (noticiaFinanzasMercados) {
        newsGroup['notiFinanzasMercados'] = noticiaFinanzasMercados;
      }
    }

    //TODO seguir aca
    var forecastPrompt = buildFinalPromptOfVars('climaPronosticoPina');//pending: fix
    //var forecastPrompt = null;
    if (forecastPrompt != null && forecastPrompt.length > 0) {
      var today = new Date();
      var todayInSimpleDateFormat = Utilities.formatDate(today, 'GMT', 'dd/MM/yyyy');
      newsGroup['climaPronosticoPina'] = [];
      newsGroup['climaPronosticoPina'][0] = { 'cellvalue': todayInSimpleDateFormat }; // news_date
      newsGroup['climaPronosticoPina'][1] = { 'cellvalue': "CLIMA" }; // news_category
      newsGroup['climaPronosticoPina'][2] = { 'cellvalue': "PRONOSTICO PARA PINAMAR Y ALREDEDORES" }; // news_title
      newsGroup['climaPronosticoPina'][3] = { 'cellvalue': "https://4.bp.blogspot.com/-nlvylP8GM-g/XGGiUGsbdaI/AAAAAAAA3Yw/n06yyrMEQ3g2uG7uC7vJA4t2OmOMCEowACLcBGAs/s1600/PLA.jpg,https://resizer.glanacion.com/resizer/v2/el-pronostico-del-tiempo-para-pinamar-para-el-10-XDCY2RPTFNF6JCZRWTMCI7UGAA.jpg?auth=ef6b4fa3b72b5ddeb0e42033d1501f4a4513095feba2c17d9d468a94aeab77ff,https://media.airedesantafe.com.ar/p/963af3875f5f9ffb3a1844b02bc46d6a/adjuntos/268/imagenes/003/343/0003343486/1200x675/smart/imagepng.png,https://www.pinamarturismo.com.ar/cms/resources/jpg/DiegoMedina-0011.jpg" }; // news_images
      newsGroup['climaPronosticoPina'][4] = { 'cellvalue': "Este es el pronostico del clima para la ciudad de Pinamar y alrededores"}; // news_description
      newsGroup['climaPronosticoPina'][5] = { 'cellvalue': forecastPrompt};
      newsGroup['climaPronosticoPina'][6] = { 'cellvalue': 'climaPronosticoPina' };
    }
    if (Object.keys(newsGroup).length > 0) {
      actualizarHoja(shortFlashContentSheet, newsGroup, targetRowNum, flashNumber);
      targetRowNum = targetRowNum + Object.keys(newsGroup).length + 1;
      flashNumber++;
    }
  }
}

function getTableDataWithCellCoordinates(newsTableData,newsDataSheet,rango,rowsOffset,columnsOffset) {
  var newsTableDataWithCoordinates = []; // Array para almacenar la estructura modificada

  //TODO seguir aca:
  var rangeInitCell = rango.split(":")[0]; // Obtiene la primera parte del rango ingresado
  var rangeInitCellLetters = rangeInitCell.match(/[A-Za-z]+/g).join(''); // Obtiene las letras
  var rangeInitCellNumber = parseInt(rangeInitCell.match(/\d+/)[0]); // Obtiene el valor numérico
  var rangeLastCell = rango.split(":")[1]; // Obtiene la primera parte del rango ingresado
  var rangeLastCellLetters = rangeLastCell.match(/[A-Za-z]+/g).join(''); // Obtiene las letras
  var rangeLastCellNumber = parseInt(rangeLastCell.match(/\d+/)[0]); // Obtiene el valor numérico
  /*
  var ultimaLetra = rangeInitCell.match(/[A-Za-z]/g).pop(); // Obtiene la última letra de la primera terna (B)
  var columnaASCII = ultimaLetra.charCodeAt(0); // Obtiene el valor ASCII de la última letra
  var primeraLetra = primeraTerna.charCodeAt(0);
  */
  var primeraColumnaFirstLetter = "";
  var primeraColumnaFirstLetterASCII = null;
  var primeraColumnaLastLetterASCII = rangeInitCellLetters.charCodeAt(0); // Obtener el valor ASCII de la primera columna del rango
  if (rangeInitCellLetters.length == 2) {
    primeraColumnaLastLetterASCII = rangeInitCellLetters.charCodeAt(1); // Obtener el valor ASCII de la primera columna del rango
    primeraColumnaFirstLetterASCII = rangeInitCellLetters.charCodeAt(0);
    primeraColumnaFirstLetter = rangeInitCellLetters[0];//sino reemplazar por .charAt(0);
  }
  if (rowsOffset == null) {
    rowsOffset = 0;
  }
  if (columnsOffset == null) {
    columnsOffset = 0;
  }

  for (var i = 0; i < newsTableData.length; i++) {
    var fila = newsTableData[i];
    var filaConCoordenadas = [];
    for (var j = 0; j < fila.length; j++) {
      var valor = fila[j];
      var columnaASCII = primeraColumnaLastLetterASCII + j + columnsOffset; // Calcular el valor ASCII de la columna dinámicamente
      if (columnaASCII < 91) {
        if (rowsOffset > 0) {
          var coordenada = newsDataSheet.getName() + "!" + primeraColumnaFirstLetter + String.fromCharCode(columnaASCII) + (i + rowsOffset); // Calcular la coordenada con el ajuste de offset
        } else {
          var coordenada = newsDataSheet.getName() + "!" + primeraColumnaFirstLetter + String.fromCharCode(columnaASCII) + (3 + i); // Calcular la coordenada con el ajuste respecto a la fila de ubicacion de las tablas de noticias
        }
      } else {
        columnaASCII = columnaASCII - 26;//TODO algo voy a tener q hacer con el +j en este caso
        primeraColumnaLastLetterASCII = columnaASCII;
        if (primeraColumnaFirstLetter == "") {
          var columnaFirstLetterASCII = 65 + columnsOffset; // Calcular el valor ASCII de la columna dinámicamente teniendo en cuenta que habia una sola letra y ahora habrá dos (65 = A)
          primeraColumnaFirstLetter = "A";
        } else {
          var columnaFirstLetterASCII = primeraColumnaFirstLetterASCII +  columnsOffset; // Calcular el valor ASCII de la columna dinámicamente
        }
        if (rowsOffset > 0) {
          var coordenada = newsDataSheet.getName() + "!" + String.fromCharCode(primeraColumnaFirstLetter + 1) + String.fromCharCode(columnaASCII) + (i + rowsOffset); // Calcular la coordenada con el ajuste de offset
        } else {
          var coordenada = newsDataSheet.getName() + "!" + primeraColumnaFirstLetter + String.fromCharCode(columnaASCII) + (3 + i); // Calcular la coordenada con el ajuste respecto a la fila de ubicacion de las tablas de noticias
        }
      }
      var celdaConCoordenadas = {
        'cellpath': coordenada,
        'cellvalue': valor
      };
      filaConCoordenadas.push(celdaConCoordenadas);
    }
    newsTableDataWithCoordinates.push(filaConCoordenadas);
  }
  return newsTableDataWithCoordinates;
}

// Función para actualizar la hoja "shortFlashContent" con las noticias seleccionadas
function actualizarHoja(shortFlashContentSheet, newsGroup, targetRowNum, flashNumber) {
  var range = shortFlashContentSheet.getRange("A2:K30");
  var rowsOffset = 1;
  var columnsOffset = 1;
  var values = range.getValues();
  var i = 0;
  if (targetRowNum > 0) {
    i = targetRowNum - rowsOffset;
  }
  for (var varName in newsGroup) {
    if (newsGroup.hasOwnProperty(varName)) {
      //Logger.log('Clave: ' + varName + ', Valor: ' + newsGroup[varName]);
      var varType = getVarType(varName);
      var isComplexVar = false;
      if (varType == "complex") {
        isComplexVar = true;
      }
      values[i][columnsOffset+0] = newsGroup[varName][0]['cellvalue']; // news_date
      values[i][columnsOffset+1] = newsGroup[varName][1]['cellvalue']; // news_category
      values[i][columnsOffset+2] = newsGroup[varName][2]['cellvalue']; // news_title
      values[i][columnsOffset+3] = newsGroup[varName][3]['cellvalue']; // news_images
      values[i][columnsOffset+4] = newsGroup[varName][4]['cellvalue']; // news_description
      //TODO: IF mensajero >> concatenar values[i][columnsOffset+4] = values[i][columnsOffset+4] + "\n " + newsGroup[varName][5]['cellvalue']; // news_description

      var temporarySummarizedDescriptionWords  = newsGroup[varName][4]['cellvalue'].split(' ');// Divide la cadena en palabras utilizando un espacio como delimitador.
      values[i][columnsOffset+6] = temporarySummarizedDescriptionWords.slice(0, 80).join(' ');// news_description_prompt_results (temporary): Toma las primeras X palabras y únelas de nuevo en una cadena

      if (isComplexVar) {
        var promptVarsNewValues = {
          "varNewsTitle": newsGroup[varName][2]['cellpath'],
          "varNewsBody": newsGroup[varName][4]['cellpath']
        }
        values[i][columnsOffset+5] = buildFinalPromptOfVars(varName,promptVarsNewValues);
      } else {
        values[i][columnsOffset+5] = buildFinalPromptOfVars(varName);
      }
      values[i][0] = "flash" + flashNumber + "_" + varName;
    }
    i++;
  }
  range.setValues(values);
}

function getForecastColumnLetterOfToday() {
  var sheet = SpreadsheetApp.getActiveSpreadsheet().getSheetByName("smnPina2023");
  var dataRow = sheet.getRange("A2:I36");
  var dateValues = dataRow.getValues()[0];
  var today = new Date();
  var todayInSimpleDateFormat = Utilities.formatDate(today, 'GMT', 'yyyy-MM-dd');
  for (var i = 0; i < dateValues.length; i++) {
    if (dateValues[i] === todayInSimpleDateFormat) {
      // Convierte el número de columna (0-indexed) en letra de columna
      var columnLetter = String.fromCharCode(65 + i);
      return columnLetter;
      //return i + 1; // Sumamos 1 para obtener el número de columna (1-indexed)
    }
  }
  return -1;//not found
}

function getForecastColumnLetterOfTomorrow() {
  var forecastColumnLetterOfToday = getForecastColumnLetterOfToday();
  if (forecastColumnLetterOfToday === -1) {
    // La fecha de hoy no se encontró en la fila "date"
    return -1; // No se puede determinar la columna de mañana
  }

  // Convierte la letra de columna de hoy en número (0-indexed)
  var todayColumnNumber = forecastColumnLetterOfToday.charCodeAt(0) - 65;

  // Calcula el número de columna para mañana (sumando 1 al número de columna de hoy)
  var tomorrowColumnNumber = todayColumnNumber + 1;

  // Convierte el número de columna de mañana en letra de columna
  var tomorrowColumnLetter = String.fromCharCode(65 + tomorrowColumnNumber);

  return tomorrowColumnLetter;
}

function buildFinalPromptOfVars(varNameToReplace,promptVarsNewValues) {
  var ss = SpreadsheetApp.getActiveSpreadsheet();
  var sheet = ss.getSheetByName('newsData');
  var startCell = ss.getRangeByName('promptsInfoVarName');
  var promptVarsRange = sheet.getRange(startCell.getRow(), startCell.getColumn(), (sheet.getLastRow() - startCell.getRow() + 1), 7);
  var data = promptVarsRange.getValues();
  var promptVarsRangeA1notation = promptVarsRange.getA1Notation();
  
  var varsPromptsWithCoords = getTableDataWithCellCoordinates(data,sheet,promptVarsRangeA1notation,startCell.getRow());
  var promptContent = 'UNKNOWN';
  
  // Iterar todas las VAR NAME
  for (var i = 0; i < varsPromptsWithCoords.length; i++) {
    var row = varsPromptsWithCoords[i];
    var varName = row[0]['cellvalue'];
    promptContent = row[1]['cellvalue'];
    var promptVars = row[2]['cellvalue'];
    var promptVarsCellPath = row[2]['cellpath'];
    var finalPrompt = row[5]['cellvalue'];
    if (varNameToReplace != null && varNameToReplace != varName) {
      continue;
    }

    try {
      //leer el contenido de las variables de la variable
      var parsedVars = JSON.parse(promptVars);
    } catch (e) {
      continue;//avoid non-json cells
    }
    if (varName == "") {//empty row=end of rows
      break;
    }

    //si se enviaron valores nuevos, actualizar las variables de la variable
    if (promptVarsNewValues != null && (Object.keys(promptVarsNewValues).length > 0)) {
      try {
        for (var key in promptVarsNewValues) {
          if (promptVarsNewValues.hasOwnProperty(key)) {
            parsedVars[key] = promptVarsNewValues[key];
          }
        }
        //ss.getRangeByName('errorMsgCronista').setValue(errorInfo);
        // Convertir el objeto a una cadena JSON
        var promptVarsNewValuesJsonString = JSON.stringify(promptVarsNewValues);
        sheet.getRange(promptVarsCellPath).setValue(promptVarsNewValuesJsonString);
      } catch (e) {
        return "";//invalid prompt vars provided. No prompt will be retrieved
      }
    }

    // Realizar reemplazo de variables en el contenido
    for (var key in parsedVars) {
      var value = parsedVars[key];

      // Verificar si el valor contiene una función
      var functionRegex = /(\w+)\(\)/;
      var match = value.match(functionRegex);
      if (match) {
        // Si el valor contiene una función, ejecutarla y reemplazar el resultado
        var functionName = match[1];
        var result = null;
        switch (functionName) {
          case 'getForecastColumnLetterOfToday':
            result = getForecastColumnLetterOfToday();
            if (result == -1) {
                Logger.log("ERROR! getForecastColumnLetterOfToday() not found. Skipping this item");
                //continue; se va a loop infinito. Pendiente averiguar el motivo y fixearlo
            }
            break;
          case 'getForecastColumnLetterOfTomorrow':
            result = getForecastColumnLetterOfTomorrow();
            if (result == -1) {
                Logger.log("ERROR! getForecastColumnLetterOfTomorrow() not found. Skipping this item");
                //continue; se va a loop infinito. Pendiente averiguar el motivo y fixearlo
            }
            break;
          default:
            result = 'INVALID_FUNCTION_NAME';
        }
        value = value.replace(match[0], result);
        var cellValue = sheet.getRange(value).getValue();
        promptContent = promptContent.replace('{' + key + '}', cellValue);
      } else {
        // Si no contiene una función, obtener el contenido de la celda
        var cellValue = sheet.getRange(value).getValue();
        promptContent = promptContent.replace('{' + key + '}', cellValue);
      }
    }
    
    // Guardar el resultado en la columna FINAL PROMPT
    sheet.getRange(i + startCell.getRow(), 6).setValue(promptContent);
    return promptContent;
  }
}

function getVarCellsRange(newsDataSheet,varName) {
  // Obtener los datos de la hoja de noticias
  var data = newsDataSheet.getRange("A26:G35").getValues();
  
  // Iterar sobre las filas de datos
  for (var i = 1; i < data.length; i++) { // Empezar desde la segunda fila (la primera fila tiene los encabezados)
    // Verificar si el nombre de la variable coincide
    if (data[i][0] === varName) {
      // Devolver el valor de la celda VAR INDEX CELL NAME
      return data[i][6];
    }
  }
  
  // Si no se encuentra la variable, devolver nulo
  return null;
}

function getVarType(varName) {
  // Obtener los datos de la hoja de noticias
  var ss = SpreadsheetApp.getActiveSpreadsheet();
  var newsDataSheet = ss.getSheetByName("newsData");
  var data = newsDataSheet.getRange("A26:G35").getValues();
  
  // Iterar sobre las filas de datos
  for (var i = 1; i < data.length; i++) { // Empezar desde la segunda fila (la primera fila tiene los encabezados)
    // Verificar si el nombre de la variable coincide
    if (data[i][0] === varName) {
      // Devolver el valor de la celda VAR TYPE
      return data[i][3];
    }
  }
  
  // Si no se encuentra la variable, devolver nulo
  return null;
}

function BARD(prompt) {
  if (prompt == null || prompt.length < 1) {
    prompt = "Write a line about the current wheather";
  }
  //var api_key = "AIzaSyB4TPDlbfnOpyuWdT9wFOiyzHA2N7Son_A";
  var api_key = "AIzaSyAQD9gqZQa9VB1nMUDMwvf_k-A-igHbQfM";//gemini pro
  //var apiurl = "https://generativelanguage.googleapis.com/v1beta3/models/text-bison-001:generateText";

 // -d '{"contents":[{"parts":[{"text":"Write a story about a magic backpack"}]}]}' \

  var apiurl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent";
  var url = apiurl + "?key=" + api_key;
  var headers = {
    "Content-Type": "application/json"
  };
  var requestBody = {
    "contents": [{
      "parts": [{
        "text": prompt
      }]
    }]
  }
  var options = {
    "method": "POST",
    "headers": headers,
    "payload": JSON.stringify(requestBody)
  }
  var response = UrlFetchApp.fetch(url,options);
  var data = JSON.parse(response.getContentText());
  var output = data.candidates[0].content.parts[0].text;
  Logger.log(output);
  return output;
}
