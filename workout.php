<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ワークアウト</title>
    <link rel="stylesheet" href="css/workout.css">
</head>

<body>
    <header>
        <a href="" class="prev"><img src="images/prev_icon.svg" alt="前へ"></a>
        <h1>ワークアウト</h1>
        <a href="" class="settings"><img src="images/settings_icon.svg" alt="設定"></a>
    </header>
    <main>
        <div class="control">
            <div class="timer">
                <div class="timer_visual">
                    <div class="circle"></div>
                    <div class="timer_count_wrapper"><p class="timer_count"></p></div>
                </div>
                <div class="timer_buttons">
                    <button class="start-stop"></button>
                </div>
            </div>
            <iframe data-testid="embed-iframe" src="https://open.spotify.com/embed/playlist/37i9dQZF1DX9vYRBO9gjDe?utm_source=generator&theme=0" width="100%" height="352" frameBorder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>
        </div>
        <div class="menu_wrapper">
            <div class="current_menu_wrapper">
                <h2>現在のメニュー</h2>
                <div class="menu">
                    <h3>メニュー</h3>
                    <p>回数（時間） x セット数</p>
                </div>
            </div>
            <div class="next_menu_wrapper">
                <h2>次のメニュー</h2>
                <div class="menu">
                    <h3>メニュー</h3>
                    <p>回数（時間） x セット数</p>
                </div>
            </div>
        </div>
    </main>
    <footer>
    </footer>
    <script>
        // タイマー機能
        const timer = document.querySelector('.timer_count');
        const startStopButton = document.querySelector('.start-stop');

        let countdown;
        let timeLeft = 0; // メニューから取得した時間（秒）
        let isRunning = false;

        function updateTimer() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            timer.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
        }

        startStopButton.textContent = isRunning ? 'ストップ' : 'スタート';

        startStopButton.addEventListener('click', () => {
            if (isRunning) {
                clearInterval(countdown);
            } else {
                clearInterval(countdown);
                countdown = setInterval(() => {
                    if (timeLeft > 0) {
                        timeLeft--;
                        updateTimer();
                    } else {
                        clearInterval(countdown);
                    }
                }, 1000);
            }
            isRunning = !isRunning;
            startStopButton.textContent = isRunning ? 'ストップ' : 'スタート';
        });
        updateTimer();
    </script>
</body>

</html>