<?php

/**
 *  Frequently used utility functions needed on various places throughout the app
 */



/**
 * 
 * Check if the current logged in admin user is authorized to manage this client.
 * 
 *  if is not authorized he is redirected client list page with unauthorized message.
 *  
 * 
 * @param unknown $clientId
 * @return boolean
 */

function validate_client_access($clientId) {
	$id = LMVC_Session::get ( 'adminId' );
	
	$adminUser = new Models_AdminUser ( $id );
	
	if ($adminUser->role == 'super_admin') {
		
		return true;
	} else {
		
		$cid = ( int ) $clientId;		
		$assigned = $adminUser->isClientAssigned ( $cid );		
		if (! $assigned) {
			
			header ( 'Location: /admin/clients/index/?message=unauthenticated_client' );
			die ();
		}
	}
}

function  validate_order_access( $orderId ){
	$clientId = LMVC_Session::get('clientId');
		
	if( $orderId != '' ){
		$order = new Models_ClientOrder( $orderId );
			
		if( $clientId != $order->clientId ){
				
			header('Location: /client/orders/index/?message=unauthenticated_client_order');
			die();
				
		}
	
	}
}
