<?php
/***************************************************************************
File Name 	: index.php
Domain		: http://www.PHPSupportTickets.com/
----------------------------------------------------------------------------
Author		: Ian Warner
Copyright	: (C) 2001 Triangle Solutions Ltd
Email		: iwarner@triangle-solutions.com
URL		: http://www.triangle-solutions.com/
Description	: Brings together all the elements of the Support Tickets app.
Date Created	: Wednesday 19 January 2005 16:13:23
File Version	: 1.9
\\||************************************************************************/

#############################################################################################
############################### CURRENT CASEID'S ON THIS PAGE ###############################
#############################################################################################

	// home		- LINE 401
	// view		- LINE 669
	// NewTicket	- LINE 1018


#############################################################################################
################## INCLUDE THE CONFIG, FUNCTIONS, LANGUAGE AND HEADER FILE ##################
#############################################################################################

	// STARTS THE SESSION FOR THE USERS SO LOGIN IS TRACKED THROUGH THE PAGES

	session_start();

	// INCLUDE THE CONFIG AND FUNCTIONS AND LANGUAGE FILE

	include_once ('config.php');
	include_once ('class/functions.php');

	IF (!isset($_REQUEST['lang']))
		{
		$_REQUEST['lang'] = $langdefault;
		}

	IF (!isset($_GET['action']))
		{
		$_GET['action'] = 'Login';
		}

	include_once ('language/'.$_REQUEST['lang'].'.php');
	include_once ('header.php');


