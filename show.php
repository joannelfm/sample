
<script type="text/javascript">
$(function() {
    $('#form1').each(function() {
        $('input').keypress(function(e) {
            // Enter pressed?
            if(e.which == 10 || e.which == 13) {
                this.form.submit();
            }
        });

        $('input[type=submit]').hide();
    });
	
	$("#searchkey").val($("#searchkey").attr("default"));
});

function trace_droprate(c, hid){
	var web_path = window.location.href;
	$.ajax({
	type: "POST",
	crossDomain: true,
	url: "http://checktraffic.php",
	cache: false,
	data: {c: c , hid: hid},
		error:function (xhr, ajaxOptions, thrownError){
		//alert(xhr.status);
		//alert(thrownError);
		},
		success: function(){
		//alert("done");
		}
	})
}

function keywordsearch(c,s,m){

	switch(m){
		case "ratedown":
			var sby = "rate";
			var sorder = "desc";
			break;
		case "rateup":
			var sby = "rate";
			var sorder = "asc";
			break;
		case "engnameup":
			var sby = "engname";
			var sorder = "asc";
			break;
		case "engnamedown":
			var sby = "engname";
			var sorder = "desc";
			break;
		case "rate":
			var sby = "rate";
			var sorder = "asc";
			break;				
		default:
			var sby = "engname";
			var sorder = "asc";
	}	
	
	if (s == "name")
		s = ""	
	
	if (s == "rateall"){
		s = "";
		sby = "rate";
		sorder = "desc";
	}
	
	window.parent.location.href = "http://website/?a=hotel&start=1&cityid="+c+"&orderby="+sby+"&orderseq="+sorder+"&search="+encodeURI(s)+"&item=15&totalitem="
}

function showmap(la, lo, c, n, i){
	$(".google_map").attr("src","http://website/gmap.html?la="+la+"&lo="+lo+"&c="+c+"&e="+n);
	$(".showmap").css("top",(150*i)+"px");
	$(".showmap").show();
}

function searchStyle(TxtBox){
	var val = $("#"+TxtBox).val();
	
	if (val != $("#"+TxtBox).attr("default")){
		$("#"+TxtBox).val(val);
	}else
		$("#"+TxtBox).val("");
		
	if (val.length == 0){
		$("#"+TxtBox).val($("#"+TxtBox).attr("default"));
	}
	
}

</script>

<body>
<div class="wrapper">
<?php 
include '../connectstr.php';

	$cityID = $_GET['cityid'];
	$orderBy = $_GET['orderby'];
	$orderSEQ = $_GET['orderseq'];
	$startpage = $_GET['start'];
	$searchKey = $_GET['search'];
	$totalitem = $_GET['totalitem'];
	$qString = "";

	if ($orderBy == "default")
		$orderBy = "rate";
	else
		$orderBy = $_GET['orderby'];
		
	if ($orderSEQ == "default")
		$orderSEQ = "desc";
	else
		$orderSEQ = $_GET['orderseq'];	
		
	if ($searchKey != ""){	
		if ($orderBy == "rate"){
			$qString = " and rate = '".$searchKey."'";
			$showKey = "";
			$displaySearchBox = "";
		}
		else{
			$qString = " and (engname like '%".$searchKey."%' or chiname like '%".$searchKey."%' or address like '%".$searchKey."%')";
			$showKey = $searchKey;					
		}
	}else
		$displaySearchBox = ";display:none";

	$startImg = 0;
	$ImgPerPage = 15;	
	
	$result = mysql_query("SELECT id FROM hotelinfo where status='Y' and cityid='".$cityID."'".$qString);
	$numrows = mysql_num_rows($result);
	
	if ($totalitem != ""){
		$numrows_t = $totalitem;
	}else{
		$numrows_t = $numrows;
	}	
	
	$maxPage = ceil($numrows_t/$ImgPerPage);	
	$itemRemainder = $numrows_t % $ImgPerPage;

	if (($searchKey != "") && ($totalitem == "")){
		if ($numrows_t < ($ImgPerPage + 1)){
			if ($itemRemainder > 0){
			$showsearchitem = $itemRemainder;
			}else{
			$showsearchitem = 	15;	
			}
		}else{
			$showsearchitem = $ImgPerPage;
		}		
?>		
<script type="text/javascript">		
	window.parent.location.href = "http://website/?a=hotel&start=<?php echo $startpage ?>&cityid=<?php echo $cityID ?>&orderby=<?php echo $orderBy ?>&orderseq=<?php echo $orderSEQ ?>&search=<?php echo urlencode($searchKey) ?>&item=<?php echo $showsearchitem ?>&totalitem=<?php echo $numrows_t ?>";
</script>	
<?php }
	
	if (($startpage == ($maxPage - 1)) || (($searchKey != "") && ($startpage == ($maxPage - 1)))){
		if ($itemRemainder > 0){
			$itemRemainder_value = 	$itemRemainder;		
		}else{
			$itemRemainder_value = 	15;			
		}
	}else{
		$itemRemainder_value = 	$ImgPerPage;
	}	
	

