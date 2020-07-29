<?php
include("functions.php");
// ...関数ファイル読み込み処理を記述(認証関連は省略でOK)
// ...DB接続の処理を記述
$search_word = $_GET["serchword"]; // GETのデータ受け取り
$sql = "SELECT * FROM todo_table WHERE todo LIKE '%{$search_word}%'";


// DB接続
$pdo = connect_to_db();

// データ登録SQL作成
// `created_at`と`updated_at`には実行時の`sysdate()`関数を用いて実行時の日時を入力する

// SQL準備&実行
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();
// ...SQL実行の処理を記述
if ($status == false) {
    // ...エラー処理を記述 
    $error = $stmt->errorInfo();
    echo json_encode(["error_msg" => "{$error[2]}"]);
} else {
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($result); // JSON形式にして出力
    exit();
}
