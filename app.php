<?php
// ini_set('display_errors', 'On');
// error_reporting(E_ALL);

require ("config.php");

$endpoint = "webservices.amazon.com";

$uri = "/onca/xml";

// get the keywords parameter from URL
if ($_REQUEST["q"]){
  $keywords = $_REQUEST["q"];
};

$params = array(
    "Service" => "AWSECommerceService",
    "Operation" => "ItemSearch",
    "AWSAccessKeyId" => AWS_ACCESS_KEY_ID,
    "AssociateTag" => ASSOCIATE_ID,
    "SearchIndex" => "All",
    "Keywords" => $keywords,
    "ResponseGroup" => "ItemAttributes,ItemIds,OfferListings"
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

$response = file_get_contents($request_url);
$parsed_xml = simplexml_load_string($response);

function printSearchResults($parsed_xml){
  $numOfItems = $parsed_xml->Items->TotalResults;
  // print("<table>");
  if($numOfItems>0){
    $index = 0;
    foreach($parsed_xml->Items->Item as $current){
      print("<div id=".$index." class='item'>");
      if (isset($current->ASIN)) {
        $asin = $current->ASIN;
        print("<br>ASIN: <span class='asin'>".$asin."</span>");
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
        // $price = $current->Offers->Offer->OfferListing->Price->Amount;
        print("<br>Price: <span class='price'>".$formattedPrice."</span>");
      };
      if (isset($current->ASIN)) {
        // print("<br><button type='button' onclick='addToDB(\"".$asin."\", \"".$title."\", \"".$mpn."\", ".$price.")'>Add to DB</button><br>");
        print("<br><button type='button' onclick='addToDB(".$index.")'>Add to DB</button>");
      };
      print("</div>");
      $index++;
    };
  };
}
printSearchResults($parsed_xml);


// Database interaction
function printData(){

  // Create connection
  $conn = mysqli_connect(DB_SERVERNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
  // Check connection
  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  $sql = "SELECT asin, title, mpn, price FROM items";
  $result = mysqli_query($conn, $sql);

  if ($result->num_rows > 0) {
    echo "<table><tr><th>ASIN</th><th>Title</th><th>MPN</th><th>Price</th></tr>";
    // output data of each row
    while($row = $result->fetch_assoc()) {
      // echo "<tr><td>".$row["asin"]."</td></tr>";

      $price = $row["price"];
      settype($price, "string");
      if ($price == "0"){
        $price = "Not shown";
      }
      else {
        $price = "$".number_format($price/100, 2);
      };

      echo "<tr><td>".$row["asin"]
      ."</td><td>".stripslashes($row["title"])
      ."</td><td>".$row["mpn"]
      ."</td><td>".$price
      ."</td></tr>";
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

  // print("<h1>".$price."</h1>");

  // Create connection
  $conn = mysqli_connect(DB_SERVERNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
  // Check connection
  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }

  $sql = "INSERT INTO items (asin, title, mpn, price) VALUES ('".$asin."', '".$title."', '".$mpn."', ".$price.")";

  mysqli_query($conn, $sql);

  $conn->close();

  printData();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  addToDB();
} elseif (!$_REQUEST["q"]) {
  printData();
}

?>
