<?php

//全ての親となる肉体クラス生成(抽象クラス)
//=======================================================
abstract class Physical
{
    protected $name;

    // セッター（モンスター名をセット）
    public function setName($str)
    {
        return $this->name = $str;
    }

    // ゲッター（モンスター名を取得）
    public function getName()
    {
        return $this->name;
    }

    // ツイート用のリンクを生成する
    public function tweetlink()
    {
        if ($this->QuizFinish()) {
            $totalScore = $this->getCorrectanswer();
            // index.phpを切り取る
            $fullURL = $_SERVER["REQUEST_URI"];
            $cutURL = mb_substr($fullURL, 0, -10);
            //現在のURLを取得
            $URL = (empty($_SERVER["HTTPS"]) ? "http://" : "https://") .$_SERVER["HTTP_HOST"] . $cutURL;
        }
        echo '<a href="https://twitter.com/share?ref_src=twsrc%5Etfw" 
              class="twitter-share-button" data-url="'.$URL.'" data-size="large" data-lang="ja" 
              data-count="none" data-show-count="false" 
              data-hashtags="プログラミング学習,フィジカルモンスター" 
              data-text="あなたの正答率は'.sanitize($totalScore).'%です！クイズに答えて筋トレしよう！"></a>';
    }
}

//クイズモンスタークラス
//=======================================================
class PhysicalMonster extends Physical
{

  // コンストラクタにて初期化処理
    public function __construct($name)
    {
        $this->name = $name;
        $this->setUPeasy();
        $this->setUPnormal();
        $this->setUPhard();
        $this->ImgSetUP();

        //SESSIONがセットされていなければセットする
        if (!isset($_SESSION['current_num'])) {
            $_SESSION['current_num'] = 0;
            $_SESSION['correct_count'] = 0;
            $_SESSION['easy'] = 0;
            $_SESSION['normal'] = 0;
            $_SESSION['hard'] = 0;
            $test = $_SERVER["REQUEST_URI"];
            $g = mb_substr($test, 0, -10);
            debug('SESSIONをセットしました construct処理(36)：'.print_r($_SESSION, true));
            debug('   ');
            debug('debagg u：'. $g);
            debug('   ');
        }
    }

    //現在、何問目か判断するメソッド
    public function getCurrentQuiz()
    {
        switch (true) {
      case $_SESSION['easy'] === 1:
        return $this->questionEasy[$_SESSION['current_num']];
 
      case $_SESSION['normal'] === 2:
        return $this->questionNormal[$_SESSION['current_num']];

      case $_SESSION['hard'] === 3:
        return $this->questionHard[$_SESSION['current_num']];
     
      default:
        debug('defaultの処理です ：getCurrentQuizメソッド');
        debug('   ');
        return false;
    }
    }

    // 正答率を算出するメソッド
    public function getCorrectAnswer()
    {
        switch (true) {
        case $_SESSION['easy'] === 1:
          return round($_SESSION['correct_count'] / count($this->questionEasy) * 100) ;
  
        case $_SESSION['normal'] === 2:
          return round($_SESSION['correct_count'] / count($this->questionNormal) * 100) ;

        case $_SESSION['hard'] === 3:
          return round($_SESSION['correct_count'] / count($this->questionHard) * 100) ;
      
        default:
          debug('defaultの処理です ：getCurrentQuizメソッド');
          debug('   ');
          return false;
      }
    }

    // 正解を引っ張ってくるためのメソッド
    public function checkAnswer()
    {
        switch (true) {
      case $_SESSION['easy'] === 1:
          $correct_answer = $this->questionEasy[$_SESSION['current_num']]['a'][0];
          if ($correct_answer === $_POST['answer']) {
              $_SESSION['correct_count']++;
          }
            debug('$_SESSION[current_num]チェック前'.print_r($_SESSION['current_num'], true));
            debug('   ');
            
          $_SESSION['current_num']++;
            debug('$_SESSION[current_num]チェック後'.print_r($_SESSION['current_num'], true));
            debug('   ');
          return $correct_answer;
 
      case $_SESSION['normal'] === 2:
          $correct_answer = $this->questionNormal[$_SESSION['current_num']]['a'][0];
          if ($correct_answer === $_POST['answer']) {
              $_SESSION['correct_count']++;
          }
           debug('$_SESSION[current_num]チェック前'.print_r($_SESSION['current_num'], true));
           debug('   ');

          $_SESSION['current_num']++;
           debug('$_SESSION[current_num]チェック後'.print_r($_SESSION['current_num'], true));
           debug('   ');
          return $correct_answer;

      case $_SESSION['hard'] === 3:
          $correct_answer = $this->questionHard[$_SESSION['current_num']]['a'][0];
            if ($correct_answer === $_POST['answer']) {
                $_SESSION['correct_count']++;
            }
            debug('$_SESSION[current_num]チェック前'.print_r($_SESSION['current_num'], true));
            debug('   ');

          $_SESSION['current_num']++;
            debug('$_SESSION[current_num]チェック後'.print_r($_SESSION['current_num'], true));
            debug('   ');
          return $correct_answer;
     
      default:
          debug('defaultの処理です ：checkAnswerメソッド');
          debug('   ');
          return false;
    }
    }

