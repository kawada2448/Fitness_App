<?php
date_default_timezone_set('Asia/Tokyo'); 

// URLから 'date' パラメータを取得
$selected_date = isset($_GET['date']) ? htmlspecialchars($_GET['date']) : date('Y-m-d');
$display_date = date('Y/m/d', strtotime($selected_date));

$filename = 'records.txt';
$has_record = false;
$parsed_records = [];

if (file_exists($filename)) {
    $file_content = file_get_contents($filename);
    $record_blocks = explode("--- START RECORD ---\n", $file_content);
    
    foreach ($record_blocks as $block) {
        if (trim($block) === '') continue;
        
        // 選択された日付のデータのみ抽出
        if (strpos($block, "日時: " . $selected_date) !== false) {
            $has_record = true;
            
            // 「内容:」以降のテキストを取得
            if (preg_match('/内容:\n(.*?)\n--- END RECORD ---/s', $block, $matches)) {
                $content = trim($matches[1]);
                $parsed_records[] = $content;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?php echo $display_date; ?> の記録</title>
    <style>
        :root {
            --main-green: #2e8b57;
            --light-green: #e6f4ea;
            --bg-gray: #f5f5f5;
            --text-dark: #333;
        }
        body { font-family: 'Segoe UI', 'Hiragino Kaku Gothic ProN', sans-serif; margin: 0; background-color: var(--bg-gray); color: var(--text-dark); }
        
        /* ヘッダー */
        .header { background-color: var(--main-green); color: white; padding: 10px; text-align: center; position: sticky; top: 0; z-index: 100; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .header-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        .back-link { color: white; text-decoration: none; font-size: 22px; padding: 0 15px; font-weight: bold; }
        
        .stats-container { display: flex; justify-content: space-around; gap: 5px; padding: 0 5px; }
        .stat-box { background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); border-radius: 8px; padding: 8px 5px; flex: 1; text-align: center; }
        .stat-label { font-size: 10px; display: block; margin-bottom: 2px; opacity: 0.9; }
        .stat-value { font-size: 16px; font-weight: bold; }

        .container { padding: 15px; display: flex; flex-direction: column; align-items: center; min-height: 60vh; justify-content: center; }

        .empty-state { text-align: center; width: 100%; }
        .empty-msg { color: #888; margin-bottom: 25px; font-size: 1.2em; font-weight: 500; }
        
        /* 四角い追加ボタン */
        .rect-add-button {
            display: inline-block;
            width: 85%;
            max-width: 400px;
            padding: 18px;
            background-color: var(--main-green);
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-weight: bold;
            font-size: 1.1em;
            box-shadow: 0 4px 10px rgba(46, 139, 87, 0.3);
            transition: background 0.2s;
        }
        .rect-add-button:active { background-color: #246d44; transform: scale(0.98); }

        /* 記録がある時の種目カード */
        .record-card { background: white; border-radius: 12px; margin-bottom: 15px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05); width: 100%; max-width: 500px; }
        .card-header { background: var(--light-green); color: var(--main-green); padding: 12px 15px; font-weight: bold; border-bottom: 1px solid #eee; }
        
        .record-table { width: 100%; border-collapse: collapse; }
        .record-table th { background: #fafafa; font-size: 12px; color: #999; padding: 8px; text-align: center; border-bottom: 1px solid #eee; }
        .record-table td { padding: 15px 8px; text-align: center; border-bottom: 1px solid #eee; font-size: 1.1em; }

        /* 右下の丸い追加ボタン (記録がある時用) */
        .fab {
            position: fixed; right: 20px; bottom: 30px;
            background-color: var(--main-green); color: white; width: 60px; height: 60px;
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            text-decoration: none; font-size: 30px; box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            z-index: 200;
        }
    </style>
</head>
<body>

    <header class="header">
        <div class="header-top">
            <a href="calendar.php" class="back-link">✕</a>
            <span><?php echo $display_date; ?></span>
            <div style="width:40px;"></div>
        </div>
        <div class="stats-container">
            <div class="stat-box"><span class="stat-label">合計種目数</span><span class="stat-value"><?php echo count($parsed_records); ?></span></div>
            <div class="stat-box"><span class="stat-label">セット数</span><span class="stat-value">-</span></div>
            <div class="stat-box"><span class="stat-label">レップ数</span><span class="stat-value">-</span></div>
            <div class="stat-box"><span class="stat-label">負荷量</span><span class="stat-value">-</span></div>
        </div>
    </header>

    <main class="container">
        <?php if ($has_record): ?>
            <?php foreach ($parsed_records as $record): ?>
                <div class="record-card">
                    <div class="card-header">記録されたトレーニング</div>
                    <table class="record-table">
                        <thead>
                            <tr>
                                <th>セット</th><th>重さ</th><th>回数</th><th>RM</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td><?php echo nl2br(htmlspecialchars($record)); ?></td>
                                <td>-</td>
                                <td style="color:#999; font-size: 0.9em;">-</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; ?>

            <a href="record_input.php?date=<?php echo $selected_date; ?>" class="fab">＋</a>

        <?php else: ?>
            <div class="empty-state">
                <p class="empty-msg">記録がまだありません</p>
                <a href="record_input.php?date=<?php echo $selected_date; ?>" class="rect-add-button">
                    トレーニング記録を追加
                </a>
            </div>
        <?php endif; ?>
    </main>

</body>
</html>