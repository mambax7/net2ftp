<?php

//   -------------------------------------------------------------------------------
//  |                  net2ftp: a web based FTP client                              |
//  |              Copyright (c) 2003-2004 by David Gartner                         |
//  |                                                                               |
//  | This program is free software; you can redistribute it and/or                 |
//  | modify it under the terms of the GNU General Public License                   |
//  | as published by the Free Software Foundation; either version 2                |
//  | of the License, or (at your option) any later version.                        |
//  |                                                                               |
//   -------------------------------------------------------------------------------




// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **

function net2ftp_loginform($net2ftp_directory, $net2ftp_url) {

// -------------------------------------------------------------------------
// This function prints the net2ftp login form.
//
// ----- VARIABLES -----
// $includes_directory = directory where the net2ftp includes are located, for example 
//                          /usr/web/net2ftp/includes
//                       Note that there is a leading / but no trailing / at the end.
// $net2ftp_url        = URL of net2ftp on your website, for example
//                          http://www.mydomain.com/tools/net2ftp/index.php
//
// ----- STYLE -----
// The look of the login form can be customized with CSS (cascading style sheets) classes.
// The input textboxes use class "input" and the login button uses class "button".
// -------------------------------------------------------------------------


// -------------------------------------------------------------------------
// Includes
// -------------------------------------------------------------------------

// The if is necessary, because on some PHP installations the files below 
// are included twice anyway, despite the _once, and this generates an error...
	if (function_exists("getSetting") == false) {
		require_once($net2ftp_directory . "/settings.inc.php");
		require_once($net2ftp_directory . "/settings_authorizations.inc.php");
		require_once($net2ftp_directory . "/includes/errorhandling.inc.php");
		require_once($net2ftp_directory . "/includes/languages.inc.php");
		require_once($net2ftp_directory . "/includes/skins.inc.php");
	}


// -------------------------------------------------------------------------
// Global variables and settings
// -------------------------------------------------------------------------
	global $HTTP_COOKIE_VARS, $HTTP_SERVER_VARS;

	$show_beta = getSetting("show_beta");

	$net2ftp_allowed_ftpservers    = getAuthorization("net2ftp_allowed_ftpservers");
	$net2ftp_allowed_ftpserverport = getAuthorization("net2ftp_allowed_ftpserverport");
	$logindirectory                = getAuthorization("logindirectory");

	$net2ftpcookie_ftpserver     = remove_magic_quotes(&$HTTP_COOKIE_VARS['net2ftpcookie_ftpserver']);
	$net2ftpcookie_ftpserverport = remove_magic_quotes(&$HTTP_COOKIE_VARS['net2ftpcookie_ftpserverport']);
	$net2ftpcookie_username      = remove_magic_quotes(&$HTTP_COOKIE_VARS['net2ftpcookie_username']);
	$net2ftpcookie_directory     = remove_magic_quotes(&$HTTP_COOKIE_VARS['net2ftpcookie_directory']);
	$net2ftpcookie_language      = remove_magic_quotes(&$HTTP_COOKIE_VARS['net2ftpcookie_language']);
	$net2ftpcookie_skin          = remove_magic_quotes(&$HTTP_COOKIE_VARS['net2ftpcookie_skin']);
	$net2ftpcookie_passivemode   = remove_magic_quotes(&$HTTP_COOKIE_VARS['net2ftpcookie_passivemode']);
	$net2ftpcookie_sslconnect    = remove_magic_quotes(&$HTTP_COOKIE_VARS['net2ftpcookie_sslconnect']);
	$net2ftpcookie_viewmode      = remove_magic_quotes(&$HTTP_COOKIE_VARS['net2ftpcookie_viewmode']);

	$PHP_SELF = $HTTP_SERVER_VARS['PHP_SELF'];


// -------------------------------------------------------------------------
// Begin output
// -------------------------------------------------------------------------
	echo "\n\n\n\n\n";
	echo "<!-- This login form is generated by the net2ftp function net2ftp_loginform() -->\n";
	echo "<!-- net2ftp's main code is free software, released under the GNU/GPL license -->\n";


// -------------------------------------------------------------------------
// Javascript
// -------------------------------------------------------------------------
		echo "<script type=\"text/javascript\"><!--\n";

// Check that some values are entered on the input form
		echo "function CheckInput(form) {\n";
		echo "	var u,p1,p2,e;\n";
		echo "	s=form.input_ftpserver.value;\n";
		echo "	u=form.input_username.value;\n";
		echo "	p=form.input_password.value;\n";

		echo "	if (s.length==0) {\n";
		echo "		form.input_ftpserver.focus();\n";
		echo "		alert(\"Please enter an FTP server.\");\n";
		echo "		return false;\n";
		echo "	}\n";

		echo "	if (u.length==0) {\n";
		echo "		form.input_username.focus();\n";
		echo "		alert(\"Please enter a username.\");\n";
		echo "		return false;\n";
		echo "	}\n";

//		echo "	if (p.length==0) {\n";
//		echo "		form.input_password.focus();\n";
//		echo "		alert(\"Please enter a password.\");\n";
//		echo "		return false;\n";
//		echo "	}\n";

		echo "}\n";

// Anonymous login
		echo "function do_anonymous(form) {\n";
		echo "	var checked = form.anonymous.checked;\n";
		echo "	if (checked == true) {\n";
		echo "	vars_defined = 'true';\n";
		echo "		last_username = form.input_username.value;\n";
		echo "		form.input_username.value = \"anonymous\";\n";
		echo "		last_password = form.input_password.value;\n";
		echo "		form.input_password.value = \"user@net2ftp\";\n";
		echo "	} else {\n";
		echo "		form.input_username.value = last_username;\n";
		echo "		form.input_password.value = last_password;\n";
		echo "	}\n";
		echo "	return true;\n";
		echo "}\n";
		
// Clear Cookies
		echo "function ClearCookies() {\n";
		echo "	document.LoginForm.state.value='homepage';\n";
		echo "	document.LoginForm.state2.value='';\n";
		echo "	document.LoginForm.cookiesetonlogin.value='clear';\n";
		echo "	document.LoginForm.submit();\n";
		echo "}\n";

		echo "//--></script>\n";

// -------------------------------------------------------------------------
// Form and table start
// -------------------------------------------------------------------------
	echo "<form name=\"LoginForm\" id=\"LoginForm\" action=\"$net2ftp_url\" method=\"post\" onSubmit=\"return CheckInput(this);\">\n";
	echo "<div style=\"border: 1px solid black; background-color: #DDDDDD; margin-top: 10px; padding: 10px; width: 400px;\">\n";
	echo "<table border=\"0\">\n";

	echo "<tr>\n";



// -------------------------------------------------------------------------
// Row 1: FTP server + port + explanation
// -------------------------------------------------------------------------

// ---------------------------------------
// FTP server
// ---------------------------------------
	echo "<td valign=\"top\">FTP server</td>\n";
	echo "<td valign=\"top\" colspan=\"2\">\n";

	if ($net2ftp_allowed_ftpservers[1] != "ALL" && sizeof($net2ftp_allowed_ftpservers) > 1) {
		echo "<select name=\"input_ftpserver\">\n";
		for ($i=1; $i<=sizeof($net2ftp_allowed_ftpservers); $i=$i+1) {
			// Select the first entry by default
			if ($i == 1) { $selected = "selected"; }
			else { $selected = ""; }

			echo "<option value=\"$net2ftp_allowed_ftpservers[$i]\" $selected>$net2ftp_allowed_ftpservers[$i]</option>\n";
		} // end for
		echo "</select>\n";
	}
	elseif ($net2ftp_allowed_ftpservers[1] != "ALL" && sizeof($net2ftp_allowed_ftpservers) == 1) { 
		echo "<input type=\"hidden\" name=\"input_ftpserver\" value=\"$net2ftp_allowed_ftpservers[1]\" />\n"; echo "<b>$net2ftp_allowed_ftpservers[1]</b>\n"; 
	}
	else { 
		echo "<input type=\"text\" class=\"input\" name=\"input_ftpserver\" value=\"$net2ftpcookie_ftpserver\" />\n";
	}

// ---------------------------------------
// FTP server port
// ---------------------------------------
	if ($net2ftp_allowed_ftpserverport == "ALL") {
		if ($net2ftpcookie_ftpserverport != "") {
			echo " port <input type=\"text\" class=\"input\" size=\"3\" maxlength=\"5\" name=\"input_ftpserverport\" value=\"$net2ftpcookie_ftpserverport\" />\n";
		}
		else {
			echo " port <input type=\"text\" class=\"input\" size=\"3\" maxlength=\"5\" name=\"input_ftpserverport\" value=\"21\" />\n";
		}
	}
	else {
		echo "<input type=\"hidden\" name=\"input_ftpserverport\" value=\"$net2ftp_allowed_ftpserverport\" />\n";
	}

// ---------------------------------------
// Explanation
// ---------------------------------------
	if ($net2ftp_allowed_ftpservers[1] == "ALL") { 
		echo "<div style=\"font-size: 65%;\">\n";
		echo "Example: ftp.server.com or 192.123.45.67\n";
		echo "</div>\n";
	}

	echo "</td>\n";
	echo "</tr>\n";


// -------------------------------------------------------------------------
// Row 2: username
// -------------------------------------------------------------------------
	echo "<tr>\n";
	echo "<td>Username</td>\n";
	echo "<td><input type=\"text\" class=\"input\" name=\"input_username\" value=\"$net2ftpcookie_username\" /></td>\n";
	echo "<td><span style=\"font-size: 65%;\"><input type=\"checkbox\" name=\"anonymous\" value=\"1\" onclick=\"do_anonymous(form);\" $input_anonymous_checked/> Anonymous</span></td>\n";
	echo "</tr>\n";


// -------------------------------------------------------------------------
// Row 3: password
// -------------------------------------------------------------------------
	echo "<tr>\n";
	echo "<td>Password</td>\n";
	echo "<td><input type=\"password\" class=\"input\" name=\"input_password\" $input_password_value/></td>\n";
	if ($net2ftpcookie_passivemode == "yes") { $input_passivemode_checked = "checked"; }
	echo "<td><span style=\"font-size: 65%;\"><input type=\"checkbox\" name=\"input_passivemode\" value=\"yes\" /> Passive mode</span></td>\n";
	echo "</tr>\n";


// -------------------------------------------------------------------------
// Row 4: directory
// -------------------------------------------------------------------------
	if (strlen($net2ftpcookie_directory) > 1) { $input_directory = $net2ftpcookie_directory; }
	else                                      { $input_directory = $logindirectory; }

	echo "<tr>\n";
	echo "<td>Initial directory</td>\n";
	echo "<td><input type=\"text\" class=\"input\" name=\"input_directory\" value=\"$input_directory\" /></td>\n";

// SSL connect
	if (!function_exists("ftp_ssl_connect"))     { $input_sslconnect_status = "disabled"; }
	else if ($net2ftpcookie_sslconnect == "yes") { $input_sslconnect_status = "checked"; }
	echo "<td><span style=\"font-size: 65%;\"><input type=\"checkbox\" name=\"input_sslconnect\" value=\"yes\" $input_sslconnect_status/> SSL Connect</span></td>\n";
	echo "</tr>\n";

// -------------------------------------------------------------------------
// Row 5-6: optional entries
// -------------------------------------------------------------------------
	if ($show_beta == "yes") {
		echo "<tr>\n";
		echo "<td>Language</td>\n";
		echo "<td>\n";
		printLanguageSelect("input_language", "");
		echo "</td>\n";
		echo "<td></td>\n";
		echo "</tr>\n";
	}

	echo "<tr>\n";
//	echo "<td>Skin</td>\n";
//	echo "<td>\n";
//	printSkinSelect("input_skin", "");
	echo "</td>\n";
	echo "<td></td>\n";
	echo "</tr>\n";


// -------------------------------------------------------------------------
// Row 7: login button
// -------------------------------------------------------------------------
	echo "<tr><td colspan=\"3\">\n";
	echo "<input type=\"hidden\" name=\"state\" value=\"browse\" />\n";
	echo "<input type=\"hidden\" name=\"state2\" value=\"main\" />\n";
	echo "<input type=\"hidden\" name=\"cookiesetonlogin\" value=\"yes\" />\n";
	echo "<div style=\"text-align: center; margin-top: 10px;\">\n";
	echo "<input type=\"submit\" class=\"button\" value=\"Login\" />\n";
	echo "</div>\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "<tr><td colspan=\"3\" align=\"center\">\n";
	echo "<span style=\"font-size: 70%;\"><a href=\"javascript:ClearCookies();\">Clear Cookies</a></span></td>\n";
	echo "</td></tr>\n";

	echo "</table>\n";
	echo "</div>\n";
	echo "</form>\n";


// -------------------------------------------------------------------------
// Set focus on the first input textbox
// -------------------------------------------------------------------------
	echo "<script type=\"text/javascript\"><!--\n";
	if   ($net2ftp_allowed_ftpservers[1] == "ALL") { echo "document.LoginForm.input_ftpserver.focus();\n"; }
	else                                           { echo "document.LoginForm.input_username.focus();\n"; }
	echo "//--></script>\n";

// -------------------------------------------------------------------------
// End output
// -------------------------------------------------------------------------
	echo "<!-- End of net2ftp login form -->\n";
	echo "\n\n\n\n\n";

} // End function net2ftp_loginform

// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************





// **************************************************************************************
// **************************************************************************************
// **                                                                                  **
// **                                                                                  **

function remove_magic_quotes(&$x, $keyname="") {

	// http://www.php.net/manual/en/configuration.php#ini.magic-quotes-gpc (by the way: gpc = get post cookie)
	// if (magic_quotes_gpc == 1), then PHP converts automatically " --> \", ' --> \'
	// Has only to be done when getting info from get post cookie
	if (get_magic_quotes_gpc() == 1) {

		if (is_array($x)) {
			while (list($key,$value) = each($x)) {
				if ($value) { remove_magic_quotes(&$x[$key],$key); }
			}
		}
		else { 
			$quote = "'";
			$doublequote = "\"";
			$backslash = "\\";

			$x = str_replace("$backslash$quote", $quote, $x);
			$x = str_replace("$backslash$doublequote", $doublequote, $x);
			$x = str_replace("$backslash$backslash", $backslash, $x);
		}

	} // end if get_magic_quotes_gpc

	return $x;

} // end function remove_magic_quotes

// **                                                                                  **
// **                                                                                  **
// **************************************************************************************
// **************************************************************************************


?>


