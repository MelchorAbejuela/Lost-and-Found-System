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

        <div class="chat-box" id="chat-box">
        </div>

        <div class="input-area">
            <input type="text" id="message-input" placeholder="Type your message here...">
            <button id="send-btn" onclick="sendMessage()">Send</button>
        </div>
    </div>

    <script>
        const sender_id = 999;
        const recipient_id = 1;
        let lastMessageId = 0;
        let isSendingMessage = false;
        let lastTimestamp = "";

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

        document.getElementById("message-input").addEventListener("keydown", function(event) {
            if (event.key === "Enter") {
                sendMessage();
            }
        });

        function loadMessages() {
            fetch(`load-messages.php?sender_id=${sender_id}&recipient_id=${recipient_id}&last_id=${lastMessageId}`)
            .then(response => response.json())
            .then(messages => {
                const chatBox = document.getElementById("chat-box");
                let newMessagesAdded = false;

                messages.forEach(message => {
                    const messageTime = new Date(message.timestamp).toLocaleTimeString([], { 
                        hour: '2-digit', 
                        minute: '2-digit' 
                    });

                    if (messageTime !== lastTimestamp) {
                        const timestampDiv = document.createElement("div");
                        timestampDiv.className = "timestamp";
                        timestampDiv.textContent = messageTime;
                        chatBox.appendChild(timestampDiv);
                        lastTimestamp = messageTime;
                    }

                    const msgDiv = document.createElement("div");
                    msgDiv.className = "message " + (message.sender_id === sender_id ? 'admin-message' : 'user-message');

                    const img = document.createElement("img");
                    img.src = '../img/user.png';
                    img.alt = message.sender_id === sender_id ? 'Admin' : 'User';
                    img.className = 'user-avatar';

                    const messageContentDiv = document.createElement("div");
                    messageContentDiv.className = "message-content";
                    
                    // Add text message if it exists
                    if (message.message.trim() !== '') {
                        const textDiv = document.createElement("div");
                        textDiv.className = "message-text";
                        textDiv.textContent = message.message;
                        messageContentDiv.appendChild(textDiv);
                    }

                    // Add media content if it exists
                    if (message.file_path) {
                        const mediaDiv = document.createElement("div");
                        mediaDiv.className = "media-message";

                        if (message.file_type.startsWith("image")) {
                            const mediaImg = document.createElement("img");
                            mediaImg.src = message.file_path;
                            mediaImg.alt = "Shared Image";
                            mediaImg.className = "shared-media";
                            mediaDiv.appendChild(mediaImg);
                        } else if (message.file_type.startsWith("video")) {
                            const video = document.createElement("video");
                            video.src = message.file_path;
                            video.controls = true;
                            video.className = "shared-media";
                            mediaDiv.appendChild(video);
                        }

                        messageContentDiv.appendChild(mediaDiv);
                    }

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
            })
            .catch(error => {
                console.error('Error loading messages:', error);
            });
        }

        setInterval(loadMessages, 2000);
        loadMessages();
    </script>
</body>
</html>