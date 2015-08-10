<?php 
        error_reporting(E_ALL ^ E_NOTICE); 
	include("apiKey.php");       

function getAvailability($number) { 
	global $apiKey;

        $url = "https://www.eventbrite.com/json/event_get?id=".$number."&app_key=" . $apiKey;
        $content = file_get_contents($url); 
        $json = json_decode($content,true);
        $event = $json["event"];
        $capacity = $event["capacity"];
        $attending = $event["num_attendee_rows"];
        if ($attending >= $capacity) {
                return "false";
        } else {
                return "true";
        }
}

	$url = $_GET["url"];     
        if (!$url) { 
                $url = "http://contentapi.theodi.org/with_tag.json?type=course_instance";        
        } 
        $content = file_get_contents($url); 
        $json = json_decode($content,true); 
        $results = $json["results"]; 
        for ($i=0;$i<count($results);$i++) { 
                $num = null; 
                $link = $results[$i]["details"]["url"]; 
                $results[$i]["details"]["ticketsAvailable"] = "true"; 
                if (strpos($link,"eventbrite") !== false) { 
                        $num = $link; 
                        if (strpos($num,"?") !== false) $num = substr($link,0,strpos($link,"?")); 
                        if (strpos($num,"/") !== false) $num = substr($num,strrpos($num,"/")+1,strlen($num)); 
                        if (strpos($num,"-") !== false) $num = substr($num,strrpos($num,"-")+1,strlen($num)); 
                } 
//                if ($num) { 
//                        $results[$i]["details"]["ticketsAvailable"] = getAvailability($num); 
//                } 
        } 
        $json["results"] = $results; 
        $content = json_encode($json); 
 
        if (isset($_SERVER['HTTP_ORIGIN'])) { 
                header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}"); 
                header('Access-Control-Allow-Credentials: true');     
                header("Access-Control-Allow-Methods: GET, POST, OPTIONS");  
        }    
                        // Access-Control headers are received during OPTIONS requests 
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { 
                header("Access-Control-Allow-Headers: *"); 
        } 
        header('Content-Type: application/json'); 
        echo $content; 
?> 

