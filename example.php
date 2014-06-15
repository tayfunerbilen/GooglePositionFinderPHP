<?php

require_once 'GooglePositionFinder.class.php';

$google = new GooglePositionFinder();

$page = 2;
$lang = 'tr'; // turkish
$data = $google->query('facebook', $page, $lang);

print_r($data);
