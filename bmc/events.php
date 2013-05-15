<?php

/*
  ===========================

  boastMachine v3.1
  Released : Sunday, June 5th 2005 ( 06/05/2005 )
  http://boastology.com

  Developed by Kailash Nadh
  Email   : mail@kailashnadh.name
  Website : http://kailashnadh.name, http://bnsoft.net
  Blog    : http://boastology.com/blog

  boastMachine is a free software licensed under GPL (General public license)

  ===========================
*/

/*

BN Soft BOAST MACHINE v3.0 Platinum
// 

Written by Kailash Nadh
http://kailashnadh.name , http://bnsoft.net, kailash@bnsoft.net

===========================================================
This scripts shows the date specific messages on your boastMachine page.
For eg: You can set to show a "Happy christmas" message on Dec 25 of every year

===========================================================
Format to Define messages:
--------------------------

All the dates should be in the format
"day_month_year" and none of these should start with a 0


eg:
	"1_12_2004" , "21_1_2006"
So the messages you set for these dates will be displayed when the date comes.
But note that these are year specific.


If you want to display a message that is not Year specific, i.e,
every year on Dec 25th, enter date in this formar

	"25_12_Y" where Y shows null Year.
A message set in this date format will be displayed on 25 Dec every year

If you want to display a message on a specific date every month,
enter the date in this format

	"9_M_Y" where M shows Null month and Y Null year
A message set for this date will be displayed on Every 9th of a month

NOTE:
-----
You can even enter multi-lined , HTML messages for a date.
eg:

$events["1_1_Y"]=<<<EOF

<b>Happy new year!</b><br>
Yet another year has passed!<br>
It was so quick that I didnt even notice that
I grew 1 year older!

EOF;



When entering messages in this format, DONOT touch THE LINE
<<<EOF and EOF;

The bottom EOF; must NOT BE INDENTED OR TABBED!


If 2 events are set to the same day,
eg: 1_M_Y = "Today is the 1st day"; (this is displayed every month, on 1st)
and 1_12_Y = "World aids day"; (Displayed on every Dec 1st)

only 1 message will be displayed when Dec 1st comes.
[ The one which is defined 1st is displayed ]

===========================================================
*/



$events=array(); // The array which holds the events and Dates
				 // No need to touch this arry declaration

// USER CONFIGURATION . DEFINE DATES AND MESSAGES HERE
// ==========================


$events["25_12_Y"] = "MERRY CHRISTMAS!!";

$events["1_1_Y"] = "Happy NewYear!!!";

$events["1_12_Y"] = "Today is world AIDS day";

$events["1_M_Y"] = "Today is the 1st day of the month";



// ===================== This prints the messages on the current date ===============

$dt=date("j");
$mn=date("n");
$yr=date("year");


if($events[$dt."_".$mn."_".$yr]) { echo $events[$dt."_".$mn."_".$yr."_"]; }
if($events[$dt."_".$mn."_Y"]) { echo $events[$dt."_".$mn."_Y"]; }
if($events[$dt."_M_Y"]) { echo $events[$dt."_M_Y"]; }


?>