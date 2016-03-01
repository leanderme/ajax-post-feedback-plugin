<?php



function pf_save_options($data, $key = null) {

    if (empty($data))
        return;	
    // SAVING STYLES
    $pf_data = pf_get_settings( PFPATH .'/admin/settings/pf-settings.php' );
    POST_FEEDBACK::pf_generate_options_css($pf_data);

		foreach ( $data as $k=>$v ) {
			if (!isset($pf_data[$k]) || $pf_data[$k] != $v) { // Only write to the DB when we need to
				update_option($k, $v);
			} else if (is_array($v)) {
				foreach ($v as $key=>$val) {
					if ($key != $k && $v[$key] == $val) {
						update_option($k, $v);
						break;
					}
				}
			}
	  	}

}


function pf_ajax_callback() {

	$pf_data = pf_get_settings( PFPATH .'/admin/settings/pf-settings.php' );

	$nonce=$_POST['security'];
	
	if (! wp_verify_nonce($nonce, 'of_ajax_nonce') ) die('-1'); 
			
	//get options array from db
	$all = pf_get_settings( PFPATH .'/admin/settings/pf-settings.php' );

	
	wp_parse_str(stripslashes($_POST['data']), $pf_data);
	unset($pf_data['security']);
	unset($pf_data['of_save']);
	pf_save_options($pf_data);
		
	die('1');

}