#############################################################################################
####################### AUTH LOGIN AND LOGOUT SYSTEM REQUIRES SESSIONS ######################
#############################################################################################

	// LOGOUT

	IF (isset($_GET['action']) && $_GET['action'] == 'Logout')
		{
		unset($_SESSION['stu_username']);
		unset($_SESSION['stu_password']);

		$_GET['action'] = 'Login';
		}

	// WHAT TO DO IF NO USERNAME OR PASSWORD IS SET

	IF (!isset($_SESSION['stu_username']) && !isset($_SESSION['stu_password']))
		{
?>
		<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
		  <tr>
			<td class="boxborder text" bgcolor="<?php echo $background ?>"><?php echo $_GET['action'] ?></td>
			<td class="boxborder list-menu" width="15%"><a href="<?php echo $_SERVER['PHP_SELF'] ?>?action=Login"><?php echo $text_login ?></a></td>
<?php
		IF (isset($allowreg) && $allowreg == 'ON')
			{
?>
			<td class="boxborder list-menu" width="15%"><a href="<?php echo $_SERVER['PHP_SELF'] ?>?action=Register"><?php echo $text_register ?></a></td>
<?php
			}
?>
			<td class="boxborder list-menu" width="15%"><a href="<?php echo $_SERVER['PHP_SELF'] ?>?action=Resend"><?php echo $text_resend ?></a></td>
			<td class="boxborder list-menu" width="10%"><a href="javascript:popwindow('help.php#userpage','top=150,left=300,width=400,height=400,buttons=no,scrollbars=YES,location=no,menubar=no,resizable=no,status=no,directories=no,toolbar=no')"><?php echo $text_help ?></a></td>
		  </tr>
		</table>

<?php
	// CREATE LOGIN AREA

		IF ($_GET['action'] == 'Login')
			{
			IF (isset($_GET['sub']))
				{
				IF ((AuthUser($_REQUEST['username'], $_REQUEST['password'])) || (isset($_COOKIE['demomode']) && $demomode == 'ON' && $_POST['username'] == 'demo' && $_POST['password'] == 'demo'))
					{
					$_SESSION['stu_username'] = $_POST['username'];
					$_SESSION['stu_password'] = $_POST['password'];

	// LOG THE LOGIN TIMES ONLY DO THIS WHEN NOT IN DEMO MODE

					IF (!isset($_COOKIE['demomode']) || $demomode != 'ON')
						{
	// SELECT THE LAST LOGGED IN FIELD
						$query = "	SELECT tickets_users_newlogin
								FROM tickets_users
								WHERE tickets_users_username = '".$_SESSION['stu_username']."'";

						$result = mysql_query($query);
						$row    = mysql_fetch_array($result);

	// UPDATE THE NEW LOGGED IN FIELD IN THE USER ACCOUNT

						$query = "	UPDATE tickets_users
								SET
								tickets_users_newlogin	     = '".mktime()."',
								tickets_users_lastlogin	     = '".$row['0']."'
								WHERE tickets_users_username = '".$_SESSION['stu_username']."'";

						$result = mysql_query($query);
						}
?>
					<meta http-equiv="refresh" content="0;url=<?php echo $_SERVER['PHP_SELF'] ?>" />
<?php
					}
				ELSE
					{
?>
					<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
					  <tr>
						<td class="text">
						<?php echo $text_loginpage ?>
						<input type="button" value="<?php echo $text_loginback ?>" onclick="history.back()" />
						</td>
					  </tr>
					</table>
<?php
					}
				}
			ELSE
				{
?>
				<form action="<?php echo $_SERVER['PHP_SELF'] ?>?action=Login&amp;sub=verify" method="post">
				<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
				  <tr>
					<td class="text" align="center"><br />
					<?php echo $text_username ?>: <input name="username" size="20"
<?php
				IF (isset($_COOKIE['demomode']) && $demomode == 'ON')
					{
					echo 'value="demo"';
					}
?>
					/> <?php echo $text_password ?>: <input type="password" name="password" size="20"
<?php
				IF (isset($_COOKIE['demomode']) && $demomode == 'ON')
					{
					echo 'value="demo"';
					}
?>
					/> <input type="submit" name="form" value="<?php echo $text_login ?>" /><br /><br />
					</td>
				  </tr>
				</table>
				</form>
<?php
				}
			}

	// DEAL WITH THE RESEND REQUESTS

		IF ($_GET['action'] == 'Resend')
			{
			IF (isset($_GET['sub']))
				{
				$query = "	SELECT tickets_users_name, tickets_users_username, tickets_users_password
						FROM tickets_users
						WHERE tickets_users_email = '".$_POST['email']."'";

				$result = mysql_query($query);

				IF (mysql_num_rows($result) > '0')
					{
					$row = mysql_fetch_array($result);

	// OUTGOING EMAIL MESSAGE TO USERS WHO REQUEST RESEND DETAILS

					$message  = "Dear ".$row['tickets_users_name']."\n\n";
					$message .= "Below are the requested Account Details.\n";
					$message .= "Username: ".$row['tickets_users_username']."\n";
					$message .= "Password: ".$row['tickets_users_password']."\n\n";
					$message .= "Kind Regards\n";
					$message .= "Customer Care at ".$socketfromname."\n";
?>
					<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
					  <tr>
						<td class="text"><?php echo SendMail($_POST['email'], $row['tickets_users_name'], 'Account Details Request', $message) ?></td>
					  </tr>
					</table>
<?php
					}
				ELSE
					{
?>
					<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
					  <tr>
						<td class="text"><?php echo $text_resenderror ?><input type="button" value="<?php echo $text_resendback ?>" onclick="history.back()" /></td>
					  </tr>
					</table>
<?php
					}
				}
			ELSE
				{
?>
				<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
				  <tr>
					<td class="text"><?php echo $text_resendpage ?></td>
				  </tr>
				</table><br />

				<form action="<?php echo $_SERVER['PHP_SELF'] ?>?action=Resend&amp;sub=verify" method="post">
				<table width="300" cellspacing="1" cellpadding="1" class="boxborder" align="center">
				  <tr>
					<td width="100%" class="boxborder text"><?php echo $text_email ?>:</td>
					<td class="boxborder"><input name="email"
<?php
				IF (isset($_POST['email']))
					{
?>
					value="<?php echo $_POST['email'] ?>"
<?php
					}
?>
					size="42" /></td>
				  </tr>
				</table>

				<table width="300" cellspacing="1" cellpadding="1" align="center">
				  <tr>
					<td align="right"><input type="submit" value="<?php echo $text_submit ?>" /></td>
				  </tr>
				</table>
				</form>
<?php
				}
			}

	// DEAL WITH THE USER SELF REGISTRY OPTIONS

		IF ($_GET['action'] == 'Register')
			{
			IF (isset($_GET['sub']) && $allowreg == 'ON')
				{
				IF ($_POST['name'] == '' || !eregi('^[0-9a-z]{6,16}$', $_POST['username']) || !eregi('^[0-9a-z]{6,16}$', $_POST['password']) || $_POST['email'] == '' || !ereg('^..*\@.+\..+[A-Za-z0-9]$', $_POST['email']))
					{
?>
					<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
					  <tr>
						<td class="text"><?php echo $text_regpageerr ?>
						<input type="button" value="<?php echo $text_regpagback ?>" onclick="history.back()" />
						</td>
					  </tr>
					</table>
<?php
					}
				ELSE
					{
	// TEST FOR DUPLICATE USERNAME
					$query = "	SELECT tickets_users_id
							FROM tickets_users
							WHERE tickets_users_username = '".$_POST['username']."'
							LIMIT 0,1";

					$result = mysql_query($query);

					IF (mysql_num_rows($result) <= '0')
						{
						$query = "	INSERT INTO tickets_users
								SET
								tickets_users_name     = '".$_POST['name']."',
								tickets_users_username = '".$_POST['username']."',
								tickets_users_password = '".$_POST['password']."',
								tickets_users_email    = '".$_POST['email']."'";

						IF ($result = mysql_query($query))
							{
	// REGISTRATION EMAIL
							$message  = 'Dear '.$_POST['name']."\n\n";
							$message .= "Thank you for registering.\n";
							$message .= 'Username: '.$_POST['username']."\n";
							$message .= 'Password: '.$_POST['password']."\n";
							$message .= 'Email: '.$_POST['email']."\n\n";
							$message .= "Kind Regards\n";
							$message .= 'Customer Care at '.$socketfromname."\n";

							$msg = $text_regconf.SendMail($_POST['email'], $_POST['name'], $text_regsubject, $message);
							}
						}
					ELSE
						{
						$msg = $text_regusererr.'<input type="button" value="'.$text_regpagback.'" onclick="history.back()" />';
						}
?>
					<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
					  <tr>
						<td class="text"><?php echo $msg ?></td>
					  </tr>
					</table><br />
<?php
					}
				}
			ELSE
				{
?>
				<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
				  <tr>
					<td class="text"><?php echo $text_regpage ?></td>
				  </tr>
				</table><br />

				<form action="<?php echo $_SERVER['PHP_SELF'] ?>?action=Register&amp;sub=verify" method="post">
				<table width="300" cellspacing="1" cellpadding="1" class="boxborder" align="center">
				  <tr>
					<td class="boxborder text"><?php echo $text_regname ?></td>
					<td class="boxborder"><input name="name" size="35" /></td>
				  </tr>
				  <tr>
					<td class="boxborder text"><?php echo $text_reguser ?></td>
					<td class="boxborder"><input name="username" size="35" /></td>
				  </tr>
				  <tr>
					<td class="boxborder text"><?php echo $text_regpass ?></td>
					<td class="boxborder"><input type="password" name="password" size="35" /></td>
				  </tr>
				  <tr>
					<td width="100%" class="boxborder text"><?php echo $text_regemail ?></td>
					<td class="boxborder"><input name="email" size="35" /></td>
				  </tr>
				</table>

				<table width="300" cellspacing="0" cellpadding="2" align="center">
				  <tr>
					<td align="right"><input type="submit" value="<?php echo $text_regsubmit ?>" /></td>
				  </tr>
				</table>
				</form>
<?php
				}
			}

		include_once ('footer.php');

		Exit();
		}


