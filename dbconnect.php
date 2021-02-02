<?php
try {
  $db = new PDO('mysql:dbname=heroku_07672f8095a1cdf;host=us-cdbr-east-03.cleardb.com;charset=utf8','b4517625d8bf13','d2650344');
} catch(PDOException $e) {
  print('DB接続エラー：' . $e->getMessage());
}
?>