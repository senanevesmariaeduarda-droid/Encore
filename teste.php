
<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);
set_time_limit(20);

echo "Iniciando teste...<br>";
flush();

$apiKey = 'fbe47989970cb6f2ab666966bc802d35';

$url = 'https://api.themoviedb.org/3/search/movie?' .
    http_build_query([
        'api_key' => $apiKey,
        'query' => 'High School Musical',
        'year' => 2006
    ]);

$contexto = stream_context_create([
    'http' => ['timeout' => 10]
]);

$resposta = @file_get_contents($url, false, $contexto);

if ($resposta === false) {
    exit('Erro ao pesquisar o filme.');
}

$dados = json_decode($resposta, true);

if (empty($dados['results'][0]['poster_path'])) {
    exit('Filme não encontrado. Resposta: ' .
        htmlspecialchars($resposta));
}

$capa = 'https://image.tmdb.org/t/p/w500' .
    $dados['results'][0]['poster_path'];

$imagem = @file_get_contents($capa, false, $contexto);

if ($imagem === false) {
    exit('O filme foi encontrado, mas o download da imagem falhou.');
}

if (file_put_contents(__DIR__ . '/img/teste-capa.jpg', $imagem) === false) {
    exit('A imagem foi baixada, mas não foi possível salvar na pasta img.');
}

echo 'SUCESSO! A capa foi salva em img/teste-capa.jpg';
?>
