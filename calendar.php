<?php

// ----------------------------------------------------
// (PHPロジックは変更なし)
// ----------------------------------------------------

// タイムゾーン設定 (日本のタイムゾーンを推奨)
date_default_timezone_set('Asia/Tokyo'); 

$today = time(); 
$year = date('Y', $today);
$month = date('m', $today);

// GETパラメータが設定されているかチェック
if (isset($_GET['year']) && isset($_GET['month'])) {
    // 安全のため、整数にキャスト
    $input_year = (int)$_GET['year'];
    $input_month = (int)$_GET['month'];

    // 入力値の基本的な検証（例：年を1900年〜2100年、月を1月〜12月に制限）
    if ($input_year >= 1900 && $input_year <= 2100 && $input_month >= 1 && $input_month <= 12) {
        $year = $input_year;
        // 月は2桁表示を維持するため、sprintfを使用
        $month = sprintf('%02d', $input_month); 
    }
}

// ターゲットの年月日のタイムスタンプを取得
$target_month_timestamp = strtotime("{$year}-{$month}-01"); 
$days_in_month = date('t', $target_month_timestamp);
// 0(日曜日)から6(土曜日)の数値を取得
$first_day_of_week = date('w', $target_month_timestamp); 

// 前月と次月の計算（再利用）
$prev_month_timestamp = strtotime('-1 month', $target_month_timestamp);
$next_month_timestamp = strtotime('+1 month', $target_month_timestamp);
$prev_year = date('Y', $prev_month_timestamp);
$prev_month = date('m', $prev_month_timestamp);
$days_in_prev_month = date('t', $prev_month_timestamp);
$next_year = date('Y', $next_month_timestamp);
$next_month = date('m', $next_month_timestamp);

