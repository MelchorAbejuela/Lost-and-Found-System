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
            <h2 id="chat-header">Admin Chat</h2>
        </div>
    
        <!-- Chat Area -->
        <div class="chat-box" id="chat-box">
            <!-- Messages will appear here -->
        </div>

        <div class="input-area">
            <input type="text" id="message" placeholder="Type your message here...">
            <button id="send-btn" onclick="sendMessage()">Send</button>
        </div>

    </div>

    <script>
        const sender_id = 999; // Set admin ID
        const recipient_id = 1; // Set user ID

        // Function to send message
        function sendMessage() {
            const message = document.getElementById("message").value;

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
                    document.getElementById("message").value = '';
                    loadMessages(); // Refresh chat
                } else {
                    alert(data.message);
                }
            });
        }

        // Function to load messages
        function loadMessages() {
            fetch(`load-messages.php?sender_id=${sender_id}&recipient_id=${recipient_id}`)
                .then(response => response.json())
                .then(messages => {
                    const chatBox = document.getElementById("chat-box");
                    chatBox.innerHTML = '';
                    messages.forEach(message => {
                        const msgDiv = document.createElement("div");
                        msgDiv.textContent = `${message.sender_id === sender_id ? 'You' : 'User'}: ${message.message}`;
                        chatBox.appendChild(msgDiv);
                    });
                    chatBox.scrollTop = chatBox.scrollHeight; // Auto-scroll to bottom
                });
        }

        // Load messages every 2 seconds
        setInterval(loadMessages, 2000);
        loadMessages(); // Initial load
    </script>

    <script src="../script/chat.js"></script>
</body>
</html>
