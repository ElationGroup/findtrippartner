<?php

/**
 * View Helper to display Menu if ACL is passed, it will check
 * if user has privilege to access that section and display the menu accordingly.
 *
 * @author Penuel
 */
class Helpers_Views_Menu extends LMVC_ViewHelper {

    //put your code here    
    private $menuItems = array();


    final public function init() {
        $this->helperName = 'menu';
        
        
        //admin module menus
        $this->menuItems['admin'] = array(
        		array(
        				'label' => 'Owners',
        				'controller' => 'owners',
        				'action' => 'index',
        				'class' => '',
        				'icon' => '<i class="fa fa-users"></i>'    				
        		),
        		array(
        				'label' => 'Vehicles',
        				'controller' => 'vehicles',
        				'action' => 'index',
        				'class' => '',
        				'icon' => '<i class="fa fa-users"></i>'
        		)
        );
        
        //client module menus
        $this->menuItems['client'] = array(
        		array(
        				'label' => 'Users',
        				'controller' => 'users',
        				'action' => 'index',
        				'class' => '',
        				'icon' => '<i class="fa fa-user"></i>'
        		),
        		array(
        				'label' => 'Orders',
        				'controller' => 'orders',
        				'action' => 'index',
        				'class' => '',
        				'icon' => '<i class="fa fa-bell-o"></i>'
        		),
        		
        		array(
        				'label' => 'Calendar',
        				'controller' => 'calendar',
        				'action' => 'index',
        				'class' => '',
        				'icon' => '<i class="fa fa-calendar"></i>'
        		)
        
        );
    }
    
   /**
    * possible parameters
    * 
    * module	required parameter. pass the module name whose menu you want to display
    * acl		option parameter. pass the ACL name that you want to check if displaying a menu item.
    * 
    */

    final public function helperFunc($params) {
        
    	
    	extract($params);
    	
    	if(isset($acl))
    	{
    		$aclObj = Service_ACL_Broker::getInstance($acl);
    	}
    	
    	$request = LMVC_Request::getInstance();
    	
    	$currentModuleName		= $request->getModuleName();
    	$currentControllerName	= $request->getControllerName();
    	$currentActionName		= $request->getActionName();
    	$html = '';
    	
    	
    		
    		if(!isset($this->menuItems[$module]))
    		{
    			return  '';
    		}
    		
    		$menuItems = $this->menuItems[$module];	
    		
    		foreach($menuItems as $item)
    		{
    			$addMenu = false;
    			if(isset($aclObj) && $aclObj->currentUserHasAccess($item['controller'], $item['action']))
    			{
    				$addMenu = true;
    			}
    			else {
    				if(!isset($aclObj)){	//if acl object has not been passed, then we don't apply acl
    					$addMenu = true;
    				}
    			}
    			
    			if($addMenu == false) continue;	//hide the item..
    			
    			$class = $item['class'];
    			$url = $module .'/'. $item['controller'] .'/'. $item['action'] ."/";
    			$url = rtrim($url,'/');
    			
    			if($currentControllerName == $item['controller'])
    			{
    				$class .= ' active';
    			}
    			$html .= '<li class="'. $class .'"><a href="/'. $url  .'">'. $item['icon'] .' <span>'. $item['label'] .'</span></a></li>';
    		}
    	
    	
    	return $html;        
    }

}

?>
