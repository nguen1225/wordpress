<?php

// メッセージを保存するファイルのパス設定
define('FILENAME', './message.txt');

//タイムゾーン設定
date_default_timezone_set('Asia/tokyo');

//変数の初期化
$now_date = null;
$date = null;
$file_handle = null;
$split_date = null;
$message = array();
$message_array = array();
$success_message = null;
$error_message = array();
$clean = array();


if (!empty($_POST['btn_submit'])) {
    //表示名のチェック
    if (empty($_POST['view_name'])) {
        $error_message[] = '名前を入力してください。';
    } else {
        $clean['view_name'] = htmlspecialchars($_POST['view_name'], ENT_QUOTES);
        $clean['view_name'] = preg_replace('/\\r\\n|\\r/', '', $clean['view_name']);
    }
    //テキストのチェック
    if (empty($_POST['message'])) {
        $error_message[] = 'textを入力してください。';
    } else {
        $clean['message'] = htmlspecialchars($_POST['message'], ENT_QUOTES);
        $clean['message'] = preg_replace('/\\r\\n|\\r/', '<br>', $clean['message']);
    }

    if (empty($error_message)) {

        if ($file_handle = fopen(FILENAME, "a")) {
            //書き込み日時を取得
            $now_date = date("Y-m-d H:i:s");
            //書き込むデータを作成
            $date = "'" . $clean['view_name'] . "','" . $clean['message'] . "','" . $now_date . "'\n";
            //書き込み
            fwrite($file_handle, $date);
            //ファイルを閉じる。
            fclose($file_handle);

            $success_message = 'ｔｗｅｅｔが完了しました。';
        }
        // var_dump($_POST);	
    }

    if ($file_handle = fopen(FILENAME, 'r')) {
        while ($data = fgets($file_handle)) {
            $split_date = preg_split('/\'/', $data);
            $message = array(
                'view_name' => $split_date[1],
                'message' => $split_date[3],
                'post_date' => $split_date[5]
            );
            array_unshift($message_array, $message);
            // echo $data . "<be>";
        }
        //ファイルを閉じる
        fclose($file_handle);
    }
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>テストで作った</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="/wp-content/themes/original/app.css">
    <link rel="stylesheet" href="/wp-content/themes/original/style.css">
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>
    <!-- ここにメッセージの入力フォームを設置 -->
    <h1><span class="red">テストで作った</span></h1>

    <?php if (!empty($success_message)) : ?>
        <p class="success_message">
            <?php echo $success_message; ?>
        </p>
    <?php endif; ?>

    <?php if (!empty($error_message)) : ?>
        <ul class="error_message">
            <?php foreach ($error_message as $value) : ?>
                <li>
                    <?php echo $value; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <div class="messageform" style="margin-left: 20px;">
        <form method="post">
            <div>
                <label for="view_name">name</label>
                <br>
                <input id="view_name" type="text" name="view_name" value="">
            </div>
            <br>
            <div>
                <label for="message">text</label>
                <br>
                <textarea id="message" name="message"></textarea>
            </div>
            <br>
            <input type="submit" name="btn_submit" value="ｔｗｅｅｔ">
        </form>
        <hr>
        <section>
            <?php if (!empty($message_array)) : ?>
                <?php foreach ($message_array as $value) : ?>
                    <article>
                        <div class="info">
                            <h2>
                                <?php echo $value['view_name']; ?>
                            </h2>
                            <time>
                                <?php echo date('y年m月d日 H:i', strtotime($value['post_date'])); ?>
                            </time>
                        </div>
                        <p>
                            <?php echo $value['message']; ?>
                        </p>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    </div>
    <hr>

    <footer>
        <div class="container">
            <p class="text-center">Copyright © books.</p>
        </div>
    </footer>
</body>

</html>