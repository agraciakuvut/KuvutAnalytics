<?php

namespace Kuvut;

class Analytics
{
    /** @var   \Google_Service_Analytics */
    protected $ga;
    protected $profileId;

    public function __construct($accountId, $propertyId, $applicatinName, $configFile)
    {
        $client = new \Google_Client();
        $client->setApplicationName($applicatinName);
        $client->setAuthConfig($configFile);
        $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
        $this->ga = new \Google_Service_Analytics($client);
        $this->setAccount($accountId, $propertyId);
    }

    public function getSessions($startDate, $endDate)
    {
        return $this->getResult($this->getResults($startDate, $endDate, 'ga:sessions'));
    }

    public function getPageViews($startDate, $endDate)
    {
        return $this->getResult($this->getResults($startDate, $endDate, 'ga:pageviews'));
    }

    public function getUniquePageViews($startDate, $endDate)
    {
        return $this->getResult($this->getResults($startDate, $endDate, 'ga:uniquePageviews'));
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

    protected function getResults($startDate, $endDate, $metrics)
    {
        return $this->ga->data_ga->get(
            'ga:' . $this->profileId,
            $startDate,
            $endDate,
            $metrics);
    }

    protected function getResult($results)
    {
        if (count($results->getRows()) > 0) {
            $rows = $results->getRows();
            return $rows[0][0];
        }
        return 0;
    }
}
