<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 

class Api extends CI_Controller {

	private $Base_Resource_Url = "public/restaurantPictures/";
		
	public function __construct()
    {
        parent::__construct();
        // Load models
        $this->load->model('Api_model', 'api');
    }
	
	public function registerRestaurant()
	{
	
		$fb_id = isset($_POST['fb_id'])? $_POST['fb_id']:"";
		
		$pagelink  = isset($_POST['pagelink'])? $_POST['pagelink']:"";
		$name  = isset($_POST['name'])? $_POST['name']:"";
		$street  = isset($_POST['street'])? $_POST['street']:"";
		$city  = isset($_POST['city'])? $_POST['city']:"";
		$latitude  = isset($_POST['latitude'])? $_POST['latitude']:"";
		$longitude  = isset($_POST['longitude'])? $_POST['longitude']:"";
		$phone_number  = isset($_POST['phonenumber'])? $_POST['phonenumber']:"";
		
		$contact_name  = isset($_POST['contact_name'])? $_POST['contact_name']:"";
		$paypalemail  = isset($_POST['paypalemail'])? $_POST['paypalemail']:"";
		$category  = isset($_POST['category'])? $_POST['category']:"";
		$tax  = isset($_POST['tax'])? $_POST['tax']:0.00;
		$devicetoken =  isset($_POST['devicetoken'])? $_POST['devicetoken']:"";
		$gcm_devicetoken =  isset($_POST['gcm_devicetoken'])? $_POST['gcm_devicetoken']:"";		
		
		$data = array(
			"fb_id"=>$fb_id,
			"name"=>$name,
			"street"=>$street,
			"city"=>$city,
			"latitude"=>$latitude,
			"longitude"=>$longitude,
			"phonenumber"=>$phone_number,
			"contact_name"=>$contact_name,
			"category"=>$category,
			"tax"=>$tax,
			"paypalemail"=>$paypalemail,
			"pagelink"=>$pagelink
		);
		
		$model = new Api_model();
				
		$restaurant = $model->getRestaurantData($fb_id);
		$returnString = array();
		
		if(count($restaurant) == 0)  /// if is not exist
		{
			$model->addRestaurant($data);
			if($devicetoken != "")
				$model->registerDevice($fb_id, $devicetoken);
			
			if($gcm_devicetoken != "")
				$model->registerAndroidDevice($fb_id, $gcm_devicetoken);
				
			$returnString['success'] = "ok";
		}else{
			$returnString['success'] = "fail";
		}
		echo json_encode($returnString);
	}
	
	public function signIn()
	{
		$fb_id = isset($_POST['fb_id'])? $_POST['fb_id']:"";
		
		$pagelink  = isset($_POST['pagelink'])? $_POST['pagelink']:"";
		$name  = isset($_POST['name'])? $_POST['name']:"";
		$street  = isset($_POST['street'])? $_POST['street']:"";
		$city  = isset($_POST['city'])? $_POST['city']:"";
		$latitude  = isset($_POST['latitude'])? $_POST['latitude']:"";
		$longitude  = isset($_POST['longitude'])? $_POST['longitude']:"";
		$phone_number  = isset($_POST['phonenumber'])? $_POST['phonenumber']:"";
		$devicetoken =  isset($_POST['devicetoken'])? $_POST['devicetoken']:"";
		$gcm_devicetoken =  isset($_POST['gcm_devicetoken'])? $_POST['gcm_devicetoken']:"";	
		
		
		$data = array(
			"fb_id"=>$fb_id,
			"name"=>$name,
			"street"=>$street,
			"city"=>$city,
			"latitude"=>$latitude,
			"longitude"=>$longitude,
			"phonenumber"=>$phone_number,
			"pagelink"=>$pagelink
		);
		
		$returnValue = array();
				
		$model = new Api_model();
		$restaurant = $model->getRestaurantData($fb_id);
		
		
		
		if(is_array($restaurant) != true || count($restaurant) == 0)  /// if is not exist
		{
		//	$model->addRestaurant($data);
			$returnValue['flag'] = "new"; 
		}else{
			$model->updateRestaurantData($fb_id, $data);
			$returnValue['flag'] = "old";
			
			if($devicetoken != "")
				$model->registerDevice($fb_id, $devicetoken);
			
			if($gcm_devicetoken != "")
				$model->registerAndroidDevice($fb_id, $gcm_devicetoken);
			
			
			$rows = $model->getRestaurantData($fb_id);
			
			if(is_array($rows) == true)  /// if is not exist
			{
				$returnValue['data'] = $rows[0];
			}else{
				$returnValue['data'] = array();
			}
		}
		
		echo json_encode($returnValue);
	}
	
