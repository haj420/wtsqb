<?
# @Author: Kroes, William
# @Date:   29-Jun-2021
# @Email:  charwebsllc@gmail.com
# @GitHub: https://github.com/haj420/wtsqb
# @Last modified by:   williamkroes
# @Last modified time: 22-Sep-2021
# @License: MIT
# @Copyright: Start Advertising, LLC

if(!$_POST) {
	echo "<form method='post'>";
	echo "<input type='text' name='emailAddy' />";
	echo "<input type='submit' value='submit' />";
	echo "</form>";
	exit();
}

/*
 * TESTING OPTIONS
 */
$emailAddy = $_POST['emailAddy'];
error_reporting(E_ALL);
require 'vendor/autoload.php';


use QuickBooksOnline\API\Core\ServiceContext;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\PlatformService\PlatformService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Customer;


include('oauth.php');

$dataService->setLogLocation("/Users/hlu2/Desktop/newFolderForLog");

$dataService->throwExceptionOnError(true);

// Run a query to check for customer
$entities = $dataService->Query("Select * from Customer WHERE PrimaryEmailAddr LIKE '$emailAddy'");

$error = $dataService->getLastError();
if ($error) {
    echo "The Status code is: " . $error->getHttpStatusCode() . "\n";
    echo "The Helper message is: " . $error->getOAuthHelperError() . "\n";
    echo "The Response message is: " . $error->getResponseBody() . "\n";
    exit();
}
// Echo some formatted output
	//echo '<script>alert("Customer exists.")</script>';

if($entities == 0) {
	echo '<script>alert("Adding customer '.$emailAddy.'.")</script>';
	// Add a customer
	$customerObj = Customer::create([
	  "BillAddr" => [
	     "Line1"=>  "123 Main Street",
	     "City"=>  "Rock Hill",
	     "Country"=>  "USA",
	     "CountrySubDivisionCode"=>  "SC",
	     "PostalCode"=>  "29730"
	 ],
	 "Notes" =>  "Here are other details.",
	 "Title"=>  "Mr",
	 "GivenName"=>  "William2",
	 "MiddleName"=>  "J",
	 "FamilyName"=>  "Kroes2",
	 "Suffix"=>  "",
	 "FullyQualifiedName"=>  "William Kroes2",
	 "CompanyName"=>  "Start Sandbox Group, LLC",
	 "DisplayName"=>  "Start Sandbox Group, LLC",
	 "PrimaryPhone"=>  [
	     "FreeFormNumber"=>  "(555) 555-5555"
	 ],
	 "PrimaryEmailAddr"=>  [
	     "Address" => "$emailAddy"
	 ]
	]);
	$resultingCustomerObj = $dataService->Add($customerObj);
	$error = $dataService->getLastError();
	if ($error) {
	    echo "The Status code is: " . $error->getHttpStatusCode() . "\n";
	    echo "The Helper message is: " . $error->getOAuthHelperError() . "\n";
	    echo "The Response message is: " . $error->getResponseBody() . "\n";
	} else {
		echo '<script>alert("Customer created '.$emailAddy.' with ID: '.$resultingCustomerObj->Id.'.")</script>';
	}

}

if($entities > 0) {
	//echo '<script>alert("Customer exists. ID: '.$dataService->Id.'")</script>';
	//$CustomerRef = $entities->Id;
	foreach($entities as $resultingCustomerObj) {
		echo "<script>alert('Customer exists. ID: ".$resultingCustomerObj->Id."'. Emailing at {$emailAddy})</script>";
	}
}

//Add a new Invoice
$theResourceObj = Invoice::create([
     "Line" => [
   [
     "Amount" => 5000.00,
     "DetailType" => "SalesItemLineDetail",
     "SalesItemLineDetail" => [
       "ItemRef" => [
         "value" => 2,
         "name" => "TWR1001",
        ]
      ]
      ]
    ],
"CustomerRef"=> [
  "value"=> "$resultingCustomerObj->Id"
],
      "BillEmail" => [
            "Address" => "$emailAddy"
      ],
      "BillEmailCc" => [
            "Address" => ""
      ],
      "BillEmailBcc" => [
            "Address" => "v@intuit.com, charwebsllc@gmail.com"
      ],
],
);

$resultingObj = $dataService->Add($theResourceObj);


$error = $dataService->getLastError();
if ($error) {
    echo "The Status code is: " . $error->getHttpStatusCode() . "\n";
    echo "The Helper message is: " . $error->getOAuthHelperError() . "\n";
    echo "The Response message is: " . $error->getResponseBody() . "\n";
}
else {
    echo "Created invoice Id={$resultingObj->Id} and emailed to {$emailAddy}. Reconstructed response body:<br><br><br>";

	/*
	 * Trying to use the SendEmail method of data object to send
	 * the customer an email with invoice attached.  Maybe you
	 * have to find the invoice created online and then send it?
	 */

	 $invoice = $dataService->FindById("invoice", $resultingObj->Id,);
 	 $result = $dataService->SendEmail($invoice, $emailAddy);

	#$invoice->SendEmail($resultingObj->Id, 'charwebsllc@gmail.com');
    $xmlBody = XmlObjectSerializer::getPostXmlFromArbitraryEntity($resultingObj, $urlResource);
    echo $xmlBody . "\n";
}
