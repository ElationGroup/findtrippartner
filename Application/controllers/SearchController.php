<?php
class SearchController extends LMVC_Controller{
	
	public function init(){
		
		
	}
	
	public function indexAction(){

		$this->setTitle("Search");
		
		$location = $this->getRequest()->getVar('loc','string','');
		$noOfPassanger = $this->getRequest()->getVar('noofpass','string','');
		
		
		//Filters
		
		$filters = array();
		
		$cat = $this->getRequest()->getVar('category','string','');
		$isLed = $this->getRequest()->getVar('isLed','string','');
		$isMusicSys = $this->getRequest()->getVar('isMusicSys','string','');
		$fareOrderBy = $this->getRequest()->getVar('fareOrderBy','string','asc');
		if($fareOrderBy != "desc" && $fareOrderBy != "asc") {$fareOrderBy = "asc"; }
		
		
		
		$filters['category'] = $cat;
		$filters['isLed'] = $isLed;
		$filters['isMusicSys'] = $isMusicSys;
		$filters['fareOrderBy'] = $fareOrderBy;
		
		
		$results = array();
		
		if($location != "") {
			
			$lat = "123456";
			$long = "123456";
			
			$vehicle = new Models_Vehicle();
			$results = $vehicle->searchVehicles($lat, $long, $noOfPassanger, $cat, $isLed, $isMusicSys,$fareOrderBy);
			
			if(empty($results)) {
				echo "No result found";
			}
			
			
			
		}
		
		$this->setViewVar('location', $location);
		$this->setViewVar('noOfPass', $noOfPassanger);
		$this->setViewVar('vehicleCat', unserialize(VEHICL_CATS));
		$this->setViewVar('filters', $filters);
		$this->setViewVar('searchResults', $results);
		$this->setViewVar('resultCount', count($results));
		
		
		
		

	}
	
	
	
	
}
?>