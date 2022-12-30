<!DOCTYPE html>
<html>
<head>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }
    .container {
      max-width: 800px;
      margin: 0 auto;
      padding: 20px;
    }
    .prompt-form {
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 20px;
    }
    .prompt-form input[type="text"] {
      width: 70%;
      font-size: 24px;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    .prompt-form input[type="submit"] {
      width: 20%;
      font-size: 16px;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      background-color: #eee;
      cursor: pointer;
    }
    .messages {
      list-style: none;
      padding: 0;
      margin: 0;
    }
    .message {
      margin-bottom: 20px;
    }
    .message.user {
      text-align: right;
    }
    .message.ai {
      text-align: left;
    }
    .message-text {
      display: inline-block;
      background-color: #eee;
      border-radius: 5px;
      padding: 10px;
    }
  </style>
</head>
<body>
  <div class="container">
    <form class="prompt-form" onsubmit="return sendMessage()">
      <input type="text" name="message" id="message" placeholder="Enter your message here...">
      <input type="submit" value="Send">
    </form>
    <ul class="messages" id="messages">
    </ul>
  </div>
  <script>
    let conversation = [];

    function sendMessage() {
      // Replace with your own API key
      const apiKey = 'API_KEY_HERE';

      // Get the user's message
      const message = document.getElementById('message').value;

      // Add the message to the conversation
      conversation.push({
        type: 'user',
        text: message
      });
      renderMessages();

      // Clear the message input
      document.getElementById('message').value = '';

      // Set the POST data. I prefer setting these within the code so I don't have to be bothered.
      const data = {
          prompt: `User: ${message}\nAI:`,
          model: 'text-davinci-003',
          max_tokens: 1500,
          top_p: 1,
          frequency_penalty: 0.7,
          presence_penalty: 0.7
      };

      // Make the request to the OpenAI API
      fetch('https://api.openai.com/v1/completions', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${apiKey}`
        },
        body: JSON.stringify(data)
      })
      .then(response => response.json())
      .then(responseData => {
        // Get the completed text
        const completedText = responseData['choices'][0]['text'].split('\n').pop();

        // Add the AI's response to the conversation
        conversation.push({
          type: 'ai',
          text: completedText
        });
        renderMessages();
      })
      .catch(error => {
        console.error(error);
      });

      // Prevent the form from submitting and refreshing the page
      return false;
    }

    function renderMessages() {
      // Clear the messages list
      document.getElementById('messages').innerHTML = '';
 
      // Add each message to the list
      conversation.forEach(message => {
        const li = document.createElement('li');
        li.classList.add('message', message.type);
        const p = document.createElement('p');
        p.classList.add('message-text');
        p.innerText = message.text;
        li.appendChild(p);
        document.getElementById('messages').appendChild(li);
      });

      // Scroll to the bottom of the list
      document.getElementById('messages').scrollTop = document.getElementById('messages').scrollHeight;
    }
  </script>
</body>
</html>

