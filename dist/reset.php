<?php

require 'function.php';
require 'quiz.php';

debug("rest.php に遷移しています。");
debug("セッションの中身です。" . print_r($_SESSION, true));

$_SESSION = [];
session_destroy();

// クイズインスタンス生成
// =======================================================
$quiz = new PhysicalMonster('フィジカルモンスター');

header("Location:index.php");

exit();
