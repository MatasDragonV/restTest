<?php

require __DIR__ . '/vendor/autoload.php';

$oBitrix = new \Zloykolobok\Bitrix24\Classes\Custom();
$oBitrix->setUrl('https://test.bitrix24.ru/rest/1/5hwvpc9l6wuzf6m7/');
$oBitrix->setTimeout(30);

$res = $oBitrix->sendRequest([],'crm.enum.ownertype');
$array = json_decode(json_encode($res), true);
$CompanyOwnerID = 0;
foreach ($array['result'] AS $owner) {
	if ($owner['NAME']=='Компания')
		$CompanyOwnerID = $owner['ID'];
}
$file = file('file.csv');
$CSVarray=[];
foreach ($file as $line) {
	//$CSVarray[] = str_getcsv(mb_convert_encoding($line,'UTF-8','windows-1251'),';');
	$CSVarray[] = str_getcsv($line,';');
}
foreach ($CSVarray as $key=>$value) {
	if ($key>0) {
		$fields=[
			"ENTITY_TYPE_ID"=>$CompanyOwnerID,
            		"ENTITY_ID"=>$value[0],
            		"PRESET_ID"=>1,
            		"NAME"=>$value[1],            		
            		"ACTIVE"=>"Y",
            		"RQ_INN"=>$value[2],
			"RQ_KPP"=>$value[3],	
		];
		$oBitrix->sendRequest($fields,'crm.requisite.add');
	}
}
?>
