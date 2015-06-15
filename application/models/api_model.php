
<?php

/*
	Author : Glenn 
	Date : 2/24/2015

*/
class Api_model extends CI_Model{
    
    public function __construct(){
        parent::__construct();
		$this->load->database();
    }
	
	/*   User  DATA PROCESS                 */
	
	
	// Add Store
	public function addUser($data)
	{
		$this->db->insert('users',$data);
		return $this->db->insert_id();
	}
	
	//  update User Data
	public function updateUserData($fb_id, $data)
	{
		$this->db->where('fb_id', $fb_id);
		$result = $this->db->update('users', $data);
		
		return $result;
	}
	
	//  Get User Data using FB ID.
	public function getUserData($fb_id)
	{
		$query = $this->db->query("SELECT * FROM users WHERE fb_id=?",array($fb_id));
		return $query->result_array();
	}
	
        public function getFriendList($friend_id_list){
            
            $nameArray = $this->getNameArray();
            
            $returnValue = array();
            if(is_array($friend_id_list)){
               
                foreach($friend_id_list as $friend_fb_id){
                    
                    $temp = array();
                    $temp["id"] = $friend_fb_id;
                    $temp["name"] = $nameArray[$friend_fb_id];
                   
                     $returnValue[] = $temp;
                }
                              
            }
            
            return $returnValue;
        }
        
        public function  decreaseActiveFixes($fb_id){
            $query = $this->db->query("UPDATE users SET fixes=fixes-1 WHERE fb_id=?",array($fb_id));
	
        }

        public function resetFixes(){
            $query = $this->db->query("UPDATE users SET fixes=5");
        }
        
        public function addCoin($fb_id, $amount)
        {
            $query = $this->db->query("UPDATE users SET coins=coins  + ? WHERE fb_id=?",array($amount,$fb_id));
        }
        
        
        public function withdrawCoin($fb_id, $amount)
        {
            $query = $this->db->query("UPDATE users SET coins=coins - ? WHERE fb_id=?",array($amount,$fb_id));
        }
        
        
        //  Get Friend Profile  Data using FB ID.
	public function getProfileData($fb_id)
	{
		$query = $this->db->query("SELECT fb_id, name, birthday, state, city, street, tagline, workplace, schools, religion, interest, photo_path FROM users WHERE fb_id=?",array($fb_id));
		return $query->result();
	}
        
        
        public function addMatch($data)
        {
		$this->db->insert('matches',$data);
		return $this->db->insert_id();
           
        }
              
        // My Matches 
        
        public function getMyMatches($fb_id){
            
            $returnValue = array();
            
            $now = date("Y-m-d H:i:s");
            $query = $this->db->query("SELECT *, (7 - DATEDIFF(DATE(?),DATE(fix_date))) as expire_day"
                    . " FROM matches WHERE (user1=? OR user2=?) AND is_dislike=0 AND is_accept=0 AND DATEDIFF(DATE(?),DATE(fix_date)) <=7 ",array($now,$fb_id,$fb_id,$now));
            $suggestedMatches =  $query->result_array();
            
            $nameArray = $this->getNameArray();
            
            $tempArray = array();
            if(is_array($suggestedMatches)){
               
                
                foreach($suggestedMatches as $row){
                    $row["name1"] = $nameArray[$row["user1"]];
                    $row["name2"] = $nameArray[$row["user2"]];
                    $row["provider_name"] = $nameArray[$row["provider"]];
                    $tempArray[] = $row;
                }
                
                $returnValue["suggested_matches"] = $tempArray;
            }
            
            $query = $this->db->query("SELECT *"
                    . " FROM matches WHERE (user1=? OR user2=?) AND is_accept=1",array($fb_id,$fb_id));
            $acceptedMatches =  $query->result_array();
            
            $tempArray = array();
            if(is_array($acceptedMatches)){
                              
                foreach($acceptedMatches as $row){
                    $row["name1"] = $nameArray[$row["user1"]];
                    $row["name2"] = $nameArray[$row["user2"]];
                    $row["provider_name"] = $nameArray[$row["provider"]];
                    $tempArray[] = $row;
                }
                
                $returnValue["accepted_matches"] = $tempArray;
            }
            
            return $returnValue;
            
        }
        
        public function likeFriend($id, $fb_id){
            
            $query = $this->db->query("UPDATE matches  "
                    . " SET liked_user = ? where id = ? ", array($fb_id,$id));
            
            $query = $this->db->query("UPDATE users  "
                    . " SET coins = coins - 1 where fb_id = ? ", array($fb_id));
           
        }
        
        public function acceptMatch($id){
            
            $query = $this->db->query("UPDATE matches  "
                    . " SET is_accept = 1 where id = ? ", array($id));
           
        }
        
