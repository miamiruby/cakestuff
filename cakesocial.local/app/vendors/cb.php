<?php

$companies_url = 'http://api.crunchbase.com/v/1/companies.js';
$company_url = 'http://api.crunchbase.com/v/1/company/';

$companies = getData($companies_url);

//echo 'companies : '; print_r($companies);

foreach($companies as &$company){

$company = getData($company_url . $company->permalink . '.js');
	foreach($company->offices as $office){
		if($office->state_code == 'FL'){
			echo $company->name . '

';
			pr($office);
		}
	}
}

function pr($var){
	print_r($var);
}
function getData($url){
	$content = file_get_contents($url);
	$data = json_decode($content);
	return $data;
}
?>
