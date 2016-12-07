<?php
class Admin_DashboardController extends LMVC_Controller{
	
	public function init(){
		$this->setTitle("Dashboard");
		$this->registerStylesheet('/js/vendor/datatables/dataTables.bootstrap.css');
		$this->registerFooterScript('/js/vendor/datatables/jquery.dataTables.min.js');
		$this->registerFooterScript('/js/vendor/datatables/dataTables.bootstrap.min.js');
		
	}
	
	public function indexAction(){		
	
	}
	
	
}
?>