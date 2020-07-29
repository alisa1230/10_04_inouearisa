<?php
if (isset($_FILES['upfile']) && $_FILES['upfile']['error'] == 0) {
  // 送信が正常に行われたときの処理 ...

  $uploadedFileName = $_FILES['upfile']['name']; //ファイル名の取得
  $tempPathName = $_FILES['upfile']['tmp_name']; //tmpフォルダの場所
  $fileDirectoryPath = 'upload/'; //アップロード先フォルダ

  $extension = pathinfo($uploadedFileName, PATHINFO_EXTENSION);
  $uniqueName = date('YmdHis') . md5(session_id()) . "." . $extension;
  $fileNameToSave = $fileDirectoryPath . $uniqueName;

  $img = '';
  if (is_uploaded_file($tempPathName)) {
    if (move_uploaded_file($tempPathName, $fileNameToSave)) {
      chmod($fileNameToSave, 0644);
      $img = '<img src="' . $fileNameToSave . '" >';
    } else {
      exit('Error:アップロードできませんでした');
    }
  } else {
    exit('Erro:アップロードできませんでした');
  }
} else {
  exit('Error:画像が送信されていません');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>file_upload</title>
</head>

<body>

  <?= $img ?>
</body>

</html>