<?php
require 'function.php';
require 'quiz.php';

//メソッドを使うためにこっちでもインスタンス化
$quiz = new PhysicalMonster('フィジカルモンスター');

debug('SESSIONの中身 answer.php' . print_r($_SESSION, true));
debug('   ');
debug(
  'Ajax処理でPOSTされているか確認 answer.php：' .
    print_r($_POST['answer'], true)
);
debug('   ');

$correct_answer = $quiz->checkAnswer();
debug(
  '配列から引っ張ってきた答え answer.php：' . print_r($correct_answer, true)
);
debug('   ');

//返ってきた値をJSONで渡す
header('Content-Type: application/json; charset=UTF-8');
echo json_encode([
  //key => value の形で渡している。これをJS側でキー（correct_answer）を指定してやると、$(this).text()と正誤判定できる
  'correct_answer' => $correct_answer,
]);

debug('答えの中身 answer.php：' . print_r($correct_answer, true));
debug('   ');
