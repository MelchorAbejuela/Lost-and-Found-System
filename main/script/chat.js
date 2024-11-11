// chat.js

const sendBtn = document.getElementById('send-btn');
const messageInput = document.getElementById('message-input');
const chatBox = document.getElementById('chat-box');
const chatHeader = document.getElementById('chat-header');

// Set the user/admin label on the header
const isAdmin = false; // Set to true for admin, false for user
chatHeader.textContent = isAdmin ? "Admin Chat" : "User Chat";

// Function to append messages
function appendMessage(message, isUser) {
    const messageDiv = document.createElement('div');
    messageDiv.classList.add('message');

    const messageBubble = document.createElement('div');
    messageBubble.classList.add(isUser ? 'user-message' : 'admin-message');
    messageBubble.textContent = message;

    messageDiv.appendChild(messageBubble);
    chatBox.appendChild(messageDiv);
    chatBox.scrollTop = chatBox.scrollHeight; // Auto-scroll to bottom
}

// Event listener for sending messages
sendBtn.addEventListener('click', () => {
    const message = messageInput.value.trim();
    if (message) {
        appendMessage(message, true); // true for user message
        messageInput.value = '';
        // Simulate Admin response (you can replace this with real-time data)
        setTimeout(() => {
            appendMessage("Admin's response: " + message, false); // false for admin message
        }, 1000);
    }
});

// Allow pressing "Enter" to send the message
messageInput.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') {
        sendBtn.click();
    }
});
