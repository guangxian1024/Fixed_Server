
<?php

// Utility Function
        
        function comparePersonWithScore($person1, $person2)
        {
            if ($person1->match_score == $person2->match_score) {
               return 0;
            }
           
            return ($person1->match_score > $person2->match_score) ? -1 : 1;
        }

/*
	Author : Glenn 
	Date : 5/24/2015

*/
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 

class Api extends CI_Controller {

    private $Base_Resource_Url = "photos/";
   
    // Weights for Match Score
    private $sex_weight = 0.01;
    private $sex_seeking_weight = 0.01;
    private $single_weight = 0.01;
    private $age_weight = 0.15;
    private $distance_weight = 0.15;
    private $height_weight = 0.15;
    private $religion_weight = 0.25;
    private $match_tags_weight = 0.27;
   
    public function __construct()
    {
        parent::__construct();
        // Load models
        $this->load->model('Api_model', 'api');
		
    }
		
    
	public function fb_login()
	{
		$fb_id = isset($_POST['fb_id'])? $_POST['fb_id']:"";
		
		$name  = isset($_POST['name'])? $_POST['name']:"";
                $email = isset($_POST['email'])? $_POST['email']:"";
                $sex = isset($_POST['sex'])? $_POST['sex']:0;
		$birthday = isset($_POST['birthday'])? $_POST['birthday']:"";
                $single = isset($_POST['single'])? $_POST['single']:0;
                $workplace = isset($_POST['workplace'])? $_POST['workplace']:"";
                $schools = isset($_POST['schools'])? $_POST['schools']:"";
                $interest = isset($_POST['interest'])? $_POST['interest']:"";
                $state = isset($_POST['state'])? $_POST['state']:"";
                $city = isset($_POST['city'])? $_POST['city']:"";
                $street = isset($_POST['street'])? $_POST['street']:"";
                $zipcode = isset($_POST['zipcode'])? $_POST['zipcode']:"";
                $latitude = isset($_POST['latitude'])? $_POST['latitude']:0.0;
                $longitude = isset($_POST['longitude'])? $_POST['longitude']:0.0;
                $religion = isset($_POST['religion'])? $_POST['religion']:0;
                $fb_friend_list = isset($_POST['fb_friend_list'])? $_POST['fb_friend_list']:"";
                
                $devicetoken =  isset($_POST['devicetoken'])? $_POST['devicetoken']:"";
			
		$data = array(
                    "fb_id"=>$fb_id,
                    "name"=>$name,
                    "email"=>$email,
                    "sex"=>$sex,
                    "birthday"=>$birthday,
                    "single"=>$single,
                    "workplace"=>$workplace,
                    "schools"=>$schools,
                    "interest"=>$interest,
                    "state"=>$state,
                    "city"=>$city,
                    "street"=>$street,
                    "zipcode"=>$zipcode,
                    "latitude"=>$latitude,
                    "longitude"=>$longitude,
                    "religion"=>$religion                    
		);
		
                $fb_friend_temp_array = json_decode($fb_friend_list);
                if(is_array($fb_friend_temp_array) && count($fb_friend_temp_array) != 0 ){
                    $data['fb_friend_list'] = $fb_friend_list;
                }
                    
		$returnValue = array();
				
		$model = new Api_model();
		$user = $model->getUserData($fb_id);
			
		if(!is_array($user) || count($user) == 0)  /// if is not exist
		{
                        $photo_paths = array($fb_id.".jpg");
                        $data["photo_path"] = json_encode($photo_paths);
			$model->addUser($data);
			$returnValue['flag'] = "new"; 
		}else{
			$model->updateUserData($fb_id, $data);
			$returnValue['flag'] = "old";
                }
                
                $users = $model->getUserData($fb_id);
                
                $userObj = $users[0];
                
                $userObj["my_friend_list"] = array();
                $friendListStr = $userObj["fb_friend_list"];
                
                if(!is_null($friendListStr)){
                    $fb_friend_ids = json_decode($friendListStr);
                    
                    if (is_array($fb_friend_ids)) {
                        $userObj["my_friend_list"] = $model->getFriendList($fb_friend_ids);
                    }
                }
                               
                    
		$returnValue['data'] = $userObj;
                		
		// Register Device	
			if($devicetoken != "")
				$model->registerDevice($fb_id, $devicetoken);
						
		echo json_encode($returnValue);
		exit;
	
	}
	
