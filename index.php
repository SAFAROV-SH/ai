<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$apiKey = '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'; // kalitni shu yerga yozing

$userQuestion = $_GET['q'] ?? '';
if (!$userQuestion) {
    echo "Savol yuboring: ?q=Sizning savolingiz";
    exit;
}

$systemPrompt = <<<EOD
Salom sen targetologning yordamchi botisan.
Agar foydalanuvchi salomlashsa — "Assalomu alaykum, yaxshimisiz?" deb javob ber.
Agar topshiriq bersa — "Xo'p bo'ladi" deb ayt.
Agar hisobotni so'rasa, lekin vaqtini aytmasa — "Qaysi vaqt uchun hisobot kerak?" deb so'ra.
EOD;

$data = [
    'model' => 'gpt-4o-mini',
    'messages' => [
        ['role' => 'system', 'content' => $systemPrompt],
        ['role' => 'user', 'content' => $userQuestion],
    ],
];

$ch = curl_init('https://api.openai.com/v1/chat/completions');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'Content-Type: application/json',
        'Authorization: ' . 'Bearer ' . $apiKey,
    ],
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($data),
]);

$response = curl_exec($ch);

if ($response === false) {
    die("cURL xatolik: " . curl_error($ch));
}

curl_close($ch);
$result = json_decode($response, true);

if (isset($result['error'])) {
    die("OpenAI xatolik: " . $result['error']['message']);
}

echo $result['choices'][0]['message']['content'] ?? 'Hech qanday javob yo‘q.';
