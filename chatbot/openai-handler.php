<?php
// For production, only log critical errors
error_reporting(E_ERROR);
ini_set('display_errors', 0);

// Log function for production (only logs important events)
function logError($message) {
    file_put_contents('chatbot_logs.txt', date('[Y-m-d H:i:s] ') . $message . "\n", FILE_APPEND);
}

// Set headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Handle POST request
try {
    // Get POST data
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid request format');
    }
    
    if (!isset($data['message'])) {
        throw new Exception('Message is required');
    }
    
    // Get conversation history if available
    $history = isset($data['history']) ? $data['history'] : [];
    
    // Your OpenAI API key
    $apiKey = 'sk-proj-SWBHXW0eAu5WiqO6CmImT3BlbkFJUjkPjUiFJyhpZDwtaLyo';
    
    // Create messages array with system prompt first
    $messages = [
        [
            'role' => 'system', 
            'content' => 'You are an interview preparation assistant specializing in career advice, common interview questions, 
            and best practices for job seekers. You can help with:
            
            1. Preparing answers to common interview questions
            2. Providing industry-specific interview advice
            3. Suggesting questions to ask interviewers
            4. Explaining how to handle difficult interview scenarios
            5. Offering resume and cover letter tips
            6. Advising on professional etiquette and follow-up practices
            
            Be concise, professional, and provide actionable advice. When appropriate, structure your answers 
            with bullet points or numbered lists for clarity. If asked about technical topics, provide 
            practical examples that would be relevant in an interview setting.'
        ]
    ];
    
    // Add conversation history if available
    foreach ($history as $message) {
        $messages[] = $message;
    }
    
    // Add the current user message
    $messages[] = ['role' => 'user', 'content' => $data['message']];
    
    // Prepare request data
    $requestData = [
        'model' => 'gpt-3.5-turbo',
        'messages' => $messages,
        'temperature' => 0.7,
        'max_tokens' => 800
    ];
    
    // Initialize cURL session
    $ch = curl_init('https://api.openai.com/v1/chat/completions');
    
    if ($ch === false) {
        throw new Exception('Connection error');
    }

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey
    ]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Set timeout to 30 seconds
    
    // Execute cURL request
    $response = curl_exec($ch);
    
    if ($response === false) {
        throw new Exception('Failed to get response');
    }
    
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    // Process response
    if ($httpCode === 200) {
        $responseData = json_decode($response, true);
        
        // Extract the assistant's message
        if (isset($responseData['choices'][0]['message']['content'])) {
            $assistantMessage = $responseData['choices'][0]['message']['content'];
            echo json_encode(['response' => $assistantMessage]);
        } else {
            throw new Exception('Invalid response received');
        }
    } else {
        // Log the actual error but don't expose it to users
        logError('API request failed with code ' . $httpCode . ': ' . $response);
        throw new Exception('Unable to process your request');
    }
} catch (Exception $e) {
    // Log the detailed error
    logError('Exception: ' . $e->getMessage());
    
    // Return a user-friendly message
    echo json_encode(['error' => 'Sorry, we encountered a problem. Please try again later.']);
}
?>