	public function uploadPhoto()
	{
		// image processing....
		$fb_id = isset($_GET['fb_id'])?$_GET['fb_id']: "";
			
		if (isset($_FILES['upload_file'])) {
			$path = $_SERVER['DOCUMENT_ROOT'] ."/".$this->Base_Resource_Url.$fb_id.".jpg";
			copy($_FILES['upload_file']['tmp_name'], $path);
			
			$image_path = $this->Base_Resource_Url.$fb_id.".jpg";
			$model = new Api_model();
			
			$data = array(
				"image_path"=>$image_path
			);
			
			$model->updateRestaurantData($fb_id, $data);
			
			echo "ok";
			exit;
			
		}
		
		echo "fail";
			
	}
	
	public function getRestaurantList()
	{
		$model = new Api_model();
		$curDateStr = isset($_POST['date'])? $_POST['date']:"";	
		
		$curTime = date('H:i:s' , strtotime($curDateStr));
			
		$list = $model->getRestaurantList($curTime);
		
		$returnValue = array();
				
		if(!$list || count($list)== 0)
			$returnValue['data'] = array();
			
		else
			$returnValue['data'] = $list;
			
		echo json_encode($returnValue);
		
	}
	
	public function updateRestaurantData()
	{
		$fb_id = isset($_POST['fb_id'])? $_POST['fb_id']:"";
		
		$food_title  = isset($_POST['food_title'])? $_POST['food_title']:"";
		$price =  isset($_POST['price'])? $_POST['price']:0.00;
		$description  = isset($_POST['description'])? $_POST['description']:"";
		$publish_flag  = isset($_POST['publish_flag'])? $_POST['publish_flag']:"";
		$end_time = isset($_POST['end_time'])? $_POST['end_time']:"";		
		
		$data = array(
			"description"=>$description,
			"food_title"=>$food_title,
			"price"=>$price,
			"publish_flag"=>$publish_flag,
			"end_time"=>$end_time
		);
		
		
		$returnValue = array();
		$model = new Api_model();
		
		$model->updateRestaurantData($fb_id, $data);
		$returnValue['success'] = "YES";
			
		echo json_encode($returnValue);
		
		exit;
	}
	
	public function unPublish()
	{
		$fb_id = isset($_POST['fb_id'])? $_POST['fb_id']:"";
		$publish_flag  = isset($_POST['publish_flag'])? $_POST['publish_flag']:"";
		
		$data = array(
			"publish_flag"=>$publish_flag
		);
		
		$returnValue = array();
		$model = new Api_model();
		
		$model->updateRestaurantData($fb_id, $data);
		$returnValue['success'] = "YES";
			
		echo json_encode($returnValue);
		
		exit;
		
	}
	
	public function updateAccountData()
	{
		$fb_id = isset($_POST['fb_id'])? $_POST['fb_id']:"";
				
		$contact_name  = isset($_POST['contact_name'])? $_POST['contact_name']:"";
		$paypalemail =  isset($_POST['paypalemail'])? $_POST['paypalemail']:"";
		$category  = isset($_POST['category'])? $_POST['category']:"";
		$tax  = isset($_POST['tax'])? $_POST['tax']:0.00;
				
		$data = array(
			"contact_name"=>$contact_name,
			"paypalemail"=>$paypalemail,
			"category"=>$category,
			"tax"=>$tax
		);
		
		
		$returnValue = array();
		$model = new Api_model();
		
		$model->updateRestaurantData($fb_id, $data);
		$returnValue['success'] = "YES";
			
		echo json_encode($returnValue);
		
		exit;
	}
	
	
	public function getOrderList()
	{
		$fb_id = isset($_POST['fb_id'])? $_POST['fb_id']:"";
		$curDate = isset($_POST['date'])? $_POST['date']:"";
		$curDateStr = substr($curDate , 0, 10);
				
		$model = new Api_model();
				
		$list = $model->getOrderList($fb_id, $curDateStr);
		
		$returnValue = array();
				
		if(!$list || count($list)== 0)
			$returnValue['data'] = array();
			
		else
			$returnValue['data'] = $list;
			
		echo json_encode($returnValue);
		
	}
	
