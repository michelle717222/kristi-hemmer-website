<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$body = json_decode(file_get_contents('php://input'), true);
if (!$body || empty($body['email']) || empty($body['quizResult'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Email and quiz result are required']);
    exit;
}

// Parse .env file directly into variables (putenv/getenv disabled on this server)
$apiKey = '';
$listId = 5;
$envFile = dirname(__DIR__) . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines !== false) {
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || $line[0] === '#' || strpos($line, '=') === false) continue;
            $parts = explode('=', $line, 2);
            if (count($parts) !== 2) continue;
            $k = trim($parts[0]);
            $v = trim($parts[1], " \t\"'");
            if ($k === 'BREVO_API_KEY') $apiKey = $v;
            if ($k === 'BREVO_LIST_ID') $listId = (int)$v;
        }
    }
}

if (!$apiKey) {
    http_response_code(500);
    echo json_encode(['error' => 'Server configuration error']);
    exit;
}

$payload = json_encode([
    'email'         => $body['email'],
    'attributes'    => ['QUIZ_RESULT' => $body['quizResult']],
    'listIds'       => [$listId],
    'updateEnabled' => true,
]);

$ch = curl_init('https://api.brevo.com/v3/contacts');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $payload,
    CURLOPT_HTTPHEADER     => [
        'api-key: ' . $apiKey,
        'Content-Type: application/json',
        'Accept: application/json',
    ],
]);

$response   = curl_exec($ch);
$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($statusCode === 201 || $statusCode === 204) {
    echo json_encode(['success' => true, 'debug_status' => $statusCode, 'debug_response' => $response]);
} else {
    error_log('Brevo API error: ' . $statusCode . ' ' . $response);
    http_response_code(502);
    echo json_encode(['error' => 'Failed to subscribe. Please try again.', 'debug_status' => $statusCode, 'debug_response' => $response]);
}
