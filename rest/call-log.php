<?php
	/* Include the Twilio PHP library */
	require "twilio.php";

	/* Twilio REST API version */
	$ApiVersion = "2010-04-01";

	/* Set our AccountSid and AuthToken */
	$AccountSid = "ACXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX";
	$AuthToken = "YYYYYYYYYYYYYYYYYYYYYYYYYYYYYYYY";

	/* Instantiate a new Twilio Rest Client */
	$client = new TwilioRestClient($AccountSid, $AuthToken);

	/* Get Recent Calls */
	$response = $client->request("/$ApiVersion/Accounts/$AccountSid/Calls", "GET");
	if($response->IsError) {
		echo "Error: {$response->ErrorMessage}";
	} else {
		// iterate over calls
		foreach($response->ResponseXml->Calls->Call AS $call)
			echo "Call from {$call->From} to {$call->To} at {$call->StartTime} of length: {$call->Duration}";
	}
?>
