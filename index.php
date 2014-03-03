<?php

/**
 * In a real system, most of this information would be in configuration globals
 * or server settings.  This file would likely be in a view and the javascript
 * possibly moved to its own file.
 *
 * Author: Tom Phillips
 * Date: 3/2/2014
 */
include 'twilio-php-latest/Services/Twilio/Capability.php';
require 'twilio-config.php';
 
// This is the dev account info
$accountSid = TWILIO_ACCOUNT_SID;
$authToken  = TWILIO_AUTH_TOKEN;
$appSid     = TWILIO_APP_SID;
$clientName = CLIENT_NAME;

// get the Twilio Client name from the page request parameters, if given
if (isset($_REQUEST['client'])) {
    $clientName = $_REQUEST['client'];
}

$capability = new Services_Twilio_Capability($accountSid, $authToken);
$capability->allowClientOutgoing($appSid);
$capability->allowClientIncoming($clientName);
$token = $capability->generateToken();
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Test Twilio Phone</title>
    <script type="text/javascript"
      src="//static.twilio.com/libs/twiliojs/1.1/twilio.min.js"></script>
    <script type="text/javascript"
      src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js">
    </script>
    <link href="css/main.css" type="text/css" rel="stylesheet" />
    <script type="text/javascript">

      Twilio.Device.setup("<?php echo $token; ?>");

      Twilio.Device.ready(function (device) {
	$("#log").text("Ready.");
      });

      Twilio.Device.error(function (error) {
        $("#log").text("Error: " + error.message);
      });

      Twilio.Device.connect(function (conn) {
        $("#log").text("Successfully established call");
      });

      Twilio.Device.disconnect(function (conn) {
        $("#log").text("Call ended");
      });

      Twilio.Device.incoming(function (conn) {
        $("#log").text("Incoming connection from " + conn.parameters.From);
        // accept the incoming connection and start two-way audio
        conn.accept();
      });

      Twilio.Device.presence(function (pres) {
        if (pres.available) {
          // create an item for the client that became available
          $("<li>", {id: pres.from, text: pres.from}).click(function () {
            $("#number").val(pres.from);
            call();
          }).prependTo("#people");
        }
        else {
          // find the item by client name and remove it
          $("#" + pres.from).remove();
        }
      });

      function call() {
        // get the phone number or client to connect the call to
        params = {"PhoneNumber": $("#number").val()};
        Twilio.Device.connect(params);
      }

      function hangup() {
        Twilio.Device.disconnectAll();
      }

      /**
       * Handle input via the .dialer keypad.
       */
      function addNumber(thisDiv) {
        var phoneNum;
        if (thisDiv.id != 'star' && thisDiv.id != 'pound') {
	    //Proceed if string is less than 10 digits, or less than 11 digits if it starts with a 1.
	    if ( ($("#number").val().length < 10) || ( ($("#number").val().charAt(0) == '1') && ($("#number").val().length < 11))) {
	        phoneNum = $("#number").val() + thisDiv.id;
	        $("#number").val(phoneNum);
	    }
        }
      }

      /**
       * Toggles the text messaging area which includes the text message send button.
       */
      function showTextingArea() {
          var newLinkText;
	  $("#textMessageArea").toggle();
	  if ($("#textLink").text() == "Send text") {
	      newLinkText = "Cancel text";
          } else {
	      newLinkText = "Send text";
          }

	  $("#textLink").text(newLinkText);
      }

      /**
       * Updates the character count of the current text message length so the user knows how
       * many more characters they have before it's rejected.
       */
      function updateCount()
      {
	  var len;
	  len = $("#textMessage").val().length;
	  $("#textCharacterCount").text("(" + len + "/160)");
      }

      /**
       * Send an AJAX request to send the text message via the Twilio REST client.  This
       * AJAX request is -NOT- Asynchronous because we do actually care if there was an
       * error message.
       */
      function sendTextMessage()
      {
	 var xmlhttp;
	 xmlhttp = new XMLHttpRequest();

	 $("#log").text("Sending..");
	 xmlhttp.open("POST", "sendText.php", false);
	 xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	 $("#sendText").text("Sending...");
	 xmlhttp.send("number=" + $("#number").val() + "&textMessage=" + $("#textMessage").val());
	 $("#sendText").text("Send");

	 $("#log").text(xmlhttp.responseText);
      }

    </script>
  </head>
  <body>
    <div id="phonePad" class="dialer">
      <h3>Test Phone</h3>

      <div class="phoneNumberField">
        <input type="text" class="phoneNumber" id="number" name="number" placeholder="Enter a phone number to call"/>
      </div>

      <div class="textCell">
          <div id="textCellLink" class="textButton"><a href="#" id="textLink" onclick="showTextingArea();">Send text</a></div>
	  <div id="textMessageArea" class="textMessage" style="display: none;">
	    <textarea id="textMessage" name="textMessage" onInput="updateCount();"></textarea>
	    <a href="#" id="sendText" class="sendText" onclick="sendTextMessage();">Send</a>
	    <div id="textCharacterCount">(0/160)</div>
          </div>
      </div>

      <div id="log">Loading...</div>

      <div class="numberRow">
        <div id="1" class="numberButton" onclick="addNumber(this);">
	  <div class="number">1</div>
          <div class="letters">&nbsp;</div>
        </div>
        <div id="2" class="numberButton" onclick="addNumber(this);">
	  <div class="number">2</div>
          <div class="letters">ABC</div>
        </div>
        <div id="3" class="numberButton" onclick="addNumber(this);">
	  <div class="number">3</div>
	  <div class="letters">DEF</div>
        </div>
      </div>

      <div class="numberRow">
        <div id="4" class="numberButton" onclick="addNumber(this);">
	  <div class="number">4</div>
          <div class="letters">GHI</div>
        </div>
        <div id="5" class="numberButton" onclick="addNumber(this);">
	  <div class="number">5</div>
          <div class="letters">JKL</div>
        </div>
        <div id="6" class="numberButton" onclick="addNumber(this);">
	  <div class="number">6</div>
	  <div class="letters">MNO</div>
        </div>
      </div>

      <div class="numberRow">
        <div id="7" class="numberButton" onclick="addNumber(this);">
	  <div class="number">7</div>
          <div class="letters">PQRS</div>
        </div>
        <div id="8" class="numberButton" onclick="addNumber(this);">
	  <div class="number">8</div>
          <div class="letters">TUV</div>
        </div>
        <div id="9" class="numberButton" onclick="addNumber(this);">
	  <div class="number">9</div>
	  <div class="letters">WXYZ</div>
        </div>
      </div>

      <div class="numberRow">
        <div id="star" class="numberButton" onclick="addNumber(this);">
	  <div class="number">*</div>
          <div class="letters">&nbsp;</div>
        </div>
        <div id="0" class="numberButton" onclick="addNumber(this);">
	  <div class="number">0</div>
          <div class="letters">&nbsp;</div>
        </div>
        <div id="pound" class="numberButton" onclick="addNumber(this);">
	  <div class="number">#</div>
	  <div class="letters">&nbsp;</div>
        </div>
      </div>

      <div class="buttons">
        <button class="call" onclick="call();">
          Call
        </button>

        <button class="hangup" onclick="hangup();">
          Hangup
        </button>
      </div>
    </div>
    <!-- Twilio client/buddy list would eventually go here -->
    <ul id="people"/>
  </body>
</html>
