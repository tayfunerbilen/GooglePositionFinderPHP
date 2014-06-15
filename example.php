<?php

require_once 'GooglePositionFinder.class.php';

$google = new GooglePositionFinder();

$page = 2;
$lang = 'com.tr'; // turkish
$data = $google->query('facebook', $page, $lang);

print_r($data);

$match = $google->matchResult('facebook.com');
echo 'Aramalarda '.$match.' sonuçta siteniz yer alıyor.';
