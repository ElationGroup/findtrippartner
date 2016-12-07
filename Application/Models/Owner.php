<?php
class Models_Owner extends LMVC_ActiveRecord{
	
	public $tableName = "owners";
	public $id= "";
	public $name = "";
	public $mobile_no = "";
	public $alt_mobile_no = "";
	public $landline_no = "";
	public $email = "";
	public $website = "";
	public $licence_no = "";
	public $address_line1 = "";
	public $address_line2 = "";
	public $city = "";
	public $state = "";
	public $pincode = "";
	public $agency_name = "";
	public $holder = "";
	public $date_created = "";
	
	public $dbIgnoreFields= array('id');
	
	function init(){
		$this->addListener('beforeCreate', array($this,'doBeforeCreate'));
		$this->addListener('beforeUpdate', array($this,'doBeforeUpdate'));
	}
	
	protected function doBeforeCreate(){	
		
		return $this->validate();		
		
	}
	
	protected function doBeforeUpdate(){
	
		return $this->validate();
	
	}	
	
	protected function validate(){
		if( trim($this->name) =='' ){
			$this->addError('Please enter owner name');
		}
		
		if( trim($this->mobile_no) =='' ){
			$this->addError('Please enter mobile no');
		}
		
		if( trim($this->state) =='' ){
			$this->addError('Please select state');
		}
		
		if( trim($this->city) =='' ){
			$this->addError('Please select city');
		}
		
		if( trim($this->holder) =='' ){
			$this->addError('Please select holder(owner/agency)');
		}
		
		return !$this->hasErrors();
	}
	
		
}
?>