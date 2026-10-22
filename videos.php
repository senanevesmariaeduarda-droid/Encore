
<?php
$apiKey = "AIzaSyB-RrXdazfOR7ji5nCj0--PTgZ8BwoEl6g";
$filme = "Divertida Mente 2 trailer oficial";

$url = "https://www.googleapis.com/youtube/v3/search?"
    . http_build_query([
        "part" => "snippet",
        "q" => $filme,
        "type" => "video",
        "maxResults" => 5,
        "key" => $apiKey
    ]);

$resposta = file_get_contents($url);
$dados = json_decode($resposta, true);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Trailers de filmes</title>
</head>
<body>

    <h1>Trailers de filmes</h1>

    <?php if (!empty($dados["items"])): ?>
        <?php foreach ($dados["items"] as $video): ?>
            <h2>
                <?= htmlspecialchars($video["snippet"]["title"]) ?>
            </h2>

            <iframe
                width="560"
                height="315"
                src="https://www.youtube.com/embed/<?= htmlspecialchars($video["id"]["videoId"]) ?>"
                title="Trailer do filme"
                allowfullscreen>
            </iframe>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Não foi possível carregar os vídeos.</p>
    <?php endif; ?>

</body>
</html>
