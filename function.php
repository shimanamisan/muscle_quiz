<?php

ini_set('log_errors', 'on'); //ログを取る
ini_set('error_log', 'logs/php.log'); //エラーログの出力先
session_start(); //セッションを使う

//===================
//デバッグログ関数
//===================
//デバッグフラグ
$debug_flg = true; // 空にすればログを出力しない
//デバッグログ関数
function debug($str)
{
    global $debug_flg;
    if (!empty($debug_flg)) {
        error_log($str);
    }
}

function sanitize($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
