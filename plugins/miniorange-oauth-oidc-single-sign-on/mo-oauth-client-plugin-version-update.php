<?php


require_once dirname(__FILE__) . "\x2f\151\x6e\x63\154\x75\x64\145\x73\x2f\x6c\151\x62\x2f\155\x6f\x2d\x6f\x70\164\x69\x6f\156\x73\55\145\x6e\165\155\x2e\160\x68\160";
add_action("\141\x64\x6d\151\156\x5f\x69\x6e\151\164", "\x6d\157\x5f\x6f\x61\x75\164\150\x5f\143\x6c\x69\x65\x6e\164\137\x75\160\144\141\x74\x65");
use MoOauthClient\Standard\SignInSettingsSettings;
class mo_oauth_client_update_framework
{
    private $current_version;
    private $update_path;
    private $plugin_slug;
    private $slug;
    private $plugin_file;
    private $new_version_changelog;
    public function __construct($FL, $wI = "\x2f", $Tn = "\x2f")
    {
        $this->current_version = $FL;
        $this->update_path = $wI;
        $this->plugin_slug = $Tn;
        list($k8, $tv) = explode("\57", $Tn);
        $this->slug = $k8;
        $this->plugin_file = $tv;
        add_filter("\x70\162\145\137\163\145\x74\137\x73\151\164\x65\x5f\164\162\141\x6e\163\x69\145\x6e\164\137\x75\x70\x64\x61\x74\145\137\x70\x6c\165\147\151\156\x73", array(&$this, "\155\x6f\137\x6f\x61\165\164\x68\x5f\143\x68\145\143\153\x5f\165\x70\144\141\164\x65"));
        add_filter("\x70\x6c\x75\x67\x69\x6e\163\x5f\141\x70\x69", array(&$this, "\x6d\157\137\157\141\x75\x74\x68\137\143\154\x69\145\x6e\x74\x5f\143\x68\x65\143\153\137\151\156\x66\x6f"), 10, 3);
    }
    public function mo_oauth_check_update($qS)
    {
        global $Yh;
        if (!empty($qS->checked)) {
            goto AOe;
        }
        return $qS;
        AOe:
        $Ty = $this->getRemote();
        if (!isset($Ty["\163\164\141\x74\165\163"])) {
            goto aAb;
        }
        if ($Ty["\163\164\x61\x74\x75\x73"] == "\x53\x55\103\x43\x45\x53\123") {
            goto ADF;
        }
        if (!($Ty["\x73\x74\x61\x74\x75\x73"] == "\104\105\116\111\105\104")) {
            goto vMu;
        }
        $YL = true;
        $Yh->mo_oauth_client_update_option("\155\x6f\137\x6f\141\x75\x74\150\137\x73\154\x65", $YL);
        if (!version_compare($this->current_version, $Ty["\x6e\x65\x77\x56\x65\162\x73\151\x6f\x6e"], "\74")) {
            goto n9J;
        }
        $JD = new stdClass();
        $JD->slug = $this->slug;
        $JD->new_version = $Ty["\156\145\167\126\x65\x72\163\151\157\x6e"];
        $JD->url = "\150\x74\164\160\163\72\x2f\57\155\151\156\x69\157\162\x61\156\147\145\56\x63\157\155";
        $JD->plugin = $this->plugin_slug;
        $JD->tested = $Ty["\143\x6d\x73\103\x6f\155\x70\x61\164\x69\142\x69\x6c\151\x74\171\x56\x65\x72\163\151\157\x6e"];
        $JD->icons = array("\61\170" => $Ty["\x69\x63\157\x6e"]);
        $JD->status_code = $Ty["\x73\x74\141\164\x75\x73"];
        $JD->license_information = $Ty["\154\x69\x63\x65\x6e\163\x65\x49\x6e\146\x6f\x72\155\x61\164\x69\x6f\x6e"];
        $Yh->mo_oauth_client_update_option("\x6d\x6f\137\x6f\x61\x75\164\x68\x5f\x6c\151\x63\x65\x6e\163\145\137\145\x78\x70\151\x72\171\137\144\x61\164\145", $Ty["\x6c\x69\143\145\x6e\145\x45\x78\160\x69\162\171\x44\141\x74\x65"]);
        $qS->response[$this->plugin_slug] = $JD;
        $YL = true;
        $Yh->mo_oauth_client_update_option("\155\x6f\x5f\157\x61\165\x74\x68\x5f\x73\x6c\145", $YL);
        set_transient("\165\x70\144\x61\x74\x65\x5f\x70\x6c\x75\x67\x69\156\163", $qS);
        return $qS;
        n9J:
        vMu:
        goto FDy;
        ADF:
        $YL = false;
        $Yh->mo_oauth_client_update_option("\x6d\x6f\137\x6f\x61\x75\x74\x68\137\163\x6c\145", $YL);
        if (!version_compare($this->current_version, $Ty["\156\145\167\126\145\162\163\x69\x6f\156"], "\x3c")) {
            goto eac;
        }
        ini_set("\x6d\141\x78\137\x65\x78\145\x63\x75\x74\151\x6f\x6e\x5f\164\151\x6d\145", 600);
        ini_set("\155\145\155\157\x72\x79\x5f\154\x69\155\151\164", "\61\60\x32\x34\x4d");
        $m3 = plugin_dir_path(__FILE__);
        $m3 = rtrim($m3, "\x2f");
        $m3 = rtrim($m3, "\x5c");
        $OO = $m3 . "\x2d\x62\x61\x63\x6b\x75\160\x2d" . $this->current_version . "\x2e\172\x69\x70";
        $this->mo_oauth_client_create_backup_dir();
        $Qj = $this->getAuthToken();
        $D3 = round(microtime(true) * 1000);
        $D3 = number_format($D3, 0, '', '');
        $JD = new stdClass();
        $JD->slug = $this->slug;
        $JD->new_version = $Ty["\x6e\x65\167\x56\145\162\x73\151\x6f\x6e"];
        $JD->url = "\150\x74\164\x70\x73\72\x2f\x2f\x6d\x69\x6e\151\157\162\x61\156\147\x65\56\x63\157\155";
        $JD->plugin = $this->plugin_slug;
        $JD->package = mo_oauth_client_options_plugin_constants::HOSTNAME . "\x2f\x6d\157\141\x73\57\160\x6c\x75\x67\x69\156\x2f\144\157\x77\156\x6c\x6f\x61\144\55\x75\x70\x64\x61\x74\x65\x3f\x70\x6c\x75\x67\151\156\x53\x6c\165\147\75" . $this->plugin_slug . "\x26\154\151\143\x65\156\163\x65\x50\x6c\x61\x6e\x4e\x61\155\x65\x3d" . mo_oauth_client_options_plugin_constants::LICENSE_PLAN_NAME . "\x26\143\x75\x73\x74\157\155\145\162\x49\x64\x3d" . $Yh->mo_oauth_client_get_option("\155\157\x5f\157\141\165\164\150\137\x61\144\155\x69\156\x5f\x63\x75\163\x74\x6f\155\x65\x72\x5f\x6b\x65\171") . "\46\154\x69\x63\145\x6e\163\x65\x54\x79\160\145\75" . mo_oauth_client_options_plugin_constants::LICENSE_TYPE . "\46\141\165\164\150\124\157\x6b\145\x6e\75" . $Qj . "\x26\157\x74\x70\x54\157\x6b\x65\x6e\75" . $D3;
        $JD->tested = $Ty["\x63\x6d\163\103\x6f\x6d\x70\x61\x74\151\142\151\x6c\151\164\171\x56\x65\x72\x73\151\157\x6e"];
        $JD->icons = array("\x31\x78" => $Ty["\x69\143\157\156"]);
        $JD->new_version_changelog = $Ty["\143\150\x61\156\x67\x65\x6c\157\x67"];
        $JD->status_code = $Ty["\163\x74\141\164\x75\x73"];
        $Yh->mo_oauth_client_update_option("\155\x6f\137\157\x61\165\x74\150\137\154\151\x63\x65\x6e\x73\x65\x5f\145\170\x70\151\x72\171\137\144\x61\x74\145", $Ty["\x6c\151\x63\x65\x6e\x65\105\x78\x70\x69\x72\x79\x44\x61\164\145"]);
        $qS->response[$this->plugin_slug] = $JD;
        set_transient("\165\160\x64\x61\164\145\x5f\x70\154\x75\147\151\x6e\163", $qS);
        return $qS;
        eac:
        FDy:
        aAb:
        return $qS;
    }
    public function mo_oauth_client_check_info($JD, $RB, $E7)
    {
        global $Yh;
        if (!(($RB == "\161\165\145\162\x79\137\160\154\x75\x67\151\x6e\163" || $RB == "\160\154\165\147\x69\x6e\137\151\x6e\x66\157\x72\x6d\141\x74\151\157\156") && isset($E7->slug) && ($E7->slug === $this->slug || $E7->slug === $this->plugin_file))) {
            goto dwx;
        }
        $bK = $this->getRemote();
        remove_filter("\x70\x6c\165\x67\151\x6e\163\x5f\141\x70\x69", array($this, "\x6d\x6f\137\x6f\141\x75\x74\x68\x5f\x63\x6c\151\145\156\164\x5f\x63\150\145\143\153\137\x69\156\146\x6f"));
        $G_ = plugins_api("\x70\x6c\165\x67\151\156\137\x69\156\146\157\162\x6d\141\164\x69\x6f\156", array("\163\154\165\147" => "\x6d\151\156\x69\x6f\x72\141\156\147\145\55\x6c\157\x67\x69\156\55\x77\151\164\x68\55\145\x76\145\x2d\x6f\156\x6c\151\156\x65\55\147\x6f\x6f\x67\154\145\55\146\x61\143\145\142\157\157\x6b", "\146\x69\145\154\144\x73" => array("\141\x63\164\x69\166\145\x5f\x69\156\x73\x74\x61\154\x6c\163" => true, "\x6e\165\x6d\x5f\162\x61\164\x69\156\147\163" => true, "\x72\x61\x74\151\x6e\x67" => true, "\162\141\x74\x69\x6e\147\163" => true, "\x72\x65\166\151\145\167\x73" => true)));
        $TI = false;
        $aL = false;
        $ko = false;
        $bo = false;
        $HH = '';
        $wn = '';
        if (is_wp_error($G_)) {
            goto xD8;
        }
        $TI = $G_->active_installs;
        $aL = $G_->rating;
        $ko = $G_->ratings;
        $bo = $G_->num_ratings;
        $HH = $G_->sections["\x64\145\x73\143\x72\x69\160\x74\151\157\x6e"];
        $wn = $G_->sections["\162\145\x76\151\145\x77\163"];
        xD8:
        add_filter("\160\154\165\x67\x69\156\163\137\141\160\x69", array($this, "\155\x6f\137\157\x61\x75\164\150\x5f\143\154\x69\x65\156\164\x5f\x63\x68\x65\143\153\x5f\x69\156\x66\157"), 10, 3);
        if ($bK["\163\x74\x61\x74\165\x73"] == "\123\125\103\103\105\x53\x53") {
            goto BfD;
        }
        if (!($bK["\x73\x74\141\x74\x75\x73"] == "\x44\105\116\111\105\x44")) {
            goto dFN;
        }
        if (!version_compare($this->current_version, $bK["\x6e\145\x77\x56\x65\x72\x73\x69\157\156"], "\x3c")) {
            goto rEZ;
        }
        $Xh = new stdClass();
        $Xh->slug = $this->slug;
        $Xh->plugin = $this->plugin_slug;
        $Xh->name = $bK["\160\154\165\147\151\156\x4e\141\155\x65"];
        $Xh->version = $bK["\156\145\167\x56\145\x72\x73\x69\x6f\x6e"];
        $Xh->new_version = $bK["\156\x65\167\126\145\162\163\151\x6f\156"];
        $Xh->tested = $bK["\143\x6d\163\103\x6f\x6d\x70\141\x74\151\142\x69\154\x69\164\x79\x56\145\162\163\x69\x6f\156"];
        $Xh->requires = $bK["\x63\x6d\163\115\x69\x6e\126\x65\162\163\x69\157\x6e"];
        $Xh->requires_php = $bK["\x70\x68\160\x4d\x69\x6e\x56\145\162\163\x69\157\156"];
        $Xh->compatibility = array($bK["\143\155\x73\103\x6f\x6d\160\x61\x74\x69\x62\151\154\151\x74\171\126\x65\162\x73\x69\x6f\x6e"]);
        $Xh->url = $bK["\143\155\x73\x50\154\x75\x67\151\156\125\x72\154"];
        $Xh->author = $bK["\160\x6c\x75\x67\151\156\x41\x75\164\150\x6f\162"];
        $Xh->author_profile = $bK["\160\x6c\165\x67\151\156\x41\165\164\x68\157\x72\x50\x72\x6f\x66\x69\x6c\145"];
        $Xh->last_updated = $bK["\x6c\x61\x73\164\125\160\144\141\164\x65\144"];
        $Xh->banners = array("\154\x6f\167" => $bK["\142\141\156\x6e\145\x72"]);
        $Xh->icons = array("\61\x78" => $bK["\151\x63\157\156"]);
        $Xh->sections = array("\143\150\x61\156\147\145\x6c\157\147" => $bK["\x63\x68\x61\156\147\145\x6c\157\x67"], "\x6c\x69\143\x65\x6e\x73\x65\x5f\151\156\x66\x6f\x72\155\x61\x74\151\x6f\x6e" => _x($bK["\154\x69\x63\x65\156\163\145\x49\156\x66\157\x72\x6d\141\164\151\157\156"], "\x50\x6c\165\147\x69\156\x20\x69\x6e\x73\x74\141\x6c\154\x65\x72\40\163\x65\143\164\x69\x6f\156\40\x74\x69\x74\154\145"), "\144\x65\x73\143\x72\151\x70\x74\x69\157\x6e" => $HH, "\x52\x65\166\151\x65\167\163" => $wn);
        $Xh->external = '';
        $Xh->homepage = $bK["\x68\x6f\155\145\x70\x61\147\145"];
        $Xh->reviews = true;
        $Xh->active_installs = $TI;
        $Xh->rating = $aL;
        $Xh->ratings = $ko;
        $Xh->num_ratings = $bo;
        $Yh->mo_oauth_client_update_option("\155\157\137\157\141\165\x74\150\x5f\154\x69\143\145\156\x73\x65\x5f\x65\x78\160\151\162\x79\x5f\x64\x61\x74\x65", $bK["\x6c\151\143\145\156\x65\105\x78\160\x69\162\171\104\141\164\x65"]);
        return $Xh;
        rEZ:
        dFN:
        goto aCn;
        BfD:
        $YL = false;
        $Yh->mo_oauth_client_update_option("\155\x6f\137\x6f\141\165\164\x68\137\163\154\x65", $YL);
        if (!version_compare($this->current_version, $bK["\156\145\167\126\145\162\163\x69\157\x6e"], "\74\x3d")) {
            goto S48;
        }
        $Xh = new stdClass();
        $Xh->slug = $this->slug;
        $Xh->name = $bK["\160\x6c\x75\147\x69\156\116\x61\155\145"];
        $Xh->plugin = $this->plugin_slug;
        $Xh->version = $bK["\x6e\x65\x77\126\x65\x72\x73\151\x6f\x6e"];
        $Xh->new_version = $bK["\156\145\x77\126\145\x72\x73\x69\157\156"];
        $Xh->tested = $bK["\x63\x6d\163\103\x6f\155\160\x61\164\x69\142\151\x6c\151\x74\x79\126\145\x72\x73\151\x6f\x6e"];
        $Xh->requires = $bK["\143\155\x73\115\151\156\126\145\x72\163\x69\x6f\x6e"];
        $Xh->requires_php = $bK["\x70\x68\160\115\151\156\126\x65\162\163\x69\x6f\156"];
        $Xh->compatibility = array($bK["\143\x6d\x73\x43\x6f\155\x70\x61\164\151\142\x69\x6c\151\164\171\x56\x65\x72\163\x69\x6f\156"]);
        $Xh->url = $bK["\x63\x6d\163\x50\x6c\165\147\x69\156\x55\x72\x6c"];
        $Xh->author = $bK["\160\x6c\165\x67\x69\x6e\101\165\x74\150\157\x72"];
        $Xh->author_profile = $bK["\x70\154\x75\147\x69\x6e\101\x75\x74\150\x6f\x72\x50\x72\x6f\146\151\x6c\145"];
        $Xh->last_updated = $bK["\154\141\163\164\x55\x70\x64\141\164\x65\144"];
        $Xh->banners = array("\154\x6f\x77" => $bK["\x62\141\156\156\x65\162"]);
        $Xh->icons = array("\61\170" => $bK["\x69\143\157\156"]);
        $Xh->sections = array("\x63\150\x61\x6e\x67\x65\x6c\x6f\147" => $bK["\143\x68\141\x6e\147\x65\154\x6f\x67"], "\154\x69\x63\x65\x6e\163\x65\137\151\156\146\157\x72\155\141\x74\x69\157\x6e" => _x($bK["\x6c\x69\x63\x65\x6e\x73\145\111\x6e\x66\157\x72\155\x61\164\151\x6f\x6e"], "\120\x6c\165\147\x69\x6e\40\x69\x6e\x73\164\x61\154\x6c\145\162\40\163\145\143\164\151\x6f\156\40\164\151\164\154\x65"), "\144\x65\163\143\162\151\x70\x74\151\x6f\156" => $HH, "\x52\x65\x76\151\x65\x77\163" => $wn);
        $Qj = $this->getAuthToken();
        $D3 = round(microtime(true) * 1000);
        $D3 = number_format($D3, 0, '', '');
        $Xh->download_link = mo_oauth_client_options_plugin_constants::HOSTNAME . "\x2f\155\x6f\141\x73\x2f\x70\x6c\165\x67\x69\156\x2f\x64\157\167\156\x6c\x6f\141\x64\x2d\x75\x70\144\141\164\145\x3f\160\154\165\147\151\156\123\x6c\x75\x67\75" . $this->plugin_slug . "\46\154\x69\x63\x65\x6e\x73\x65\x50\154\141\x6e\116\x61\155\x65\75" . mo_oauth_client_options_plugin_constants::LICENSE_PLAN_NAME . "\x26\143\x75\x73\164\x6f\x6d\145\162\111\x64\x3d" . $Yh->mo_oauth_client_get_option("\155\157\137\x6f\x61\165\164\x68\x5f\141\144\x6d\151\x6e\x5f\143\x75\x73\x74\157\x6d\145\162\137\153\145\171") . "\46\154\x69\143\x65\x6e\163\x65\124\x79\160\x65\x3d" . mo_oauth_client_options_plugin_constants::LICENSE_TYPE . "\46\x61\x75\x74\x68\x54\157\153\145\156\x3d" . $Qj . "\x26\157\164\x70\124\x6f\153\145\156\x3d" . $D3;
        $Xh->package = $Xh->download_link;
        $Xh->external = '';
        $Xh->homepage = $bK["\150\x6f\x6d\145\x70\141\x67\x65"];
        $Xh->reviews = true;
        $Xh->active_installs = $TI;
        $Xh->rating = $aL;
        $Xh->ratings = $ko;
        $Xh->num_ratings = $bo;
        $Yh->mo_oauth_client_update_option("\x6d\157\137\157\141\165\164\150\137\x6c\151\143\x65\x6e\163\145\x5f\x65\x78\160\x69\162\x79\137\x64\141\x74\145", $bK["\x6c\x69\143\145\156\145\x45\x78\x70\151\x72\x79\x44\x61\164\x65"]);
        return $Xh;
        S48:
        aCn:
        dwx:
        return $JD;
    }
    private function getRemote()
    {
        global $Yh;
        $BR = $Yh->mo_oauth_client_get_option("\155\157\137\x6f\x61\165\x74\150\x5f\x61\144\155\x69\156\137\143\165\163\x74\157\x6d\145\x72\x5f\153\145\x79");
        $oS = $Yh->mo_oauth_client_get_option("\x6d\157\137\x6f\141\x75\x74\150\x5f\141\x64\x6d\151\x6e\x5f\141\160\151\137\153\145\171");
        $D3 = round(microtime(true) * 1000);
        $eq = $BR . number_format($D3, 0, '', '') . $oS;
        $Qj = hash("\x73\x68\141\65\61\62", $eq);
        $D3 = number_format($D3, 0, '', '');
        $vM = array("\x70\x6c\165\147\151\156\x53\x6c\165\x67" => $this->plugin_slug, "\154\151\143\145\156\x73\x65\120\x6c\141\x6e\116\x61\x6d\x65" => mo_oauth_client_options_plugin_constants::LICENSE_PLAN_NAME, "\143\x75\163\x74\157\155\145\162\111\x64" => $BR, "\154\151\x63\x65\x6e\163\x65\124\x79\160\x65" => mo_oauth_client_options_plugin_constants::LICENSE_TYPE);
        $Zn = array("\x68\145\141\144\x65\162\x73" => array("\x43\x6f\156\x74\145\x6e\x74\x2d\124\171\x70\145" => "\141\x70\160\154\151\143\x61\x74\x69\157\x6e\57\x6a\163\157\156\x3b\40\143\x68\141\x72\163\x65\x74\75\165\x74\x66\x2d\x38", "\103\165\163\164\x6f\x6d\145\x72\55\113\145\x79" => $BR, "\124\151\155\x65\163\x74\141\155\x70" => $D3, "\101\165\x74\150\157\x72\151\172\x61\x74\151\157\156" => $Qj), "\142\x6f\x64\171" => json_encode($vM), "\155\x65\x74\x68\x6f\144" => "\120\117\x53\124", "\x64\141\164\141\137\x66\157\162\155\141\164" => "\142\157\144\171", "\163\x73\x6c\x76\x65\162\151\x66\171" => false);
        $uh = wp_remote_post($this->update_path, $Zn);
        if (!(!is_wp_error($uh) || wp_remote_retrieve_response_code($uh) === 200)) {
            goto Uax;
        }
        $Ca = json_decode($uh["\x62\x6f\x64\x79"], true);
        return $Ca;
        Uax:
        return false;
    }
    private function getAuthToken()
    {
        global $Yh;
        $BR = $Yh->mo_oauth_client_get_option("\x6d\x6f\x5f\x6f\x61\x75\164\150\x5f\x61\144\x6d\x69\x6e\x5f\x63\x75\x73\164\x6f\x6d\145\x72\x5f\x6b\145\171");
        $oS = $Yh->mo_oauth_client_get_option("\155\x6f\x5f\157\141\x75\x74\150\x5f\141\x64\x6d\151\x6e\x5f\x61\x70\x69\x5f\153\x65\x79");
        $D3 = round(microtime(true) * 1000);
        $eq = $BR . number_format($D3, 0, '', '') . $oS;
        $Qj = hash("\x73\x68\141\65\61\x32", $eq);
        return $Qj;
    }
    function zipData($Js, $PJ)
    {
        if (!(extension_loaded("\172\151\x70") && file_exists($Js) && count(glob($Js . DIRECTORY_SEPARATOR . "\x2a")) !== 0)) {
            goto YR8;
        }
        $Bh = new ZipArchive();
        if (!$Bh->open($PJ, ZIPARCHIVE::CREATE)) {
            goto wBx;
        }
        $Js = realpath($Js);
        if (is_dir($Js) === true) {
            goto S_c;
        }
        if (!is_file($Js)) {
            goto det;
        }
        $Bh->addFromString(basename($Js), file_get_contents($Js));
        det:
        goto GnS;
        S_c:
        $Pw = new RecursiveDirectoryIterator($Js);
        $Pw->setFlags(RecursiveDirectoryIterator::SKIP_DOTS);
        $Ol = new RecursiveIteratorIterator($Pw, RecursiveIteratorIterator::SELF_FIRST);
        foreach ($Ol as $BB) {
            $BB = realpath($BB);
            if (is_dir($BB) === true) {
                goto ogk;
            }
            if (!(is_file($BB) === true)) {
                goto G2d;
            }
            $Bh->addFromString(str_replace($Js . DIRECTORY_SEPARATOR, '', $BB), file_get_contents($BB));
            G2d:
            goto KRM;
            ogk:
            $Bh->addEmptyDir(str_replace($Js . DIRECTORY_SEPARATOR, '', $BB . DIRECTORY_SEPARATOR));
            KRM:
            KP0:
        }
        fNO:
        GnS:
        wBx:
        return $Bh->close();
        YR8:
        return false;
    }
    function mo_oauth_client_plugin_update_message($UX, $uh)
    {
        if (array_key_exists("\163\x74\x61\164\x75\163\x5f\x63\x6f\144\x65", $UX)) {
            goto bAt;
        }
        return;
        bAt:
        if ($UX["\x73\164\141\x74\x75\163\x5f\x63\x6f\x64\145"] == "\123\125\x43\103\x45\x53\123") {
            goto r33;
        }
        if (!($UX["\x73\x74\141\x74\x75\163\137\143\157\144\x65"] == "\x44\x45\x4e\x49\105\104")) {
            goto V8H;
        }
        echo sprintf(__($UX["\154\x69\x63\145\x6e\163\x65\137\151\156\x66\x6f\162\x6d\141\164\x69\x6f\156"]));
        V8H:
        goto Cbv;
        r33:
        $YC = wp_upload_dir();
        $WB = $YC["\x62\x61\163\x65\x64\x69\x72"];
        $YC = rtrim($WB, "\x2f");
        $m3 = str_replace("\x2f", "\x5c", $YC) . "\x5c\x62\x61\x63\153\x75\x70";
        $OO = "\155\x69\156\x69\157\162\x61\156\x67\145\55\157\x61\x75\164\x68\x2d\x6f\x69\x64\x63\55\x73\x69\x6e\147\x6c\x65\55\163\x69\x67\156\x2d\157\156\x2d\x62\141\143\153\165\160\55" . $this->current_version;
        $xK = explode("\x3c\57\x75\x6c\76", $UX["\x6e\x65\x77\137\x76\x65\162\x73\151\157\156\137\x63\150\141\156\147\145\154\157\147"]);
        $qO = $xK[0];
        $yw = $qO . "\x3c\x2f\x75\x6c\x3e";
        echo "\x3c\144\x69\x76\x3e\74\x62\x3e" . __("\x3c\142\162\x20\57\x3e\x41\x6e\40\141\165\x74\x6f\155\x61\164\151\143\40\x62\x61\x63\x6b\x75\160\40\x6f\x66\x20\x63\165\x72\x72\145\156\164\x20\166\x65\162\163\151\157\x6e\40" . $this->current_version . "\x20\150\x61\163\x20\x62\145\145\156\x20\x63\162\145\x61\x74\145\x64\40\141\x74\x20\164\x68\x65\x20\154\x6f\x63\141\x74\151\x6f\x6e\x20" . $m3 . "\40\167\151\164\150\x20\x74\x68\145\x20\156\141\x6d\x65\x20\74\163\160\x61\156\x20\x73\x74\x79\x6c\x65\x3d\x22\143\157\x6c\x6f\162\72\43\60\x30\67\x33\141\x61\x3b\x22\76" . $OO . "\74\57\163\x70\141\156\x3e\56\x20\111\x6e\x20\x63\x61\x73\145\x2c\40\x73\x6f\x6d\x65\164\x68\151\x6e\x67\40\x62\162\x65\x61\x6b\163\x20\x64\x75\x72\151\156\147\40\x74\150\145\40\x75\160\144\x61\164\x65\54\40\171\157\165\40\143\x61\156\40\x72\145\166\x65\162\164\40\164\x6f\x20\171\157\x75\x72\40\143\165\162\x72\145\156\x74\40\166\145\162\x73\x69\x6f\x6e\40\x62\x79\40\x72\x65\x70\154\141\x63\151\156\147\40\x74\x68\x65\x20\x62\141\143\153\x75\x70\40\x75\x73\151\156\147\40\x46\124\120\x20\141\x63\143\x65\x73\x73\56", "\x6d\x69\156\x69\157\162\x61\156\147\x65\55\157\141\165\x74\150\55\x6f\x69\x64\143\x2d\163\151\x6e\147\154\145\x2d\x73\151\x67\156\55\157\x6e") . "\74\57\x62\76\74\x2f\x64\x69\166\x3e\74\x64\x69\166\40\x73\164\171\x6c\x65\x3d\42\143\157\x6c\x6f\x72\x3a\x20\x23\x66\60\x30\73\42\76\x3c\142\x3e" . __("\x3c\x62\162\x20\x2f\76\124\x61\x6b\x65\40\141\40\155\151\156\165\164\145\40\x74\157\40\x63\150\145\x63\x6b\x20\x74\150\x65\40\143\x68\141\156\147\x65\x6c\157\147\40\x6f\146\40\154\x61\x74\145\163\164\40\166\145\162\x73\151\157\x6e\x20\x6f\x66\40\164\x68\x65\x20\x70\x6c\165\147\151\x6e\x2e\x20\110\145\162\x65\47\163\x20\x77\150\171\x20\171\x6f\165\x20\x6e\x65\x65\144\40\164\x6f\40\x75\160\x64\x61\x74\x65\72", "\x6d\151\156\x69\157\162\141\156\147\145\x2d\157\141\x75\164\x68\x2d\x6f\x69\144\143\x2d\163\151\156\x67\154\x65\x2d\x73\x69\x67\x6e\x2d\157\x6e") . "\74\57\142\76\74\57\x64\x69\x76\x3e";
        echo "\x3c\144\151\x76\x20\163\164\171\x6c\x65\x3d\42\146\157\x6e\x74\x2d\167\145\x69\x67\x68\164\x3a\x20\x6e\157\x72\155\141\154\73\x22\76" . $yw . "\74\57\x64\151\x76\76\74\142\x3e\116\157\164\x65\x3a\x3c\x2f\x62\x3e\x20\x50\x6c\x65\141\163\145\x20\143\x6c\x69\x63\153\x20\157\x6e\x20\x3c\142\76\126\x69\x65\167\x20\126\145\162\x73\151\x6f\x6e\40\144\x65\164\141\151\x6c\163\x3c\x2f\x62\x3e\40\154\x69\x6e\153\x20\x74\x6f\40\x67\x65\x74\40\143\157\155\x70\x6c\x65\164\145\x20\x63\x68\x61\156\147\x65\x6c\157\x67\x20\x61\156\x64\40\154\151\x63\x65\x6e\x73\x65\40\x69\156\146\157\162\x6d\x61\164\151\x6f\x6e\x2e\x20\103\x6c\151\x63\x6b\x20\157\x6e\x20\74\142\76\x55\x70\x64\x61\x74\145\40\x4e\x6f\x77\x3c\x2f\x62\76\40\x6c\151\x6e\x6b\x20\164\x6f\x20\x75\x70\x64\x61\164\x65\40\164\x68\x65\x20\160\x6c\165\x67\x69\x6e\x20\164\x6f\40\x6c\x61\x74\145\163\x74\x20\166\145\162\x73\x69\x6f\x6e\x2e";
        Cbv:
    }
    public function mo_oauth_license_domain_notice()
    {
        echo "\x3c\x64\x69\x76\x20\x69\x64\75\x22\155\x65\163\x73\x61\x67\145\42\x20\x73\164\x79\x6c\x65\75\x22\142\141\143\153\147\162\157\165\156\x64\x3a\43\146\x66\145\70\145\x38\x3b\40\x62\x6f\162\144\145\162\55\x72\141\144\x69\165\x73\72\64\x70\x78\73\40\146\x6f\x6e\x74\55\x73\151\172\x65\72\61\x33\x70\170\x3b\x20\142\x6f\162\144\145\162\72\x20\61\160\170\x20\163\157\x6c\151\144\x20\x72\145\x64\x22\40\x63\154\x61\x73\x73\x3d\42\x6e\157\164\x69\x63\145\x20\x6e\157\164\151\143\145\40\x6e\157\164\x69\x63\145\x2d\167\x61\162\156\151\x6e\147\42\x3e\xd\12\11\x9\11\x9\74\151\155\x67\x20\x73\162\143\x3d\x22" . MOC_URL . "\162\145\x73\x6f\165\162\x63\x65\163\57\x69\155\141\x67\x65\163\57\167\x61\162\156\151\x6e\x67\x2e\x70\x6e\x67" . "\42\x20\143\x6c\141\x73\x73\75\x22\141\154\x69\x67\x6e\x6c\145\146\x74\x22\40\150\x65\151\x67\150\x74\75\x22\70\67\42\40\x77\151\144\164\x68\x3d\x22\x36\x36\42\40\x61\x6c\164\x3d\x22\x6d\x69\x6e\151\117\162\x61\156\x67\145\40\x6c\x6f\x67\157\42\40\163\x74\x79\154\x65\75\42\x6d\x61\162\x67\151\x6e\72\62\x70\x78\40\60\160\x78\x20\x30\160\170\x20\x30\73\40\150\145\x69\x67\150\164\72\65\x30\x70\x78\73\40\167\151\144\x74\150\72\40\65\x30\x70\x78\x3b\42\76\x3c\57\x69\x6d\x67\76\15\xa\11\x9\x9\x9\x3c\150\x33\40\163\164\x79\x6c\x65\x3d\x22\x66\x6f\156\x74\55\x73\x69\x7a\145\x3a\x31\x2e\62\162\x65\x6d\73\40\x6d\x61\162\147\151\156\72\40\61\x36\56\x32\x70\x78\40\x30\x20\x31\66\x2e\62\160\x78\42\x3e\x6d\x69\x6e\x69\117\162\x61\156\147\x65\40\x4f\101\165\164\x68\40\57\40\117\x70\145\x6e\111\104\40\123\151\156\x67\x6c\145\x20\123\x69\147\x6e\x2d\x4f\x6e\40\x6c\151\143\145\x6e\x73\x65\40\156\x6f\164\x20\146\x6f\165\156\x64\x20\146\157\162\x20\164\x68\151\x73\x20\144\x6f\x6d\x61\x69\156\x2e\74\57\x68\63\x3e\15\12\x9\x9\x9\x9\x3c\160\x20\163\x74\171\154\145\x20\75\40\x22\146\x6f\x6e\x74\55\x73\x69\x7a\x65\x3a\61\x34\x70\x78\73\x20\146\x6f\x6e\x74\x2d\167\145\151\147\150\x74\72\40\x35\60\x30\x3b\42\76\15\12\x9\x9\11\x9\x9\131\x6f\165\162\x20\154\151\143\x65\x6e\x73\x65\40\x6b\145\171\x20\x69\163\40\151\x6e\x76\x61\x6c\151\144\40\x6f\x72\40\x65\x78\x70\x69\162\145\x64\x2e\x20\x41\x73\x20\160\145\x72\40\x6f\x75\x72\40\154\151\x63\x65\156\x73\x69\156\x67\x20\x70\157\154\x69\143\x79\x20\x79\x6f\x75\40\143\141\x6e\x20\157\x6e\154\x79\40\x75\163\x65\x20\x6f\156\145\x20\154\151\143\145\x6e\163\x65\40\141\x74\40\157\156\145\x20\144\157\155\x61\x69\156\x20\x61\164\x20\x61\x20\164\151\155\x65\56\x20\x49\156\40\143\141\163\145\40\171\x6f\165\40\x77\x61\x6e\x74\40\164\x6f\x20\165\163\x65\40\x74\x68\x65\40\160\154\165\x67\x69\156\x20\x6f\x6e\40\x6d\x75\154\164\x69\160\154\145\x20\x64\157\155\x61\x69\x6e\x73\x20\141\164\x20\141\x20\164\x69\155\x65\40\171\x6f\165\x20\167\x6f\x75\x6c\144\40\x6e\145\145\144\40\164\x6f\x20\x67\157\x20\146\x6f\162\40\155\165\x74\x6c\x74\x69\160\154\145\40\154\151\x63\x65\156\163\x65\163\56\x20\x46\x6f\x72\40\155\x6f\x72\x65\40\x69\x6e\146\157\x72\x6d\141\x74\151\157\156\40\160\154\x65\141\163\x65\x20\162\x65\x61\143\x68\x20\157\165\164\40\164\x6f\40\74\x61\x20\x68\x72\145\146\x3d\x22\155\x61\151\x6c\x74\x6f\72\157\x61\x75\x74\x68\x73\165\160\160\x6f\162\164\x40\170\145\x63\x75\x72\x69\x66\x79\56\143\157\x6d\42\76\157\141\165\164\x68\163\x75\160\160\157\162\x74\x40\170\x65\143\165\162\151\x66\x79\x2e\x63\x6f\x6d\x3c\57\x61\76\x2e\74\142\162\76\x3c\142\x72\76\120\154\145\141\163\x65\40\160\165\x72\143\150\141\x73\145\x20\141\x20\156\145\167\x20\x6c\151\x63\145\156\x73\145\40\153\x65\171\40\x6f\162\40\x64\145\x61\143\164\x69\x76\141\x74\x65\x20\x74\x68\x65\x20\145\170\x69\x73\164\151\156\x67\x20\157\156\145\x20\x66\162\x6f\155\40\x61\x6e\x6f\164\150\x65\x72\x20\x73\x69\164\x65\x2e\x3c\x62\x72\x3e\x3c\142\162\76\x20\111\146\x20\x79\157\165\x20\143\x6f\156\164\x69\x6e\165\x65\40\164\157\40\x75\163\x65\40\x74\x68\x65\x20\x70\x6c\165\x67\x69\156\x20\151\x74\40\x77\151\x6c\x6c\40\x72\145\x73\x75\x6c\164\x20\151\x6e\40\x66\x6f\x6c\x6c\x6f\x77\x69\156\147\x20\72\x2d\x20\xd\12\x9\11\11\x9\74\x2f\x70\x3e\xd\12\x9\x9\11\x9\x3c\154\151\40\163\x74\x79\x6c\x65\75\x22\155\x61\162\147\x69\156\x3a\60\x70\170\x20\55\x32\x70\x78\x20\x32\x70\x78\x20\x31\63\x70\170\x22\x3e\15\12\11\11\11\11\x9\74\x62\x3e\x44\x69\x73\x61\142\x6c\x69\156\x67\x20\157\146\x20\x61\x64\x6d\151\156\x20\141\143\x63\145\x73\163\40\x74\x6f\x20\165\160\x64\141\x74\145\40\160\154\165\x67\151\x6e\40\x63\x6f\156\x66\151\147\x75\x72\x61\164\151\x6f\x6e\56\xd\xa\11\x9\11\11\74\57\x6c\x69\76\15\xa\x9\11\11\11\74\154\151\40\x73\164\171\x6c\145\75\42\x6d\141\162\x67\x69\x6e\72\x30\160\170\40\60\160\170\40\x32\160\x78\x20\61\x33\x70\x78\42\x3e\xd\xa\11\11\x9\11\11\104\151\x73\x61\142\154\x69\x6e\147\x20\x6f\146\40\123\x53\x4f\x20\157\x6e\40\x74\150\151\x73\40\x64\157\155\141\x69\156\56\x3c\57\142\x3e\15\12\11\11\x9\11\74\57\x6c\151\76\xd\12\x9\x9\11\11\74\142\x72\x3e\15\12\11\11\11\40\x20\74\x2f\x64\x69\x76\x3e";
    }
    public function mo_oauth_client_dismiss_notice()
    {
        global $Yh;
        if (!empty($_GET["\x6d\x6f\x6f\x61\x75\x74\150\x63\154\x69\x65\156\x74\x2d\144\151\x73\155\x69\x73\163"])) {
            goto a5i;
        }
        return;
        a5i:
        if (wp_verify_nonce($_GET["\155\x6f\157\x61\x75\164\x68\143\x6c\151\145\x6e\164\55\144\151\x73\155\151\163\163"], "\x6f\x61\165\x74\x68\55\143\154\x69\x65\156\164\x2d\x64\x69\163\155\x69\163\x73")) {
            goto sWW;
        }
        return;
        sWW:
        if (!(isset($_GET["\155\157\157\141\x75\164\x68\x63\x6c\151\x65\x6e\x74\x2d\x64\151\163\155\x69\163\x73"]) && wp_verify_nonce($_GET["\x6d\x6f\x6f\x61\165\164\150\143\x6c\x69\145\x6e\x74\x2d\144\x69\x73\155\151\x73\x73"], "\x6f\141\x75\x74\x68\x2d\x63\154\151\145\156\x74\55\144\151\163\x6d\x69\x73\163"))) {
            goto rmm;
        }
        $iQ = new DateTime();
        $iQ->modify("\53\x31\x20\144\141\x79");
        $Yh->mo_oauth_client_update_option("\155\x6f\x2d\157\x61\165\164\x68\x2d\143\x6c\151\x65\x6e\164\55\x70\x6c\165\x67\151\x6e\x2d\164\151\x6d\145\x72", $iQ);
        rmm:
    }
    function mo_oauth_client_create_backup_dir()
    {
        $m3 = plugin_dir_path(__FILE__);
        $m3 = rtrim($m3, "\57");
        $m3 = rtrim($m3, "\134");
        $UX = get_plugin_data(__FILE__);
        $pE = $UX["\124\145\170\x74\x44\157\155\141\151\156"];
        $YC = wp_upload_dir();
        $WB = $YC["\x62\141\163\x65\x64\x69\x72"];
        $YC = rtrim($WB, "\57");
        if (is_writable($YC)) {
            goto jXt;
        }
        return;
        jXt:
        $K6 = $YC . DIRECTORY_SEPARATOR . "\142\141\143\x6b\x75\x70" . DIRECTORY_SEPARATOR . $pE . "\55\142\x61\143\153\x75\160\55" . $this->current_version;
        if (file_exists($K6)) {
            goto FVr;
        }
        mkdir($K6, 0777, true);
        FVr:
        $Js = $m3;
        $PJ = $K6;
        $this->mo_oauth_client_copy_files_to_backup_dir($Js, $PJ);
    }
    function mo_oauth_client_copy_files_to_backup_dir($m3, $K6)
    {
        if (!is_dir($m3)) {
            goto Ke3;
        }
        $wU = scandir($m3);
        Ke3:
        if (!empty($wU)) {
            goto BiV;
        }
        return;
        BiV:
        foreach ($wU as $OY) {
            if (!($OY == "\x2e" || $OY == "\56\x2e")) {
                goto n4o;
            }
            goto B0f;
            n4o:
            $y3 = $m3 . DIRECTORY_SEPARATOR . $OY;
            $WS = $K6 . DIRECTORY_SEPARATOR . $OY;
            if (is_dir($y3)) {
                goto jbW;
            }
            copy($y3, $WS);
            goto WGD;
            jbW:
            if (file_exists($WS)) {
                goto NqP;
            }
            mkdir($WS, 0777, true);
            NqP:
            $this->mo_oauth_client_copy_files_to_backup_dir($y3, $WS);
            WGD:
            B0f:
        }
        Qev:
    }
}
function mo_oauth_client_update()
{
    global $Yh;
    $Wb = $Yh->get_plugin_config()->get_current_config();
    $qD = time();
    if (empty($Wb["\155\157\137\x64\x74\145\x5f\144\141\x74\x61"])) {
        goto htd;
    }
    $qD = strtotime($Yh->mooauthdecrypt($Wb["\155\157\137\144\164\145\137\144\x61\x74\x61"]));
    htd:
    $YB = mo_oauth_client_options_plugin_constants::HOSTNAME;
    $tk = mo_oauth_client_options_plugin_constants::Version;
    $E_ = $YB . "\x2f\x6d\x6f\141\x73\57\141\160\151\57\160\x6c\165\147\x69\156\x2f\155\145\x74\141\x64\x61\x74\x61";
    $Tn = plugin_basename(dirname(__FILE__) . "\57\155\x6f\137\x6f\141\x75\164\150\x5f\x73\145\x74\x74\x69\x6e\147\163\56\x70\150\x70");
    $Fg = new mo_oauth_client_update_framework($tk, $E_, $Tn);
    add_action("\x69\x6e\137\x70\154\165\147\151\156\x5f\165\160\x64\141\164\145\x5f\x6d\x65\163\x73\141\147\145\55{$Tn}", array($Fg, "\155\x6f\x5f\157\141\165\x74\150\137\143\x6c\151\145\156\164\x5f\160\154\x75\x67\x69\x6e\137\x75\x70\x64\141\164\x65\x5f\155\145\x73\x73\141\x67\x65"), 10, 2);
    $VR = new SignInSettingsSettings();
    $Wb = $VR->get_config_option();
    if ($Yh->mo_oauth_is_cld()) {
        goto X2g;
    }
    add_action("\x61\x64\x6d\x69\x6e\x5f\x68\145\141\x64", array($Fg, "\x6d\x6f\x5f\x6f\x61\x75\x74\150\137\x6c\x69\143\x65\x6e\163\x65\x5f\x64\157\x6d\x61\x69\156\x5f\156\157\x74\x69\143\145"));
    X2g:
    add_action("\141\x64\x6d\151\x6e\137\156\x6f\x74\151\143\145\163", array($Fg, "\x6d\x6f\x5f\x6f\x61\165\164\150\x5f\x63\x6c\x69\x65\156\164\x5f\144\151\163\x6d\x69\163\163\137\x6e\x6f\x74\x69\143\x65"), 50);
    if (!$Yh->mo_oauth_client_get_option("\x6d\x6f\x5f\157\x61\165\164\150\137\163\x6c\145")) {
        goto vDp;
    }
    $Yh->mo_oauth_client_update_option("\155\x6f\x5f\x6f\141\x75\x74\x68\x5f\163\x6c\x65\x5f\155\x65\163\163\x61\x67\145", "\131\x6f\165\162\40\117\101\x75\164\x68\x20\57\x20\117\x70\145\156\x49\104\x20\103\x6f\156\x6e\145\143\164\x20\160\x6c\x75\x67\x69\x6e\40\x6c\151\143\145\x6e\x73\145\x20\150\x61\163\40\142\x65\145\156\x20\x65\170\160\151\x72\145\x64\56\x20\x59\157\x75\40\x61\x72\x65\40\x6d\x69\163\x73\151\156\x67\40\x6f\x75\x74\x20\157\156\x20\165\x70\144\141\x74\x65\x73\x20\141\x6e\144\40\x73\x75\x70\160\x6f\162\164\41\x20\x50\x6c\145\x61\163\145\x20\74\x61\x20\150\162\x65\x66\x3d\42" . mo_oauth_client_options_plugin_constants::HOSTNAME . "\57\155\157\141\x73\x2f\x6c\157\147\x69\156\77\x72\x65\144\x69\x72\145\x63\164\125\162\x6c\75" . mo_oauth_client_options_plugin_constants::HOSTNAME . "\x2f\x6d\x6f\x61\x73\x2f\141\144\x6d\x69\156\57\143\165\163\164\x6f\155\x65\162\57\x6c\x69\x63\145\x6e\x73\145\x72\145\x6e\145\x77\x61\x6c\163\x3f\x72\145\156\x65\167\141\x6c\x72\145\161\x75\x65\163\x74\75" . mo_oauth_client_options_plugin_constants::LICENSE_TYPE . "\x20\x22\40\164\x61\162\x67\x65\164\x3d\42\x5f\142\x6c\x61\156\153\x22\76\74\x62\x3e\x43\154\151\x63\x6b\40\110\145\162\145\74\57\142\x3e\74\x2f\141\x3e\x20\x74\x6f\40\x72\145\156\x65\x77\x20\164\x68\145\x20\123\x75\x70\x70\x6f\x72\164\40\x61\x6e\x64\40\115\x61\x69\156\164\x65\x6e\141\x63\x65\40\x70\154\x61\x6e\56");
    vDp:
}
