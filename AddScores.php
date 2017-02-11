<?php
	// Starting session
	session_start(); 
	
	// Includes
	require("includes/bowling-DB.php");
	require("includes/bowling-session.php");	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php 	

	$con = getDBConnection();
	$loginFailed = false;
	
	// If the user is not authenticated and the username has been posted back to this page
	if(!$_SESSION['authenticated'] && !empty($_POST['username']))
	{
		if(authenticateUser($_POST['username'], $_POST['password'], $con) > -1)
		{
			// If the user is authenticated populate Session data 
			$_SESSION['username'] = $_POST['username'];
			$_SESSION['userTable'] = getUserTable($_SESSION['username'], $con);
			$_SESSION['userProfile'] = getUserProfile($_SESSION['username'], $con);
			$_SESSION['authenticated'] = true;
		}
		else if(!empty($_POST['username']))
			$loginFailed = true;
	}
?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>

<meta name="Description" content="Find yourself a local contractor!" />
<meta name="Keywords" content="work, contractor, marlborough, shed, houses, building" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="Distribution" content="Global" />
<meta name="Author" content="Scott Rehlander - srehlander@gmail.com" />
<meta name="Robots" content="index,follow" />

<title>Welcome to Alley Stats - Main</title>
	
<link href="css/Azulmedia.css" rel="stylesheet" type="text/css" />
</head>

<body>
<!-- wrap starts here -->

<div id="wrap">

	<div id="header">	
		
		<h1 id="logo">Got<span class="gray">Pins</span>?</h1>
		<!-- Menu Tabs -->
<div id="menu">
			<ul>
			
			<?php
				// Display navigation list
				echoNavList($con, true);

			?>
			
			</ul>					
		</div>		
	
	</div>
				
	<!-- content-wrap starts here -->
	<div id="content-wrap">	 
	
		<div id="sidebar" >				

			<?php
											
				// Display username if logged in
				if($_SESSION['authenticated'] == true)
					echo("<p align=\"center\">Welcome " . $_SESSION['username'] . ".</p>");
				else if($loginFailed)
					echo("<p align=\"center\"><font color=\"red\">Login failed.</font></p>");
										
			?>

			<h1 class="clear">Main Navigation</h1>
			<ul class="sidemenu">
			
				<?php
					echoNavList($con, false);
				?>			
				
			</ul>		
			
			<h1>Featured Bowlers</h1>
			<ul class="sidemenu">
			
				<?php
					echoFeaturedProfilesList($con);
				?>
				
			</ul>
			
			<?php
				// Display login if we are not authenticated
				if($_SESSION['authenticated'] != true)
				{
			?>
					<h1>Log in </h1>
					<div class="loginform">
						<form action="main.php" method="post">
							<p>
							<input name="username" size="29" value="Username" class="textbox" type="text" />
							</p>
							<p>
							<input name="password" size="29" class="textbox" type="password" />
							</p>
							<p align="right">
  							<input name="login" class="button" value="Log in" type="submit" />
							</p>
						</form>
					</div>
			
			<?php
				} // end if logged in
			?>
			
			<h1>Quotables</h1>
			<p>&quot;The happiest people are those who invested their time in others.
			The unhappiest people are those who wonder how the world is going to 
			make them happy&quot;</p>		
				
			<p class="align-right">- John Maxwell</p>			
			
		</div>	
	
		<div id="main">	
			<?php
				#if submit then update database with new scores
				if(!empty($_POST['Frame1']))
				{
					echo("user id: " . $_POST['userId']);
					addNewScore($con, $_POST['userId'], $_POST['date'], $_POST['Frame1'], $_POST['Frame2'], $_POST['Frame3'], $_POST['Frame4'], $_POST['Frame5'], $_POST['Frame6'], $_POST['Frame7'], $_POST['Frame8'], $_POST['Frame9'], $_POST['Frame10']);
				}
			?>
			<form action="AddScores.php" method="post">
				<table width=90% align="center">
					<tr>
						<td><br>Select user:</td>
						<td><br>
							<select name="userId" style="width: 350px">
							<?php
								#we need to get a list of users to populatehe optionBox
								$usersTable = getUsersRecordSet($con);
								while($userRow = mysql_fetch_array($usersTable))
									echo("<option value=\"" . $userRow["userId"] . "\">" . $userRow["username"] . "</option>");
							?>
							</select>
						</td>
					<tr>
						<td>Date (YYYY-MM-DD):</td>
						<td><input type="text" style="width: 344px" name="date"></td>
					</tr>	
					<tr>
						<td>Frame 1:</td>
						<td><input type="text" style="width: 344px" name="Frame1"></td>
					</tr>
					<tr>
						<td>Frame 2:</td>
						<td><input type="text" style="width: 344px" name="Frame2"></td>
					</tr>
					<tr>
						<td>Frame 3:</td>
						<td><input type="text" style="width: 344px" name="Frame3"></td>
					</tr>
					<tr>
						<td>Frame 4:</td>
						<td><input type="text" style="width: 344px" name="Frame4"></td>
					</tr>
					<tr>
						<td>Frame 5:</td>
						<td><input type="text" style="width: 344px" name="Frame5"></td>
					</tr>
					<tr>
						<td>Frame 6:</td>
						<td><input type="text" style="width: 344px" name="Frame6"></td>
					</tr>
					<tr>
						<td>Frame 7:</td>
						<td><input type="text" style="width: 344px" name="Frame7"></td>
					</tr>
					<tr>
						<td>Frame 8:</td>
						<td><input type="text" style="width: 344px" name="Frame8"></td>
					</tr>
					<tr>
						<td>Frame 9:</td>
						<td><input type="text" style="width: 344px" name="Frame9"></td>
					</tr>
					<tr>
						<td>Frame 10:</td>
						<td><input type="text" style="width: 344px" name="Frame10"></td>
					</tr>
					<tr>
						<td colspan=2 align="center"><br><input type="submit" value="Submit" name="submit"><br><br></td>
					</tr>
				</table>
			</form>
		</div>				
		
	<br />			
	<!-- content-wrap ends here -->		
	</div>	

<!-- wrap ends here -->
</div>

<!-- footer starts here -->	
<div id="footer-wrap">
	
	<div class="footer-left">
		<p class="align-left">			
		&copy; 2006 <strong>SR Technoligies</strong> |
		Design by <a href="http://www.styleshout.com/">styleshout</a> | Valid <a href="http://validator.w3.org/check/referer">XHTML</a> |
		<a href="http://jigsaw.w3.org/css-validator/check/referer">CSS</a>
		</p>		
	</div>
	
	<div class="footer-right">
		<p class="align-right">
		<a href="main.php">Home</a>&nbsp;|&nbsp;
  		<a href="main.php">SiteMap</a>&nbsp;|&nbsp;
   	<a href="main.php">RSS Feed</a>
		</p>
	</div>
	
</div>
<!-- footer ends here -->	

</body>
</html>