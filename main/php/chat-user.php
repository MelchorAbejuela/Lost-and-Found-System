<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Chat</title>
    <link rel="stylesheet" href="../css/chat-user.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="chat-container">
        <div class="chat-header">
            <img src="../img/user.png" alt="user">
            <h2 id="chat-header">Admin</h2>
            <div class="user-status">is Online...</div>
        </div>

        <div class="chat-box" id="chat-box">
        </div>

        <div class="input-area">
            <button class="attach-file" id="attach-file-btn">
                <i class="fas fa-paperclip"></i>
            </button>
            <span id="file-name">No file selected</span>
            <button id="remove-file-btn" style="display: none;">X</button>

            <input type="file" id="media-input" style="display: none;" accept="image/jpeg,image/png,video/mp4">
            <input type="text" id="message-input" placeholder="Type your message here...">
            
            <button id="send-btn" onclick="sendMessage()">Send</button>
        </div>
    </div>

    <script>
    const sender_id = 1;
    const recipient_id = 999;
    let lastMessageId = 0;
    let isSendingMessage = false;

    // Attach File button functionality
    document.getElementById('attach-file-btn').addEventListener('click', function() {
        document.getElementById('media-input').click();
    });

    document.getElementById('media-input').addEventListener('change', function(event) {
        const fileInput = event.target;
        const file = fileInput.files[0];
        const fileNameSpan = document.getElementById('file-name');
        const removeFileBtn = document.getElementById('remove-file-btn');

        if (file) {
            fileNameSpan.textContent = file.name;
            fileNameSpan.style.display = 'inline';
            removeFileBtn.style.display = 'inline';
        } else {
            fileNameSpan.textContent = 'No file selected';
            fileNameSpan.style.display = 'none';
            removeFileBtn.style.display = 'none';
        }
    });

    document.getElementById('remove-file-btn').addEventListener('click', function() {
        document.getElementById('media-input').value = '';
        document.getElementById('file-name').textContent = 'No file selected';
        document.getElementById('file-name').style.display = 'none';
        document.getElementById('remove-file-btn').style.display = 'none';
    });

    function sendMessage() {
        if (isSendingMessage) return;
        isSendingMessage = true;

        const message = document.getElementById("message-input").value;
        const mediaInput = document.getElementById("media-input");
        const file = mediaInput.files[0];

        if (message.trim() === "" && !file) {
            isSendingMessage = false;
            return;
        }

        const sendButton = document.getElementById("send-btn");
        sendButton.disabled = true;

        const formData = new FormData();
        formData.append("sender_id", sender_id);
        formData.append("recipient_id", recipient_id);
        formData.append("message", message);
        formData.append("role", "user");

        // Changed 'media' to 'file' to match PHP expecting $_FILES['file']
        if (file) {
            formData.append("file", file);
        }

        fetch("send-message.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                document.getElementById("message-input").value = '';
                mediaInput.value = '';
                document.getElementById('file-name').textContent = 'No file selected';
                document.getElementById('file-name').style.display = 'none';
                document.getElementById('remove-file-btn').style.display = 'none';
                loadMessages();
            } else {
                alert(data.message || 'Error sending message');
            }

            sendButton.disabled = false;
            isSendingMessage = false;
        })
        .catch(error => {
            console.error('Error:', error);
            sendButton.disabled = false;
            isSendingMessage = false;
            alert('Error sending message');
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
            let lastTimestamp = '';
            let newMessagesAdded = false;

            messages.forEach(message => {
                const messageTime = new Date(message.timestamp).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

                if (messageTime !== lastTimestamp) {
                    const timestampDiv = document.createElement("div");
                    timestampDiv.className = "timestamp";
                    timestampDiv.textContent = messageTime;
                    chatBox.appendChild(timestampDiv);
                    lastTimestamp = messageTime;
                }

                const msgDiv = document.createElement("div");
                msgDiv.className = "message " + (message.sender_id === sender_id ? 'user-message' : 'admin-message');

                const img = document.createElement("img");
                img.src = '../img/user.png';
                img.alt = message.sender_id === sender_id ? 'User' : 'Admin';

                const messageContent = document.createElement("div");
                messageContent.className = "message-content";

                const textDiv = document.createElement("div");
                textDiv.textContent = message.message;
                messageContent.appendChild(textDiv);

                if (message.file_path) {
                    const mediaDiv = document.createElement("div");
                    mediaDiv.className = "media-message";

                    if (message.file_type.startsWith("image")) {
                        const img = document.createElement("img");
                        img.src = message.file_path;
                        img.alt = "Image";
                        mediaDiv.appendChild(img);
                    } else if (message.file_type.startsWith("video")) {
                        const video = document.createElement("video");
                        video.src = message.file_path;
                        video.controls = true;
                        mediaDiv.appendChild(video);
                    }

                    messageContent.appendChild(mediaDiv);
                }

                // Append elements in the correct order based on message type
                if (message.sender_id === sender_id) {
                    msgDiv.appendChild(messageContent);
                    msgDiv.appendChild(img);
                } else {
                    msgDiv.appendChild(img);
                    msgDiv.appendChild(messageContent);
                }

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