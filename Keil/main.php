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
		
			<a name="News"></a>	
            <div class="box">
				
				<h1>News 12/01/07</span></h1>
				
				<p>The search page is still being coded... For now you can select a user and see their stats.  In the future
                you will be able to view everyone's average on a single page and then select them to see more in depth stats.
                I'm open to any and all suggestion to make this better so I can actually propose it for leagues to use in the future.  Thanks!!</p>  

				<p>My email is <a href="mailto:srehlander@gmail.com">srehlander@gmail.com</a>.  Email me anytime.</p>
				
			</div>		
			<div class="box">
				
				<h1>News 11/01/07</span></h1>
				
				<p><strong>Got Pins?</strong> is a free utility provided by <strong>SR Technologies.
				</strong> We are a versatile organization that is dedicated to making the lives of
				small business owners a bit easier.  We are currently in the business of custom
				database applications and web design.  Please <a href="contact.php">Contact Us</a>
				with any requests for business (or if you wish to advertise on this site).  Thanks for
				visiting us and we look foward to working with you in the near future!</p>  

				<p>For more information, please visit our <a href="about.php">About Us</a> page.</p>
		
				<p>Good luck and I hope you find our free service benficial to your bowling game!</p>
				
			</div>
			
			<a name="Chatter"></a>			
			<div class="box">
				
				<h1>Recent <span class="gray">Chatter</span></h1>
				
				<p class="chatter">
					<b>xi2elic:</b>  Hi all, welcome to my website!!				</p>
		  </div>
			
			<?php
				if ($_POST['submitComment']) 
				{
					$file = fopen("comments.dat", "a");
					$date = date('m-j-Y');
					$name = $_POST['name'];
					$email = $_POST['email'];
					$comment = $_POST['comment'];
					fwrite($file, 
						"$date\t$name\t$email\t$comment\n");
					fclose($file);
				}
			?>
				<h3>Send us a Comment:</h3>
				<form action="main.php" method="post">		
					<p>
					<label>Name</label>
					<input name="name" value="Your Name" type="text" size="30" />
					<label>Email</label>
					<input name="email" value="Your Email" type="text" size="30" />
					<label>Your Comment</label>
					<textarea name="comment" rows="5" cols="5"></textarea>
					<br />	
					<input class="button" name="submitComment" type="submit" />		
					</p>		
				</form>				
				
				<br />	
			
			</div>	
							
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