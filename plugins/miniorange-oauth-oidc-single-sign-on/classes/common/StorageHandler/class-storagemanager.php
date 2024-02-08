<?php


namespace MoOauthClient;

use MoOauthClient\StorageHandler;
class StorageManager
{
    private $storage_handler;
    const PRETTY = "\160\x72\x65\x74\x74\x79";
    const JSON = "\x6a\x73\x6f\156";
    const RAW = "\x72\x61\x77";
    public function __construct($OC = '')
    {
        $this->storage_handler = new StorageHandler(empty($OC) ? $OC : base64_decode($OC));
    }
    private function decrypt($hH)
    {
        return empty($hH) || '' === $hH ? $hH : strtolower(hex2bin($hH));
    }
    private function encrypt($hH)
    {
        return empty($hH) || '' === $hH ? $hH : strtoupper(bin2hex($hH));
    }
    public function get_state()
    {
        return $this->storage_handler->stringify();
    }
    public function add_replace_entry($cW, $LQ)
    {
        if ($LQ) {
            goto TW;
        }
        return;
        TW:
        $LQ = is_string($LQ) ? $LQ : wp_json_encode($LQ);
        $this->storage_handler->add_replace_entry(bin2hex($cW), bin2hex($LQ));
    }
    public function get_value($cW)
    {
        $LQ = $this->storage_handler->get_value(bin2hex($cW));
        if ($LQ) {
            goto ek;
        }
        return false;
        ek:
        $DP = json_decode(hex2bin($LQ), true);
        return json_last_error() === JSON_ERROR_NONE ? $DP : hex2bin($LQ);
    }
    public function remove_key($cW)
    {
        $LQ = $this->storage_handler->remove_key(bin2hex($cW));
    }
    public function validate()
    {
        return $this->storage_handler->validate();
    }
    public function dump_all_storage($HW = self::RAW)
    {
        $Nb = $this->storage_handler->get_storage();
        $nh = [];
        foreach ($Nb as $cW => $LQ) {
            $ul = \hex2bin($cW);
            if ($ul) {
                goto PV;
            }
            goto KP;
            PV:
            $nh[$ul] = $this->get_value($ul);
            KP:
        }
        Oc:
        switch ($HW) {
            case self::PRETTY:
                echo "\74\160\x72\x65\76";
                print_r($nh);
                echo "\74\x2f\160\x72\145\76";
                goto sV;
            case self::JSON:
                echo \json_encode($nh);
                goto sV;
            default:
            case self::RAW:
                print_r($nh);
                goto sV;
        }
        bG:
        sV:
    }
}
