<?php

/**
 * Service ACL Class
 * 
 *  
 */
class Service_ACL_Client extends Service_ACL_Abstract
{

	protected function registerRoles()
	{
	
		$permissions =  array(
		 'login' => array("*", ""),
		 'logout' => array("*", ""),
		 'unauthorized' => array("*", ""),
		 'dashboard' =>  array("*",""),
		 'index' => array("*",""), 
		 'profile' => array("*", ""),
		 'error' => array("*", ""),		 
		 'orders' => array("*", ""),
		'calendar' => array("*", ""),
		 );
		 
		$this->addRole('normal', $permissions);
		

		
	}
	
	public function fullAccessRoleName()
	{
		return 'primary';
	}
	
	public function setCurrentUserRole()
	{
		if(LMVC_Session::get('userRole') !='')
		{
			return LMVC_Session::get('userRole');
		}
		else
		{
			return false;
		}
	}
}

?>
