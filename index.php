<?php
/*
 * Total Time 
 * Counts total time of all your videos
 * Pierre Norrbrink
 * 
 */
	$key = "AIzaSyADKVLQ7rAX3wY-TdUbwPGu-sUCIH0qh6s"; /* rek to make your own key*/
	$channelId = "UCmiJd2hQvZpWdv37UMv5C2A"; /* StephenVlog, https://developers.google.com/youtube/v3/docs/channels/list */
	
	/* Skipped playlist(s)  id */
	$skippedArray[0] = "PLkOr2vuFTeuWPjPssu-wrOdU1zguWHTVH";
	$skippedArray[1] = "PLF2982BF0FA8C50D6";
	$skippedArray[2] = "PLkOr2vuFTeuUUQ3dOfBSQCRQJ27RbkLu0";
	$skippedArray[3] = "PL41997611033EEF75";
	$skippedArray[4] = "PLCBD821C11993179D";
	$skippedArray[5] = "PLkOr2vuFTeuXWqT8yskzf2ck5Qdm0zJhi";
	$skippedArray[6] = "PL00AA703F71297523";
	$skippedArray[7] = "PL5AB7C7BC48E8EC1E";
	$skippedArray[8] = "PL7267515F676327BE";
	/**/
	$skippedCount = count($skippedArray);
	
	$playlistPageToken = "";
	
	$end_S = 0;
	$end_M = 0;
	$end_H = 0;
	
	/* Get playlists */
	playlist: /* if more than 50 playlists */
	$playlist = "https://www.googleapis.com/youtube/v3/playlists?part=id&channelId=" .$channelId. "&maxResults=50&key=" .$key. "&pageToken=" .$playlistPageToken;
	$playlist = file_get_contents($playlist);
	$playlist = json_decode($playlist, true);
	
	/* gather information */
	$playlistCount = count($playlist["items"]);
	
	for ($i=0;$i<$playlistCount;$i++){
		/*remove skipped playlists*/
		for ($k=0;$k<$skippedCount;$k++){
			if ($playlist["items"][$i]["id"]== $skippedArray[$k]){
				/*
				 * Skipp everything
				*/
				/*echo "skip<br>";*/
				goto skip;
			}
		}
		
		/* start a new playlist */
		echo $playlist["items"][$i]["id"];
		echo "<br>";
		
		$videoIdPageToken = "";
		videoId:/* if more than 50 videos in a playlist */
		$videoId = "https://www.googleapis.com/youtube/v3/playlistItems?part=contentDetails&maxResults=50&playlistId=" .$playlist["items"][$i]["id"]. "&key=" .$key. "&pageToken=" .$videoIdPageToken;
		$videoId = file_get_contents($videoId);
		$videoId = json_decode($videoId, true);
			
		/* gather information */
		$videoIdCount = count($videoId["items"]);

		/* get Video Id s*/
		for ($j=0;$j<$videoIdCount;$j++){
			/*echo $videoId["items"][$j]["contentDetails"]["videoId"];*/
		
		
			$videoData = "https://www.googleapis.com/youtube/v3/videos?part=contentDetails&id=" .$videoId["items"][$j]["contentDetails"]["videoId"]. "&key=" .$key;
			$videoData = file_get_contents($videoData);
			$videoData = json_decode($videoData, true);
			
			$time = $videoData["items"]["0"]["contentDetails"]["duration"]; /* if video is "private", a notice will pop up, does not affect the time*/
			
			$arr = str_split($time);
			
			$time_S = "";
			$time_M = "";
			$time_H = "";
			
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
			
			
			$end_S = $end_S + intval($time_S);
			$end_M = $end_M + intval($time_M);
			$end_H = $end_H + intval($time_H);
			
			/* If more than 60 sek/min */
			if ($end_S>60){
				$end_M++;
				$end_S = $end_S - 60;
			}
			
			if ($end_M>60){
				$end_H++;
				$end_M = $end_M - 60;
			}
			
			echo "  ". $end_H . "H " . $end_M . "Min ". $end_S. "Sek<br>";
			
		}
		if(isset($videoId["nextPageToken"])){
			$videoIdPageToken = $videoId["nextPageToken"];
			goto videoId;
		}
	skip:
	}
	if(isset($playlist["nextPageToken"])){
		$playlistPageToken = $playlist["nextPageToken"];
		echo "<br>yayy<br>";
		goto playlist;	
	}
	echo "  ". $end_H . "H " . $end_M . "Min ". $end_S. "Sek<br>";
	echo "<br>done";
?>