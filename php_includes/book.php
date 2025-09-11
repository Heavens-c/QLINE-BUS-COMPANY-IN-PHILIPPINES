<?php
	if(isset($_POST['submit']))
	{
		$_SESSION['Origin'] = $_POST['Origin'];
		$_SESSION['Destination'] = $_POST['Destination'];
		$_SESSION['Departure'] = $_POST['Departure'];
		$_SESSION['Number_Pass'] = $_POST['no_of_pass'];
		$_SESSION['WayType'] = $_POST['way'];
		$_SESSION['BusType'] = $_POST['bustype'];
		header("Location: view.php");
		if($_POST['way'] == "1")
		{
			if($_POST['Origin'] == "0" || $_POST['Destination'] == "0" || $_POST['bustype'] == "0" || $_POST['Departure'] == "")
			{
				echo "<script type='text/javascript'>alert('Please Complete the Form.')</script>";
			}
			else
			{

			}
		}
		else
		{
			echo "<script type='text/javascript'>alert('Currently on Maintenance.')</script>";
		}
	}
?>