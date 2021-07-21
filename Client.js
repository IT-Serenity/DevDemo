/**
    Created By: IT Serenity
    =======================================================================
    Original Developer: Rob Couch
    =======================================================================
    Purpose: JavaScript Supporting Same Named .php Script
    =======================================================================
    Modifications: Date - Developer - Description Of Coding Change
    -----------------------------------------------------------------------
    2)
    -----------------------------------------------------------------------
    1)
**/
var bScript = false ;

console.log( 'Client: It Has Begun!' ) ;

window.onload = function()
{
    Server.init() ;
    
    //    Value May Be Changed By In Each Script JS
    if ( bScript )
    {
        Script.init() ;
    }
}

console.log( 'Client: Instantiated' ) ;