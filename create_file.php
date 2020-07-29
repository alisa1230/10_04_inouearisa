<?php
session_start();
include("functions.php");
check_session_id();
if (
  !isset($_POST['todo']) || $_POST['todo'] == '' ||
  !isset($_POST['deadline']) || $_POST['deadline'] == ''
) {
  // 項目が入力されていない場合はここでエラーを出力し，以降の処理を中止する
  echo json_encode(["error_msg" => "no input"]);
  exit();
}
// 受け取ったデータを変数に入れる
$todo = $_POST['todo'];
$deadline = $_POST['deadline'];
// ここからファイルアップロード&DB登録の処理を追加しよう！！！
if (isset($_FILES['upfile']) && $_FILES['upfile']['error'] == 0) {
  // 送信が正常に行われたときの処理
  $uploadedFileName = $_FILES['upfile']['name']; //ファイル名の取得
  $tempPathName = $_FILES['upfile']['tmp_name']; //tmpフォルダの場所
  $fileDirectoryPath = 'upload/'; //アップロード先フォルダ
  $extension = pathinfo($uploadedFileName, PATHINFO_EXTENSION);
  $uniqueName = date('YmdHis') . md5(session_id()) . "." . $extension;
  $fileNameToSave = $fileDirectoryPath . $uniqueName;
  if (is_uploaded_file($tempPathName)) {
    if (move_uploaded_file($tempPathName, $fileNameToSave)) {
      chmod($fileNameToSave, 0644); // 権限の変更
    } else {
      exit('Error:アップロードできませんでした'); // 画像の保存に失敗
    }
  } else {
    exit('Error:画像がありません'); // tmpフォルダにデータがない
  }
} else {
  // 送られていない，エラーが発生，などの場合
  exit('Error:画像が送信されていません');
}
$pdo = connect_to_db();
// INSERT文にimageカラムを追加！
$sql = 'INSERT INTO
todo_table(id, todo, deadline, image, created_at, updated_at)
VALUES(NULL, :todo, :deadline, :image, sysdate(), sysdate())';
// ...
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':image', $fileNameToSave, PDO::PARAM_STR);
$stmt->bindValue(':todo', $todo, PDO::PARAM_STR);
$stmt->bindValue(':deadline', $deadline, PDO::PARAM_STR);
// ...実行，エラー処理，etc...
$status = $stmt->execute();
// データ登録処理後
if ($status == false) {
  // SQL実行に失敗した場合はここでエラーを出力し，以降の処理を中止する
  $error = $stmt->errorInfo();
  echo json_encode(["error_msg" => "{$error[2]}"]);
  exit();
} else {
  // 正常にSQLが実行された場合は入力ページファイルに移動し，入力ページの処理を実行する
  header("Location:todo_read.php");
  exit();
}