    // count($this->question)で配列の総数をカウント、ここでは問題が5問入っているので5が返ってくる
    // $_SESSION['current_num']と同数になれば最後の問題なので終了する
    public function QuizFinish()
    {
        switch (true) {
        case $_SESSION['easy'] === 1:
          return count($this->questionEasy) === $_SESSION['current_num'];
     
        case $_SESSION['normal'] === 2:
          return count($this->questionNormal) === $_SESSION['current_num'];
       
        case $_SESSION['hard'] === 3:
          return count($this->questionHard) === $_SESSION['current_num'];
   
        default:
          debug('defaultの処理です ：QuizFinishメソッド');
          debug('   ');
          return false;
    }
    }

    //フィジカルモンスターの画像をランダムで出力
    public function getImg()
    {
        $i = mt_rand(0, 4);
        return $this->img[$i];
    }


    // コンストラクタ呼び出し時に初期化させるメソッド
    // =======================================================
    // キー'a'の先頭が正解という判定をしているので、正解は先頭に持ってくる
    // インスタンス生成時に、各questionプロパティにクイズを格納する
    private function setUPeasy()
    {
        $this->questionEasy[] = [
      'Q' => 'Ｑ：トレーニング後の休息は、何時間必要とされているか？',
      'a' => ['48～72時間','12～24時間','3～5時間','6～9時間']
    ];

        $this->questionEasy[] = [
      'Q' => 'Ｑ：プロテインは日本語に直訳すると何という意味か？',
      'a' => ['タンパク質','アミノ酸','ロイシン','グルタミン']
    ];
        $this->questionEasy[] = [
      'Q' => 'Ｑ：トレーニング後、適切な休息をとることで筋力の向上や筋肥大などの効果が現れる現象をなんと呼ぶか？',
      'a' => ['超回復','即回復','全回復','急回復']
    ];
        $this->questionEasy[] = [
      'Q' => 'Ｑ：トレーニング後、○○分以内にタンパク質を摂取することが筋タンパク質合成のゴールデンタイムとされているか？' ,
      'a' => ['60分','30分','15分','5分']
    ];
        $this->questionEasy[] = [
      'Q' => 'Ｑ：ヨーグルトの上澄みから生成されるプロテインを何というか？' ,
      'a' => ['ホエイプロテイン','ソイプロテイン','マイプロテイン','アシッドプロテイン']
    ];
        $this->questionEasy[] = [
      'Q' => 'Ｑ：ビタミン、ミネラル、食物繊維を豊富に含む和名が「メハナヤサイ」と呼ばれる緑黄色野菜は？' ,
      'a' => ['ブロッコリー','アスパラガス','カリフラワー','キャベツ']
    ];
    }

    private function setUPnormal()
    {
        $this->questionNormal[] = [
      'Q' => 'Ｑ：筋トレにはBIG3と呼ばれる種目があり、ベンチプレス、スクワット、あと一つは何か？' ,
      'a' => ['デッドリフト','プルダウン','シーテッドローイング','スーパーリフト']
    ];
        $this->questionNormal[] = [
      'Q' => 'Ｑ：筋肉の中でも回復が早い部位はどこか？' ,
      'a' => ['腹筋','大胸筋','広背筋','大腿四頭筋']
    ];
        $this->questionNormal[] = [
      'Q' => 'Ｑ：ランニングのような有酸素運動は、一般的に何分以上行うことが脂肪燃焼に良いとされているか？' ,
      'a' => ['20分','10分','15分','30分']
    ];
        $this->questionNormal[] = [
      'Q' => 'Ｑ：トレーニング時間の短縮及び、心肺機能の向上も期待できるセット法を何というか？' ,
      'a' => ['スーパーセット法','マルチセット法','ファーストレップス法','トライセット法']
    ];
        $this->questionNormal[] = [
      'Q' => 'Ｑ：同一筋系統の種目を2つ続けて行うことを1セットとするセット法を何というか？' ,
      'a' => ['コンパウントセット法','ジャイアントセット法','フォーストレップス法','サードレップス法']
    ];
        $this->questionNormal[] = [
      'Q' => 'Ｑ：トレーニング後、筋タンパク質の合成感度が上昇するが、少なくとも何時間継続することが明らかにされているか？' ,
      'a' => ['24時間','12時間','48時間','72時間']
    ];
    }

    private function setUPhard()
    {
        $this->questionHard[] = [
        'Q' => 'Ｑ：筋肉の合成を上回る量で、筋肉の分解が行われている状態を何というか。' ,
        'a' => ['カタボリック','アナボリック','メタボリック','シンボリック']
      ];
        $this->questionHard[] = [
        'Q' => 'Ｑ：総クレアチン量は最大○○グラムでまで増やせると推測されているか？' ,
        'a' => ['160g','150g','140g','120g']
      ];
        $this->questionHard[] = [
        'Q' => 'Ｑ：一般にアミノ酸が○○個以上結合したものをタンパク質呼ぶか？' ,
        'a' => ['50個','100個','150個','200個']
      ];
        $this->questionHard[] = [
        'Q' => 'Ｑ：筋肉の分解を上回る量で、筋肉の合成が行われている状態を何というか。' ,
        'a' => ['アナボリック','カタボリック','シンボリック','メタボリック']
      ];
        $this->questionHard[] = [
        'Q' => 'Ｑ：一般にアミノ酸の結合が○○個未満であればペプチドと呼ぶか？' ,
        'a' => ['50個','40個','45個','30個']
      ];
        $this->questionHard[] = [
        'Q' => 'Ｑ：筋タンパク質の合成作用を高める酵素を何というか？' ,
        'a' => ['mTOR','mTPI','pAPI','iTRO']
      ];
    }

    private function ImgSetUP()
    {
        $this->img = ['img/img01.jpg','img/img02.jpg','img/img03.jpg','img/img04.jpg','img/img05.jpg'];
    }
}
