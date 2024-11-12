const sendBtn = document.getElementById('send-btn');
const messageInput = document.getElementById('message-input'); 
const chatBox = document.getElementById('chat-box');
let lastMessageId = 0; 

// Function to append a message to the chat
function appendMessage(message, isUser) {
    const messageDiv = document.createElement('div');
    messageDiv.classList.add('message');

    const messageBubble = document.createElement('div');
    messageBubble.classList.add(isUser ? 'user-message' : 'admin-message');
    messageBubble.textContent = message;

    messageDiv.appendChild(messageBubble);
    chatBox.appendChild(messageDiv);
    chatBox.scrollTop = chatBox.scrollHeight;
}

// Function to send a message to the server
function sendMessage() {
    const message = messageInput.value.trim();
    if (message) {
        fetch("send-message.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `sender_id=1&recipient_id=999&message=${encodeURIComponent(message)}&role=user`
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                messageInput.value = ''; // Clear input
                fetchMessages(); // Fetch new messages to update chat
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error sending message:', error));
    }
}

// Function to fetch new messages from the server
function fetchMessages() {
    fetch(`load-messages.php?sender_id=1&recipient_id=999&last_id=${lastMessageId}`)
        .then(response => response.json())
        .then(messages => {
            messages.forEach(message => {
                if (message.id > lastMessageId) { // Ensure only new messages are appended
                    appendMessage(
                        message.message,
                        message.sender_id === 1 
                    );
                    lastMessageId = message.id; // Update lastMessageId
                }
            });
        })
        .catch(error => console.error('Error fetching messages:', error));
}

// Event listeners for sending messages
sendBtn.addEventListener('click', sendMessage);
messageInput.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') {
        sendMessage();
    }
});

// Real-time polling for new messages every 2 seconds
setInterval(fetchMessages, 2000);
