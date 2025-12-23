<?php
date_default_timezone_set('Asia/Tokyo'); 

$today = time(); 
$today_str = date('Y-m-d', $today);

$year = date('Y', $today);
$month = date('m', $today);

if (isset($_GET['year']) && isset($_GET['month'])) {
    $input_year = (int)$_GET['year'];
    $input_month = (int)$_GET['month'];
    if ($input_year >= 1900 && $input_year <= 2100 && $input_month >= 1 && $input_month <= 12) {
        $year = $input_year;
        $month = sprintf('%02d', $input_month); 
    }
}

$target_month_timestamp = strtotime("{$year}-{$month}-01"); 
$days_in_month = date('t', $target_month_timestamp);
$first_day_of_week = date('w', $target_month_timestamp); 

$prev_month_timestamp = strtotime('-1 month', $target_month_timestamp);
$next_month_timestamp = strtotime('+1 month', $target_month_timestamp);
$prev_year = date('Y', $prev_month_timestamp);
$prev_month = date('m', $prev_month_timestamp);
$next_year = date('Y', $next_month_timestamp);
$next_month = date('m', $next_month_timestamp);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>マイルドなグリーンのカレンダー</title>
    <style>
        :root {
            --main-green: #2e8b57;
            --light-green: #e6f4ea;
            --hover-green: #dcf0e3;
            --accent-green: #c8e6c9;
            --bg-white: #ffffff;
            --text-dark: #333333;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        body {
            background-color: var(--bg-white);
            color: var(--text-dark); 
            font-family: 'Segoe UI', 'Hiragino Kaku Gothic ProN', sans-serif; 
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .header-container {
            width: 100%;
            background-color: var(--bg-white); 
            border-bottom: 2px solid var(--main-green);
            padding: 10px 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            margin-bottom: 15px; 
            box-sizing: border-box; 
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .app-title { font-size: 1.2em; font-weight: bold; color: var(--main-green); margin: 0 auto; }
        
        .main-content {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: flex-start;
            gap: 15px;
            max-width: 1100px;
            width: 100%;
            padding: 0 10px;
            box-sizing: border-box;
        }

        .calendar-section, .muscle-section {
            flex: 1;
            min-width: 300px;
            max-width: 500px;
            width: 100%;
        }

        .calendar-nav-form {
            display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;
        }
        .calendar-nav-form a {
            color: var(--main-green); text-decoration: none; padding: 5px 10px;
            border: 1px solid var(--main-green); border-radius: 20px; font-weight: bold; font-size: 0.85em;
        }
        .calendar-nav-form input[type="number"] {
            padding: 3px; font-size: 1.1em; color: var(--main-green); border: none;
            border-bottom: 2px solid var(--main-green); background: transparent; text-align: center;
            -moz-appearance: textfield;
        }
        .calendar-nav-form input[type="number"]::-webkit-outer-spin-button,
        .calendar-nav-form input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none; margin: 0;
        }

        .calendar-table { 
            border-collapse: collapse; width: 100%; 
            box-shadow: var(--shadow); background: white; table-layout: fixed;
        }
        .calendar-table th, .calendar-table td { 
            border: 1px solid #eee; padding: 12px 5px; text-align: center; font-size: 1em;
        }
        .calendar-table thead th { background: var(--light-green); color: var(--main-green); font-size: 0.9em; }

        .day-cell { cursor: pointer; transition: all 0.15s; }
        .day-cell:hover { background-color: var(--hover-green); }
        .day-cell:active { background-color: var(--accent-green); transform: scale(0.95); }

        .current-day { background-color: var(--main-green) !important; color: white !important; font-weight: bold; }
        .weekend-sun { color: #d9534f; }
        .weekend-sat { color: #428bca; }
        .prev-month { color: #ccc; }

        .muscle-image {
            width: auto; max-width: 80%; max-height: 250px; height: auto;
            border-radius: 12px; box-shadow: var(--shadow);
            background: #fcfcfc; margin: 0 auto;
        }

        .button-container { margin: 20px 0 50px; text-align: center; width: 100%; padding: 0 15px; box-sizing: border-box; }
        .add-training-button {
            width: 100%; max-width: 400px; padding: 14px; font-weight: bold; 
            background: var(--main-green); color: white; border-radius: 50px; text-decoration: none; display: inline-block;
        }
    </style>
</head>
<body>

    <header class="header-container">
        <div style="width: 28px;"></div>
        <span class="app-title">トレーニング記録</span>
        <div style="width: 28px;"></div> 
    </header>

    <main class="main-content">
        <section class="calendar-section">
            <form action="" method="get" class="calendar-nav-form">
                <a href="?year=<?php echo $prev_year; ?>&month=<?php echo $prev_month; ?>">前月</a>
                <div class="date-input-group">
                    <input type="number" name="year" value="<?php echo $year; ?>" style="width:55px;" onchange="this.form.submit()">年
                    <input type="number" name="month" value="<?php echo (int)$month; ?>" style="width:35px;" onchange="this.form.submit()">月
                </div>
                <a href="?year=<?php echo $next_year; ?>&month=<?php echo $next_month; ?>">次月</a>
            </form>

            <table class="calendar-table">
                <thead>
                    <tr>
                        <th class="weekend-sun">日</th><th>月</th><th>火</th><th>水</th><th>木</th><th>金</th><th class="weekend-sat">土</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    <?php
                    $day_count = 0;
                    $days_in_prev_month = date('t', $prev_month_timestamp);
                    $start_day_of_prev_month = $days_in_prev_month - $first_day_of_week + 1;

                    for ($i = 0; $i < $first_day_of_week; $i++) {
                        echo "<td class='prev-month'>" . ($start_day_of_prev_month + $i) . "</td>";
                        $day_count++;
                    }
                    
                    for ($day = 1; $day <= $days_in_month; $day++) {
                        $current_date_str = "{$year}-{$month}-" . sprintf('%02d', $day);
                        $class = 'day-cell';
                        if ($current_date_str === $today_str) $class .= ' current-day';
                        
                        $day_of_week = ($day_count % 7);
                        if ($day_of_week === 0) $class .= ' weekend-sun';
                        if ($day_of_week === 6) $class .= ' weekend-sat';
                        
                        echo "<td class='{$class}' data-href='record_view.php?date={$current_date_str}'>{$day}</td>";
                        
                        $day_count++;
                        if ($day_count % 7 === 0 && $day < $days_in_month) echo "</tr><tr>";
                    }
                    
                    $next_day = 1;
                    while ($day_count % 7 !== 0) {
                        echo "<td class='prev-month'>{$next_day}</td>";
                        $day_count++; $next_day++;
                    }
                    ?>
                    </tr>
                </tbody>
            </table>
        </section>

        <section class="muscle-section" style="text-align: center;">
            <h3 style="color: var(--main-green); font-size: 1.1em; margin-top: 0;">筋肉コンディション</h3>
            <img src="file.svg" alt="筋肉の画像" class="muscle-image">
        </section>
    </main>

    <div class="button-container">
        <a href="record_view.php?date=<?php echo $today_str; ?>" class="add-training-button">
            本日のトレーニングを追加
        </a>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(function() {
            $('.day-cell').on('click', function() {
                const destination = $(this).data('href');
                if(destination) {
                    window.location.href = destination;
                }
            });
        });
    </script>
</body>
</html>