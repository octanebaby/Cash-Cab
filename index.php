<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Cash Cab!</title>
	<style type="text/css">
	body,
	html {
		font-family: Helvetica;
		margin:0;
		padding:0;
		color:0000;
		background-color:#FFFFF
	}
	#wrap {
		width:1200px;
		margin:0 auto;
	}
	#header {
		padding:5px 0px 5px 100px;
	}
	h1 {
	    margin:0;
	}
	#nav {
		padding:5px 10px;
	}
	#leftbar {
		float: left;
		width: 200px;
		min-height: 400px;
		margin: 10 0 0 0;
		padding: 10 10 10 10;
		background-color: #FFFF00;
		font-family: Helvetica;
		font-size: 12px;
		color: #00000;
	}
	#streams {
		text-align: center;
	}
	object.stream {
		padding: 10 0 0 0;
	}
	#main {
		float:left;
		width:900px;
		padding:10px;
		font-family: Impact;
		font-size: 14px;
		line-height: 19px;
	}
	p.title {
		font-size: 42px;
	}
	h2 {
		margin:0 0 1em;
	}
	div.section {
		font-weight: bold;
		font-size: 18px;
		padding: 15 0 8 0;
	}
	div.description {
	}
	div.left-pic {
		float:left;
		padding-right: 10;
	}
	div.right-pic {
		float:right;
		padding-left: 10;
	}
	div.controls {
		float:right;
		width:450px;
		padding:10 100 10 0;
		font-size: 14px;
	}
	div.driver {
		float:right;
		width:375px;
		padding:10 10 10 10;
		font-size: 12px;
		background: 00000;
	}
	#footer {
		clear:both;
		padding:15px 10px;
		font-size: 12px;
	}
	#footer p {
		margin:0;
        }
	* html #footer {
		height:1px;
	}
	.publisherContainer {
		position:relative;
		left:20px;
	}
	</style>

        <script type="text/javascript" src="http://staging.tokbox.com/v0.90/js/TB.min.js"></script>
	
	<script type="text/javascript">
                var thePartnerKey = 1127;  // TBD: tokbox internal key
                var theSessionId = "1734c97817df685be716ad3e5027531be2d7bfca";
                var theSession = null;
                var thePublisher = null;

                var debug = false; // Set to true if you want event alerting

                // Stand on our heads to get the sessionId out of the URL
                // to enable many concurrent demos
        
                theSessionId =  paramsParse("sessionId") != "" ? paramsParse("sessionId") : theSessionId;
                
                function paramsParse(name){
                          name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");            
                          var regexS = "[\\?&]"+name+"=([^&#]*)";
                          var regex = new RegExp( regexS );
                          var results = regex.exec( window.location.href );
                          if( results == null )
                           return "";
                          else
                          return results[1];
                }

		// Dont try to handle anything; just pump exception
		// messages to a Javascript alert box for now
		function exceptionHandler(e) {
			alert("Exception: "+e.code+"::"+e.message);
		}

		// Generic function to dump streamEvents to the alert box
		function dumpStreams(streams, reason) {
			for (var i=0; i<streams.length; i++) {
				alert("streamID: "+streams[i].streamId + "\n" +
					"connectionId: "+streams[i].connection.connectionId +" \n" +
					"type: "+streams[i].type +"\n" +
					"name: "+streams[i].name +"\n" +
					"reason: "+reason);
			}
		}

		// Generic function to dump connectionEvents to the alert box
		function dumpConnections(connections, reason) {
			for (var i=0; i<connections.length; i++) {
				alert("connectionId: "+connections[i].connectionId +" \n" +
					"reason: "+reason);
			}
		}

                // Action functions

		// Called when user wants to start participating in the call
		function startPublishing() {
			// Starts publishing user local camera and mic 
			// as a stream into the session

			var parentDiv = document.getElementById("myCamera");
			var stubDiv = document.createElement("div");
			stubDiv.id = "tbx_publisher";
			parentDiv.appendChild(stubDiv);

			// TBD: failed silently when div ID didn't exist
                        thePublisher = theSession.publish(stubDiv.id, {width: 160, height: 120});

			document.getElementById("action").innerHTML = "&nbsp;";
		}

		// Called when user wants to stop participating in the call
		function stopPublishing() {
			if (thePublisher != null) {
				// Stop the stream
				theSession.unpublish(thePublisher);
				thePublisher = null;
			}

			document.getElementById("action").innerHTML = "&nbsp;";
		}

		// Called to subscribe to a new stream
                function subscribeToStream(session, stream) {
                        // Create a div for the subscribe widget to replace

                        var parentDiv = document.getElementById("streams");
                        var stubDiv = document.createElement("div");
                        stubDiv.id = "tbx_subscriber_" + stream.streamId;
                        parentDiv.appendChild(stubDiv);

                        session.subscribe(stream, stubDiv.id, {width: 160, height: 120});
                }

		// Called to unsubscribe from an existing stream
		function unsubscribeFromStream(session, stream) {
			var subscribers = session.getSubscribersForStream(stream);

			for (var i=0; i<subscribers.length; i++)
				session.unsubscribe(subscribers[i]);
		}


                // Handler functions

		function sessionConnectedHandler(e) {
			if (debug) {
				alert("sessionConnectedHandler");
				dumpConnections(e.connections, "");
				dumpStreams(e.streams, "");
			}

			// Now possible to join a call

			document.getElementById("action").innerHTML = '<a href="javascript:startPublishing()">Play!</a>';

			// Display streams on screen
			for (var i=0; i<e.streams.length; i++)
				subscribeToStream(e.target, e.streams[i]);
		}

		function streamCreatedHandler(e) {
			if (debug) {
				alert("streamCreatedHandler");
				dumpStreams(e.streams, "");
			}

			// Display streams on screen.  Note that
			// we will get a streamCreated event for ourselves
			// when we successfully start publishing

			for (var i=0; i<e.streams.length; i++) {
				if (e.streams[i].connection.connectionId != e.target.connection.connectionId)
					subscribeToStream(e.target, e.streams[i]);
				else
					document.getElementById("action").innerHTML = '<a href="javAscript:stopPublishing()">Just Watch</a>';
			}
		}

		function streamDestroyedHandler(e) {
			if (debug) {
				alert("streamDestroyedHandler");
				dumpStreams(e.streams, e.reason);
			}

			// Remove streams from screen.  Note that
			// we will get a streamDestroyed event for ourselves
			// when we successfully stop publishing

			for (var i=0; i<e.streams.length; i++) {
				if (e.streams[i].connection.connectionId != e.target.connection.connectionId)
					unsubscribeFromStream(e.target, e.streams[i]);
				else
					document.getElementById("action").innerHTML = '<a href="javascript:startPublishing()">Play!</a>';
			}
		}

	</script>
