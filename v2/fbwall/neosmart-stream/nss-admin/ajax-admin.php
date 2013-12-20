<?php
	include "../setup.php";
	
	if(!$nss->is_logged_in($nss)) 		die('error logged_out');
	if(empty($_POST['action'])) 		die('error no_action');
		
	switch($_POST['action']){
		//Save groups -------------------------------------------------------
		case 'save_groups':
		
			$json = json_decode($_POST['json']);
			$data = $json->data;
			$groups = '';
			foreach($data as $key => $value){
				$groups .= "$"."nss->addGroup(array('id'=>'".$key."','channels'=>'".$value."'));";
			}
			$nss->updateGroups($groups);
			
		break;
		//Download and install latest version
		case 'download_latest':
			echo $nss->downloadLatest();
		break;
		case 'install_latest':
			echo $nss->installLatest();
		break;
		default:
			die('error wrong_action');
		
	}	
?>