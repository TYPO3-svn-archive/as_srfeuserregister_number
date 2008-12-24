<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2008-2010 Andraes Steinhuber <office@typotemp.com>
*  All rights reserved
*
*  This script is part of the Typo3 project. The Typo3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*  A copy is found in the textfile GPL.txt and important notices to the license
*  from the author is found in LICENSE.txt distributed with these scripts.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 *
 * Extents sr_feuser_register. A configurable number as a fe_user username is generated.
 * 
 * @author	Kasper Skarhoj <kasperYYYY@typo3.com>
 * @author	Stanislas Rolland <stanislas.rolland(arobas)sjbr.ca>
 * @author	Franz Holzinger <contact@fholzinger.com>
 * @author	Andraes Steinhuber <office@typotemp.com>
 * @maintainer	Andraes Steinhuber <office@typotemp.com>
 *
 */
require_once(PATH_tslib . 'class.tslib_pibase.php');



class ux_tx_srfeuserregister_data extends tx_srfeuserregister_data{    
    
	// setUsername normal or as number if username_as_number is enabled 
	function setUsername($theTable, &$dataArray, $cmdKey) {
		if ($this->conf[$cmdKey.'.']['useEmailAsUsername'] && $theTable == "fe_users" && t3lib_div::inList($this->getFieldList(), 'username') && !$this->failureMsg['email']) {
			$dataArray['username'] = trim($dataArray['email']);
		} elseif ($this->conf['username_as_number']) {
		  $dataArray['username']=$this->getNewUsername();
		}
	}	
	
		
	function getNewUsername(){

	//lengh of serial number / adding zeros / new serial if new year / also for getting the years for select		
	$prefix_char = $this->cObj->stdWrap($this->conf['userPraefix'],$this->conf['userPraefix.']);
	$prefix= ($prefix_char) ? $prefix_char : 11;
	$prefix_lenght = strlen($prefix);
	
	$number_lenght = ($this->conf['serialNumberLength']) ? $this->conf['serialNumberLength'] : 6;
	$prefix_lenght_year = $prefix_lenght +2;
	
	$timestamp = time(); 
	$actualYear=date("Y",$timestamp);
	$thisYear = (int)substr($actualYear,$number_lenght,2);
	$oldYear = $thisYear -1;
	
	
	// select only username with thisYear & oldYear in username, so username_as_number 
	$res = $GLOBALS['TYPO3_DB']->sql(TYPO3_db, "SELECT `username`,`uid` FROM `fe_users` WHERE username LIKE '%".$thisYear."%' OR username LIKE '%".$oldYear."%' ORDER BY uid DESC LIMIT 1");
	$lastUserName=$GLOBALS['TYPO3_DB']->sql_fetch_row($res);
	
	$lastPrefix=(int)substr($lastUserName[0],0,$prefix_lenght);
	$lastYear=(int)substr($lastUserName[0],$prefix_lenght,2);
	$lastNumber=(int)substr($lastUserName[0],$prefix_lenght_year,$number_lenght);
	
	
	$resetedNumber=addLeadingZeros(1,$number_lenght);
	$newNumber_count=$lastNumber+1;
	$newNumber = addLeadingZeros($newNumber_count,$number_lenght);
	
	$checkOldYear=(int)substr($actualYear,2,2);
	if($checkOldYear==$lastYear){
		$newUsername=$prefix.substr($actualYear,2,2).$newNumber;
	}else{
		$newUsername=$prefix.substr($actualYear,2,2).$resetedNumber;
	}
	return $newUsername;		

	}	
}
function addLeadingZeros($number, $digits){
		for($ii=strlen($number) ; $ii<$digits; $ii++) {$number="0".$number;}
		return $number;
}

?>