?>	<input type="hidden" class="no_of_item" value="<?php echo $itemRemainder_value ?>" />
<div class="left poweredby_row"><div class="left poweredby">sponsor</div><div class="agoda_logo left"></div></div>
<div class="thumb_box left">	
    <div class="nav">
    	<span class="showtoponly">              
        
		<div class="choosecitylist left">
        	choice<br />
        	<select class="htcntry">
<?php
			$select_selCntry = "SELECT id,chiname FROM hotelcity where status='Y'";	
			$resultcntry = mysql_query($select_selCntry); 
					
			while ($row = mysql_fetch_array($resultcntry)) {
				if ($row["id"] == $cityID){
					$cityselected = "selected";			
				}else{
					$cityselected = "";			
				}				
				echo "<option value='".$row["id"]."' ".$cityselected.">".$row["chiname"]."</option>";
			}
			mysql_free_result ($resultcntry);
?>
            </select>
            
            
            <input type="button" value="Go" onclick="window.parent.location.href='http://website/?a=hotel&start=1&cityid='+$('.htcntry').val()+'&orderby=rate&orderseq=desc&search='" />
        </div>     	
        <div class="left SelectionBox orderSelectBox">
        <?php
			if (($orderBy == "rate") && ($orderSEQ == "desc")){
				$ratedownSel = "selected=true";
			}
			
			if (($orderBy == "rate") && ($orderSEQ == "asc")){
				$rateupSel = "selected=true";
			}
			
			if (($orderBy == "engname") && ($orderSEQ == "asc")){
				$engnameupSel = "selected=true";
			}
			
			if (($orderBy == "engname") && ($orderSEQ == "desc")){
				$engnameupSe2 = "selected=true";
			}				
		?>
        	order<br />
            <select class="sortmethod">                        
                <option value="engnameup" <?php echo $engnameupSel ?>>name asc</option>        
                <option value="engnamedown" <?php echo $engnameupSe2 ?>>name desc</option>                        
            	<option value="ratedown" <?php echo $ratedownSel ?>>smiley face</option>
                <option value="rateup" <?php echo $rateupSel ?>>upset face</option>      
            </select>    
            <input type="button" value="Go" onclick="keywordsearch($('.htcntry').val(),'',$('.sortmethod').val())" />
        </div>
        <div class="left SelectionBox MarkSelectBox">
        	score<br />
        	<select class="sortmethod rate_no" style="width: 56px;">  
            <option value="rateall">all</option>
            <?php
				for ($j=6; $j > 0; $j--)
				{
					if ($j == $searchKey)
						$ratehl = " selected";
					else
						$ratehl = "";
						
					echo "<option value='".$j."' ".$ratehl.">".$j."</option>";
				}
			?>            
            </select>         
            <input type="button" value="Go" onclick="keywordsearch($('.htcntry').val(),$('.rate_no').val(),'rate')"/>
        </div>
        <div class="left SelectionBox CustomSelectBox">      
                
        search<br />
        <input class="searchkey" id="searchkey" type="text" value=""  default="name" onFocus="searchStyle('searchkey')" onblur="searchStyle('searchkey')" /><input class="ksearch" type="button" value="Go" onclick="keywordsearch($('.htcntry').val(),$('.searchkey').val(),'')"/>                  	
        </div>
        <div class="clr"></div>
        <div class="left"><?php echo $numrows ?></div>
        <div class="left" style="margin:0 5px<?php echo $displaySearchBox ?>" >search word: <?php echo $showKey ?></div>        
        </span>
        <div style="float: right; width: 180px;"><div style="float:right; margin-top:5px"><?php echo $maxPage ?></div>
        <a class="navpre" href="" target="_parent"><div class="sc_prev"></div></a><div style="float:left"><form id="form1" action="show.php" method="get">第 <input class="start" name="start" value="<?php echo $startpage ?>" size="2" style="width: 19px;"/>頁<input type="hidden" name="cityid" value="<?php echo $cityID ?>" /><input type="hidden" name="orderby" value="<?php echo $orderBy ?>" /><input type="hidden" name="orderseq" value="<?php echo $orderSEQ ?>" /><input type="hidden" name="search" value="<?php echo $searchKey ?>" /><input type="submit" style="display:none"/></form></div><a class="navnext" href="" target="_parent"><div class="sc_next"></div></a></div>
       	</div>
    </div>
	<script type="text/javascript">
