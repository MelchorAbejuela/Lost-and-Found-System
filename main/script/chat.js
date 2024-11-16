const sendBtn = document.getElementById('send-btn');
const messageInput = document.getElementById('message-input'); 
const chatBox = document.getElementById('chat-box');
const fileInput = document.getElementById('file-input');  // New file input element
const fileName = document.getElementById('file-name');  // Span to show the file name
let lastMessageId = 0; 

// Function to append a message to the chat
function appendMessage(message, isUser, file = null) {
    const messageDiv = document.createElement('div');
    messageDiv.classList.add('message');

    const messageBubble = document.createElement('div');
    messageBubble.classList.add(isUser ? 'user-message' : 'admin-message');
    messageBubble.textContent = message;

    if (file) {
        const filePreview = document.createElement('img');
        filePreview.src = URL.createObjectURL(file);
        filePreview.alt = file.name;
        filePreview.style.maxWidth = '150px';  // Adjust preview size as necessary
        messageBubble.appendChild(filePreview);
    }

    messageDiv.appendChild(messageBubble);
    chatBox.appendChild(messageDiv);
    chatBox.scrollTop = chatBox.scrollHeight;
}

// Function to send a message to the server, including any attached file
function sendMessage() {
    const message = messageInput.value.trim();
    const file = fileInput.files[0];  // Get the selected file

    if (message || file) {
        const formData = new FormData();
        formData.append('sender_id', '1');
        formData.append('recipient_id', '999');
        formData.append('message', message);
        formData.append('role', 'user');
        if (file) {
            formData.append('file', file);  // Attach file to FormData
        }

        fetch("send-message.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                messageInput.value = ''; // Clear input
                fileInput.value = ''; // Clear file input
                fileName.style.display = 'none'; // Hide file name
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
                        message.sender_id === 1, 
                        message.file ? message.file : null // Handle file message
                    );
                    lastMessageId = message.id; // Update lastMessageId
                }
            });
        })
        .catch(error => console.error('Error fetching messages:', error));
}

// Handle file selection and display file name indicator
fileInput.addEventListener('change', function () {
    const file = fileInput.files[0];
    if (file) {
        fileName.textContent = `Attached: ${file.name}`;
        fileName.style.display = 'inline-block'; // Show the file name
    } else {
        fileName.style.display = 'none'; // Hide the file name if no file is selected
    }
});

// Event listeners for sending messages
sendBtn.addEventListener('click', sendMessage);
messageInput.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') {
        sendMessage();
    }
});

// Real-time polling for new messages every 2 seconds
setInterval(fetchMessages, 2000);
