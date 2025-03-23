document.addEventListener("DOMContentLoaded", function() {
    function sendMessage() {
        let userInput = document.getElementById("user-input").value;
        if (userInput.trim() === "") return;

        let chatBox = document.getElementById("chat-box");

        let userMessage = document.createElement("div");
        userMessage.classList.add("message", "user-message");
        userMessage.innerText = userInput;
        chatBox.appendChild(userMessage);

        document.getElementById("user-input").value = "";

        setTimeout(() => {
            let botMessage = document.createElement("div");
            botMessage.classList.add("message", "bot-message");
            botMessage.innerText = getRandomResponse();
            chatBox.appendChild(botMessage);
            chatBox.scrollTop = chatBox.scrollHeight;
        }, 1000);
    }

    function sendLike() {
        let chatBox = document.getElementById("chat-box");
        let likeMessage = document.createElement("div");
        likeMessage.classList.add("message", "user-message");
        likeMessage.innerHTML = "👍";
        chatBox.appendChild(likeMessage);
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    function getRandomResponse() {
        const responses = [
            "Hello! How can I assist you?",
            "That sounds interesting! Tell me more!",
            "I'm here to help, what do you need?",
            "Can you elaborate on that?",
            "I'm just an AI, but I love chatting with you!",
            "Let's talk about something fun! Any ideas?",
            "I'm always here for a good conversation!"
        ];
        return responses[Math.floor(Math.random() * responses.length)];
    }

    window.sendMessage = sendMessage;
    window.sendLike = sendLike;
});
