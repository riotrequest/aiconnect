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
    .completion {
      font-size: 16px;
      white-space: pre-wrap;
    }
  </style>
</head>
<body>
  <div class="container">
    <form class="prompt-form" onsubmit="return generateCompletion()">
      <input type="text" name="prompt" id="prompt" placeholder="Enter your prompt here...">
      <input type="submit" value="Generate">
    </form>
    <div class="completion" id="completion"></div>
  </div>
  <script>
    function generateCompletion() {
      // Replace with your own API key
      const apiKey = 'YOUR_API_KEY';

      // Get the user's prompt
      const prompt = document.getElementById('prompt').value + " and format it for WordPress and check it for grammar and spelling mistakes.";
      // const prompt = document.getElementById('prompt').value; // This is the original prompt call. You can use it in place of the above.

      // Set the POST data. I prefer setting these within the code so I don't have to be bothered.
      const data = {
          prompt: prompt,
          model: 'text-davinci-003',
          max_tokens: 1500,
          top_p: 1,
          frequency_penalty: 0.7,
          presence_penalty: 0.7
      };

      // Show the loading indicator
      document.getElementById('completion').innerHTML = '<p>Loading...</p>';

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
        const completedText = responseData['choices'][0]['text'];

        // Make the request to the LanguageTool API
        fetch('https://api.languagetool.org/v2/check', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: `text=${encodeURIComponent(completedText)}&language=en-US&enabledOnly=false`
        })
        .then(response => response.json())
        .then(responseData => {
          // Get the corrected text
          const correctedText = responseData['matches'].reduce((text, match) => {
            return text.slice(0, match.offset) + match.replacement + text.slice(match.offset + match.length);
          }, completedText);

          // Update the page with the corrected text
          document.getElementById('completion').innerHTML = correctedText;
        })
        .catch(error => {
          console.error(error);
        });
      })
      .catch(error => {
        console.error(error);
      });

      // Prevent the form from submitting and refreshing the page
      return false;
    }
  </script>
</body>
</html>
