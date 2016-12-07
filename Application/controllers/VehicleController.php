<?php
class VehicleController extends LMVC_Controller{
	
	public function init(){
		
		
	}
	
	public function indexAction(){	
		
	
	}
	
	public function detailAction() {
		
		$this->setTitle("Vehicle Detail");
		
		$vehicleId = $this->getRequest()->getParam('id','numeric',0);
		
		$vehicle = new Models_Vehicle($vehicleId);
		
		
		if($vehicle->isEmpty) {
			
			echo "Invalid vehicle id";
			
		} else {
			
			$owner = new Models_Owner($vehicle->owner_id);
			
			
			$this->setViewVar('vehicle', $vehicle);
			$this->setViewVar('owner', $owner);	
				
		}
		
		
	}	
	
	
	
}
?>