        public function getMyFriendList(){
            $fb_id = isset($_POST['fb_id'])? $_POST['fb_id']:"";
            
            if($fb_id == "")
                return;
            
            $model = new Api_model();
            $users = $model->getUserData($fb_id);
              //  var_dump($users);
                $userObj = $users[0];
                
                $userObj["my_friend_list"] = array();
                $friendListStr = $userObj["fb_friend_list"];
                
                if(!is_null($friendListStr)){
                    $fb_friend_ids = json_decode($friendListStr);
                    
                    if (is_array($fb_friend_ids)) {
                        $userObj["my_friend_list"] = $model->getFriendList($fb_friend_ids);
                    }
                }
                    
		$returnValue['data'] = $userObj;
                		
		// Register Device	
			if($devicetoken != "")
				$model->registerDevice($fb_id, $devicetoken);
						
		echo json_encode($returnValue);
                
        }
        
        
        public function logout(){
            $fb_id = isset($_POST['fb_id'])? $_POST['fb_id']:"";
            $devicetoken =  isset($_POST['devicetoken'])? $_POST['devicetoken']:"";
            
            $model = new Api_model();
            if($devicetoken != "")
		$model->gotoOfflineDevice($fb_id, $devicetoken);
            
            $returnValue = array();
            $returnValue['success'] = "OK";
            
            echo json_encode($returnValue);
		exit;
        }
        
        // Refresh Fixes every 1 day : 12:00 PM
        
        public function refresh_fixes(){
            $model = new Api_model();
            $model->resetFixes();
        }
        
        public function addCoin(){
            $fb_id = isset($_POST['fb_id'])? $_POST['fb_id']:"";
            $coin_count =  isset($_POST['coin_count'])? $_POST['coin_count']:"";
            
            $model = new Api_model();
            $model->addCoin($fb_id, $coin_count);
            
            $returnValue = array();
            $returnValue['success'] = "OK";
            
            echo json_encode($returnValue);
            exit;
        }
        
        public function withdrawCoin(){
            $fb_id = isset($_POST['fb_id'])? $_POST['fb_id']:"";
            $coin_count =  isset($_POST['coin_count'])? $_POST['coin_count']:"";
            
            $model = new Api_model();
            $model->withdrawCoin($fb_id, $coin_count);
            
            $returnValue = array();
            $returnValue['success'] = "OK";
            
            echo json_encode($returnValue);
            exit;
        }
        