<?php	
	if(isset($startpage))
	{
		if ($startpage >= $maxPage){
			$startImg = (($maxPage - 1)  * $ImgPerPage) + $startImg;	
?>
			
				$(".start").val(<?php echo $maxPage; ?>);
				$(".navnext").removeAttr("href");
				$(".navnext").css("opacity","0.3");
				$(".navnext").css("filter","alpha(opacity=30)");				
				$(".navpre").attr("href","http://website/?a=hotel&start=<?php echo $maxPage-1 ?>&cityid=<?php echo $cityID ?>&orderby=<?php echo $orderBy ?>&orderseq=<?php echo $orderSEQ ?>&search=<?php echo $searchKey ?>&item=<?php echo $itemRemainder_value ?>&totalitem=<?php echo $totalitem ?>");
<?php			
		}else{
			$startImg = (($startpage - 1) * $ImgPerPage) + $startImg;
?>
				$(".start").val(<?php echo $startpage; ?>);
<?php 
				if ($startpage > 1){
?>
				$(".navpre").attr("href","http://website/?a=hotel&start=<?php echo $startpage-1 ?>&cityid=<?php echo $cityID ?>&orderby=<?php echo $orderBy ?>&orderseq=<?php echo $orderSEQ ?>&search=<?php echo $searchKey ?>&item=<?php echo $itemRemainder_value ?>&totalitem=<?php echo $totalitem ?>");
<?php } else { ?>

				$(".navpre").removeAttr("href");
				$(".navpre").css("opacity","0.3");
				$(".navpre").css("filter","alpha(opacity=30)");
<?php }
				if ($startpage < $maxPage){
?>			
				$(".navnext").attr("href","http://website/?a=hotel&start=<?php echo $startpage+1 ?>&cityid=<?php echo $cityID ?>&orderby=<?php echo $orderBy ?>&orderseq=<?php echo $orderSEQ ?>&search=<?php echo $searchKey ?>&item=<?php echo $itemRemainder_value ?>&totalitem=<?php echo $totalitem ?>");
<?php } 
		}
	}else{
?>
				$(".navnext").attr("href","http://website/?a=hotel&start=2&cityid=<?php echo $cityID ?>&orderby=<?php echo $orderBy ?>&orderseq=<?php echo $orderSEQ ?>&search=<?php echo $searchKey ?>&item=<?php echo $itemRemainder_value ?>&totalitem=<?php echo $totalitem ?>");
	 
<?php
	}
