<?php
require '../vendor/autoload.php';
require '../src/KuvutAnalytics/KuvutAnalytics.php';

$a = new \agraciakuvut\KuvutAnalytics('YOUR-ACCOUNT-ID', 'YOUR-PROPERTY-ID',"Hello KuvutAnalytics Reporting",'YOUR-CONFIG-FILE');

echo '<h1> Data 7 days </h1>';
echo 'Sessions: ' . $a->getSessions('7daysAgo','today') . '<br>';
echo 'PageViews: ' . $a->getPageViews('7daysAgo','today'). '<br>';
echo 'UniquePageViews: ' . $a->getUniquePageViews('7daysAgo','today'). '<br>';

