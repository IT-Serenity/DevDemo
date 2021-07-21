/**
    Created By: IT Serenity
    =======================================================================
    Created On: 2021 - 05 - 06
    =======================================================================
    Original Developer: Rob Couch
    =======================================================================
    Purpose: JavaScript Common Web Site Functions
    =======================================================================
    Modifications: Date - Developer - Description Of Coding Change
    -----------------------------------------------------------------------
    2) 
    -----------------------------------------------------------------------
    1)  
**/
console.log( 'Serenity: Linked!' ) ;

//    Handle For Document
$ = null ;

//    Server Object
Server = {
    //    Properties
    oF: null,
    
    
    /**
        Method: Init
        
        Purpose: Initialize Any Global Variables
    **/
    init : function()
    {
        console.log( 'Server: init()' ) ;
        
        $ = document ;
    },
    
    
    /**
        Method: FillForm
        
        Purpose: Populate Values Of Input Fields From A JSON Object
    **/
    FillForm : function( sTheForm, sJSON )
    {
        var hForm = $.getElementById( sTheForm ) ;
        
        var oData = JSON.parse( sJSON ) ;
        
        for ( sField in oData )
        {
            hForm[ sField ].value = oData[ sField ] ;
        }
    },
} ;


//  Estabish That This File Has Been Instanciated
console.debug( 'Serenity: Instantiated' ) ;
