<?php


namespace MoOauthClient;

interface OauthHandlerInterface
{
    public function get_token($CX, $z5, $gL, $n0);
    public function get_access_token($CX, $z5, $gL, $n0);
    public function get_id_token($CX, $z5, $gL, $n0);
    public function get_resource_owner_from_id_token($DU);
    public function get_resource_owner($nI, $AP);
    public function get_response($ht);
}
