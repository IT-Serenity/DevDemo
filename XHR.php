<?php
if ( !isset( $_SESSION ) ) session_start() ;

include( 'Config.php' ) ;
include( 'IncludeAll.php' ) ;

$oData = json_decode( $_REQUEST[ 'data' ], true ) ;

$aReturn = array() ;
$aReturn[ 'error' ] = 'No Object Declared!' ;

LogIt( __DIR__, __FILE__, __CLASS__, __FUNCTION__, __LINE__, '$oData:  ' . var_export( $oData, true ), 'Flow' ) ;

$oDB = null ;

if ( !isset( $oData[ 'nodb' ] )  )
{
    LogIt( __DIR__, __FILE__, __CLASS__, __FUNCTION__, __LINE__, 'DB Connection Required', 'Flow' ) ;
    $oDB = ConnectMySQLi() ;
}

if ( isset( $oData[ 'object' ] ) )
{
    $aReturn[ 'error' ] = 'Class File: ' . $oData[ 'object' ] . ' Does Not Exist!' ;
    
    if ( file_exists( 'class.' . $oData[ 'object' ] . '.php' ) )
    {
        LogIt( __DIR__, __FILE__, __CLASS__, __FUNCTION__, __LINE__, 'Instantiating Object: ' . $oData[ 'object' ], 'Flow' ) ;
        
        require( 'class.' . $oData[ 'object' ] . '.php' ) ;
        
        $oClass = null ;
        
        if ( null != $oDB )
        {
            $oClass = new $oData[ 'object' ]( $oDB ) ;
        }
        else
        {
            $oClass = new $oData[ 'object' ]() ;
        }
        
        $aReturn[ 'error' ] = 'Class Method: ' . $oData[ 'action' ] . ' Does Not Exist!' ;
        
        if ( method_exists( $oClass, $oData[ 'action' ] ) )
        {
            //    Calling An Objects Method Via A Variable Requires A String, A String Element Of An Array Will Not Due!
            $sMethod = $oData[ 'action' ] ;
            
            LogIt( __DIR__, __FILE__, __CLASS__, __FUNCTION__, __LINE__, 'Calling Method: ' . $sMethod, 'Flow' ) ;
            
            unset( $aReturn[ 'error' ] ) ;
            
            $aReturn[ 'ReturnValue' ] = $oClass->$sMethod( $oData[ 'data' ] ) ;
        }
    }
    else
    {
        LogIt( __DIR__, __FILE__, __CLASS__, __FUNCTION__, __LINE__, $aReturn[ 'error' ], 'Flow' ) ;
    }
}

echo json_encode( $aReturn ) ;