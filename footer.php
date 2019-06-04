<?php
/***************************************************************************
File Name 	: footer.php
Domain		: http://www.PHPSupportTickets.com/
----------------------------------------------------------------------------
Author		: Ian Warner
Copyright	: (C) 2001 Triangle Solutions Ltd
Email		: iwarner@triangle-solutions.com
URL		: http://www.triangle-solutions.com/
Description	: Displays the Footer.
Date Created	: Wednesday 19 January 2005 16:13:47
File Version	: 1.9
\\||************************************************************************/
?>
	<table width="100%" cellspacing="1" cellpadding="1" align="<?php echo $maintablealign ?>">
	  <tr>
		<td align="center" class="text">
<?php
	IF (isset($_COOKIE['demomode']))
		{
?>
		You have entered the DEMO MODE<br />Username = demo<br />Password = demo
<?php
		}
?>
		</td>
	  </tr>
	  <tr>
		<td align="center"><br />
			<h2>NOTE on June 4th 2019: Brad and Ginger have been updating this website!
			    Let Brad know if you have any problems with the website!
			    Email: <a href="mailto:bradworktickets@sogee.com">bradworktickets@sogee.com</a>
			</h2>
		</td>
	  </tr>
	</table>

	</body>
	</html>
