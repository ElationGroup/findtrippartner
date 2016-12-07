<?php
class Admin_VehiclesController extends LMVC_Controller{
	
	public function init(){
		$this->setTitle("Vehicles");
	}
	
	public function indexAction(){		
		
		$this->registerStylesheet('/js/vendor/datatables/dataTables.bootstrap.css');
		$this->registerFooterScript('/js/vendor/datatables/jquery.dataTables.min.js');
		$this->registerFooterScript('/js/vendor/datatables/dataTables.bootstrap.min.js');
		
		
		
		$ownerId = $this->getRequest()->getParam('owner','numeric',0);
		
		$this->setViewVar('owner', $ownerId);
		
		
		
		$success_msg = "";
		$success_msg = $this->getRequest()->getVar('success');
		
		if ($success_msg == "vehicle_added")
			$this->setViewVar('success_msg', 'Vehicle added successfully');
		elseif ($success_msg == "vehicle_updated")
			$this->setViewVar('success_msg', 'Vehicle detail updated successfully');
		
	}
	
	
	public function listAction() {
		
		global $db;
		
		$this->setNoRenderer(true);
		
		$ownerId = $this->getRequest()->getParam('owner','numeric',0);
		
		$dt = new LMVC_DataTables();
		$dt->setDBAdapter($db);
		
		$joinStatment = " INNER JOIN owners ON owners.id = vehicles.owner_id ";
		
		$dt->setTable('vehicles');
		$dt->setJoins($joinStatment);
		$dt->setIdColumn('vehicles.id');
		$dt->addColumn(array('vehicles.id','owners.name','vehicles.name as vehicleName'));
		
		$defaultFilters = array();
		if($ownerId != '' && $ownerId != 0)
		{
			$defaultFilters['owner_id']=$ownerId;
		}
		
		$dt->setDefaultFilters($defaultFilters);
		
		
		$dt->addColumnAlias("vehicles.id","id");
		$dt->addColumnAlias("owners.name","name");
			$dt->addColumnAlias("vehicles.name as vehicleName","vehicleName");
		header('Content-type:application/json');
		$dt->getData();
		
		
	}
	
	public function addAction() {
		
		$this->getViewHelper('bread_crumb')->addItem('Add Vehicle','');
		
		$ownerId = $this->getRequest()->getParam('ownerId','numeric',0);
		
		$owner = new Models_Owner($ownerId);
		
		if(!$owner->isEmpty) {
			
			
			
			if($this->isPost()){
				
				$vehicle = new Models_Vehicle();
			
				$vehicle->getPostData();
			
				$currentTime = date('Y-m-d H:i:s');
			
				$vehicle->owner_id = $owner->id;
				$vehicle->date_created = $currentTime;
				
				$isLed = $this->getRequest()->getPostVar('is_led','string','n');
				$isMusicSystem = $this->getRequest()->getPostVar('is_music_system','string','n');
				$satelliteNavigation = $this->getRequest()->getPostVar('satellite_navigation','string','n');
				$powerDoorLocks = $this->getRequest()->getPostVar('power_door_locks','string','n');
				$powerWindows = $this->getRequest()->getPostVar('power_windows','string','n');
				
				$vehicle->is_led = $isLed;
				$vehicle->is_music_system = $isMusicSystem;
				$vehicle->satellite_navigation = $satelliteNavigation;
				$vehicle->power_door_locks = $powerDoorLocks;
				$vehicle->power_windows = $powerWindows;
				
				$id = $vehicle->create();
			
				if($id > 0)
				{
					header('Location: /admin/vehicles/index/?success=vehicle_added');
					die();
				}
				else
				{
					$this->setViewVar('error_list', $vehicle->getErrors());
					$this->setViewVar('vehicle', $vehicle);
				}
				
			}
			
			$this->setViewVar('vehicleCat', unserialize(VEHICL_CATS));
			$this->setViewVar('vehicleFuelType', unserialize(VEHICL_FUEL_TYPE));
			
			
			
		} else {
			
			echo "Invalid owner id";
			die();
			
			
		}
		
		
		
		
		
		
		
	}
	
	
	public function editAction(){
	
		$this->getViewHelper('bread_crumb')->addItem('Edit Vehicle','');
	
		$id = $this->getRequest()->getParam('id','numeric',0);
	
		$vehicle = new Models_Vehicle($id);
		
		if($this->isPost())
		{
			$vehicle->getPostData();
	
			$result = $vehicle->update();
			
			if($result)
			{
				header("Location: /admin/vehicles/index/?success=vehicle_updated");
				die();
			}
			else
			{
				$this->setViewVar("error_list", $vehicle->getErrors());
			}
		}
	
		$this->setViewVar('vehicleCat', unserialize(VEHICL_CATS));
		$this->setViewVar('vehicleFuelType', unserialize(VEHICL_FUEL_TYPE));
		$this->setViewVar('vehicle', $vehicle);
	
	}
	
	
	public function deleteAction(){
	
		$this->setNoRenderer(true);
	
		if($this->isPost()){
	
			$response = new LMVC_AjaxResponse('json');
	
			$id = $this->getRequest()->getPostVar('id');
	
			$vehicle = new Models_Vehicle($id);
	
			if(!$owner->isEmpty){
	
				$vehicle->delete();
				$response->setMessage("Vehicle deleted successfully");
	
			}else{
	
				$response->addError("There was some error deleting the vehicle" . $id);
			}
	
			$response->output();
		}
	
	}
	
	
	
	
	
}
?>