        public function dislikeMatch($id){
            
            $query = $this->db->query("UPDATE matches  "
                    . " SET is_dislike = 1 where id = ? ", array($id));
            $query = $this->db->query("UPDATE users  "
                    . " SET coins = coins + 1 where fb_id = ? ", array($fb_id));
           
        }
        
        // Get Suggested Friend List 
        
        public function getUserFriendList($fb_id)
        {
            $query = $this->db->query("SELECT fb_friend_list FROM users WHERE fb_id=?",array($fb_id));
            return $query->result();
        }
        
	public function getPersonForMatch($fb_id){
            $now = date("Y-m-d H:i:s");
            
            $sql = sprintf("SELECT fb_id,name, interest, sex, is_man, single, (YEAR('%s') - YEAR(STR_TO_DATE(birthday,'%%m/%%d/%%Y'))) as age, min_age, max_age, latitude, longitude"
                    . ",distance_range, height,min_height, max_height, religion, religion_priority, t1.match_tags, 0 as match_score"
                    . " FROM users ,(SELECT count(*) as match_tags FROM matches WHERE user1=%s OR user2=%s) as t1 "
                    . "WHERE fb_id=%s",$now,$fb_id,$fb_id, $fb_id);
            $query = $this->db->query($sql);
                       
            $result =  $query->result(); 
            if(is_array($result) && count($result)!= 0 ){
                return $result[0];
            }
                
            return NULL;
                    
        }
        
        public function getSuggestedMatchByMe($fb_id){
            
            $now = date("Y-m-d H:i:s");
            
            $query = $this->db->query("SELECT *, (7 - DATEDIFF(DATE(?),DATE(fix_date))) as expire_day"
                    . " FROM matches WHERE provider=? AND is_dislike=0 AND is_accept=0 AND DATEDIFF(DATE(?),DATE(fix_date)) <=7 ",array($now,$fb_id,$now));
            $result =  $query->result_array();
            
            $returnValue = array();
            if(is_array($result)){
                $nameArray = $this->getNameArray();
                
                foreach($result as $row){
                    $row["name1"] = $nameArray[$row["user1"]];
                    $row["name2"] = $nameArray[$row["user2"]];
                    $row["provider_name"] = $nameArray[$row["provider"]];
                    $returnValue[] = $row;
                }
            }
            return $returnValue;
        }
        
        public function getSuggestedMatchCountByMe($fb_id){
            $query = $this->db->query("SELECT count(*) as count"
                    . " FROM matches WHERE provider=?",array($fb_id));
            $result =  $query->result_array();
                        
            if(is_array($result)){
                 return $result[0]["count"];                            
            }
            return 0;
        }
        
        public function getFixedMatchByMe($fb_id){
            $query = $this->db->query("SELECT *"
                    . " FROM matches WHERE provider=? AND is_accept=1",array($fb_id));
            $result =  $query->result_array();
                        
            if(is_array($result)){
                $nameArray = $this->getNameArray();
                
                foreach($result as $row){
                    $row["name1"] = $nameArray[$row["user1"]];
                    $row["name2"] = $nameArray[$row["user2"]];
                    $row["provider_name"] = $nameArray[$row["provider_name"]];
                }
            }
            return $result;
        }
        
        public function getFixedMatchCountByMe($fb_id){
            $query = $this->db->query("SELECT count(*) as count"
                    . " FROM matches WHERE provider=? AND is_accept=1",array($fb_id));
            $result =  $query->result_array();
                        
            if(is_array($result)){
                 return $result[0]["count"];                            
            }
            return 0;
        }
        
        public function getPendingMatchCountByMe($fb_id){
            
            $now = date("Y-m-d H:i:s");
            $query = $this->db->query("SELECT count(*) as count"
                    . " FROM matches WHERE provider=? AND is_dislike=0 AND is_accept=0 AND DATEDIFF(DATE(?),DATE(fix_date)) <=7",array($fb_id,$now));
            $result =  $query->result_array();
                        
            if(is_array($result)){
                 return $result[0]["count"];                            
            }
            return 0;
        }
        
        public function getNameArray(){
            $query = $this->db->query("SELECT fb_id, name FROM users ");
            $result = $query->result_array();
            
            $returnArray = array();
            if(is_array($result)){
                
                foreach($result as $row){
                    $returnArray[$row["fb_id"]] = $row["name"];
                }
            
            }
            
            return $returnArray;
        }
        
        
        public function getBankInfo($fb_id){
            $query = $this->db->query("SELECT match_revenue, referral_revenue"
                    . " FROM users WHERE fb_id=?",array($fb_id));
            $result =  $query->result_array();
                        
            
            return $result;
        }
        
	////////////////////   Register Device(user and merchant ) , Getting Merchant Device List   //////////////////////
	
