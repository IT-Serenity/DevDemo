<?php
/**
    Created By: IT Serenity
    =======================================================================
    Created On: 2021 - 05 - 06
    =======================================================================
    Original Developer: Rob Couch
    =======================================================================
    Purpose: Home Page
    =======================================================================
    Modifications: Date - Developer - Description Of Coding Change
    -----------------------------------------------------------------------
    2)
    -----------------------------------------------------------------------
    1)
**/
if ( !isset($_SESSION) ) session_start() ;

LogIt( __DIR__, __FILE__, __CLASS__, __FUNCTION__, __LINE__, 'Instantiate Customers', 'Flow' ) ;

require_once( 'class.Customers.php' ) ;

//    Get List Of Current Customers
$oCustomers = new Customers( $oDB ) ;

LogIt( __DIR__, __FILE__, __CLASS__, __FUNCTION__, __LINE__, 'Calling Customers->List()', 'Flow' ) ;

$aList = $oCustomers->GetList() ;
$sHTML = $aList[ 'list' ] ;

//$aC = $oCustomers->Get( 1 ) ;
//LogIt( __DIR__, __FILE__, __CLASS__, __FUNCTION__, __LINE__, var_export( $aC, true ), 'Flow' ) ;
?>
    <!-- ********************************************************************************************************** -->
    <!-- **********  Home  **************************************************************************************** -->
    <!-- ********************************************************************************************************** -->
        <h2>
            HTML, CSS, JavaScript, PHP, XHR2, And MySQL Developer Demonstration
        </h2>
        <div id="Page" >
            <div id="ListDIV" >
                <div>
                    <h3>
                        Customers
                    </h3>
                    <div id="Searching" >
                        <form id="SearchFrm" action="Shit.php" method="post" onsubmit="return false;" >
                            <label for="Search" >Search &nbsp;</label>
                            <input type="text" id="Search" maxlength="320" minlength="1" placeholder="Enter To Search, Escape To Reset" />
                        </form>
                    </div>
                </div>
                <div class="List" >
                    <div class="thead" >
                        <div alt="Change Sort Order"  title="Change Sort Order" class="headcell priority" data-id="Priority" >Priority</div><div alt="Change Sort Order"  title="Change Sort Order" class="headcell" data-id="First" >First</div><div alt="Change Sort Order"  title="Change Sort Order" class="headcell" data-id="Last" >Last</div><div alt="Change Sort Order"  title="Change Sort Order" class="headcell" data-id="Phone" >Phone</div><div alt="Change Sort Order"  title="Change Sort Order" class="headcell email" data-id="Email" >Email</div>
                    </div>
                    <div class="tbody" id="Customers" >
<?php
echo $sHTML ;
?>
                    </div>
                </div>
                <div id="Natural" style="display: none;" >
                    Reset List To Natural Order
                </div>
            </div>
            <div style="margin: 10px 10px; display: inline-block; vertical-align: top; min-width: 240px; " >
                <div id="FormHead" >
                    <h3>
                        Customer
                    </h3>
                    <div id="CustomerID" >
                        ID: &nbsp; New &nbsp;
                    </div>
                </div>
                <form id="Customer" action="Bad.php" method="post" >
                    <input type="hidden" id="ID" value="0" />
                    <p>
                        <label id="lblFirst" for="First" >First Name</label>
                        <label id="reqFirst" for="First" class="Required" style="display: none;" >First Name - Required</label>
                        <br />
                        <input autofocus type="text" id="First" maxlength="32" minlength="1" required />
                    </p>
                    <p>
                        <label id="lblLast" for="Last" >Last Name</label>
                        <label id="reqLast" for="Last" class="Required" style="display: none;" >Last Name - Required</label>
                        <br />
                        <input type="text" id="Last" maxlength="32" minlength="1" required />
                    </p>
                    <p>
                        <label id="lblEmail" for="Email" >Email</label>
                        <label id="reqEmail" for="Email" class="Required" style="display: none;" >Email - Required</label>
                        <br />
                        <input type="text" id="Email" maxlength="320" minlength="3" required />
                    </p>
                    <p>
                        <label id="lblPhone" for="Phone" >Phone</label>
                        <label id="reqPhone" for="Phone" class="Required" style="display: none;" >Phone - Required</label>
                        <br />
                        <input type="tel" id="Phone" maxlength="12" minlength="10" required placeholder="###-###-####" />
                    </p>
                    <p>
                        <label id="lblPriority" for="Priority" >Priority</label>
                        <label id="reqPriority" for="Priority" class="Required" style="display: none;" >Priority - Required</label>
                        <br />
                        <select id="Priority" style="width: 100%;" >
                            <option value="1" >1 - Concerned</option>
                            <option value="2" >2 - Important</option>
                            <option value="3" >3 - Serious</option>
                            <option value="4" >4 - Urgent</option>
                            <option value="5" >5 - Dangerous</option>
                        </select>
                    </p>
                    <div class="buttons" >
                        <p>
                            <button type="button" id="add" >Add</button>
                        </p>
                        <p>
                            <button type="button" id="create" style="display: none;" >Create New</button>
                        </p>
                        <p>
                            <button type="button" id="update" style="display: none;" >Update</button>
                        </p>
                        <p>
                            <button type="button" id="delete" style="display: none;" >Delete</button>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    <!-- ********************************************************************************************************** -->
    <!-- **********  Home  **************************************************************************************** -->
    <!-- ********************************************************************************************************** -->