// ----------------------------------------------------
// 3. HTMLの出力
// ----------------------------------------------------
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>マイルドなグリーンのカレンダー</title>
    <style>
        /* **マイルドなグリーン** */
        :root {
            --soft-green: #66cdaa; 
            --dark-bg: #1e1e1e;     
            --header-bg: #2b2b2b;   
            --shadow-intensity: 0 0 8px; 
        }
        
        /* 全体のスタイル */
        body {
            background-color: var(--dark-bg);
            color: var(--soft-green); 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 0; 
            margin: 0;
        }

        /* 最上部のヘッダーコンテナ */
        .header-container {
            width: 100%;
            background-color: #000; 
            border-bottom: 1px solid var(--soft-green);
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(102, 205, 173, 0.4);
            margin-bottom: 20px; 
            box-sizing: border-box; 
        }
        .app-title {
            font-size: 1.5em;
            font-weight: bold;
            color: var(--soft-green);
            margin: 0 auto; 
        }
        
        /* 設定ボタン（画像）のスタイル */
        .settings-btn {
            display: inline-block;
            transition: all 0.3s;
            line-height: 0; 
            box-shadow: none; 
            padding: 0; 
        }
        .settings-btn img {
            width: 30px; 
            height: 30px;
            display: block;
            filter: grayscale(100%) brightness(1.7) sepia(100%) hue-rotate(80deg) saturate(200%);
            opacity: 0.9; 
            transition: all 0.3s;
        }
        .settings-btn:hover img {
            filter: grayscale(100%) brightness(2.0) sepia(100%) hue-rotate(80deg) saturate(250%);
            opacity: 1.0; 
        }

        /* 月移動ヘッダー（フォーム）のスタイル */
        .calendar-nav-form {
            text-align: center; 
            font-size: 1.2em; 
            margin-bottom: 25px;
            display: flex;
            /* 修正: 要素を均等に配置 */
            justify-content: space-between; 
            align-items: center;
            width: 500px; /* 幅を再指定してボタンを両端に寄せる */
            padding: 10px 0;
        }
        /* 前月/次月ボタンのスタイル (枠を非表示に) */
        .calendar-nav-form a {
            color: var(--soft-green);
            text-decoration: none;
            padding: 5px 10px;
            /* 修正: 枠と影を削除 */
            border: none; 
            border-radius: 5px;
            transition: all 0.3s;
            box-shadow: none; 
            font-size: 1.2em; 
            font-weight: bold;
            text-shadow: 0 0 3px var(--soft-green); /* ネオングリーン感を維持 */
        }
        .calendar-nav-form a:hover {
            /* ホバー時に背景と色を反転させ、ボタンらしくする */
            background-color: var(--soft-green);
            color: var(--dark-bg);
            text-shadow: none;
            box-shadow: 0 0 10px rgba(102, 205, 173, 0.7); /* ホバー時のみ影を適用 */
        }
        
        /* 年月入力フィールドの増減ボタンを非表示にするためのCSS */
        .calendar-nav-form input[type="number"]::-webkit-inner-spin-button,
        .calendar-nav-form input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none; 
            margin: 0;
        }
        .calendar-nav-form input[type="number"] {
            -moz-appearance: textfield; 
        }

        /* 年月入力フィールドのスタイル */
        .calendar-nav-form input[type="number"] {
            padding: 8px 10px;
            font-size: 1.3em;
            background-color: var(--dark-bg);
            color: var(--soft-green);
            border: 2px solid var(--soft-green);
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(102, 205, 173, 0.7); 
            cursor: text;
            outline: none;
            margin: 0 5px; 
            text-align: center;
            width: 80px; /* 年入力用 */
        }
        .calendar-nav-form input[name="month"] {
            width: 50px; /* 月入力用 */
        }
        .calendar-nav-form input[type="number"]:focus {
             box-shadow: 0 0 8px var(--soft-green); 
        }
        .date-input-group {
            display: flex;
            align-items: center;
            font-size: 1.3em; 
        }


        /* カレンダーテーブル (変更なし) */
        .calendar-table { 
            border-collapse: collapse; 
            width: 100%; 
            max-width: 500px; 
            margin: 0 auto 20px auto; 
            box-shadow: var(--shadow-intensity) var(--soft-green); 
        }
        .calendar-table th, .calendar-table td { 
            border: 1px solid var(--soft-green); 
            padding: 12px; 
            text-align: center; 
            font-size: 1.1em;
            background-color: #000; 
        }
        
        .calendar-table thead th {
            background-color: var(--header-bg); 
            color: var(--soft-green);
            text-shadow: 0 0 3px var(--soft-green); 
            border: 2px solid var(--soft-green);
        }
        .day-cell { 
            cursor: pointer; 
            transition: background-color 0.3s, box-shadow 0.3s;
        }
        .day-cell:hover { 
            background-color: #2b2b2b; 
            box-shadow: inset 0 0 5px var(--soft-green); 
        }
        .current-day { 
            background-color: var(--soft-green) !important; 
            color: var(--dark-bg) !important;
            font-weight: bold;
            box-shadow: 0 0 10px var(--soft-green); 
        } 
        .weekend-sun { color: #ff6347; text-shadow: none; } 
        .weekend-sat { color: #87ceeb; text-shadow: none; } 
        .prev-month { 
            color: #444; 
            cursor: default; 
            border: 1px solid #444;
            box-shadow: none;
        } 
        .button-container {
            text-align: center;
            margin-top: 30px;
        }
        .add-training-button {
            padding: 12px 30px;
            font-size: 1.2em;
            cursor: pointer;
            background-color: #000;
            color: var(--soft-green);
            border: 2px solid var(--soft-green);
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            box-shadow: var(--shadow-intensity) var(--soft-green); 
            transition: all 0.3s;
        }
        .add-training-button:hover {
            background-color: var(--soft-green);
            color: var(--dark-bg);
            box-shadow: 0 0 15px var(--soft-green);
        }
    </style>
</head>
<body>

    <div class="header-container">
        <a href="#" class="settings-btn">
            <img src="icon_000020_256.png" alt="設定"> 
        </a>
        <span class="app-title">記録</span>
        <div style="width: 30px;"></div> 
    </div>

    <form action="" method="get" class="calendar-nav-form">
        
        <a href="?year=<?php echo $prev_year; ?>&month=<?php echo $prev_month; ?>">&lt; 前月</a>

        <div class="date-input-group">
            <input 
                type="number" 
                name="year" 
                value="<?php echo $year; ?>" 
                min="1900" 
                max="2100" 
                required
                onchange="this.form.submit()" 
            >年
            
            <input 
                type="number" 
                name="month" 
                value="<?php echo (int)$month; ?>" 
                min="1" 
                max="12" 
                required
                onchange="this.form.submit()"
            >月
            
        </div>
        
        <a href="?year=<?php echo $next_year; ?>&month=<?php echo $next_month; ?>">次月 &gt;</a>
    </form>

    <table class="calendar-table">
        <thead>
            <tr>
                <th class="weekend-sun">日</th>
                <th>月</th>
                <th>火</th>
                <th>水</th>
                <th>木</th>
                <th>金</th>
                <th class="weekend-sat">土</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            <?php
            $day_count = 0;

            // 先月の日付を挿入
            $start_day_of_prev_month = $days_in_prev_month - $first_day_of_week + 1;
            for ($i = 0; $i < $first_day_of_week; $i++) {
                $day_in_prev_month = $start_day_of_prev_month + $i;
                echo "<td class='prev-month'>";
                echo $day_in_prev_month;
                echo "</td>";
                $day_count++;
            }

            // 今月の日付を挿入
            for ($day = 1; $day <= $days_in_month; $day++) {
                
                $current_date_str = "{$year}-{$month}-" . sprintf('%02d', $day);
                $is_today = (date('Y-m-d', $today) === $current_date_str) ? ' current-day' : '';
                
                $day_of_week = ($day_count % 7);
                $weekend_class = '';
                if ($day_of_week === 0) { // 日曜日
                    $weekend_class = ' weekend-sun';
                } elseif ($day_of_week === 6) { // 土曜日
                    $weekend_class = ' weekend-sat';
                }

                // クリックできるセルとして出力
                echo "<td class='day-cell{$is_today}{$weekend_class}' data-date='{$current_date_str}'>";
                echo $day;
                echo "</td>";

                $day_count++;

                // 土曜日になったら行を閉じて新しい行を開く
                if ($day_count % 7 === 0 && $day < $days_in_month) {
                    echo "</tr><tr>";
                }
            }

            // 次月の日付を挿入
            $next_day = 1;
            while ($day_count % 7 !== 0) {
                echo "<td class='prev-month'>";
                echo $next_day;
                echo "</td>";
                $day_count++;
                $next_day++;
            }
            ?>
            </tr>
        </tbody>
    </table>

    <div class="button-container">
        <?php
        $today_date_str = date('Y-m-d', $today);
        echo "<a href='record_view.php?date={$today_date_str}' class='add-training-button'>";
        echo "本日のトレーニングを追加";
        echo "</a>";
        ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // 日付セルクリック時の処理
        $('.day-cell').on('click', function() {
            var selectedDate = $(this).data('date');
            window.location.href = 'record_view.php?date=' + selectedDate;
        });
        
        // 設定ボタンクリック時の処理
        $('.settings-btn').on('click', function(e) {
            e.preventDefault(); 
            alert('設定画面へ遷移します (現在はデモです)');
        });
        
        // エンターキーでフォームを送信する処理
        $('.calendar-nav-form input[type="number"]').on('keypress', function(e) {
            if (e.which == 13) { // Enterキー
                e.preventDefault(); 
                $(this).closest('form').submit();
            }
        });
    </script>
</body>
</html>