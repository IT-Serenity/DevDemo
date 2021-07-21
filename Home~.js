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
console.log( 'Home: Linked' ) ;

bScript = true ;    //    Not Ready To For Production

Script = {
    
    //    Properties
    displays: {
        Customers  : null,
        CustomerID : null,
    },
    
    form: {
        ID       : null,
        First    : null,
        Last     : null,
        Phone    : null,
        Email    : null,
        Priority : null,
    },
    
    elements: {
        Customer : null,
        Search   : null,
        Natural  : null,
    },
    
    bttns: {
        add    : null,
        create : null,
        update : null,
        delete : null,
    },
    
    CurrentSortOrder : 'ID',
    
    init : function()
    {
        console.log( 'Home: init()' ) ;
        
        Script.GetElements( Script.displays ) ;
        Script.GetElements( Script.form ) ;
        Script.GetElements( Script.bttns ) ;
        Script.GetElements( Script.elements ) ;
        Script.ListNew() ;
        Script.ObjectListeners( Script.bttns ) ;
        Script.form.Email.addEventListener( 'blur', Script.EmailValidate ) ;
        Script.ElementEvents( '.headcell', 'click', Script.ListOrder ) ;
        Script.elements.Search.addEventListener( 'keyup', Script.Search ) ;
        Script.form.Phone.addEventListener( 'keyup', Script.Phone ) ;
        Script.elements.Natural.addEventListener( 'click', Script.Natural ) ;
    },
    
    
    ObjectListeners : function( oObj )
    {
        console.log( 'ObjectListeners()' ) ;
        
        for ( sObj in oObj )
        {
            if ( null !== oObj[ sObj ] )
            {
                //console.log( 'sObj:  ' + sObj ) ;
                oObj[ sObj ].addEventListener( 'click', Script.ButtonClicked ) ;
            }
        }
    },
    
    
    PhoneValidate : function()
    {
        var bGood = true ;
        
        if ( Script.form.Phone.value.length > 0 )
        {
            if (/^(\([0-9]{3}\) |[0-9]{3}-)[0-9]{3}-[0-9]{4}/.test( Script.form.Phone.value ) )
            {
                //    We Are Good
            }
            else
            {
                bGood = false ;
                alert( "Phone number entered is invalid!" ) ;
            }
        }
        
        return bGood ;
    },
    
    
    Phone : function( event )
    {
        console.log( 'Key:  ' + event.keyCode ) ;
        
        var nKey = ( event.keyCode * 1 ) ;
        var bRetrun = true ;
        
        //    Is Not A Number Or Is A Hyphen
        //    After Three & Seven Characters Add A Hyphen
        if ( isNaN( nKey ) )
        {
            bRetrun = false ;
        }
        else
        {
            console.log( 'Phone Length:  ' + Script.form.Phone.value.length ) ;
            
            if ( ( ( 3 == Script.form.Phone.value.length ) || ( 7 == Script.form.Phone.value.length ) ) && ( 8 != nKey ) )
            {
                Script.form.Phone.value = Script.form.Phone.value + '-' ;
            }
        }
    },
    
    Search : function( event )
    {
        var nLength = Script.elements.Search.value.length ;
        
        console.log( 'Key:  ' + event.keyCode + '   Length:  ' + nLength ) ;
        
        //    Key Codes: 13 Enter, 27 Escape
        
        if ( ( 13 == event.keyCode ) && ( nLength > 0 ) )
        {
            console.log( 'Value:  ' + Script.elements.Search.value ) ;
            
            var oData = {
                object : 'Customers',
                action : 'Search',
                data : { 
                    Phrase : Script.elements.Search.value,
                },
            } ;
            
            var sJSON = JSON.stringify( oData ) ;
            
            //console.log( sJSON );
            
            //    perform xhr
            XHR.Request(
                {
                    Error   : Script.XHRError,
                    Success : Script.SrchSuccess,
                    data    : sJSON,
                },
            ) ;
        }
        else if ( 27 == event.keyCode ) 
        {
            Script.elements.Search.value = '' ;
            Script.ListOrder( null ) ;
        }
    },
    
    
    SrchSuccess: function( response )
    {
        console.log( 'XHRError()' ) ;
        console.log( response.status ) ;
        
        Script.ListClear() ;
        Script.displays.Customers.innerHTML = response.list ;
        Script.ListNew() ;
    },
    
    ButtonClicked : function( event )
    {
        console.log( event.path[0].id ) ;
        
        Script.ElementsDisabled( Script.bttns, true ) ;
        
        if ( 'add' == event.path[0].id )
        {
            Script.bttns.add.innerHTML = 'Saving' ;
            Script.CustomerAdd() ;
            Script.bttns.add.innerHTML = 'Add' ;
        }
        else if ( 'create' == event.path[0].id )
        {
            Script.ElementsDisplay( Script.bttns, 'none' ) ;
            Script.displays.CustomerID.innerHTML = 'ID: &nbsp; New &nbsp;' ;
            Script.Reset() ;
        }
        else if ( 'update' == event.path[0].id )
        {
            Script.bttns.update.innerHTML = 'Saving' ;
            Script.CustomerUpdate() ;
            Script.bttns.update.innerHTML = 'Update' ;
        }
        else if ( 'delete' == event.path[0].id )
        {
            Script.bttns.delete.innerHTML = 'Deleting' ;
            Script.CustomerDelete() ;
            Script.ElementsDisplay( Script.bttns, 'none' ) ;
            Script.bttns.update.innerHTML = 'Delete' ;
            Script.Reset() ;
        }
        
        Script.ElementsDisabled( Script.bttns, false ) ;
        
        return false;
    },
    
    ElementsDisplay : function( oList, sDisplay )
    {
        for ( sElement in oList )
        {
            oList[ sElement ].style.display = sDisplay ;
        }
    },
    
    
    ElementsDisabled : function( oList, bDisabled )
    {
        for ( sElement in oList )
        {
            oList[ sElement ].disabled = bDisabled ;
        }
    },
    
    
    EmailValidate : function()
    {
        console.log( 'Checking Email' ) ;
        
        var bReturn = true ;
        var sEmail = Script.form.Email.value ;
        
        if ( sEmail.length > 0 )
        {
            sEmail = sEmail.trim() ;
            sEmail = sEmail.toLowerCase() ;
            
            Script.form.Email.value = sEmail ;
            
            if (/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test( Script.form.Email.value ) )
            {
                console.log( 'Good Email' ) ;
            }
            else
            {
                bReturn = false ;
                alert( "You have entered an invalid email address!" ) ;
            }
        }
        
        return bReturn ;
    },
    
    
    Natural : function()
    {
        Script.CurrentSortOrder = 'ID' ;
        
        var oData = {
            object : 'Customers',
            action : 'GetList',
            data : { 
                Order : 'ID',
            },
        } ;
    
        var sJSON = JSON.stringify( oData ) ;
        
        //console.log( sJSON );
        
        //    perform xhr
        XHR.Request(
            {
                Error   : Script.XHRError,
                Success : Script.OrdSuccess,
                data    : sJSON,
            },
        ) ;
        
        Script.elements.Natural.style.display = 'none' ;
    },
    
    
    ListOrder : function( event )
    {
        var sOrder =  ( null == event ? Script.CurrentSortOrder : event.path[0].dataset.id ) ;
        
        
        console.log( 'ListOrder()  sOrder:  ' + sOrder ) ;
        
        var oData = {
            object : 'Customers',
            action : 'GetList',
            data : { 
                Order : sOrder,
            },
        } ;
        
        Script.CurrentSortOrder = sOrder ;
        
        var sJSON = JSON.stringify( oData ) ;
        
        //console.log( sJSON );
        
        //    perform xhr
        XHR.Request(
            {
                Error   : Script.XHRError,
                Success : Script.OrdSuccess,
                data    : sJSON,
            },
        ) ;
        
        if ( 'ID' != sOrder )
        {
            Script.elements.Natural.style.display = '' ;
        }
    },
    
    
    OrdSuccess: function( response )
    {
        console.log( 'OrdSuccess()' ) ;
        console.log( response.status ) ;
        
        Script.ListClear() ;
        Script.displays.Customers.innerHTML = response.list ;
        Script.ListNew() ;
    },
    
    
    CustomerDelete : function()
    {
        console.log( 'CustomerDelete()' ) ;
        
        var nID = Script.form.ID.value ;
        
        if ( !isNaN( nID ) )
        {
            var oData = {
                object : 'Customers',
                action : 'Delete',
                data : { 
                    ID : nID,
                },
            } ;
        
            var sJSON = JSON.stringify( oData ) ;
            
            //console.log( sJSON );
            
            //    perform xhr
            XHR.Request(
                {
                    Error   : Script.XHRError,
                    Success : Script.DelSuccess,
                    data    : sJSON,
                },
            ) ;
        }
    },
    
    
    DelSuccess: function( response )
    {
        console.log( 'DelSuccess()' ) ;
        console.log( response.status ) ;
        
        Script.ListClear() ;
        Script.displays.Customers.innerHTML = response.list ;
        Script.ListNew() ;
    },
    
    
    CustomerAdd : function()
    {
        console.log( 'CustomerAdd()' ) ;
        
        Script.Set() ;
    },
    
    
    CustomerUpdate : function()
    {
        console.log( 'CustomerUpdate()' ) ;
        
        Script.Set() ;
    },
    
    
    Reset : function()
    {
        Script.elements.Customer.reset() ;
        Script.form.ID.value = 0 ;
        Script.displays.CustomerID.innerHTML = 'ID: &nbsp; New &nbsp;' ;
        Script.form.Priority.value = 1 ;
        
        for ( sField in Script.form )
        {
            if ( ( 'ID' != sField ) && ( 'Priority' != sField ))
            {
                var sLbl = 'lbl' + sField ;
                var sReq = 'req' + sField ;
                
                //console.log( 'sLbl:  ' + sLbl + '   sReq:  ' + sReq ) ;
                
                $.getElementById( sLbl ).style.display = '' ;
                $.getElementById( sReq ).style.display = 'none' ;
            }
        }
        
        Script.bttns.add.style.display = '' ;
        Script.form.First.focus() ;
    },
    
    
    Set : function()
    {
        console.log( 'Set()' ) ;
        
        var oData = {
            object : 'Customers',
            action : 'Set',
            data : {},
        } ;
        
        var bGood = true ;
        
        //    Validate Data
        bGood = Script.EmailValidate() ;
        bGood = Script.PhoneValidate() ;
        
        for ( sField in Script.form )
        {
            //onsole.log( 'sField:  ' + sField ) ;
            
            if ( 0 == Script.form[ sField ].value.length )
            {
                if ( bGood )
                {
                    Script.form[ sField ].focus() ;
                }
                
                bGood = false ;
                $.getElementById( 'lbl' + sField ).style.display = 'none' ;
                $.getElementById( 'req' + sField ).style.display = '' ;
            }
            
            
            if ( null != Script.form[ sField ] )
            {
                oData.data[ sField ] = Script.form[ sField ].value ;
                //console.log( 'Script.form[ sField ].value:  ' + Script.form[ sField ].value ) ;
            }
        }
        
        if ( bGood )
        {
            //console.log( oData );
            
            var sJSON = JSON.stringify( oData ) ;
            
            //console.log( sJSON );
            
            //    perform xhr
            XHR.Request(
                {
                    Error   : Script.XHRError,
                    Success : Script.SetSuccess,
                    data    : sJSON,
                },
            ) ;
            
            Script.Reset() ;
        }
    },
    
    
    SetSuccess: function( response )
    {
        console.log( 'SetSuccess()' ) ;
        console.log( response.status ) ;
        
        Script.ListClear() ;
        Script.displays.Customers.innerHTML = response.list ;
        Script.ListNew() ;
    },
    
    
    ElementEvents : function( sClass, sEvent, fFunc )
    {
        var oDIVs = $.querySelectorAll( sClass ) ;
        var nLen = oDIVs.length ;
        
        //console.log( 'nLen:  ' + nLen ) ;
        
        for ( var x = 0; x < nLen; ++x )
        {
            oDIVs[ x ].addEventListener( sEvent, fFunc ) ;
        }
    },
    
    ListNew : function()
    {
        Script.ListWork( true ) ;
    },
    
    ListClear : function()
    {
        Script.ListWork( false ) ;
    },
    
    ListWork : function( bAdd )
    {
        var oDIVs = $.querySelectorAll( '.bodyrow' ) ;
        var nLen = oDIVs.length ;
        
        console.log( 'nLen:  ' + nLen ) ;
        
        for ( var x = 0; x < nLen; ++x )
        {
            console.log( 'Index:  ' + x + '   ID:  ' + oDIVs[ x ].dataset.id ) ;
            
            if ( null !== oDIVs[ x ] )
            {
                if ( bAdd )
                {
                    oDIVs[ x ].addEventListener( 'click', Script.RowClick ) ;
                }
                else
                {
                    oDIVs[ x ].removeEventListener( 'click', Script.RowClick ) ;
                }
            }
        }
    },
    
    RowClick : function( event )
    {
        console.log( event ) ;
        
        Script.Reset() ;
        
        var nID = event.path[1].dataset.id ;
        
        console.log( nID ) ;
        
        Script.displays.CustomerID.innerHTML = 'ID: &nbsp; ' + nID + ' &nbsp;' ;
        
        var oData = {
            object : 'Customers',
            action : 'Get',
            data : { ID: nID, },
        } ;
        
        var sJSON = JSON.stringify( oData ) ;
            
        //console.log( sJSON );
        
        //    perform xhr
        XHR.Request(
            {
                Error   : Script.XHRError,
                Success : Script.RowSuccess,
                data    : sJSON,
            },
        ) ;
    },
    
    
    RowSuccess: function( response )
    {
        console.log( 'RowSuccess()' ) ;
        
        Script.ElementsDisplay( Script.bttns, 'none' ) ;
        
        for ( sFld in response )
        {
            if ( 'Created' !== sFld )
            {
                Script.form[ sFld ].value = response[ sFld ] ;
            }
        }
        
        Script.ElementsDisplay( Script.bttns, '' ) ;
        Script.bttns.add.style.display = 'none' ;
    },
    
    
    GetElements : function( oList )
    {
        for ( element in oList )
        {
            oList[ element ] = $.getElementById( element ) ;
        }
    },
    
    
    XHRError: function()
    {
        console.log( 'XHRError()' ) ;
    },
} ;
