<?php

/**
 * View Helper to display breadcrumb
 *
 * @author Penuel
 */
class Helpers_Views_BreadCrumb extends LMVC_ViewHelper {

    //put your code here    
    private $items = array();


    final public function init() {
        $this->helperName = 'bread_crumb';
    }
    
    final public function addItem($itemLabel,$url='')
    {
    	$this->items[$itemLabel] = $url;
    	
    	return $this;
    }

    final public function helperFunc($params) {
        
    	$breadCrumb ='';
    	$items = array();
    	foreach($this->items as $label=>$url)
    	{
    		if($url !='')    			
    			array_push($items, '<a href="'. $url .'">'. $label .'</a>');
    		else
    			array_push($items, $label );
    	}
    	
    	if(count($items)>0)
    	{
    		$html = implode(" > ", $items);
    		
    		$homeUrl = SITE_URL .'/'. LMVC_Request::getInstance()->getModuleName();
    		
    		$breadCrumb = '<ol class="breadcrumb">';
    		$breadCrumb .= '<li><a href="'.$homeUrl.'"><i class="fa fa-dashboard"></i> Home</a></li>';
    		foreach($items as $item)
    		{
    			$breadCrumb .= '<li>'. $item .'</li>';
    		}
    		
    		$breadCrumb .= '</ol>';
    	}
    	
    	
    	
    	
    	
    	
    	return $breadCrumb;

        
    }

}

?>
