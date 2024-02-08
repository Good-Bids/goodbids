<?php


namespace MoOauthClient;

class StorageHandler
{
    private $storage;
    public function __construct($OC = '')
    {
        $df = empty($OC) || '' === $OC ? json_encode([]) : sanitize_text_field(wp_unslash($OC));
        $this->storage = json_decode($df, true);
    }
    public function add_replace_entry($cW, $LQ)
    {
        $this->storage[$cW]["\x56"] = $LQ;
        $this->storage[$cW]["\x48"] = md5($LQ);
    }
    public function get_value($cW)
    {
        if (isset($this->storage[$cW])) {
            goto pp;
        }
        return false;
        pp:
        $LQ = $this->storage[$cW];
        if (!(!is_array($LQ) || !isset($LQ["\126"]) || !isset($LQ["\110"]))) {
            goto bF;
        }
        return false;
        bF:
        if (!(md5($LQ["\126"]) !== $LQ["\x48"])) {
            goto Ee;
        }
        return false;
        Ee:
        return $LQ["\126"];
    }
    public function remove_key($cW)
    {
        if (!isset($this->storage[$cW])) {
            goto w1;
        }
        unset($this->storage[$cW]);
        w1:
    }
    public function stringify()
    {
        global $Yh;
        $Nb = $this->storage;
        $Nb[\bin2hex("\x75\151\144")]["\126"] = bin2hex(MO_UID);
        $Nb[\bin2hex("\165\x69\x64")]["\x48"] = md5($Nb[\bin2hex("\165\x69\x64")]["\x56"]);
        return $Yh->base64url_encode(wp_json_encode($Nb));
    }
    public function get_storage()
    {
        return $this->storage;
    }
}
