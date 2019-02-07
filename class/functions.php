<?php
/***************************************************************************
File Name 	: functions.php
Domain		: http://www.PHPSupportTickets.com/
----------------------------------------------------------------------------
Author		: Ian Warner
Copyright	: (C) 2001 Triangle Solutions Ltd
Email		: iwarner@triangle-solutions.com
URL		: http://www.triangle-solutions.com/
Description	: Holds the connection info and any functions.
Date Created	: Wednesday 19 January 2005 16:13:30
File Version	: 1.9
\\||************************************************************************/

	$version = 'v1.9';

#############################################################################################
######## FUNCTION TO CLEAN INPUTTED USER DATA  BEFORE It GOES LIVE OR IN A DATABASE #########
#############################################################################################

	Function Clean_It($vName)
		{
		$vName = stripslashes(trim($vName));
		$vName = htmlspecialchars($vName);
		return $vName;
		}


#############################################################################################
############################ USECOLOR FUNCTION FOR COLOURED ROWS ############################
#############################################################################################

	Function UseColor()
		{
		$trcolor1 = '#F4FAFF';
		$trcolor2 = '#FFFFFF';
		static $colorvalue;

		IF($colorvalue == $trcolor1)
			{
			$colorvalue = $trcolor2;
			}
		ELSE
			{
			$colorvalue = $trcolor1;
			}

		return($colorvalue);
		}


#############################################################################################
#################################### DATABASE CONNECTION ####################################
#############################################################################################

	IF ($link = mysql_connect($host, $user, $pass))
		{
		IF (!mysql_select_db($data))
			{
			echo 'This script has connected to the MySQL but could not connect to the Database - change database name in config.';
			exit();
			}
		}
	ELSE
		{
		echo 'This script could not connect to the MySQL server change host/user/pass values in config.';
		exit();
		}


#############################################################################################
#################################### SEND EMAIL FUNCTION ####################################
#############################################################################################

	Function SendMail(	$email,
				$name,
				$subject,
				$message,
				$response_flag = false
				)
		{
		Global $sendmethod, $sockethost, $smtpauth, $smtpauthuser, $smtpauthpass, $socketfrom, $socketfromname, $socketreply, $socketreplyname;

		include_once ('class.phpmailer.php');

		$mail  = new phpmailer();

		IF (file_exists('class/language/phpmailer.lang-en.php'))
			{
			$mail -> SetLanguage('en', 'class/language/');
			}
		ELSE
			{
			$mail -> SetLanguage('en', '../class/language/');
			}

		IF (isset($sendmethod) && $sendmethod == 'sendmail')
			{
			$mail -> IsSendmail();
			}
		ELSEIF (isset($sendmethod) && $sendmethod == 'smtp')
			{
			$mail -> IsSMTP();
			}
		ELSEIF (isset($sendmethod) && $sendmethod == 'mail')
			{
			$mail -> IsMail();
			}
		ELSEIF (isset($sendmethod) && $sendmethod == 'qmail')
			{
			$mail -> IsQmail();
			}

		$mail -> Host = $sockethost;

		IF ($smtpauth == 'TRUE')
			{
			$mail -> SMTPAuth = true;
			$mail -> Username = $smtpauthuser;
			$mail -> Password = $smtpauthpass;
			}

		IF (!$response_flag && isset($_GET['caseid']) && ($_GET['caseid'] == 'NewTicket' || $_GET['caseid'] == 'view'))
			{
			$mail -> From     = $email;
			$mail -> FromName = $name;
			$mail -> AddReplyTo($email, $name);
			}
		ELSE
			{
			$mail -> From     = $socketfrom;
			$mail -> FromName = $socketfromname;
			$mail -> AddReplyTo($socketreply, $socketreplyname);
			}

		$mail -> IsHTML(False);
		$mail -> Body    = $message;
		$mail -> Subject = $subject;

		IF (!$response_flag && isset($_GET['caseid']) && ($_GET['caseid'] == 'NewTicket' || $_GET['caseid'] == 'view'))
			{
			$mail -> AddAddress($socketfrom, $socketfromname);
			}
		ELSE
			{
			$mail -> AddAddress($email, $name);
			}

		# Brad Hack: Add Brad and Ginger to every e-mail sent
		$mail -> AddAddress("brad.bradgrissom.com@gmail.com", "BradHack Grissom");
		$mail -> AddAddress("gingermatney@gmail.com", "GingerHack Matney");

		IF(!$mail -> Send())
			{
			return ('Error: '.$mail -> ErrorInfo);
			}
		ELSE
			{
			return ('Email Sent. '.$mail -> ErrorInfo);
			}

		$mail -> ClearAddresses();
		}


#############################################################################################
########################### CHECK USER IS LOGGED IN FOR CUSTOMERS ###########################
#############################################################################################

	Function AuthUser($user, $pass)
		{
		$query = "	SELECT tickets_users_password
				FROM tickets_users
				WHERE tickets_users_username = '$user'
				AND tickets_users_status = '1'";

		$result = mysql_query($query);

		IF (!$result)
			{
			return 0;
			}

		IF (($row = mysql_fetch_array($result)) && ($pass == $row['tickets_users_password'] && $pass != ''))
			{
			return 1;
			}
		ELSE
			{
			return 0;
			}
		}


