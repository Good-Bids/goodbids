<?php


namespace MoOauthClient\Accounts;

interface AccountsInterface
{
    public function mo_oauth_show_new_registration_page();
    public function verify_password_ui();
}