#############################################################################################
################ MAKE SURE THE RIGHT CASEID IS ENTERED OR DEFAULT TO HOME ID ################
#############################################################################################

	IF (!isset($_GET['caseid']) || $_GET['caseid'] == '' || $_GET['caseid'] != 'home' && $_GET['caseid'] != 'view' && $_GET['caseid'] != 'NewTicket')
		{
		$_GET['caseid'] = 'home';
		}


#############################################################################################
########################### DISPLAY THE PAGE TITLE AND NAVIGATION ###########################
#############################################################################################
?>
	<form action="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=home" method="post">
	<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" align="<?php echo $maintablealign ?>">
	  <tr>
		<td><a href="<?php echo $_SERVER['PHP_SELF'] ?>"><img src="images/support_tickets_logo.gif" width="83" height="61" title="Triangle Solutions PHP Support Tickets" alt="Triangle Solutions PHP Support Tickets" vspace="2" border="0" /></td>
		<td valign="bottom" align="right" class="text" style="padding:2px">Search Tickets:
		<input name="keywords" size="24" onfocus="javascript:this.value=''" value="Search Ticket Subject" />
		<input type="submit" value="Go" />
		</td>
	  </tr>
	</table>
	</form>

	<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
	  <tr>
		<td class="boxborder text" bgcolor="<?php echo $background ?>"><a href="<?php echo $_SERVER['PHP_SELF'] ?>"><?php echo $text_titlelink ?></a> - <?php echo $text_title ?></td>
		<td class="boxborder list-menu" width="15%"><a href="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=NewTicket"><?php echo $text_titlereq ?></a></td>
		<td class="boxborder list-menu" width="15%"><a href="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=home&amp;order=Open"><?php echo $text_titleope ?></a></td>
		<td class="boxborder list-menu" width="15%"><a href="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=home&amp;order=Closed"><?php echo $text_titleclo ?></a></td>
		<td class="boxborder list-menu" width="10%"><a href="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=home&amp;action=Logout"><?php echo $text_titlelog ?></a></td>
	  </tr>
	</table>

