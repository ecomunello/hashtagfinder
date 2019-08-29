<?php

	// Point to where you downloaded the phar
	require('./libs/httpful.phar');

	function getTags($tag_name){
		$uri = "https://www.instagram.com/web/search/topsearch/?context=blended&query=%23" . $tag_name;
	    $response = \Httpful\Request::get($uri)->expectsJson()->send()->body;
		echo json_encode($response);
		
	};
	
	getTags($_GET['tag_name']);

?>