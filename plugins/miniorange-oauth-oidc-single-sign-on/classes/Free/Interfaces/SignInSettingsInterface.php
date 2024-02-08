<?php


namespace MoOauthClient\Free;

interface SignInSettingsInterface
{
    function render_sign_in_options();
    function render_advanced_settings();
    function render_free_ui();
}
