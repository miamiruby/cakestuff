<?php

class crunchbase{

	var $apiurl = 'http://api.crunchbase.com/';
	var $version = 'v/1/';
	var $namespaces = array(
		'company',
		'person',
		'financial-organization',
		'product',
		'service-provider'
	);
	var $callback_str = '?callback={callback}';
	var $callback;
	var $type = '.js';

	var $api_url = '{crunchbase_url}{version}{namespace}/{permalink}{type}';

	var $companies_url = '{crunchbase_url}{version}companies{type}';
	var $search_url = '{crunchbase_url}{version}search{type}?query={keywords}';  //&page={page}

	function getCompany($company){
		$url = $this->fetchUrl('company', $company);
		return $this->getData($url);
	}
	
	function getPerson($person){
		$url = $this->fetchUrl('person', $person);
		return $this->getData($url);
	}
	function getOrganization($org){
		$url = $this->fetchUrl('financial-organization', $org);
		return $this->getData($url);
	}
	function getProduct($product){
		$url = $this->fetchUrl('product', $product);
		return $this->getData($url);
	}
	function getProvider($provider){
		$url = $this->fetchUrl('service-provider', $provider);
		return $this->getData($url);
	}

	function fetchUrl($namespace, $permalink){
		$url = $this->api_url;
		$url = str_replace('{crunchbase_url}', $this->apiurl, $url);	
		$url = str_replace("{version}", $this->version, $url);
		$url = str_replace("{type}", $this->type, $url);
		$url = str_replace('{permalink}', $permalink, $url);
		$url = str_replace('{namespace}', $namespace, $url);
		if($this->callback != ''){
			$url = str_replace("{callback}", $this->callback_str, $url);
			$url = str_replace("{callback}", $this->callback, $url);
		}
		return $url;
	}

	function getAllCompanies(){
		$url = $this->companies_url;
		$url = str_replace('{crunchbase_url}', $this->apiurl, $url);	
		$url = str_replace("{version}", $this->version, $url);
		$url = str_replace("{type}", $this->type, $url);
		$companies = $this->getData($url);
		return $companies;
	}

	function search($keywords){
		$url = $this->search_url;
		$keywords = urlencode($keywords);
		$url = str_replace('{crunchbase_url}', $this->apiurl, $url);	
		$url = str_replace("{version}", $this->version, $url);
		$url = str_replace("{type}", $this->type, $url);
		$url = str_replace("{keywords}", $keywords, $url);
	
		return $this->getData($url);	
		
	}

	function getData($url){
		$data = file_get_contents($url);
		$data = $this->cleanData($data);
		return $data;
	}

	function cleanData($data){
		$data = json_decode($data);
		$data = $this->objectToArray($data);
		return $data;
	}
	function arrayToObject( $array ){
		foreach( $array as $key => $value ){
			if( is_array( $value ) ) $array[ $key ] = arrayToObject( $value );
		}
		return (object) $array;
	}

	function objectToArray( $var ){
	  	if(is_array($var) || is_object($var)){
			foreach( $var as &$item ){
				if( is_object( $item ) || is_array($item) ){ 
					$item = $this->objectToArray( $item );
				}
			}
		}
	 	if(is_object($var)){
			 return (array) $var;
		} else {
			return $var;
		}
	}
}

?>
