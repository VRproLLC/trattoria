<?php

namespace App\Wrappers;

use App\Enums\SendSmsEnum;
use Illuminate\Support\Facades\Log;

class TurboSmsWrapper
{
    protected $login;
    public $password;
    public $sender = '';
    public $options = [];

    public $debug = false;
    protected $client;
    protected $wsdl = 'http://turbosms.in.ua/api/wsdl.html';

    public function __construct()
    {
        $this->login = env('SMS_TURBOSMS_LOGI');
        $this->password = env('SMS_TURBOSMS_PASSWOR');
        $this->sender = env('SMS_TURBOSMS_SENDE');
        $this->debug = env('SMS_TURBOSMS_DEBUG');
    }

    protected function getClient()
    {
        if (!$this->client) {
            return $this->connect();
        }
        return $this->client;
    }

    protected function connect()
    {
        if ($this->client) {
            return $this->client;
        }

        if (class_exists('SOAPClient')) {
            try {
                $client = new \SoapClient($this->wsdl, $this->options);
                if (!$this->login || !$this->password) {
                    $error = 'Enter login and password for Turbosms in config file';
                } else {
                    $result = $client->Auth(
                        [
                            'login' => $this->login,
                            'password' => $this->password,
                        ]
                    );

                    // check for authentification result
                    if ($result->AuthResult . '' != 'Вы успешно авторизировались') {
                        $error = 'Soap auth: ' . $result->AuthResult;
                    } else {
                        $this->client = $client;
                    }
                }
            } catch (\SoapFault $e) {
                $error = $e->getMessage();
                // disable laravel exception https://github.com/laravel/framework/issues/6618
                set_error_handler('var_dump', 0); // Never called because of empty mask.
                @trigger_error("");
                restore_error_handler();

            }
        } else {
            $error = 'No SOAP client. Install Extesions php-soap';
        }

        return $this->client ? $this->client : $error;
    }


    public function send($phone, string $text)
    {
        if (!$this->debug) {
            $client = $this->getClient();
            if (is_a($client, 'SoapClient')) {
                $destination = $phone;

                if (is_array($phone)) {
                    $destination = implode(",", $phone);
                }
                // send Sms with Soap
                $results = $client->SendSMS(
                    [
                        'sender' => $this->sender,
                        'destination' => $destination,
                        'text' => $text
                    ]
                );

                if (is_array($results->SendSMSResult->ResultArray)) {
                    unset($results->SendSMSResult->ResultArray['0']);
                    if (is_array($results->SendSMSResult->ResultArray)) {
                        foreach ($results->SendSMSResult->ResultArray as $key => $result) {
                            if (preg_match(
                                '/^\{?[a-z0-9]{8}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{12}\}?$/',
                                $result
                            )) {
                                $status = 1; // Send
                                $status_detail = 'Message send';
                                $messageid = $result;
                            } else {
                                if (strpos($result, 'Не удалось распознать номер получателя') !== false || strpos(
                                        $result,
                                        'Страна не поддерживается'
                                    ) !== false) {
                                    $status = 3; // Undelivered
                                    $status_detail = 'Message undelivered: ' . $result;
                                    $messageid = null;
                                } else {
                                    $status = 2; // Waiting
                                    $status_detail = 'Message wait retry: ' . $result;
                                    $messageid = null;
                                }
                            }
                            $messages[] = [
                                'status' => $status,
                                'status_detail' => $status_detail,
                                'messageid' => $messageid,
                            ];
                        }

                        return $messages;
                    }
                } else {
                    $status = 2; // Waiting for resolve problem with SendSMS
                    $status_detail = $results->SendSMSResult->ResultArray;
                    $messageid = null;
                }
            } else {
                $status = 2; // Waiting for resolve problem with SOAP
                $status_detail = $client; // Log SOAP problem
                $messageid = null;
            }
        } else {
            $status = 1; // Sended
            $status_detail = 'Debug mode'; // Debug mode enabled
            $messageid = null;
        }
        // Error response for message
        if (!is_array($phone)) {
            $phone = [$phone];
        }

        foreach ($phone as $item) {
            $messages[] = [
                'status' => $status,
                'status_detail' => $status_detail,
                'messageid' => $messageid,
            ];
        }

        return $messages;
    }


    public function getBalance()
    {
        if (!$this->debug) {
            // get SOAP client
            $client = $this->getClient();
            // fi we have successful client soap created
            if (is_a($client, 'SoapClient')) {
                $balance = intval($client->GetCreditBalance()->GetCreditBalanceResult);
            } else {
                $balance = $client;
            }
        } else {
            $balance = 0;
        }

        return $balance;
    }

    public function getMessageStatus(string $messageId)
    {
        if (!$this->debug) {
            // get SOAP client
            $client = $this->getClient();
            // fi we have successful client soap created
            if (is_a($client, 'SoapClient')) {
                $result = $client->GetMessageStatus(['MessageId' => $messageId])->GetMessageStatusResult;
                //default
                $status = '';
                // work with statuses
                $all_statuses = [
                    '0' => 'не найдено',
                    '1' => 'Отправлено',
                    '2' => 'В очереди',
                    '3' => 'Сообщение передано в мобильную сеть',
                    '4' => 'Сообщение доставлено получателю',
                    '5' => 'Истек срок сообщения',
                    '6' => 'Удалено оператором',
                    '7' => 'Не доставлено',
                    '8' => 'Сообщение доставлено на сервер',
                    '9' => 'Отклонено оператором',
                    '10' => 'Неизвестный статус',
                    '11' => 'Ошибка, сообщение не отправлено',
                    '12' => 'Не достаточно кредитов на счете',
                    '13' => 'Отправка отменена',
                    '14' => 'Отправка приостановлена',
                    '15' => 'Удалено пользователем',
                ];

                foreach ($all_statuses as $key => $value) {
                    if (strpos($result, $value) !== false) {
                        $status = $key;
                        break;
                    }
                }
                $info[] = [
                    'status' => $status,
                    'status_description' => $result,
                ];
            } else {
                $info[] = [
                    'status' => '',
                    'status_description' => $client,
                ];
            }
        } else {
            $info[] = [
                'status' => '',
                'status_description' => 'Debug mode',
            ];
        }

        return $info;
    }
}
