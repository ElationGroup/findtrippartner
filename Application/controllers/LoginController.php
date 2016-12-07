<?php
class LoginController extends LMVC_Controller{
	
	public function init(){

	}
	public function indexAction(){
		
		$username = '';
		$password = '';
		$error_list = array();
		$error_message = "";
		$retUrl = "";
		
		if($this->isPost()){
			
			$invalid_login = true;
			
			$username = $this->getRequest()->getPostVar('username');
			$password = $this->getRequest()->getPostVar('password');
			
			$retUrl = $this->getRequest()->getPostVar('retUrl');
			
			if(trim($username)==""){
				array_push($error_list, "Please Enter userName");
			}
			if(trim($password)==""){
				array_push($error_list, "Please Enter Password");
			}
			
			if(empty($error_list)){
				$employee = new Model_Employees();
				$employee->fetchByProperty(userName, $userName);
				
				if(!$employee->isEmpty){
					if($employee->status == 'Active' && $employee->password == md5($password)){
						$invalid_login = false;
					}
				}
			}else{
				$error_message = "Incorrect Username or Password";
			}
		}
		
	}
	
}

?>