<?php
#############################################################################################
############## HOME DEFAULT CASE THIS DEALS WITH THE DISPLAYING OF ANY TICKETS ##############
#############################################################################################

	SWITCH ($_GET['caseid'])
		{
		CASE 'home':

			IF (!isset($_GET['order']) && !isset($_POST['keywords']))
				{
				$_GET['order'] = 'Open';
				}

	// PROCESS THE FUNCTIONS WHEN THE CHECKBOXES ARE CHECKED - IE OPEN CLOSE TICKET

			IF (isset($_POST['status']))
				{
				IF (isset($_POST['ticket']))
					{
					FOREACH ($_POST['ticket'] AS $ticketid)
						{
						$query = "	UPDATE tickets_tickets
								SET tickets_status = '".$_POST['status']."'
								WHERE tickets_id   = '".$ticketid."'";

						IF (mysql_query($query))
							{
							$msg = 'Ticket '.$_POST['status'];
							}
						ELSE
							{
							$msg = 'This could not be done at this time';
							}
						}
					}
				ELSE
					{
					$msg = 'Please select a Ticket.';
					}
?>
				<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
				  <tr bgcolor="#AACCEE">
					<td class="text"><?php echo $msg ?></td>
				  </tr>
				</table>
<?php
				}

	// QUERY TO SELECT THE TICKETS LISTING - THIS CAN BE CHANGED TO OPEN OR CLOSED ONLY
	// DEPENDING ON THE LINK THAT IS HIT ON THE NAV BAR - HOME PAGE DEFAULTS TO BOTH.

			$query = "	SELECT  tickets_id, tickets_subject, tickets_timestamp, tickets_status,
						tickets_status_name, tickets_status_color, tickets_categories_name
					FROM tickets_tickets a, tickets_status b, tickets_categories c
					WHERE a.tickets_username = '".$_SESSION['stu_username']."'
					AND a.tickets_child = '0'
					AND a.tickets_urgency = b.tickets_status_id
					AND a.tickets_category = c.tickets_categories_id";

			IF (isset($_GET['order']))
				{
				$query .= " AND a.tickets_status = '".$_GET['order']."'";
				$addon  = '&amp;order='.$_GET['order'];
				}

			ELSEIF (isset($_POST['keywords']))
				{
				$query .= " AND a.tickets_subject LIKE '%".$_POST['keywords']."%'";
				$addon  = '';
				}

			$query .= '	ORDER BY a.tickets_id DESC, a.tickets_timestamp DESC';

	// SET PAGE NUMBER IF NONE SPECIFIED ASSUME IT IS EQUAL TO ONE

			$result       = mysql_query($query);
			$totaltickets = mysql_num_rows($result);

			$per_page = $ticket_display;

			IF (!isset($_GET['display']))
				{
		   		$_GET['display'] = '1';
				}

			$prev_page = $_GET['display'] - 1;
			$next_page = $_GET['display'] + 1;
	// SET UP PAGE
			$page_start = ($per_page * $_GET['display']) - $per_page;

			$num_rows = $totaltickets;

			IF ($num_rows <= $per_page)
				{
				$num_pages = '1';
				}
			ELSEIF (($num_rows % $per_page) == '0')
				{
				$num_pages = ($num_rows / $per_page);
				}
			ELSE
				{
				$num_pages = ($num_rows / $per_page) + 1;
				}

			$num_pages = (int) $num_pages;

	// DISPLAY RESULTS

			$query  = $query . " LIMIT $page_start, $per_page";
			$result = mysql_query($query);
?>
			<div style="padding-top:5px"></div>
<?php
			IF ($totaltickets > '0')
				{
				ShowPaging(	$_SERVER['PHP_SELF'].'?'.htmlentities($_SERVER['QUERY_STRING']),
						$prev_page,
						$next_page,
						$num_pages,
						$_GET['display']
						);
				}
?>
			<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
			  <tr bgcolor="<?php echo $background ?>">
				<td class="boxborder text"><?php echo $text_listtitle ?>
<?php
			IF ($totaltickets > '0')
				{
				echo ' '.$totaltickets.' - '.$text_listmsg;
				}
			ELSE
				{
				echo ' 0';
				}
?>
				</td>
			  </tr>
			</table>
<?php
			IF ($totaltickets > '0')
				{
?>
				<script language="javascript" type="text/javascript">
				<!--
				function check_all()
					{
					for (var c = 0; c < document.myform.elements.length; c++)
					  	{
				  		if (document.myform.elements[c].type == 'checkbox')
						    	{
							if(document.myform.elements[c].checked == true)
								{
								document.myform.elements[c].checked = false;
								}
								else
									{
									document.myform.elements[c].checked = true;
									}
							}
						}
					}
				// -->
				</script>

				<form name="myform" action="index.php?caseid=home<?php echo $addon ?>" method="post">
				<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
				  <tr align="center" bgcolor="<?php echo $background ?>">
					<td class="boxborder text" onclick="check_all();" style="cursor:pointer"><b><u>All</u></b></td>
					<td class="boxborder text"><b>Ticket ID</b></td>
					<td class="boxborder text"><b>Replies</b></td>
					<td class="boxborder text"><b><?php echo $text_listsub ?></b></td>
					<td class="boxborder text"><b>Date / Time</b></td>
					<td class="boxborder text"><b><?php echo $text_listurg ?></b></td>
					<td class="boxborder text"><b>Department</b></td>
					<td class="boxborder text"><b><?php echo $text_liststa ?></b></td>
				  </tr>
<?php
	// LOOP THROUGH THE REQUESTS FOR THE USERS ACCOUNT

				WHILE ($row = mysql_fetch_array($result))
					{

	// QUERY TO GET THE AMOUNT OF REPLIES TO A CERTAIN TICKET AND DATE OF LAST ENTRY

					$queryA = "	SELECT COUNT(*) AS ticket_count, MAX(tickets_timestamp) AS date, tickets_users_lastlogin
							FROM tickets_tickets a, tickets_users b
							WHERE tickets_child = '".$row['tickets_id']."'
							AND a.tickets_username = b.tickets_users_username
							GROUP BY tickets_child";

					$resultA = mysql_query($queryA);
					$rowA    = mysql_fetch_array($resultA);

					IF ($rowA['ticket_count'] <= '0')
						{
						$rowA['ticket_count'] = '0';
						}
?>
					<tr align="center" bgcolor="<?php echo UseColor() ?>">
						<td class="boxborder"><input type="checkbox" name="ticket[]" value="<?php echo $row['tickets_id'] ?>" /></td>
						<td class="boxborder list-menu"><a href="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=view&amp;ticketid=<?php echo $row['tickets_id'] ?>"><?php echo $row['tickets_id'] ?></a></td>
						<td class="boxborder text">[<?php echo $rowA['ticket_count'] ?>]
<?php
					IF (isset($rowA['date']) && ($rowA['date'] > $rowA['tickets_users_lastlogin']))
						{
						echo '<img src="images/new_reply.gif" border="0" />';
						}
?>
						</td>
						<td class="boxborder text"><?php echo $row['tickets_subject'] ?></td>
						<td class="boxborder text"><?php echo date($dformat, $row['tickets_timestamp']).' '.date('H:i:s', $row['tickets_timestamp']) ?></td>
						<td class="boxborder text" bgcolor="#<?php echo $row['tickets_status_color'] ?>"><?php echo $row['tickets_status_name'] ?></td>
						<td class="boxborder text"><?php echo $row['tickets_categories_name'] ?></td>
						<td class="boxborder text">
<?php
					IF ($row['tickets_status'] == 'Closed')
						{
						echo '<span style="color:#FF0000">';
						}
					ELSE
						{
						echo '<span style="color:#000000">';
						}
?>
					<?php echo $row['tickets_status'] ?></span></td>
						  </tr>
<?php
					}
?>
				  <tr>
					<td colspan="8">
					<select name="status">
					<option value="Open">Open</option>
					<option value="Closed">Closed</option>
					</select>
					<input type="submit" name="sub" value="Go">
					</td>
				  </tr>
				</table>
				</form>
<?php
				ShowPaging(	$_SERVER['PHP_SELF'].'?'.htmlentities($_SERVER['QUERY_STRING']),
						$prev_page,
						$next_page,
						$num_pages,
						$_GET['display']
						);
				}

	// IF THERE ARE NO TICKETS TO SHOW THEN PLACE A DEFAULT MESSAGE

			ELSE
				{
				IF (isset($_POST['keywords']))
					{
					$msg = $text_searcherr;
					}
				ELSE
					{
					$msg = $text_listnon;
					}
?>
				<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
				  <tr>
					<td class="text"><?php echo $msg ?></td>
				  </tr>
				</table>
<?php
				}
		BREAK;


#############################################################################################
######## VIEW A TICKET - VALID IF THE USER CLICKS LINK FROM SEARCH OR HOME LISTINGS #########
#############################################################################################

		CASE 'view':

	// CLOSE OR REOPEN A TICKET

			IF (isset($_GET['closesub']))
				{
				$query = "	UPDATE tickets_tickets
						SET
						tickets_status	 = '".$_GET['closesub']."'
						WHERE tickets_id = '".$_GET['ticketid']."'";

				IF (mysql_query($query))
					{
					$msg = 'Ticket '.$_GET['closesub'];
					}
				ELSE
					{
					$msg = 'This could not be done at this time';
					}
?>
				<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
				  <tr bgcolor="#AACCEE">
					<td class="text"><?php echo $msg ?></td>
				  </tr>
				</table>
<?php
				}

	// INSERT THE TICKET INTO THE DATABASE AND SEND THE EMAIL

			IF (isset($_GET['sub']))
				{
				IF ($_POST['message'] == '')
					{
					$msg = 'Please complete all the fields';
					}
				ELSE
					{
					$_POST['postsubject']  = Clean_It($_POST['postsubject']);
					$_POST['posturgency']  = Clean_It($_POST['posturgency']);
					$_POST['postcategory'] = Clean_It($_POST['postcategory']);
					$_GET['ticketid']      = Clean_It($_GET['ticketid']);
					$_POST['message']      = Clean_It($_POST['message']);

					$urgency  = explode('|', $_POST['posturgency']);
					$category = explode('|', $_POST['postcategory']);

					$query = "	INSERT INTO tickets_tickets
							SET
							tickets_username  = '".$_SESSION['stu_username']."',
							tickets_subject   = '".$_POST['postsubject']."',
							tickets_timestamp = '".mktime()."',
							tickets_urgency   = '".$urgency['0']."',
							tickets_category  = '".$category['0']."',
							tickets_child 	  = '".$_GET['ticketid']."',
							tickets_question  = '".addslashes($_POST['message'])."'";

					IF ($result = mysql_query($query))
						{

	// CHECK THE FILE ATTACHMENT AND DISPLAY ANY ERRORS

						IF ($allowattachments == 'TRUE' && (!isset($_COOKIE['demomode']) || $demomode != 'ON'))
							{
							FileUploadsVerification("$_FILES(userfile)", mysql_insert_id());
							}

	// EMAIL ADMINISTRATOR THE TICKET NOTIFICATION

						$message  = "Ticket ID:\t ".$_GET['ticketid']."\n";
						$message .= "Name:\t\t ".$_POST['name']."\n";
						$message .= "Subject:\t ".$_POST['postsubject']."\n";
						$message .= "Urgency:\t ".$urgency['1']."\n";
						$message .= "Department:\t ".$category['1']."\n";
						$message .= "Post Date:\t ".date($dformatemail)."\n";
						$message .= "----------------------------------------------------------------------\n";
						$message .= "Message:\n";
						$message .= stripslashes($_POST['message'])."\n";
						$message .= "----------------------------------------------------------------------\n\n\n";
						$message .= "Previous Thread Messages (Latest First):\n";
						$message .= "----------------------------------------------------------------------\n";

	// LOOP THROUGH THE PREVIOUS MESSAGES AND ADD DATA REGARDING QUESTION TIME AND ATTACHMENT

						FOR ($i = COUNT($_POST['ticketquestion']) - 1; $i >= '0'; $i--)
							{
							$message .= $_POST['postedby'][$i]." - ".$_POST['postdate'][$i]."\n";
							$message .= stripslashes($_POST['ticketquestion'][$i]);

							IF (isset($_POST['attachment'][$i]) && $_POST['attachment'][$i] != '')
								{
								$message .= "\nAttachment - ".$_POST['attachment'][$i];
								}

							$message .= "\n----------------------------------------------------------------------\n";
							}

						$message .= "\nRegards\n\n";
						$message .= $socketfromname;
?>
						<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
						  <tr>
							<td class="text">
<?php
						echo SendMail(	$_POST['email'],
								$_POST['name'],
								'Response To Ticket '.$_GET['ticketid'].' Written By - '.$_SESSION['stu_username'],
								$message);

						IF ($emailuser == 'TRUE')
							{
							echo SendMail(	$_POST['email'],
									$_POST['name'],
									'Response To Ticket '.$_GET['ticketid'].' Written By - '.$_SESSION['stu_username'],
									$message,
									'1');
							}
?>
							</td>
						  </tr>
						</table>
<?php
						$refresh = 'TRUE';
						}
					}
				}

	// QUERY TO GET THE TICKET IN QUESTION AND ANY REPLIES TO THAT TICKET

			$query = "	SELECT tickets_id, tickets_subject, tickets_timestamp, tickets_status, tickets_name, tickets_email, tickets_admin, tickets_child, tickets_question, tickets_status_id, tickets_status_name, tickets_status_color, tickets_categories_id, tickets_categories_name
					FROM tickets_tickets a, tickets_status b, tickets_categories c
					WHERE (a.tickets_id = '".$_GET['ticketid']."'
					OR tickets_child = '".$_GET['ticketid']."')
					AND a.tickets_urgency = b.tickets_status_id
					AND a.tickets_category = c.tickets_categories_id
					AND tickets_username = '".$_SESSION['stu_username']."'
					ORDER BY tickets_id ASC";

			$result	      = mysql_query($query);
			$totaltickets = mysql_num_rows($result);
			$row	      = mysql_fetch_array($result);

	// PRINT OUT THE TABLES TO HOLD THE TICKET INFO - REPLY SUBMISSION AND TOPIC AND ANY REPLIES AND ATTACHMENTS
?>
			<form enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=view&amp;ticketid=<?php echo $_GET['ticketid'] ?>&amp;sub=add" method="post">
			<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
			  <tr>
				<td class="boxborder" width="50%" valign="top" style="padding-top:5px">

				<table width="97%" cellspacing="1" cellpadding="1" class="boxborder" align="center">
				  <tr>
					<td bgcolor="#AABBDD" class="boxborder text"><b>Ticket #<?php echo $_GET['ticketid'] ?> Information</b></td>
<?php
			IF ($row['tickets_status'] == 'Open')
				{
?>
				<td class="boxborder list-menu" width="30%"><a href="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=view&amp;ticketid=<?php echo $_GET['ticketid'] ?>&amp;closesub=Closed">Close Ticket</a></td>
<?php
				}
			ELSE
				{
?>
				<td class="boxborder list-menu"" width="30%"><a href="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=view&amp;ticketid=<?php echo $_GET['ticketid'] ?>&amp;closesub=Open">Reopen Ticket</a></td>
<?php
				}
?>
				  </tr>
				</table>

				<table width="97%" cellspacing="1" cellpadding="1" class="boxborder" align="center">
				  <tr>
					<td bgcolor="#EEEEEE" class="boxborder text"><b>Account:</b></td>
					<td class="boxborder text"><?php echo $_SESSION['stu_username'] ?></td>
				  </tr>
				  <tr>
					<td bgcolor="#EEEEEE" class="boxborder text"><b>Name:</b></td>
					<td class="boxborder text"><?php echo $row['tickets_name'] ?></td>
				  </tr>
				  <tr>
					<td bgcolor="#EEEEEE" class="boxborder text"><b>Email:</b></td>
					<td class="boxborder text"><?php echo $row['tickets_email'] ?></td>
				  </tr>
				  <tr>
					<td bgcolor="#EEEEEE" class="boxborder text"><b>Subject:</b></td>
					<td class="boxborder text"><?php echo $row['tickets_subject'] ?></td>
				  </tr>
				  <tr>
					<td bgcolor="#EEEEEE" class="boxborder text"><b>Department:</b></td>
					<td class="boxborder text"><?php echo $row['tickets_categories_name'] ?></td>
				  </tr>
				  <tr>
					<td bgcolor="#EEEEEE" class="boxborder text"><b>Urgency:</b></td>
					<td class="boxborder text" bgcolor="#<?php echo $row['tickets_status_color'] ?>"><b><?php echo $row['tickets_status_name'] ?></b></td>
				  </tr>
				  <tr>
					<td bgcolor="#EEEEEE" class="boxborder text"><b>Status:</b></td>
					<td class="boxborder text">
<?php
			IF ($row['tickets_status'] == 'Closed')
				{
				echo '<span style="color:#FF0000">';
				}
			ELSE
				{
				echo '<span style="color:#000000">';
				}

			echo		$row['tickets_status'];
?>
					</span></td>
				  </tr>
				</table><div style="padding-top:5px"></div>
<?php
			IF ($row['tickets_status'] != 'Closed')
				{
?>
				<table width="97%" cellspacing="1" cellpadding="1" class="boxborder" align="center">
				  <tr bgcolor="#AABBDD">
					<td class="boxborder text"><b>Respond</b></td>
				  </tr>
				  <tr>
					<td align="center"><textarea name="message" cols="80" rows="10"></textarea></td>
				  </tr>
				  <tr>
					<td align="right">
					<input type="hidden" name="name" value="<?php echo $row['tickets_name'] ?>" />
					<input type="hidden" name="email" value="<?php echo $row['tickets_email'] ?>" />
					<input type="hidden" name="postsubject" value="<?php echo $row['tickets_subject'] ?>" />
					<input type="hidden" name="posturgency" value="<?php echo $row['tickets_status_id'] ?>|<?php echo $row['tickets_status_name'] ?>" />
					<input type="hidden" name="postcategory" value="<?php echo $row['tickets_categories_id'] ?>|<?php echo $row['tickets_categories_name'] ?>" />
					<input type="submit" value="Submit" />
					</td>
				  </tr>
				</table><div style="padding-top:5px"></div>
<?php
	// ALLOW THE USERS TO ATTACH A FILE TO THE TICKET

				IF ($allowattachments == 'TRUE' && (!isset($_COOKIE['demomode']) || $demomode != 'ON'))
					{
					FileUploadForm();
					}
				}
?>
				<br /></td>
				<td width="50%" valign="top" style="padding-top:5px">
<?php
			$j = '0';
			$result = mysql_query($query);

	// LOOP THROUGH THE QUESTIOSN AND RESPONSES TO THIS QUESTION

			WHILE ($row = mysql_fetch_array($result))
				{
?>
				<table width="97%" cellspacing="1" cellpadding="1" class="boxborder" align="center">
				  <tr bgcolor="#AABBDD">
					<td class="boxborder text"><b>
<?php
				IF ($j == '0')
					{
					echo '	Dialog Question';
					}
				ELSE
					{
					echo '	Response '.$j;
					}
?>
					</b></td>
					<td class="boxborder text" bgcolor="#AACCDD" width="50%" align="right"><?php echo date($dformat.' H:i:s', $row['tickets_timestamp']) ?></td>
				  </tr>
<?php
				IF ($row['tickets_admin'] == 'Admin')
					{
					$bgcolor = '#FFF000';
					}
				ELSE
					{
					$bgcolor = '#AACCEE';
					}
?>
				  <tr>
					<td class="boxborder text" colspan="2"><?php echo nl2br($row['tickets_question']) ?></td>
				  </tr>
				  <tr bgcolor="<?php echo $bgcolor ?>">
					<td class="boxborder text">Posted By: <?php echo $row['tickets_admin'] ?></td>
					<td class="text">
<?php
	// SCAN THE UPLOAD DIRECTORY FOR ATTACHMENTS TO THIS POST IF ATTACHMENTS ARE OFF THEN THIS WONT DO IT

				IF ($allowattachments == 'TRUE')
					{
					$d = dir($uploadpath);

					WHILE (false !== ($files = $d -> read()))
						{
						$files = explode('.', $files);

						IF ($files['0'] == $row['tickets_id'])
							{
?>
						  	<b>Attachment:</b> <?php echo $files['0'] ?>.<?php echo $files['1'] ?>
							<a href="<?php echo $relativepath.$files['0'] ?>.<?php echo $files['1'] ?>" target="_blank">
							<img src="images/download.gif" width="13" height="13" align="absmiddle" border="0" /></a>
<?php
							$filename = $files['0'].'.'.$files['1'];
?>
							<input type="hidden" name="attachment[<?php echo $_GET['ticketid'] - 1 ?>]" value="<?php echo $filename ?>" />
<?php
							}
						ELSE
							{
							$filename = '';
							}
						}

					$d -> close();
					}
?>
					</td>
				  </tr>
				</table><div style="padding-top:5px"></div>
<?php
				$j ++;
?>
				<input type="hidden" name="ticketquestion[]" value="<?php echo $row['tickets_question'] ?>" />
				<input type="hidden" name="postedby[]" value="<?php echo $row['tickets_admin'] ?>" />
				<input type="hidden" name="postdate[]" value="<?php echo date($dformat.' H:i:s', $row['tickets_timestamp']) ?>" />
<?php
				}
?>
				</td>
			  </tr>
			</table>
			</form>
<?php
			IF (isset($refresh) && $refresh == 'TRUE')
				{
?>
				<meta http-equiv="refresh" content="2;URL=<?php echo $_SERVER['PHP_SELF'] ?>?caseid=view&amp;ticketid=<?php echo $_GET['ticketid'] ?>" />
<?php
				}

		BREAK;


#############################################################################################
#################################### CREATE A NEWTICKET #####################################
#############################################################################################

		CASE 'NewTicket':

	// IF THE FORM IS SUBMITTED THEN VERIFY SOME CONTENTS

			IF (isset($_GET['sub']))
				{

	// IF FORM IS NOT FILLED OUT CORRECTLY THEN SHOW ERROR MESSAGES

				IF ($_POST['message'] == '' || $_POST['name'] == '' || $_POST['email'] == '' || !ereg('^..*\@.+\..+[A-Za-z0-9]$', $_POST['email']) || $_POST['ticketsubject'] == '')
					{
?>
					<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
					  <tr bgcolor="#AACCEE">
						<td class="text">Please complete all the fields.</td>
						<td class="text">Ahhhhhh!!!!! What the hell happened?  The form is not filled out correctly.</td>
					  </tr>
					</table>
<?php
					}

	// IF FORM IS OK THEN INSERT INTO THE DATABASE

				ELSE
					{
					$_POST['ticketsubject']	= Clean_It($_POST['ticketsubject']);
					$_POST['name']		= Clean_It($_POST['name']);
					$_POST['email']		= Clean_It($_POST['email']);
					$_POST['urgency']	= Clean_It($_POST['urgency']);
					$_POST['category']	= Clean_It($_POST['category']);
					$_POST['message']	= Clean_It($_POST['message']);

					$urgency  = explode('|', $_POST['urgency']);
					$category = explode('|', $_POST['category']);

					$query = "	INSERT INTO tickets_tickets
							SET
							tickets_username  = '".$_SESSION['stu_username']."',
							tickets_subject	  = '".$_POST['ticketsubject']."',
							tickets_timestamp = '".mktime()."',
							tickets_name	  = '".$_POST['name']."',
							tickets_email	  = '".$_POST['email']."',
							tickets_urgency	  = '".$urgency['0']."',
							tickets_category  = '".$category['0']."',
							tickets_question  = '".addslashes($_POST['message'])."'";

					IF ($result = mysql_query($query))
						{
						$lastinsertid = mysql_insert_id();

	// CHECK THE FILE ATTACHMENT AND DISPLAY ANY ERRORS

						IF ($allowattachments == 'TRUE' && !isset($_COOKIE['demomode']) || $demomode != 'ON')
							{
							FileUploadsVerification("$_FILES(userfile)", mysql_insert_id());
							}
	// EMAIL ADMINISTRATOR THE TICKET NOTIFICATION

						$message  = "Ticket ID:\t ".mysql_insert_id()."\n";
						$message .= "Name:\t\t ".$_POST['name']."\n";
						$message .= "Email:\t ".$_POST['email']."\n";
						$message .= "Subject:\t ".$_POST['ticketsubject']."\n";
						$message .= "Urgency:\t ".$urgency['1']."\n";
						$message .= "Department:\t ".$category['1']."\n";
						$message .= "Post Date:\t ".date($dformatemail)."\n";
						$message .= "----------------------------------------------------------------------\n";
						$message .= "Message:\n";
						$message .= stripslashes($_POST['message'])."\n";
						$message .= "----------------------------------------------------------------------\n";
?>
						<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
						  <tr>
							<td class="text">
<?php
						echo SendMail(	$_POST['email'],
								$_POST['name'],
								'Support Ticket Written By - '.$_SESSION['stu_username'],
								$message);

						IF ($emailuser == 'TRUE')
							{
							echo SendMail(	$_POST['email'],
									$_POST['name'],
									'Support Ticket Written By - '.$_SESSION['stu_username'],
									$message,
									'1');
							}
?>
							</td>
						  </tr>
						</table>
<?php
						$refresh = 'TRUE';
						}
					}
				}

	// PRODUCE THE FORM SO THE PERSON CAN WRITE THE NEW TICKET

			IF ($_SESSION['stu_username'] != 'demo')
				{
				$query = "	SELECT tickets_users_name, tickets_users_email
						FROM tickets_users
						WHERE tickets_users_username = '".$_SESSION['stu_username']."'
						LIMIT 0,1";

				$result = mysql_query($query);
				$row    = mysql_fetch_array($result);
				}
			ELSE
				{
				$row['tickets_users_name']  = '';
				$row['tickets_users_email'] = '';
				}
?>
			<form enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=NewTicket&amp;sub=add" method="post">
			<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
			  <tr>
				<td class="boxborder" width="50%" valign="top" style="padding-top:5px">

				<table width="97%" cellspacing="1" cellpadding="1" class="boxborder" align="center">
				  <tr bgcolor="#AABBDD">
					<td class="boxborder text" colspan="2"><b>New Support Ticket - All Fields Required</b></td>
				  </tr>
				  <tr>
					<td bgcolor="#EEEEEE" class="boxborder text"><b>Account:</b></td>
					<td class="boxborder text"><?php echo $_SESSION['stu_username'] ?></td>
				  </tr>
				  <tr>
					<td bgcolor="#EEEEEE" class="boxborder text"><b>Name:</b></td>
					<td class="boxborder text"><input name="name" size="40" value="<?php echo $row['tickets_users_name'] ?>" /></td>
				  </tr>
				  <tr>
					<td bgcolor="#EEEEEE" class="boxborder text"><b>Email:</b></td>
					<td class="boxborder text"><input name="email" size="40" value="<?php echo $row['tickets_users_email'] ?>" /></td>
				  </tr>
				  <tr>
					<td bgcolor="#EEEEEE" class="boxborder text"><b>Subject:</b></td>
					<td class="boxborder text"><input name="ticketsubject" size="40"
<?php
			IF (isset($_POST['ticketsubject']) && $_POST['ticketsubject'] != '')
				{
				echo ' value="'.$_POST['ticketsubject'].'"';
				}
?>
					></td>
				  </tr>
				  <tr>
					<td bgcolor="#EEEEEE" class="boxborder text"><b>Department:</b></td>
					<td class="boxborder text">
					<select name="category">
<?php
			$query = "	SELECT tickets_categories_id, tickets_categories_name
					FROM tickets_categories
					ORDER BY tickets_categories_name ASC";

			$result = mysql_query($query);

			WHILE ($row = mysql_fetch_array($result))
				{
				echo '<option value="'.$row['tickets_categories_id'].'|'.$row['tickets_categories_name'].'">'.$row['tickets_categories_name'].'</option>';
				}
?>
					</select>
					</td>
				  </tr>
				  <tr>
					<td bgcolor="#EEEEEE" class="boxborder text"><b>Urgency:</b></td>
					<td class="boxborder text">
					<select name="urgency">
<?php
			$query = "	SELECT tickets_status_id, tickets_status_name, tickets_status_color
					FROM tickets_status
					ORDER BY tickets_status_order ASC";

			$result = mysql_query($query);

			WHILE ($row = mysql_fetch_array($result))
				{
				echo '<option style="background-color:#'.$row['tickets_status_color'].'" value="'.$row['tickets_status_id'].'|'.$row['tickets_status_name'].'">'.$row['tickets_status_name'].'</option>';
				}
?>
					</select></td>
				  </tr>
				</table><div style="padding-top:5px"></div>

				<table width="97%" cellspacing="1" cellpadding="1" class="boxborder" align="center">
				  <tr bgcolor="#AABBDD">
					<td class="boxborder text"><b>Question</b></td>
				  </tr>
				  <tr>
					<td align="center">
					<textarea name="message" cols="80" rows="10">
<?php
			IF (isset($_POST['message']) && $_POST['message'] != '')
				{
				echo $_POST['message'].'</textarea>';
				}
			ELSE
				{
				echo '</textarea>';
				}
?>
					</td>
				  </tr>
				  <tr>
					<td align="right"><input type="submit" value="Submit" /></td>
				  </tr>
				</table><div style="padding-top:5px"></div>
<?php
	// ALLOW THE USERS TO ATTACH A FILE TO THE TICKET

			IF ($allowattachments == 'TRUE' && (!isset($_COOKIE['demomode']) || $demomode != 'ON'))
				{
				FileUploadForm();
				}
?>
				<br /></td>
				<td class="boxborder" width="50%" valign="top" style="padding-top:5px">

				<table width="97%" cellspacing="1" cellpadding="1" class="boxborder" align="center">
				  <tr>
					<td class="text">Please fill in all the information. And make sure the question is very
					explicit as to what the problem is, some guidelines follow:
					<ul>
					<li>Type of question (bug / content / Other)</li>
					<li>When did you see this (date and time)</li>
					<li>Is there a location to see this bug (URL / Media)</li>
					<li>Description (detailed but concise)</li>
					</ul>

					Make sure all fields are filled in; the script will check for
					a correctly formed email address. Please choose the category that
					best suits this ticket.
					<br /><br /></td>
				  </tr>
<?php
	// IF ATTACHMENTS ARE TRUE THEN SHOW ALLOWED FILETYPES

			IF ($allowattachments == 'TRUE')
				{
?>
				  <tr>
					<td class="text"><b>Allowed FILE TYPES for attachments:</b><br />
<?php
				FOR ($i = '0'; $i <= COUNT($allowedtypes) - 1; $i++)
					{
					echo $allowedtypes[$i].'<br />';
					}
?>
					</td>
				  </tr>
<?php
				}
?>
				</table><br />

				</td>
			  </tr>
			</table>
			</form>
<?php
			IF (isset($refresh) && $refresh == 'TRUE')
				{
?>
				<meta http-equiv="refresh" content="2;URL=<?php echo $_SERVER['PHP_SELF'] ?>?caseid=view&amp;ticketid=<?php echo $lastinsertid ?>" />
<?php
				}

		BREAK;
		}


#############################################################################################
################################ ADD THE FOOTER INFORMATION #################################
#############################################################################################

	include_once ('footer.php');

	IF (isset($result))
		{
		mysql_free_result($result);
		mysql_close();
		}
?>
