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
            <img src="../img/user.png" alt="user">
            <h2 id="chat-header">Admin</h2>
            <div class="user-status">is Online...</div>
        </div>

        <!-- Chat Area -->
        <div class="chat-box" id="chat-box">
            <!-- Messages will appear here -->
        </div>

        <div class="input-area">
            <input type="text" id="message-input" placeholder="Type your message here...">
            <button id="send-btn" onclick="sendMessage()">Send</button>
        </div>
    </div>

    <script>
        const sender_id = 1; // Set user ID
        const recipient_id = 999; // Set admin ID
        let lastMessageId = 0; // Track the last message ID loaded
        let isSendingMessage = false; // Flag to prevent double sending

        // Function to send message
        function sendMessage() {
            if (isSendingMessage) return; // Prevent sending multiple messages simultaneously
            isSendingMessage = true;

            const message = document.getElementById("message-input").value;
            if (message.trim() === "") {
                isSendingMessage = false; // Reset flag if the message is empty
                return; // Prevent sending empty messages
            }

            // Disable the send button temporarily
            const sendButton = document.getElementById("send-btn");
            sendButton.disabled = true;

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

                // Re-enable the send button
                sendButton.disabled = false;
                isSendingMessage = false; // Reset flag
            });
        }

        // Listen for Enter key press to send the message
        document.getElementById("message-input").addEventListener("keydown", function(event) {
            if (event.key === "Enter") {
                sendMessage(); // Call sendMessage when Enter is pressed
            }
        });

       // Function to load messages with timestamps
function loadMessages() {
    fetch(`load-messages.php?sender_id=${sender_id}&recipient_id=${recipient_id}&last_id=${lastMessageId}`)
        .then(response => response.json())
        .then(messages => {
            const chatBox = document.getElementById("chat-box");

            // Keep track of the last timestamp to avoid repeating it
            let lastTimestamp = '';

            // Append each new message
            let newMessagesAdded = false;
            messages.forEach(message => {
                // Format timestamp for display, e.g., "12:30 PM"
                const messageTime = new Date(message.timestamp).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

                // Insert timestamp only if it’s different from the last one
                if (messageTime !== lastTimestamp) {
                    const timestampDiv = document.createElement("div");
                    timestampDiv.className = "timestamp";
                    timestampDiv.textContent = messageTime;
                    chatBox.appendChild(timestampDiv);

                    lastTimestamp = messageTime; // Update last timestamp
                }

                const msgDiv = document.createElement("div");
                msgDiv.className = "message " + (message.sender_id === sender_id ? 'user-message' : 'admin-message');

                // Create user icon (admin or user)
                const img = document.createElement("img");
                img.src = message.sender_id === sender_id ? '../img/user.png' : '../img/user.png';
                img.alt = message.sender_id === sender_id ? 'User' : 'Admin';

                const messageDiv = document.createElement("div");
                messageDiv.textContent = message.message;

                msgDiv.appendChild(img);
                msgDiv.appendChild(messageDiv);
                chatBox.appendChild(msgDiv);

                // Update the last message ID
                if (message.id > lastMessageId) {
                    lastMessageId = message.id;
                    newMessagesAdded = true;
                }
            });

            // Auto-scroll to bottom only if new messages were added
            if (newMessagesAdded) {
                chatBox.scrollTop = chatBox.scrollHeight;
            }
        });
}


        // Load messages every 2 seconds without clearing previous messages
        setInterval(loadMessages, 2000);
        loadMessages(); // Initial load
    </script>
</body>
</html>
