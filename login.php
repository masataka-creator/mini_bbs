<?php
session_start();
require('dbconnect.php');

if ($_COOKIE['number'] !== '') {
  $number = $_COOKIE['number'];
}

if(!empty($_POST)) {
  $number = $_POST['number'];
  
  if (strlen($_POST['number']) >6) {
    $error['number'] = 'small';
  }
  if (strlen($_POST['password']) <4) {
    $error['password'] = 'length';
  }
  if ($_POST['number'] !== '' && $_POST['password'] !== '') {
$login = $db->prepare('SELECT * FROM members WHERE number=? AND password=?');
$login->execute(array(
  $_POST['number'],
  sha1($_POST['password'])
));
$member = $login->fetch();

if ($member) {
  $_SESSION['id'] = $member['id'];
  $_SESSION['time'] = time();

  if ($_POST['save'] === 'on') {
    setcookie('number', $_POST['number'], time()+60*60*24*14);
  }
  header('Location: index.php');
  exit();
} else {
  $error['login'] = 'failed';
}
  } else {
    $error['login'] = 'blank';
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="style.css" />
<title>one-group</title>
</head>

<body>
<div id="wrap">
  <div id="head">
    <h1>one-group</h1>
  </div>
  <div id="content">
    <div id="lead">
      <p>社員番号とパスワードを記入してログインしてください。</p>
      <p>登録がまだの場合は管理者による登録が必要です</p>
      <p>&raquo;<a href="admin/">管理者による登録画面へ</a></p>
    </div>
    <form action="" method="post">
      <dl>
        <dt>社員番号</dt>
        <dd>
          <input type="number" name="number" size="35" maxlength="255" value="<?php echo htmlspecialchars($number, ENT_QUOTES); ?>" />
          <?php if ($error['login'] === 'blank'): ?>
					<p class="error">*社員番号とパスワードをご記入下さい</p>
					<?php endif; ?>
          <?php if ($error['login'] === 'failed'): ?>
					<p class="error">*ログインに失敗しました。正しくご記入下さい</p>
					<?php endif; ?>
					<?php if ($error['number'] === 'small'): ?>
					<p class="error">*社員番号は6文字以内の数字で入力して下さい</p>
					<?php endif; ?>
        </dd>
        <dt>パスワード</dt>
        <dd>
          <input type="password" name="password" size="35" maxlength="255" value="<?php echo htmlspecialchars($_POST['password'], ENT_QUOTES); ?>" />
					<?php if ($error['password'] === 'length'): ?>
					<p class="error">*パスワードは4文字以上で入力して下さい</p>
					<?php endif; ?>
        </dd>
        <dt>ログイン情報の記録</dt>
        <dd>
          <input id="save" type="checkbox" name="save" value="on">
          <label for="save">次回からは自動的にログインする</label>
        </dd>
      </dl>
      <div>
        <input type="submit" value="ログインする" />
      </div>
    </form>
  </div>
</div>
</body>
</html>
