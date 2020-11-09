<?php
require 'function.php';
require 'quiz.php';

// クイズインスタンス生成
// =======================================================
$quiz = new PhysicalMonster('フィジカルモンスター');

// POST送信がない場合
if (empty($_POST)) {
  debug('POSTの無いときの処理です');
  debug("   ");
  // POSTの内容が空の送信は、クイズ終了時にトップ画面に戻る場合
  $topPage = 1;
} else {
  if (!empty($_POST['easy'])) {
    $_SESSION['easy'] = 1;
  }
  if (!empty($_POST['normal'])) {
    $_SESSION['normal'] = 2;
  }
  if (!empty($_POST['hard'])) {
    $_SESSION['hard'] = 3;
  }

  debug(
    'SESSIONの途中経過です。フラグ確認 index.php(19)：  ' .
      print_r($_SESSION, true)
  );
  debug("   ");

  $img = $quiz->getImg();
  debug('画像配列の中身：' . print_r($img, true));
  debug("   ");

  // 最後のクイズでなければその都度実行される
  if (!$quiz->QuizFinish()) {
    // 現在の問題を取得する
    $data = $quiz->getCurrentQuiz();
    // 回答をシャッフルする
    shuffle($data['a']);
  }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
   

  <!-- CSS -->
  <!-- リセットCSS 他のCSSファイルより前に置く  -->
  <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css">
  <link href="https://fonts.googleapis.com/css?family=M+PLUS+Rounded+1c&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css"/>
  <!-- エフェクト用 -->
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
  <link rel="stylesheet" href="css/style.min.css">
  <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script> 
  <title>フィジカルモンスター</title>
</head>
<body>

<div id="home" class="bg-big">

  <?php if (empty($topPage) && $quiz->QuizFinish()) { ?>

      <?php switch (true) {
        case $_SESSION['easy'] === 1:

          debug('easyの終了画面処理です');
          debug("   ");
          ?>
        <div class="main-contents-fin">
            <h2>お疲れさまでした！</h2>
                <div>
                  <p class="text">
                    正答率<?php echo sanitize($quiz->getCorrectAnswer()); ?>%
                  </p>
                  <p class="text">
                    レジェンドトレーナーの動画を見て更にトレーニングに役立つ知識を身に着けましょう！！
                  </p>
                </div>
                  <div class="box">
                    <iframe width="560" height="350" 
                      src="https://www.youtube.com/embed/_hRmdCueNck" 
                      frameborder="0" 
                      allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" 
                      allowfullscreen>
                    </iframe>
                  </div>
                  <div class="btn-wrapp">
                    <a class="link-btn" href="reset.php">TOPへ戻る</a>
                    <div class="tweet">
                      <?php $quiz->tweetlink(); ?>
                    </div>
                  </div>
          </div><!-- end main-contents-fin -->
      <?php break; ?>

      <?php
        case $_SESSION['normal'] === 2:

          debug('normalの終了画面処理です');
          debug("   ");
          ?>

      <div class="main-contents-fin">
            <h2>お疲れさまでした！</h2>
                <div>
                  <p class="text">
                    正答率<?php echo sanitize($quiz->getCorrectAnswer()); ?>%
                  </p>
                  <p class="text">
                    レジェンドトレーナーの動画を見て更にトレーニングに役立つ知識を身に着けましょう！！
                  </p>
                </div>
                  <div class="box">
                    <iframe marginHeight="10" height="350" src="https://www.youtube.com/embed/gwJFLRmIhec" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                  </div>
                  <div class="btn-wrapp">
                    <a class="link-btn" href="reset.php">TOPへ戻る</a>
                    <?php $quiz->tweetlink(); ?>
                  </div>
       </div><!-- end main-contents-fin -->
      
       <?php break; ?>

      <?php
        case $_SESSION['hard'] === 3:

          debug('hardの終了画面処理です');
          debug("   ");
          ?>

        <div class="main-contents-fin">
                <h2>お疲れさまでした！</h2>
                    <div>
                      <p class="text">
                        正答率<?php echo sanitize(
                          $quiz->getCorrectAnswer()
                        ); ?>%
                      </p>
                      <p class="text">
                        レジェンドトレーナーの動画を見て更にトレーニングに役立つ知識を身に着けましょう！！
                      </p>
                    </div>
                      <div class="box">
                        <iframe width="560" height="350" src="https://www.youtube.com/embed/L7lA6wwH5Z4" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                      </div>
                  <div class="btn-wrapp">
                    <a class="link-btn" href="reset.php">TOPへ戻る</a>
                    <?php $quiz->tweetlink(); ?>
                  </div>
        </div><!-- end main-contents-fin -->

        <?php break; ?>

        <?php
        default:
          debug('switch構文のdefaultの処理です');
          debug("   ");
          return false;
      } ?>

    <?php } else { ?>
 
      <?php if (!empty($topPage)) { ?>
      <div class="main-contents">
          <h1>フィジカル モンスター</h1><span class="main-span">～クイズに答えて筋トレしよう！～</span>
            <div>
              <h2>挑戦する</h2>
              <form method="post" action="">
                <button class="easy" type="submit" name="easy" value="1">かんたん</button>
                <button class="normal" type="submit" name="normal" value="2">ふつう</button>
                <button class="hard" type="submit" name="hard" value="3">えぐい</button>
              </form>
            </div>
      </div>
      <!-- end main-contents -->

      <?php return; ?>

  <?php } elseif (empty($_POST['next'])) {

        debug("POST['next']が空だったときの処理です。" . print_r($_POST, true));
        debug("   ");
        ?>

      <div class="qs-contents">
        <div class="monster-area"> 
          <img  src="<?php echo sanitize($img); ?>" alt="">
        </div>
        
            <div class="qs">
                <p>
                  <?php echo sanitize($quiz->getName()); ?>が現れた！！
                </p>
                <p>
                <?php echo sanitize(
                  $quiz->getName()
                ); ?>は筋トレに関するクイズを出題してきた！！
                </p>
   
                  <div class="form-main">
                      <div class="form">
                        <form method="post" action="">
                          <button type="submit" name="next" value="1">▶次へ</button>
                        </form>
                      </div>
                  </div><!-- end form-main -->            
            </div><!-- end qs -->
        </div><!-- end qs-contents -->

      <?php
      } elseif (
        $_SESSION['easy'] === 1 ||
        $_SESSION['normal'] === 2 ||
        $_SESSION['hard'] === 3
      ) {
        debug(
          "POST['next']が空では無いときの処理です index.php：" .
            print_r($_POST, true)
        ); ?>
            
      <div class="qs-contents">
          <div class="monster-area js-monster-area">
              <p class="correct_answer js-correct">正解！</p> 
              <p class="incorrect_answer js-incorrect">不正解！</p>
              <div class="monster-img" id="monster">
                <img src="<?php echo sanitize($img); ?>" alt="">
              </div>
          </div><!-- end monster-img -->
       
            <div class="qs">
                <p>
                  <?php echo sanitize($data['Q']); ?>
                </p>
                  <ul>
                    <?php foreach ($data['a'] as $a): ?>
                  <li class="answer"><?php echo sanitize($a); ?></li>
                    <?php endforeach; ?>
                  </ul>
                  <div class="form-main">
                      <form method="post" action="">
                          <button type="submit" name="next" value="1" id="btn" class="disable" disabled>▶次の問題へ</button>
                      </form>
                  </div><!-- end form-main -->            
            </div><!-- end qs -->
        </div><!-- end qs-contents -->

      <?php
      } ?>
    
  </div><!-- end bg-big -->
  
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

  <script src="js/bundle.min.js"></script>
 
  
<?php } ?>

</body>
</html>