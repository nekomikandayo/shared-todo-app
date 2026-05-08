<?php

class Controller_Logout extends Controller
{
    public function action_index()
    {
        Session::destroy();

        Response::redirect('/login');
    }
}