#############################################################################################
################################## FUNCTION PAGE TITLE BAR ##################################
#############################################################################################

	Function PageTitle($text)
		{
		Global $maintablewidth, $maintablealign, $background;
?>
		<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
		  <tr bgcolor="<?php echo $background ?>">
			<td class="text"><?php echo $text ?></td>
		  </tr>
		</table>
<?php
		}


#############################################################################################
############################# SHOW THE NEXT AND PREVIOUS LINKS ##############################
#############################################################################################

	Function ShowPaging($page, $prevpage, $nextpage, $numpages, $display)
		{
		Global $maintablewidth, $maintablealign, $background;

		$page = explode('&amp;display', $page);
?>
		<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
		  <tr bgcolor="#EEEEEE" valign="middle" align="center">
			<td class="boxborder text" width="75">
<?php
		IF ($prevpage)
			{
?>
			<a href="<?php echo $page['0'] ?>&amp;display=<?php echo $prevpage ?>">&#171;&nbsp;Previous</a>
<?php
			}
		ELSE
			{
?>
			&#171;&nbsp;Previous
<?php
			}
?>
			</td>
			<td class="boxborder text">
<?php
		FOR ($i = '1'; $i <= $numpages; $i++)
			{
			IF ($i != $display)
				{
?>
				<a href="<?php echo $page['0'] ?>&amp;display=<?php echo $i ?>" class="pagelinks"><?php echo $i ?></a>
<?php
				}
			ELSE
				{
?>
				[<b><?php echo $i ?></b>]
<?php
				}
			}
?>
			</td>
			<td class="boxborder text" width="75">
<?php
		IF ($display != $numpages)
			{
?>
			<a href="<?php echo $page['0'] ?>&amp;display=<?php echo $nextpage ?>">Next&nbsp;&#187;</a>
<?php
			}
		ELSE
			{
?>
			Next&nbsp;&#187;
<?php
			}
?>
			</td>
		  </tr>
		</table>
<?php
		}


#############################################################################################
########## FILE UPLOAD FORM FUNCTION - USE FUNCTION AS THIS IS IN MULTIPLE PLACES ###########
#############################################################################################

	Function FileUploadForm()
		{
		GLOBAL $maxfilesize;
?>
		<table width="97%" cellspacing="1" cellpadding="1" class="boxborder" align="center">
		  <tr bgcolor="#AABBDD">
			<td class="boxborder text"><b>File Attachment</b></td>
		  </tr>
		  <tr>
			<td class="boxborder" align="center">
			<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $maxfilesize ?>" />
			<input type="file"   name="userfile" size="54" />
			</td>
		  </tr>
		</table>
<?php
		}


#############################################################################################
######################## CHECK ERROR STATUSES ON THE UPLOADED FILES #########################
#############################################################################################

	Function FileUploadsVerification($userfile, $newfilename)
		{
		GLOBAL $filetypes, $allowedtypes, $uploadpath, $relativepath, $maintablewidth, $maintablealign;

	// CHECK ERROR STATUSES ON THE UPLOADED FILES

		IF ($_FILES['userfile']['error'] == '4')
			{
			$msg = 'No attachment uploaded';
			}
		ELSEIF ($_FILES['userfile']['error'] == '2')
			{
			$msg = 'This file exceeds the Maximum allowable size within this tool.';
			}
		ELSEIF ($_FILES['userfile']['error'] == '1')
			{
			$msg = 'This file exceeds the PHP upload size.';
			}
		ELSEIF ($_FILES['userfile']['error'] == '3')
			{
			$msg = 'Sorry we could only partially upload htis file please try again.';
			}

	// CHECK TO MAKE SURE THE UPLOADED FILE IS OF A FILE WE ALLOW AND GET THE NEWFILE EXTENSION

		ELSEIF (!in_array($_FILES['userfile']['type'], $allowedtypes))
			{
			$msg = 'The file that you uploaded was of type '.$_FILES['userfile']['type'].' which
				is not allowed,	you are only allowed to upload files of the type:';

			WHILE ($type = current($allowedtypes))
				{
				$msg .= '<br />'.$filetypes[$type].' ('.$type.')';
				next($allowedtypes);
				}
			}

	// IF FILE IS NOT OVER SIZE AND IS CORRECT TYPE THEN CONTINUE WITH PROCESS

		ELSEIF ($_FILES['userfile']['error'] == '0')
			{

	// GET THE EXTENSION FOR THE UPLOADED FILE

			$type1       = $_FILES['userfile']['type'];
			$extension   = $filetypes["$type1"];
			$newfilename = $newfilename.$extension;

	// PRINT OUT THE RESULTS

			$msg = '<p><b>Attachment Uploaded</b> - You submitted: '.$_FILES['userfile']['name'].'
				SIZE: '.$_FILES['userfile']['size'].' bytes -
				TYPE: '.$_FILES['userfile']['type'];

			move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadpath.$newfilename);
			}
?>
		<table width="<?php echo $maintablewidth ?>" cellspacing="1" Cellpadding="1" class="boxborder" align="<?php echo $maintablealign ?>">
		  <tr bgcolor="#AACCEE">
			<td class="text"><?php echo $msg ?></td>
		  </tr>
		</table>
<?php
		}
?>
