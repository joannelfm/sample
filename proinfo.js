// JavaScript Document

var querystring = location.search.replace( '?', '' ).split( '&' );
var queryObj = {};
// loop through each name-value pair and populate object
for ( var i=0; i<querystring.length; i++ ) {
	// get name and value
	var name = querystring[i].split('=')[0];
	var value = querystring[i].split('=')[1];
	// populate object
	 queryObj[name] = value;
}

var pro_id = queryObj["p"];

var ProImg_src = new Array();
ProImg_src[0] = "/hk/html/images/property/"+pro_id+"/lat_01.jpg";
ProImg_src[1] = "/hk/html/images/property/"+pro_id+"/lat_02.jpg";
ProImg_src[2] = "/hk/html/images/property/"+pro_id+"/lat_03.jpg";

function showmap(la, lo, p){	

    var latlng = new google.maps.LatLng(la,lo);
    var myOptions = {
      zoom: 17,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
	var myMarker = new google.maps.Marker({
	  position: latlng,
	  map: map,
	  icon:"/hk/html/images/property/"+p+"/1.png"
	}); 
}

function PropertyPre1(){
	var currVal = document.getElementById("currCubeAlbum").value;
		currVal = parseInt(currVal);
		
	var obj2=$(".AlbumArrow");						
	
	if(currVal <=0){
		currVal3 = 2;
	}else{
		currVal3 = currVal-1;
	}

	document.getElementById("currCubeAlbum").value=currVal3;
	document.getElementById("PropImg").src=ProImg_src[currVal3];
}

function PropertyNext1(){
	var currVal = document.getElementById("currCubeAlbum").value;
		currVal = parseInt(currVal); 
		
	var obj2=$(".AlbumArrow");						
	var p2 = obj2.css("background-position"); 

	if(currVal <2){
		currVal3 = currVal+1;
	}else{
		currVal3 = 0;  
	}
	document.getElementById("currCubeAlbum").value=currVal3;
	document.getElementById("PropImg").src=ProImg_src[currVal3];
}

function gendata(p){
	$.ajax({
		type: "GET",
		url: "/hk/html/xml_GoogleMap/propertyinfo"+p+".xml",
		dataType: "xml",
		success: function(xml) {
			var itemcount = 0;
			var addvalue = 0;

			$(xml).find("pinfo[id='"+pro_id+"']").each(function(){
				
				if ($(this).find('show_gallery').text() == "n")
					$(".galleryslider").hide();
					
				$(".name").text($(this).find('name').text());
				$(".address").text($(this).find('address').text());
				$(".developer").text($(this).find('developer').text());
				var fpformat = $(this).find('fpformat').text();
				showmap($(this).find('latitude').text(),$(this).find('longtitude').text(),pro_id);
				
				if ($(this).find('floorplan').text() != ""){
					var n = $(this).find('floorplan').text().split(",");
					var fString = "";
					
					for (j=0; j < n.length; j++)
						fString = fString + "<tr><td style='width: 227px; height:29px' valign='middle'>"+n[j]+"</td><td align='right'><a href='/hk/html/images/property/"+pro_id+"/fp_"+(j+1)+"."+fpformat+"' target='new'><img height='22' width='70' alt='' src='../html/zh-hk/templates/landingpage/images/property/PropertyV2/btnDL.png' /></a></td></tr>"					
					
					$(".floorplan .content").html(fString)
				}
				
				$(".openday").text($(this).find('openday').text());
				$(".opentime").text($(this).find('time').text());
				$(".openaddress").text($(this).find('openaddress').text());
				$(".opentel").text($(this).find('opentel').text());
				$(".open_remk").text($(this).find('open_remk').text());				
				$(".type").text($(this).find('type').text());
				$(".pro_region").text($(this).find('region').text());
				$(".unit").text($(this).find('unit').text());
				$(".division").text($(this).find('division').text());
				
				if ($(this).find('price').text() !="")
					$(".price").text($(this).find('price').text());					
				else
					$(".price_wrapper").text("");
					
				$(".view").text($(this).find('view').text());
				$(".direction").text($(this).find('direction').text());
				$(".finishdate").text($(this).find('finishdate').text());
				$(".noofunit").text($(this).find('noofunit').text());
				$(".block").text($(this).find('block').text());	
				$(".flat").text($(this).find('flat').text());
				$(".flat_remark").text($(this).find('flat_remark').text());
				$(".block_no").text($(this).find('block_no').text());
				$(".management").text($(this).find('management').text());
				$(".lift").text($(this).find('lift').text());				
				$(".address").text($(this).find('address').text());
				$(".nearbyfac").text($(this).find('nearbyfac').text());
				$(".school").text($(this).find('school').text());
				$(".club").html($(this).find('club').text());
				$(".imgsrc").text($(this).find('imgsrc').text());				
				
				$(".floorplan").nanoScroller({alwaysVisible: true});
			});	
		}
     });	
}

$(function() {
	
	var proImg = "";
	
	for (i=0; i<3; i++)
		proImg = proImg + "<li><img height='48' width='72' alt='' src='/hk/html/images/property/"+pro_id+"/lat_0"+(i+1)+".jpg'></li>"		
		
	
	$("#PropImg").attr("src","/hk/html/images/property/"+pro_id+"/lat_02.jpg");
	
	$(".PropertyAlbum ul").html(proImg)

	$(".PropertyAlbum").jCarouselLite({
		btnNext: ".AlbumNext",
        btnPrev: ".AlbumPrevi",
		vertical: false,
		visible: 3,//3
		speed:300
	});	
});	