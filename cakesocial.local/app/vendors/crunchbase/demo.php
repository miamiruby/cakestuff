<?php

/* Example of how to use this file */
function pr($obj){
print_r($obj);
}

include 'crunchbase.php';

$crunchbase = new crunchbase;
$companies = $crunchbase->getAllCompanies();

//test get company
$company = $crunchbase->getCompany($companies['0']['permalink']);
//pr($company);

//test get person
$person = $crunchbase->getPerson($company['relationships']['0']['person']['permalink']);
pr($person);

//test get product
//$product = $crunchbase->getProduct($company['products']['0']['permalink']);
//pr($product);

//test get fo
//$forg = $crunchbase->getOrganization($company['funding_rounds']['0']['investments']['0']['financial_org']['permalink']);
//pr($forg);


?>
