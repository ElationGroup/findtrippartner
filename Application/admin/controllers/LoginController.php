<?php
class Admin_LoginController extends LMVC_Controller{
	
	public function init(){
		
		$layout = LMVC_Front::getLayoutObject();
		LMVC_Layout::getInstance()->setLayout('admin', null, 'Login.html');
	}
	
	public function indexAction(){		
	
		$username = '';
		$password 	= '';
		$error_list = array();
		$error_message = "";
		$retUrl = "";
		
		
		if($this->isPost()){
			
			$invalid_login = true;
			
			$username = $this->getRequest()->getPostVar('username');
			
			$password = $this->getRequest()->getPostVar('password');
			
			$retUrl = $this->getRequest ()->getPostVar ( 'retURL' );
			
			if (trim ( $username ) == "")
				$this->addError('Please enter username' );
				
			if (trim ( $password ) == "")
				$this->addError( 'Please enter password' );
				
			if (!$this->hasErrors()) {
				
				$adminUser = new Models_AdminUser ();
				$adminUser->fetchByProperty ( 'username', $username );
									
				if(!$adminUser->isEmpty){					
					if ( ($adminUser->password == md5( $password ) ) && ($adminUser->active == 'y') ) {
						$invalid_login = false;
					}
				}
				if (! $invalid_login){
					
											
					LMVC_Session::set ( 'adminId', $adminUser->id );
					LMVC_Session::set ( 'adminUsername', $adminUser->username );
					LMVC_Session::set ( 'adminFullName', implode(' ', array($adminUser->firstName, $adminUser->lastName)));
					LMVC_Session::set ( 'userRole', $adminUser->role );
					
					if ($retUrl == "")			
						header('Location: /admin/dashboard');
					else				
						header ( "Location: " . $retUrl );
				}
				
				else
				
				{
						
					$this->addError( "Incorrect username or password");
				}
			}
			
		}else{
			$retUrl = $this->getRequest ()->getVar ( 'retURL' );
		}
		$this->setViewVar ( 'retURL', $retUrl );
		
		$this->setViewVar ( 'username', $username );
		
		$this->setViewVar ( 'password', $password );				
		
		$this->setViewVar ( 'error_list', $this->getErrors() );
		
	}
	
	
	
	
}
?>