	public function userLocationUpdate()
	{
		$fb_id = isset($_POST['fb_id'])? $_POST['fb_id']:"";
				
		$latitude  = isset($_POST['latitude'])? $_POST['latitude']:0.0;
		$longitude  = isset($_POST['longitude'])? $_POST['longitude']:0.0;
		
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
	
        public function getFriendProfile(){
            $fb_id = isset($_POST['fb_id'])?$_POST['fb_id']: "";
		if($fb_id == "")
                   exit;
                
            $model = new Api_model();
            
            $user = $model->getProfileData($fb_id);
            
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
        
        public function saveProfile(){
            $fb_id = isset($_POST['fb_id'])?$_POST['fb_id']: "";
            $tagline = isset($_POST['tagline'])?$_POST['tagline']: "";
            $height = isset($_POST['height'])?$_POST['height']:0;
            $religion = isset($_POST['religion'])?$_POST['religion']: "";
            $photo_path = isset($_POST['photo_path'])?$_POST['photo_path']: "";
                             
            $model = new Api_model();
            
            $data = array();
            $data['tagline'] = $tagline;
            $data['height'] = $height;
            $data['religion'] = $religion;
            
            if($photo_path != "")
                $data['photo_path'] = $photo_path;
            
            $model->updateUserData($fb_id,$data);
            $returnValue['success'] = "OK";
            echo json_encode($returnValue);
		
            exit;
                
        }
        
        public function changeSetting(){
            $fb_id = isset($_POST['fb_id'])? $_POST['fb_id']:"";
            
            $data = array();
            
            if(isset($_POST['fix_reminder']))
                $data["fix_reminder"] = $_POST['fix_reminder'];
            if(isset($_POST['cash_notification']))
                $data["cash_notification"] = $_POST['cash_notification'];
            if(isset($_POST['match_notification']))
                $data["match_notification"] = $_POST['match_notification'];
            if(isset($_POST['chat_notification']))
                $data["chat_notification"] = $_POST['chat_notification'];
            if(isset($_POST['alert_setting']))
                $data["alert_setting"] = $_POST['alert_setting'];
            if(isset($_POST['update_setting']))
                $data["update_setting"] = $_POST['update_setting'];
            if(isset($_POST['email']))
                $data["email"] = $_POST['email'];
                      
               
            $model = new Api_model();
            
            $model->updateUserData($fb_id, $data);

            $returnValue = array("success" => "OK");

            echo json_encode($returnValue);

            exit;
                
        }
        
	public function savePreference()
	{
		$fb_id = isset($_POST['fb_id'])? $_POST['fb_id']:"";
				
		$is_man  = isset($_POST['is_man'])? $_POST['is_man']:0;
		$is_interested_man  = isset($_POST['is_interested_man'])? $_POST['is_interested_man']:0;
                $is_single  = isset($_POST['is_single'])? $_POST['is_single']:0;
                $religion_priority  = isset($_POST['religion_priority'])? $_POST['religion_priority']:0;
                $match_zipcode  = isset($_POST['match_zipcode'])? $_POST['match_zipcode']:"";
                $distance_range  = isset($_POST['distance_range'])? $_POST['distance_range']:0;
                $min_age  = isset($_POST['min_age'])? $_POST['min_age']:0;
                $max_age = isset($_POST['max_age'])? $_POST['max_age']:100;
                $min_height  = isset($_POST['min_height'])? $_POST['min_height']:0;
                $max_height  = isset($_POST['max_height'])? $_POST['max_height']:1000;
                               
		
		$data = array(
                    "is_man"=>$is_man,
                    "is_interested_man"=>$is_interested_man,
                    "is_single"=>$is_single,
                    "religion_priority"=>$religion_priority,
                    "match_zipcode"=>$match_zipcode,
                    "distance_range"=>$distance_range,
                    "min_age"=>$min_age,
                    "max_age"=>$max_age,
                    "min_height"=>$min_height,
                    "max_height"=>$max_height
                  
		);
		
		$model = new Api_model();
		$model->updateUserData($fb_id, $data);
		
		$returnValue = array("success" => "OK");
		
		echo json_encode($returnValue);
		
		exit;
	}
	
        
        //////////////////  ------  My Matches Part   -----   //////////////////
        
        public function getMyMatches(){
            $fb_id = isset($_POST['fb_id'])? $_POST['fb_id']:"";
            
            $model = new Api_model();
            $result = $model->getMyMatches($fb_id);
            
            $returnValue = array();
            $returnValue["success"] =  "OK";
            $returnValue["data"] = $result;
            
            echo json_encode($returnValue);
		
            exit;
            
        }
        
	public function likeFriend(){
            $fb_id = isset($_POST['fb_id'])? $_POST['fb_id']:"";
            
            $id = isset($_POST['id'])? $_POST['id']:"";
            
            $model = new Api_model();
            $model->likeFriend($id, $fb_id);
            
            $returnValue = array("success" => "OK");
		
            echo json_encode($returnValue);
		
            exit;
                
            
        }
        
        public function acceptMatch(){
                       
            $id = isset($_POST['id'])? $_POST['id']:"";
            
            $model = new Api_model();
            $model->acceptMatch($id);
            
            $returnValue = array("success" => "OK");
		
            echo json_encode($returnValue);
		
            exit;
        }
        
        public function dislikeMatch(){
                       
            $id = isset($_POST['id'])? $_POST['id']:"";
            
            $model = new Api_model();
            $model->dislikeMatch($id);
            
            $returnValue = array("success" => "OK");
		
            echo json_encode($returnValue);
		
            exit;
        }
        
        
	public function uploadPhoto()
	{
		// image processing....
		$fb_id = isset($_GET['fb_id'])?$_GET['fb_id']: "";
		$flag = isset($_GET['save_flag'])?$_GET['save_flag']: false;
                
                $returnValue = array();
                
		if (isset($_FILES['upload_file'])) {
                        $image_name = $_FILES['upload_file']['name'];
			$path = $_SERVER['DOCUMENT_ROOT'] ."/".$this->Base_Resource_Url.$image_name;
			move_uploaded_file($_FILES['upload_file']['tmp_name'], $path);
			
			$returnValue["success"] = "OK";
			$returnValue["image_path"] = $image_name;
                        
                        if($flag){
                            $photo_paths = array($image_name);
                            $data = array(
                                "photo_path"=> json_encode($photo_paths)
                            );
                            $model = new Api_model();
                            $model->updateUserData($fb_id, $data);
                        }
		}else{
		    $returnValue["success"] = "FAIL";
                }
                
                echo json_encode($returnValue);
                
		exit;
			
	}
	
        
        
        
        //Get Suggested Friend List 
        
        // in : user_fb_id
        //out: friend_list[5][]; 
	public function getSuggestedFriendList()
	{
                $fb_id = isset($_POST['fb_id'])?$_POST['fb_id']: "";
		if($fb_id == "")
                   exit;
                
                $model = new Api_model();
		               
		$result = $model->getUserFriendList($fb_id);
		
		$returnValue = array();
				
		if(is_array($result) && count($result) != 0 ){
                    $friend_list = json_decode($result[0]->fb_friend_list);
                    
                    if(is_array($friend_list) && count($friend_list) != 0 ){
                        $me = $model->getPersonForMatch($fb_id);
                        $personObjList = array();
                        
                        foreach($friend_list as $person_id){
                            
                            $person = $model->getPersonForMatch($person_id);
                            $person->match_score = $this->calculateMatchScore($me, $person);
                            $personObjList[] = $person;
                        }
                        
                        usort($personObjList, "comparePersonWithScore");
                                             
                        $limit = 5;
                        
                        if(count($personObjList) < 5){
                            $limit  =  count($personObjList);
                        }
                        
                        $suggested_list = array();
                        
                        for($i = 0 ; $i < $limit ; $i++){
                            $temp_list = array();
                            $top_friend =  $personObjList[$i];
                            $temp_list[0] = $this->getFriendDataFromObject($top_friend);
                            
                            $friend_friends = $this->getFriendsOfFriend($top_friend->fb_id, $personObjList);
                            
                            $friends_limit = 9;
                            if(count($friend_friends) < 9){
                                $friends_limit = count($friend_friends);
                            }
                            
                            for($j =0 ; $j < $friends_limit ; $j++ ){
                               
                                $temp_list[] = $this->getFriendDataFromObject($friend_friends[$j]);
                            }
                            
                            
                            $suggested_list[$i] = $temp_list;
                        }
                        
                        // Set ReturnValue 
                        $returnValue["success"] = "OK";
                        $returnValue["data"] = $suggested_list;
                        echo json_encode($returnValue);
                        exit;
                        
                                                
                    }
                        
                }
		
                $returnValue["success"] = "FAIL";	
		echo json_encode($returnValue);
		exit;
	}
	
        public function getFriendsOfFriend($friend_fb_id, $friend_list){
                $model = new Api_model();
		                  
		$result = $model->getUserFriendList($friend_fb_id);
								
		if(is_array($result) && count($result) != 0 ){
                    $friend_friends = json_decode($result[0]->fb_friend_list); // Friends of friend
                    
                    if(is_array($friend_friends) && count($friend_friends) != 0 ){
                        foreach($friend_friends as $person_id){
                            $flag = FALSE;
                                                       
                            foreach($friend_list as $temp){
                                if($person_id == $temp->fb_id){
                                    $flag = TRUE;
                                    break ;
                                }
                                    
                            }
                            
                            if(!$flag){
                                
                                $person = $model->getPersonForMatch($person_id);
                                
                                $friend_list[count($friend_list)] = $person;
                            }
                        }
                        
                    }
                    
                }
                
                $friend = $model->getPersonForMatch($friend_fb_id);
                foreach($friend_list as $person){
                    $person->match_score = $this->calculateMatchScore($friend, $person);
                }
                
                usort($friend_list, "comparePersonWithScore");
                             
                return $friend_list;
        }
        
        public function getFriendDataFromObject($person){
           
            $returndata = array();
            $returndata["fb_id"] = $person->fb_id;
            $returndata["name"] = $person->name;
            $returndata["match_score"] = $person->match_score;
            $returndata["interest"] = $person->interest;
            $returndata["match_tags"] = $person->match_tags;
            
            return $returndata;
            
        }
        
	public function  getDBTime()
	{
		$model = new Api_model();
		
		$current_date = $model->getMYSQLCurrentTime();
		
		var_dump($current_date);
                var_dump(date("Y-m-d H:i:s"));
		exit;
	}
	       

        public function getScoreFromMatchCount($match_count){
            if($match_count < 15){
                return $match_count * 0.1;
            }else {
                return 1.5; 
            }
        } 
        
	public function calculateMatchScore($person1, $person2){
                      
            $sex_score = 0;
            if($person1->sex != $person2->sex){
                $sex_score = 1;
            }
            $sex_score = $sex_score * $this->sex_weight;
            
            $sex_seeking_score = 0 ;
            if($person1->is_man != $person2->is_man)
            {
                $sex_seeking_score = 1;
            }
            $sex_seeking_score = $sex_seeking_score * $this->sex_seeking_weight;
            
            
            $single_score = 0;
            if($person1->single == $person2->single){
                $single_score = 1;
            }
            $single_score = $single_score * $this->single_weight;
            
            $age_score = 0;
            if($person1->min_age <= $person2->age && $person1->max_age >= $person2->age ){
                $age_score = 1;
            }
            $age_score = $age_score * $this->age_weight;
            
            $distance_score = 0;
            $tempDistance = $this->distance($person1->latitude, $person1->longitude, $person2->latitude, $person2->longitude, "M");
            if($person1->distance_range >= $tempDistance){
                $distance_score = 1;
            }
            $distance_score = $distance_score * $this->distance_weight;
            
            
            $height_score = 0;
            if($person1->min_height <= $person2->height && $person1->max_height >= $person2->height ){
                $height_score = 1;
            }
            $height_score = $height_score * $this->height_weight;
                        
            $religion_score = 0;
            if($person1->religion != $person2->religion){
                $religion_score = -1;
            }
           
            
            
            if($person1->religion_priority == 0){
                $religion_score ++;
            }else if($person1->religion_priority == 2){
                $religion_score --;
            }
            $religion_score = $religion_score * $this->religion_weight;
             
            $match_tags_score = $this->getScoreFromMatchCount($person2->match_tags) * $this->match_tags_weight;
            
            $total_score = $sex_score + $sex_seeking_score +$single_score + $age_score + $distance_score + $height_score + $religion_score + $match_tags_score;
            
            return $total_score;
        
        }
	
        
        function distance($lat1, $lon1, $lat2, $lon2, $unit) {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);
            if ($unit == "K") {
              return ($miles * 1.609344);
            } else if ($unit == "N") {
                return ($miles * 0.8684);
              } else {
                  return $miles;
                }
        }


       public function fixIt(){
            
            $user1 = isset($_POST['user1'])?$_POST['user1']: "";
            $user2 = isset($_POST['user2'])?$_POST['user2']: "";
            $provider = isset($_POST['provider'])?$_POST['provider']: "";
            $comment = isset($_POST['comment'])?$_POST['comment']: "";
            $anonymous = isset($_POST['anonymous'])?$_POST['anonymous']: "";
            
                
            $model = new Api_model();
            
            // MySql Time format : date("Y-m-d H:i:s")
            
            $now = date("Y-m-d H:i:s");
            
            $data = array(
                "user1"=>$user1,
                "user2"=>$user2,
                "provider"=>$provider,
                "fix_date"=>$now,
                "comment"=>$comment,
                "anonymous"=>$anonymous
            );
            
            $model->addMatch($data);
            $model->decreaseActiveFixes($provider);
            
            $returnValue = array();
            $returnValue['success'] = "OK";
		
            echo json_encode($returnValue);
		
            exit;
       }
        
       
       public function getSuggestedMatchByMe(){
           $fb_id = isset($_POST['fb_id'])?$_POST['fb_id']: "";
           $model = new Api_model();
           
           $result = $model->getSuggestedMatchByMe($fb_id);
            
           $returnValue = array();
           $returnValue['success'] = "OK";
           $returnValue['data'] = $result;
		
            echo json_encode($returnValue);
		
            exit;
                   
       }
       
       public function getFixedMatchByMe(){
           $fb_id = isset($_POST['fb_id'])?$_POST['fb_id']: "";
           $model = new Api_model();
           
           $result = $model->getFixedMatchByMe($fb_id);
            
           $returnValue = array();
           $returnValue['success'] = "OK";
           $returnValue['data'] = $result;
		
            echo json_encode($returnValue);
		
            exit;
                   
       }
       
       public function getStatistics(){
           $fb_id = isset($_POST['fb_id'])?$_POST['fb_id']: "";
           $model = new Api_model();
           
           $suggestedMatches = $model->getSuggestedMatchCountByMe($fb_id);
           $fixedMatches = $model->getFixedMatchCountByMe($fb_id);
           $pendingMatches = $model->getPendingMatchCountByMe($fb_id);
           
           $bankinfo = $model->getBankInfo($fb_id);
           
           $match_revenue = 0;
           $referral_revenue = 0;
           if(is_array($bankinfo)){
                $match_revenue = $bankinfo[0]["match_revenue"];
                $referral_revenue = $bankinfo[0]["referral_revenue"];
            }
           
           $returnValue = array();
           $returnValue['success'] = "OK";
           $returnValue['data'] = array("suggested_matches" => $suggestedMatches,
                            "successful_matches" =>$fixedMatches,
                            "pending_matches"=>$pendingMatches,
                            "match_revenue"=>$match_revenue,
                            "referral_revenue"=>$referral_revenue
               );
           
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
        }
	
        
        public function addTesters()
        {
            $model = new Api_model();
            $model->addTesters();
            var_dump("Ok");
        }
}
