<?php

// Your AWS Access Key ID, as taken from the AWS Your Account page
$aws_access_key_id = "AKIAIOWFZ4KTTJAKNLFQ";

// Your AWS Secret Key corresponding to the above ID, as taken from the AWS Your Account page
$aws_secret_key = "DL6rUpqfXpMuQEVmiGGYgudKa0ePlbaR8OX4OjHB";

// The region you are interested in
$endpoint = "webservices.amazon.com";

$uri = "/onca/xml";

// get the keywords parameter from URL
$keywords = $_REQUEST["q"];

$params = array(
    "Service" => "AWSECommerceService",
    "Operation" => "ItemSearch",
    "AWSAccessKeyId" => "AKIAIOWFZ4KTTJAKNLFQ",
    "AssociateTag" => "q0d9b-20",
    "SearchIndex" => "All",
    "Keywords" => $keywords,
    "ResponseGroup" => "ItemAttributes,ItemIds,OfferListings"
);

// Set current timestamp if not set
if (!isset($params["Timestamp"])) {
    $params["Timestamp"] = gmdate('Y-m-d\TH:i:s\Z');
}

// Sort the parameters by key
ksort($params);

$pairs = array();

foreach ($params as $key => $value) {
    array_push($pairs, rawurlencode($key)."=".rawurlencode($value));
}

// Generate the canonical query
$canonical_query_string = join("&", $pairs);

// Generate the string to be signed
$string_to_sign = "GET\n".$endpoint."\n".$uri."\n".$canonical_query_string;

// Generate the signature required by the Product Advertising API
$signature = base64_encode(hash_hmac("sha256", $string_to_sign, $aws_secret_key, true));

// Generate the signed URL
$request_url = 'http://'.$endpoint.$uri.'?'.$canonical_query_string.'&Signature='.rawurlencode($signature);

// echo $request_url."<br>";

$response = file_get_contents($request_url);
// echo "<br>Response: \"".$response."\"";
$parsed_xml = simplexml_load_string($response);
// print_r($parsed_xml);
// print($parsed_xml->Items->Item->ItemAttributes->Title);

function printSearchResults($parsed_xml){
  $numOfItems = $parsed_xml->Items->TotalResults;
  print("<table>");
  if($numOfItems>0){
  foreach($parsed_xml->Items->Item as $current){
    // print("<td><font size='-1'><b>".$current->ItemAttributes->Title."</b>");
    if (isset($current->ASIN)) {
      print("<br>ASIN: ".$current->ASIN);
    }
    if (isset($current->ItemAttributes->Title)) {
      print("<br>Title: ".$current->ItemAttributes->Title);
    }
    if (isset($current->ItemAttributes->MPN)) {
      print("<br>MPN: ".$current->ItemAttributes->MPN);
    }
    if (isset($current->Offers->Offer->OfferListing->Price->FormattedPrice)){
      print("<br>Price: ".$current->Offers->Offer->OfferListing->Price->FormattedPrice."<br>");
    }
    // else{
    //   print("<center>No matches found.</center>");
    // };
  }
 }
}
printSearchResults($parsed_xml);

?>
