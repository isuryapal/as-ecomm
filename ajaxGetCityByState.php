<?php
	Include_once("include/functions.php");
	$functions = New Functions();
/*
	if(isset($_POST['state']) && $_POST['state']!=''){
		$state = $functions->escape_string($functions->strip_all($_POST['state']));
		$sql="select DISTINCT(districtname) from ".PREFIX."pincode where statename='".$state."' order by districtname";
		$cityResult = $functions->query($sql);
		$cityStr='<option value="">Please select city</option>';
		while($cityRow=$functions->fetch($cityResult)){
			$cityStr.="<option value='".$cityRow['districtname']."'>".$cityRow['districtname']."</option>";
		}

		// echo $cityStr;
		$ajaxResponse = array();
		$ajaxResponse['status'] = 1;
		$ajaxResponse['cityStr'] = $cityStr;
		echo json_encode($ajaxResponse);
	}*/

	if(isset($_POST['state']) && $_POST['state']!=''){
		$state = $functions->escape_string($functions->strip_all($_POST['state']));
		//echo $state; 
		$sql="select DISTINCT(name),id from ".PREFIX."states where name='".$state."' order by name";
		$result = $functions->query($sql);
		$cityData =$functions->fetch($result);
		//print
		$sql = "SELECT * FROM ".PREFIX."cities WHERE `state_id`='".$cityData['id']."'";
		$cityResult = $functions->query($sql);
		$cityStr='<option value="">Please select city</option>';
		while($cityRow=$functions->fetch($cityResult)){
			$cityStr.="<option value='".$cityRow['name']."'>".$cityRow['name']."</option>";
		}

		// echo $cityStr;
		$ajaxResponse = array();
		$ajaxResponse['status'] = 1;
		$ajaxResponse['cityStr'] = $cityStr;
		echo json_encode($ajaxResponse);
	}
?>