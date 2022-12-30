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
    <form class="prompt-form" method="post" action="index.php">
      <input type="text" name="prompt" placeholder="Enter your prompt here...">
      <input type="submit" value="Generate">
    </form>
    <?php if (isset($_POST['prompt'])): ?>
      <?php
      // Replace with your own API key
      $api_key = 'YOUR_API_HEY';

      // Set up the HTTP request
      $ch = curl_init('https://api.openai.com/v1/completions');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
          'Content-Type: application/json',
          'Authorization: Bearer ' . $api_key
      ));

      // Get the user's prompt
      $prompt = $_POST['prompt'] . " and format it for WordPress and check it for grammar and spelling mistakes.";

      // Set the POST data
      $data = array(
          'prompt' => $prompt,
          'model' => 'text-davinci-003',
          'max_tokens' => 1500,
          'top_p' => 1,
          'frequency_penalty' => 0.7,
          'presence_penalty' => 0.7
      );
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

      // Make the request
      $response = curl_exec($ch);

      // Check for errors
      if ($response === false) {
          die('Error: ' . curl_error($ch));
      }

      // Decode the response
      $response_data = json_decode($response, true);

      // Get the completed text
      $completed_text = $response_data['choices'][0]['text'];
      ?>
      <div class="completion">
        <?php echo $completed_text; ?>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>
