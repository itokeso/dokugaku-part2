<?php

function validate($review)
{
  $errors = [];

  // 書籍が正しく入力されているかチェック
  if (!strlen($review['title'])) {
    $errors['title'] = '書籍名を入力してください';
  } elseif (strlen($review['title']) > 255) {
      $errors['title'] = '255文字以内で入力してください';
  }

  //評価が正しく入力されているかチェック
  if ($review['score'] < 1 || $review['score'] > 5) {
    $errors['score'] = '評価は1から5の整数を入力して下さい';
  }

  // 著者名が正しく入力されているかチェック
  if (!strlen($review['author'])) {
    $errors['author'] = '著者を入力してください';
  } elseif (strlen($review['title']) > 100) {
    $errors['author'] = '著者名は100文字以内で入力してください';
  }

  // 読書状況が正しく入力されているかチェック
  $array = ['未読', '読んでる', '読了'];
  if (!in_array($review['status'], $array, true)) {
    $errors['status'] = '未読, 読んでる, 読了から選択して下さい';
  }

  // 感想が正しく入力されているかチェック
  if (!strlen($review['summary'])) {
    $errors['summary'] = '感想を入力してください';
  } elseif (strlen($review['title']) > 1000) {
    $errors['summary'] = '感想は1000文字以内で入力してください';
  }

  return $errors;
}

function createReview($link)
{
  $review = [];
  
  echo '読書ログを登録してください'. PHP_EOL;
  echo '書籍名:';
  $review['title'] = trim(fgets(STDIN));
  
  echo '著者名:';
  $review['author'] = trim(fgets(STDIN));
  
  echo '読書状況:';
  $review['status'] = trim(fgets(STDIN));
  
  echo '評価:';
  $review['score'] = (int) trim(fgets(STDIN));
  
  echo '感想:';
  $review['summary'] = trim(fgets(STDIN));

  $validated = validate($review);
  if (count($validated) > 0) {
    foreach ($validated as $error) {
      echo $error . PHP_EOL;
    }
    return;
  }

$sql = <<<EOT
INSERT INTO reviews (
    title,
    author,
    status,
    score,
    summary
) VALUES (
    "{$review['title']}",
    "{$review['author']}",
    "{$review['status']}",
    "{$review['score']}",
    "{$review['summary']}"
)
EOT;

  $result = mysqli_query($link, $sql);
  if ($result) {
    echo 'データを追加しました。'. PHP_EOL;
  } else {
    echo '失敗しました。'. PHP_EOL;
    echo 'error:'. mysqli_error($link). PHP_EOL;
  }
}

function showReviews($link)
{
  echo '読書ログを表示します。'. PHP_EOL;
  
  $sql = 'SELECT id, title, author, status, score, summary FROM reviews';
  $results = mysqli_query($link, $sql);

  while ($review = mysqli_fetch_assoc($results)) {
    echo 'タイトル:' . $review['title']. PHP_EOL;
    echo '著者:' . $review['author'] . PHP_EOL;
    echo '読書状況:' . $review['status'] . PHP_EOL;
    echo '評価:' . $review['score'] . PHP_EOL;
    echo '感想:' . $review['summary'] . PHP_EOL;
    echo '-------------------------------------'. PHP_EOL;
  }

  mysqli_free_result($results);
}

function connectDatabase()
{
  $link = mysqli_connect('db', 'book_log', 'pass', 'book_log');

  if (!$link) {
    echo 'dbに接続できませんでした'. PHP_EOL;
    echo 'error'. mysqli_connect_error(). PHP_EOL;
  }

  return $link;
}


$link = connectDatabase();
while (true) {
  echo '1: 読書ログを登録'.PHP_EOL;
  echo '2: 読書ログを表示'.PHP_EOL;
  echo '9: アプリケーションを終了する'.PHP_EOL;
  echo '番号を選択してください(1,2,9):';
  $num = trim(fgets(STDIN));
  if ($num === '1') {
    createReview($link);
  } elseif ($num === '2') {
    showReviews($link);
  } elseif ($num === '9') {

    mysqli_close($link);
    echo 'dbとの接続を切断しました'. PHP_EOL;
    break;
  } else {
    echo '無効な値です'.PHP_EOL;
  }

}

