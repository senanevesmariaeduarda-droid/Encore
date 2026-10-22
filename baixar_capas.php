
<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);
set_time_limit(20);

echo "1. PHP iniciou.<br>";
flush();

$apiKey = 'fbe47989970cb6f2ab666966bc802d35';

$url = 'https://api.themoviedb.org/3/configuration?api_key='
     . urlencode($apiKey);

$contexto = stream_context_create([
    'http' => [
        'timeout' => 10,
        'ignore_errors' => true
    ],
    'ssl' => [
        'verify_peer' => true,
        'verify_peer_name' => true
    ]
]);

echo "2. Testando conexão com TMDB...<br>";
flush();

$resposta = @file_get_contents($url, false, $contexto);

if ($resposta === false) {
    echo "3. FALHA: o PHP não conseguiu acessar a API.";
} else {
    $dados = json_decode($resposta, true);

    if (isset($dados['images'])) {
        echo "3. CONEXÃO OK! A API respondeu corretamente.";
    } else {
        echo "3. A API respondeu, mas retornou um erro:<br>";
        echo htmlspecialchars($resposta);
    }
}
?>

