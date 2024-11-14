<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Chat</title>
    <link rel="stylesheet" href="../css/chat-admin.css">
</head>
<body>
    <div class="chat-container">
        <div class="chat-header">
            <img src="../img/user.png" alt="user">
            <h2 id="chat-header">User</h2>
            <div class="user-status">is Online... </div>
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
        const sender_id = 999; // Set admin ID
        const recipient_id = 1; // Set user ID
        let lastMessageId = 0; // Track the last message ID loaded
        let isSendingMessage = false; // Flag to prevent double sending
        let lastTimestamp = "";


        // Function to send message
        function sendMessage() {
            if (isSendingMessage) return;
            isSendingMessage = true;

            const message = document.getElementById("message-input").value;
            if (message.trim() === "") {
                isSendingMessage = false;
                return;
            }

            const sendButton = document.getElementById("send-btn");
            sendButton.disabled = true;

            fetch("send-message.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: `sender_id=${sender_id}&recipient_id=${recipient_id}&message=${message}&role=admin`,
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    document.getElementById("message-input").value = '';
                    loadMessages();
                } else {
                    alert(data.message);
                }

                sendButton.disabled = false;
                isSendingMessage = false;
            });
        }

        // Listen for Enter key press to send the message
        document.getElementById("message-input").addEventListener("keydown", function(event) {
            if (event.key === "Enter") {
                sendMessage();
            }
        });

        // Function to load messages with timestamps
            function loadMessages() {
                fetch(`load-messages.php?sender_id=${sender_id}&recipient_id=${recipient_id}&last_id=${lastMessageId}`)
                .then(response => response.json())
                .then(messages => {
                    const chatBox = document.getElementById("chat-box");
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
                        msgDiv.className = "message " + (message.sender_id === sender_id ? 'admin-message' : 'user-message');

                        const img = document.createElement("img");
                        img.src = message.sender_id === sender_id ? '../img/user.png' : '../img/user.png';
                        img.alt = message.sender_id === sender_id ? 'Admin' : 'User';

                        const messageContentDiv = document.createElement("div");
                        messageContentDiv.className = "message-content";
                        messageContentDiv.textContent = message.message;

                        msgDiv.appendChild(img);
                        msgDiv.appendChild(messageContentDiv);
                        chatBox.appendChild(msgDiv);

                        if (message.id > lastMessageId) {
                            lastMessageId = message.id;
                            newMessagesAdded = true;
                        }
                    });

                    if (newMessagesAdded) {
                        chatBox.scrollTop = chatBox.scrollHeight;
                    }
                });
        }

        setInterval(loadMessages, 2000);
        loadMessages();
    </script>
</body>
</html>
