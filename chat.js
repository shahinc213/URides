const sendBtn = document.getElementById("send");
const form = document.getElementById("typing-area");
const inputField = document.getElementById("message");
const chatbox = document.getElementById("chat-history");
const chatModal = document.getElementById("chat-modal");
const closeChatBtn = document.getElementById("close-chat");

// let sender_id = 1;  // Placeholder for logged-in user
// let receiver_id = null;  // Will be set dynamically

form.onsubmit = (e) => {
    e.preventDefault();
};

function sendChat() {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "chat.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = () => {
        if (xhr.status === 200) {
            inputField.value = ""; // Clear input field after sending
        }
    };
    let params = `sender_id=${sender_id}&receiver_id=${receiver_id}&message=${inputField.value}`;
    xhr.send(params);
}

document.addEventListener("keypress", function(event) {
    if (event.key === "Enter") {
        event.preventDefault();
        sendChat();
    }
});

sendBtn.onclick = () => {
    sendChat();
};

// Open the chat modal
document.querySelectorAll('.chat-btn').forEach(button => {
    button.addEventListener('click', () => {
        receiver_id = button.getAttribute('data-user-id'); // Set receiver_id dynamically
        chatModal.style.display = 'flex'; // Show chat modal
        loadChatHistory();
    });
});

// Close the chat modal
closeChatBtn.addEventListener('click', () => {
    chatModal.style.display = 'none';
});

// Fetch new messages every 500ms
setInterval(() => {
    if (receiver_id) {
        loadChatHistory();
    }
}, 500);

function loadChatHistory() {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "get-chat.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = () => {
        if (xhr.status === 200) {
            let data = xhr.response;
            chatbox.innerHTML = data; // Update chatbox with new messages
        }
    };
    let params = `sender_id=${sender_id}&receiver_id=${receiver_id}`;
    xhr.send(params);
}
