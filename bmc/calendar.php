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

	// The calendar class (XHTML)
	// adopted from the calendar class by Adam bidwell (bidwell.com)

	class Calendar {

		// Constants
		var $firstDayOfTheWeek = 0;	// 0 - Sunday through to 6 - Saturday

		// Variables
		var $timestamp;
		var $day;
		var $month;
		var $year;


		/**
		*	Construct an instance of the calendar
		*/
		function Calendar ($day, $month, $year) {
			// create a timestamp of these details for simplicity later on
			$this -> timestamp = mktime (0, 0, 0, $month, $day, $year);
			$this -> day = $day;
			$this -> month = $month;
			$this -> year = $year;
		}


		/**
		*	Output the calendar as XHTML
		*/
		function drawXHTML ($cellPadding, $cellSpacing) {
			global $db, $lang, $bmc_vars;

		$days = array ($lang['date']['week_day_1'],$lang['date']['week_day_2'],$lang['date']['week_day_3'],$lang['date']['week_day_4'],$lang['date']['week_day_5'],$lang['date']['week_day_6'],$lang['date']['week_day_7']);




			$numberOfDaysInMonth = date ("t", $this -> timestamp);
			$firstDayOfTheMonth = date ("w", mktime (0, 0, 0, $this -> month, 1, $this -> year));

			$currentDay = $this -> firstDayOfTheWeek - $firstDayOfTheMonth - 6;
		
			echo "<table class=\"calendar_table\" cellpadding=\"" . $cellPadding . "\" cellspacing=\"" . $cellSpacing . "\">\n";
			
			echo "<tr>";
			echo "<td colspan=\"7\" class=\"calendar_month\">";
			echo bmc_Date ($this -> timestamp, "M"). " " . $this -> year;
			echo "</td>";
			echo "</tr>\n";

			echo "<tr>";
			for ($day = 0; $day < 7; $day ++) {
				$d = $day + $this -> firstDayOfTheWeek;
				if ($d > 6) $d -= 7;
				echo "<td class=\"calendar_days\">" . $days [$d] . "</td>";
			}
			echo "</tr>\n";


			$this_month_start=mktime(0,0,0,$this->month,1,$this->year); // This month's start
			$this_month_end=mktime(0,0,0,($this->month)+1,1,$this->year); // Next month's start

			// The DB queries
			$result=$db->query("SELECT date FROM ".MY_PRF."posts WHERE status='1' AND date <= '{$this_month_end}' AND date >= '{$this_month_start}' AND blog='".BLOG."'");
			// Get all the posts posted between the 1st of this month and the end

			if(!$result || !count($result)) {
				$date_flag=false;
			} else {
				$date_flag=true;

				foreach($result as $array) {
					$dates[bmc_Date($array['date'], "jm")]=1;
				}
			}

		
			for ($week = 0; (($week < 7) && ($currentDay <= $numberOfDaysInMonth)); $week ++) {
				echo "<tr>";
				for ($day = 0; $day < 7; $day++) {

					if ($currentDay == $this -> day) {
						echo "<td class=\"calendar_current_day\">";
					} else {
						echo "<td class=\"calendar_day\">";
					}

					if (($currentDay > 0) && ($currentDay <= $numberOfDaysInMonth)) {

						$tmp_date=$currentDay.$this->month;

						if(isset($dates[$tmp_date]) && $date_flag) {
							echo "<a href=\"{$bmc_vars['site_url']}/".bmc_SE_friendly_url('calendar',BLOG_FILE,"{$currentDay},{$this->month},{$this->year}")."\" title=\"{$lang['caln_tip']} {$currentDay}/{$this->month}/{$this->year}\">".$currentDay."</a>\n";
						} else {
							echo $currentDay;
						}


					}
					echo "</td>\n";
			
					$currentDay ++;
				}
				echo "</tr>\n";
			}
			
			echo "</table>\n";
		}

	}

?>
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td align="center" colspan="3">
					<?php		




	if(isset($_GET['cal']) && strlen($_GET['cal']) == 7) {

		list($month,$year)=explode(",", $_GET['cal']);

		if(empty($month) || $month > 12 || $month < 1) {
			$month = date ("m");
		}

		if(empty($year) || $year < 1975 || $year > 2050) {
			$year = date ("Y");
		}

	} else {

		if(isset($_GET['show']) && strlen($_GET['show']) < 10) {
			list($na,$month,$year)=explode(",", $_GET['show']);

			if(empty($month) || $month > 12 || $month < 1) {
				$month = date ("m");
			}

			if(empty($year) || $year < 1975 || $year > 2050) {
				$year = date ("Y");
			}

		} else {
			$month = date ("m");
			$year = date ("Y");
		}
	}

	$day = date ("j");
					
					
	// create an instance of the calendar, using the defined date ...
	$calendar = new Calendar ($day, $month, $year);
					
					
	// ... and then draw it
	$calendar -> drawXHTML (1,1);

	// work out the previous/next months
	$previousMonth = date ("m", mktime (0, 0, 0, $month - 1, $day, $year));
	$nextMonth = date ("m", mktime (0, 0, 0, $month + 1, $day, $year));


	if($nextMonth == 1) {
		$nextYear=$year+1;
	} else {
		$nextYear=$year;
	}



	if($month == 1) {
		$previousYear=$year-1;
	} else {
		$previousYear=$year;
	}

					?>
				</td>
			</tr>
			<tr>
				<td align="left"><a href="<?php echo BLOG_FILE; ?>?cal=<?php echo $previousMonth.",".$previousYear; ?>" title="<?php echo $lang['caln_prev']; ?>">&lt;&lt;</a></td>
				<td align="right"><a href="<?php echo BLOG_FILE; ?>?cal=<?php echo $nextMonth.",".$nextYear; ?>" title="<?php echo $lang['caln_next']; ?>">&gt;&gt;</a></td>
			</tr>
		</table>

