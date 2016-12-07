<?php
class Models_AdminUser extends LMVC_ActiveRecord{
	public $tableName = "admin_users";
	public $id= "";
	public $username = "";
	public $password = "";
	public $firstName = "";
	public $lastName = "";
	public $email = "";
	public $active = "n";
	public $role = "editor";
	
	public $dbIgnoreFields= array('id');
		
	function init(){
		$this->addListener('beforeCreate', array($this,'doBeforeCreate'));
		$this->addListener('beforeUpdate', array($this,'doBeforeUpdate'));
		$this->addListener('beforeDelete', array($this,'doBeforeDelete'));
	}
	
	protected function doBeforeCreate(){
	
		if($this->validate())
		{
			$this->password = md5($this->password);
			return  true;
		}else{
			return false;
		}
	
	}
	
	protected function doBeforeUpdate(){
		
		if($this->validate())
		{
			if($this->password == ''){
				array_push($this->dbIgnoreFields, 'password');
			}else{
				$this->password = md5($this->password);
			}
			
			return  true;
		}else{
			return false;
		}
			
	}
	
	protected function doBeforeDelete(){
		if($this->role == 'super_admin'){
			$this->addError('you can not delete super admin');
			return false;
		}else{
			return true;
		}
	}
	
	protected function validate(){
		
		if( trim($this->username) == '' ){
			$this->addError('Please enter username');
		}elseif( !$this->isUniqueUsername($this->username)){
			$this->addError('Username already exists. Please enter a unique username');
		}		
		
		if($this->_getCurrentAction() != "update" || trim($this->password) != ''){
		
			if( trim($this->password) == '' ){
				$this->addError('Please enter password');
			}elseif( strlen(trim($this->password)) <6 ){
				$this->addError ( 'Password must be minimum six character long' );
			}
			
		}
		
		if( trim($this->firstName) == '' ){
			$this->addError('Please enter first name');
		}
		
		if( trim($this->lastName) == '' ){
			$this->addError('Please enter last name');
		}
		
		if( trim($this->email) == '' ){
			$this->addError('Please enter email');
		}elseif ( !filter_var($this->email, FILTER_VALIDATE_EMAIL) ){
			$this->addError ( 'Invalid email.' );
		}elseif ( !$this->isUniqueEmail($this->email)){
			$this->addError ( 'Email already exists. Please enter a unique email.' );
		}
		
		return !$this->hasErrors();
	}
	
	public function isUniqueUsername($username){
		global $db;
	
		$sql = "SELECT count(username) FROM ".$this->tableName . " WHERE username = '$username' ";
		if($this->id>0)
		{
			$sql .= " AND id <> $this->id";
		}
		
		$count = $db->fetchOne($sql);
	
		if($count==0){
			return true;
		}else{
			return false;
		}
	}


	public function isUniqueEmail($email){
		global $db;
	
		$sql = "SELECT count(email) FROM ".$this->tableName . " WHERE email = '$email' ";
		if($this->id>0)
		{
			$sql .= " AND id <> $this->id";
		}
	
		$count = $db->fetchOne($sql);
	
		if($count==0){
			return true;
		}else{
			return false;
		}
	}

	public function getAssignedClients( $adminUserId=0, $ids=false ){
		global $db;
	
		$sql = "SELECT clients.id,clients.clientName FROM clients";
		
		if(! empty($adminUserId) ){
			$sql .= " INNER JOIN admin_user_clients ON admin_user_clients.adminUserId = ". (int) $adminUserId . " AND admin_user_clients.clientId = clients.id";
		}
		
		if( $ids ){
			
			$results = $db->fetchCol($sql);			
			
		}else{
			
			$results = $db->fetchAll($sql);			
			
		}	
	
		return $results;
	
	}
	
	public function isClientAssigned( $cliendId ){
		global $db;
	
		$sql = "SELECT count(clientId) FROM admin_user_clients WHERE adminUserId = $this->id AND clientId = ". (int) $cliendId;
		
		$count = $db->fetchOne($sql);
	
		if( $count > 0 ){
			return true;
		}else{
			return false;
		}
	
	}
	
	public function saveAssignedClients($clientIds){

		global $db;
		
		$sql = "DELETE FROM admin_user_clients WHERE adminUserId = $this->id";
		$db->query($sql);
		
		$fields = array('adminUserId','clientId');
		$data = array();
		
		foreach($clientIds as $cid){		
			$data[] = array($this->id,$cid);		
		}
		
		$this->insertMultiple('admin_user_clients', $fields, $data);
	}
	
}
?>