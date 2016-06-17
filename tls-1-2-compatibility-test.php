<?php
/*
Plugin Name: TLS 1.2 Compatibility Test
Plugin URI: http://www.paidmembershipspro.com
Description: Verify TLS 1.2 support for included API endpoints and diagnose a solution to enable compatibility.
Version: 1.0
Author: Stranger Studios
Author URI: http://www.strangerstudios.com
Text Domain: tls12
*/
/*
	Copyright 2016	Stranger Studios	(email : jason@strangerstudios.com)
	GPLv2 Full license details in license.txt
*/


/**
 * Add settings page
 */
//add menu item
function tls12ct_admin_menu() {
	add_management_page('TLS 1.2 Test', 'TLS 1.2 Test', 'manage_options', 'tls12ct-tests', 'tls12ct_tests_page');
}
add_action('admin_menu', 'tls12ct_admin_menu');

/**
 * Get endpoints and tests.
 */
function tls12ct_getEndPoints() {
	return array(
		//id => array(label, endpoint, callback handler)
		'paypal'=>array('name'=>'paypal', 'label'=>'PayPal', 'url'=>'https://tlstest.paypal.com', 'callback'=>'tls12ct_test_paypal'),
		'google'=>array('name'=>'google', 'label'=>'Google', 'url'=>'https://cert-test.sandbox.google.com/', 'callback'=>'tls12ct_test_google'),
		'howsmyssl'=>array('name'=>'howsmyssl', 'label'=>"How's My SSL?", 'url'=>'https://www.howsmyssl.com/a/check', 'callback'=>'tls12ct_test_howsmyssl'),		
		
	);
}

/**
 * Hit the endpoint
 */
function tls12ct_test_endpoint($endpoint) {
	//hit the url
	$result = wp_remote_retrieve_body(wp_remote_get($endpoint['url']));		
	
	//process results
	return call_user_func($endpoint['callback'], $result);
}

/**
 * Process Result from PayPal Endpoint
 */
function tls12ct_test_paypal($result) {	
	if($result == 'PayPal_Connection_OK')
		return array('enabled'=>true, 'message'=>$result);
	else
		return array('enabled'=>false, 'message'=>$result);	
}

/**
 * Process Result from Google Endpoint
 */
function tls12ct_test_google($result) {	
	if($result == 'Client test successful.')
		return array('enabled'=>true, 'message'=>$result);
	else
		return array('enabled'=>false, 'message'=>'See below.');
}

/**
 * Process Result from HowsMySSL Endpoint
 */
function tls12ct_test_howsmyssl($result) {	
	$result = json_decode($result, true);
	
	if($result['tls_version'] == 'TLS 1.2')
		$enabled = true;
	else
		$enabled = false;	
		
	return array('enabled'=>$enabled, 'message'=>'Rating: ' . $result['rating']);
}


/**
 * Load TLS 1.2 Test Page
 */
