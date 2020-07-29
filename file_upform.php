<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ファイルアップロード練習</title>
</head>

<body>
  <form action="file_upload.php" method="POST" enctype="multipart/form-data">
    <fieldset>
      <legend>ファイルアップロード</legend>
      <div>
        <input type="file" name="upfile" accept="image/*" capture="camera">
      </div>
      <div>
        <button>submit</button>
      </div>
    </fieldset>
  </form>

</body>

</html>