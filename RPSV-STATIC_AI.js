document.addEventListener("DOMContentLoaded", function() {
  // Global variables
  let playerScore = 0;
  let botScore = 0;

  // Function to send user message
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

  // Function to send a like
  function sendLike() {
      let chatBox = document.getElementById("chat-box");
      let likeMessage = document.createElement("div");
      likeMessage.classList.add("message", "user-message");
      likeMessage.innerHTML = "👍";
      chatBox.appendChild(likeMessage);
      chatBox.scrollTop = chatBox.scrollHeight;
  }

  // Function to handle bot responses
  function respo(userMessage) {
      userMessage = userMessage.toLowerCase().trim();
      if (userMessage === "hi") {
          return "Hello! How can I assist you today?";
      } else if (userMessage === "play") {
          return "Type 'rock', 'paper', or 'scissors' to play!";
      } else if (["rock", "paper", "scissors"].includes(userMessage)) {
          return playGame(userMessage);
      } else if (userMessage === "score") {
          return `Your Score: ${playerScore} | Bot Score: ${botScore}`;
      } else if (userMessage === "reset") {
          playerScore = 0;
          botScore = 0;
          return "Scores have been reset! Type 'play' to start a new game.";
      } else if (isMathQuestion(userMessage)) {
          return solveMath(userMessage);
      } else {
          return getRandomResponse();
      }
  }

  // Function to play rock-paper-scissors
  function playGame(userChoice) {
      const choices = ["rock", "paper", "scissors"];
      let botChoice = Math.random() < 0.3 ?
          (userChoice === "rock" ? "paper" : userChoice === "paper" ? "scissors" : "rock") :
          choices[Math.floor(Math.random() * choices.length)];

      let result = userChoice === botChoice ? "It's a tie!" :
          (userChoice === "rock" && botChoice === "scissors") ||
          (userChoice === "scissors" && botChoice === "paper") ||
          (userChoice === "paper" && botChoice === "rock") ?
              (playerScore++, "You win! 🎉") : (botScore++, "You lose! 😢");

      return `You chose ${userChoice}, I chose ${botChoice}. ${result}\nYour Score: ${playerScore} | Bot Score: ${botScore}`;
  }

  // Function to check if input is a math question
  function isMathQuestion(message) {
      return /^[\d+\-*/().^sqrt%= ]+=$/.test(message) || message.startsWith("what is ") || message.startsWith("calculate ");
  }

  // Function to solve math expressions
  function solveMath(expression) {
      try {
          expression = expression.replace("what is ", "").replace("calculate ", "").replace("=", "").trim();
          expression = expression.replace("^", "**");
          return `The answer is: ${eval(expression)}`;
      } catch (error) {
          return "I couldn't solve that math problem. Please try again!";
      }
  }

  // Function to return random responses
  function getRandomResponse() {
      const responses = [
          "Why did the scarecrow win an award? Because he was outstanding in his field!",
          "Octopuses have three hearts and blue blood!",
          "Believe in yourself! You are stronger than you think!",
          "If time is money, is an ATM a time machine?",
          "Let’s talk about food! What’s your favorite dish?"
      ];
      return responses[Math.floor(Math.random() * responses.length)];
  }

  // Expose functions globally
  window.sendMessage = sendMessage;
  window.sendLike = sendLike;
});

// Functions to handle chat window
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
window.addEventListener("scroll", function() {
  if (document.getElementById("chat-container").classList.contains("open")) {
      closeChat();
  }
});




/**  FOR OPENAI API  */

/*

document.addEventListener("DOMContentLoaded", function() {
    async function sendMessage() {
        let userInput = document.getElementById("user-input").value;

        if (userInput.trim() === "") return;

        let chatBox = document.getElementById("chat-box");

        let userMessage = document.createElement("div");
        userMessage.classList.add("message", "user-message");
        userMessage.innerText = userInput;
        chatBox.appendChild(userMessage);
        document.getElementById("user-input").value = "";

        let botMessage = document.createElement("div");
        botMessage.classList.add("message", "bot-message");
        botMessage.innerText = "Typing...";
        chatBox.appendChild(botMessage);
        chatBox.scrollTop = chatBox.scrollHeight;

        let botResponse = respo(userInput) || await fetchAIResponse(userInput);
        botMessage.innerText = botResponse;
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    async function fetchAIResponse(userMessage) {
        const apiKey = "YOUR_OPENAI_API_KEY"; // Replace with your actual OpenAI API key

        try {
            const response = await fetch("https://api.openai.com/v1/chat/completions", {
                method: "POST",
                headers: {
                    "Authorization": `Bearer ${apiKey}`,
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    model: "gpt-4", // You can use "gpt-3.5-turbo" if preferred
                    messages: [{ role: "user", content: userMessage }],
                    temperature: 0.7
                })
            });

            if (!response.ok) {
                return "Sorry, I couldn't process your request. Please try again later.";
            }

            const data = await response.json();
            return data.choices[0].message.content.trim();
        } catch (error) {
            return "Error connecting to AI. Please check your internet connection.";
        }
    }

    window.sendMessage = sendMessage;
});



*/
