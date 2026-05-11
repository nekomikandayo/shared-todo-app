<?php

abstract class Controller_Base extends Controller
{
    /**
     * POST に含まれる CSRF トークンを検証する。
     * 欠落・不一致のときはメッセージを表示して処理を中断する。
     */
    protected function require_csrf()
    {
        $input = json_decode(
            file_get_contents('php://input'),
            true
        );

        $token = $input['csrf_token']
            ?? Input::post('csrf_token');

        if (!$token || !Security::check_token($token)) {
            return Response::forge(
                json_encode([
                    'success' => false,
                    'message' => 'CSRFトークンがありません'
                ]),
                403,
                [
                    'Content-Type' => 'application/json'
                ]
            );
        }
    }
}
