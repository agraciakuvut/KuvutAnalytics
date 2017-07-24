<?php
require '../vendor/autoload.php';
require '../src/Analytics.php';

$a = new \Kuvut\Analytics('YOUR-ACCOUNT-ID', 'YOUR-PROPERTY-ID',"Hello Analytics Reporting",'YOUR-CONFIG-FILE');

echo '<h1> Data 7 days </h1>';
echo 'Sessions: ' . $a->getSessions('7daysAgo','today') . '<br>';
echo 'PageViews: ' . $a->getPageViews('7daysAgo','today'). '<br>';
echo 'UniquePageViews: ' . $a->getUniquePageViews('7daysAgo','today'). '<br>';

