<?php

add_action('init','rentalPriceTemporary',4);
add_action('init','rentalPlaceTemporary',5);
add_action('init','rentalPostRatingTemporary',6);
add_action('comment_post',	'rentalDeleteTransient');
add_action('save_post',		'rentalDeleteTransient');

//	==========================================================	//
//	==	PRICE ==	//
//	==========================================================	//

function rentalPriceTemporary()
{
	global $rentalSetting,$wpdb;
	
	$low_price	= wp_cache_get('rental_low_price','rental');
	$high_price	= wp_cache_get('rental_high_price','rental');
	
	if($low_price === false || $high_price === false) :
		$query	= "SELECT MIN(CONVERT(meta_value,UNSIGNED INTEGER)) AS low, MAX(CONVERT(meta_value,UNSIGNED INTEGER)) AS high ".
				  "FROM `".$wpdb->prefix."postmeta` ".
				  "WHERE ( `meta_key` = 'price_low' OR `meta_key` = 'price_high' ) AND `meta_value` <> '' ";
				  
		$result	= $wpdb->get_row($query,ARRAY_A);
		
		$low_price	= $result['low'];
		$high_price	= $result['high'];
		
		wp_cache_set('rental_low_price',$result['low'],'rental');
		wp_cache_set('rental_high_price',$result['high'],'rental');
	endif;
	
	$rentalSetting['setting']['low_price']		= $low_price;
	$rentalSetting['setting']['high_price']	= $high_price;
}

//	==========================================================	//
//	==	PLACE ==	//
//	==========================================================	//

function rentalPlaceTemporary()
{
	global $rentalSetting,$wpdb;
	
	$place		= wp_cache_get('rental_place','rental');
	$city		= wp_cache_get('rental_city','rental');
	$country	= wp_cache_get('rental_country','rental');
	
	if($place === false || $city === false || $country === false) :
	
		$country	= array();
		$city		= array();
	
		$query	= "SELECT country.meta_value AS country, city.meta_value AS city, rental.ID as postID ".
				  "FROM `".$wpdb->prefix."postmeta` AS country ".
				  "INNER JOIN `".$wpdb->prefix."postmeta` AS city ON country.post_id = city.post_id ".
				  "INNER JOIN `".$wpdb->prefix."posts` AS rental ON country.post_id = rental.ID ".
				  "WHERE ".
					"( country.meta_key = 'map_nation' AND city.meta_key = 'map_city' ) AND ".
					"( rental.post_type = 'rental' AND rental.post_status = 'publish' )".
				  "GROUP BY rental.ID ";
					
		$result	= $wpdb->get_results($query,ARRAY_A);
		
		foreach($result as $data) :
		
			
			if(!in_array($data['country'],$country)) :
				$country[]	= $data['country'];
			endif;
			
			if(!in_array($data['city'],$city)) :
				$city[]		= $data['city'];
			endif;
			
			if(!isset($place[$data['country']][$data['city']])) :
				$place[$data['country']][$data['city']]	= 1;
			else :
				$place[$data['country']][$data['city']]++;
			endif;
			
		endforeach;
		
		wp_cache_set('rental_place',	$place,'rental');
		wp_cache_set('rental_city',		$city,'rental');
		wp_cache_set('rental_country',	$country,'rental');
					
	endif;
	
	$rentalSetting['place']		= $place;
	$rentalSetting['country']	= $country;
	$rentalSetting['city']		= $city;
}

//	==========================================================	//
//	==	POST RATING ==	//
//	==========================================================	//

function rentalPostRatingTemporary()
{
	global $rentalSetting,$wpdb,$post;
	
	$postRating		= wp_cache_get('rental_post_rating','rental');
	
	if($postRating === false || !isset($postRating[$post->ID])) :
		$args		= array(
			'post_type'			=> 'rental',
			'posts_per_page'	=> -1,
		);
		
		$rentals	= get_posts($args);
		
		foreach($rentals as $rental) :
		
			$ratval	= array(
				'e'	=> 0,
				'g'	=> 0,
				'a'	=> 0,
				'ba'=> 0,
				'p'	=> 0,
			);
			
			$query	= "SELECT commentMeta.meta_value ".
					  "FROM `".$wpdb->prefix."comments` AS comment INNER JOIN `".$wpdb->prefix."commentmeta` AS commentMeta ".
					  "ON comment.comment_ID = commentMeta.comment_id ".
					  "WHERE commentMeta.meta_value <> '' AND comment.comment_post_ID = '".$rental->ID."' ";
					  
			$result	= $wpdb->get_results($query,ARRAY_A);
			
			$total	= sizeof($result);
			$sum	= 0;
			foreach($result as $data) :
				switch($data['meta_value']) :
					case 5		: $ratval['e']++; break;
					case 4		: $ratval['g']++; break;
					case 3		: $ratval['a']++; break;
					case 2		: $ratval['ba']++; break;
					case 1		: $ratval['p']++; break;
				endswitch;
				
				$sum	+= $data['meta_value'];
			endforeach;
			
			foreach($ratval as $key => $totnum) :
				$perc[$key]	= ($total == 0) ? '0%' : intval( $totnum / $total * 100).'%';
			endforeach;
			
			$mod	= ($total == 0) ? 0 : intval($sum / $total);
			
			$postRating[$rental->ID]	= array(
				'total'	=> $total,
				'mod'	=> $mod,
				'value'	=> $ratval,
				'perc'	=> $perc,
			);
		endforeach;
		
		wp_cache_set('rental_post_rating',$postRating,'rental');
	endif;

	$rentalSetting['post_rating']	= $postRating;
}

function rentalDeleteTransient()
{
	wp_cache_delete('rental_low_price','rental');
	wp_cache_delete('rental_high_price','rental');
	
	wp_cache_delete('rental_place','rental');
	wp_cache_delete('rental_city','rental');
	wp_cache_delete('rental_country','rental');
	
	wp_cache_delete('rental_post_rating','rental');
}


?>