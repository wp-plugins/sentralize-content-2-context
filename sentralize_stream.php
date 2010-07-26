<?php
	/*
	*	Get stream data from Sentralize
	*	Requires an API key
	*	Returns json_encoded stream object
	*/
	function sentralize_get_stream($api)
	{
		$cache_ttl = get_option('sentralize_cache_ttl'); // Cache TTL as a unix timestamp
		$sentralize = 'http://c2c.sentralize.com/export/json/'; // URL to sentralize export

		// Check whether we have a recent enough copy of the stream in the cache
		if((time() - get_option('sentralize_last_grabbed_'.$api)) >= $cache_ttl)
		{
			$data = @file_get_contents($sentralize.$api);

			// If file_get_contents fails, return cached data instead of empty object
			if(!$data)
			{
				$cache = get_option('sentralize_'.$api);
				$stream = json_decode($cache);

				//Update cache in database with current data and timestamp
				update_option('sentralize_last_grabbed_'.$api, time());
				update_option('sentralize_'.$api, $cache);
			}
			else
			{
				$stream = json_decode($data);

				// If stream is empty, return cached data instead of empty stream.
				if (!(count($stream->data) > 0))
				{
					$cache = get_option('sentralize_'.$api);
					$stream = json_decode($cache);
				}
				else
				{
					//Update cache in database with current data and timestamp
					update_option('sentralize_last_grabbed_'.$api, time());
					update_option('sentralize_'.$api, $data);
				}
			}
		}
		else
		{
			$cache = get_option('sentralize_'.$api);
			$stream = json_decode($cache);
		}

		return $stream;
	}


	/*
	*	Stream Function - One function for getting the stream, and formatting the data into HTML
	*	Requires API Key
	*	Optional post count limit, show source flag, show content flag, show stream title flag, and content character length
	*	Returns HTML encoded string object.
	*/
	function sentralize_stream($api, $post_count=5, $show_source=true, $show_content=true, $show_stream_title=true, $content_length=500)
	{
		$stream = sentralize_get_stream($api);

		$html = '<div id="sz_stream">';

		if(!$stream)
			echo '<h2>API Key Incorrect</h2>';

		if($show_stream_title)
			$html = $html.'<h2>'.$stream->name.'</h2>';

		$maxcount = $post_count;
		$count = 0;

		foreach ($stream->data as $item)
		{
			if($count >= $maxcount)
				break;
	
			$html = $html.'<div class="sz_data_item" id="sz_data_'.sha1($item->id).'">
				<div class="sz_data_item_title"><h3><a href="'.$item->identifier.'" target="_blank">'.$item->title.'</a></h3></div>';

				if($show_source)
					$html = $html.'<div class="sz_data_item_source"><small>From: '.$item->source->name.' | '.human_time_diff(strtotime($item->published_at)).' ago.</small></div>';
			
				if($show_content)
					$html = $html.'<div class="sz_data_item_content "><p>'.sentralize_truncate($item->content, $content_length, '... <a href="'.$item->identifier.'" target="_blank">Read More</a>', false).'</p></div>';

			$html = $html.'</div>';

			$count++;
		} 

		$html = $html.'<p class="sentralize_tag"><small>Powered by <a href="http://www.sentralize.com">Sentralize.com</a></small></p></div>';

		return $html;
	}


      	/*
	*	Truncates a string for a given character length.
	*	Requires String
	*	Optional Chatacter Length, Truncate Suffix
	*	Returns truncated string
	*/
      	function sentralize_truncate($str, $length=10, $trailing='...')
      	{
        	if (mb_strlen($str)> $length)
            	{
			//If truncating, strip out formatting tags so we dont accidentally cut out a closing tag.
               		return mb_substr(strip_tags($str),0,$length).$trailing;
            	}
            	else
            	{
               		$res = $str;
            	}

            	return $res;
      	}
?>
