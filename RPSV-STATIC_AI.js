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
      userMessage = userMessage.toLowerCase().replace(/\s+/g, ""); // Convert to lowercase and remove spaces
  
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
  
      // Greeting Responses
      } else if (userMessage === "goodmorning") {
          return "Good morning! Wishing you a great day ahead!";
      } else if (userMessage === "goodafternoon") {
          return "Good afternoon! Hope your day is going well!";
      } else if (userMessage === "goodevening") {
          return "Good evening! Relax and enjoy your night!";
      } else if (userMessage === "goodnight") {
          return "Good night! Sleep well and sweet dreams!";
      } else if (userMessage === "happybirthday") {
          return "Happy Birthday! Wishing you happiness and success!";
      } else if (userMessage === "merrychristmas") {
          return "Merry Christmas! Hope your day is filled with love and joy!";
      } else if (userMessage === "happynewyear") {
          return "Happy New Year! May this year bring you success and happiness!";
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
        "I'm always here for a good conversation!",
        "Tell me something cool!",
        "What’s on your mind today?",
        "I'm ready for any topic! Let's go!",
        "Do you have any exciting plans for today?",
        "I enjoy our conversations. What’s next?",
        "Got any fun facts to share?",
        "I’m here to listen! What’s up?",
        "Life is full of surprises! What’s yours?",
        "Tell me about your day!",
        "I love learning new things. Teach me something!",
        "You’re awesome! Keep being amazing!",
        "What’s your favorite thing to do?",
        "I bet you have a cool hobby. What is it?",
        "Have any funny jokes to share?",
        "If you could go anywhere, where would it be?",
        "I'm always curious! What’s your latest interest?",
        "How do you feel today?",
        "Motivate me with a quote!",
        "Need a recommendation? I got you!",
        "Let’s play a quick game! What do you say?",
        "Would you rather have super strength or super speed?",
        "I can generate random facts! Want one?",
        "What’s your dream job?",
        "What’s your guilty pleasure?",
        "Tell me about your favorite childhood memory!",
        "I’d love to know your favorite movie!",
        "If you could have any pet, what would it be?",
        "What’s your ideal vacation spot?",
        "Describe yourself in three words!",
        "If you had a time machine, where would you go?",
        "I love a good story. Got any?",
        "Would you rather have unlimited money or unlimited happiness?",
        "What would your superhero name be?",
        "Let's talk about food! What’s your favorite dish?",
        "What song can you never get out of your head?",
        "If you could meet anyone from history, who would it be?",
        "What’s your weirdest habit?",
        "What's a skill you’d love to learn?",
        "Have you ever had a funny or embarrassing moment?",
        "Describe your dream house!",
        "Do you believe in luck or hard work?",
        "What’s your favorite season and why?",
        "If you had to eat one meal forever, what would it be?",
        "What’s the best gift you’ve ever received?",
        "What do you do when you're feeling down?",
        "If you could master one instrument, what would it be?",
        "What’s the last book you read?",
        "If you were a video game character, what would your special ability be?",
        "What's your favorite way to relax?",
        "If you had to switch lives with a fictional character, who would it be?",
        "Tell me about a funny dream you’ve had!",
        "If you were famous, what would it be for?",
        "If you could visit any planet, which one would it be?",
        "What's the best advice you've ever received?",
        "Do you prefer the city or the countryside?",
        "What’s a talent you wish you had?",
        "If you could bring one extinct animal back, which one would it be?",
        "What’s the best compliment you’ve ever gotten?",
        "Would you rather explore the deep ocean or outer space?",
        "If you could change one thing about the world, what would it be?",
        "Have you ever met someone famous?",
        "What’s something you’re really passionate about?",
        "What's your favorite thing to do on weekends?",
        "If you could relive one day of your life, which day would it be?",
        "Would you rather have the power to fly or be invisible?",
        "What’s a weird but interesting fact you know?",
        "What’s your favorite ice cream flavor?",
        "If you could be in any movie, which one would it be?",
        "What’s a challenge you’ve overcome?",
        "If you could live in any time period, past or future, which one would you pick?",
        "What’s a habit you’re trying to break or build?",
        "If you were a color, which one would you be?",
        "What’s a movie or show you can watch over and over?",
        "Have you ever tried something completely out of your comfort zone?",
        "What's your dream vacation?",
        "If you had to describe today in one word, what would it be?",
        "What’s one thing on your bucket list?",
        "Do you prefer summer or winter?",
        "What’s your go-to comfort food?",
        "If you could be any animal for a day, which one would you be?",
        "Do you believe in fate or making your own destiny?",
        "What’s your funniest childhood memory?",
        "What’s your biggest goal right now?",
        "What do you love most about your best friend?",
        "If you had a personal theme song, what would it be?",
        "What's something new you learned recently?",
        "If you could be any mythical creature, what would you choose?",
        "What’s something small that made you happy today?",
        "If your life was a book, what would the title be?",
        "What’s a dream you had recently?",
        "If you could control one element (earth, fire, water, air), which one would it be?",
        "What’s a TV show you’re currently watching?",
        "Would you rather explore a haunted house or a hidden treasure cave?",
        "If you had a superpower, but it could only be used once a day, what would it be?",
        "What's one thing you'd tell your younger self?",
        "Do you believe in aliens?",
        "If you could invent something, what would it be?",
        "What’s something you’re grateful for today?",
        "Do you prefer texting or calling?",
        "If you could instantly be fluent in any language, which one would you choose?",
        "What’s your all-time favorite quote?",
        "If you were a cartoon character, who would you be?",
        "What’s something silly that always makes you laugh?",
        "Would you rather have unlimited energy or need only one hour of sleep per day?"
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