<?php

namespace App\Services\Iiko;

use Ixudra\Curl\CurlService;

class IikoApi
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

        if(!$token){
            return false;
        }
        $this->token = $token;
    }

    public function closeOrder($data){
        $curl = new CurlService();

        $headers = array(
            'Content-type:application/json',
            'Accept:application/json',
            'Timeout:10',
            'Authorization:Bearer '.$this->token
        );

        return $curl
            ->to($this->url.'deliveries/cancel')
            ->withData($data)
            ->withHeaders($headers)->asJson()
            ->post();
    }

    protected function getApiToken() {
        $curl = new CurlService();

        $post = array(
            'apiLogin'       => $this->login,
        );
        $headers = array(
            'Content-type:application/json',
            'Accept:application/json',
            'Timeout:10'
        );
        return  $curl
            ->to($this->url.'access_token')->withData($post)->withHeaders($headers)->asJson()
            ->post()->token;
    }

    public function getOrganizationList()
    {
        $curl = new CurlService();
        $headers = array(
            'Content-type:application/json',
            'Accept:application/json',
            'Timeout:10',
            'Authorization:Bearer '.$this->token
        );
        $post = array(
            'organizationIds'       => $this->organization,
            "returnAdditionalInfo"=> true,
"includeDisabled"=> false
        );
        return  $curl
            ->to($this->url.'organizations')->withData($post)->withHeaders($headers)->asJson()
            ->post();
    }

    public function getCitiesAndStreetsList()
    {
        $curl = new CurlService();

        return $curl
            ->to($this->url.'/cities/cities?access_token='.$this->token.'&organization='.$this->organization)
            ->get();
    }

    public function getProducts()
    {


//        $data = [
//            'organizationIds' => [
//                'e3620000-7b0f-0696-7098-08d886212cd5'
//            ],
//            "includeDisabled" => true,
//        ];
//
////        dd(json_encode($data));
//        $data = json_encode($data);
//        $a = $curl
//            ->to('https://card.iiko.co.uk:9900/api/1/terminal_groups?access_token='.$this->token.'&request_timeout=10000')
//            ->withData($data)
////            ->asJson()
//            ->post();
//        dd(json_decode($a));
//        return $curl
//            ->to($this->url.'nomenclature/'.$this->organization.'?revision=0&access_token='.$this->token)
//            ->get();

        $curl = new CurlService();
        $headers = array(
            'Content-type:application/json',
            'Accept:application/json',
            'Timeout:10',
            'Authorization:Bearer '.$this->token
        );
        $post = array(
            'organizationId'       => $this->organization,

        );
        return  $curl
            ->to($this->url.'nomenclature')->withData($post)->withHeaders($headers)->asJson()
            ->post();


    }


    public function getTerminals(){
        $curl = new CurlService();

        $post = array(
            'organizationIds'       => [$this->organization],
        );
        $headers = array(
            'Content-type:application/json',
            'Accept:application/json',
            'Timeout:10',
            'Authorization:Bearer '.$this->token
        );


        return  $curl
            ->to($this->url.'terminal_groups')->withData($post)->withHeaders($headers)->asJson()
            ->post();
    }



    public function sendOrder($data)
    {

        $curl = new CurlService();
        $headers = array(
            'Content-type:application/json',
            'Accept:application/json',
            'Timeout:10',
            'Authorization:Bearer '.$this->token
        );
        return $curl
            ->to($this->url.'deliveries/create')
            ->withData($data)
            ->withHeaders($headers)->asJson()
            ->post();

    }

    public function getOrder($guid)
    {
        $curl = new CurlService();
        $headers = array(
            'Content-type:application/json',
            'Accept:application/json',
            'Timeout:10',
            'Authorization:Bearer '.$this->token
        );
        $post = array(
            'organizationId'       => $this->organization,
            'orderIds'       => [$guid],

        );
        return  $curl
            ->to($this->url.'deliveries/by_id')->withData($post)->withHeaders($headers)->asJson()
            ->post();
    }

    public function getOrdersByPhone($phone)
    {
        $curl = new CurlService();

        return $curl
            ->to($this->url.'orders/deliveryHistoryByPhone?access_token='.$this->token.'&organization='.$this->organization.'&phone='.$phone.'&requestTimeout=00:01:00')
            ->get();
    }

    public function getOrdersByUser($guid)
    {
        $curl = new CurlService();

        return $curl
            ->to($this->url.'orders/deliveryHistoryByCustomerId?access_token='.$this->token.'&organization='.$this->organization.'&customerId='.$guid.'&requestTimeout=00:01:00')
            ->get();
    }

    public function getOrdersByDate($from, $end)
    {
        $curl = new CurlService();

        return $curl
            ->to($this->url.'orders/deliveryOrders?access_token='.$this->token.'&organization='.$this->organization.'&dateFrom='.$from.'&dateTo='.$end.'&requestTimeout=00:01:00')
            ->get();
    }

    public function getDiscounts()
    {
        $curl = new CurlService();

        return $curl
            ->to($this->url.'deliverySettings/deliveryDiscounts?access_token='.$this->token.'&organization='.$this->organization)
            ->get();
    }

    public function getDiscountTypes()
    {
        $curl = new CurlService();

        return $curl
            ->to($this->url.'orders/get_manual_condition_infos?access_token='.$this->token.'&organization='.$this->organization)
            ->get();
    }
}
