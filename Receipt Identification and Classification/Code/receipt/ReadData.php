<?php require 'vendor/autoload.php';
   
    
    
   //echo "Access 1";
   



   
   $client = new  MongoDB\Client("mongodb://localhost:27017");
   //echo "connect client";
$collection = $client->Receipt_db->categorization;
//echo "get collection";
$cursor = $collection->find();
   
    //echo "connect ot mongo db";
    $data  = "<table style='border:1px solid red;";
    $data .= "border-collapse:collapse' border='1px'>";
    //$data .= "<thead>";
    $data .= "<tr>";
    //$data .= "<th>itemid</th>";
    $data .= "<th>category</th>";
    $data .= "<th>price</th>";
    //$data .= "<th>date</th>";
	//$data .= "<th>type</th>";
    $data .= "</tr>";
    //$data .= "</thead>";
    $data .= "<tbody>";
 
    $myArray =  array(); 
       
        foreach($cursor as $document){
            $data .= "<tr>";
            //$data .= "<td>" . $document["itemid"] . "</td>";
            $data .= "<td>" . $document["category"] ."</td>";
            $data .= "<td>" . $document["price"] ."</td>";
            //$data .= "<td>" . $document["date"] ."</td>";
			//$data .= "<td>" . $document["type"] ."</td>";
            $data .= "</tr>";
           $myArray[] = array("label"=>$document["category"], "y"=>$document["price"]);
		   //echo $myArray;

        }
		
		//echo $myArray;
        $data .= "</tbody>";
        $data .= "</table>";
		
       
?>
<!DOCTYPE HTML>
<html>
<head>
<script>
window.onload = function() {
 
 
var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	title: {
		text: "Report"
	},
	
	data: [{
		type: "pie",
		yValueFormatString: "#,##0.00\"Rs\"",
		indexLabel: "{label} ({y})",
		dataPoints: <?php echo json_encode($myArray, JSON_NUMERIC_CHECK); ?>
	}]
});
chart.render();
 
}
</script>
</head>
<body>
<div id="chartContainer" style="height: 100%; width: 100%;"></div>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

</body>
</html>              