<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct()
    {
        parent::__construct();
        // Load models
	    $this->load->model('Api_model', 'api');
    }

	public function index()
	{		
		var_dump("Loading>>");
		$data = array("correctflag"=>false);
		$this->load->view('index',$data);
		
		//$this->load->view->$correctflag = false;
	}

	public function login()
	{
		$username = isset($_REQUEST['username']) ? $_REQUEST['username'] : "";
		$password = isset($_REQUEST['password']) ? $_REQUEST['password'] : "";
		
		
		$api_model = new Api_model();
		$result  = $api_model->getUserData($username, $password);
		
		if(count($result) == 0)
		{
			$data = array("correctflag"=>true);
			$this->load->view('index',$data);
					
		}else{
		
			if(count($result)>0) //success
			{
				$picturemanage = new PictureManage();
				$picturemanage ->showPictures(-1000);
			}
		}	
	}
	
	public function signup()
	{
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */