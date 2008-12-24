<?php


if (!defined ('TYPO3_MODE'))     die ('Access denied.');
 
$TYPO3_CONF_VARS['FE']['XCLASS']['ext/sr_feuser_register/model/class.tx_srfeuserregister_data.php']= t3lib_extMgm::extPath($_EXTKEY).'pi1/class.ux_tx_srfeuserregister_data.php'; 

?>