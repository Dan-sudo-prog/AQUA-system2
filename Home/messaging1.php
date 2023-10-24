<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Sample messaging page</title>
</head>
<body>
	<div id="message container">
		<div id="message-display">
			<!-- messages will appear here -->
		</div>
		<input type="text" id="message-input" name="message" placeholder="Type your message">
		<button id="send-button">Send</button>
	</div>
	<script type="text/javascript">
		//Connect to websocket server
		const socket = new WebSocket('ws://localhost');
	</script>
</body>
</html>