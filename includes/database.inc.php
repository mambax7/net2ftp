<?php//   -------------------------------------------------------------------------------//  |                  net2ftp: a web based FTP client                              |//  |              Copyright (c) 2003-2004 by David Gartner                         |//  |                                                                               |//  | This program is free software; you can redistribute it and/or                 |//  | modify it under the terms of the GNU General Public License                   |//  | as published by the Free Software Foundation; either version 2                |//  | of the License, or (at your option) any later version.                        |//  |                                                                               |//   -------------------------------------------------------------------------------// **************************************************************************************// **************************************************************************************// **                                                                                  **// **                                                                                  **function connect2db () {// --------------// This function logs user accesses to the site// --------------// -------------------------------------------------------------------------// Global variables and settings// -------------------------------------------------------------------------	$dbusername = getSetting("dbusername");	$dbpassword = getSetting("dbpassword");	$dbname     = getSetting("dbname");	$dbserver   = getSetting("dbserver");	$mydb = @mysql_connect($dbserver, $dbusername, $dbpassword);	if ($mydb == false) { return putResult(false, "", "mysql_connect", "connect2db > mysql_connect", "Unable to connect to the DB<br />"); exit(); }	$result1 = @mysql_select_db($dbname);	if ($result1 == false) { return putResult(false, "", "mysql_select_db", "connect2db > mysql_select_db", "Unable to select the DB<br />"); exit(); }	return putResult(true, $mydb, "", "", "");} // End connect2db// **                                                                                  **// **                                                                                  **// **************************************************************************************// **************************************************************************************?>