<?php


namespace MoOauthClient;

use MoOauthClient\StorageHandler;
class StorageManager
{
    private $storage_handler;
    const PRETTY = "\160\162\145\164\164\171";
    const JSON = "\152\x73\157\x6e";
    const RAW = "\x72\x61\x77";
    public function __construct($Yw = '')
    {
        $this->storage_handler = new StorageHandler(empty($Yw) ? $Yw : base64_decode($Yw));
    }
    private function decrypt($P3)
    {
        return empty($P3) || '' === $P3 ? $P3 : strtolower(hex2bin($P3));
    }
    private function encrypt($P3)
    {
        return empty($P3) || '' === $P3 ? $P3 : strtoupper(bin2hex($P3));
    }
    public function get_state()
    {
        return $this->storage_handler->stringify();
    }
    public function add_replace_entry($Mr, $t_)
    {
        if ($t_) {
            goto Je;
        }
        return;
        Je:
        $t_ = is_string($t_) ? $t_ : wp_json_encode($t_);
        $this->storage_handler->add_replace_entry(bin2hex($Mr), bin2hex($t_));
    }
    public function get_value($Mr)
    {
        $t_ = $this->storage_handler->get_value(bin2hex($Mr));
        if ($t_) {
            goto fL;
        }
        return false;
        fL:
        $x4 = json_decode(hex2bin($t_), true);
        return json_last_error() === JSON_ERROR_NONE ? $x4 : hex2bin($t_);
    }
    public function remove_key($Mr)
    {
        $t_ = $this->storage_handler->remove_key(bin2hex($Mr));
    }
    public function validate()
    {
        return $this->storage_handler->validate();
    }
    public function dump_all_storage($MP = self::RAW)
    {
        $ob = $this->storage_handler->get_storage();
        $DN = [];
        foreach ($ob as $Mr => $t_) {
            $FG = \hex2bin($Mr);
            if ($FG) {
                goto Ol;
            }
            goto KW;
            Ol:
            $DN[$FG] = $this->get_value($FG);
            KW:
        }
        uI:
        switch ($MP) {
            case self::PRETTY:
                echo "\74\160\162\145\x3e";
                print_r($DN);
                echo "\x3c\x2f\160\162\145\76";
                goto Hg;
            case self::JSON:
                echo \json_encode($DN);
                goto Hg;
            default:
            case self::RAW:
                print_r($DN);
                goto Hg;
        }
        XS:
        Hg:
    }
}
