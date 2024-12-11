<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Chat</title>
    <link rel="stylesheet" href="../css/chat-admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<div class="main-container">
        <!-- Sidebar -->
        <div class="chat-sidebar">
            <div class="sidebar-header">
                <div class="admin-profile">
                    <img src="../img/user.png" alt="Admin">
                    <span>Admin Portal</span>
                </div>
                <button class="back-button" onclick="window.location.href='admin-portal.php'">
                    <i class="fas fa-arrow-left"></i>
                </button>
            </div>
            <div class="user-list">
                <!-- Single active user -->
                <div class="user-chat active">
                    <img src="../img/user.png" alt="User">
                    <div class="user-info">
                        <h4>Current User</h4>
                        <p>Click to view conversation</p>
                    </div>
                    <span class="last-message-time">Now</span>
                </div>
            </div>
        </div>

    <div class="chat-container">
        <div class="chat-header">
            <img src="../img/user.png" alt="user">
            <h2 id="chat-header">User</h2>
            <div class="user-status">is Online...</div>

        </div>

        <div class="chat-box" id="chat-box">
        </div>


        <div class="quick-replies">
            <button class="quick-reply-btn" data-message="Thank you for reporting your lost item. Could you please provide more details about when and where you last saw it?">Request Details</button>
            <button class="quick-reply-btn" data-message="Good news! We've found an item matching your description. Please visit the lost and found office during business hours (9 AM - 5 PM) to identify and claim your item.">Item Found</button>
            <button class="quick-reply-btn" data-message="I've logged your lost item in our system. We'll notify you immediately if it's turned in to lost and found.">Log Confirmation</button>
            <button class="quick-reply-btn" data-message="For security purposes, could you please provide a detailed description of the item including any identifying marks or features?">Request Description</button>
        </div>


        <div class="input-area">
            <button class="attach-file" id="attach-file-btn">
                <i class="fas fa-paperclip"></i>
            </button>
            <span id="file-name"></span>
            <button id="remove-file-btn" style="display: none;">X</button>

            <input type="file" id="media-input" style="display: none;" accept="image/jpeg,image/png,video/mp4">
            <input type="text" id="message-input" placeholder="Type your message here...">
            <button id="send-btn" onclick="sendMessage()">Send</button>
        </div>
    </div>
</div>
    <script>
        const sender_id = 999;
        const recipient_id = 1;
        let lastMessageId = 0;
        let isSendingMessage = false;
        let lastTimestamp = "";

         // Quick reply functionality
         document.querySelectorAll('.quick-reply-btn').forEach(button => {
            button.addEventListener('click', function() {
                const messageInput = document.getElementById('message-input');
                messageInput.value = this.dataset.message;
                messageInput.focus();
            });
        });

        // Chat suggestions based on user input
        const chatSuggestions = {
            'lost': [
                'When did you last see your item?',
                'Could you describe the item in detail?',
                'What location did you last have the item?'
            ],
            'found': [
                'Thank you for finding this item. Where did you find it?',
                'Could you please bring the item to the lost and found office?',
                'Can you describe the condition of the item?'
            ],
            'where': [
                'Our lost and found office is located at [Location]. Opening hours are 9 AM - 5 PM.',
                'You can claim your item at our main office during business hours.',
                'Please bring identification when claiming your item.'
            ]
        };

        document.getElementById('message-input').addEventListener('input', function(e) {
            const input = e.target.value.toLowerCase();
            let suggestions = [];

            Object.keys(chatSuggestions).forEach(key => {
                if (input.includes(key)) {
                    suggestions = suggestions.concat(chatSuggestions[key]);
                }
            });

            // Remove existing suggestions
            const existingSuggestions = document.querySelector('.suggestions');
            if (existingSuggestions) {
                existingSuggestions.remove();
            }

            if (suggestions.length > 0) {
                const suggestionsDiv = document.createElement('div');
                suggestionsDiv.className = 'suggestions';
                suggestions.forEach(suggestion => {
                    const suggestionItem = document.createElement('div');
                    suggestionItem.className = 'suggestion-item';
                    suggestionItem.textContent = suggestion;
                    suggestionItem.addEventListener('click', () => {
                        e.target.value = suggestion;
                        suggestionsDiv.remove();
                    });
                    suggestionsDiv.appendChild(suggestionItem);
                });
                e.target.parentNode.insertBefore(suggestionsDiv, e.target.nextSibling);
            }
        });
        
        // Attach File button functionality
    document.getElementById('attach-file-btn').addEventListener('click', function() {
    document.getElementById('media-input').click();
    });

    document.getElementById('media-input').addEventListener('change', function(event) {
        const fileInput = event.target;
        const file = fileInput.files[0];
        const fileNameSpan = document.getElementById('file-name');
        const removeFileBtn = document.getElementById('remove-file-btn');
        const attachBtn = document.getElementById('attach-file-btn');

        if (file) {
            attachBtn.classList.add('has-file');
            fileNameSpan.textContent = file.name;
            fileNameSpan.style.display = 'inline-block';
            removeFileBtn.style.display = 'inline-block';
        } else {
            attachBtn.classList.remove('has-file');
            fileNameSpan.textContent = '';
            fileNameSpan.style.display = 'none';
            removeFileBtn.style.display = 'none';
        }
    });

    document.getElementById('remove-file-btn').addEventListener('click', function(e) {
        e.preventDefault(); // Prevent any default button behavior
        const attachBtn = document.getElementById('attach-file-btn');
        const mediaInput = document.getElementById('media-input');
        const fileNameSpan = document.getElementById('file-name');
        
        mediaInput.value = '';
        attachBtn.classList.remove('has-file');
        fileNameSpan.textContent = '';
        fileNameSpan.style.display = 'none';
        this.style.display = 'none';
    });

        // Modified sendMessage function
        function sendMessage() {
            if (isSendingMessage) return;
            isSendingMessage = true;

            const message = document.getElementById("message-input").value;
            const mediaInput = document.getElementById("media-input");
            const file = mediaInput.files[0];
            const attachBtn = document.getElementById('attach-file-btn');
            const fileNameSpan = document.getElementById('file-name');
            const removeFileBtn = document.getElementById('remove-file-btn');

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
                    
                    // Reset attachment button styles
                    attachBtn.classList.remove('has-file');
                    fileNameSpan.style.display = 'none';
                    fileNameSpan.textContent = '';
                    removeFileBtn.style.display = 'none';
                    
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