function tls12ct_tests_page() {
?>
<div class="wrap">
	<h2><?php _e('TLS 1.2 Compatibility Test', 'tls12ct'); ?></h2>
	<p class="description"><?php _e('Verify TLS 1.2 support for included API endpoints and diagnose a solution to enable compatibility.', 'tls12ct'); ?></p>
	<hr />
	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">
			<div id="post-body-content">							
				<?php
					//get all endpoints
					$endpoints = tls12ct_getEndPoints();
					
					//which was chosen
					if( !empty($_POST['tls12ct_endpoint']) && check_admin_referer( 'refresh', 'tls12_nonce' ))
						$tls12ct_endpoint = $_POST['tls12ct_endpoint'];
					else
						$tls12ct_endpoint = array_keys($endpoints)[0];
						
					//run test
					$tls12 = tls12ct_test_endpoint($endpoints[$tls12ct_endpoint]);
				?>				
				<div id="postbox-container-2" class="postbox-container">
					<div class="postbox">
						<h3 class="hndle"><?php printf(__('Test Results Using %s Endpoint', 'tls12ct'), $endpoints[$tls12ct_endpoint]['label']); ?></h3>
						<div class="inside">
							<?php if($tls12['enabled']) { ?>
								<h1 style="color: green"><?php _e('TLS 1.2 Enabled', 'tls12ct');?></h1>
								<p><?php _e('Your site should work fine when making calls to gateways and APIs that only support TLS 1.2. You may still want to consider the actions below to be fully secure.', 'tls12ct');?></p>
							<?php } else { ?>
								<h1 style="color: red"><?php _e('TLS 1.2 Not Enabled', 'tls12ct');?></h1>
								<p><?php _e('Your site is likely to fail when attempting calls to gateways and APIs that only support TLS 1.2. Consider following the actions below.', 'tls12ct');?></p>
							<?php } ?>
						<table class="wp-list-table widefat fixed" width="100%" cellpadding="0" cellspacing="0" border="0">
							<thead>
								<tr class="alternate">
									<th><?php _e('Type', 'tls12ct'); ?></th>
									<th><?php _e('Result', 'tls12ct'); ?></th>
									<th><?php _e('Action', 'tls12ct'); ?></th>
								</tr>
							</thead>
							<tbody>									
								<tr>
									<td><?php _e('Endpoint', 'tls12ct'); ?></td>
									<td><?php echo $endpoints[$tls12ct_endpoint]['url'] . ' (' . $endpoints[$tls12ct_endpoint]['label'] . ')'; ?></td>
									<td>
										<?php 
											if($tls12['enabled'])
												echo $tls12['message']; 
											else
												echo '<strong style="color: red">' . $tls12['message'] . '</strong>';
										?>
									</td>
								</tr>
								<tr>
									<td><?php _e('PHP Version', 'tls12ct'); ?></td>
									<td><?php echo phpversion(); ?></td>
									<td>
										<?php 
											if(version_compare(phpversion(), '5.5.19', '>='))
												_e('Upgrade to PHP version 5.5.19 or higher.', 'tls12ct');
											else
												echo '<strong style="color: red">' . __('Upgrade to PHP version 5.5.19 or higher.', 'tls12ct') . '</strong>';
										?>
									</td>
								</tr>
								<tr class="alternate">
									<td><?php _e('cURL SSL Version', 'tls12ct'); ?></td>
									<td>
										<?php 
											if(!function_exists('curl_version'))
												_e('cURL not installed.', 'tls12ct');
											else {
												$curl_version = curl_version();
												echo $curl_version['ssl_version'];
											}
										?>
									</td>
									<td>
										<?php
											if(function_exists('curl_version'))
												echo '<strong>' . __('Make sure you are running OpenSSL/1.0.1 or higher, NSS/3.15.1 or higher, or latest version of other cryptographic libraries.' . '</strong>',
												'tls12ct');
											else
												_e('Install cURL if requests are not working or not secure.', 'tls12ct');
										?>
									</td>
								</tr>									
							</tbody>
						</table>
						</div> <!-- end inside -->
					</div> <!-- end postbox -->
				</div> <!-- end postbox-container-2 -->					
				<form class="settings" method="post" action="">
					<table class="form-table">
						<tbody>
							<tr valign="top">
								<th scope="row"><?php _e('Select API Endpoint', 'tls12ct'); ?></th>
								<td>
									<select id="tls12ct_endpoint" name="tls12ct_endpoint">
										<?php
											foreach($endpoints as $endpoint) {
											?>
											<option value="<?php echo esc_attr($endpoint['name']);?>" <?php selected($tls12ct_endpoint, $endpoint['name']);?>><?php echo $endpoint['label'];?></option>
											<?php
											}
										?>
									</select>
									<span class="description"><?php _e('You only need to test against one API Endpoint.', 'tls12ct'); ?></span>
								</td>
							</tr>
						</tbody>
					</table>
					<fieldset class="submit">
						<input class="button-primary" type="submit" name="tls12ct_test_submit" value="<?php _e('Refresh Test', 'tls12ct');?>">
						<?php wp_nonce_field('refresh', 'tls12_nonce'); ?>
					</fieldset>
				</form>												
			</div> <!-- end post-body-content -->
			<div id="postbox-container-1" class="postbox-container">
				<div class="postbox">
					<h3 class="hndle"><?php _e('About the Test', 'tls12ct'); ?></h3>
					<div class="inside">
						<p><?php _e('Payment gateways are now requiring commmunication via TLS 1.2. This plugin will test your webserver with popular gateways such as Stripe, PayPal, Braintree and Authorize.net, as well as the testing endpoint provided by OpenSSL to ensure there is no outage in your ecommerce application.', 'tls12ct'); ?></p>
						<p><?php _e('If your server is not able to communicate via the TLS 1.2 protocol, you will be shown the appropriate steps to take to upgrade the server version of OpenSSL, PHP, or direct you to update the SSLVERSION of CURL.', 'tls12ct'); ?></p>
					</div> <!-- end inside -->
				</div> <!-- end postbox -->
			</div> <!-- end postbox-container-1 -->
		</div> <!-- end post-body -->
	</div> <!-- end poststuff -->
</div> <!-- end wrap -->
<?php   
}
