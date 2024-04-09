<?php


namespace MoOauthClient;

interface OauthHandlerInterface
{
    public function get_token($vy, $uo, $U_, $fn);
    public function get_access_token($vy, $uo, $U_, $fn);
    public function get_id_token($vy, $uo, $U_, $fn);
    public function get_resource_owner_from_id_token($A2);
    public function get_resource_owner($pU, $C2);
    public function get_response($Ws);
}
