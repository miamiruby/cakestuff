<?php

/* Example of how to use this file */
function pr($obj){
print_r($obj);
}

include 'crunchbase.php';
include '../twitter/twitter.php';

$crunchbase = new crunchbase;
$companies = $crunchbase->getAllCompanies();

foreach ($companies as $company){
	$compdata = $crunchbase->getCompany($company['permalink']);
	pr($compdata);die();

	foreach($compdata['relationships'] as $relationship){
		echo $relationship['person']['permalink'] . '
';
	}
}
/*
//test get person
$person = $crunchbase->getPerson($company['relationships']['0']['person']['permalink']);
pr($person);

//test get product
//$product = $crunchbase->getProduct($company['products']['0']['permalink']);
//pr($product);

//test get fo
//$forg = $crunchbase->getOrganization($company['funding_rounds']['0']['investments']['0']['financial_org']['permalink']);
//pr($forg);
*/

?>
