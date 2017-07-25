<?php

namespace agraciakuvut;

use PHPUnit\Runner\Exception;

class KuvutAnalytics
{
    /** @var   \Google_Service_Analytics */
    protected $ga;
    protected $profileId;
    protected $touched = false;
    protected $result = [];
    protected $data = [];

    public function __construct($accountId, $propertyId, $applicatinName, $configFile, $iniData = [])
    {
        $client = new \Google_Client();
        $client->setApplicationName($applicatinName);
        $client->setAuthConfig($configFile);
        $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
        $this->ga = new \Google_Service_Analytics($client);
        $this->setAccount($accountId, $propertyId);
        $this->startDate = isset($iniData['startDate']) ? $iniData['startDate'] : '7daysAgo';
        $this->endDate = isset($iniData['endDate']) ? $iniData['endDate'] : 'today';
    }

    public function __set($name, $value)
    {
        $this->touched = true;
        $this->data[$name] = $value;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }
        return null;
    }

    public function dates($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        return $this;
    }

    public function sessions()
    {
        return $this->addMetric('ga:sessions');
    }

    public function pageViews()
    {
        return $this->addMetric('ga:pageviews');
    }

    public function uniquePageViews()
    {
        return $this->addMetric('ga:uniquePageviews');
    }

    public function get($metric = '')
    {
        if($this->touched){
            $this->getResult($this->getResults());
        }

        if($metric){
            if(!isset($this->result['ga:' . $metric])){
                throw new Exception('Metric ' . $metric . ' not exist or not request');
            }
            return $this->result['ga:' . $metric];
        }

        return $this->result;

    }

    protected function addMetric($metric){
        if($this->metrics){
            $this->metrics .= ',';
        }
        $this->metrics .= $metric;
        return $this;
    }


    protected function setAccount($accountId, $propertyId)
    {
        $profiles = $this->ga->management_profiles->listManagementProfiles($accountId, $propertyId);

        if ($profiles->count() > 0) {
            $items = $profiles->getItems();
            $this->profileId = $items[0]->getId();
        } else {
            throw new \Exception('No views (profiles) found for this user.');
        }
    }

    protected function getResults()
    {
        $this->touched = false;
        return $this->ga->data_ga->get(
            'ga:' . $this->profileId,
            $this->startDate,
            $this->endDate,
            $this->metrics);
    }

    protected function getResult(\Google_Service_Analytics_GaData $results)
    {
        $this->result = [];

        $headers = $results->getColumnHeaders();
        $rows = $results->getRows();

        if (count($rows) > 0) {
            foreach ($rows[0] as $k => $row){
                $this->result[$headers[$k]['name']] = $row;
            }
        }
    }
}
