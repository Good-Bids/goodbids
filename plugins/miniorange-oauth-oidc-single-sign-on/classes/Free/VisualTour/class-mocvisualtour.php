<?php


namespace MoOauthClient\Free;

require_once "\166\x74\x2d\143\x6f\x6e\163\164\163\x2e\160\x68\x70";
class MOCVisualTour
{
    protected $nonce;
    protected $nonce_key;
    protected $tour_ajax_action;
    public function __construct()
    {
        $this->nonce = "\155\157\137\x61\144\155\x69\x6e\137\x61\x63\164\151\x6f\156\163";
        $this->nonce_key = "\163\145\143\165\x72\x69\164\171";
        $this->tour_ajax_action = "\x6d\x69\x6e\x69\157\162\x61\x6e\147\x65\x2d\x74\157\x75\x72\x2d\164\141\x6b\145\156";
        add_action("\x61\x64\x6d\x69\x6e\x5f\x65\x6e\161\x75\x65\x75\145\x5f\x73\143\x72\x69\160\x74\x73", [$this, "\x65\156\x71\x75\145\165\x65\137\166\x69\x73\x75\x61\x6c\137\x74\157\165\162\137\163\x63\162\151\160\x74"]);
        add_action("\x77\160\x5f\141\152\141\x78\x5f{$this->tour_ajax_action}", [$this, "\x75\x70\x64\141\164\145\137\x74\x6f\165\x72\137\164\x61\153\x65\156"]);
        add_action("\x77\x70\137\141\152\x61\x78\x5f\156\x6f\160\x72\151\166\137{$this->tour_ajax_action}", [$this, "\x75\160\x64\x61\164\x65\137\x74\x6f\x75\162\x5f\x74\141\x6b\x65\156"]);
    }
    public function update_tour_taken()
    {
        global $Uj;
        $this->validate_ajax_request();
        $Uj->mo_oauth_client_update_option("\164\x6f\165\162\124\x61\153\145\x6e\x5f" . $_POST["\x70\141\147\145\111\104"], $_POST["\144\x6f\x6e\145\x54\157\165\162"]);
        $Uj->mo_oauth_client_update_option("\155\x6f\x63\x5f\x74\x6f\165\162\124\141\x6b\x65\x6e\x5f\146\x69\162\x73\164", true);
        die;
    }
    private function validate_ajax_request()
    {
        if (check_ajax_referer($this->nonce, $this->nonce_key)) {
            goto EM;
        }
        wp_send_json(["\155\145\163\163\141\x67\145" => "\111\x6e\166\141\154\151\x64\x20\117\x70\145\x72\x61\164\151\157\x6e\x2e\x20\x50\154\145\141\x73\x65\x20\x74\x72\171\40\x61\147\141\151\x6e\x2e", "\x72\145\x73\x75\154\164" => "\145\162\x72\157\x72"]);
        exit;
        EM:
    }
    public function enqueue_visual_tour_script()
    {
        global $Uj;
        wp_register_script("\x74\x6f\165\162\137\x73\x63\162\151\x70\x74", TOUR_RES_JS . "\x76\151\163\x75\141\154\124\157\x75\x72\56\152\x73", ["\152\161\165\x65\x72\x79"]);
        $pr = isset($_GET["\x74\141\142"]) && '' !== $_GET["\164\x61\x62"] ? $_GET["\x74\141\142"] : '';
        wp_localize_script("\164\x6f\x75\x72\137\x73\x63\162\151\160\x74", "\x6d\x6f\124\157\165\x72", array("\x73\x69\x74\x65\125\x52\114" => admin_url("\141\x64\x6d\151\x6e\x2d\141\152\141\170\56\160\x68\160"), "\164\x6e\157\x6e\143\x65" => \wp_create_nonce($this->nonce), "\160\x61\147\x65\x49\x44" => $pr, "\x74\157\165\162\x44\141\164\141" => $this->get_tour_data($pr), "\164\x6f\x75\162\x54\141\x6b\x65\x6e" => $Uj->mo_oauth_client_get_option("\164\x6f\x75\162\124\141\x6b\x65\x6e\137" . $pr), "\x61\152\x61\170\x41\x63\164\151\x6f\x6e" => $this->tour_ajax_action, "\156\x6f\156\143\145\113\x65\171" => \wp_create_nonce($this->nonce_key)));
        wp_enqueue_script("\164\x6f\x75\162\137\x73\143\162\x69\x70\164");
        wp_enqueue_style("\x6d\157\143\137\166\x69\x73\165\141\154\137\x74\157\x75\162\137\163\x74\171\x6c\x65", TOUR_RES_CSS . "\166\151\x73\165\141\154\124\x6f\165\162\x2e\143\163\x73");
    }
    public function tour_template($rw, $oX, $JW, $iX, $n_, $KR, $d7)
    {
        $ur = ["\x73\155\x61\154\154", "\x6d\x65\144\x69\165\x6d", "\142\151\147"];
        return ["\164\x61\162\147\145\164\x45" => $rw, "\x70\157\x69\156\164\x54\x6f\123\x69\x64\x65" => $oX, "\x74\x69\x74\154\145\110\x54\x4d\x4c" => $JW, "\x63\x6f\156\164\145\156\x74\110\x54\x4d\114" => $iX, "\x62\x75\x74\164\157\156\x54\x65\x78\x74" => $n_, "\x69\155\x67" => $KR ? TOUR_RES_IMG . $KR : $KR, "\x63\141\x72\x64\x53\x69\x7a\x65" => $ur[$d7]];
    }
    private function get_tour_data($pr = '')
    {
        global $Uj;
        $zu = [];
        if (boolval($Uj->mo_oauth_client_get_option("\155\157\x63\137\164\x6f\x75\x72\124\141\153\x65\156\x5f\x66\151\x72\x73\x74"))) {
            goto MR;
        }
        $zu = [$this->tour_template('', '', "\x3c\150\61\x3e\x57\x65\x6c\143\x6f\155\x65\41\74\57\x68\x31\76", "\106\x61\163\x74\145\x6e\x20\171\x6f\x75\x72\40\163\145\x61\164\x20\142\x65\154\x74\x73\40\x66\157\162\x20\x61\40\161\x75\x69\x63\153\40\x72\x69\144\145\x2e", "\114\x65\x74\47\163\40\x47\157\41", "\x73\x74\141\x72\x74\124\x6f\165\x72\x2e\x73\166\147", 2)];
        $zu = array_merge($zu, $this->get_tab_pointers());
        MR:
        if (!("\x63\x6f\x6e\x66\x69\x67" === $pr)) {
            goto ro;
        }
        if (!(isset($_GET["\141\143\164\151\x6f\x6e"]) && "\x75\160\144\x61\164\x65" === $_GET["\x61\143\164\x69\157\x6e"])) {
            goto Zt;
        }
        $zu = array_merge($zu, $this->get_updateui_pointers());
        Zt:
        $H5 = $Uj->mo_oauth_client_get_option("\x6d\x6f\137\x6f\x61\x75\164\x68\x5f\x61\160\x70\x73\x5f\154\151\x73\x74") ? $Uj->mo_oauth_client_get_option("\155\157\137\157\141\x75\x74\150\137\141\x70\x70\163\137\154\151\163\164") : false;
        if ($H5 && is_array($H5) && 0 < count($H5) && !isset($_GET["\141\160\160\x49\144"])) {
            goto ot;
        }
        if (!isset($_GET["\141\x70\160\111\x64"])) {
            goto K6;
        }
        goto Ds;
        ot:
        $zu = array_merge($zu, $this->get_applist_pointers());
        goto Ds;
        K6:
        $zu = array_merge($zu, $this->get_defaultapps_pointers());
        Ds:
        if (!(isset($_GET["\x61\x70\x70\x49\144"]) && '' !== $_GET["\141\x70\160\111\144"])) {
            goto Qy;
        }
        $zu = array_merge($zu, $this->get_addapp_pointers());
        Qy:
        ro:
        if (!("\x73\151\x67\156\151\x6e\x73\145\164\x74\151\156\147\x73" === $pr)) {
            goto mI;
        }
        $zu = array_merge($zu, $this->get_signinsettings_pointers());
        mI:
        return $zu;
    }
    private function get_tab_pointers()
    {
        return [$this->tour_template("\x6d\x6f\137\x73\x75\160\x70\157\162\164\137\x6c\x61\171\x6f\x75\x74", "\162\151\147\150\164", "\74\x68\61\x3e\127\x65\40\x61\x72\x65\x20\150\x65\x72\145\41\41\x3c\57\x68\61\x3e", "\x47\x65\x74\x20\151\156\x20\x74\157\165\143\x68\40\167\151\164\x68\x20\165\x73\x20\141\x6e\144\40\167\145\x20\x77\x69\x6c\154\40\150\x65\154\160\40\171\157\x75\40\x73\x65\x74\165\160\x20\x74\150\145\x20\160\154\x75\147\x69\x6e\40\151\156\x20\x6e\x6f\40\164\x69\x6d\145\56", "\116\x65\x78\x74", "\150\145\x6c\x70\x2e\x73\x76\147", 2), $this->tour_template("\x74\141\x62\55\143\x6f\156\146\151\147", "\165\160", "\x3c\150\61\x3e\103\157\156\146\x69\147\165\x72\x61\x74\151\157\156\40\x54\141\x62\x3c\x2f\x68\61\76", "\x59\157\165\x20\143\141\156\40\x63\150\x6f\x6f\163\x65\40\141\156\x64\40\143\157\x6e\x66\151\x67\165\162\145\x20\141\x6e\171\x20\117\x41\x75\164\150\57\117\x70\145\x6e\111\104\40\141\160\160\154\x69\143\141\x74\151\x6f\156\56", "\x4e\x65\170\164", "\x63\150\x6f\157\163\x65\56\163\166\147", 2), $this->tour_template("\x74\x61\x62\55\x63\x75\163\x74\157\x6d\151\172\x61\164\151\157\156", "\165\160", "\74\x68\x31\x3e\x57\151\x64\x67\x65\x74\x20\103\x75\163\164\157\x6d\151\172\x61\164\151\157\156\40\124\141\142\x3c\57\x68\61\76", "\131\157\x75\x20\x63\141\156\40\143\x75\x73\x74\x6f\x6d\151\x7a\145\40\x79\x6f\x75\162\x20\154\157\x67\151\x6e\x20\167\151\144\147\x65\x74\40\157\162\x20\x73\150\x6f\162\164\143\x6f\x64\x65\40\167\x69\x64\147\145\164\40\164\157\40\x79\x6f\x75\162\x20\154\151\153\x69\x6e\147\x20\167\x69\x74\150\40\x43\x53\x53\x20\150\145\162\x65\x21", "\116\145\170\x74", "\x63\150\x6f\157\x73\x65\x2e\x73\x76\x67", 2), $this->tour_template("\x74\x61\142\55\x73\x69\x67\x6e\151\156\x73\145\x74\164\x69\156\147\x73", "\x75\160", "\x3c\x68\x31\x3e\123\151\147\x6e\x20\x49\156\40\123\x65\x74\x74\x69\x6e\x67\163\x3c\x2f\150\61\x3e", "\x59\157\x75\40\143\x61\156\x20\x66\151\x6e\144\40\166\x61\162\x69\x6f\165\x73\40\x53\123\117\40\162\145\154\141\164\x65\144\x20\x63\x6f\156\x66\151\147\165\x72\141\164\x69\x6f\x6e\x73\40\163\165\x63\150\x20\x61\163\40\163\x68\157\162\x74\x63\x6f\x64\145\163\x20\x61\156\x64\x20\125\x73\145\x72\40\122\x65\x67\151\x73\x74\x72\x61\164\151\x6f\156\x20\150\x65\162\145\x21", "\116\145\x78\x74", "\160\x72\157\146\151\154\x65\56\163\x76\x67", 2), $this->tour_template("\x74\141\x62\55\x72\x65\x71\x75\145\163\164\x64\145\155\157", "\x75\160", "\74\150\61\76\x52\145\x71\165\145\163\164\40\x46\157\162\x20\104\x65\x6d\157\x3c\x2f\150\61\x3e", "\101\162\145\x20\171\x6f\165\40\154\157\157\x6b\151\x6e\x67\40\x66\x6f\x72\x20\160\x72\145\x6d\x69\165\x6d\40\146\x65\141\164\x75\162\x65\163\77\x20\116\157\x77\x2c\x20\171\x6f\165\x20\143\x61\156\40\163\x65\156\x64\x20\141\40\162\145\x71\165\145\163\x74\40\x74\x6f\x20\163\145\164\165\x70\x20\x61\40\x64\x65\x6d\x6f\x20\157\x66\40\x74\150\x65\x20\x70\x72\145\x6d\151\165\155\40\x76\145\x72\x73\x69\157\x6e\40\x79\x6f\165\40\x61\162\145\x20\151\x6e\164\x65\x72\x65\x73\x74\x65\144\40\151\x6e\40\141\x6e\x64\40\157\x75\x72\40\x74\145\x61\x6d\x20\167\151\x6c\x6c\40\x73\x65\x74\40\151\164\x20\165\160\x20\x66\157\162\40\171\x6f\165\41", "\x4e\x65\170\x74", "\x70\x72\x65\x76\x69\145\167\56\163\166\x67", 2), $this->tour_template("\x6c\x69\143\145\x6e\x73\151\156\x67\x5f\142\x75\x74\x74\157\156\137\151\144", "\165\x70", "\x3c\x68\x31\x3e\x4c\x69\143\x65\156\x73\151\x6e\147\x20\x50\x6c\141\156\x73\74\57\x68\61\76", "\x59\x6f\165\40\x63\141\x6e\40\x63\150\x65\x63\153\40\141\154\154\40\164\150\145\40\154\x69\x63\x65\156\163\151\x6e\x67\x20\160\154\141\x6e\163\x20\x61\x6e\144\x20\164\x68\x65\40\146\145\x61\164\x75\x72\x65\163\40\141\163\x20\167\145\154\154\40\141\x73\x20\157\x70\x74\151\157\156\163\x20\x74\150\x65\171\x20\x6f\x66\x66\x65\162\54\x20\x68\x65\x72\145\56", "\x4e\145\170\164", "\165\x70\x67\162\x61\x64\x65\x2e\163\x76\x67", 2), $this->tour_template("\x66\x61\161\137\x62\165\164\x74\157\x6e\x5f\151\x64", "\x75\x70", "\x3c\x68\x31\x3e\106\141\143\x69\x6e\x67\x20\x61\x20\160\x72\157\x62\x6c\x65\x6d\77\74\x2f\x68\x31\76", "\x59\157\165\x20\x63\141\x6e\x20\143\150\x65\x63\x6b\x20\x46\x41\x51\163\x2e\x20\x4d\157\163\164\x20\161\x75\x65\x73\x74\151\157\x6e\x73\40\143\x61\x6e\40\x62\145\x20\163\157\x6c\166\145\x64\x20\x62\x79\x20\x72\x65\x61\x64\x69\156\x67\40\164\150\162\157\165\147\150\x20\164\x68\145\x20\106\x41\x51\163\x2e\x2e", "\116\x65\x78\164", "\146\141\x71\x2e\163\166\147", 2), $this->tour_template("\x61\x63\143\x5f\x73\x65\164\165\x70\x5f\x62\165\164\164\x6f\x6e\x5f\151\144", "\165\160", "\x3c\150\x31\76\x49\x20\x77\141\x6e\164\x20\164\157\40\x75\x70\x67\x72\141\x64\145\x21\74\57\150\x31\76", "\x59\x6f\x75\x20\144\x6f\x20\156\x6f\x74\x20\x6e\x65\145\144\x20\x74\157\40\163\145\164\165\160\x20\x79\157\165\x72\x20\x61\143\143\157\165\156\164\40\x74\157\x20\165\x73\x65\40\x74\150\145\40\160\x6c\x75\147\x69\156\x2e\x20\x49\146\x20\x79\x6f\x75\40\x77\141\156\x74\40\x74\x6f\40\165\x70\x67\x72\x61\144\x65\x2c\x20\171\x6f\x75\x20\167\151\x6c\154\40\x6e\x65\x65\x64\40\141\x20\155\x69\156\151\117\x72\x61\156\147\145\x20\141\143\143\157\x75\x6e\164\56", "\116\x65\x78\164", "\160\157\x70\x55\x70\x2e\x73\166\147", 2), $this->tour_template("\162\x65\x73\x74\141\x72\164\137\164\x6f\165\162\x5f\142\x75\164\164\x6f\156", "\x72\x69\147\x68\164", "\x3c\150\61\76\x52\145\163\x74\141\x72\x74\40\124\x6f\165\162\x3c\x2f\x68\61\x3e", "\x49\146\40\x79\x6f\x75\40\156\x65\x65\x64\x20\x74\157\x20\x72\x65\x76\151\x73\151\164\40\164\150\x65\x20\x74\157\165\x72\x2c\40\171\x6f\165\x20\143\141\x6e\40\165\x73\x65\40\x74\x68\151\x73\40\142\x75\x74\164\157\156\40\x74\157\x20\162\145\x70\154\x61\171\x20\x69\164\40\x66\157\x72\40\x74\150\145\40\143\165\x72\x72\x65\x6e\164\x20\164\x61\142\41", "\116\x65\170\x74", "\162\x65\x70\x6c\141\171\56\163\x76\x67", 2)];
    }
    private function get_updateui_pointers()
    {
        return [$this->tour_template("\155\157\x5f\x6f\141\165\164\150\137\x74\x65\163\x74\x5f\x63\x6f\156\146\151\x67\165\162\141\164\x69\157\156", "\154\x65\x66\164", "\74\150\x31\76\x54\x65\163\164\40\x79\157\165\162\40\x63\157\x6e\x66\151\147\x75\162\141\x74\x69\x6f\x6e\74\x2f\150\x31\76", "\103\154\151\x63\153\x20\x68\x65\x72\x65\x20\x74\157\40\x73\x65\145\40\164\150\x65\40\154\151\x73\164\x20\157\146\x20\141\164\164\x72\151\142\x75\164\x65\163\x20\160\x72\x6f\166\x69\x64\145\x64\x20\142\171\x20\x79\x6f\x75\162\40\117\101\165\164\150\40\x50\162\157\x76\x69\x64\145\x72\x2e\x20\x49\146\40\x79\x6f\x75\x20\x61\162\145\x20\x67\x65\164\164\151\x6e\x67\40\141\156\x79\40\145\x72\162\x6f\162\x2c\40\x70\154\x65\x61\x73\x65\40\162\145\146\x65\162\40\164\x68\145\x20\106\x41\x51\x20\164\141\142\x2e", "\x4e\x65\x78\164", "\160\162\145\x76\151\145\x77\x2e\x73\x76\147", 2), $this->tour_template("\141\x74\164\162\x6d\x61\160\x70\x69\156\x67", "\x6c\145\x66\164", "\x3c\x68\61\76\x4d\141\160\x70\151\156\147\40\101\164\x74\x72\151\142\165\164\145\163\74\57\150\61\76", "\105\x6e\x74\145\x72\x20\164\150\x65\40\x61\x70\x70\162\157\x70\x72\x69\141\164\x65\40\166\x61\154\x75\x65\x73\x28\141\164\164\162\151\142\x75\x74\145\x20\x6e\x61\x6d\x65\x73\51\x20\146\162\157\x6d\x20\x74\150\x65\x20\124\x65\163\164\40\x43\157\x6e\x66\151\147\x75\162\x61\164\x69\157\156\x20\164\x61\x62\154\x65\x2e", "\116\145\x78\x74", "\x70\x72\x65\x76\151\145\x77\56\x73\x76\147", 2), $this->tour_template("\162\157\x6c\x65\x6d\141\160\x70\151\156\x67", "\154\x65\146\x74", "\74\150\61\76\x4d\x61\x70\x70\151\x6e\x67\40\x52\x6f\x6c\x65\x73\74\57\x68\x31\x3e", "\105\156\x74\x65\162\x20\x74\150\x65\40\x72\x6f\154\145\40\x76\141\154\x75\x65\x73\40\x66\x72\x6f\x6d\40\x79\x6f\x75\x72\x20\117\101\165\x74\150\x2f\x4f\160\145\156\x49\x44\x20\160\162\157\166\151\x64\x65\x72\40\141\x6e\144\40\x74\150\145\x6e\x20\163\145\154\x65\x63\x74\40\164\x68\145\x20\x57\x6f\162\x64\x50\x72\145\163\163\40\x52\157\x6c\x65\x20\164\x68\141\164\x20\171\x6f\165\x20\x6e\145\x65\x64\40\164\157\x20\x61\x73\163\151\147\156\x20\164\x68\141\x74\40\162\157\154\145\x2e", "\146\141\x6c\163\145", "\x70\162\145\x76\151\x65\x77\x2e\163\x76\147", 2)];
    }
    private function get_signinsettings_pointers()
    {
        return [$this->tour_template("\x77\151\x64\55\x73\150\157\162\x74\143\x6f\144\145", "\154\145\146\164", "\x3c\150\x31\76\123\x69\147\156\x20\x49\156\40\117\x70\x74\151\157\x6e\163\x3c\x2f\150\61\76", "\x59\157\x75\x20\143\x61\x6e\x20\144\x69\x73\x70\154\x61\x79\40\171\157\x75\162\40\x6c\157\x67\151\156\x20\142\165\x74\x74\x6f\156\x20\165\163\151\x6e\x67\40\x74\x68\145\x73\x65\x20\155\145\x74\x68\157\144\163\x2e", "\116\145\x78\164", "\x70\162\x65\166\151\x65\167\x2e\163\x76\147", 2), $this->tour_template("\x61\x64\x76\141\156\x63\145\144\137\x73\x65\164\164\x69\x6e\147\163\x5f\x73\163\x6f", "\x6c\145\146\x74", "\74\150\61\x3e\x41\x64\166\141\156\143\145\144\x20\123\145\164\164\x69\x6e\147\163\x3c\57\x68\x31\76", "\x59\157\x75\40\143\x61\x6e\40\x63\157\156\x66\151\147\165\x72\145\40\x76\141\x72\x69\157\x75\163\x20\157\x70\164\151\x6f\x6e\x73\40\x6c\151\153\145\x20\x43\x61\154\x6c\142\141\x63\153\x20\125\x52\x4c\x2c\x20\104\157\x6d\141\x69\156\40\122\x65\x73\164\162\x69\143\164\x69\x6f\x6e\x2c\x20\x65\x74\x63\x2e\40\x68\145\162\x65\56", "\x66\x61\154\163\x65", "\x70\x72\145\166\x69\x65\x77\56\163\166\147", 2)];
    }
    private function get_applist_pointers()
    {
        return [$this->tour_template("\x6d\x6f\137\x6f\141\165\x74\150\x5f\141\160\160\x5f\154\151\163\164", "\x6c\x65\x66\164", "\74\150\x31\x3e\x41\x70\160\x20\114\x69\163\x74\74\57\150\x31\x3e", "\103\x6c\x69\x63\153\40\x68\145\162\145\40\164\x6f\x20\125\160\x64\141\164\145\x20\x6f\162\x20\104\x65\x6c\x65\164\x65\x20\x74\x68\145\40\x61\x70\160\x6c\x69\143\141\164\x69\157\156\56", "\x66\x61\154\x73\x65", "\x70\162\x65\166\x69\x65\x77\x2e\163\166\147", 2)];
    }
    private function get_defaultapps_pointers()
    {
        return [$this->tour_template("\x6d\157\x5f\x6f\141\165\x74\150\x5f\x63\154\x69\145\156\164\137\x64\x65\x66\141\165\154\164\x5f\x61\160\160\x73\137\x63\x6f\156\164\141\151\x6e\145\162", "\154\145\x66\x74", "\74\x68\x31\76\123\x65\154\145\x63\164\x20\117\x41\x75\164\150\x20\120\x72\157\166\x69\144\145\x72\x3c\x2f\150\61\x3e", "\x43\150\x6f\x6f\x73\x65\x20\x79\x6f\165\x72\40\117\101\165\164\150\x20\120\x72\x6f\166\x69\144\145\162\x20\x66\x72\x6f\x6d\40\x74\150\145\x20\x6c\x69\x73\164\40\x6f\146\40\117\x41\x75\164\150\x20\x50\162\157\166\x69\144\145\x72\x73", "\x66\x61\x6c\x73\145", "\x70\162\145\x76\151\x65\167\56\x73\166\x67", 2)];
    }
    private function get_addapp_pointers()
    {
        return [$this->tour_template("\x6d\157\137\x6f\141\165\x74\150\x5f\143\157\x6e\146\x69\147\x5f\x67\x75\x69\144\x65", "\154\x65\146\164", "\74\x68\x31\x3e\x43\157\156\x66\151\x67\165\162\145\x20\131\x6f\x75\162\40\x41\160\160\74\57\150\61\76", "\x4e\x65\x65\x64\40\x68\145\154\x70\40\167\151\x74\x68\40\x63\157\x6e\146\151\147\165\162\x61\x74\x69\157\156\x3f\x20\x43\154\151\x63\x6b\x20\x6f\x6e\x20\110\x6f\167\x20\x74\157\40\103\157\156\x66\x69\147\165\x72\x65\77", "\146\x61\x6c\163\x65", '', 1)];
    }
}
