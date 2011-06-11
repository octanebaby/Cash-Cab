package com.twilio;

import java.util.HashMap;
import java.util.Map;

import com.twilio.sdk.TwilioRestClient;
import com.twilio.sdk.TwilioRestException;
import com.twilio.sdk.TwilioRestResponse;

public class ClickToCall {
    /* Twilio REST API version */
    public static final String APIVERSION = "2010-04-01";
    public static final String ACCOUNTSID = "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX";
    public static final String AUTHTOKEN = "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX";
    
    public static String makeCall(String to, String url){
        /* Outgoing Caller ID previously validated with Twilio */
        String CallerID = "NNNNNNNNNNNNNNN";

        /* Instantiate a new Twilio Rest Client */
        TwilioRestClient client = new TwilioRestClient(ACCOUNTSID, AUTHTOKEN, null);
        
        /*  
         * Initiate a new outbound call
         *         Is a POST to the Calls resource
         *         Returns a TwilioRestResponse object 
         */ 
        
        //build map of post parameters 
        Map<String,String> params = new HashMap<String,String>();
        params.put("From", CallerID);
        params.put("To", to);
        params.put("Url", url);
        String returnValue="";
        TwilioRestResponse response;
        try {
            response = client.request("/"+APIVERSION+"/Accounts/"+client.getAccountSid()+"/Calls", "POST", params);
        
            if(response.isError())
            	returnValue="Error making outgoing call: "+response.getHttpStatus()+"\n"+response.getResponseText();
            else {
            	returnValue=response.getResponseText();
            }
        } catch (TwilioRestException e) {
            e.printStackTrace();
        }
		return returnValue;
    }
}