</head>
<body>
<div id="wrap">
<div id="main">
   <p class="title">
		CASH CAB!<img src="powered-by-tokbox.png" style="padding-bottom:4px; vertical-align: middle;"/>&nbsp 		</p>
</div>
       <div class="controls">
       </div>
	<div class="driver">
	   <h2>THE DRIVER SEAT</h2>
	   <object data="http://tokbox.com/bizdev/cashcab/index2.html#" type="text/html" width="350px" height="250px"></object>
<BR>
<h2>SHOUT OUT!</h2>
<script src="http://platform.twitter.com/widgets.js" type="text/javascript"></script>
<div><a href="http://twitter.com/share" class="twitter-share-button"
  data-count="none" data-text="Cash Cab Shout Out! What's the Answer?">Tweet</a></div>
<form action="call-request.php">
  <p> <input type="text" name="TO" placeholder="Type Number Here"><input type="submit" value="Call your friend"></p>
</form>
  

 	</div>
	 <div id="leftbar">
	         <span style="font-weight: bold; font-size: 18px">THE BACK SEAT</span>
                <div id="streams"></div>
		<div id="myCamera" class="publisherContainer"></div>
                <div id="action"><a href="javascript:startPublishing()">Play!</a></div>
        </div>


</div>

        <script type="text/javascript">
                // Set debugging level if you want; displays to a div with id="tokbox_console"
                // TB.setLogLevel(TB.DEBUG);

                if (theSessionId == "") {
                        alert("Please contact TokBox Business Development to enable this demo.  Thanks!");
                } else {
			// Create the local session object
			theSession = TB.initSession(theSessionId);

			// Register all the callbacks that route events to
			// Javascript functions

			theSession.addEventListener("sessionConnected", sessionConnectedHandler);
			theSession.addEventListener("streamCreated", streamCreatedHandler);
			theSession.addEventListener("streamDestroyed", streamDestroyedHandler);
			TB.addEventListener("exception", exceptionHandler);

			// Connect to the session

			theSession.connect(thePartnerKey, "devtoken");
                }
        </script>

</body>
</html>
