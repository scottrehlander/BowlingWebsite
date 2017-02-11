<?php

	function getDBConnection()
	{
		$dbServer = "db1153.perfora.net";
		$dbName = "db220806198";
		$dbUser = "dbo220806198";
		$dbPassword = "bqfv5G5c";

		$con = mysql_connect($dbServer, $dbUser, $dbPassword);
		if(!$con)
			die(mysql_error());
			
		mysql_select_db($dbName, $con);

		return $con;
	}
	
	function executeSelectQuery($sql, $con)
	{
		$result = mysql_query($sql, $con) or die(mysql_error());
			return $result;	
	}
	
	// This may do something later
	function prepareStatement($sql)
	{
		return $sql;
	}
	
	function addNewScore($con, $userId, $date, $frame1, $frame2, $frame3, $frame4, $frame5, $frame6, $frame7, $frame8, $frame9, $frame10)
	{
		$sqldate = date($date);
		$sql = prepareStatement("INSERT INTO BowlingScores (userId, date, Frame1, Frame2, Frame3, Frame4, Frame5, ". 
			"Frame6, Frame7, Frame8, Frame9, Frame10) VALUES ($userId, '$date', $frame1, $frame2, $frame3, $frame4, ".
			"$frame5, $frame6, $frame7, $frame8, $frame9, $frame10)");
		
		$result = executeSelectQuery($sql, $con);
		return $result;
	}
	
	function getNavigationRecordSet($con)
	{
		$sql = prepareStatement("SELECT * FROM navigation ORDER BY sequence");
		
		$result = executeSelectQuery($sql, $con);
		return $result;
	}
	
	function getUserRecordSet($con, $id)
	{
		$sql = prepareStatement("SELECT * FROM users WHERE userId = " . $id);
		
		$result = executeSelectQuery($sql, $con);
		return $result;
	}
	
	function getUsersRecordSet($con)
	{
		$sql = prepareStatement("SELECT * FROM users");
		
		$result = executeSelectQuery($sql, $con);
		return $result;
	}
	
	function getFeaturedProfilesRecordSet($con)
	{
		$sql = prepareStatement("SELECT * FROM featuredprofiles");
		
		$result = executeSelectQuery($sql, $con);
		return $result;
	}
	
	function getUserScores($con, $userId)
	{
		$sql = prepareStatement("SELECT * FROM BowlingScores WHERE userId = " . $userId);
		
		$result = executeSelectQuery($sql, $con);
		return $result;
	}
	
	function echoNavList($con, $top)
	{
		$navTable = getNavigationRecordSet($con);
	
		while($row = mysql_fetch_array($navTable))
		{
			if($top && !$row["inTop"])
				continue;
			echo("<li><a href = \"" . $row["link"] . "\">" . $row["display"] . "</a></li>");
		}
	}
	
	function echoFeaturedProfilesList($con)
	{
		$profileLinkTable = getFeaturedProfilesRecordSet($con);
		
		while($row = mysql_fetch_array($navTable))
		{
			echo("<li><a href = \"" . $row["link"] . "\">" . $row["display"] . "</a></li>");
		}		
	}
	
	function getUserTable()
	{
		return null;
	}
	
	function getUserProfile()
	{
		return null;
	}
	
?>