<?php
class Models_Vehicle extends LMVC_ActiveRecord{
	
	public $tableName = "vehicles";
	public $id= "";
	public $owner_id = "";
	public $name = "";
	public $color = "";
	public $model_year = "";
	public $seating_cap = "";
	public $vehicle_no = "";
	public $basic_fare = "";
	public $category = "";
	public $fuel_type = "";
	public $baggage_qty = "";
	public $is_music_system = "n";
	public $is_led = "n";
	public $satellite_navigation = "n";
	public $power_door_locks = "n";
	public $power_windows = "n";
	public $image1 = "";
	public $alt_images = "";
	public $oldImage1Name = "";
	public $oldAltImages = "";
	public $date_created = "";
	
	public $dbIgnoreFields= array('id','oldImage1Name','alt_images','oldAltImages');
	
	function init(){
		
		$this->addListener('beforeCreate', array($this,'doBeforeCreate'));
		$this->addListener('afterCreate', array($this,'doAfterCreate'));
		$this->addListener('beforeUpdate', array($this,'doBeforeUpdate'));
		$this->addListener('afterUpdate', array($this,'doAfterUpdate'));
	}
	
	protected function doBeforeCreate(){
		
		// start db transaction
		$this->start_transaction();

		if($this->validate()) {
			return true;
		}
		
		return false;
		
	}
	
	protected function doAfterCreate() {
		
		if($this->uploadImage()) {
			
			$this->saveImage($this->image1);
			
			$this->commit();
			
		} else {
			
			$this->addError('Errors in image uploading..');
			
			$this->rollback();
		}
		
		
	}
	
	protected function doBeforeUpdate(){
	
		// start db transaction
		$this->start_transaction();

		if($this->validate('update')) {
			return true;
		}
		
		return false;
		
	
	}

	protected function doAfterUpdate(){
	
		if($this->uploadImage()) {
			
			$this->saveImage($this->image1);
			
			$this->commit();
			
		} else {
			
			$this->addError('Errors in image uploading..');
			
			$this->rollback();
		}
	
	
	}
	
	
	protected function validate($action){
		
		if( trim($this->name) =='' ){
			$this->addError('Please enter vehicle name');
		}
		
		if( trim($this->color) =='' ){
			$this->addError('Please enter vehicle color');
		}
		
		if( trim($this->model_year) =='' ){
			$this->addError('Please enter vehicle registration model year');
		}
		
		if( trim($this->seating_cap) =='' ){
			$this->addError('Please enter vehicle seating capacity');
		}
		
		if( trim($this->vehicle_no) =='' ){
			$this->addError('Please enter vehicle number');
		}
		
		if( trim($this->basic_fare) =='' ){
			$this->addError('Please enter basic fare');
		}
		
		if( trim($this->category) =='' ){
			$this->addError('Please select vehicle category');
		}
		
		if( trim($this->fuel_type) =='' ){
			$this->addError('Please select vehicle fuel type');
		}
		
		if( trim($this->baggage_qty) =='' ){
			$this->addError('Please enter baggage qty');
		}
		
		
		if($action != "update") {
			
			if( $_FILES['image1']['name'] =='' ){
				$this->addError('Please select vehicle image');
			}
				
		}
		
		
		
		return !$this->hasErrors();
	}
	
	
	
	
	public function uploadImage(){
		
		/*
		 * validation for logo upload.
		 * if user wants to upload new logo then
		 * first we have to check that selected file is image or not by calling the getimagesize()
		 * if user does not select the file then it checks that logo is already uploaded or not
		 */
		
		if($_FILES['image1']['name'] !== ""){
			$checkImage = getimagesize($_FILES['image1']['tmp_name']);
		} else {
			if($this->image1 !== ""){
				return true;
			}
		}
		 
		$newImage = $this->owner_id . "_" . $this->id . "_". $_FILES['image1']['name'];
		$uploadedFileName = IMAGE_PATH . $newImage;
	
		if($checkImage !== false){
	
			/*
			 * when user wants to upload company logo then first we have to check that
			 * user has already upload a logo or not.
			 * if user has already uploaded then delete the old logo and proceed to
			 * upload new logo for the company.
			 */
			if(file_exists(IMAGE_PATH . $this->oldImage1Name)){
	
				@unlink(IMAGE_PATH . $this->oldImage1Name);
	
			}
	
			/*
			 * after uploading the logo we have to check that file is uploaded successfully or not
			 * if it is successfully uploaded then save the name of new logo in object
			 * otherwise register an error for uploading the file.
			 */
			if(move_uploaded_file($_FILES['image1']['tmp_name'], $uploadedFileName)){
	
				$this->image1 = $newImage;
				return true;
	
			}else{
	
				$this->addError("Error in uploading logo");
				return false;
	
			}
		}
	}
	
	
	private function saveImage($imageName) {
		
		$sql = "UPDATE $this->tableName SET image1='$imageName' WHERE id=$this->id";
		$this->query($sql);
	}
	
	public function searchVehicles($lat, $long, $seatingCap=0, $cat='', $isLed='', $isMusicSys='', $fareOrderBy='asc') {
		
		global $db;
		
		$sql = "SELECT a.*, b.*, b.id as vehicle_id, a.name AS owner_name, b.name AS vehicle_name FROM owners a 
				INNER JOIN vehicles b ON b.owner_id = a.id
				WHERE (a.`lat`<='{$lat}' AND a.`lat`>='{$lat}') AND (a.`long`<='{$long}' AND a.`long`>='{$long}')
				AND b.seating_cap >= '{$seatingCap}'";
		
		if($cat != "") {
			$sql .= " AND b.category = '{$cat}'";
		}
		
		if($isLed != "") {
			$sql .= " AND b.is_led = '{$isLed}'";
		}
		
		if($isMusicSys != "") {
			$sql .= " AND b.is_music_system = '{$isMusicSys}'";
		}
		
		
		$sql .= " ORDER BY b.basic_fare {$fareOrderBy}";
		
//  		echo $sql;
//  		die();
		
		$results = $db->fetchAll($sql);
		
		echo '<pre>';
		print_r($results);
		die();
		
		
		return $results;
		
		
		
		
		
	}
	
		
}
?>