<?php
class IndexController extends LMVC_Controller{
	
	public function init(){
		
		
		
	}
	
	public function indexAction(){	

		//die('called');
		
		$layout = LMVC_Layout::getInstance();
		
		$formLayoutDir = $layout->getLayoutDir('Default');
		
		LMVC_Layout::getInstance()->setLayout('Default',$formLayoutDir, 'Home.html');
			
	}
	
	
	
	
}
?>