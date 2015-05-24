
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
		$this->db->insert('user',$data);
		return $this->db->insert_id();
	}
	
	//  update User Data
	public function updateUserData($fb_id, $data)
	{
		$this->db->where('fb_id', $fb_id);
		$result = $this->db->update('user', $data);
		
		return $result;
	}
	
	//  Get User Data using FB id.
	public function getUserData($fb_id)
	{
		$query = $this->db->query("SELECT * FROM user WHERE fb_id=?",array($fb_id));
		return $query->result();
	}
	
		
	
	/*   Store  DATA PROCESS                 */
	
	// Get All Store Data
	public function getStoreList()
	{

            $query = $this->db->query("SELECT id,name,contact_name,special_title,fb_id ,image_path,special_description, street, 
											city ,latitude, longitude, special_price, tax_rate, phonenumber, paypalemail, 
											publish_flag, pagelink, expire_time,offer_token_limit, TIMESTAMPDIFF(SECOND, published_time, NOW()) as different_time
									FROM store 
									WHERE publish_flag=1 AND DATEDIFF(CURDATE() ,DATE(published_time) ) >= 0 AND DATEDIFF( CURDATE(),DATE(published_time)) <=expire_day 
                                                                               AND TIME_TO_SEC(TIMEDIFF(CURTIME(),TIME(published_time))) > 0 AND TIME_TO_SEC(TIMEDIFF(CURTIME(), TIME(published_time))) < expire_time * 60 AND ((special_price = 0 AND offer_token_limit != 0) || special_price != 0)  order by name");
		/*$query = $this->db->query("SELECT id,name,contact_name,special_title,fb_id ,image_path,special_description, street, 
											city ,latitude, longitude, special_price, tax_rate, phonenumber, paypalemail, 
											publish_flag, pagelink, expire_time, TIMESTAMPDIFF(SECOND, published_time, NOW()) as different_time
									FROM store 
									WHERE publish_flag=1 AND TIMESTAMPDIFF(SECOND, published_time, NOW()) > 0 AND TIMESTAMPDIFF(SECOND, published_time, NOW()) < expire_time * 60 order by name");
		/*
		$query = $this->db->query("SELECT id,name,contact_name,special_title,fb_id ,image_path,special_description, street, 
											city ,latitude, longitude, special_price, tax_rate, phonenumber, paypalemail, 
											publish_flag, pagelink, expire_time, TIMESTAMPDIFF(SECOND, published_time, NOW()) as end_time
									FROM store 
									WHERE publish_flag=1 AND TIMESTAMPDIFF(SECOND, published_time, NOW()) > 0 AND TIMESTAMPDIFF(SECOND, published_time, NOW()) < expire_time * 60 order by name");
		*/
		return $query->result();
				 
	}
	
	// Add Store
	public function addStore($data)
	{
		$this->db->insert('store',$data);
		return $this->db->insert_id();
	}
	              
	//  update Store Data
	public function updateStoreData($fb_id, $data)
	{
		$this->db->where('fb_id', $fb_id);
		$result = $this->db->update('store', $data);
		
		return $result;
	}
			
	
	// Delete Store Data
	public function deleteStoreData($fb_id)
	{
		$this->db->where('fb_id', $fb_id);
		$this->db->delete('store');
		
	}
	
	//  Get Store Data using FB id.
	public function getStoreData($fb_id)
	{
		$query = $this->db->query("SELECT * FROM store WHERE fb_id=?",array($fb_id));
		return $query->result();
	}
	
        public function unpublishStore($fb_id)
        {
            $query = $this->db->query("UPDATE store SET token_limit = token_limit + offer_token_limit, publish_flag = 0 , offer_token_limit = 0  where fb_id = ? ", array(($fb_id)));
            
        }
	
        
        public function decreaseSpecialOfferLimit($fb_id)
        {
            $query = $this->db->query("UPDATE store SET offer_token_limit = offer_token_limit-1  where fb_id = ? ", array(($fb_id)));
           
        }
        
        
	  /*            Order Operation     **/
	  
	// Get All Store Data
	public function getOrderList($merchant_fb_id, $startDateStr, $endDateStr)
	{
		$query = $this->db->query("SELECT * FROM order_list WHERE merchant_fb_id=? AND DATE(order_date)>= ? AND DATE(order_date) <= ? order by order_date",array($merchant_fb_id,$startDateStr, $endDateStr));
				
		return $query->result();
				 
	}
	
	public function getUserOrderList($user_fb_id)
	{
		$query = $this->db->query("SELECT * FROM (SELECT * FROM order_list WHERE user_fb_id=? order by order_date ) AS t1 INNER JOIN (SELECT fb_id , name AS store_name, street, city FROM store ) AS t2  ON t1.merchant_fb_id = t2.fb_id ",array($user_fb_id));
			
		return $query->result();
	}
	
	public function getOrderNumber()
	{
		$query = $this->db->query("SELECT last_order_number FROM order_number ");
		$result = $query->result();
		
		$orderNumber = $result[0]->last_order_number;
		
		$query = $this->db->query("UPDATE order_number set last_order_number = ? ",array(intval($orderNumber) + 1));
		
		return $orderNumber;
	}
	
	public function addOrder($data)
	{
		$this->db->insert('order_list', $data);
		
		return $this->db->insert_id();
	}
	
	public function updateOrder($fb_id,$data)
	{
		$this->db->where('merchant_fb_id', $fb_id);
		$this->db->update('order_list', $data);
	}
		
	public function completeOrder($orderNumber)
	{
		$this->db->query("UPDATE order_list SET completed_flag=1 WHERE orderNumber = ?", array($orderNumber));
	
	}
	
	public function completeOrderUsingBarcode($fb_id, $barcode)
	{
		$this->db->query("UPDATE order_list SET completed_flag=1 WHERE merchant_fb_id = ? AND barcode = ?", array($fb_id, $barcode));
		$query = $this->db->query("SELECT *  FROM order_list WHERE merchant_fb_id = ? AND barcode = ?", array($fb_id, $barcode));
		$result = $query->result();
		
		return $result;
	}
	
	public function unCompleteOrder($orderNumber)
	{
		$this->db->query("UPDATE order_list SET completed_flag=0 WHERE orderNumber = ?", array($orderNumber));
	
	}
	
	////////////////////   Register Device(user and merchant ) , Getting Merchant Device List   //////////////////////
	
	public function  registerDevice($fb_id, $devicetoken, $user_flag)
	{
		$query = $this->db->query("SELECT * FROM device_list WHERE fb_id=? AND devicetoken=? AND user_flag=?", array($fb_id, $devicetoken, $user_flag));
		
		$result =  $query->result();
	
		if(is_array($result) == false || count($result) == 0)  /// if is not exist
		{
			$data = array("fb_id"=>$fb_id, "devicetoken"=>$devicetoken, "user_flag"=>$user_flag);
			
			$this->db->insert("device_list", $data);
		}
	}
	
	
	public function getDeviceList($fb_id, $user_flag)
	{
		if($user_flag == 0)
			$query = $this->db->query("SELECT devicetoken FROM device_list WHERE fb_id=? AND user_flag=?", array($fb_id,$user_flag));
		else
			$query = $this->db->query("SELECT devicetoken ,fb_id FROM ( SELECT t1.fb_id, latitude, longitude, devicetoken FROM  (SELECT fb_id, latitude, longitude FROM user WHERE push_flag = 1 ) as t1 INNER JOIN (SELECT fb_id , devicetoken FROM device_list WHERE user_flag=1) AS t2  ON t1.fb_id = t2.fb_id ) AS t3, 
													(SELECT fb_id AS merchant_fb_id, latitude, longitude FROM  store WHERE store.fb_id = ?) AS t4
											WHERE 111.1111 * DEGREES(ACOS(COS(RADIANS(t3.latitude))
																 * COS(RADIANS(t4.latitude))
																 * COS(RADIANS(t3.longitude - t4.longitude))
																 + SIN(RADIANS(t3.latitude))
																 * SIN(RADIANS(t4.latitude)))) < 1", array($fb_id));
		return $query->result();
	}
	
	public function  registerAndroidDevice($fb_id, $devicetoken, $user_flag)
	{
		$query = $this->db->query("SELECT * FROM android_device_list WHERE fb_id=? AND devicetoken=? AND user_flag=?", array($fb_id, $devicetoken, $user_flag));
		
		$result =  $query->result();
	
		if(is_array($result) == false || count($result) == 0)  /// if is not exist
		{
			$data = array("fb_id"=>$fb_id, "devicetoken"=>$devicetoken, "user_flag"=>$user_flag);
			
			$this->db->insert("android_device_list", $data);
		}
		
	}
	
	public function getAndroidDeviceList($fb_id, $user_flag)
	{
		$query = $this->db->query("SELECT devicetoken FROM android_device_list WHERE fb_id=? AND user_flag=?", array($fb_id, $user_flag));
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
        
        
}

?>