<?php

	function authenticateUser($username, $password, $con)
	{
		$sql = prepareStatement("SELECT * FROM users WHERE username = '" . $username . "' AND password = '" . $password . "'");
		
		$result = executeSelectQuery($sql, $con);
		
		$userRow = mysql_fetch_array($result);
		
		if(empty($userRow["userId"]))
			return -1;
		else
			return $userRow["userId"];
	}
	
?>