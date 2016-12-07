<?php

class Admin_LogoutController extends LMVC_Controller {
	public function indexAction() 

	{
		LMVC_Session::destroy ();
		
		//$this->registerStylesheet ( '/front/css/custom.css' );
		
		header ( "Location: /admin/login" );
		
	}
}

?>