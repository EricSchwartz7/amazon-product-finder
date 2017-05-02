<?php
// ini_set('display_errors', 'On');
// error_reporting(E_ALL);

require ("config.php");

function APICall($keywords){
  $endpoint = "webservices.amazon.com";

  $uri = "/onca/xml";

  $params = array(
    "Service" => "AWSECommerceService",
    "Operation" => "ItemSearch",
    "AWSAccessKeyId" => AWS_ACCESS_KEY_ID,
    "AssociateTag" => ASSOCIATE_ID,
    "SearchIndex" => "All",
    "Keywords" => $keywords,
    "ResponseGroup" => "ItemAttributes,ItemIds,OfferListings,Images"
  );

  // Set current timestamp if not set
  if (!isset($params["Timestamp"])) {
    $params["Timestamp"] = gmdate('Y-m-d\TH:i:s\Z');
  };

  // Sort the parameters by key
  ksort($params);

  $pairs = array();

  foreach ($params as $key => $value) {
    array_push($pairs, rawurlencode($key)."=".rawurlencode($value));
  };

  // Generate the canonical query
  $canonical_query_string = join("&", $pairs);

  // Generate the string to be signed
  $string_to_sign = "GET\n".$endpoint."\n".$uri."\n".$canonical_query_string;

  // Generate the signature required by the Product Advertising API
  $signature = base64_encode(hash_hmac("sha256", $string_to_sign, AWS_SECRET_KEY, true));

  // Generate the signed URL
  $request_url = 'http://'.$endpoint.$uri.'?'.$canonical_query_string.'&Signature='.rawurlencode($signature);
  // print "<p>".$request_url."</p>";

  $response = @file_get_contents($request_url) or die('Request failed, please try again.');
  $parsed_xml = simplexml_load_string($response);
  printSearchResults($parsed_xml);
}

function connectToDB(){
  // Create connection to ClearDB
  $url = parse_url(getenv("CLEARDB_DATABASE_URL"));

  $server = $url["host"];
  $username = $url["user"];
  $password = $url["pass"];
  $db = substr($url["path"], 1);

  $conn = new mysqli($server, $username, $password, $db);

  // Create connection to local DB
  // $conn = mysqli_connect(DB_SERVERNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

  // Check connection
  if (!$conn) {
    return die("Connection failed: " . mysqli_connect_error());
  };

  return $conn;
}


function printSearchResults($parsed_xml){
  $numOfItems = $parsed_xml->Items->TotalResults;
  //Verify a successful request
  if ($parsed_xml->OperationRequest->Errors->Error){
    foreach($parsed_xml->OperationRequest->Errors->Error as $error){
      echo "Error code: " . $error->Code . "\r\n";
      echo $error->Message . "\r\n";
      echo "\r\n";
    };
  };
  if($numOfItems>0){
    $index = 0;
    foreach($parsed_xml->Items->Item as $current){
      print("<div id=".$index." class='item'>");
      print("<div class='col-lg-6 item-left'>");
      if (isset($current->ASIN)) {
        $asin = $current->ASIN;
        print("ASIN: <span class='asin'>".$asin."</span>");
      };
      if (isset($current->ItemAttributes->Title)) {
        $title = $current->ItemAttributes->Title;
        print("<br>Title: <span class='title'>".$title."</span>");
      };
      if (isset($current->ItemAttributes->MPN)) {
        $mpn = $current->ItemAttributes->MPN;
        print("<br>MPN: <span class='mpn'>".$mpn."</span>");
      };
      if (isset($current->Offers->Offer->OfferListing->Price->FormattedPrice)){
        $formattedPrice = $current->Offers->Offer->OfferListing->Price->FormattedPrice;
        print("<br>Price: <span class='price'>".$formattedPrice."</span>");
      };
      if (isset($current->ASIN)) {
        print("<br><div class='btn-db'><button type='button' class='btn btn-default' onclick='addToDB(".$index.")'>Add to DB</button></div>");
      };
      print("</div><div class='col-lg-6 item-right'>");
      if (isset($current->MediumImage->URL)){
        $img = $current->MediumImage->URL;
        print("<img src='".$img."'</img>");
      }
      print("</div></div>");
      $index++;
    };
  };
}

// Database interaction
function printData(){

  $conn = connectToDB();

  $sql = "SELECT asin, title, mpn, price FROM items";
  $result = mysqli_query($conn, $sql);

  if ($result->num_rows > 0) {
    echo "<table class='table table-hover'><tr><th>ASIN</th><th>Title</th><th>MPN</th><th>Price</th></tr>";
    // output data of each row
    while($row = $result->fetch_assoc()) {

      $price = $row["price"];
      settype($price, "string");

      if ($price == "0"){
        $price = "Not shown";
      }
      else {
        $price = "$".number_format($price/100, 2);
      };

      echo "<tr><td>".$row["asin"]
      ."<br><button type='button' class='btn btn-default' onclick=removeFromDB('".$row["asin"]."')>X</button></td>
      <td>".stripslashes($row["title"])."</td>
      <td>".$row["mpn"]."</td>
      <td>".$price."</td></tr>";
    }
    echo "</table>";
  } else {
    echo "0 results";
  }
  $conn->close();
}

function addToDB(){

  // Define the product attributes coming in
  $asin = $_POST["asin"];
  $title = addslashes($_POST["title"]);
  $mpn = $_POST["mpn"];
  $price = $_POST["price"];

  $conn = connectToDB();

  // Define the SQL query to add item data to DB
  $sql = "INSERT INTO items (asin, title, mpn, price) VALUES ('".$asin."', '".$title."', '".$mpn."', ".$price.")";

  // Fire the SQL query
  mysqli_query($conn, $sql);

  // Close the connection to DB
  $conn->close();

  // Refresh the DB for the client
  printData();
}

function removeFromDB(){
  $asin = $_POST["asin"];
  $conn = connectToDB();
  $sql = "DELETE FROM items WHERE (asin = '".$asin."') LIMIT 1";
  mysqli_query($conn, $sql);
  $conn->close();
  printData();
}

// Determine which request to handle, then trigger the appropriate function
if ($_REQUEST["q"]){
  $keywords = $_REQUEST["q"];
  APICall($keywords);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST["title"]) {
  addToDB();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
  removeFromDB();
} elseif (!$_REQUEST["q"]) {
  printData();
};

?>
