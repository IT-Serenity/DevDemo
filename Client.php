<?php
/**
    Created By: IT Serenity
    ==================================
    Created On: 2021 - 05 - 06
    ==================================
    Original Developer: Rob Couch
    ==================================================================================================================================================
    Purpose: Client Side View Main Wrapper
    ==================================================================================================================================================
    Modifications: Date - Developer - Description Of Coding Change
    --------------------------------------------------------------------------------------------------------------------------------------------------
    2)
    --------------------------------------------------------------------------------------------------------------------------------------------------
    1)
**/
if ( !isset($_SESSION) ) session_start() ;

error_log( date( 'H:i:s ' ) . '[] Client.php[' . __LINE__ . ']' . PHP_EOL, 3, 'Flow_' . date( 'Ymd' ) . '_.log' ) ;

//    Configure ErrorHandling
error_reporting( E_ALL ) ;

ini_set( 'display_errors', 1 ) ;
ini_set( 'display_startup_errors', 1 ) ;
ini_set( 'log_errors', 1 ) ;
ini_set( 'error_log', 'Errors_' . date( 'Ymd' ) . '_.log' ) ;

//    Master Library
include_once( 'IncludeAll.php' ) ;

//    Set Some Defaults
if ( !isset( $sScript ) ) $sScript = 'Home~' ;                      //    Set Default Script To Load
if ( !isset( $_SESSION[ 'Level' ] ) ) $_SESSION[ 'Level' ] = 0 ;    //    Set Default User Security Level

LogIt( __DIR__, __FILE__, __CLASS__, __FUNCTION__, __LINE__, 'sScript: ' . $sScript, 'Flow' ) ;

//    Establish Connection To Database
$oDB = ConnectMySQLi() ;

//    Add Common CSS And JS Files To Be Included Only In Head Tag. 
$aCSS = array( 'Client' ) ;
$aJS  = array( 'Serenity', 'Client', 'XHR' ) ;
$aCSS[] = $sScript ;
$aJS[] = $sScript ;

//    Build CSS & JS Linking HTML Script
$sHTML  = Includes( $aCSS, 'c' ) ;
$sHTML .= Includes( $aJS, 'j' ) ;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd" >
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" >
    <head>
        <meta name="ROBOTS" content="INDEX, FOLLOW" />
        <meta name="description" content="Quick Form Test" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Dev Demonstration Review</title>
<?php
echo $sHTML ;
?>
    </head>
    <body>
<!-- ********************************************************************************************************** -->
<!-- **********  Client  ************************************************************************************** -->
<!-- ********************************************************************************************************** -->
        <noscript>
            <p>
                If you see this message, your web browser doesn't support JavaScript or JavaScript is disabled. 
                Please enable JavaScript in your browser settings so this site can function correctly.
                Or user a real browser such as Chrome, Firefox or Brave.
            </p>
        </noscript>
<?php
include( $sScript . '.php' ) ;

//    Is There A Valid MySQLi Object
if ( $_SESSION[ 'MySQLi' ] )
{
    $_SESSION[ 'MySQLi' ]->close() ;
}
?>
        <div style="padding: 32px 0; text-align: center; " >
            <div style="font-size: .9em; color: #000000; margin-left: auto; margin-right: auto; " >
                All Content &copy; Copyright <?php echo date( 'Y' ) ;?> IT Serenity All Rights Reserved.
                <br />
                All trademarks, product names, and company names or logos cited herein are the property of their respective owners. 
            </div>
            <a href="http://www.itserenity.com" style="color: #000000; margin-top: 30px;" onclick="return ! window.open( this.href ) ;" >
                Powered By: IT Serenity
            </a>
        </div>
<!-- ********************************************************************************************************** -->
<!-- **********  Client  ************************************************************************************** -->
<!-- ********************************************************************************************************** -->
    </body>
</html>
