<?php
session_start();
require('../dbconnect.php');
if($_SESSION['admin_login'] == false){
					 header("Location:./index.php");
}
if(!isset($_SERVER['HTTP_REFERER'])){
	// redirect them to your desired location
	header('location:../login.php');
	exit;
}

if (!empty($_POST)) {
    if ($_POST['name'] === '') {
        $error['name'] = 'blank';
    }
    if ($_POST['email'] === '') {
	      $error['email'] = 'blank';
		}
		if (!preg_match('/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/',$_POST['email']) ){
	      $error['email'] = 'path';
	  }
		if (strlen($_POST['number']) >6) {
			  $error['number'] = 'small';
	  }
	  if ($_POST['number'] === '') {
		  	$error['number'] = 'blank';
	  }
    if (strlen($_POST['password']) <4) {
        $error['password'] = 'length';
    }
    if ($_POST['password'] === '') {
	      $error['password'] = 'blank';
		}
		$fileName = $_FILES['image']['name'];
		if (!empty($fileName)) {
				$ext = substr($fileName, -3);
				if ($ext != 'jpg' && $ext != 'gif' && $ext != 'png') {
					  $error['image'] = 'type';
				}
		}
		if(empty($error)) {
			$member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE email=?');
			$member->execute(array($_POST['email']));
			$record = $member->fetch();
			if ($record['cnt'] > 0) {
				$error['email'] = 'duplicate';
			}
		}
		if(empty($error)) {
			$member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE number=?');
			$member->execute(array($_POST['number']));
			$record = $member->fetch();
			if ($record['cnt'] > 0) {
				$error['number'] = 'duplicate';
			}
		}
    if (empty($error)) {
			 $image = date('YmdHis') . $_FILES['image']['name'];
			 move_uploaded_file($_FILES['image']['tmp_name'],'../member_picture/' . $image);
			 $_SESSION['join'] = $_POST;
			 $_SESSION['join']['image'] = $image;
       header('Location: ../join/check.php');
	  exit();
    }
}

if ($_REQUEST['action'] == 'rewrite' && isset($_SESSION['join'])) {
	  $_POST = $_SESSION['join'];
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>会員登録</title>

	<link rel="stylesheet" href="../style.css" />
</head>
<body>
<div id="wrap">
<div id="head">
<h1>会員登録</h1>
</div>

<div id="content">
<p>次のフォームに必要事項をご記入ください。</p>
<form action="" method="post" enctype="multipart/form-data">
	<dl>
		<dt>名前<span class="required">必須</span></dt>
		<dd>
        	<input type="text" name="name" size="35" maxlength="255" value="<?php print(htmlspecialchars($_POST['name'], ENT_QUOTES)); ?>" />
					<?php if ($error['name'] === 'blank'): ?>
					<p class="error">*名前を入力して下さい</p>
					<?php endif; ?>
		</dd>
		<dt>メールアドレス<span class="required">必須</span></dt>
		<dd>
        	<input type="text" name="email" size="35" maxlength="255" value="<?php print(htmlspecialchars($_POST['email'], ENT_QUOTES)); ?>" />
					<?php if ($error['email'] === 'blank'): ?>
					<p class="error">*メールアドレスを入力して下さい</p>
					<?php endif; ?>
					<?php if ($error['email'] === 'path'): ?>
					<p class="error">*メールアドレスの形式で入力して下さい</p>
					<?php endif; ?>
					<?php if ($error['email'] === 'duplicate'): ?>
					<p class="error">*指定されたメールアドレスは既に登録されています</p>
					<?php endif; ?>
		<dt>社員番号<span class="required">必須</span></dt>
		<dd>
        	<input type="number" name="number" size="10" maxlength="20" value="<?php print(htmlspecialchars($_POST['number'], ENT_QUOTES)); ?>" />
					<?php if ($error['number'] === 'blank'): ?>
					<p class="error">*社員番号を入力して下さい</p>
					<?php endif; ?>
					<?php if ($error['number'] === 'small'): ?>
					<p class="error">*社員番号は6文字以内の数字で入力して下さい</p>
					<?php endif; ?>
					<?php if ($error['number'] === 'duplicate'): ?>
					<p class="error">*指定された社員番号は既に登録されています</p>
					<?php endif; ?>
		<dt>パスワード<span class="required">必須</span></dt>
		<dd>
        	<input type="password" name="password" size="10" maxlength="20" value="<?php print(htmlspecialchars($_POST['password'], ENT_QUOTES)); ?>" />
					<?php if ($error['password'] === 'blank'): ?>
					<p class="error">*パスワードを入力して下さい</p>
					<?php endif; ?>
					<?php if ($error['password'] === 'length'): ?>
					<p class="error">*パスワードは4文字以上で入力して下さい</p>
					<?php endif; ?>
        </dd>
		<dt>写真など</dt>
		<dd>
        	<input type="file" name="image" size="35" value="test"  />
        </dd>
				  <?php if ($error['image'] === 'type'): ?>
					<p class="error">*写真などは「.gif」または「.jpg」「.png」の画像を指定して下さい</p>
					<?php endif; ?>
					<?php if (!empty($error)): ?>
					<p class="error">*恐れ入りますが、画像を改めて指定して下さい</p>
					<?php endif; ?>
	</dl>
	<div><input type="submit" value="入力内容を確認する" /><a href="../logout.php">&laquo;&nbsp;ログアウトしてトップページに戻る</a></div>
</form>
</div>
</body>
</html>