<?php
/**
    Created By: IT Serenity
    ===================================
    Created On: 2021 - 05 - 06
    ===================================
    Original Developer: Rob Couch
**/
if ( !isset( $_SESSION ) ) session_start() ;

class Customers
{
    //    Object Properties
    public $aCustomer = null ;    //    Customer DB Row
    public $nID       = null ;    //    Customer ID
    
    private $oDB    = null ;
    private $sTable = '`Customers`' ;
    
    /**
        Constructor
        Initial Setup Of Object
        
        $mysqli : Valid MySQLi Object
        $nID    : Integer Of Customer ID
    **/
    public function __construct( mysqli $mysqli = null, $nID = null )
    {
        LogIt( __DIR__, __FILE__, __CLASS__, __FUNCTION__, __LINE__, 'It Lives!', 'Flow' ) ;
        
        //    If Not Order Specified, Set To Natural Order
        if ( !isset( $_SESSION[ 'Order' ] ) )
        {
            $_SESSION[ 'Order' ] = '`ID`' ;
        }
        
        //    Store MySQLi Object
        $this->oDB = $mysqli ;
        
        //    Validate ID As A Numeric Value
        if ( ( null != $nID ) && is_numeric( $nID ) )
        {
            $this->nID = $nID ;
            
            $this->Get( $nID ) ;
        }
    }
    
    
    /**
        GetList
        
        API To Return Customer HTML Display List
        
        $sOrder    : String Or Array With String
    **/
    public function GetList( $sOrder = null )
    {
        //    Could Be Data From XHR Call
        if ( is_array( $sOrder ) )
        {
            $sOrder = $sOrder[ 'Order' ] ;
        }
        
        //    Initialize Return Values
        $aReturn = array( 'status' => 1 ) ;
        
        $aReturn[ 'list' ] = $this->List( $sOrder ) ;
        
        return $aReturn ;
    }
    
    
    /**
        List
        
        Select Customer Records Build HTML Display List
        
        $sOrder    : String Or Array With String
        $sSQL      : String - SQL Override
    **/
    private function List( $sOrder = null, $sSQL = null )
    {
        LogIt( __DIR__, __FILE__, __CLASS__, __FUNCTION__, __LINE__, 'sOrder:  ' . $sOrder . '   _SESSION[ Order ]:  ' . $_SESSION[ 'Order' ], 'Flow' ) ;
        
        //    If Order Is Indentical Set To Descending
        if ( $sOrder === $_SESSION[ 'Order' ] )
        {
            LogIt( __DIR__, __FILE__, __CLASS__, __FUNCTION__, __LINE__, 'Reversing Sort Order', 'Flow' ) ;
            $sOrder .= ' DESC' ;
        }
        
        //    Initialize Vars
        $sHTML  = '' ;
        
        //    Previously Used Order Or New Order
        $sOrder = ( null == $sOrder ? $_SESSION[ 'Order' ] : $sOrder ) ;
        
        //    Always Update Session With The Last Order Used
        $_SESSION[ 'Order' ] = $sOrder ;
        
        if ( null == $sSQL )
        {
            $sSQL = 'SELECT
        *
    FROM
        ' . $this->sTable . '
    ORDER BY
        ' . $sOrder ;
        }
        
        $aCustomers = ExecSQL( $sSQL,  __DIR__, __FILE__, __CLASS__, __FUNCTION__, __LINE__ ) ;
        
        LogIt( __DIR__, __FILE__, __CLASS__, __FUNCTION__, __LINE__, '$aCustomers[ Rows ]:  ' . $aCustomers[ 'Rows' ], 'Flow' ) ;
        
        if ( $aCustomers[ 'Rows' ] > 0 )
        {
            LogIt( __DIR__, __FILE__, __CLASS__, __FUNCTION__, __LINE__, 'Starting fetch_assoc Loop', 'Flow' ) ;
            
            while ( $aRow = $aCustomers[ 'Result' ]->fetch_assoc() )
            {
                LogIt( __DIR__, __FILE__, __CLASS__, __FUNCTION__, __LINE__, 'Row ID:  ' . $aRow[ 'ID' ], 'Flow' ) ;
                
                $sHTML .= '                        <div class="bodyrow" data-id="' . $aRow[ 'ID' ] . '" >
                            <div class="bodycell cellcenter priority" >' . $aRow[ 'Priority' ] . '</div><div class="bodycell" >' . $aRow[ 'First' ] . '</div><div class="bodycell" >' . $aRow[ 'Last' ] . '</div><div class="bodycell cellcenter" >' . $aRow[ 'Phone' ] . '</div><div class="bodycell email emaildots" alt="' . $aRow[ 'Email' ] . '" title="' . $aRow[ 'Email' ] . '" >' . $aRow[ 'Email' ] . '</div>
                        </div>' . PHP_EOL ;
            }
        }
        
        return $sHTML ;
    }
    
    
    /**
        Get Customer API
        
        Select Customer Record And Set Object Property To Row
        
        $nID    : Integer Or Array Of Customer ID
    **/
    public function Get( $nID = null )
    {
        if ( is_array( $nID ) )
        {
            $nID = $nID[ 'ID' ] ;
        }
        
        LogIt( __DIR__, __FILE__, __CLASS__, __FUNCTION__, __LINE__, 'nID:  ' . $nID, 'Flow' ) ;
        
        $aReturn = null ;
        
        if ( ( null !== $nID ) && is_numeric( $nID ) )
        {
            $aCustomers = $this->GetRecs( '`ID` = ' . $nID, '`ID`', 1 ) ;
            
            if ( $aCustomers[ 'Rows' ] > 0 )
            {
                $this->aCustomer = $aCustomers[ 'Result' ]->fetch_assoc() ;
                LogIt( __DIR__, __FILE__, __CLASS__, __FUNCTION__, __LINE__, var_export( $this->aCustomer, true ), 'Flow' ) ;
            }
        }
        
        return $this->aCustomer ;
    }
    
    
    private function GetRecs( $sWhere, $sOrder, $nLimit = null )
    {
        $sSQL = 'SELECT
    *
FROM
    ' . $this->sTable . '
WHERE
    ' . $sWhere . '
ORDER BY
    ' . $sOrder ;

        
        if ( null !== $nLimit && is_numeric( $nLimit ) )
        {
            $sSQL .= ' LIMIT ' . $nLimit ;
        }
        
        return ExecSQL( $sSQL,  __DIR__, __FILE__, __CLASS__, __FUNCTION__, __LINE__ ) ;
    }
    
    
    public function Set( $aData = null )
    {
        LogIt( __DIR__, __FILE__, __CLASS__, __FUNCTION__, __LINE__, var_export( $aData, true ), 'Flow' ) ;
        
        $aReturn = array( 'status' => 0 ) ;
        
        if ( isset( $aData[ 'ID' ] ) && is_numeric( $aData[ 'ID' ] ) )
        {
            //    Bit Of Data Cleaning
            foreach( $aData as $sFld => $sValue )
            {
                $aData[ $sFld ] = addslashes( $sValue ) ;
            }
            
            $aData[ 'First' ] = ucwords( $aData[ 'First' ] ) ;
            $aData[ 'Last' ]  = ucwords( $aData[ 'Last' ] ) ;
            $aData[ 'Email' ] = strtolower( $aData[ 'Email' ] ) ;
            
            //    Insert
            if ( 0 == $aData[ 'ID' ] )
            {
                $sSQL = 'INSERT INTO ' . $this->sTable . ' ( `First`, `Last`, `Email`, `Phone`, `Priority` ) VALUES ( ?, ?, ?, ?, ? )' ;
                
                LogIt( __DIR__, __FILE__, __CLASS__, __FUNCTION__, __LINE__, $sSQL, 'Flow' ) ;
                
                //   Prepare Statement And Bind Variables
                $oStmt = $this->oDB->prepare( $sSQL ) ;
                $oStmt->bind_param( 'ssssi', $aData[ 'First' ], $aData[ 'Last' ], $aData[ 'Email' ], $aData[ 'Phone' ], $aData[ 'Priority' ] ) ;
            }
            else    //    Update
            {
                //   Prepare Statement And Bind Variables
                $oStmt = $this->oDB->prepare( 'UPDATE ' . $this->sTable . ' SET `First` = ?, `Last` = ?, `Email` = ?, `Phone` = ?, `Priority` = ? WHERE `ID` = ?' ) ;
                $oStmt->bind_param( 'ssssii', $aData[ 'First' ], $aData[ 'Last' ], $aData[ 'Email' ], $aData[ 'Phone' ], $aData[ 'Priority' ], $aData[ 'ID' ] ) ;
            }
            
            $oStmt->execute() ;
            
            $aReturn[ 'status' ] = ( 0 == $aData[ 'ID' ] ? $this->oDB->insert_id : $this->oDB->affected_rows ) ;
            $aReturn[ 'list' ] = $this->List() ;
        }
        
        return $aReturn ;
    }
    
    
    public function Search( $aData = null )
    {
        $aReturn = array( 'status' => 0 ) ;
        
        if ( isset( $aData[ 'Phrase' ] ) )
        {
            $sPhrase = $aData[ 'Phrase' ] ;
            
            LogIt( __DIR__, __FILE__, __CLASS__, __FUNCTION__, __LINE__, 'sPhrase:  ' . $sPhrase, 'Flow' ) ;
            
            $sWhere = 
            
            $sSQL = 'SELECT
    *
FROM
    ' . $this->sTable . '
WHERE
    `First` LIKE "%' . $sPhrase . '%"' . '
OR
    `Last` LIKE "%' . $sPhrase . '%"' . '
OR
    `Email` LIKE "%' . $sPhrase . '%"' . '
OR
    `Phone` LIKE "%' . $sPhrase . '%"' . '
OR
    `Priority` LIKE "%' . $sPhrase . '%"' . '
ORDER BY
    ' . $_SESSION[ 'Order' ] ;
            
            $aReturn[ 'status' ] = 1 ;
            $aReturn[ 'list' ] = $this->List( null, $sSQL ) ;
        }
        
        return $aReturn ;
    }
    
    
    public function Delete( $nID = null )
    {
        if ( is_array( $nID ) )
        {
            $nID = $nID[ 'ID' ] ;
        }
        
        LogIt( __DIR__, __FILE__, __CLASS__, __FUNCTION__, __LINE__, 'nID:  ' . $nID, 'Flow' ) ;
        
        $aReturn = array( 'status' => 0 ) ;
        
        if ( ( null !== $nID ) && is_numeric( $nID ) )
        {
            $sSQL = 'DELETE
FROM
    ' . $this->sTable . '
WHERE
    `ID` = ' . $nID ;
            
            $aDelete = ExecSQL( $sSQL,  __DIR__, __FILE__, __CLASS__, __FUNCTION__, __LINE__ ) ;
            
            $aReturn[ 'status' ] = $aDelete[ 'Rows' ] ;
            
            if ( $aDelete[ 'Rows' ] > 0 )
            {
                $aReturn[ 'list' ] = $this->List() ;
            }
        }
        
        return $aReturn ;
   }
}