<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Chat</title>
    <link rel="stylesheet" href="../css/chat-user.css">
</head>
<body>
    <div class="chat-container">
        <div class="chat-header">
            <h2 id="chat-header">User Chat Interface</h2>
        </div>
    
        <!-- Chat Area -->
        <div class="chat-box" id="chat-box">
            <!-- Messages will appear here -->
        </div>

        <div class="input-area">
            <input type="text" id="message-input" placeholder="Type your message here...">
            <button id="send-btn">Send</button>
        </div>

    </div>
    
</body>
</html>

<script src="../script/chat.js"></script>

<script>
    const sender_id = 1; // Set user ID
    const recipient_id = 999; // Set admin ID
    let lastMessageId = 0; // Track the last message ID loaded

    // Function to send message
    function sendMessage() {
        const message = document.getElementById("message-input").value;

        if (message.trim() === "") return; // Prevent sending empty messages

        fetch("send-message.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `sender_id=${sender_id}&recipient_id=${recipient_id}&message=${message}&role=user`,
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                document.getElementById("message-input").value = ''; // Clear input
                loadMessages(); // Refresh chat
            } else {
                alert(data.message);
            }
        });
    }

    // Function to load messages, only adds new ones
    function loadMessages() {
        fetch(`load-messages.php?sender_id=${sender_id}&recipient_id=${recipient_id}&last_id=${lastMessageId}`)
            .then(response => response.json())
            .then(messages => {
                const chatBox = document.getElementById("chat-box");

                // Append each new message
                messages.forEach(message => {
                    const msgDiv = document.createElement("div");
                    msgDiv.className = message.sender_id === sender_id ? 'user-message' : 'admin-message';
                    msgDiv.textContent = message.message;
                    chatBox.appendChild(msgDiv);

                    // Update the last message ID
                    if (message.id > lastMessageId) {
                        lastMessageId = message.id;
                    }
                });

                // Auto-scroll to bottom
                chatBox.scrollTop = chatBox.scrollHeight;
            });
    }

    // Prevent form submission on 'Enter' key press, and call sendMessage()
    document.getElementById('message-input').addEventListener('keypress', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault(); // Prevent form submission
            sendMessage(); // Call the sendMessage function
        }
    });

    // Prevent double message submission on button click
    document.getElementById('send-btn').addEventListener('click', function(event) {
        event.preventDefault(); // Prevent default button behavior
        sendMessage(); // Call the sendMessage function
    });

    // Load messages every 2 seconds without clearing previous messages
    setInterval(loadMessages, 2000);
    loadMessages(); // Initial load
</script>