	public function  registerDevice($fb_id, $devicetoken)
	{
		$query = $this->db->query("SELECT * FROM device WHERE fb_id=? AND token=?", array($fb_id, $devicetoken));
		
		$result =  $query->result();
	
		if(is_array($result) == false || count($result) == 0)  /// if is not exist
		{
			$data = array("fb_id"=>$fb_id, "token"=>$devicetoken, "flag"=>1);
			
			$this->db->insert("device", $data);
		}else{
                    $this->db->query("UPDATE device SET flag = 1 WHERE fb_id=? AND token=?", array($fb_id, $devicetoken));
                }
	}
	
        public function  gotoOfflineDevice($fb_id, $devicetoken)
	{
		$this->db->query("UPDATE device SET flag = 0 WHERE fb_id=? AND token=?", array($fb_id, $devicetoken));
			
	}
        
	
	public function getDeviceList($fb_id)
	{
		
		$query = $this->db->query("SELECT token FROM device WHERE fb_id=? AND flag=1", array($fb_id));
		
                /*
		$query = $this->db->query("SELECT devicetoken ,fb_id FROM ( SELECT t1.fb_id, latitude, longitude, devicetoken FROM  (SELECT fb_id, latitude, longitude FROM user WHERE push_flag = 1 ) as t1 INNER JOIN (SELECT fb_id , devicetoken FROM device_list WHERE user_flag=1) AS t2  ON t1.fb_id = t2.fb_id ) AS t3, 
													(SELECT fb_id AS merchant_fb_id, latitude, longitude FROM  store WHERE store.fb_id = ?) AS t4
											WHERE 111.1111 * DEGREES(ACOS(COS(RADIANS(t3.latitude))
																 * COS(RADIANS(t4.latitude))
																 * COS(RADIANS(t3.longitude - t4.longitude))
																 + SIN(RADIANS(t3.latitude))
																 * SIN(RADIANS(t4.latitude)))) < 1", array($fb_id));
		*/
                return $query->result();
	}
		
				
/*	
	SELECT a.city AS from_city, b.city AS to_city, 
   111.1111 *
    DEGREES(ACOS(COS(RADIANS(a.Latitude))
         * COS(RADIANS(b.Latitude))
         * COS(RADIANS(a.Longitude - b.Longitude))
         + SIN(RADIANS(a.Latitude))
         * SIN(RADIANS(b.Latitude)))) AS distance_in_km
  FROM city AS a
  JOIN city AS b ON a.id <> b.id
 WHERE a.city = 3 AND b.city = 7
 */
 
	
	public function getMYSQLCurrentTime()
	{
		$query = $this->db->query("SELECT NOW() as now");
		$result = $query->result();
		
		return $result[0]->now;
	}
  /*      
        public function getExpireTimeDiff($fb_id)
	{
		$query = $this->db->query("SELECT  TIME_TO_SEC(TIMEDIFF(TIME(published_time), CURTIME())) as diff FROM store WHERE fb_id = ".$fb_id);
		$result = $query->result();
		
		return $result[0]->diff;
	}
        
        public function getExpireDayDiff($fb_id)
	{
		$query = $this->db->query("SELECT (DATEDIFF(DATE(published_time), CURDATE())) as diff FROM store WHERE fb_id = ".$fb_id);
		$result = $query->result();
		
		return $result[0]->diff;
	}
    */    
     
//        public function addTesters()
//        {
//            for($i = 123456750 ;$i < 123456773; $i++)
//             $query = $this->db->query("INSERT INTO `fixed_db`.`users` (`fb_id`, `name`, `email`, `sex`, `birthday`, `single`, `workplace`, `schools`, `interest`, `state`, `city`, `street`, `zipcode`, `latitude`, `longitude`, `is_man`, `is_interested_man`, `is_single`, `religion_priority`, `match_zipcode`, `diance_range`, `min_age`, `max_age`, `min_height`, `max_height`, `tagline`, `height`, `religion`, `photo_path`, `fix_reminder`, `cash_notification`, `match_notification`, `chat_notification`, `alert_setting`, `update_setting`, `fb_friend_list`, `coins`, `match_revenue`, `referral_revenue`, `fixes`)"
//                     . " VALUES ('?', 'Bryan', 'bryan?@gmail.com', '0', '1990-5-5', '1', 'JpMorgan Chase Bank', 'California University, University of Marland', 'Dog Lover , Momma''s Boy', 'NY', 'New York', 'Potomac, MD', '1234', '12.56', '45.23', '0', '0', '1', '0', '1234', '100', '18', '30', '45', '65', 'Just moved to new york, busy being myself', '62', '2', '[\"?.jpg\"]', '1', '1', '1', '1', '1', '1', '[\"123456752\",\"123456753\"]', '0', '0', '0', '5')", array($i,$i,$i));
//        }
        
}

?>