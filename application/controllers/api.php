
<?php

/*
	Author : Glenn 
	Date : 2/24/2015

*/
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 

class Api extends CI_Controller {

	private $Base_Resource_Url = "specialPictures/";
		
	public function __construct()
    {
        parent::__construct();
        // Load models
        $this->load->model('Api_model', 'api');
		
    }
	
	
	public function userSignIn()
	{
		$fb_id = isset($_POST['fb_id'])? $_POST['fb_id']:"";
		
		$name  = isset($_POST['name'])? $_POST['name']:"";
		$devicetoken =  isset($_POST['devicetoken'])? $_POST['devicetoken']:"";
		$gcm_devicetoken =  isset($_POST['gcm_devicetoken'])? $_POST['gcm_devicetoken']:"";	
		
		$data = array(
			"fb_id"=>$fb_id,
			"name"=>$name,
		);
		
		$returnValue = array();
				
		$model = new Api_model();
		$user = $model->getUserData($fb_id);
			
		if(!is_array($user) || count($user) == 0)  /// if is not exist
		{
			$model->addUser($data);
			$returnValue['flag'] = "new"; 
		}else{
			$model->updateUserData($fb_id, $data);
			$returnValue['flag'] = "old";
						
			$user = $model->getUserData($fb_id);
			$returnValue['data'] = $user[0];
						
		}
		
		// Register Device	
			if($devicetoken != "")
				$model->registerDevice($fb_id, $devicetoken ,1);
			
			if($gcm_devicetoken != "")
				$model->registerAndroidDevice($fb_id, $gcm_devicetoken, 1);
				
		echo json_encode($returnValue);
		exit;
	
	}
	
	public function userLocationUpdate()
	{
		$fb_id = isset($_POST['fb_id'])? $_POST['fb_id']:"";
				
		$latitude  = isset($_POST['latitude'])? $_POST['latitude']:"";
		$longitude  = isset($_POST['longitude'])? $_POST['longitude']:"";
		
		$data = array(
			"fb_id"=>$fb_id,
			"latitude"=>$latitude,
			"longitude"=>$longitude,
		);
								
		$model = new Api_model();
		$user = $model->getUserData($fb_id);
			
		if(is_array($user) && count($user) != 0)  /// if is not exist
		{
			$model->updateUserData($fb_id, $data);
			
		}
		exit;
	}
	
	public function getUserData()
	{
		$fb_id = isset($_POST['fb_id'])? $_POST['fb_id']:"";
		
		$model = new Api_model();
		$user = $model->getUserData($fb_id);
		
		$returnValue = array();
		
		if(!is_array($user) || count($user) == 0)  /// if is not exist
		{
			$returnValue['success'] = "FAIL";
		}else{
			$returnValue['success'] = "OK";
			$returnValue['data'] = $user[0];
		}
		
		echo json_encode($returnValue);
		
		exit;
		
	}
	
	public function saveUserPreference()
	{
		$fb_id = isset($_POST['fb_id'])? $_POST['fb_id']:"";
				
		$push_flag  = isset($_POST['push_flag'])? $_POST['push_flag']:"";
		$contact_email  = isset($_POST['contact_email'])? $_POST['contact_email']:"";
		
		$data = array(
			"fb_id"=>$fb_id,
			"push_flag"=>$push_flag,
			"contact_email"=>$contact_email,
		);
		
		$model = new Api_model();
		$model->updateUserData($fb_id, $data);
		
		$returnValue = array("success" => "OK");
		
		echo json_encode($returnValue);
		
		exit;
	}
	
	
	 ////////////////////  Store Side   /////////////////
	 
