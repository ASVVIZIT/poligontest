<?php

namespace App\Http\Controllers;

use App\Mail\SendInvoice;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Request;

class EmailController extends Controller
{

    public function create()
    {
    	$form = [
    		'email_to' => 'DillerASV@yandex.ru',
    		'subject' => 'Уведомление о статусе заказа {number} для {business_name} - {id-order-label} {id-order-value}',
    		'message' => File::get(base_path('resources/js/views/email/email-message.txt'))
    	];

    	$options = [
    		'variables' => [
    			'subject' => [
    			    '{label-order}' => 'Ваш заказ',
    			    '{id-order-label}' => 'ID Заказa',
                    '{id-order-value}' => 'ID',
    				'{number}' => 'Number',
    				'{business_name}' => 'Business Name'
    			],
    			'message' => [
                    '{status}' => 'Status',
                    '{label-order}' => 'Ваш заказ',
                    '{id-order-label}' => 'ID Заказa',
                    '{id-order-value}' => 'ID',
    				'{customer}' => 'Customer',
    				'{amount}' => 'Amount',
    				'{due_date}' => 'Время жизни ссылки',
    				'{example_link}' => 'Example Link',
    				'{business_name}' => 'Business Name',
    				'{info_contact_mail}' => 'info@laravue.dev'
    			]
    		],
    		'replaceable' => $this->getReplaceable()
    	];

    	return response()
    		->json([
    			'form' => $form,
    			'options' => $options
    		]);
    }

    protected function getReplaceable()
    {
    	return [
            '{label-order}' => 'Ваш заказ',
            '{id-order-label}' => 'ID заказa',
            '{id-order-value}' => '234',
            '{status}' => 'Заказ принят, и находится в статусе обработки',
    		'{number}' => 'INV/2018/1002',
    		'{business_name}' => 'Сидор Сидорович Inc',
    		'{customer}' => 'Иван Иванович Иванов',
    		'{amount}' => '$1850.00',
    		'{due_date}' => 'May 25th 2018',
    		'{example_link}' => 'https://example.com/example-link',
            '{info_contact_mail}' => 'info@laravue.dev'
    	];
    }

    public function send(Request $request)
    {
    	$request->validate([
    		'email_to' => 'required|email',
    		'subject' => 'required',
    		'message' => 'required',
    	]);

    	$data = [
    		'subject' => $this->replaceVariables($request->subject),
    		'message' => $this->replaceVariables($request->message),
    	];

    	return Mail::to($request->email_to)
    		->send(new SendInvoice($data));

    	// debug
        // return new SendInvoice($data);
    }

    protected function replaceVariables($message)
    {
    	$items = $this->getReplaceable();

    	foreach($items as $key => $value) {
    		$message = str_replace($key, $value, $message);
    	}

    	return $message;
    }
}
