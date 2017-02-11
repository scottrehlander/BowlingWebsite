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
			<form action="ViewStats.php" method="get">
				<p>
				Select user:
				<?php

						echo("<select name=\"showStats\" style=\"width: 350px\">");
						echo("<option value=\"-1\">" . "Please select a bowler..." . "</option>");
						#we need to get a list of users to populatehe optionBox
						$usersTable = getUsersRecordSet($con);
						while($userRow = mysql_fetch_array($usersTable))
							echo("<option value=\"" . $userRow["userId"] . "\">" . $userRow["username"] . "</option>");
					?>
				</select>
				<input type="submit" value="Show">
				</p>
			</form>
			<br>
			<?php
				#if a user was previous selected, get their stats and echo a table
				if(!empty($_GET["showStats"]))
				{
					#get the users scores
					$userScores = getUserScores($con, $_GET["showStats"]);
					$userName = mysql_fetch_array(getUserRecordSet($con, $_GET["showStats"]));
					echo("Showing records for <b>" . $userName["username"] . ":</b><br>"); 
					if(!empty($userScores))
					{
						#unfortunately we need to tally up averages first to display them, this isn't very efficient right now
						$numOfStrings = 0;
						$finalHighestFrame = 0;
						$totalPins = 0;
						while($tempScore = mysql_fetch_array($userScores))
						{
							$stringTotal = 0;
							for($i = 1; $i <= 10; $i++)
							{
								$frameString = "Frame" . $i;
								$stringTotal += $tempScore[$frameString];
								$totalPins += $tempScore[$frameString];
								if($tempScore[$frameString] > $finalHighestFrame)
									$finalHighestFrame = $tempScore[$frameString];
							}
							$numOfStrings++;
						}
						
						$finalAverage = $totalPins / $numOfStrings;
						echo("<div class=\"box\"><br><center>");
						echo("<table width=90% border=0><tr>");
						echo("<td align=\"center\"><font size=\"2\">Overall Average: <b>" . $finalAverage . "</b></font></td>");
						echo("<td align=\"center\"><font size=\"2\">Overall Pins: <b>" . $totalPins . "</b></font></td>");
						echo("<td align=\"center\"><font size=\"2\">Overall Highest Frame: <b>" . $finalHighestFrame . "</b></font></td>");
						echo("</tr></table></center>");
						echo("<br></div><br>");
						
						#iterate through user scores
						mysql_data_seek($userScores, 0);
						while($userScore = mysql_fetch_array($userScores))
						{
							echo("<br>");
							echo("<b>" . $userScore["date"] . "</b>");
							echo("<div class=\"box\">");
							echo("<table border=0 width=90% cellspacing=10>");
							echo("<tr>");
							echo("<td width=50% rowspan=4>");
							echo("<table border=1 cellpadding=0 cellspacing=0 width=90%");
							
							$totalScore = 0;
							$highestFrame = 0;
							for($i = 1; $i <= 10; $i++)
							{
								echo("<tr align=\"center\"><td>");
								$frameString = "Frame" . $i;
								if($i == 1)
								{
									echo("</td><td align=\"center\"><font size=\"3\">" . $userScore[$frameString] . "</font>");
									echo("</td></tr>");
									$totalScore = $userScore[$frameString];
									$highestFrame = $totalScore;
								}
								else
								{
									echo("<font size=\"3\">" . $userScore[$frameString] . "</font>");
									echo("</td>");
									$totalScore += $userScore[$frameString];
									echo("<td><font size=\"3\">" . $totalScore . "</font>");
									echo("</td></tr>");
									
									if($userScore[$frameString] > $highestFrame)
										$highestFrame = $userScore[$frameString];
								}
							}
							
							echo("</table>");
							echo("<tr><td valign=\"top\"><font size=\"3\">Score: <b>" . $totalScore . "</b></font></td></tr>");
							echo("<tr><td valign=\"top\"><font size=\"3\">Frame Average: <b>" . ($totalScore / 10) . "</b></font></td></tr>");
							echo("<tr><td valign=\"top\"><font size=\"3\">Highest Frame: <b>" . $highestFrame . "</b></font></td></tr>");;
							echo("</table>");
							echo("</div><br>");
						}
					}
				}
				else
				{
					#crappy, just for now
					echo("<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>");
				}
			?>
			
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
		&copy; 2006 <strong>SR Technologies</strong> |
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