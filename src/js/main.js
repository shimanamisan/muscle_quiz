$(function () {
  "use strict";
  /*******************************************
ユーザーエージェントよりスマホ端末か判定する
*********************************************/
  // 即時関数をの返り値を変数に格納（オブジェクトの形で返ってくる）
  let UserAgent = (function () {
    let phoneActive = /iPhone|iPod|iPad|Android/i.test(
      window.navigator.userAgent
    );
    let $home = $("#home");

    // オブジェクトを返す
    return {
      phoneFlg: function () {
        if (phoneActive) {
          console.log("ユーザーエージェントを実行");
          $home.css({
            height: 'auto',
            height: `${window.outerHeight}`,
          });
        }
      },
      phone: phoneActive,
    };
  })();
  // オブジェクトの中の関数を実行
  UserAgent.phoneFlg();

  /*******************************************
画像の高さを取得して親要素に付与する
砕けるアニメーション後に、要素がなくならないようにする
*********************************************/
  let $monster = $("#monster"); // 画像を包んでいる要素の
  let monsterHeight = $monster.innerHeight(); // 画像を包んでいる要素の高さ
  let monsterWrapp = $(".js-monster-area"); // 画像領域の親要素
  console.log(monsterHeight);
  monsterWrapp.attr("style", "height: " + monsterHeight + "px");

  $(".answer").on("click", function () {
    let $select = $(this);
    let answer = $select.text();
    let $correct = $(".js-correct");
    let $incorrect = $(".js-incorrect");
    let $btn = $("#btn");
    console.log();

    // 正誤判定後のクラスがついていた場合、後続の処理は行わない
    if ($select.hasClass("true") || $select.hasClass("false")) {
      return;
    }

    $.ajax({
      type: "POST",
      url: "answer.php",
      dataType: "json",
      data: { answer },
    }).done(function (response) {
      // answerクラスのDOMに対して正誤判定を繰り返す。要素にtrueクラス、falseクラスのスタイルを適用
      // eachメソッド：要素に対して処理を繰り返す
      $(".answer").each(function () {
        if ($(this).text() === response.correct_answer) {
          $(this).addClass("true");
        } else {
          $(this).addClass("false");
        }
      });
      if (answer === response.correct_answer) {
        $correct.show().addClass("animate__bounceIn");

        if (UserAgent.phone) {
          // スマホ表示だったら砕けるアニメーションのクラスは付与しない
          console.log("スマホ表示です");
        } else {
          // 画像が砕けるアニメーションクラスを付与
          $monster.toggle(
            "explode",
            {
              pieces: 100,
            },
            1000
          );
        }

        setTimeout(function () {
          $correct.addClass("animate__flipOutY");
          // CSSのanimationプロパティで設定したものが終了したときに以下の内容を実行する
          $correct.on("animationend webkitAnimationEnd", function () {
            $correct.hide();
            $correct.removeClass("animate__flip");
            $correct.removeClass("animate__flipOutY");
          });
        }, 2000);
      } else {
        // 不正解だったときのアニメーション
        $incorrect.show().addClass("animate__bounceIn");
        setTimeout(function () {
          $incorrect.addClass("animate__bounceOut");
          // CSSのanimationプロパティで設定したものが終了したときに以下の内容を実行する
          $incorrect.on("animationend webkitAnimationEnd", function () {
            $incorrect.hide();
            $correct.removeClass("animate__bounceIn");
            $correct.removeClass("animate__bounceOut");
          });
        }, 2000);
      }
      $btn.removeClass("disable"); // disableクラスを削除する
      $btn.prop("disabled", false); // disabled属性を外す
    });
  });
});
