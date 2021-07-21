<?php
/**
    Created By: IT Serenity
    ===================================
    Created On: 2011 - 09 - 25
    ===================================
    Original Developer: Rob Couch
    ===================================================================================================================================================
    Purpose: Server Side Script Encapsulate All AJAX Requests Between Client And Server
    ===================================================================================================================================================
    Modifications: Date - Developer - Description Of Coding Change
    --------------------------------------------------------------------------------------------------------------------------------------------------
    2)
    --------------------------------------------------------------------------------------------------------------------------------------------------
    1)
**/
if ( !isset($_SESSION) ) session_start() ;

error_log( date( 'H:i:s ' ) . '[] Server.php[' . __LINE__ . ']' . PHP_EOL, 3, 'Flow_' . date( 'Ymd' ) . '_.log' ) ;

include_once( 'IncludeAll.php' ) ;

$aParams = ( isset( $_POST[ 'Params' ] ) ? $_POST[ 'Params' ] : NULL ) ;
$sRetVal = 'Server [ ' . __LINE__ . ' ] No Parameters' ;

LogIt( __DIR__, __FILE__, __CLASS__, __FUNCTION__, __LINE__, 'aParams:  ' . var_export( $aParams, true ), 'Flow' ) ;

if ( ( $aParams != NULL ) && ( isset( $aParams[ 'Object' ] ) ) )
{
    $aRetVal = array( 'Good' => false, 'Return' => 'Servers [ ' . __LINE__ . ' ] No Such Object: >'.$aParams[ 'Object' ].'<' ) ;
    
    if ( file_exists( '0_' . $aParams[ 'Object' ] . '.php' ) )
    {
        LogIt( __DIR__, __FILE__, __CLASS__, __FUNCTION__, __LINE__, 'Object Found:  ' . $aParams[ 'Object' ], 'Flow' ) ;
        
        $aRetVal = array( 'Good' => false, 'Return' => 'Server [ ' . __LINE__ . ' ] Failed To Connect To DataBase!' ) ;
        
        if ( ConnectMySQLi() )
        {
            LogIt( __DIR__, __FILE__, __CLASS__, __FUNCTION__, __LINE__, '_POST[ Data ]:  ' . var_export( $_POST[ 'Data' ], true ), 'Flow' ) ;
            
            $aRetVal = array( 'Good' => false, 'Return' => 'Server [ ' . __LINE__ . ' ] No Such Function: >' . $aParams[ 'Function' ] . '<' ) ;
            
            include_once( '0_' . $aParams[ 'Object' ] . '.php' ) ;
            
            $aRetVal = call_user_func( $aParams[ 'Function' ], $_POST[ 'Data' ] ) ;
            
            if ( !$aRetVal[ 'Good' ] )
            {
                $aParams[ 'Function' ] = 'Error' ;
                $aRetVal[ 'Good' ] = true ;
            }
            
            $_SESSION[ 'MySQLi' ]->close() ;
        }
    }
    
    //    TODO - Convert To JSON
    echo ( $aRetVal[ 'Good' ] ? '1' : '0' ).'<=>'.$aParams[ 'Object' ].'<=>'.$aParams[ 'Return' ].'<=>'.$aParams[ 'Function' ].'<=>'.$aRetVal[ 'Return' ] ;
}
else
{
    //    TODO - Convert To JSON
    echo '0<=>UnkwnObject<=>UnkwnReturn<=>UnkwnFunction<=>'.$sRetVal ;
}
