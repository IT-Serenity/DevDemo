<?php
/**
    Created By: IT Serenity
    =======================================================================
    Created On: 2021 - 05 - 06
    =======================================================================
    Original Developer: Rob Couch
    =======================================================================
    Purpose: Home Launcher
    =======================================================================
    Modifications: Date - Developer - Description Of Coding Change
    -----------------------------------------------------------------------
    2)
    -----------------------------------------------------------------------
    1)
**/
if ( !isset($_SESSION) ) session_start() ;

//    Page To Load
$sScript = 'Home~' ;

//    Associated CSS And JS Files To Accompany PHP Script
$aScriptCSS = $aScriptJS = array( $sScript ) ;

include( 'Client.php' ) ;
