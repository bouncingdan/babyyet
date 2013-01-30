<?php
	$twitter_search_user = 'bouncingdan';
	
	$twitter_search_hashtag = 'hadbaby';
	//$twitter_search_hashtag = 'fb';
	
	$twitter_search = 'from%3A'.$twitter_search_user.'%20%23'.$twitter_search_hashtag;
	$twitter_search_uri = 'http://search.twitter.com/search.json?q='.$twitter_search;

	$test_result_positive = '{"completed_in":0.05,"max_id":295458666486829056,"max_id_str":"295458666486829056","page":1,"query":"%3Dfrom%3Abouncingdan+%23fb","refresh_url":"?since_id=295458666486829056&q=%3Dfrom%3Abouncingdan%20%23fb","results":[{"created_at":"Sun, 27 Jan 2013 09:10:10 +0000","from_user":"bouncingdan","from_user_id":18018478,"from_user_id_str":"18018478","from_user_name":"Dan Goodwin","geo":null,"id":295458666486829056,"id_str":"295458666486829056","iso_language_code":"it","metadata":{"result_type":"recent"},"profile_image_url":"http:\/\/a0.twimg.com\/profile_images\/1465643472\/avatar-large_normal.jpg","profile_image_url_https":"https:\/\/si0.twimg.com\/profile_images\/1465643472\/avatar-large_normal.jpg","source":"&lt;a href=&quot;http:\/\/levelupstudio.com\/plume&quot;&gt;Plume\u00a0for\u00a0Android&lt;\/a&gt;","text":"http:\/\/t.co\/poCKxvDx giant pint anyone? #fb\u00a0","to_user":null,"to_user_id":0,"to_user_id_str":"0","to_user_name":null},{"created_at":"Sat, 26 Jan 2013 18:39:42 +0000","from_user":"bouncingdan","from_user_id":18018478,"from_user_id_str":"18018478","from_user_name":"Dan Goodwin","geo":null,"id":295239607178633216,"id_str":"295239607178633216","iso_language_code":"en","metadata":{"result_type":"recent"},"profile_image_url":"http:\/\/a0.twimg.com\/profile_images\/1465643472\/avatar-large_normal.jpg","profile_image_url_https":"https:\/\/si0.twimg.com\/profile_images\/1465643472\/avatar-large_normal.jpg","source":"&lt;a href=&quot;http:\/\/twitter.com\/&quot;&gt;web&lt;\/a&gt;","text":"When it comes to hiccough cures, H is a fan of the surprise technique. She keeps declaring \"I need more boos to get rid of my hiccoughs\" #fb","to_user":null,"to_user_id":0,"to_user_id_str":"0","to_user_name":null}],"results_per_page":15,"since_id":0,"since_id_str":"0"}';
	$test_result_negative = '{"completed_in":0.013,"max_id":296724888075509760,"max_id_str":"296724888075509760","page":1,"query":"%3Dfrom%3Abouncingdan+%23fbx","refresh_url":"?since_id=296724888075509760&q=%3Dfrom%3Abouncingdan%20%23fbx","results":[],"results_per_page":15,"since_id":0,"since_id_str":"0"}';
	
	$cache_path=dirname(__FILE__).'/cache/cache.txt';
	$cache_time_seconds=1800;

	$have_they=0;
	$have_they_string='No';
	$extra_info='';
	try {
		$handle = fopen($cache_path, 'r+b');

		if (FALSE === $handle) {
			throw new Exception('Failed to open cache file');
		}

		$timestamp=(integer)stream_get_line($handle, 4096, "\n");
		$have_they=(integer)stream_get_line($handle, 4096, "\n");
		$extra_info=stream_get_line($handle, 4096, "\n");

		if ($have_they) {
			//no point in changing anything, the baby has been had
		}
		elseif (!$timestamp || (time() - $timestamp > $cache_time_seconds)) {
			//$api_result = $test_result_positive;
			$api_result = file_get_contents($twitter_search_uri);
			$result = json_decode($api_result);

			print '<pre>';
			print_r($result);
			print '</pre>';

			if (sizeof($result->results)) {
				$have_they = 1;
				$extra_info = $result->results[0]->created_at.': '. $result->results[0]->text;
			}
			else {
				$have_they = 0;
			}

			rewind($handle);
			fwrite($handle, time()."\n");
			fwrite($handle, $have_they."\n");
			fwrite($handle, $extra_info."\n");
		} //if cache dead

		fclose($handle);
		$have_they_string = $have_they ? 'Yes!' : 'No';
	}
	catch(Exception $e) {
		fclose($handle);
		$have_they_string='Huh, something went wrong and we just don\'t know';
	}
?>
<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<title>havekateanddanhadtheirbabyyet</title>
	<style type="text/css">
	body {
		font-family:arial, sans-serif;
	}
	h1 {
		text-align:center;
		font-size:100pt;
		margin:0.5em 0 0.1em 0;
	}
	h2 {
		text-align:center;
	}
	footer {
		text-align:right;
		margin:4em 2% 0 0;
	}
	</style>
</head>
<body>
	<h1><?php print $have_they_string; ?></h1>
	
	<?php if ($extra_info): ?>
		<h2><?php print $extra_info; ?></h2>
	<?php endif; ?>
	
	<footer>
		<p class="show-working">[coming soon: show working]</p>
		<a href="http://bouncingdan.co.uk">bouncingdan.co.uk</a>
	</footer>
</body>