	public function getOrderNumber()
	{
		$model = new Api_model();
						
		$orderNumber = $model->getOrderNumber();
					
		echo('orderNumber=='.$orderNumber);
	}
	
	// Get Current Server Timestamp 
		
	public function getCurrentTime()
	{
		echo time();
	}
	
	public function addOrder()
	{
		$fb_id = isset($_POST['fb_id'])? $_POST['fb_id']:"";
		
		$orderNumber  = isset($_POST['orderNumber'])? $_POST['orderNumber']:"";
		$restaurant_paypal  = isset($_POST['restaurant_paypal'])? $_POST['restaurant_paypal']:"";
		$user_paypal  = isset($_POST['user_paypal'])? $_POST['user_paypal']:"";
		$transactionId  = isset($_POST['transactionId'])? $_POST['transactionId']:"";
		$food_title  = isset($_POST['food_title'])? $_POST['food_title']:"";
		$count  = isset($_POST['count'])? $_POST['count']:"";
		$unit_price  = isset($_POST['unit_price'])? $_POST['unit_price']:"";
		$tax  = isset($_POST['tax'])? $_POST['tax']:"";
		$total_price  = isset($_POST['total_price'])? $_POST['total_price']:"";
		$delivery_date  = isset($_POST['delivery_date'])? $_POST['delivery_date']:"";
		
		//$timestamp = date('Y-m-d H:i:s' , strtotime($delivery_date));	
		/* 	var_dump($delivery_date);
			var_dump($timestamp);exit; */
		$data = array(
			"fb_id"=>$fb_id,
			"orderNumber"=>$orderNumber,
			"restaurant_paypal"=>$restaurant_paypal,
			"user_paypal"=>$user_paypal,
			"transactionId"=>$transactionId,
			"food_title"=>$food_title,
			"count"=>$count,
			"unit_price"=>$unit_price,
			"tax"=>$tax,
			"total_price"=>$total_price,
			"delivery_date"=>$delivery_date
		);
		
		$model = new Api_model();
						
		$orderNumber = $model->addOrder($data);
		$returnValue = array();
		$returnValue['success'] = 'ok';
		
		echo json_encode($returnValue);
		
		// Send Push notification 
	 	
		$devicelist = $model->getDeviceList($fb_id);
	
		$message = "New Lunch Order Received: Count ".$count;
		if(is_array($devicelist))
		{
			$this->sendPushNotification($devicelist, $message);
			
		} 
		
		$android_devicelist = $model->getAndroidDeviceList($fb_id);
	
		if(is_array($android_devicelist))
		{
			$this->sendGCMPushNotification($android_devicelist, $message);
			
		} 
		
	}
	
	public function completeOrder()
	{
		$orderNumber  = isset($_POST['orderNumber'])? $_POST['orderNumber']:"";
		
		$model = new Api_model();
						
		$orderNumber = $model->completeOrder($orderNumber);
		
		$returnValue = array("success"=>"ok");
		echo json_encode($returnValue);
		exit;
	}
	
	public function unCompleteOrder()
	{
		$orderNumber  = isset($_POST['orderNumber'])? $_POST['orderNumber']:"";
		
		$model = new Api_model();
						
		$orderNumber = $model->unCompleteOrder($orderNumber);
		
		$returnValue = array("success"=>"ok");
		echo json_encode($returnValue);
		exit;
	}
	
	/// iOS version ACM
	
