<?php
header('Content-Type: application/json');

$input = json_decode(file_get_contents("php://input"), true);
$userMessage = trim($input['message'] ?? '');

if (empty($userMessage)) {
    echo json_encode(["reply" => "No message received."]);
    exit;
}

$data = [
    "inputs" => [
        "past_user_inputs" => [],
        "generated_responses" => [],
        "text" => $userMessage
    ]
];

// HuggingFace Inference API (Free)
$url = "https://api-inference.huggingface.co/models/mistralai/Mistral-7B-Instruct-v0.1";
$headers = [
    "Content-Type: application/json",
    // Optionally, add an API key here if needed for higher usage:
    // "Authorization: Bearer YOUR_HF_TOKEN"
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);

if (isset($result[0]['generated_text'])) {
    $reply = $result[0]['generated_text'];
} elseif (isset($result['error'])) {
    $reply = "Bot error: " . $result['error'];
} else {
    $reply = "Bot is temporarily unavailable. Please try again.";
}

echo json_encode(["reply" => $reply]);
