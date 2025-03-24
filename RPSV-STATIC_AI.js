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
  




    let playerScore = 0;
let botScore = 0;

function respo(userMessage) {
    userMessage = userMessage.toLowerCase().trim(); // Normalize input

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

function playGame(userChoice) {
    const choices = ["rock", "paper", "scissors"];

    let botChoice;
    if (Math.random() < 0.3) {
        if (userChoice === "rock") botChoice = "paper";
        else if (userChoice === "paper") botChoice = "scissors";
        else botChoice = "rock";
    } else {
        botChoice = choices[Math.floor(Math.random() * choices.length)];
    }

    let result = "";
    if (userChoice === botChoice) {
        result = "It's a tie!";
    } else if (
        (userChoice === "rock" && botChoice === "scissors") ||
        (userChoice === "scissors" && botChoice === "paper") ||
        (userChoice === "paper" && botChoice === "rock")
    ) {
        result = "You win! 🎉";
        playerScore++;
    } else {
        result = "You lose! 😢";
        botScore++;
    }

    return `You chose ${userChoice}, I chose ${botChoice}. ${result}\nYour Score: ${playerScore} | Bot Score: ${botScore}`;
}

// ✅ Function to check if input is a math question
function isMathQuestion(message) {
    return /^[\d+\-*/().^sqrt%= ]+=$/.test(message) || message.startsWith("what is ") || message.startsWith("calculate ");
}

// ✅ Function to solve math expressions safely
function solveMath(expression) {
    try {
        expression = expression.replace("what is ", "").replace("calculate ", "").replace("=", "").trim();
        expression = expression.replace("^", "**"); // Replace ^ with ** for exponents
        let result = eval(expression); // Evaluate math expression
        return `The answer is: ${result}`;
    } catch (error) {
        return "I couldn't solve that math problem. Please try again!";
    }
}

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

// Example usage
console.log(respo("play"));        // "Type 'rock', 'paper', or 'scissors' to play!"
console.log(respo("rock"));        // Play the game!
console.log(respo("score"));       // Show current score
console.log(respo("reset"));       // Reset scores
console.log(respo("1+1="));        // "The answer is: 2"
console.log(respo("10 * 5 ="));    // "The answer is: 50"
console.log(respo("sqrt(16)="));   // "The answer is: 4"
console.log(respo("2^3="));        // "The answer is: 8"
console.log(respo("What is 5+3?"));// "The answer is: 8"
console.log(respo("Calculate 12/4"));// "The answer is: 3"



  
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