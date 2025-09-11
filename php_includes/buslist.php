<?php
	$Origin = $_SESSION['Origin'];
	$Destination = $_SESSION['Destination'];
	$Departure = $_SESSION['Departure'];
	$Number = $_SESSION['Number'];
	$BusType = $_SESSION['BusType'];
	$result = mysqli_query($conn, "SELECT * FROM routes WHERE origin='$Origin' AND destination='$Destination' AND bustype='$BusType'");

	if(isset($_POST['register']))
	{
		$id = $_SESSION['id'];
		$result2 =mysqli_query($conn, "SELECT * FROM routes WHERE busid=$id");
		$row2 = mysqli_fetch_array($result2);
		$bustype = $row2['bustype'];
		$origin = $row2['origin'];
		$destination = $row2['destination'];
		$price = $row2['price'];
		$time = $row2['time'];
		$Name = $_SESSION['Name'];
		$Address = $_SESSION['Address'];
		$Email = $_SESSION['Email'];
		$Contact = $_SESSION['Contact'];
		$Seats = $_SESSION['Seats'];
		$Seat = preg_split('/\s+/', $Seats);
		for($i=0; $i < count($Seat); $i++)
		{
			$seatnumber = $Seat[$i];
			echo "<script type='text/javascript'>alert('".$seatnumber."')</script>";
			mysqli_query($conn, "INSERT INTO regs(name, address, mobile, email, bustype, origin, destination, price, seat_no, timetodep) VALUES ('$Name','$Address','$Contact','$Email', '$bustype', '$origin', '$destination', '$price', '$seatnumber', '$time')");
		}
		echo "<script type='text/javascript'>alert('Information has been added.')</script>";
	}

	if(isset($_POST['sub']))
		{
			$_SESSION['Name'] = $_POST['rn'];
			$_SESSION['Address'] = $_POST['addr'];
			$_SESSION['Email'] = $_POST['email'];
			$_SESSION['Contact'] = $_POST['cont'];
			$_SESSION['Seats'] = $_POST['seat'];

			print "Please Confirm your Information:<br/>

					<form action='".$_SERVER['PHP_SELF']."' method='POST'>
						<table border='1'>
							<tr>
								<td>Name</td>
								<td>".$_SESSION['Name']."</td>
							</tr>
							<tr>
								<td>Address</td>
								<td>".$_SESSION['Address']."</td>
							</tr>
							<tr>
								<td>Email</td>
								<td>".$_SESSION['Email']."</td>
							</tr>
								<td>Contact No.</td>
								<td>".$_SESSION['Contact']."</td>
							</tr>
							<tr>
								<td>Seats</td>
								<td>".$_SESSION['Seats']."</td>
							</tr>
							<tr>
								
							<tr><td colspan='2' align='center'><input type='submit' name='register' value='Register'/></td>
							</tr>
						</table>
					</form>";

		}

	if(isset($_POST['Book']))
	{
		$_SESSION['id'] = $_POST['hidden'];
			print "Personal Info:<br/>

					<form action='".$_SERVER['PHP_SELF']."' method='POST'>
						<table border='1'>
							<tr>
								<td>Name</td>
								<td><input type='text' name='rn' placeholder='Name'/></td>
							</tr>
							<tr>
								<td>Address</td>
								<td><input type='text' name='addr' placeholder='Address'/></td>
							</tr>
							<tr>
								<td>Email</td>
								<td><input type='email' name='email' placeholder='Email Address'/></td>
							</tr>
							<tr>
								<td>Contact No.</td>
								<td><input type='text' name='cont' placeholder='Contact Number'/></td>
							</tr>
							<tr>
								<td>Seats</td>
								<td><input type='text' name='seat' placeholder='Seats'/></td>
							</tr>
							<tr>
								<td colspan='2' align='center'><input type='submit' name='sub' value='Register'/></td>
							</tr>
						</table>
					</form>";

	}
	if(!isset($_POST['Book']) && !isset($_POST['sub']))
	{
		if(mysqli_num_rows($result) > 0)
		{
			print "<table border='1'>
						<tr>
							<th>Origin</th>
							<th>Destination</th>
							<th>Time</th>
							<th>Price</th>
							<th>Bus Type</th>
							<th>Available Seats</th>
						</tr>";

			while($row = mysqli_fetch_array($result))
			{?>
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method='POST'>
					<tr>
						<td><?php echo $row['origin']; ?></td>
						<td><?php echo $row['destination']; ?></td>
						<td><?php echo $row['time']; ?></td>
						<td><?php echo $row['price']; ?></td>
						<td><?php echo $row['bustype']; ?></td>
						<td>40 daw</td>
						<td>
							<input type='hidden' name='hidden' value='<?php echo $row['busid']?>'>
							<input type='submit' name='Book' value='Book'/>
						</td>
					</tr>
				</form>
			<?php
			}
			print "</table>";
		}
		else
		{
		?>
			No Records Found.
		<?php
		}
	}
?>