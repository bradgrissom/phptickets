<?php
/***************************************************************************
File Name 	: index.html
Domain		: http://www.PHPSupportTickets.com/
----------------------------------------------------------------------------
Author		: Ian Warner
Copyright	: (C) 2001 Triangle Solutions Ltd
Email		: iwarner@triangle-solutions.com
URL		: http://www.triangle-solutions.com/
Description	: Admin page
Date Created	: Wednesday 19 January 2005 16:16:09
File Version	: 1.9
\\||************************************************************************/

#############################################################################################
############################### CURRENT CASEID'S ON THIS PAGE ###############################
#############################################################################################

	// home	  	- LINE 213
	// AdminView  	- LINE 501
	// adduser	- LINE 791
	// document	- LINE 1071
	// categories	- LINE 1113
	// status	- LINE 1353
	// newticket	- LINE 1421
	// footer	- LINE 1715


#############################################################################################
############################ INCLUDE THE CONFIG AND HEADER FILE #############################
#############################################################################################

	// STARTS THE SESSION FOR THE USERS SO LOGIN IS TRACKED THROUGH THE PAGES

	session_start();

	include_once ('../config.php');
	include_once ('../class/functions.php');
	include_once ('header.php');


#############################################################################################
###################### AUTH LOGIN AND LOGOUT SYSTEM REQUIRES SESSIONS #######################
#############################################################################################

	// LOGOUT

	IF (isset($_GET['action']) && $_GET['action'] == 'Logout')
		{
		unset($_SESSION['sta_username']);
		unset($_SESSION['sta_type']);
		}

	// CHECK THE THE ENTERED USERNAME AND PASSWORD ARE CORRECT

	IF (isset($_POST['form']) && isset($_POST['username']) && isset($_POST['password']))
		{

	// CHECK AGAINST THE DB FOR MODERATORS AND ADMINS WITH THE SAME USER AND PASS

		$query = "	SELECT tickets_users_admin
				FROM tickets_users
				WHERE tickets_users_username = '".$_POST['username']."'
				AND tickets_users_password = '".$_POST['password']."'
				AND (tickets_users_admin = 'Admin'
				OR tickets_users_admin = 'Mod')
				LIMIT 0,1";

		$result = mysql_query($query);
		$row    = mysql_fetch_array($result);

		IF (mysql_num_rows($result) > '0')
			{
			$_SESSION['sta_username'] = $_POST['username'];
			$_SESSION['sta_type']	  = $row['tickets_users_admin'];
			}
		ELSE
			{
			$_SESSION = array();
?>
			<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" align="<?php echo $maintablealign ?>">
			  <tr>
				<td><a href="<?php echo $_SERVER['PHP_SELF'] ?>"><img src="../images/support_tickets_logo.gif" width="83" height="61" title="Triangle Solutions PHP Support Tickets" alt="Triangle Solutions PHP Support Tickets" vspace="1" border="0" /></td>
			  </tr>
			</table>

			<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
			  <tr bgcolor="<?php echo $background ?>">
				<td class="text">Access Denied</td>
			  </tr>
			</table>

			<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
			  <tr>
				<td class="text">
				Your Username or Password is incorrect, or you are not a
				registered user on this site. Please Try logging in again.
				<input type="button" value="Back" onclick="history.back()" /><br /><br />
				</td>
			  </tr>
			</table>
<?php
			include_once ('footer.php');
			Exit();
			}
		}

	// IF NO USER OR PASS SESSION ARE ACTIVE THEN SHOW THE LOG IN AREA

	IF (!isset($_SESSION['sta_username']) && !isset($_SESSION['sta_type']))
		{
?>
		<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" align="<?php echo $maintablealign ?>">
		  <tr>
			<td><a href="<?php echo $_SERVER['PHP_SELF'] ?>"><img src="../images/support_tickets_logo.gif" width="83" height="61" title="Triangle Solutions PHP Support Tickets" alt="Triangle Solutions PHP Support Tickets" vspace="1" border="0" /></td>
		  </tr>
		</table>

		<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
		  <tr bgcolor="<?php echo $background ?>">
			<td class="text">Login Required</td>
		  </tr>
		</table>

		<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
		<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
		  <tr>
			<td class="text" align="center"><br />
			Username: <input name="username" size="20" />
			Password: <input type="password" name="password" size="20" />
			<input type="submit" name="form" value="Log in" /><br /><br />
			</td>
		  </tr>
		</table>
		</form>
<?php
	include_once ('footer.php');

	Exit();
	}


#############################################################################################
################ MAKE SURE THE RIGHT CASEID IS ENTERED OR DEFAULT TO HOME ID ################
#############################################################################################

	IF (		!isset($_GET['caseid']) || $_GET['caseid'] == '' || $_GET['caseid'] != 'home'
			&& $_GET['caseid'] != 'AdminView' && $_GET['caseid'] != 'document'
			&& $_GET['caseid'] != 'AddUser'   && $_GET['caseid'] != 'cats'
			&& $_GET['caseid'] != 'status'    && $_GET['caseid'] != 'NewTicket'
		)
		{
		$_GET['caseid'] = 'home';
		}

	IF (($_GET['caseid'] == 'AddUser' && $_SESSION['sta_type'] != 'Admin') || ($_GET['caseid'] == 'cats' && $_SESSION['sta_type'] != 'Admin') || ($_GET['caseid'] == 'status' && $_SESSION['sta_type'] != 'Admin'))
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
		<td><a href="<?php echo $_SERVER['PHP_SELF'] ?>"><img src="../images/support_tickets_logo.gif" width="83" height="61" title="Triangle Solutions PHP Support Tickets" alt="Triangle Solutions PHP Support Tickets" vspace="2" border="0" /></td>
		<td valign="bottom" align="right" class="text" style="padding:2px">Search Tickets:
		<input name="keywords" size="24" onfocus="javascript:this.value=''" value="Search Ticket Subject" />
		<input type="submit" value="Go" />
		</td>
	  </tr>
	</table>
	</form>

	<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
	  <tr>
		<td class="boxborder list-menu" width="10%"><a href="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=home">Home</td>
		<td class="boxborder list-menu" width="10%"><a href="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=NewTicket">New Ticket</td>
		<td class="boxborder list-menu" width="10%"><a href="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=home&amp;order=Open">Open</td>
		<td class="boxborder list-menu" width="10%"><a href="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=home&amp;order=Closed">Closed</td>
