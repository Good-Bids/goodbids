<?php


namespace MoOauthClient;

class StorageHandler
{
    private $storage;
    public function __construct($Yw = '')
    {
        $Rp = empty($Yw) || '' === $Yw ? json_encode([]) : sanitize_text_field(wp_unslash($Yw));
        $this->storage = json_decode($Rp, true);
    }
    public function add_replace_entry($Mr, $t_)
    {
        $this->storage[$Mr]["\x56"] = $t_;
        $this->storage[$Mr]["\x48"] = md5($t_);
    }
    public function get_value($Mr)
    {
        if (isset($this->storage[$Mr])) {
            goto MY;
        }
        return false;
        MY:
        $t_ = $this->storage[$Mr];
        if (!(!is_array($t_) || !isset($t_["\126"]) || !isset($t_["\110"]))) {
            goto Vd;
        }
        return false;
        Vd:
        if (!(md5($t_["\126"]) !== $t_["\x48"])) {
            goto JL;
        }
        return false;
        JL:
        return $t_["\126"];
    }
    public function remove_key($Mr)
    {
        if (!isset($this->storage[$Mr])) {
            goto Bt;
        }
        unset($this->storage[$Mr]);
        Bt:
    }
    public function stringify()
    {
        global $Uj;
        $ob = $this->storage;
        $ob[\bin2hex("\x75\151\x64")]["\126"] = bin2hex(MO_UID);
        $ob[\bin2hex("\165\151\144")]["\x48"] = md5($ob[\bin2hex("\165\x69\144")]["\126"]);
        return $Uj->base64url_encode(wp_json_encode($ob));
    }
    public function get_storage()
    {
        return $this->storage;
    }
}
