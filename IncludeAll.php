<?php
/**
    Created By: IT Serenity
    ===================================
    Created On: 2021 - 05 - 06
    ===================================
    Original Developer: Rob Couch
    ===================================================================================================================================================
    Purpose: Maintain Global Functions
    ===================================================================================================================================================
    Modifications: Date - Developer - Description Of Coding Change
    --------------------------------------------------------------------------------------------------------------------------------------------------
    2)
    --------------------------------------------------------------------------------------------------------------------------------------------------
    1)
**/
if ( !isset($_SESSION) ) session_start() ;

error_log( date( 'H:i:s ' ) . '[] IncludeAll.php[' . __LINE__ . ']  Included' . PHP_EOL, 3, 'Flow_' . date( 'Ymd' ) . '_.log' ) ;

//    Configure ErrorHandling
error_reporting( E_ALL ) ;

ini_set( 'display_errors', 1 ) ;
ini_set( 'display_startup_errors', 1 ) ;
ini_set( 'log_errors', 1 ) ;
ini_set( 'error_log', 'Errors_' . date( 'Ymd' ) . '_.log' ) ;

include_once( 'Config.php' ) ;

/**
    Create A MySQLi Connection
**/
function ConnectMySQLi()
{
    LogIt( __DIR__, __FILE__, __CLASS__, __FUNCTION__, __LINE__, 'Connecting', 'Flow' ) ;
    
    $aDB = $_SESSION[ 'Config' ][ 'DB' ] ;
    
    $_SESSION[ 'MySQLi' ] = new mysqli( 'localhost', $aDB[ 'Prefix' ] . $aDB[ 'User' ], $aDB[ 'Password' ], $aDB[ 'Prefix' ] . $aDB[ 'Name' ] ) ;
    
    //    Log Any Error That May Have Occurred
    if ( $_SESSION[ 'MySQLi' ]->connect_errno )
    {
        LogIt( __DIR__, __FILE__, __CLASS__, __FUNCTION__, __LINE__, 'Failed to connect to MySQLi: ' . $_SESSION[ 'MySQLi' ]->connect_error ) ;
        
        $_SESSION[ 'MySQLi' ] = false ;
    }
    else
    {
        LogIt( __DIR__, __FILE__, __CLASS__, __FUNCTION__, __LINE__, 'Setting Character Set', 'Flow' ) ;
        
        //    Set Character Set To utf8mb4
        if ( $_SESSION[ 'MySQLi' ]->set_charset( 'utf8mb4' ) )
        {
            LogIt( __DIR__, __FILE__, __CLASS__, __FUNCTION__, __LINE__, 'Current character set:  ' . $_SESSION[ 'MySQLi' ]->character_set_name(), 'Flow' ) ;
        }
        else
        {
            LogIt( __DIR__, __FILE__, __CLASS__, __FUNCTION__, __LINE__, 'Error Loading Character Set utf8mb4:  ' . $_SESSION[ 'MySQLi' ]->error ) ;
        }
    }
    
    return $_SESSION[ 'MySQLi' ] ;  
}


