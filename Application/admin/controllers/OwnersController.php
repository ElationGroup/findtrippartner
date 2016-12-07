<?php
class Admin_OwnersController extends LMVC_Controller{
	
	public function init(){
		$this->setTitle("Owners");
	}
	
	public function indexAction(){		
		
		$this->registerStylesheet('/js/vendor/datatables/dataTables.bootstrap.css');
		$this->registerFooterScript('/js/vendor/datatables/jquery.dataTables.min.js');
		$this->registerFooterScript('/js/vendor/datatables/dataTables.bootstrap.min.js');
		
		$success_msg="";
		$success_msg = $this->getRequest()->getVar('success');
		if ($success_msg == "owner_added")
			$this->setViewVar('success_msg', 'Owner added successfully');
		elseif ($success_msg == "owner_updated")
		$this->setViewVar('success_msg', 'Owner detail updated successfully');
		
	}
	
	
	public function listAction() {
		
		global $db;
		
		$this->setNoRenderer(true);
		
		$dt = new LMVC_DataTables();
		$dt->setDBAdapter($db);
		$dt->setTable('owners');
		$dt->setIdColumn('id');
		$dt->addColumn(array('id','name','mobile_no','city','email','holder'));
		header('Content-type:application/json');
		$dt->getData();
		
		
	}
	
	public function addAction() {
		
		$this->getViewHelper('bread_crumb')->addItem('Add Owner','');
		
		if($this->isPost()){
				
			$owner = new Models_Owner();
				
			$owner->getPostData();
				
			$currentTime = date('Y-m-d H:i:s');
				
			$owner->date_created = $currentTime;
			
			$id = $owner->create();
				
			if($id > 0)
			{
				header('Location: /admin/owners/index/?success=owner_added');
				die();
			}
			else
			{
				$this->setViewVar('error_list', $owner->getErrors());
				$this->setViewVar('owner', $owner);
			}
		}
		
		
	}
	
	
	public function editAction(){
	
		$this->getViewHelper('bread_crumb')->addItem('Edit Owner','');
	
		$id = $this->getRequest()->getParam('id','numeric',0);
	
		$owner = new Models_Owner($id);
		
		if($this->isPost())
		{
			$owner->getPostData();
	
			$result = $owner->update();
			
			if($result)
			{
				header("Location: /admin/owners/index/?success=owner_updated");
				die();
			}
			else
			{
				$this->setViewVar("error_list", $owner->getErrors());
			}
		}
	
		$this->setViewVar('owner', $owner);
	
	}
	
	
	public function deleteAction(){
	
		$this->setNoRenderer(true);
	
		if($this->isPost()){
	
			$response = new LMVC_AjaxResponse('json');
	
			$id = $this->getRequest()->getPostVar('id');
	
			$owner = new Models_Owner($id);
	
			if(!$owner->isEmpty){
	
				$owner->delete();
				$response->setMessage("Owner deleted successfully");
	
			}else{
	
				$response->addError("There was some error deleting the owner" . $id);
			}
	
			$response->output();
		}
	
	}
	
	
	
	
}
?>