<?php
	require 'streamList.php';
	
	$action = $_GET['action'];
	
	if ($action == 'twitch') {
		$twitchURL = 'https://api.twitch.tv/kraken/streams?channel=';
		foreach($twitch as $username => $title)
				$twitchURL .= $username . ",";

		echo file_get_contents($twitchURL);
	} else {
		$tmp = array();

		foreach($own3D as $id => $title) {
			$own3DURL = "http://api.own3d.tv/liveCheck.php?live_id=".$id;
			$xml = (string)file_get_contents($own3DURL);
			$oldxml = $xml;
			$titleTrimmed = trim($title);
			$begin = "<{$titleTrimmed}><liveEvent>";
			$end = "</liveEvent></{$titleTrimmed}>";
			$xml = str_replace("<liveEvent>", $begin, $xml);
			$xml = str_replace("</liveEvent>", $end, $xml);
			$xml = str_replace("<isLive>", "<id>{$id}</id><isLive>", $xml);
			
			//array_push($tmp, $title);
			$tmp[] = @(array) simplexml_load_string($xml);

		}
		$json = json_encode($tmp);
		$json = str_replace("[", '{ "streams": ', $json);
		$json = str_replace('},{', ',', $json);
		$json = str_replace("]", "}", $json);
		echo $json;
	}
?>