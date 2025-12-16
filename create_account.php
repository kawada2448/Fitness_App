<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>アカウントを作るんだ！</title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/account_form.css">
    <?php
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
    ?>
</head>

<body>
    <h1>アカウントがない！？<br>ならば、作るのだ！！！</h1>
    <p class="muscle"><img src="images/pro_man 1.png" alt="マッスル！"><img src="images/pro_man 1.png" alt="マッスル！"><img src="images/pro_man 1.png" alt="マッスル！"></p>
    <form action="add_user.php" method="post">
        <p>ユーザーID<br>
            <input type="text" name="name" placeholder="20文字以内で好きな名前を設定してくれ！！！">
        </p>
        <p>パスワード<br>
            <input type="password" name="pass" placeholder="20文字以内で好きなパスワードを設定してくれ！！！">
        </p>
        <p>パスワード（確認用）<br>
            <input type="password" name="pass_again" placeholder="さっきのパスワードをもう一度！！！">
        </p>
        <p class="create_pass_error">
            <?php
                if (isset($_GET['msg'])){
                    echo $_GET['msg'];
                }
            ?>
        </p>


        <button type="submit">アカウントを作るぞぉ！！！</button>
    </form>
</body>

</html>