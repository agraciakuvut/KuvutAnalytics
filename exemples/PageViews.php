<?php
require '../vendor/autoload.php';
require '../src/KuvutAnalytics/KuvutAnalytics.php';

$a = new \agraciakuvut\KuvutAnalytics('YOUR-ACCOUNT-ID', 'YOUR-PROPERTY-ID',"Hello KuvutAnalytics Reporting",'YOUR-CONFIG-FILE');
$a = $a->dates('7daysAgo','today')->sessions()->pageViews()->uniquePageViews();

echo '<h1> Data 7 days </h1>';
echo 'Sessions: ' . $a->get('sessions') . '<br>';
echo 'PageViews: ' . $a->get('pageviews'). '<br>';
echo 'UniquePageViews: ' . $a->get('uniquePageviews') . '<br>';

