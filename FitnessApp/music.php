<?php
require_once('vendor/autoload.php');

$session = new SpotifyWebAPI\Session(
    'c679689a309540a4b257a20d901b19a3',
    'd5880bf84e954881aaf11833585f8294',
    // 'https://localhost:3000/music/music.php',
);
$api = new SpotifyWebAPI\SpotifyWebAPI();
$session->requestCredentialsToken();
$accessToken = $session->getAccessToken();
$api->setAccessToken($accessToken);
?>


<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style.css">
    <title>music</title>
</head>

<body>
    <header>
        <form method="get">
            <input type="text" name="q" placeholder="アーティスト名や曲名を入力" style="width:300px" name="q" class="search">
            <button type="submit">検索</button>
        </form>
        <div class="kinnosuke">
            <p class="kinnosuke_text">力こそパワー<br>power is power</p>
            <img src="imges/fukidashi.png" alt="吹き出し">
            <img src="imges/kinnnosuke.png" alt="きんのすけ">
        </div>
    </header>



    <?php
    if (isset($_GET['q']) && $_GET['q'] !== '') {
        $query = htmlspecialchars($_GET['q']); // XSS対策
        echo "<h3>検索結果: " . $query . "</h3>";

        // 検索
        $result = $api->search($query, 'track');

        if (count($result->tracks->items) > 0) {
            foreach ($result->tracks->items as $track) {
                $name  = $track->name;
                $uri   = $track->uri;
                $image = $track->album->images[0]->url;

                // track ID 抽出
                $trackId = str_replace('spotify:track:', '', $uri);
                $embedUrl = "https://open.spotify.com/embed/track/" . $trackId;

                // echo "<p><strong>{$name}</strong></p>";
                // echo "<img src='{$image}' width='200'><br>";
    ?>
                <div class="playlists">
                    <div class="playlist">
            <?php

                echo '<iframe src="' . $embedUrl . '" 
                width="90%" height="80" frameborder="0"
                allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture">
            </iframe>';
                echo "<hr>";
            }
        } else {
            echo "<p>該当するトラックはありません。</p>";
        }
    }

            ?>
                    </div>
                </div>

                <?php

                // require 'vendor/autoload.php';

                // $session = new SpotifyWebAPI\Session(
                //     'c679689a309540a4b257a20d901b19a3',
                //     'd5880bf84e954881aaf11833585f8294',
                //     'http://127.0.0.1:3000/music/music.php',
                // );
                // $api = new SpotifyWebAPI\SpotifyWebAPI();

                // if (isset($_GET['code'])) {
                //     $session->requestAccessToken($_GET['code']);
                //     $api->setAccessToken($session->getAccessToken());
                // } else {
                //     header('Location: ' . $session->getAuthorizeUrl(array(
                //         'scope' => array(
                //             'playlist-read-private',
                //             'playlist-modify-private',
                //             'user-read-private',
                //             'playlist-modify'
                //         )
                //     )));
                //     die();
                // }

                // echo '<pre>';
                // print_r($api->me()); //認証を受けたアカウントのプロフィールが表示される
                // echo '</pre>';
                ?>

</body>

</html>