/**
    Execute A Given SQL Query, Handle Errors, Return Record Rows And Result Set

    string    $sSQL    The Query To Be Executed

    return    array
**/
function ExecSQL( $sSQL,  $dir = null, $file = null, $class = null, $function = null, $line = null )
{
    $sSQL = trim( $sSQL ) ;
    
    LogIt( __DIR__, __FILE__, __CLASS__, __FUNCTION__, __LINE__, 'sSQL:  ' . $sSQL, 'Flow' ) ;
    
    //    D - Delete, I - Insert, U - Update, R - Replace, S - SELECT
    $cAction = strtoupper( $sSQL[ 0 ] ) ;
    
    //    Query Return  Data
    $aReturn = array( 'Result' => null, 'Rows' => 0 ) ;
    
    $rResult = $_SESSION[ 'MySQLi' ]->query( $sSQL ) ;
    
    //    Is Result Valid And At Least 1 Record Returned
    if ( false === $rResult )
    {
        LogIt( __DIR__, __FILE__, __CLASS__, __FUNCTION__, __LINE__, 'SQL Error:  ' . $_SESSION[ 'MySQLi' ]->errno . ' -> ' . $_SESSION[ 'MySQLi' ]->error ) ;
        LogIt( __DIR__, __FILE__, __CLASS__, __FUNCTION__, __LINE__, 'Error sSQL:  ' . $sSQL ) ;
    }
    else
    {
        //    Only Select Uses num_rows, All Others Use affected_rows
        $aReturn[ 'Rows' ]   = ( 'S' == $cAction ? $rResult->num_rows : $_SESSION[ 'MySQLi' ]->affected_rows ) ;
        $aReturn[ 'Result' ] = $rResult ;
        $aReturn[ 'ID' ]     = ( 'I' == $cAction ? $_SESSION[ 'MySQLi' ]->insert_id : $_SESSION[ 'MySQLi' ]->affected_rows ) ;
    }
    
    LogIt( __DIR__, __FILE__, __CLASS__, __FUNCTION__, __LINE__, 'Rows: ' . $aReturn[ 'Rows' ], 'Flow' ) ;
    
    return $aReturn ;
}


/**
    Custom Error Log Output

    string    $dir          path to current script
    string    $script       current script including path
    string    $class        name of class being used
    string    $function     name of function being used
    string    $line         line number of script
    string    $output       desired message to log
    string    $file         optional file name prefix rather than 'error'

    return    null
**/
function LogIt( $dir, $script, $class, $function, $line, $output, $file = null, $echo = false )
{
    //    Init Variables
    $script   = CropScript( $dir, $script ) ;                 //    Quickly Strip Path From Script Name
    $class    = ( null == $class ? '' : $class . '->' ) ;    //    Add Pointer To Class
    $function = ( null == $function ? '' : $function . '()' ) ;         //    Will Be Same As Method If Of A Class
    $user     = ( isset( $_SESSION[ 'ID' ] ) ? $_SESSION[ 'ID' ] : '' ) ;
    
    //    Construct File Name
    $file = ( null == $file ? 'Error' : $file ) . '_' . date( 'Ymd' ) . '_.log' ;
    
    //    Write To Log File
    error_log( date( 'H:i:s ' ) . '[' . $user . '] ' . $script . '[' . $line . '] ' . $class . $function . '  ' . $output . PHP_EOL, 3, $file ) ;
    
    //    Send To stdout
    if ( $echo )
    {
        echo $output . '<br />' . PHP_EOL ;
    }
}


/**
    Crop Path From Script Name

    string    $sPath
    string    $sScript

    return    string
**/
function CropScript( $sPath, $sScript )
{
    return substr( $sScript, strlen( $sPath ) + 1 ) ;
}


/**
    Assemble HTML Lines To Incorporate CSS And JavaScript Files

    array     $aFiles    File Names Without Extensions
    string    $sType     Which Type Of File, CSS Or JS. Defaults To CSS.
    string    $sPad      The Amount Of Left Padding For HTML Indentation

    return    string     The Generated HTML
**/
function Includes( $aFiles, $cType, $sPad = '        ' )
{
    $sReturn = '' ;
    $aOpen   = array( 'c' => '<link rel="stylesheet" href="', 'j' => '<script src="' ) ;
    $aType   = array( 'c' => '.css', 'j' => '.js' ) ;
    $aClose  = array( 'c' => '" />', 'j' => '" ></script>' ) ;
    
    $sCacheBuster = '?dt=' . date( 'YmdHis' ) ;
    
    foreach ( $aFiles as $sFile )
    {
        $sReturn .= $sPad . $aOpen[ $cType ] . $sFile . $aType[ $cType ] . $sCacheBuster . $aClose[ $cType ] . PHP_EOL ;
    }
    
    return $sReturn ;
}
