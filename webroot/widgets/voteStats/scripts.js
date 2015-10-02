jQuery(document).ready(function() {
$.getJSON("https://spreadsheets.google.com/feeds/list/1eaobB4Tiqzx206P2-wtgB4EcuIiPweTGzig-L_HStAU/od6/public/values?alt=json-in-script&callback=?",function(data){
	console.log(data);
    
	data=data.feed.entry;
	$('#candidateBox1 .politicalGroupName p').append(data[0].gsx$partido1.$t);
    $('#candidateBox1 .groupChiefSurname p').append(data[1].gsx$partido1.$t);
    $('#candidateBox1 .groupPercentAmount p').append(data[2].gsx$partido1.$t);
    //$("#candidateBox1 .groupChiefImage img").attr("src", data[3].gsx$partido1.$t);
    
    $('#candidateBox2 .politicalGroupName p').append(data[0].gsx$partido2.$t);
    $('#candidateBox2 .groupChiefSurname p').append(data[1].gsx$partido2.$t);
    $('#candidateBox2 .groupPercentAmount p').append(data[2].gsx$partido2.$t);
    //$("#candidateBox2 .groupChiefImage img").attr("src", data[3].gsx$partido2.$t);
    
    $('#candidateBox3 .politicalGroupName p').append(data[0].gsx$partido3.$t);
    $('#candidateBox3 .groupChiefSurname p').append(data[1].gsx$partido3.$t);
    $('#candidateBox3 .groupPercentAmount p').append(data[2].gsx$partido3.$t);
    //$("#candidateBox3 .groupChiefImage img").attr("src", data[3].gsx$partido3.$t);
	/*
    calendarActivo = new String();
	calendarActivo = data[0].gsx$activo.$t;
	if ( calendarActivo.toLowerCase() == "si"){
		$('#box_calendar_comuna1 .fechaCalendario').append(data[0].gsx$dia.$t);
		$('#box_calendar_comuna1 .txtBarrio').append(data[0].gsx$barrio.$t);
		$('#box_calendar_comuna1 .txtComuna').append(data[0].gsx$comuna.$t);
	}else{
		$('#box_calendar_comuna1').css("visibility","hidden");
	}
	calendarActivo = data[1].gsx$activo.$t;
	if ( calendarActivo.toLowerCase() == "si"){
		$('#box_calendar_comuna2 .fechaCalendario').append(data[1].gsx$dia.$t);
		$('#box_calendar_comuna2 .txtBarrio').append(data[1].gsx$barrio.$t);
		$('#box_calendar_comuna2 .txtComuna').append(data[1].gsx$comuna.$t);
	}else{
		$('#box_calendar_comuna2').css("visibility","hidden");
	}
    */
})
})