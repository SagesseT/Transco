<?php
// filepath: c:\wamp64\www\Bill-Suiv\api\chatgpt.php

function callChatGPT($prompt) {
    $apiKey = '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'; // Replace with your actual API key
    $url = 'https://api.openai.com/v1/chat/completions';

    $data = [
        'model' => 'gpt-3.5-turbo',
        'messages' => [
            ['role' => 'user', 'content' => $prompt]
        ],
        'max_tokens' => 150
    ];

    $options = [
        'http' => [
            'header' => [
                "Content-Type: application/json",
                "Authorization: Bearer $apiKey"
            ],
            'method' => 'POST',
            'content' => json_encode($data),
        ],
    ];

    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);

    if ($response === FALSE) {
        return 'Error communicating with the ChatGPT API.';
    }

    $responseData = json_decode($response, true);
    return $responseData['choices'][0]['message']['content'] ?? 'No response from ChatGPT.';
}
?>