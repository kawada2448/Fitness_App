<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログインするんだ！！！</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/account_form.css">
    <!-- <?php
        $severname = "localhost";
        $username = "root";
        $password = "";
        $dbname = "test";
        $db = new mysqli($severname, $username, $password, $dbname);
        $sql = "select*from user";
        $result = $db->query($sql);
        if ($result->num_rows > 0) {
            echo "<table border='1'>";
            echo "<tr><th>ID</th><th>名前</th><th>pass</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["pass"]) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
    ?> -->
</head>

<body>
    <h1>トレーニング記録アプリへ、<br>ログインだ！！！</h1>
    <p class="muscle"><img src="pro_man 1.png" alt="マッスル！"><img src="pro_man 1.png" alt="マッスル！"><img src="pro_man 1.png" alt="マッスル！"></p>
    <form action="" method="post">
        <p>ユーザーID<br>
            <input type="text" name="id" placeholder="君のIDを書いてくれ！！！">
        </p>
        <p>パスワード<br>
            <input type="password" name="pass" placeholder="君のパスワードを書いてくれ！！！">
        </p>

        <button type="submit">ログインだぁ！！！</button>
    </form>
    <div class="link">
        <a href="create_account.php">アカウントがないだって！？<br>ならばここを押してくれ！！！</a>
    </div>
</body>

</html>