<?php
	/* Include the Twilio PHP library */
	require "twilio.php";

	/* Twilio REST API version */
	$ApiVersion = "2010-04-01";

	/* Set our AccountSid and AuthToken */
	$AccountSid = "AC5f884fcb0310c8d8e634f99bec5c5198";
	$AuthToken = "AC5f884fcb0310c8d8e634f99bec5c5198";
	
	// Outgoing Caller ID you have previously validated with Twilio 
	$CallerID = '9177338951';
	
	/* Instantiate a new Twilio Rest Client */
	$client = new TwilioRestClient($AccountSid, $AuthToken);

	/* Initiate a new outbound call by POST'ing to the Calls resource */
	$response = $client->request("/$ApiVersion/Accounts/$AccountSid/Calls", 
		"POST", array(
		"From" => $CallerID,
		"To" => "415-284-4619",
		"Url" => "http://demo.twilio.com/welcome/voice/"

	));
	if($response->IsError)
		echo "Error: {$response->ErrorMessage}";
	else
		echo "Started call: {$response->ResponseXml->Call->Sid}";
?>