	public function registerStore()
	{
		$fb_id = isset($_POST['fb_id'])? $_POST['fb_id']:"";
				
		$name  = isset($_POST['name'])? $_POST['name']:"";
		$street  = isset($_POST['street'])? $_POST['street']:"";
		$city  = isset($_POST['city'])? $_POST['city']:"";
		$latitude  = isset($_POST['latitude'])? $_POST['latitude']:"";
		$longitude  = isset($_POST['longitude'])? $_POST['longitude']:"";
		$phonenumber  = isset($_POST['phonenumber'])? $_POST['phonenumber']:"";
		$pagelink  = isset($_POST['pagelink'])? $_POST['pagelink']:"";
		$page_accesstoken  = isset($_POST['page_accesstoken'])? $_POST['page_accesstoken']:"";
		
		$contact_name  = isset($_POST['contact_name'])? $_POST['contact_name']:"";
		$paypalemail  = isset($_POST['paypalemail'])? $_POST['paypalemail']:"";
		$tax_rate  = isset($_POST['tax_rate'])? $_POST['tax_rate']:0.00;
		$
		$devicetoken =  isset($_POST['devicetoken'])? $_POST['devicetoken']:"";
		$gcm_devicetoken =  isset($_POST['gcm_devicetoken'])? $_POST['gcm_devicetoken']:"";		
		
		$token_limit = isset($_POST['token_limit'])? $_POST['token_limit']:"";
			
		
		$data = array(
			"fb_id"=>$fb_id,
			"name"=>$name,
			"contact_name"=>$contact_name,
			"street"=>$street,
			"city"=>$city,
			"latitude"=>$latitude,
			"longitude"=>$longitude,
			"phonenumber"=>$phonenumber,
			"tax_rate"=>$tax_rate,
			"paypalemail"=>$paypalemail,
			"pagelink"=>$pagelink,
			"page_accesstoken"=>$page_accesstoken,
			"token_limit"=>$token_limit
		);
		
		$model = new Api_model();
				
		$store = $model->getStoreData($fb_id);
		$returnString = array();
		
		if(count($store) == 0)  /// if is not exist
		{
			$model->addStore($data);
			if($devicetoken != "")
				$model->registerDevice($fb_id, $devicetoken, 0);
			
			if($gcm_devicetoken != "")
				$model->registerAndroidDevice($fb_id, $gcm_devicetoken, 0);
				
			$returnString['success'] = "OK";
		}else{
			$returnString['success'] = "fail";
		}
		echo json_encode($returnString);
		exit;
	}
	
	public function merchantSignIn()
	{
	
		$fb_id = isset($_POST['fb_id'])? $_POST['fb_id']:"";
		
		
		$name  = isset($_POST['name'])? $_POST['name']:"";
		$street  = isset($_POST['street'])? $_POST['street']:"";
		$city  = isset($_POST['city'])? $_POST['city']:"";
		$latitude  = isset($_POST['latitude'])? $_POST['latitude']:"";
		$longitude  = isset($_POST['longitude'])? $_POST['longitude']:"";
		$phone_number  = isset($_POST['phonenumber'])? $_POST['phonenumber']:"";
		$pagelink  = isset($_POST['pagelink'])? $_POST['pagelink']:"";
		$page_accesstoken  = isset($_POST['page_accesstoken'])? $_POST['page_accesstoken']:"";
		
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
			"pagelink"=>$pagelink,
			"page_accesstoken"=>$page_accesstoken
		);
		
		$returnValue = array();
				
		$model = new Api_model();
		$store = $model->getStoreData($fb_id);
			
		if(is_array($store) != true || count($store) == 0)  /// if is not exist
		{
			$returnValue['flag'] = "new"; 
		}else{
			$model->updateStoreData($fb_id, $data);
			$returnValue['flag'] = "old";
			
			$store = $model->getStoreData($fb_id);
			
			if(is_array($store) == true)  /// if is not exist
			{
				$returnValue['data'] = $store[0];
				//$date1 = strtotime($store[0]->published_time);
				//$date2 = strtotime($model->getMYSQLCurrentTime());
				$expire_time = $store[0]->expire_time;
				
                                $diffDay = $model->getExpireDayDiff($fb_id) ;
                                                   			
								
				$returnValue['expired_flag'] = "YES";
				$returnValue['rest_time'] = -1;
                                if ($diffDay > 0 ){
                                    $diff= $model->getExpireTimeDiff($fb_id) ;
                                    if($diff > 0){
                                            $diff = $expire_time * 60 - $diff;
                                            if($diff > 0){
                                                    $returnValue['expired_flag'] = "NO";
                                                    $returnValue['rest_time'] = $diff;
                                            }
                                    }
                                }
			
			
			}else{
				$returnValue['data'] = array();
			}
						
			
			// Register Device	
			if($devicetoken != "")
				$model->registerDevice($fb_id, $devicetoken, 0);
			
			if($gcm_devicetoken != "")
				$model->registerAndroidDevice($fb_id, $gcm_devicetoken, 0);
				
		}
		
