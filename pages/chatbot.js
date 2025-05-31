function toggleChat() {
  const chatWindow = document.getElementById("chatbot-window");
  chatWindow.style.display = (chatWindow.style.display === "none") ? "block" : "none";
}

function handleChatKey(e) {
  if (e.key === "Enter") {
    const input = document.getElementById("chatbot-input");
    const message = input.value.trim();
    if (message === "") return;

    addMessage("You", message);
    input.value = "";

    fetch("chatbot.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ message })
    })
    .then(res => res.json())
    .then(data => {
      addMessage("Bot", data.reply || "Sorry, no reply.");
    })
    .catch(() => {
      addMessage("Bot", "Error contacting chatbot.");
    });
  }
}

function addMessage(sender, text) {
  const msgDiv = document.getElementById("chatbot-messages");
  msgDiv.innerHTML += `<div><b>${sender}:</b> ${text}</div>`;
  msgDiv.scrollTop = msgDiv.scrollHeight;
}
