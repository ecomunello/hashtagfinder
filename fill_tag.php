<?php

	// Point to where you downloaded the phar
	require_once('./libs/httpful.phar');
	
	class Stats{
		var $commentTotalAvg;
		var $likeTotalAvg;
		var $timeTotalAvg;
		var $videoRate;
		var $media_total_count;
		
		function __construct($commentTotalAvg, $likeTotalAvg, $timeTotalAvg, $videoRate, $media_total_count){
			$this->commentTotalAvg = $commentTotalAvg;
			$this->likeTotalAvg = $likeTotalAvg;
			$this->timeTotalAvg = $timeTotalAvg;
			$this->videoRate = $videoRate;
			$this->media_total_count = $media_total_count;
		}
	}
	
	class HashFilled {
		var $tag_name;
		var $stats; 
		
		function __construct($tag_name, $stats){
		   $this->tag_name = $tag_name;
		   $this->stats = $stats;
		}
	}
		
	
	function fillHash($tag_name, $retray){
		sleep(rand(1,15));
		$uri = "https://www.instagram.com/explore/tags/". urlencode($tag_name) ."/?__a=1";
	    $response = \Httpful\Request::get($uri)->expectsJson()->send();
		if ($response->code == 200){
			$media_total_count = $response->body->graphql->hashtag->edge_hashtag_to_media->{'count'};
			$recent_post = $response->body->graphql->hashtag->edge_hashtag_to_media->edges;
			
			$commentTotal = 0;
			$likeTotal = 0;
			$timeTotal = 0;
			$counter = 0;
			$counterVideo = 0;
			$recent_post_size = count($recent_post);
			foreach ($recent_post as &$post) {
				if(!$post->node->is_video){
					$commentTotal = $commentTotal+ $post->node->edge_media_to_comment->{'count'};
					$likeTotal = $likeTotal + $post->node->edge_media_preview_like->{'count'};
					$timeTotal = $timeTotal + $post->node->taken_at_timestamp;
					$counter = $counter+1;
				}else $counterVideo = $counterVideo+1;
			};
			
			$commentTotalAvg = (int)floor($commentTotal / $counter);
			$likeTotalAvg = (int)floor($likeTotal / $counter);
			$timeTotalAvg = (int)floor($timeTotal / $counter);
			$videoRate = (int)floor($counterVideo * 100 / $recent_post_size);
			
			return new Stats($commentTotalAvg, $likeTotalAvg, $timeTotalAvg, $videoRate, $media_total_count);
		} else {
			if($response->code == 429 && $retray){
				sleep(60);
				return fillHash(tag_name, false);
			} else return "error-". $response->code;
		};
	};
	

	function fillTags($tag_name){
		$related_hash_list_filled= array();
		array_push($related_hash_list_filled, new HashFilled($tag_name, fillHash($tag_name, true)));
		return json_encode($related_hash_list_filled);
	};
	
	function init(){
		$content = trim(file_get_contents("php://input"));
		header('Content-Type: application/json');
		echo fillTags(json_decode($content)->name);
	};
	
	init();

?>