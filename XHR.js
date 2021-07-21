/**
  object:  XHR
   notes:  ajax common chores wrapper
**/
var XHR =
{
    bDebug : false,
    
    //    request: Object To Set ajax Parameters
    //    request.data Can Be Either A "GET" String Or A FormData Object Capable Of Containing Name Value Pairs, Blobs and Files 
    //    request.callback:  Function To Call Back Upon Ajax Success - !!! NOT A STRING !!!
    //    optional:  Additional Object To Send To CallBack Function
    
    /**
        param:  Type 0 = FormData, 1 = Object
    **/
    Request: function ( Data )
    {
        if ( XHR.bDebug )  console.log( 'XHR.Request()' ) ;
        
        XHR.Send( Data )
    },
    //    Request
    
    
    //    Send - open connection to server, send message, wait for response
    Send: function( oData )
    {
        if ( XHR.bDebug )  console.log( 'XHR.Send()' ) ;
        
        //    Create XHR Object
        var xhr2 = new XMLHttpRequest() ;
        
        //    Assign Functions To Handle Each Of The XHR Objects Events
        
        //    abort
        xhr2.onabort = function()
        {
            if ( XHR.bDebug )  XHR.DebugOut( 'Abort', this ) ;
        },
        
        
        //    errors
        xhr2.onerror = function()
        {
            console.log( 'Failed to communicate with server!' ) ;
        
            XHR.DebugOut( 'Error', this ) ;
            
            if ( 'Error' in oData )
            {
                oData.Error() ;
            }
        },
        
        
        //    success
        xhr2.onload = function()
        {
            if ( XHR.bDebug )  console.log( 'XHR Request Was Successful!' ) ;
        
            if ( XHR.bDebug )  XHR.DebugOut( 'Load', this ) ;
            
            //console.log( this.responseText ) ;
            
            var data = JSON.parse( this.responseText ) ;
            
            if ( 'Success' in oData )
            {
                oData.Success( data.ReturnValue ) ;
            }
        },
        
        
        //    end
        xhr2.onloadend = function()
        {
            if ( XHR.bDebug )  XHR.DebugOut( 'End', this ) ;
        },
        
        
        //    start
        xhr2.onloadstart = function()
        {
            if ( XHR.bDebug )  XHR.DebugOut( 'Start', this ) ;
        },
        
        //    progress
        xhr2.onprogress = function()
        {
            if ( XHR.bDebug )  XHR.DebugOut( 'Progress', this ) ;
            
            //XHR.Progress( this, oData ) ;
        },
        
        //    readystatechange
        xhr2.onreadystatechange = function()
        {
            if ( XHR.bDebug )  XHR.DebugOut( 'ReadyStateChange', this ) ;
        },
        
        //    timeout
        xhr2.ontimeout = function()
        {
            if ( XHR.bDebug )  XHR.DebugOut( 'TimeOut', this ) ;
        },
        
        //    Connect to server
        xhr2.open( 'POST', 'XHR.php' ) ;
        
        //    Set Header Data Type
        xhr2.setRequestHeader( 'Content-type', 'application/x-www-form-urlencoded' ) ;
        
        //    send request data
        xhr2.send( 'data=' + oData.data ) ;
    },
    //    Send
    
    
    //    Load - Success
    Load: function( funcThis, Methods )
    {
    },
    //    Load
    
    
    //    Progress
    Progress: function( funcThis, Methods )
    {
        if ( XHR.bDebug )  console.log( 'XHR.Progress()   readyState: ' + funcThis.readyState + '   responseType: ' + funcThis.responseType + '   status: ' + funcThis.status + '   statusText: ' + funcThis.statusText + '   timeout: ' + funcThis.timeout ) ;
        
        //var data = JSON.parse( funcThis.response ) ;
        
        if ( 'Progress' in Methods )
        {
            Methods.Progress( data ) ;
        }
    },
    //    Progress
    
    
    DebugOut: function( sFunction, oXHR )
    {
        console.log( 'XHR.' + sFunction + '   readyState: ' + oXHR.readyState + '   responseType: ' + oXHR.responseType + '   status: ' + oXHR.status + '   statusText: ' + oXHR.statusText + '   timeout: ' + oXHR.timeout ) ;
    }
}
