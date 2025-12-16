<?php
// URLから 'date' パラメータを取得
$selected_date = '';
if (isset($_GET['date'])) {
    $selected_date = htmlspecialchars($_GET['date']); 
} else {
    // 日付がない場合はカレンダーに戻すなどの処理が必要ですが、ここではエラーメッセージに留めます
    $selected_date = date('Y-m-d'); // デフォルトで今日の日付にしておく
}

// 表示用日付フォーマット
$display_date = date('Y年m月d日', strtotime($selected_date));

// --- この日の記録を records.txt から読み込む処理 (簡易版) ---
$records_for_display = '';
$filename = 'records.txt';

if (file_exists($filename)) {
    $file_content = file_get_contents($filename);
    $records_array = explode("--- START RECORD ---\n", $file_content);
    
    // 配列の最初の要素は空になるためスキップ
    foreach ($records_array as $record_block) {
        if (trim($record_block) === '') continue;

        // 記録ブロックから日付情報を抽出
        if (strpos($record_block, "日時: " . $selected_date . "\n") !== false) {
            // 内容の部分だけを抽出して表示
            $lines = explode("\n", $record_block);
            $content_start = array_search("内容:", $lines);
            if ($content_start !== false) {
                $content_lines = array_slice($lines, $content_start + 1, -2); // "内容:" の次から "--- END RECORD ---" の前まで
                $record_text = trim(implode("\n", $content_lines));
                
                // 表示形式に整形
                $records_for_display .= '<div class="tweet-box">';
                $records_for_display .= '<p class="record-text">' . nl2br(htmlspecialchars($record_text)) . '</p>';
                $records_for_display .= '</div>';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title><?php echo $display_date; ?> の記録</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background-color: #f4f4f9; }
        .container { max-width: 600px; margin: 0 auto; background-color: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        h1 { color: #1da1f2; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        .action-bar { margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
        .add-button { 
            padding: 10px 20px; 
            background-color: #1da1f2; 
            color: white; 
            text-decoration: none; 
            border-radius: 20px; 
            font-weight: bold; 
        }
        .tweet-box {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            background-color: #fff;
        }
        .no-record { color: #888; text-align: center; padding: 20px; border: 1px dashed #ccc; border-radius: 8px; }
    </style>
</head>
<body>

    <div class="container">
        <h1>🗓️ <?php echo $display_date; ?> の記録一覧</h1>

        <div class="action-bar">
            <p><a href="calendar.php">カレンダーに戻る</a></p>
            <a href="record_input.php?date=<?php echo $selected_date; ?>" class="add-button">＋ 記録を追加</a>
        </div>
        
        <hr>

        <h2>投稿内容</h2>
        <?php if (!empty($records_for_display)): ?>
            <?php echo $records_for_display; ?>
        <?php else: ?>
            <p class="no-record">この日の記録はまだありません。</p>
        <?php endif; ?>
    </div>

</body>
</html>