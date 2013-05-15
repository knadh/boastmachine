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


// Timezones
// Place|Difference in hours

$zones=<<<EOF
International Date Line West|-12.00
Midway Island, Samoa|-11.00
Hawaii|-10.00
Alaska|-09.00
Pacific Time(US & Canada); Tijuana|-08.00
Arizona|-07.00
Chihuahua, La Paz,Mazatlan|-07.00
Mountain Time (US & Canada)|-07.00
Central America|-06.00
Central Time (US & Canada)|-06.00
Gudalajara, Mexico City, Monterrey|-06.00
Saskatchewan|-06.00
Bogota, Lima, Quito|-05.00
Easter Time (US & Canada)|-05.00
Indiana (East)|-05.00
Atlantic Time (Canada)|-04.00
Caracas, La Paz|-04.00
Santiago|-04.00
Newfoundland|-03.50
Brasilia|-03.00
Buenos Aires, Georgetown|-03.00
Greenland|-03.00
Mid-Atlantic|-02.00
Azores|-01.00
Cape Verde Is.|-01.00
Casablanca,Monrovia|0.00
Greenwich Mean Time : Dublin, Edinburgh, Lisbon, London|0.00
Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna|+01.00
Bengrade, Bratislava, Budapest, Ljubljana, Prague|+01.00
Brussels, Copenhagen, Madrid, Paris|+01.00
Sarajevo, Skopje, Warsaw, Zagreb|+01.00
West Central Africa|+01.00
Athens, Istanbul, Minsk|+02.00
Bucharest|+02.00
Cairo|+02.00
Harare, Pretoria|+02.00
Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius|+02.00
Jerusalem|+02.00
Baghdad|+03.00
Kuwait, Riyadh|+03.00
Moscow, St. Petersburg, Volgograd|+03.00
Nairobi|+03.00
Tehran|+03.5
Abu Dhabi, Musca|+04.00
Baku, Tbilisi, Yerevan|+04.00
Kabul|+04.5
Ekaterinburg|+05.00
Islamabad, Karachi, Tashkent|+05.00
Chennai, Kolkata, Mumbai, New Delhi|+05.5
Kathmandu|+05.45
Almaty, Novosibirsj|+06.00
Astana, Dhaka|+06.00
Sri Jayawardenepura|+06.00
Rangoon|+06.5
Bangkok, Hanoi, Jakarta|+07.00
Krasnoyarsk|+07.00
Beijing, Chonhqing, Hong Kongm Urumqi|+08.00
Irkutsk, Ulaan Bataar|+08.00
Kuala Lumpur, Singapore|+08.00
Perth|+08.00
Taipei|+08.00
Osaka, Sapporo, Tokyo|+09.00
Seoul|+09.00
Yakutsk|+09.00
Adelaide|+09.5
Darwin|+09.30
Brisbane|+10.00
Canberra, Melbourne, Sydney|+10.00
Guam, Port Moresby|+10.00
Hobart|+10.00
Vladivostok|+10.00
Magadan, Solomon Is., New Caledonia|+11.00
Auckland, Wellington|+12.00
Fiji, Kamchatka, Marshall Is.|+12.00
Nuki'alofa|+13.00
EOF;
?>

<select name="timezones" onChange="javascript:document.sets.admin_gmt_diff.value=this.options[selectedIndex].value;	document.sets.admin_time_zone.value=this.options[selectedIndex].text;">
<?php

$zones=explode("\n", $zones);

$sel="";

	echo "<option value=\"\"></option>\n";

for($n=0;$n<count($zones)-1;$n++) {
	$tz=explode("|", $zones[$n]);

	if($bmc_vars['time_zone'] == "(GMT ".trim($tz[1]).") $tz[0]") {
		$gmt_diff=trim($tz[1]);
		$sel="selected";
	} else {
		$sel="";
	}

	echo "<option value=\"".trim($tz[1])."\"{$sel}>(GMT ".trim($tz[1]).") {$tz[0]}</option>\n";
}
?>
</select>