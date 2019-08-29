<?php

	// Point to where you downloaded the phar
	require_once('./libs/httpful.phar');
		
	class RelatedHash {
		var $name;
		
		function __construct($name){
		   $this->name = $name;
		}	
	}	
	
	function getRelated($tag_name){
		usleep(rand(0.05*1000000, 0.1*1000000));
		$uri = "https://i.instagram.com/api/v1/tags/". urlencode($tag_name) ."/related/?related_types=[\"hashtag\"]";
	    $response = \Httpful\Request::get($uri)->expectsJson()->send();
		if ($response->code == 200){
			return $related = $response->body->related;			
		}else{
			echo "error-". $response->code;
			return array();
		}
	};

	function getHash($tag_name){
		$related_hash_list= array();
		$related = getRelated($tag_name);
		foreach ($related as &$value) {
			array_push($related_hash_list, new RelatedHash($value->name));
			$co_related = getRelated($value->name);
			foreach ($co_related as &$co_value) {
				array_push($related_hash_list, new RelatedHash($co_value->name));
			};
		};
		$related_distinct_hash_list = array_merge(array_unique($related_hash_list, SORT_REGULAR),array());
		return json_encode($related_hash_list);
	};
	
	function init(){
		header('Content-Type: application/json');
		echo getHash($_GET['tag_name']); 
	};
	
	init();

?>