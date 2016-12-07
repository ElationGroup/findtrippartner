<?php

/**
 * Admin ACL Class
 * 
 *  
 */
class Service_ACL_Admin extends Service_ACL_Abstract
{

	protected function registerRoles()
	{
		
		$permissions =  array(
		 'login' => array("*", ""),
		 'logout' => array("*", ""),
		 'unauthorized' => array("*", ""),
		 'dashboard' => array("*",""),
		 'index' => array("*",""),
		 'profile' => array("*", ""),
		 'error' => array("*", ""),
		 'clients' => array("index,list", ""),
		 'orders' => array("*", ""),
		 'calendar' => array("*", ""),
		 'contents' => array("*","")
		 );
		 
		$this->addRole('editor', $permissions);
	}
	
	public function fullAccessRoleName()
	{
		return 'super_admin';
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
