<?php
use App\Models\Country;
use App\Models\ServiceType;

	function img($img){
		if($img == ""){
			return asset('main/avatar.png');
		}else if (strpos($img, 'http') !== false) {
	        return $img;
	    }else{
			return asset('storage/'.$img);
		}
	}

	function image($img){
		if($img == ""){
			return asset('main/avatar.jpg');
		}else{
			return asset($img);
		}
	}

	function curl($url)
	{
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    $return = curl_exec($ch);
	    curl_close ($ch);
	    return $return;
	}
	function currency_amt($value = '')
	{
		if($value == ""){
			return Setting::get('currency')." 0.00";
		} else {
			return Setting::get('currency').' '.$value;
		}
	}
	function currency($country_id = '')
	{
		if($country_id == ""){
			return '$';
		} else {
			return Country::where('countryid','=',$country_id)->pluck('currency_symbol')->first();
		}
	}
	function service_name($id = '')
	{
		return ServiceType::where('id','=',$id)->pluck('name')->first();
	}
