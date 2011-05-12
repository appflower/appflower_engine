<?php

/**
 * class for finding the coodonates for GMAPS
 */
class GmapsFinder{
	
	public static function find($location)
	{
		// Create a CURL object for later use
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		// Construct the geocoder request string (URL)
		$url = "http://maps.google.com/maps/geo?output=xml&key=".sfConfig::get('app_gmaps_key')."&q=".urlencode($location);
		curl_setopt($ch, CURLOPT_URL, $url);
		$response = curl_exec($ch);
		
		// Close the CURL file and destroy the object
		curl_close($ch);
		
		$return=false;
			
		// Use SimpleXML to parse our answer into something we can use
		if(($googleresult = @simplexml_load_string($response))!==false)
		{
			if ($googleresult->Response->Status->code == 200)
			{
				foreach ($googleresult->Response as $response) 
				{
					foreach ($response->Placemark as $place) 
					{
						$coords = explode(",",$place->Point->coordinates);
						
						$return=array('longitude'=>$coords[0],'latitude'=>$coords[1]);
					}
				}
			}
		}
		
		return $return;		
	}
}