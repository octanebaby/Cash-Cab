<?php
require "twilio.php";

/* Twilio REST API version */
$ApiVersion = "2010-04-01";

/* Set our AccountSid and AuthToken */
$AccountSid = "AC5f884fcb0310c8d8e634f99bec5c5198";
$AuthToken = "18cb75a2d8b347efba5424816278d372";

/* Outgoing Caller ID you have previously validated with Twilio */
$number = '9177338951';

/* Outgoing Number you wish to call */
$outgoing = '4152844619';

/* Directory location for callback.php file (for use in REST URL)*/
$url = 'http://www.tokbox.com/bizdev/cashcab/clicktocall/';

/* Instantiate a new Twilio Rest Client */
$client = new TwilioRestClient($AccountSid, $AuthToken);

if (!isset($_REQUEST['called'])) {
    $err = urlencode("Must specify your phone number");
    header("Location: index.php?msg=$err");
    die;
}

/* make Twilio REST request to initiate outgoing call */
$response = $client->request("/$ApiVersion/Accounts/$AccountSid/Calls",
    "POST", array(
        "From" => $number,
        "To" => $outgoing,
        "Url" => $url . 'callback.php?number=' . $_REQUEST['called']
    ));
    
if($response->IsError) {
    $err = urlencode($response->ErrorMessage);
    header("Location: index.php?msg=$err");
    die;
}

/* redirect back to the main page with CallSid */
$msg = urlencode("Connecting... ".$_REQUEST['called']);
header("Location: index.php?msg=$msg");
?>