<?php
	IF ($_SESSION['sta_type'] == 'Admin')
		{
?>
		<td class="boxborder list-menu" width="10%"><a href="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=AddUser">Users</td>
<?php
		}

	IF ($_SESSION['sta_type'] == 'Admin')
		{
?>
		<td class="boxborder list-menu" width="10%"><a href="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=cats">Departments</td>
<?php
		}

	IF ($_SESSION['sta_type'] == 'Admin')
		{
?>
		<td class="boxborder list-menu" width="10%" ><a href="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=status">Urgency's</td>
<?php
		}
?>
		<td class="boxborder list-menu" width="10%"><a href="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=document">Documents</td>
		<td class="boxborder list-menu" width="10%"><a href="<?php echo $_SERVER['PHP_SELF'] ?>?action=Logout">Logout</td>
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

	// PROCESS THE FUNCTIONS WHEN THE CHECKBOXES ARE CHECKED - IE OPEN / CLOSE / DELETE TICKET

			IF (isset($_POST['status']))
				{
				IF (isset($_POST['ticket']))
					{
					FOREACH ($_POST['ticket'] AS $ticketid)
						{
						IF ($_POST['status'] == 'Deleted')
							{
							$query = "	DELETE FROM tickets_tickets
									WHERE tickets_id   = '".$ticketid."'";
							}
						ELSE
							{
							$query = "	UPDATE tickets_tickets
									SET tickets_status = '".$_POST['status']."'
									WHERE tickets_id   = '".$ticketid."'";
							}

	// IF $emailclose IS TRUE THEN EMAIL THE USER WHEN THE ADMIN CLOSES THE TICKET

						IF (isset($emailclose) && $emailclose == 'TRUE' && $_POST['status'] == 'Closed')
							{

	// GET THE USER DETAILS - EMAIL / NAME OF THIS TICKET

							$query_em = "	SELECT tickets_users_name, tickets_users_email
									FROM tickets_users a, tickets_tickets b
									WHERE b.tickets_id = '".$ticketid."'
									AND b.tickets_username = a.tickets_users_username";

							$result_em = mysql_query($query_em);
							$row_em    = mysql_fetch_array($result_em);

							$message  = "Ticket ID:\t ".$ticketid." - has changed status to ".$_POST['status']."\n";
?>
							<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
							  <tr>
								<td class="text"><?php echo SendMail($row_em['tickets_users_email'], $row_em['tickets_users_name'], 'Ticket ID - '.$ticketid.' Closed', $message) ?></td>
							  </tr>
							</table>
<?php
							}

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

	// QUERY TO GET THE LATEST OPEN - CLOSED OR SEARCH ON SUBJECT

			$query = "	SELECT tickets_id, tickets_username, tickets_subject, tickets_timestamp, tickets_status, tickets_status_name, tickets_status_color, tickets_categories_name
					FROM tickets_tickets a, tickets_status b, tickets_categories c
					WHERE a.tickets_child = '0'
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

			$query .= "	ORDER BY a.tickets_id DESC, a.tickets_timestamp DESC";

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

	// ADD THE HOME VIEW TITLE TABLE
?>
			<div style="padding-top:5px"></div>
<?php
			IF ($totaltickets > '0')
				{
?>
				<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
				  <tr bgcolor="<?php echo $background ?>">
					<td class="text">Recent Tickets: <?php echo $totaltickets ?> - Click on the
					Ticket ID to read the ticket.</td>
				  </tr>
				</table>
<?php
				ShowPaging(	$_SERVER['PHP_SELF'].'?'.htmlentities($_SERVER['QUERY_STRING']),
						$prev_page,
						$next_page,
						$num_pages,
						$_GET['display']
						);
				}

			IF ($totaltickets > '0')
				{
?>
				<script language="javascript">
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
					<td class="boxborder text"><b>Username</b></td>
					<td class="boxborder text"><b>Subject</b></td>
					<td class="boxborder text"><b>Date / Time</b></td>
					<td class="boxborder text"><b>Urgency</b></td>
					<td class="boxborder text"><b>Department</b></td>
					<td class="boxborder text"><b>Status</b></td>
				  </tr>
<?php
				WHILE ($row = mysql_fetch_array($result))
					{

	// QUERY TO GET THE AMOUNT OF REPLIES TO A CERTAIN TICKET AND DATE OF LAST ENTRY

					$queryA = "	SELECT COUNT(*) AS ticket_total, MAX(tickets_timestamp), tickets_users_lastlogin
							FROM tickets_tickets a, tickets_users b
							WHERE tickets_child = '".$row['tickets_id']."'
							AND a.tickets_username = b.tickets_users_username
							GROUP BY tickets_child";

					$resultA = mysql_query($queryA);
					$rowA    = mysql_fetch_array($resultA);

					IF ($rowA['ticket_total'] <= '0')
						{
						$rowA['ticket_total'] = '0';
						}
?>
					<tr align="center" bgcolor="<?php echo UseColor() ?>">
						<td class="boxborder"><input type="checkbox" name="ticket[]" value="<?php echo $row['tickets_id'] ?>" /></td>
						<td class="boxborder list-menu"><a href="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=AdminView&amp;ticketid=<?php echo $row['tickets_id'] ?>"><?php echo $row['tickets_id'] ?></a></td>
						<td class="boxborder text">[<?php echo $rowA['ticket_total'] ?>]</td>
						<td class="boxborder text"><?php echo $row['tickets_username'] ?></td>
						<td class="boxborder text"><?php echo $row['tickets_subject'] ?></td>
						<td class="boxborder text"><?php echo date($dformat.' H:i:s', $row['tickets_timestamp']) ?></td>
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
						echo '<span>';
						}

					echo 		$row['tickets_status'].'</span></td>
						  </tr>';
					}
?>
					</td>
				  </tr>
				  <tr>
					<td colspan="8">
					<select name="status">
					<option value="Open">Open</option>
					<option value="Closed">Closed</option>
					<option value="Deleted">Delete</option>
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
			ELSE
				{
				IF (isset($_POST['keywords']))
					{
					$msg = 'Sorry but the search returned Zero results please try again.';
					}
				ELSE
					{
					$msg = 'You have no recent tickets for your account.';
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
################################ VIEW THE INDIVIDUAL TICKET #################################
#############################################################################################

		CASE 'AdminView':

	// CLOSE AND REOPEN THE TICKET SECTION

			IF (isset($_GET['closesub']) && ($_GET['closesub'] == 'Closed' || $_GET['closesub'] == 'Open'))
				{
				IF (isset($emailclose) && $emailclose == 'TRUE' && $_GET['closesub'] == 'Closed')
					{
					$message  = "Ticket ID:\t ".$_GET['ticketid']." - has changed status to ".$_GET['closesub']."\n";
?>
					<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
					  <tr>
						<td class="text"><?php echo SendMail($_GET['email'], $_GET['name'], 'Ticket ID - '.$_GET['ticketid'].' Closed', $message) ?></td>
					  </tr>
					</table>
<?php
					}

				$query = "	UPDATE tickets_tickets
						SET tickets_status = '".$_GET['closesub']."'
						WHERE tickets_id   = '".$_GET['ticketid']."'";

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

	// CHANGE THE URGENCY

			IF (isset($_GET['sub']) && $_GET['sub'] == 'urgency')
				{
				$query = "	UPDATE tickets_tickets
						SET tickets_urgency = '".$_POST['urgency']."'
						WHERE tickets_id    = '".$_GET['ticketid']."'";

				IF (mysql_query($query))
					{
					$msg = 'Ticket Urgency Updated';
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

	// AND A NEW RESPONSE AND ATTACHMENT TO THE SYSTEM

			ELSEIF (isset($_GET['sub']) && $_GET['sub'] == 'add')
				{
				IF ($_POST['message'] == '')
					{
					$msg = 'Please complete all the fields';
					}
				ELSE
					{
					$urgency  = explode('|', $_POST['posturgency']);
					$category = explode('|', $_POST['postdept']);

					$query = "	INSERT INTO tickets_tickets
							SET
							tickets_username  = '".$_POST['postuser']."',
							tickets_subject   = '".$_POST['postsubject']."',
							tickets_timestamp = '".mktime()."',
							tickets_urgency   = '".$urgency['0']."',
							tickets_category  = '".$category['0']."',
							tickets_admin     = 'Admin',
							tickets_child     = '".$_GET['ticketid']."',
							tickets_question  = '".$_POST['message']."'";

					IF ($result = mysql_query($query))
						{

	// CHECK THE FILE ATTACHMENT AND DISPLAY ANY ERRORS

						IF ($allowattachments == 'TRUE')
							{
							FileUploadsVerification("$_FILES(userfile)", mysql_insert_id());
							}
	// MAIL THE PERSON WHO STARTED THE TICKET

						$message  = "Ticket ID:\t ".$_GET['ticketid']." - This has been responded too.\n";
						$message .= "Name:\t\t ".$_POST['name']."\n";
						$message .= "Email:\t ".$_POST['email']."\n";
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
							<td class="text"><?php echo SendMail($_POST['email'], $_POST['name'], 'Response to your Support Ticket ID - '.$_GET['ticketid'], $message) ?></td>
						  </tr>
						</table>
<?php
						}
					}
				}

	// QUERY TO GET THE TICKET INFORMATION

			$query = "	SELECT tickets_id, tickets_subject, tickets_username, tickets_timestamp, tickets_status, tickets_name, tickets_email, tickets_admin, tickets_child, tickets_question, tickets_status_id, tickets_status_name, tickets_status_color, tickets_categories_id, tickets_categories_name
					FROM tickets_tickets a, tickets_status b, tickets_categories c
					WHERE (a.tickets_id = '".$_GET['ticketid']."'
					OR tickets_child = '".$_GET['ticketid']."')
					AND a.tickets_urgency = b.tickets_status_id
					AND a.tickets_category = c.tickets_categories_id
					ORDER BY tickets_id ASC";

			$result       = mysql_query($query);
			$totaltickets = mysql_num_rows($result);
			$row          = mysql_fetch_array($result);
?>
			<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
			  <tr>
				<td class="boxborder" width="50%" valign="top" style="padding-top:5px">

				<form action="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=AdminView&amp;ticketid=<?php echo $_GET['ticketid'] ?>&amp;sub=urgency" method="post">
				<table width="97%" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
				  <tr bgcolor="#AABBDD">
					<td class="boxborder text"><b>Ticket #<?php echo $_GET['ticketid'] ?> Information</b></td>
					<td class="boxborder list-menu">
<?php
				IF ($row['tickets_status'] == 'Open')
					{
?>
					<a href="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=AdminView&amp;ticketid=<?php echo $_GET['ticketid'] ?>&amp;closesub=Closed&amp;name=<?php echo urlencode($row['tickets_name']) ?>&amp;email=<?php echo $row['tickets_email'] ?>">Close Ticket
<?php
					}
				ELSE
					{
?>
					<a href="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=AdminView&amp;ticketid=<?php echo $_GET['ticketid'] ?>&amp;closesub=Open">Reopen Ticket
<?php
					}
?>
					</td>
				  </tr>
				  <tr>
					<td bgcolor="#EEEEEE" class="boxborder text"><b>Account:</b></td>
					<td class="boxborder text"><?php echo $row['tickets_username'] ?></td>
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
					<td class="boxborder text" bgcolor="#<?php echo $row['tickets_status_color'] ?>">
					<select name="urgency">
<?php
	// ALLOW THE ADMINS TO MODIFY THE TICKET URGENCY

			$query_u = "	SELECT tickets_status_id, tickets_status_name, tickets_status_color
					FROM tickets_status
					ORDER BY tickets_status_order ASC";

			$result_u = mysql_query($query_u);

			WHILE ($row_u = mysql_fetch_array($result_u))
				{
?>
				<option style="background-color:#<?php echo $row_u['tickets_status_color'] ?>"
<?php
				IF ($row_u['tickets_status_name'] == $row['tickets_status_name'])
					{
					echo ' selected ';
					}
?>
				value="<?php echo $row_u['tickets_status_id'] ?>|<?php echo $row_u['tickets_status_name'] ?>"><?php echo $row_u['tickets_status_name'] ?></option>
<?php
				}
?>
					</select> <input type="submit" value="Change" /></td>
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
				echo '<span>';
				}

			echo 		$row['tickets_status'].'</span></td>
				  </tr>';
?>
				</table>
				</form>
				<div style="padding-top:5px"></div>
<?php
			IF ($row['tickets_status'] != 'Closed')
				{
?>
				<form enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=AdminView&amp;ticketid=<?php echo $_GET['ticketid'] ?>&amp;sub=add" method="post">
				<table width="97%" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
				  <tr bgcolor="#AABBDD">
					<td class="boxborder text"><b>Respond</b></td>
				  </tr>
				  <tr>
					<td align="center"><textarea name="message" cols="80" rows="10"></textarea></td>
				  </tr>
				  <tr>
					<td align="right">
					<input type="hidden" name="name" value="<?php echo $row['tickets_name'] ?>" />
					<input type="hidden" name="postuser" value="<?php echo $row['tickets_username'] ?>" />
					<input type="hidden" name="email" value="<?php echo $row['tickets_email'] ?>" />
					<input type="hidden" name="postsubject" value="<?php echo $row['tickets_subject'] ?>" />
					<input type="hidden" name="posturgency" value="<?php echo $row['tickets_status_id'] ?>|<?php echo $row['tickets_status_name'] ?>" />
					<input type="hidden" name="postdept" value="<?php echo $row['tickets_categories_id'] ?>|<?php echo $row['tickets_categories_name'] ?>" />
					<input type="submit" value="Submit" />
					</td>
				  </tr>
				</table><div style="padding-top:5px"></div>
<?php
	// ALLOW THE USERS TO ATTACH A FILE TO THE TICKET

				IF ($allowattachments == 'TRUE')
					{
					FileUploadForm();
					}
				}
?>
				<br /></td>
				<td width="50%" valign="top" style="padding-top:5px">
<?php
	// LIST THE ASSOCIATED RESPONSES TO THIS TICKET

			$j = '0';
			$result = mysql_query($query);

			WHILE ($row = mysql_fetch_array($result))
				{
?>
				<table width="97%" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
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
					<td class="boxborder text" align="right">
<?php
	// SCAN THE UPLOAD DIRECTORY FOR ATTACHMENTS TO THIS POST

				$d = dir($uploadpath);

				WHILE (false !== ($files = $d -> read()))
					{
					IF ($files != '.' && $files != '..')
						{
						$files = explode('.', $files);

						IF ($files['0'] == $row['tickets_id'])
							{
?>
						  	<b>Attachment:</b> <?php echo $files['0'] ?>.<?php echo $files['1'] ?>
							<a href="<?php echo $relativepath.$files['0'] ?>.<?php echo $files['1'] ?>" target="_blank">
							<img src="../images/download.gif" width="13" height="13" align="absmiddle" border="0" /></a>
<?php
							$filename = $files['0'].'.'.$files['1'];
?>
							<input type="hidden" name="attachment[<?php echo $tickets_id - 1 ?>]" value="<?php echo $filename ?>" />
<?php
							}
						ELSE
							{
							$filename = '';
							}
						}
					}

				$d -> close();
?>
					</td>
				  </tr>
				</table><div style="padding-top:5px"></div>

				<input type="hidden" name="ticketquestion[]" value="<?php echo $tickets_question ?>" />
				<input type="hidden" name="postedby[]" value="<?php echo $tickets_admin ?>" />
				<input type="hidden" name="postdate[]" value="<?php echo date($dformat.' H:i:s', $tickets_timestamp) ?>" />
<?php

				$j ++;
				}

	// IF ATTACHMENTS ARE TRUE THEN SHOW ALLOWED FILETYPES

			IF ($allowattachments == 'TRUE')
				{
?>
				<table width="97%" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
				  <tr>
					<td class="text" colspan="2"><b>Allowed FILE TYPES for attachments:</b><br />
<?php
				FOR ($i = '0'; $i <= COUNT($allowedtypes) - 1; $i++)
					{
					echo $allowedtypes[$i].'<br />';
					}
?>
					</td>
				  </tr>
				</table>
<?php
				}
?>
				</td>
			  </tr>
			</table>
			</form>
<?php
			IF (isset($_GET['sub']))
				{
?>
				<meta http-equiv="refresh" content="2;url=<?php echo $_SERVER['PHP_SELF'] ?>?caseid=AdminView&amp;ticketid=<?php echo $_GET['ticketid'] ?>" />
<?php
				}

		BREAK;


#############################################################################################
############################## AREA TO ADD USER ADMINISTRATION ##############################
#############################################################################################

		CASE 'AddUser':

	// EDIT OR DELETE USER SETTINGS

			IF (isset($_REQUEST['sub']) && isset($_REQUEST['memberid']))
				{
				IF ($_REQUEST['sub'] == '1' || $_REQUEST['sub'] == '2')
					{
					$query = "	UPDATE tickets_users
							SET tickets_users_status = '".$_GET['sub']."'
							WHERE tickets_users_id   = '".$_GET['memberid']."'";

					IF ($_REQUEST['sub'] == '1')
						{
						$actiontaken = 'Activated';
						}
					ELSE
						{
						$actiontaken = 'Suspended';
						}
					}

				ELSEIF ($_POST['sub'] == 'Delete')
					{
					$query = "	DELETE FROM tickets_users
							WHERE tickets_users_id = '".$_POST['memberid']."'";

					$actiontaken = 'User Deleted';
					}

				ELSEIF ($_REQUEST['sub'] == 'Edit')
					{
					$query = "	UPDATE tickets_users
							SET
							tickets_users_name     = '".$_POST['name']."',
							tickets_users_password = '".$_POST['password']."',
							tickets_users_email    = '".$_POST['email']."',
							tickets_users_admin    = '".$_POST['type']."'
							WHERE tickets_users_id = '".$_REQUEST['memberid']."'";

					$actiontaken = 'Edited User';
					}

				$result = mysql_query($query);

				PageTitle('Support Tickets User '.$_REQUEST['memberid'].' '.$actiontaken);
				}
?>
			<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
			  <tr bgcolor="#AACCEE">
				<td class="text">Please add in all the details below for the user.</td>
			  </tr>
			</table>

			<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
			  <tr>
				<td class="text">
				The username and password must be between 6 and 16 characters in length. They cannot contain any spaces or unusual characters.<br /><br />
				You cannot edit a Username once this has been set, as the application depends on this. The username must be Unique within the system.<br /><br />
				Choose the type of the user you wish to add, selecting USER will allow a user to login to the client side only.<br /><br />
				Moderators are able to browse tickets in there department only, they are not allowed to perform admin activities.<br /><br />
				Only Admins can perform admin activities and add other users/moderators or admins.<br /><br />

				<form action="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=AddUser" Method="post">
 				<table width="300" cellpadding="1" cellspacing="1" align="center">
				  <tr>
					<td class="text">Name:</td>
					<td><input name="name" size="30"
<?php
			IF (isset($_POST['userform']) && isset($_POST['name']) && $_POST['name'] != '')
				{
				echo ' value="'.$_POST['name'].'"';
				}
			ELSE
				{
				echo ' style="background-color:#FDD3D4"';
				$error = 'T';
				}
?>
					/></td>
				  </tr>
				  <tr>
					<td class="text">Username:</td>
					<td><input name="username" size="30"
<?php
			IF (isset($_POST['userform']) && (isset($_POST['username']) && $_POST['username'] != ''))
				{
				IF (!eregi('^[0-9a-z]{6,16}$', $_POST['username']))
					{
					echo ' style="background-color:#FDD3D4"';
					$error = 'T';
					}
				ELSE
					{
					$query = "	SELECT tickets_users_id
							FROM tickets_users
							WHERE tickets_users_username = '".$_POST['username']."'
							LIMIT 0,1";

					$result = mysql_query($query);
					$total  = mysql_num_rows($result);

					IF ($total > '0')
						{
						echo ' style="background-color:#FDD3D4"';
						$error = 'T';
						}
					ELSE
						{
						echo ' value="'.$_POST['username'].'"';
						}
					}
				}
			ELSE
				{
				echo ' style="background-color:#FDD3D4"';
				$error = 'T';
				}
?>
					/></td>
				  </tr>
				  <tr>
					<td class="text">Password:</td>
					<td><input name="password" size="30"
<?php
			IF (isset($_POST['userform']) && isset($_POST['password']) && eregi('^[0-9a-z]{6,16}$', $_POST['password']) && strlen($_POST['password']) >= '6')
				{
				echo ' value="'.$_POST['password'].'"';
				}
			ELSE
				{
				echo ' style="background-color:#FDD3D4"';
				$error = 'T';
				}
?>
					/></td>
				  </tr>
				  <tr>
					<td class="text">Email:</td>
					<td><input name="email" size="30"
<?php
			IF (isset($_POST['userform']) && ereg('^.+@.+\\..+$', $_POST['email']))
				{
				echo ' value="'.$_POST['email'].'"';
				}
			ELSE
				{
				echo ' style="background-color:#FDD3D4"';
				$error = 'T';
				}
?>
					/></td>
				  </tr>
				  <tr>
					<td  class="text" align="center" colspan="2">
					User: <input checked type="radio" name="type" value="User" />
					Mod: <input type="radio" name="type" value="Mod" />
					Admin: <input type="radio" name="type" value="Admin" />
					</td>
				  </tr>
				  <tr>
					<td align="center" colspan="2"><input type="submit" name="userform" value="Submit" /></td>
				  </tr>
<?php
			IF (!isset($error))
				{
				$query = "	INSERT INTO tickets_users
						SET
						tickets_users_name     = '".$_POST['name']."',
						tickets_users_username = '".$_POST['username']."',
						tickets_users_password = '".$_POST['password']."',
						tickets_users_email    = '".$_POST['email']."',
						tickets_users_admin    = '".$_POST['type']."'";

				$result = mysql_query($query);
?>
				<tr>
					<td colspan="2"><br /><b>Everythings OK User added.</b></td>
				</tr>
<?php
				}
?>
				</table><br />
				</form>

				</td>
			  </tr>
			</table>

			<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
			  <tr>
				<td class="boxborder text" colspan="8" bgcolor="#AACCEE">Users Already In The System.</td>
			  </tr>
			  <tr bgcolor="#EEEEEE">
				<td class="boxborder text"><b>No.</b></td>
				<td class="boxborder text"><b>Name</b></td>
				<td class="boxborder text"><b>Username</b></td>
				<td class="boxborder text"><b>Password</b></td>
				<td class="boxborder text"><b>Email</b></td>
				<td class="boxborder text"><b>Type</b></td>
				<td class="boxborder text"><b>Status</b></td>
				<td class="boxborder text"><b>Action</b></td>
			  </tr>
<?php
	// LOOP THROUGH ALL EXISTING USERS IN THE DATABASE AND GIVE OPTIONS TO SUSPEND - DELETE ETC

			$query = '	SELECT  tickets_users_id, tickets_users_name, tickets_users_username,
						tickets_users_password, tickets_users_email, tickets_users_admin,
						tickets_users_status
					FROM tickets_users
					ORDER BY tickets_users_name';

			$result = mysql_query($query);

			$j = '1';

			WHILE ($row = mysql_fetch_array($result))
				{
				IF ($row['tickets_users_status'] == '1')
					{
					$status = 'Active';
					}
				ELSE
					{
					$status = 'Suspended';
					}
?>
				<form action="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=AddUser" Method="post">
				<tr bgcolor="<?php echo UseColor() ?>">
					<td class="boxborder text"><?php echo $j ?></td>
					<td class="boxborder"><input name="name" value="<?php echo $row['tickets_users_name'] ?>" size="15" /></td>
					<td class="boxborder text"><?php echo $row['tickets_users_username'] ?></td>
					<td class="boxborder"><input name="password" value="<?php echo $row['tickets_users_password'] ?>" size="17" /></td>
					<td class="boxborder"><input name="email" value="<?php echo $row['tickets_users_email'] ?>" size="35" /></td>
					<td class="boxborder"><input name="type" value="<?php echo $row['tickets_users_admin'] ?>" size="10" /></td>
					<td class="boxborder text"><?php echo $status ?></td>
					<td class="boxborder"><a href="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=AddUser&amp;
<?php
				IF ($row['tickets_users_status'] == '1')
					{
?>
					sub=2&amp;memberid=<?php echo $row['tickets_users_id'] ?>">Suspend
<?php
					}
				ELSE
					{
?>
					sub=1&amp;memberid=<?php echo $row['tickets_users_id'] ?>">Activate
<?php
					}
?>
					</a>
					<input type="submit" name="sub" value="Delete" onclick="return deletemember()" />
					<input type="hidden" name="memberid" value="<?php echo $row['tickets_users_id'] ?>">
					<input type="submit" name="sub" value="Edit" />
					</td>
				  </tr>
				</form>
<?php
				$j++;
				}
?>
			</table>
<?php
		BREAK;


#############################################################################################
####################### SHOW THE USER THE CHOICE OF DOCUMENTS TO READ #######################
#############################################################################################

		CASE 'document':

	// DEFAULT TO CHANGELOG

			IF (!isset($_GET['f']))
				{
				$_GET['f'] = 'ChangeLog';
				}
?>
			<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
			  <tr bgcolor="#AACCEE">
				<td class="text">
				[Please select a file to view from the list below. Simply click the
				radio button and hit submit.]
				</span>
				</h1></td>
			  </tr>
			  <tr>
				<td class="boxborder"><p>
				<a href="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=document&amp;f=ChangeLog">| Change Log File |</a>
				<a href="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=document&amp;f=Install">Install File |</a>
				<a href="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=document&amp;f=Licence">Licence File |</a>
				<a href="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=document&amp;f=ReadMe">ReadMe File |</a>
				<a href="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=document&amp;f=Todo">Todo File |</a>
				<a href="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=document&amp;f=Version">Version File |</a>
				<p></td>
			  </tr>
			</table>

			<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
			  <tr>
				<td>
				<iframe	src="../documents/<?php echo $_GET['f'] ?>.txt"
					frameborder="0"
					framespacing="0"
					width="100%"
					height="450">
				</iframe>
				</td>
			  </tr>
			</table>
<?php
		BREAK;


#############################################################################################
############################## DISPLAY THE CATEGORIES SECTION ###############################
#############################################################################################

		CASE 'cats':

	// EDIT OR DELETE USER SETTINGS

			IF (isset($_POST['sub']) && isset($_POST['depid']))
				{
				IF ($_POST['sub'] == 'Delete')
					{
					$query = "	DELETE FROM tickets_categories
							WHERE tickets_categories_id = '".$_POST['depid']."'";

					$actiontaken = 'Department Deleted';
					}

				ELSEIF ($_POST['sub'] == 'Edit')
					{
					$query = "	UPDATE tickets_categories
							SET
							tickets_categories_name     = '".$_POST['department']."'
							WHERE tickets_categories_id = '".$_POST['depid']."'";

					$actiontaken = 'Edited';
					}

				$result = mysql_query($query);

				PageTitle('Department '.$_POST['depid'].' '.$actiontaken);
				}
?>
			<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
			  <tr bgcolor="#AACCEE">
				<td class="text">Please add in all the details below for the each department.</td>
			  </tr>
			</table>

			<form action="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=cats" method="post">
			<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
			  <tr>
				<td class="text"><br />Be careful about deleting departments. Deleting them will cause
				errors with all tickets assigned to that particular department. Therefore be careful
				when you add them, make sure they are concise and what you want.<br /><br />

				<table width="350" cellpadding="0" cellspacing="0" align="center">
				  <tr>
					<td class="text">Department Name:</td>
					<td><input name="name" size="30"
<?php
			IF (isset($_POST['userform']) && isset($_POST['name']) && $_POST['name'] != '')
				{
				echo ' value="'.$_POST['name'].'"';
				}
			ELSE
				{
				echo ' style="background-color:#FDD3D4"';
				$error = 'T';
				}
?>
					/> <input type="submit" value="Submit" name="userform" /></td>
				  </tr>
<?php
			IF (!isset($error))
				{
				$query = "	INSERT INTO tickets_categories
						SET
						tickets_categories_name = '".$_POST['name']."'";

				$result = mysql_query($query);
?>
				<tr>
					<td class="text" colspan="2"><b>Everythings OK Department added.</b></td>
				</tr>
<?php
				}
?>
				</table><br />

				</td>
			  </tr>
			</table>
			</form>

			<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
			  <tr>
				<td class="boxborder text" colspan="3" bgcolor="#AACCEE">Departments Already In The System.</td>
			  </tr>
			  <tr bgcolor="#EEEEEE">
				<td class="boxborder text"><b>ID.</b></td>
				<td class="boxborder text"><b>Department</b></td>
				<td class="boxborder text"><b>Action</b></td>
			  </tr>
<?php
	// LOOP THROUGH ALL EXISTING USERS IN THE DATABASE AND GIVE OPTIONS TO SUSPEND - DELETE ETC

			$query = '	SELECT tickets_categories_id, tickets_categories_name
					FROM tickets_categories
					ORDER BY tickets_categories_id ASC';

			$result = mysql_query($query);

			WHILE ($row = mysql_fetch_array($result))
				{
?>
				<form action="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=cats" Method="post">
				<tr bgcolor="<?php echo UseColor() ?>">
					<td class="boxborder"><?php echo $row['tickets_categories_id'] ?></td>
					<td class="boxborder"><input name="department" value="<?php echo $row['tickets_categories_name'] ?>" size="40" /></td>
					<td class="boxborder">
					<input type="submit" name="sub" value="Delete" onclick="return deletemember()" />
					<input type="hidden" name="depid" value="<?php echo $row['tickets_categories_id'] ?>">
					<input type="submit" name="sub" value="Edit" />
					</td>
				  </tr>
				</form>
<?php
				}
?>
			</table>
<?php
		BREAK;


#############################################################################################
################################ DISPLAY THE STATUS SECTION #################################
#############################################################################################

		CASE 'status':

	// EDIT OR DELETE USER SETTINGS

			IF (isset($_POST['sub']) && isset($_POST['depid']))
				{
				IF ($_POST['sub'] == 'Delete')
					{
					$query = "	DELETE FROM tickets_status
							WHERE tickets_status_id = '".$_POST['depid']."'";

					$actiontaken = 'Status Deleted';
					}

				ELSEIF ($_POST['sub'] == 'Edit')
					{
					$query = "	UPDATE tickets_status
							SET
							tickets_status_name     = '".$_POST['status']."',
							tickets_status_order    = '".$_POST['order']."',
							tickets_status_color    = '".$_POST['color']."'
							WHERE tickets_status_id = '".$_POST['depid']."'";

					$actiontaken = 'Edited';
					}

				$result = mysql_query($query);

				PageTitle('Status '.$_POST['depid'].' '.$actiontaken);
				}
?>
			<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
			  <tr bgcolor="#AACCEE">
				<td class="text">Please add in all the details below for the each Urgency.</td>
			  </tr>
			</table>

			<form action="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=status" method="post">
			<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
			  <tr>
				<td class="text"><br />Be careful about deleting Urgency's. Deleting them will cause
				errors with all tickets assigned to that particular status. Therefore be careful
				when you add them, make sure they are concise and what you want. Order refers
				to where in the list it will appear, 1 being first.<br /><br />

				<table width="300" cellpadding="0" cellspacing="0" align="center">
				  <tr>
					<td class="text">Urgency Name:</td>
					<td><input name="name" size="30"
<?php
			IF (isset($_POST['userform']) && isset($_POST['name']) && $_POST['name'] != '')
				{
				echo ' value="'.$_POST['name'].'"';
				}
			ELSE
				{
				echo ' style="background-color:#FDD3D4"';
				$error = 'T';
				}
?>
					/> <input type="submit" value="Submit" name="userform" /></td>
				  </tr>
<?php
			IF (!isset($error))
				{
				$query = "	INSERT INTO tickets_status
						SET
						tickets_status_name = '".$_POST['name']."'";

				$result = mysql_query($query);
?>
				<tr>
					<td class="text" colspan="2"><b>Everythings OK Status added.</b></td>
				</tr>
<?php
				}
?>
				</table><br />

				</td>
			  </tr>
			</table>
			</form>

			<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
			  <tr>
				<td class="boxborder text" colspan="5" bgcolor="#AACCEE">Urgent Elements Already In The System.</td>
			  </tr>
			  <tr bgcolor="#EEEEEE">
				<td class="boxborder text"><b>ID.</b></td>
				<td class="boxborder text"><b>Urgency</b></td>
				<td class="boxborder text"><b>Order</b></td>
				<td class="boxborder text"><b>Color</b></td>
				<td class="boxborder text"><b>Action</b></td>
			  </tr>
<?php
	// LOOP THROUGH ALL EXISTING USERS IN THE DATABASE AND GIVE OPTIONS TO SUSPEND - DELETE ETC

			$query = '	SELECT tickets_status_id, tickets_status_name, tickets_status_order, tickets_status_color
					FROM tickets_status
					ORDER BY tickets_status_id ASC';

			$result = mysql_query($query);

			WHILE ($row = mysql_fetch_array($result))
				{
?>
				<form action="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=status" Method="post">
				<tr bgcolor="<?php echo UseColor() ?>">
					<td class="boxborder text"><?php echo $row['tickets_status_id'] ?></td>
					<td class="boxborder"><input name="status" value="<?php echo $row['tickets_status_name'] ?>" size="40" /></td>
					<td class="boxborder"><input name="order" value="<?php echo $row['tickets_status_order'] ?>" size="20" /></td>
					<td class="boxborder text" bgcolor="#<?php echo $row['tickets_status_color'] ?>"><input name="color" value="<?php echo $row['tickets_status_color'] ?>" size="20" /></td>
					<td class="boxborder">
					<input type="submit" name="sub" value="Delete" onclick="return deletemember()" />
					<input type="hidden" name="depid" value="<?php echo $row['tickets_status_id'] ?>">
					<input type="submit" name="sub" value="Edit" />
					</td>
				  </tr>
				</form>
<?php
				}
?>
			</table>
<?php
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
					  </tr>
					</table>
<?php
					}

	// IF FORM IS OK THEN INSERT INTO THE DATABASE

				ELSE
					{
					IF (!isset($_POST['ticket_status']) || $_POST['ticket_status'] == '')
						{
						$_POST['ticket_status'] = 'Open';
						}

					$_POST['account']	= Clean_It($_POST['account']);
					$_POST['name']		= Clean_It($_POST['name']);
					$_POST['email']		= Clean_It($_POST['email']);
					$_POST['ticketsubject']	= Clean_It($_POST['ticketsubject']);
					$_POST['category']	= Clean_It($_POST['category']);
					$_POST['urgency']	= Clean_It($_POST['urgency']);
					$_POST['ticket_status']	= Clean_It($_POST['ticket_status']);
					$_POST['message']	= Clean_It($_POST['message']);

					$urgency  = explode('|', $_POST['urgency']);
					$category = explode('|', $_POST['category']);

					$query = "	INSERT INTO tickets_tickets
							SET
							tickets_username  = '".$_POST['account']."',
							tickets_subject	  = '".$_POST['ticketsubject']."',
							tickets_timestamp = '".mktime()."',
							tickets_status    = '".$_POST['ticket_status']."',
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
								'Support Ticket Written By - '.$_POST['account'],
								$message);

						IF ($emailuser == 'TRUE')
							{
							echo SendMail(	$_POST['email'],
									$_POST['name'],
									'Support Ticket Written By - '.$_POST['account'],
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

	// SELECT THE DIFFERENT FROM LOCATIONS ON WHETHER THE ACCOUNT IS CHOSEN

			IF (!isset($_POST['account']))
				{
?>
				<form action="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=NewTicket" method="post">
<?php
				}
			ELSE
				{
?>
				<form enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=NewTicket&amp;sub=add" method="post">
<?php
				}
?>
			<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
			  <tr>
				<td class="boxborder" width="50%" valign="top" style="padding-top:5px">

				<table width="97%" cellspacing="1" cellpadding="1" class="boxborder" align="center">
				  <tr bgcolor="#AABBDD">
					<td class="boxborder text" colspan="2"><b>New Support Ticket - All Fields Required</b></td>
				  </tr>
				  <tr>
					<td bgcolor="#EEEEEE" class="boxborder text"><b>Account:</b></td>
					<td class="boxborder text">
					<select name="account">
<?php
	// LIST THE AVAILABLE ACCOUNT MEMBERS

			$query = "	SELECT tickets_users_name, tickets_users_username, tickets_users_email
					FROM tickets_users";

			IF (isset($_POST['account']))
				{
				$query .= " WHERE tickets_users_username = '".$_POST['account']."'
					    LIMIT 0,1";
				}
			ELSE
				{
				$query .= " WHERE tickets_users_admin = 'User'";
				}

			$result = mysql_query($query);

			WHILE ($row = mysql_fetch_array($result))
				{
?>
				<option value="<?php echo $row['tickets_users_username'] ?>"><?php echo $row['tickets_users_username'] ?></option>
<?php
				}
?>
					</select>
					</td>
				  </tr>
<?php
	// DONT SHOW THE REST OF THE FORM UNTIL THEY HAVE SELECTED THE ACCOUNT

			IF (isset($_POST['account']))
				{
				$result = mysql_query($query);
				$row    = mysql_fetch_array($result);
?>
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
				  <tr>
					<td bgcolor="#EEEEEE" class="boxborder text"><b>Pre-Close:</b></td>
					<td class="boxborder text"><input type="checkbox" name="ticket_status" value="Closed" /></td>
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
<?php
				}
?>
				  <tr>
					<td align="right" <?php IF (!isset($_POST['account'])) echo 'colspan="2"'; ?>><input type="submit" value="Submit" /></td>
				  </tr>
<?php
	// TEXT TO TELL ADMIN WHAT TO DO WITH A NEW TICKET

			IF (!isset($_POST['account']))
				{
?>
				  <tr>
					<td class="text" colspan="2">
					Firstly you must assign this ticket to an already active account. If the
					user is not active then please add them <a href="<?php echo $_SERVER['PHP_SELF'] ?>?caseid=AddUser" title="add user">here</a>.
					</td>
				  </tr>
<?php
				}
?>
				</table><div style="padding-top:5px"></div>
<?php
	// ALLOW THE USERS TO ATTACH A FILE TO THE TICKET

			IF (isset($_POST['account']) && $allowattachments == 'TRUE' && (!isset($_COOKIE['demomode']) || $demomode != 'ON'))
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
				<meta http-equiv="refresh" content="2;URL=<?php echo $_SERVER['PHP_SELF'] ?>?caseid=AdminView&amp;ticketid=<?php echo $lastinsertid ?>" />
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
