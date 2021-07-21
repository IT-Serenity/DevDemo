<?php
/**
    Created By: IT Serenity
    ===================================
    Created On: 2021 - 05 - 06
    ===================================
    Original Developer: Rob Couch
**/
if ( !isset($_SESSION) ) session_start() ;

error_log( date( 'H:i:s ' ) . '[] index.php[' . __LINE__ . ']' . PHP_EOL, 3, 'Flow_' . date( 'Ymd' ) . '_.log' ) ;

error_reporting( E_ALL ) ;

ini_set( 'display_errors', 1 ) ;
ini_set( 'display_startup_errors', 1 ) ;
ini_set( 'log_errors', 1 ) ;
ini_set( 'error_log', 'Errors_' . date( 'Ymd' ) . '_.log' ) ;

if ( file_exists( 'InMaintenance.status' ) )
{
    $sScript = 'Maintenance' ;
}

include( 'Client.php' ) ;
