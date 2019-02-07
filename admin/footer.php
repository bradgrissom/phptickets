<?php
/***************************************************************************
File Name 	: footer.php
Domain		: http://www.PHPSupportTickets.com/
----------------------------------------------------------------------------
Author		: Ian Warner
Copyright	: (C) 2001 Triangle Solutions Ltd
Email		: iwarner@triangle-solutions.com
URL		: http://www.triangle-solutions.com/
Description	: Displays the footer for the Admin area.
Date Created	: Wednesday 19 January 2005 16:15:35
File Version	: 1.9
\\||************************************************************************/
?>
	<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" align="<?php echo $maintablealign ?>">
	  <tr>
		<td align="center">
		<a href="http://www.triangle-solutions.com" target="_blank" title="triangle solutions web development">Triangle Solutions Ltd</a> |
		<a href="http://www.phpsupporttickets.com" target="_blank" title="php support tickets">PHP Support Tickets <?php echo $version ?></a><br /><br />
		</td>
	  </tr>
	</table>

	<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="center">
	  <tr bgcolor="#AABBDD">
		<td class="boxborder text">Support and Donations</td>
	  </tr>
	  <tr>
		<td class="text">If you plan on using PHP Support Tickets, then please note you use it at your own Risk.
		If you require support then I would be happy to recieve some remuneration for that via PayPal.<br /><br />

		<table width="100%" cellpadding="1" cellspacing="1">
		  <tr>
			<td width="33%" align="center">

			<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
			<table cellpadding="0" cellspacing="0">
			  <tr>
				<td class="text">Donate through PayPal!</td>
			  </tr>
			  <tr align="center">
				<td>
				<input type="hidden" name="cmd" value="_xclick" />
				<input type="hidden" name="business" value="iwarner@triangle-solutions.com" />
				<input type="hidden" name="item_name" value="PHP Support Tickets" />
				<input type="hidden" name="item_number" value="phpst_triangle" />
				<input type="hidden" name="no_note" value="1" />
				<input type="hidden" name="currency_code" value="USD" />
				<input type="hidden" name="tax" value="0" />
				<input type="image" src="https://www.paypal.com/images/x-click-but04.gif" width="62" height="31" border="0" name="submit" title="Support tickets secure payment with paypal" alt="Support tickets secure payment with paypal" />
				</td>
			  </tr>
			</table>
			</form>

			</td>
			<td width="33%" align="center">

			<form action="http://www.hotscripts.com/cgi-bin/rate.cgi" method="post" target="_blank">
			<table cellpadding="0" cellspacing="0">
			  <tr>
				<td class="text">Rate it at HotScripts.com!</td>
			  </tr>
			  <tr>
				<td><select name="rate" size="1">
				<option value="5">Excellent</option>
				<option value="4">Very Good</option>
				<option value="3">Good</option>
				<option value="2">Fair</option>
				<option value="1">Poor</option>
				</select>
				<input type="hidden" name="ID" value="22679" />
				<input type="hidden" name="external" value="1" />
				<input type="submit" name="submit" value="Vote" />
				</td>
			  </tr>
			</table>
			</form>

			</td>
			<td width="33%" align="center">

			<form action="http://www.scriptsearch.com/cgi-bin/rateit.cgi" method="post" target="_blank">
			<table cellspacing="0" cellpadding="0">
			  <tr>
				<td class="text">Rate it at ScriptSearch.com</td>
			  </tr>
			  <tr>
				<td>
				<select name="rate" size="1">
				<option value="5">Excellent!</option>
				<option value="4">Very Good</option>
				<option value="3">Good</option>
				<option value="2">Fair</option>
				<option value="1">Poor</option>
				</select>
				<input type="hidden" name="ID" value="9272" />
				<input type="submit" name="submit" value="Vote" />
				</td>
			  </tr>
			</form>
			</table>

			</td>
		  </tr>
		</table>

		</td>
	  </tr>
	</table>

	</body>
	</html>