		echo json_encode($returnValue);
		exit;
	}
	
	public function updateTokenLimit()
	{
		$fb_id = isset($_POST['fb_id'])? $_POST['fb_id']:"";
		$token_limit = isset($_POST['token_limit'])? $_POST['token_limit']:"";
		
		$model = new Api_model();
		$data = array("token_limit"=>$token_limit);
                
		$model->updateStoreData($fb_id, $data);
		
		$returnValue = array( "success"=> "OK");
		echo json_encode($returnValue);
		exit;		
	}
	
	
	public function getStoreData()
	{
		$fb_id = isset($_POST['fb_id'])? $_POST['fb_id']:"";
		
		$model = new Api_model();
		$store = $model->getStoreData($fb_id);
		
		$returnValue = array();
		
		if(!is_array($store) || count($store) == 0)  /// if is not exist
		{
			$returnValue['success'] = "FAIL";
		}else{
			$returnValue['success'] = "OK";
			$returnValue['data'] = $store[0];

			//$date1 = strtotime($store[0]->published_time);
			//$date2 = strtotime($model->getMYSQLCurrentTime());
			$expire_time = $store[0]->expire_time;
			
                        $diffDay = $model->getExpireDayDiff($fb_id) ;
                       	
			$returnValue['expired_flag'] = "YES";
			$returnValue['rest_time'] = -1;
                        
                        if($diffDay > 0 ){
                            $diff= $model->getExpireTimeDiff($fb_id) ;
                            if($diff > 0){
                                    $diff = $expire_time * 60 - $diff;
                                    if($diff > 0){
                                            $returnValue['expired_flag'] = "NO";
                                            $returnValue['rest_time'] = $diff;
                                    }
                            }
                        }
						
		}
		
		echo json_encode($returnValue);
		
		exit;
		
	}
	
	public function uploadPhoto()
	{
		// image processing....
		$fb_id = isset($_GET['fb_id'])?$_GET['fb_id']: "";
		$cur_date = date('Y_m_d_H_i_s');
		$image_name = $fb_id.$cur_date.".jpg";	
		if (isset($_FILES['upload_file'])) {
			$path = $_SERVER['DOCUMENT_ROOT'] ."/".$this->Base_Resource_Url.$image_name;
			move_uploaded_file($_FILES['upload_file']['tmp_name'], $path);
			
			$image_path = $this->Base_Resource_Url.$image_name;
			$model = new Api_model();
			
			$data = array(
				"image_path"=>$image_path
			);
			
			$model->updateStoreData($fb_id, $data);
			
			$returnValue = array();
			$returnValue["success"] = "OK";
			$returnValue["image_path"] = $image_path;
			echo json_encode($returnValue);
			exit;
			
		}
		
		echo "fail";
		exit;
			
	}
	
	public function getStoreList()
	{
		$model = new Api_model();
					
		$list = $model->getStoreList();
		
		$returnValue = array();
				
		if(!$list || count($list)== 0)
			$returnValue['data'] = array();
			
		else
			$returnValue['data'] = $list;
			
		echo json_encode($returnValue);
		exit;
	}
	
	public function  getDBTime()
	{
		$model = new Api_model();
		
		$current_date = $model->getMYSQLCurrentTime();
		
		var_dump($current_date);
		exit;
	}
	
	
	public function updateStoreData()
	{
		$fb_id = isset($_POST['fb_id'])? $_POST['fb_id']:"";
		
		$special_title  = isset($_POST['special_title'])? $_POST['special_title']:"";
		$special_price =  isset($_POST['special_price'])? $_POST['special_price']:0.00;
		$special_description  = isset($_POST['special_description'])? $_POST['special_description']:"";
		$publish_flag  = isset($_POST['publish_flag'])? $_POST['publish_flag']:"";
		$expire_time = isset($_POST['expire_time'])? $_POST['expire_time']:"";	
                $expire_day = isset($_POST['expire_day'])? $_POST['expire_day']:"";
                $token_limit = isset($_POST['token_limit'])? $_POST['token_limit']:"";
                $offer_token_limit = isset($_POST['offer_token_limit'])? $_POST['offer_token_limit']:"";
		
		$model = new Api_model();
		
		$current_date = $model->getMYSQLCurrentTime();
				
		$data = array(
			"special_description"=>$special_description,
			"special_title"=>$special_title,
			"special_price"=>$special_price,
			"publish_flag"=>$publish_flag,
			"published_time"=>$current_date,
			"expire_time"=>$expire_time,
                        "expire_day"=>$expire_day,
                        "token_limit"=>$token_limit,
                        "offer_token_limit"=>$offer_token_limit
		);
				
		$returnValue = array();
		
		if($fb_id != ""){
			$model->updateStoreData($fb_id, $data);
			$returnValue['success'] = "OK";
			
			$this->sendNotificationToUser($model, $fb_id);
		}else{
			$returnValue['success'] = "FAIL";
		}
			
		echo json_encode($returnValue);
		
		exit;
	}
	
	 public function sendNotificationToUser($model, $merchant_fb_id)
	 {
	
		$devicelist = $model->getDeviceList($merchant_fb_id, 1);
		$storeData = $model->getStoreData($merchant_fb_id);
		
		$special_title = "";
		$special_price = "";
		$merchant_name = "";
		
		if(is_array($storeData) && count($storeData) != 0)
		{
			$special_title = $storeData[0]->special_title;
			$special_price = $storeData[0]->special_price;
			$merchant_name = $storeData[0]->name;
		}
		
		$message = "RedBasket Offer Alert\n ".$special_title." for only $" . $special_price. " from" . $merchant_name;
		
		if(is_array($devicelist))
		{
			$this->sendPushNotification($devicelist, $merchant_fb_id, $message,true);
			
		} 
		
	 }
	
	public function unPublish()
	{
		$fb_id = isset($_POST['fb_id'])? $_POST['fb_id']:"";
		$publish_flag  = isset($_POST['publish_flag'])? $_POST['publish_flag']:"";
				
		$returnValue = array();
		$model = new Api_model();
		
		if($fb_id != ""){
                        
			$model->unpublishStore($fb_id);
                        $storeData = $model->getStoreData($fb_id);
                        
			$returnValue['success'] = "OK";
                        $returnValue['token_limit'] = $storeData[0]->token_limit;
                        
		}else{
			$returnValue['success'] = "FAIL";
		}
	
			
		echo json_encode($returnValue);
		
		exit;
		
	}
	
	public function updateAccountData()
	{
		$fb_id = isset($_POST['fb_id'])? $_POST['fb_id']:"";
				
		$contact_name  = isset($_POST['contact_name'])? $_POST['contact_name']:"";
		$paypalemail =  isset($_POST['paypalemail'])? $_POST['paypalemail']:"";
		$tax_rate  = isset($_POST['tax_rate'])? $_POST['tax_rate']:0.00;
				
		$data = array(
			"contact_name"=>$contact_name,
			"paypalemail"=>$paypalemail,
			"tax_rate"=>$tax_rate
		);
		
		
		$returnValue = array();
		$model = new Api_model();
		
		$model->updateStoreData($fb_id, $data);
		$returnValue['success'] = "OK";
			
		echo json_encode($returnValue);
		
		exit;
	}
	
	
	public function getOrderList()
	{
		$fb_id = isset($_POST['fb_id'])? $_POST['fb_id']:"";
		$startDate = isset($_POST['start_date'])? $_POST['start_date']:"";
		$startDateStr = substr($startDate , 0, 10);
		
		$endDate = isset($_POST['end_date'])? $_POST['end_date']:"";
		$endDateStr = substr($endDate , 0, 10);
		
		$model = new Api_model();
				
		$list = $model->getOrderList($fb_id, $startDateStr,$endDateStr);
		
		$returnValue = array();
				
		if(!$list || count($list)== 0)
			$returnValue['data'] = array();
			
		else
			$returnValue['data'] = $list;
			
		echo json_encode($returnValue);
		exit;
	}
		
	
	public function getUserOrderList()
	{
		$user_fb_id = isset($_POST['user_fb_id'])? $_POST['user_fb_id']:"";
						
		$model = new Api_model();
				
		$list = $model->getUserOrderList($user_fb_id);
		
		$returnValue = array();
				
		if(!$list || count($list)== 0)
			$returnValue['data'] = array();
			
		else
			$returnValue['data'] = $list;
			
		echo json_encode($returnValue);
		exit;
	}
	
	public function getOrderNumber()
	{
		$model = new Api_model();
						
		$orderNumber = $model->getOrderNumber();
					
		echo('orderNumber=='.$orderNumber);
	}
		
	
	public function addOrder()
	{
		$merchant_fb_id = isset($_POST['merchant_fb_id'])? $_POST['merchant_fb_id']:"";
		$user_fb_id = isset($_POST['user_fb_id'])? $_POST['user_fb_id']:"";
		$user_name = isset($_POST['user_name'])? $_POST['user_name']:"";
		$orderNumber  = isset($_POST['orderNumber'])? $_POST['orderNumber']:"";
		$merchant_paypal  = isset($_POST['merchant_paypal'])? $_POST['merchant_paypal']:"";
		$user_paypal  = isset($_POST['user_paypal'])? $_POST['user_paypal']:"";
		$transactionId  = isset($_POST['transactionId'])? $_POST['transactionId']:"";
		$special_title  = isset($_POST['special_title'])? $_POST['special_title']:"";
		$count  = isset($_POST['count'])? $_POST['count']:"";
		$unit_price  = isset($_POST['unit_price'])? $_POST['unit_price']:"";
		$tax  = isset($_POST['tax'])? $_POST['tax']:"";
		$total_price  = isset($_POST['total_price'])? $_POST['total_price']:"";
		
		$barcode = $this->getBarcode();
		
		$data = array(
			"merchant_fb_id"=>$merchant_fb_id,
			"user_fb_id"=>$user_fb_id,
			"user_name"=>$user_name,
			"merchant_paypal"=>$merchant_paypal,
			"user_paypal"=>$user_paypal,
			"special_title"=>$special_title,
			"count"=>$count,
			"unit_price"=>$unit_price,
			"tax"=>$tax,
			"total_price"=>$total_price,
			"transactionId"=>$transactionId,
			"orderNumber"=>$orderNumber,
			"barcode"=>$barcode
		);
		
		$model = new Api_model();
						
		$orderNumber = $model->addOrder($data);
		$returnValue = array();
		$returnValue['success'] = 'OK';
		$returnValue['barcode'] = $barcode;
		
		echo json_encode($returnValue);
		
               // decrease free_offer_limit 
                
                if($unit_price == 0) // Free Offer
                {
                    $model->decreaseSpecialOfferLimit($merchant_fb_id);
                }
                
                
		// Send Push notification 
	 	
		$devicelist = $model->getDeviceList($merchant_fb_id, 0);
	
		$message = "New RedBasket Sale\nYou have (".$count.") new RedBasket Sale";
		if(is_array($devicelist))
		{
			$this->sendPushNotification($devicelist, $merchant_fb_id, $message, false);
			
		} 
		exit;
		$android_devicelist = $model->getAndroidDeviceList($merchant_fb_id, 0);
	
		if(is_array($android_devicelist))
		{
			$this->sendGCMPushNotification($android_devicelist, $merchant_fb_id, $message);
			
		} 
		
	}
	
	public function getBarcode() 
	{
		$str = date('ymdHis');
				
		$sum = 0;
		for($i=12; $i>=1 ; $i--)
		{
			$j = (int)substr($str,$i-12,1);
			$m = ($i%2)==1 ? 3 : 1;
			$sum += ($m*$j);
		}
		
		$cs = 10 - ($sum%10);
		$tail = $cs == 10 ? 0 : $cs ;
		
		$barcode = $str.$tail;
		
		return $barcode;
	}
		
	
	public function completeOrder()
	{
		$orderNumber  = isset($_POST['orderNumber'])? $_POST['orderNumber']:"";
				
		$model = new Api_model();
						
		$orderNumber = $model->completeOrder($orderNumber);
		
		$returnValue = array("success"=>"OK");
		echo json_encode($returnValue);
		exit;
	}
	
	public function completeOrderUsingBarcode()
	{
		$fb_id = isset($_POST['fb_id']) ? $_POST['fb_id']:"";
		$barcode = isset($_POST['barcode'])? $_POST['barcode']:"";
		
		$model = new Api_model();
						
		$result = $model->completeOrderUsingBarcode($fb_id, $barcode);
		
		$returnValue = array();
		
		if(is_array($result) && count($result) != 0){
			$returnValue["success"] = "OK";
			$returnValue["data"] = $result[0];
		}else{
			$returnValue["success"] = "FAIL";
		}
		
		echo json_encode($returnValue);
		exit;
	}
	
	
	public function unCompleteOrder()
	{
		$orderNumber  = isset($_POST['orderNumber'])? $_POST['orderNumber']:"";
		
		$model = new Api_model();
						
		$orderNumber = $model->unCompleteOrder($orderNumber);
		
		$returnValue = array("success"=>"OK");
		echo json_encode($returnValue);
		exit;
	}
	
	/// iOS version ACM
	
	public function sendPushNotification($devicelist, $merchant_fb_id, $message, $user_flag)
	{
		// Put your private key's passphrase here:
		$passphrase = '123456';
	
		////////////////////////////////////////////////////////////////////////////////
		////////  //
		
		/*
		// SandBox
		if($user_flag)
			$pemSubPath = "/application/controllers/ck_user_sandbox.pem";
		else
			$pemSubPath = "/application/controllers/ck_merchant_sandbox.pem";
		
		$apnUrl = 'ssl://gateway.sandbox.push.apple.com:2195';
		*/
				
		
		// Pro
		if($user_flag){
			$pemSubPath = "/application/controllers/ck_user.pem";
		}else{
			$pemSubPath = "/application/controllers/ck_merchant.pem";
		}
		$apnUrl = 'ssl://gateway.push.apple.com:2195';
		
		
		$ctx = stream_context_create();
		$pemPath = $_SERVER['DOCUMENT_ROOT'] .$pemSubPath;
		stream_context_set_option($ctx, 'ssl', 'local_cert', $pemPath);
		stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

		$body['aps'] = array(
				'alert' => $message,
				'sound' => 'push_sound.mp3',
				'badge' => 0,
				'notify' => 'notification',
				'merchant_fb_id' => $merchant_fb_id
			);	
			
		$payload = json_encode($body);
		
		foreach($devicelist as  $row){
			$deviceToken = $row->devicetoken;
			
			$fp = stream_socket_client(	$apnUrl, $err,	$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

			if(!$fp)
				continue;
			
			// Encode the payload as JSON
			if($user_flag){	
				$body['user_fb_id'] = $row->fb_id;
				$payload = json_encode($body);
			}
			
			$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
			// Send it to the server
			fwrite($fp, $msg);
			
			fclose($fp);
		}
				
	}
	
	// GCM : Android Version 
	public function sendGCMPushNotification($devicelist, $fb_id,  $message)
	{
		$registatoin_ids = array();
		
		foreach($devicelist as  $row){
			$registatoin_ids[] = $row->devicetoken;
		}
		
				//Google cloud messaging GCM-API url
        $url = 'https://android.googleapis.com/gcm/send';
		
		$pushmessage = array("msg" => $message , "fb_id" => $fb_id);
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
		/*				
		$fb_id = "1443656369265544";		
		// Send Push notification 
		$devicelist = $model->getDeviceList($fb_id, 0);
		$message = "New RedBasket Sale\n  You have (1) new RedBasket Sale";
			
		$passphrase = '123456';
		
		////////////////////////////////////////////////////////////////////////////////

		// SandBox
		$pemSubPath = "/application/controllers/ck_merchant_sandbox.pem";
		$apnUrl = 'ssl://gateway.sandbox.push.apple.com:2195';
		
		// Pro
		//$pemSubPath = "/application/controllers/ck_merchant.pem";
		//$apnUrl = 'ssl://gateway.push.apple.com:2195';
		*/
		
		///////////////////// User Side /////////////////////
		
		$fb_id = "1443656369265544";
		$user_fb_id = "1431670627147629";
		$devicelist = $model->getDeviceList($fb_id, 1);
		
		$message = "New RedBasket Sale\n  You have (1) new RedBasket Sale";
			
		$passphrase = '123456';
		
		////////////////////////////////////////////////////////////////////////////////

		// SandBox
		$pemSubPath = "/application/controllers/ck_user_sandbox.pem";
		$apnUrl = 'ssl://gateway.sandbox.push.apple.com:2195';
		
		// Pro
		//$pemSubPath = "/application/controllers/ck_merchant.pem";
		//$apnUrl = 'ssl://gateway.push.apple.com:2195';
		
		
		
		$ctx = stream_context_create();
		
		$pemPath = $_SERVER['DOCUMENT_ROOT'] .$pemSubPath;
		stream_context_set_option($ctx, 'ssl', 'local_cert', $pemPath);
		stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

		// Open a connection to the APNS server
	
		 foreach($devicelist as  $row){

			$fp = stream_socket_client(	$apnUrl, $err,	$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

			if(!$fp)
				continue;
			
			$body['aps'] = array(
				'alert' => $message,
				'sound' => 'push_sound.mp3',
				'badge' => 0,
				'notify' => 'notification',
				'merchant_fb_id' => $fb_id,
				'user_fb_id'=>$user_fb_id
			);

			// Encode the payload as JSON
			$payload = json_encode($body);
					
			$deviceToken = $row->devicetoken; 
							//	$deviceToken = "7b28b1f0bfccbfc42216f2909b1515b3510f548014ce245a267abc27d0ae662d";
			// Build the binary notification
			$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
			// Send it to the server
			$result = fwrite($fp, $msg);
			
			fclose($fp);
		
		}
		
		
		exit;
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
