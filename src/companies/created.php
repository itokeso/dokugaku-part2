<?php
require_once __DIR__ . '/lib/mysqli.php';

function createCompany($link, $company)
{
  $sql = <<<EOT
INSERT INTO companies (
  name,
  establishment_date,
  founder
) VALUES (
  "{$company['name']}",
  "{$company['establishment_date']}",
  "{$company['founder']}"
)
EOT;
  $result = mysqli_query($link, $sql);
  if (!$result) {
    error_log('Error: fail to create company');
    echo 'debug error'. mysqli_error($link);
  }
}

function validate($company)
{
  $errors = [];

  //会社名
  if (!strlen($company['name'])) {
    $errors['name'] = '会社名を入力してください';
  } elseif (strlen($company['name']) > 255) {
    $errors['name'] = '会社名は255文字以内で入力して下さい';
  }

  //設立日
  //日付を分割する
  $dates = explode('-', $company['establishment_date']);
  if (!strlen($company['establishment_date'])) {
    $errors['establishment_date'] = '設立日を入力してください';
  } elseif (count($dates) !== 3) {
    $errors['establishment_date'] = '設立日を正しい形式で入力してください';
  } elseif (!checkdate($dates[1], $dates[2], $dates[0])){
    $errors['establishment_date'] = '設立日を正しい日付で入力してください';
  }

  //設立者
  if (!strlen($company['founder'])) {
    $errors['founder'] = '代表者名を入力してください';
  } elseif (strlen($company['founder']) > 100) {
    $errors['founder'] = '代表者名は100文字以内で入力して下さい';
  }
  return $errors;
}
  //HTTPメソッドがPOSTだったら
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //POSTされた会社情報を変数に格納する
    $company = [
      'name'               => $_POST['name'],
      'establishment_date' => $_POST['establishment_date'],
      'founder'            => $_POST['founder']
    ];
    //バリデーションする
    $errors = validate($company);
    if (!count($errors)) {
      //DBに接続する
      $link = dbConnect();
      //DBに登録する
      createCompany($link, $company);
      //DBとの接続を切断する
      mysqli_close($link);
      //リダイレクト
      header("Location: index.php");
    }
    //エラーがあれば下のHTMLになる
  }

include 'views/new.php';

