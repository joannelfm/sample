// Display wishing tree in aspect ratio

$(document).ready(function() {
	
	var treeDiv = [];
	
	for (i=0;i<=$(".wishtree").length-1;i++)
	{
    treeDiv[i]=$("#tree_"+(i+1)).html();
	}
	
	$("#rcontent").empty();
	
	for (j=treeDiv.length; j > -1; j--)
	{
	$("#rcontent").append(treeDiv[j]);
	}
	
	if ($(".tree_11").length > 0){
		$("#rcontent").css("width","682px");
	}
	
	if ($(".tree_21").length > 0){
		$("#rcontent").css("width","927px");
	}
	
	if ($(".tree_31").length > 0){
		$("#rcontent").css("width","832px");

	}
	
	if ($(".tree_41").length > 0){
		$("#rcontent").css("width","726px");

	}
	
	if ($(".wishtree").length == 2){
		$(".tree_22").css("margin-top","50px");
	}
		
});

// -->
</script>
<link rel="stylesheet" type="text/css" href="style.css" />
<title>wish tree</title>
</head>

<body>

<?php 
include 'connectstr.php';
include 'functions.php';

$query_nr = mysql_query("select id from 2012cny_wish where status=1");
$num_rows = mysql_num_rows($query_nr);

//$num_rows = 72;

$num_of_orange = 18;

$num_of_pos = 72;

$no_of_tree = ceil($num_rows/$num_of_orange);

$selectQ = "select wish_cat,id,sender_name from 2012cny_wish where status=1 order by id";
$result = mysql_query($selectQ);

$tree_left = array(/*1*/75,80,384,489,541,448,510,323,570,640,486,141,390,325,292,205,226,80,/*2-19*/234,160,29,145,220,80,170,96,484,479,554,380,390,300,185,130,415,276,/*3-37*/240,152,100,100,434,510,580,478,400,240,300,200,380,300,170,107,20,20,/*4-55*/423,20,230,442,600,504,619,580,350,320,242,134,160,5,80,100,90,10);
$tree_top = array(/*1*/328,100,490,410,370,285,270,195,250,210,160,105,140,62,462,340,231,250,/*2-19*/385,380,330,320,305,320,230,188,110,260,230,358,260,216,110,85,195,100,/*3-37*/135,107,90,290,280,240,190,165,380,280,500,440,108,40,290,229,270,180,/*4-55*/190,296,300,350,220,194,160,106,82,300,150,265,175,224,220,110,40,140);
$orange_width = array(62,45,40,36);
$orange_height = array(58,42,38,34);

?>

<div id="wrapper">
	<div id="content">
    	<div class="top left">
        	<div class="left"><a href="http://website" target="_blank"><img src="http://website/hk/images/spacer.gif" height="40" width="200"/></a></div>
             
            </div>
            <div id="mallbtn"><a target="_blank" href="http://88mall.website/"><img border="0" src="images/.png"></a></div>
        </div>
        <div class="title">
        	<div class="logo"><img src="images/title.gif" /></div>
        	<div class="lead-in"></div>
            <div class="left"><a href="form.php"><img src="images/btn_go_wish.gif" /></a></div>
        </div>
    	<div class="clear"></div>
        <div id="rcontent">
<?php 
$wish_count = 0;
$tree_no = 1;
$endDiv = 1;
$actual_tree_no = 1;

while ($row = mysql_fetch_row($result)) {
		
		$wish_cat = $row[0];
		$wish_id = $row[1];
		$sender_name = $row[2];
		
			if (($wish_count % $num_of_orange) == 0){	
		
				if ($no_of_tree > 4){
					$no_of_tree = 4;		
				}
				if ($tree_no > 4){
					$tree_no = 1;
				}				
		?>
			<div id="tree_<?php echo $actual_tree_no; ?>">	
			
			<div class="tree_<?php echo $no_of_tree.$tree_no ?> wishtree"><div class="display_tree tr_name_<?php echo $no_of_tree.$tree_no ?>">第<?php echo $actual_tree_no; ?>棵</div>	        
		<?php
			$tratio=$no_of_tree.$tree_no;
			}
			$wish = "<a href='view.php?id=".$wish_id."'><div class='wish' style='position: absolute;width: ".$orange_width[$no_of_tree-1]."px;height:".$orange_height[$no_of_tree-1]."px;top:".round($tree_top[$wish_count % $num_of_pos]* per_ratio($tratio))."px;left:".round($tree_left[$wish_count % $num_of_pos] * per_ratio($tratio))."px' Onmouseover=\"$('#".$wish_id."').show();\" Onmouseout=\"$('#".$wish_id."').hide();\"><div class='orange'><img src='images/".$wish_cat.$no_of_tree.".png'></div><div class='wishboard' id='".$wish_id."'>".$sender_name."</div></div></a>";
			
			echo $wish;
			
			if (($wish_count % $num_of_orange) == ($num_of_orange - 1)){
				echo "</div></div>";
			}	
			
			if (($wish_count % $num_of_orange) == 0){
				$tree_no++;
				$actual_tree_no++;
			}		
		
		$wish_count++;	
	
}

mysql_close($con);
?>	
</div>	
	</div>   
</div>
</body>
</html>
