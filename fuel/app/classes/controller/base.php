<?php

abstract class Controller_Base extends Controller
{
    /**
     * POST に含まれる CSRF トークンを検証する。
     * 欠落・不一致のときはメッセージを表示して処理を中断する。
     */
    protected function require_csrf()
    {
        if (! Security::check_token()) {
            exit('CSRFトークンがありません、または無効です。ページを再読み込みしてから再度お試しください。');
        }
    }
}
