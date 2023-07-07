<?php
/*
 * 	Perfex CRM IPPANEL Sms Module
 * 	
 * 	Link 	: https://github.com/miladworkshop/perfex-ippanel
 * 	
 * 	Author 	: Milad Maldar
 * 	E-mail 	: info@miladworkshop
 * 	Website : https://miladworkshop.ir
*/

defined('BASEPATH') or exit('No direct script access allowed');

class Sms_ippanel extends App_sms
{
    private $from;
    private $username;
    private $password;

    public function __construct()
    {
        parent::__construct();

        $this->from = $this->get_option('ippanel', 'from');
        $this->username = $this->get_option('ippanel', 'username');
        $this->password = $this->get_option('ippanel', 'password');

        $this->add_gateway('ippanel', [
            'name'    => 'آیپی پنل',
            'info'    => "<p>ارسال کلیه پیامک‌های سیستم از طریق سامانه پیامکی <a href='https://ippanel.com' target='_blank'>آیپی پنل</a> - طراحی و توسطعه داده شده توسط <a href='https://miladworkshop.ir' target='_blank'>میلاد مالدار</a></p><hr class='hr-10'>",
            'options' => [
                [
                    'name'  => 'from',
                    'label' => 'شماره فرستنده',
                ],
				[
                    'name'  => 'username',
                    'label' => 'نام کاربری',
                ],
				[
                    'name'  => 'password',
                    'label' => 'کلمه عبور',
                ],
            ],
        ]);
    }

    public function send($number, $message)
    {
		$param = array
		(
			'uname' 	=> $this->username,
			'pass' 		=> $this->password,
			'from' 		=> $this->from,
			'message' 	=> $message,
			'to' 		=> json_encode(array($number)),
			'op' 		=> 'send'
		);

		$handler = curl_init("https://ippanel.com/services.jspd");             
		curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($handler, CURLOPT_POSTFIELDS, $param);                       
		curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($handler, CURLOPT_SSL_VERIFYPEER, false);

		$response = curl_exec($handler);
		$response = json_decode($response);

		curl_close($handler);

		return (isset($response[0]) && $response[0] == 0 && isset($response[1]) && $response[1] > 0) ? true : false;
    }
}
