<?php

namespace App\Services\Iiko;

use Ixudra\Curl\CurlService;

class IikoCardApi
{
    protected $login = '';
    protected $secret = '';
    protected $url = 'https://api-eu.iiko.services/api/1/';
    public $organization = '';
    public $token = false;

    public function __construct($login, $secret, $organization)
    {
        $this->login = $login;
        $this->secret = $secret;
        $this->organization = $organization;

        $token = $this->getApiToken();

        if (!$token) {
            return false;
        }
        $this->token = $token;
    }

    protected function getApiToken()
    {
        $curl = new CurlService();

        $post = array(
            'apiLogin' => $this->login,
        );
        $headers = array(
            'Content-type:application/json',
            'Accept:application/json',
            'Timeout:10'
        );
        return $curl
            ->to($this->url . 'access_token')->withData($post)->withHeaders($headers)->asJson()
            ->post()->token;


    }


    public function getClientInfo($customerId)
    {
        $curl = new CurlService();

        return $curl
            ->to($this->url . 'customers/get_customer_by_id?access_token=' . $this->token . '&organization=' . $this->organization . '&id=' . $customerId)
            ->get();
    }

    public function getClientInfoByPhone($phone)
    {
        $curl = new CurlService();

        return $curl
            ->to($this->url . 'customers/get_customer_by_phone?access_token=' . $this->token . '&organization=' . $this->organization . '&phone=' . $phone . '&requestTimeout=5000')
            ->get();
    }

    public function getManualCondition()
    {
        $curl = new CurlService();

        return $curl
            ->to($this->url . 'orders/get_manual_condition_infos?access_token=' . $this->token . '&organization=' . $this->organization)
            ->get();
    }

    public function getOrganizationProgramm()
    {
        $curl = new CurlService();

        return $curl
            ->to($this->url . 'organization/programs?access_token=' . $this->token . '&organization=' . $this->organization)
            ->get();
    }

    public function sendNewBalance($request, $action)
    {
        $curl = new CurlService();

        return $curl
            ->to($this->url . 'customers/' . $action . '?access_token=' . $this->token)
            ->withData($request)
            ->asJson()
            ->post();
    }

    public function getOrderTypes()
    {
        $curl = new CurlService();

        $post = array(
            'organizationIds' => [$this->organization],
        );
        $headers = array(
            'Content-type:application/json',
            'Accept:application/json',
            'Timeout:10',
            'Authorization:Bearer ' . $this->token
        );


        return $curl
            ->to($this->url . 'deliveries/order_types')->withData($post)->withHeaders($headers)->asJson()
            ->post();
    }

    public function getPaymentTypes()
    {
        $curl = new CurlService();

        $post = array(
            'organizationIds' => [$this->organization],
        );
        $headers = array(
            'Content-type:application/json',
            'Accept:application/json',
            'Timeout:10',
            'Authorization:Bearer ' . $this->token
        );


        return $curl
            ->to($this->url . 'payment_types')->withData($post)->withHeaders($headers)->asJson()
            ->post();
    }

    public function getProgramms()
    {
        $curl = new CurlService();

        return $curl
            ->to($this->url . 'organization/programs?access_token=' . $this->token . '&organization=' . $this->organization)
            ->get();
    }

    public function getStopLists()
    {
        $curl = new CurlService();

        $post = array(
            'organizationIds' => [$this->organization],
        );
        $headers = array(
            'Content-type:application/json',
            'Accept:application/json',
            'Timeout:10',
            'Authorization:Bearer ' . $this->token
        );


        return $curl
            ->to($this->url . 'stop_lists')->withData($post)->withHeaders($headers)->asJson()
            ->post();
    }

    public function getTerminals()
    {
        $curl = new CurlService();

        $post = array(
            'organizationIds' => [$this->organization],
        );
        $headers = array(
            'Content-type:application/json',
            'Accept:application/json',
            'Timeout:10',
            'Authorization:Bearer ' . $this->token
        );


        return $curl
            ->to($this->url . 'terminal_groups')->withData($post)->withHeaders($headers)->asJson()
            ->post();
    }
}
