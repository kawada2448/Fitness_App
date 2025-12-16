<?php

// ----------------------------------------------------
// (PHPロジックは変更なし)
// ----------------------------------------------------

$today = time(); 
$year = date('Y', $today);
$month = date('m', $today);

if (isset($_GET['year']) && isset($_GET['month'])) {
    $year = (int)$_GET['year'];
    $month = (int)$_GET['month'];
}

$target_month_timestamp = strtotime("{$year}-{$month}-01");
$days_in_month = date('t', $target_month_timestamp);
$first_day_of_week = date('w', $target_month_timestamp);

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
            /* ★調整：初期状態で色を濃く、明るくする */
            /* brightness を 1.7 に、opacity を 0.9 に上げて視認性を向上 */
            filter: grayscale(100%) brightness(1.7) sepia(100%) hue-rotate(80deg) saturate(200%);
            opacity: 0.9; 
            transition: all 0.3s;
        }
        .settings-btn:hover img {
            /* ホバー時にさらに少しだけ明るくする（コントラスト維持のため調整）*/
            filter: grayscale(100%) brightness(2.0) sepia(100%) hue-rotate(80deg) saturate(250%);
            opacity: 1.0; 
        }

        /* 月移動ヘッダー（既存 .header のスタイル調整）*/
        .header { 
            text-align: center; 
            font-size: 1.8em; 
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 500px; 
            padding: 10px 0;
        }
        .header a {
            color: var(--soft-green);
            text-decoration: none;
            padding: 5px 10px;
            border: 1px solid var(--soft-green);
            border-radius: 5px;
            transition: all 0.3s;
            box-shadow: 0 0 5px rgba(102, 205, 173, 0.7); 
        }
        .header a:hover {
            background-color: var(--soft-green);
            color: var(--dark-bg);
            text-shadow: none;
        }

        /* カレンダーテーブル */
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
    </div>

    <div class="header">
        <a href="?year=<?php echo $prev_year; ?>&month=<?php echo $prev_month; ?>">&lt; 前月</a>
        <span><?php echo "{$year}年 {$month}月"; ?></span>
        <a href="?year=<?php echo $next_year; ?>&month=<?php echo $next_month; ?>">次月 &gt;</a>
    </div>

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
                
                $current_date_str = "{$year}-{$month}-{$day}";
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
        $('.day-cell').on('click', function() {
            var selectedDate = $(this).data('date');
            window.location.href = 'record_view.php?date=' + selectedDate;
        });
        
        $('.settings-btn').on('click', function(e) {
            e.preventDefault(); 
            alert('設定画面へ遷移します (現在はデモです)');
        });
    </script>
</body>
</html>