	public function sendPushNotification($devicelist, $message)
	{
		// Put your private key's passphrase here:
		$passphrase = '123456';
	
		////////////////////////////////////////////////////////////////////////////////

		$ctx = stream_context_create();
		$pemPath = $_SERVER['DOCUMENT_ROOT'] ."/application/controllers/ck_pro.pem";
		stream_context_set_option($ctx, 'ssl', 'local_cert', $pemPath);
		stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

					
		foreach($devicelist as  $row){
			$deviceToken = $row->devicetoken;
			
			$fp = stream_socket_client(	'ssl://gateway.push.apple.com:2195', $err,	$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

			if(!$fp)
				continue;
			
			$body['aps'] = array(
				'alert' => $message,
				'sound' => 'sound.wav',
				'badge' => 0,
				'notify' => 'notification',
			);

			// Encode the payload as JSON
			$payload = json_encode($body);
			
			$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
			// Send it to the server
			fwrite($fp, $msg);
			
			fclose($fp);
		}
				
	}
	
	// GCM : Android Version 
	public function sendGCMPushNotification($devicelist, $message)
	{
		$registatoin_ids = array();
		
		foreach($devicelist as  $row){
			$registatoin_ids[] = $row->devicetoken;
		}
		
				//Google cloud messaging GCM-API url
        $url = 'https://android.googleapis.com/gcm/send';
		
		$pushmessage = array("msg" => $message);
        $fields = array(
            'registration_ids' => $registatoin_ids,
            'data' => $pushmessage,
        );
		// Update your Google Cloud Messaging API Key
	//	define("GOOGLE_API_KEY", "AIzaSyBx2rFi40LTn58_OfhAX5K9onU49zTdKgA"); 	
		define("GOOGLE_API_KEY", "AIzaSyD1gtECnOXmxHdql7B3ZWB_BWWVd7FxLqI"); 	
		
        $headers = array(
            'Authorization: key=' . GOOGLE_API_KEY,
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);	
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);	
			
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
	
	}
	
	public function sendPush()
	{

		$model = new Api_model();
						
		$fb_id = "1443656369265544";		
		// Send Push notification 
		$devicelist = $model->getDeviceList($fb_id);
		$message = "New Lunch Order Received: Count";
			
		$passphrase = '123456';
		
		////////////////////////////////////////////////////////////////////////////////

		$ctx = stream_context_create();
		
		$pemPath = $_SERVER['DOCUMENT_ROOT'] ."/application/controllers/ck_pro.pem";
		stream_context_set_option($ctx, 'ssl', 'local_cert', $pemPath);
		stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

		// Open a connection to the APNS server
	
		 foreach($devicelist as  $row){

			$fp = stream_socket_client(	'ssl://gateway.push.apple.com:2195', $err,	$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

			if(!$fp)
				continue;
			
			$body['aps'] = array(
				'alert' => $message,
				'sound' => 'sound.wav',
				'badge' => 0,
				'notify' => 'notification',
			);

			// Encode the payload as JSON
			$payload = json_encode($body);
					
			$deviceToken = $row->devicetoken; 
								//$deviceToken = "7b28b1f0bfccbfc42216f2909b1515b3510f548014ce245a267abc27d0ae662d";
			// Build the binary notification
			$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
			// Send it to the server
			$result = fwrite($fp, $msg);
				var_dump($deviceToken);
			fclose($fp);
		
		}
		$devicelist = $model->getAndroidDeviceList($fb_id);
		
		$registatoin_ids = array();
		
		foreach($devicelist as  $row){
			$registatoin_ids[] = $row->devicetoken;
		}
		
		
		//Google cloud messaging GCM-API url
        $url = 'https://android.googleapis.com/gcm/send';
		
		$pushmessage = array("msg" => $message);
        $fields = array(
            'registration_ids' => $registatoin_ids,
            'data' => $pushmessage,
        );
		// Update your Google Cloud Messaging API Key
		define("GOOGLE_API_KEY", "AIzaSyBx2rFi40LTn58_OfhAX5K9onU49zTdKgA"); 		
        $headers = array(
            'Authorization: key=' . GOOGLE_API_KEY,
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);	
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);		
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
		
		/* if (!$result)
			echo 'Message not delivered' . PHP_EOL;
		else
			echo 'Message successfully delivered' . PHP_EOL; */

		// Close the connection to the server
	}
	
}
