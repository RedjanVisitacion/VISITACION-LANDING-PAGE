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
            botMessage.innerText = respo(userInput);
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
  

    function respo(userMessage) {
      userMessage = userMessage.toLowerCase().replace(/\s+/g, "");
  
      if (userMessage === "hi") {
          return "Hello! How can I assist you today?";
      } else if (userMessage === "redjan") {
          return "Hello Redjan, my creator! How's your day going?";
      } else if (userMessage === "iloveyou") {
          return "Sorry, I don't have feelings, but I appreciate you!";
      } else if (userMessage === "bye!") {
          return "Goodbye, Redjan! Thanks for creating me. See you soon!";
      } else if (userMessage === "whoisredjan?") {
          return "Redjan Phil S. Visitacion is a skilled photographer, designer, and programmer. A passionate student with a talent for video editing!";
      } else if (userMessage === "whatareredjansskills?") {
          return "Redjan is skilled in photography, design, programming, and video editing. A true creative mind!";
      } else if (userMessage === "wheredoesredjanstudy?") {
          return "Redjan studies at the University of Science and Technology of Southern Philippines.";
      } else if (userMessage === "whatcertificatesdoesredjanhave?") {
          return "Redjan holds DICT, TESDA, and Microsoft certificates, proving expertise in various tech fields.";
      } else {
          return getRandomResponse(); 
      }
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
  
  function openChat() {
    document.getElementById("cv-button").style.transform = "scale(0)";
    setTimeout(() => {
      document.getElementById("chat-container").classList.add("open");
    }, 200);
  }
  
  function closeChat() {
    document.getElementById("chat-container").classList.remove("open");
    setTimeout(() => {
      document.getElementById("cv-button").style.transform = "scale(1)";
    }, 400);
  }
  
  // Close chat when user scrolls
  window.addEventListener("scroll", function () {
    if (document.getElementById("chat-container").classList.contains("open")) {
      closeChat();
    }
  });