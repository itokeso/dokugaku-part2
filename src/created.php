<?php
require_once __DIR__ . '/lib/mysqli.php';

function createReview($link, $review)
{
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
    if (!$result) {
        error_log('create failed') . PHP_EOL;
        echo 'error'. mysqli_error($link);
    }
}

function validate($review)
{
    $errors = [];
    //タイトル
    if (!strlen($review['title'])) {
        $errors['title'] = 'タイトルを入力してください';
    } elseif (strlen($review['title']) > 255) {
        $errors['title'] = 'タイトルは255文字以内で入力してください';
    }

    //筆者
    if (!strlen($review['author'])) {
        $errors['author'] = 'タイトルを入力してください';
    } elseif (strlen($review['author']) > 255) {
        $errors['author'] = 'タイトルは255文字以内で入力してください';
    }

    //読書状況
    if (!in_array($review['status'], ['未読', '読んでる', '読了'])) {
        $errors['status'] = '読書状況はいずれかを選択してください';
    }

    //評価
    if ($review['score'] < 1 || $review['score'] > 5) {
        $errors['score'] = '評価は1~5の整数で入力してください';
    }

    //感想
    if (!strlen($review['summary'])) {
        $errors['summary'] = '感想を入力してください';
    } elseif (strlen($review['summary']) > 10000) {
        $errors['summary'] = '感想は10000文字以内で入力してください';
    }

    return $errors;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $status = '';
    if (array_key_exists('status', $_POST)) {
        $status = $_POST['status'];
    }
    //登録情報を変数に格納
    $review = [
        'title'   => $_POST['title'],
        'author'  => $_POST['author'],
        'status'  => $status,
        'score'   => $_POST['score'],
        'summary' => $_POST['summary']
    ];
    //バリデーション
    $errors = validate($review);
    if (!count($errors)) {
        //DBに接続
        $link = dbConnect();
        //DBに登録
        createReview($link, $review);
        //DB接続解除
        mysqli_close($link);
        //リダイレクト
        header("Location: index.php");
    }
}

include 'views/new.php';
