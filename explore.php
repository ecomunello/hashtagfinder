<?php

	// Point to where you downloaded the phar
	require('./libs/httpful.phar');

	sleep(rand(1, 10));
	
	function exploreTag($tag_name){
		$uri = "https://www.instagram.com/explore/tags/". $tag_name ."/?__a=1";
	    $response = \Httpful\Request::get($uri)->expectsJson()->send()->body;
		echo json_encode($response);
		
	};
	
	exploreTag($_GET['tag_name']);

?>