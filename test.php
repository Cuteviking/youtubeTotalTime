<?php
/*
 * Total Time
* Counts total time of all your videos
* Looks for every "Day" + number
* Pierre Norrbrink
*
*/
$key = "AIzaSyADKVLQ7rAX3wY-TdUbwPGu-sUCIH0qh6s";

$json = "https://www.googleapis.com/youtube/v3/videos?part=contentDetails&id=qe_XaI_h050&key=". $key;
$var = file_get_contents($json);
$videoId = json_decode($var, true);

echo $time = $videoId["items"]["0"]["contentDetails"]["duration"];
echo "<br>";

$arr = str_split($time);

print_r($arr);

for ($l=0;$l<count($arr);$l++){
	if ($arr[$l] == "S"){
		if ($arr[$l-2] == "M" || $arr[$l-2] == "H" || $arr[$l-2] == "T"){
			$time_S = $arr[$l-1];
		}else{
			$time_S = $arr[$l-2] . $arr[$l-1];
		}
	}
	
	if ($arr[$l] == "M"){
		if ($arr[$l-2] == "H" || $arr[$l-2] == "T"){
			$time_M = $arr[$l-1];
		}else{
			$time_M = $arr[$l-2] . $arr[$l-1];
		}
	}
	
	if ($arr[$l] == "H"){
		if ($arr[$l-2] == "T"){
			$time_H = $arr[$l-1];
		}else{
			$time_H = $arr[$l-2] . $arr[$l-1];
		}
	}
}
print_r($time_H);
print_r($time_M);
print_r($time_S);
?>