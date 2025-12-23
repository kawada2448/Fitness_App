<?php
$items = [
    "胸" => ["ベンチプレス","インクラインベンチ","ダンベルフライ","ケーブルクロス","チェストプレス","ディップス","スミスベンチ"],
    "背中" => ["デッドリフト","チンニング","ラットプル","シーテッドロー","ベントロー","バックエクステンション","Tバーロー"],
    "脚" => ["スクワット","レッグプレス","レッグエクステンション","レッグカール","ランジ","スミススクワット","ブルガリアンスクワット"],
    "肩" => ["ショルダープレス","サイドレイズ","フロントレイズ","リアレイズ","アップライトロー","フェイスプル","ショルダープレスマシン"],
    "腕" => ["アームカール","ハンマーカール","プリーチャーカール","トライセプスプレスダウン","フレンチプレス","キックバック","リストカール"],
    "お尻" => ["ヒップスラスト","ケーブルキックバック","ブルガリアンスクワット","アブダクション","スクワット","ランジ","グルートブリッジ"],
    "腹筋" => ["クランチ","レッグレイズ","アブローラー","ケーブルクランチ","ツイストクランチ","ドラゴンフラッグ","V字腹筋"],
    "有酸素運動" => ["ランニング","エアロバイク","ローイング","スイミング","階段昇降","クロストレーナー","HIIT"],
    "その他" => ["ストレッチ","フォームチェック","姿勢改善","ウォームアップ","クールダウン","モビリティ","リハビリ"]
];
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>種目一覧</title>

<style>
    body {
        font-family: "Helvetica", "Segoe UI", sans-serif;
        background-color: #f8f8f8;
        margin: 0;
        padding: 0;
    }

    .category-card {
        background: #fff;
        margin: 15px;
        border-radius: 12px;
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .category-header {
        background: linear-gradient(135deg, #2ecc71, #27ae60);
        color: #fff;
        padding: 14px 18px;
        font-size: 18px;
        font-weight: bold;
        letter-spacing: 1px;
        display: flex;
        align-items: center;
    }

    .item {
        padding: 14px 18px;
        display: flex;
        justify-content: space-between;
        border-bottom: 1px solid #eee;
        font-size: 16px;
        cursor: pointer;
    }

    .item:last-child {
        border-bottom: none;
    }

    .item:hover {
        background: #fafafa;
    }

    .icon {
        opacity: 0.6;
    }

    .footer-bar {
        display: flex;
        justify-content: space-between;
        padding: 12px 15px;
        background: #fafafa;
    }

    .add-button {
        font-size: 14px;
        color: #007bff;
        cursor: pointer;
        font-weight: bold;
        text-decoration: none;
    }
    
    .show-all-button {
        font-size: 14px;
        color: #6c757d; 
        cursor: pointer;
        font-weight: bold;
        text-decoration: none;
        padding: 6px 10px;
        border: none;
        background: none;
    }
    
    /* トグル時にボタンの色を変えるCSSをオプションで追加 */
    .show-all-button.active {
        color: #e74c3c; 
    }

</style>
</head>
<body>

<?php foreach ($items as $category => $list): 
    $category_id = md5($category);
    $item_count = count($list);
    $needs_show_all_button = $item_count > 3;
?>
    <div class="category-card">
        <div class="category-header"><?= htmlspecialchars($category) ?></div>

        <div class="item-list" id="list-<?= $category_id ?>">
            <?php foreach ($list as $index => $item): ?>
                <div class="item item-row"
                     data-index="<?= $index ?>">
                    <span><?= htmlspecialchars($item) ?></span>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="footer-bar">
            <a class="add-button" href="#">＋ 種目を追加</a>

            <?php if ($needs_show_all_button): ?>
                <button class="show-all-button"
                        id="button-<?= $category_id ?>"
                        data-state="collapsed" 
                        data-full-count="<?= $item_count ?>"
                        onclick="toggleListDisplay('<?= $category_id ?>')">
                    すべて表示 (<?= $item_count - 3 ?>件)
                </button>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>

<script>
function toggleListDisplay(id) {
    const rows = document.querySelectorAll('#list-' + id + ' .item-row');
    const button = document.getElementById('button-' + id);
    if (!button) return;

    // 現在の状態を取得
    let currentState = button.getAttribute('data-state');
    let fullCount = button.getAttribute('data-full-count');
    
    if (currentState === 'collapsed') {
        
        rows.forEach(row => {
            row.style.display = 'flex'; // すべて表示
        });

        button.textContent = '3件に戻す';
        button.setAttribute('data-state', 'expanded');
        button.classList.add('active'); // CSSで色を変える
        
    } else {
        // 状態が「展開」の場合 → 「3件表示」に戻す
        
        rows.forEach((row, index) => {
            if (index >= 3) {
                row.style.display = 'none'; // 4件目以降を非表示
            } else {
                row.style.display = 'flex';
            }
        });

        // ボタンのテキストと状態を更新
        button.textContent = `すべて表示 (${fullCount - 3}件)`;
        button.setAttribute('data-state', 'collapsed');
        button.classList.remove('active'); // CSSの色を元に戻す
    }
}

// ページ読み込み時に全カテゴリ「3件だけ表示」にする
window.onload = () => {
    document.querySelectorAll(".item-list").forEach(list => {
        const rows = list.querySelectorAll('.item-row');
        
        // 3件より多い場合のみ制限をかける
        if (rows.length > 3) {
            // 初期状態（3件表示）を適用
            rows.forEach((row, index) => {
                if (index >= 3) {
                    row.style.display = 'none';
                }
            });
        } else {
            // 3件以下の場合はボタンを非表示にする
            const categoryId = list.id.replace('list-', '');
            const button = document.getElementById('button-' + categoryId);
            if(button) {
                button.style.display = 'none';
            }
        }
    });
};
</script>

</body>
</html>