?>	
	</script>
<?php	
	$selectQ = "SELECT * FROM hotelinfo where status='Y' and cityid='".$cityID."'".$qString." order by ".$orderBy." ".$orderSEQ." limit ".$startImg." ,".$ImgPerPage;
	
	$result = mysql_query($selectQ); 
	
	if ($numrows > 0){
		$countDiv = 1;
	while ($row = mysql_fetch_array($result)) {	
		$hid = $row["id"];
		$chiname = $row["chiname"];
		$engname = $row["engname"];
		$latitude = $row["latitude"];
		$longtitude = $row["longtitude"];		
		$address = $row["address"];
		$agodalink = $row["agodalink"];
		$rate = $row["rate"];
		$photoimg = $row["photoimg"];
		
?>	
        <div class="listingitem">
        <a target="_blank" onclick="trace_droprate(<?php echo $cityID ?>,<?php echo $hid ?>)" href="http://sponsor/zh-hk<?php echo $Link ?>?cid=1510318">                    
           <div class="ht_name"><div><?php echo $chiname ?>       
		<?php
			if ($engname != ""){
				echo "(".$engname.")";
			}
		?>
        	</div>
            <div class="ht_address">
               <?php echo $address ?>         
            </div>
           </div>
        </a>   
	      <div class="ht_star">評分: 
		  	<?php 
				for ($i=0; $i<$rate; $i++){
					echo "<img src='images/star.png'>";
				}
			?>
          </div>
           <div class="ht_map" onclick="showmap(<?php echo $latitude ?>,<?php echo $longtitude ?>,'<?php echo $chiname ?>','<?php echo str_replace("'","`",$engname) ?>',<?php echo $countDiv ?>)">
               <img src="images/btn_map.png" />  
            </div>
          
                    
           <div class="ht_img_wrapper">
			<?php
				if (strrpos($photoimg, "|") != false){
					$hotelimg = explode("|", $photoimg);
					
					for ($j = 0; $j < (sizeof($hotelimg)-1); $j++) {					
						echo "<div class='ht_img'><img src='".$hotelimg[$j]."' onload=\"ResizeImage(this, 105, 79);this.style.display='block';\"></div>";
					} 				
				}else
						echo "<div class='ht_img'><img src='".$photoimg."' onload=\"ResizeImage(this, 105, 79);this.style.display='block';\"></div>";
			?>
           </div>	
             <!-- ht_img_wrapper end -->             
           <a onclick="trace_droprate(<?php echo $cityID ?>,<?php echo $hid ?>)" href="http://www.sponsor.com/zh-hk<?php echo $link ?>?cid=1510318" target="_blank" >
               <div class="enqiry"><img src="images/btn_enquiry.png" /></div>
           </a>             
           </div>           
<?php		
			 $countDiv ++;
		}
	}
	else{
		echo "<div style='float:left; width:660px; margin-left:100px'><a href='show.php?start=1&cityid=".$cityID."&orderby=rate&orderseq=desc&search=' style='text-decoration:underline'>查看所有酒店</a></div>"; 
	}


	mysql_free_result ($result);
	mysql_free_result ($numrows);
?>
    <div class="clr"></div>
    <div class="nav2"></div>
<script type="text/javascript">
	<?php
		if ($maxPage == 1){ ?>
		$(".navnext").removeAttr("href");
		$(".navpre").removeAttr("href");
	<?php } ?>

	$(".nav2").html($(".nav").html());
	$(".nav2 .start").val($(".nav .start").val());
	$(".nav2 .subtitle").hide();
	$(".nav2 .showtoponly").hide();
</script>

</div>
 <div class="showmap">
 	<div class="mapwrapper">
	 	<div class="closebtn" onclick="$('.showmap').hide()">X</div>		
  		<iframe class="google_map" width="570" height="430" scrolling="no" frameborder="0" src=""></iframe>
    </div>
 </div>
</div>
</body>
</html>
<?php
mysql_close($con);	
?>