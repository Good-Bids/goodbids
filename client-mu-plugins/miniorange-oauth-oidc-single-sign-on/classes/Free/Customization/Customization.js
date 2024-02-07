
function moOauthIconsPreview(param){
    if(param.customCss == true){
        jQuery(".mo_oauth_custom_tab_item_color").css("flex-direction", "column");
        jQuery(".mo_oauth_custom_tab_item_color").css("align-items", "center");
    }
    else{
    if(param.theme== 'default') {
        let defaultClassName = 'mo_oauth_default_icon_preview';
        jQuery(".mo_oauth_custom_icon_preview").css("display","none");
        if (param.shape == 'longbutton') {
            let defaultSpanName = 'mo_oauth_login_button_font';
            let defaultSpanImgName = 'mo_oauth_login_but_img_span';
            let defaultImgName = 'mo_oauth_login_but_img';
            jQuery(".mo_oauth_custom_tab_item_color").css("flex-direction", "column");
            jQuery(".mo_oauth_custom_tab_item_color").css("align-items", " center");
            jQuery("."+ defaultClassName).css("margin",param.space + "px");
            jQuery("."+ defaultClassName).css("height",param.height-8+ "px" );
            jQuery("."+ defaultClassName).css("width",param.width + "px");
            jQuery("."+ defaultClassName).css("padding","10px 8px 8px 20px");
            jQuery("."+ defaultClassName).css("font-size",param.height-10);
            jQuery("."+ defaultClassName).css("border-radius",param.curve + "px");
            if(param.scale == 'scale') {
                jQuery("." + defaultClassName).addClass("mo_oauth_btn_scale");
            }
            else {
                jQuery("." + defaultClassName).removeClass("mo_oauth_btn_scale");
            }
            if(param.shadow == 'shadow')
            jQuery("."+ defaultClassName).css("box-shadow","0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19)");
            else
            jQuery("."+ defaultClassName).css("box-shadow","none");

            jQuery("."+ defaultSpanName).css("display","initial");
            jQuery("."+ defaultSpanName).css("margin-left",param.width/5.5 + "px");
            // jQuery("."+ defaultSpanName).css("font-size",(((Number(param.width))/10)-((Number(param.width))/40)) + "px");
            jQuery("."+ defaultSpanName).css("font-size",14 + "px");
            jQuery("."+ defaultSpanImgName).css("vertical-align","7px");
            jQuery("."+ defaultImgName).css("vertical-align",15-(param.height/2)+"px");
            jQuery("."+ defaultImgName).css("height",param.height-8 + "px");
            jQuery("."+ defaultImgName).css("width",param.height-8 + "px");
            jQuery("."+ defaultImgName).css("background","transperent");


        }else{
            let defaultSpanName = 'mo_oauth_login_button_font';
            let defaultSpanImgName = 'mo_oauth_login_but_img_span';
            let defaultImgName = 'mo_oauth_login_but_img';
            jQuery(".mo_oauth_custom_tab_item_color").css("flex-flow", "row wrap");
            jQuery(".mo_oauth_custom_tab_item_color").css("align-items", " flex-start");
            jQuery("."+ defaultClassName).css("margin",param.space + "px");
            jQuery("."+ defaultClassName).css("height",param.size+ "px" );
            jQuery("."+ defaultClassName).css("width",param.size + "px");
            jQuery("."+ defaultClassName).css("padding","8px");
            jQuery("."+ defaultClassName).css("font-size",param.size+ "px");
            jQuery(".mo_oauth_lock_pad").css("padding","8px 3px 8px 13px");

            if(param.scale == 'scale') {
                jQuery("." + defaultClassName).addClass("mo_oauth_btn_scale");
            }
            else {
                jQuery("." + defaultClassName).removeClass("mo_oauth_btn_scale");
            }
            if(param.shadow == 'shadow')
                jQuery("."+ defaultClassName).css("box-shadow","0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19)");
            else
                jQuery("."+ defaultClassName).css("box-shadow","none");

            jQuery("."+ defaultSpanName).css("display","none");
            jQuery("."+ defaultImgName).css("vertical-align",15-(param.size/2)+"px");
            jQuery("."+ defaultImgName).css("height",param.size + "px");
            jQuery("."+ defaultImgName).css("width",param.size  + "px");
            jQuery("."+ defaultImgName).css("background","transperent");

            if(param.shape == 'square')
                jQuery("."+ defaultClassName).css("border-radius","0px");
            else if(param.shape == 'oval')
                jQuery("."+ defaultClassName).css("border-radius","10px");
            else if(param.shape == 'circle')
                jQuery("."+ defaultClassName).css("border-radius","999px");

        }
    }
    else if(param.theme== 'custom') {
        let defaultClassName = 'mo_oauth_custom_icon_preview';
        jQuery("."+ defaultClassName).css("background",param.color);
        jQuery("."+ defaultClassName).css("color","#FFFFFF");
        if (param.shape == 'longbutton') {
            let defaultSpanName = 'mo_oauth_login_button_font';
            let defaultSpanImgName = 'mo_oauth_login_but_img_span';
            let defaultImgName = 'mo_oauth_login_but_img';
            jQuery(".mo_oauth_custom_tab_item_color").css("flex-direction", "column");
            jQuery(".mo_oauth_custom_tab_item_color").css("align-items", " center");
            jQuery("."+ defaultClassName).css("margin",param.space + "px");
            jQuery("."+ defaultClassName).css("height",param.height-8+ "px" );
            jQuery("."+ defaultClassName).css("width",param.width + "px");
            jQuery("."+ defaultClassName).css("padding","10px 8px 8px 20px");
            jQuery("."+ defaultClassName).css("font-size",param.height-10);
            jQuery("."+ defaultClassName).css("border-radius",param.curve + "px");
            if(param.scale == 'scale') {
                jQuery("." + defaultClassName).addClass("mo_oauth_btn_scale");
            }
            else {
                jQuery("." + defaultClassName).removeClass("mo_oauth_btn_scale");
            }
            if(param.shadow == 'shadow')
                jQuery("."+ defaultClassName).css("box-shadow","0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19)");
            else
                jQuery("."+ defaultClassName).css("box-shadow","none");

            jQuery("."+ defaultSpanName).css("display","initial");
            jQuery("."+ defaultSpanName).css("margin-left",param.width/5.5 + "px");
            // jQuery("."+ defaultSpanName).css("font-size",(((Number(param.width))/10)-((Number(param.width))/40)) + "px");
            jQuery("."+ defaultSpanName).css("font-size",14 + "px");
            jQuery("."+ defaultSpanImgName).css("vertical-align","7px");
            jQuery("."+ defaultImgName).css("vertical-align",15-(param.height/2)+"px");
            jQuery("."+ defaultImgName).css("height",param.height-8 + "px");
            jQuery("."+ defaultImgName).css("width",param.height-8 + "px");
            jQuery("."+ defaultImgName).css("background","transperent");
        }else{
            let defaultSpanName = 'mo_oauth_login_button_font';
            let defaultSpanImgName = 'mo_oauth_login_but_img_span';
            let defaultImgName = 'mo_oauth_login_but_img';
            jQuery(".mo_oauth_custom_tab_item_color").css("flex-flow", "row wrap");
            jQuery(".mo_oauth_custom_tab_item_color").css("align-items", " flex-start");
            jQuery("."+ defaultClassName).css("margin",param.space + "px");
            jQuery("."+ defaultClassName).css("height",param.size+ "px" );
            jQuery("."+ defaultClassName).css("width",param.size + "px");
            jQuery("."+ defaultClassName).css("padding","8px");
            jQuery("."+ defaultClassName).css("font-size",param.size+ "px");
            jQuery(".mo_oauth_lock_pad").css("padding","8px 3px 8px 13px");

            if(param.scale == 'scale') {
                jQuery("." + defaultClassName).addClass("mo_oauth_btn_scale");
            }
            else {
                jQuery("." + defaultClassName).removeClass("mo_oauth_btn_scale");
            }
            if(param.shadow == 'shadow')
                jQuery("."+ defaultClassName).css("box-shadow","0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19)");
            else
                jQuery("."+ defaultClassName).css("box-shadow","none");

            jQuery("."+ defaultSpanName).css("display","none");
            jQuery("."+ defaultImgName).css("vertical-align",15-(param.height/2)+"px");
            jQuery("."+ defaultImgName).css("height",param.size + "px");
            jQuery("."+ defaultImgName).css("width",param.size  + "px");
            jQuery("."+ defaultImgName).css("background","transperent");
            if(param.shape == 'square')
                jQuery("."+ defaultClassName).css("border-radius","0px");
            else if(param.shape == 'oval')
                jQuery("."+ defaultClassName).css("border-radius","10px");
            else if(param.shape == 'circle')
                jQuery("."+ defaultClassName).css("border-radius","999px");

        }
    }
    else if(param.theme== 'white') {
        let defaultClassName = 'mo_oauth_white_icon_preview';
        if (param.shape == 'longbutton') {
            let defaultSpanName = 'mo_oauth_login_button_font';
            let defaultSpanImgName = 'mo_oauth_login_but_img_span';
            let defaultImgName = 'mo_oauth_login_but_img';
            jQuery(".mo_oauth_custom_tab_item_color").css("flex-direction", "column");
            jQuery(".mo_oauth_custom_tab_item_color").css("align-items", " center");
            jQuery("."+ defaultClassName).css("margin",param.space + "px");
            jQuery("."+ defaultClassName).css("height",param.height-8+ "px" );
            jQuery("."+ defaultClassName).css("width",param.width + "px");
            jQuery("."+ defaultClassName).css("padding","10px 8px 8px 20px");
            jQuery("."+ defaultClassName).css("font-size",param.height-10);
            jQuery("."+ defaultClassName).css("border-radius",param.curve + "px");
            jQuery("."+ defaultClassName).css("box-shadow","0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19)");

            if(param.scale == 'scale') {
                jQuery("." + defaultClassName).addClass("mo_oauth_btn_scale");
            }
            else {
                jQuery("." + defaultClassName).removeClass("mo_oauth_btn_scale");
            }


            jQuery("."+ defaultSpanName).css("display","initial");
            jQuery("."+ defaultSpanName).css("margin-left",param.width/5.5 + "px");
            // jQuery("."+ defaultSpanName).css("font-size",(((Number(param.width))/10)-((Number(param.width))/40)) + "px");
            jQuery("."+ defaultSpanName).css("font-size",14 + "px");
            jQuery("."+ defaultSpanImgName).css("vertical-align","7px");
            jQuery("."+ defaultImgName).css("vertical-align",15-(param.height/2)+"px");
            jQuery("."+ defaultImgName).css("height",param.height-8 + "px");
            jQuery("."+ defaultImgName).css("width",param.height-8 + "px");


        }else{
            let defaultSpanName = 'mo_oauth_login_button_font';
            let defaultSpanImgName = 'mo_oauth_login_but_img_span';
            let defaultImgName = 'mo_oauth_login_but_img';
            jQuery(".mo_oauth_custom_tab_item_color").css("flex-flow", "row wrap");
            jQuery(".mo_oauth_custom_tab_item_color").css("align-items", " flex-start");
            jQuery("."+ defaultClassName).css("margin",param.space + "px");
            jQuery("."+ defaultClassName).css("height",param.size+ "px" );
            jQuery("."+ defaultClassName).css("width",param.size + "px");
            jQuery("."+ defaultClassName).css("padding","8px");
            jQuery("."+ defaultClassName).css("font-size",param.size+ "px");
            jQuery(".mo_oauth_lock_pad").css("padding","8px 3px 8px 13px");

            if(param.scale == 'scale') {
                jQuery("." + defaultClassName).addClass("mo_oauth_btn_scale");
            }
            else {
                jQuery("." + defaultClassName).removeClass("mo_oauth_btn_scale");
            }
            if(param.shadow == 'shadow')
                jQuery("."+ defaultClassName).css("box-shadow","0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19)");
            else
                jQuery("."+ defaultClassName).css("box-shadow","none");

            jQuery("."+ defaultSpanName).css("display","none");
            jQuery("."+ defaultImgName).css("vertical-align",15-(param.height/2)+"px");
            jQuery("."+ defaultImgName).css("height",param.size + "px");
            jQuery("."+ defaultImgName).css("width",param.size  + "px");

            if(param.shape == 'square')
                jQuery("."+ defaultClassName).css("border-radius","0px");
            else if(param.shape == 'oval')
                jQuery("."+ defaultClassName).css("border-radius","10px");
            else if(param.shape == 'circle')
                jQuery("."+ defaultClassName).css("border-radius","999px");



        }
    }
    else if(param.theme== 'hover') {
        let defaultClassName = 'mo_oauth_hover_icon_preview';
        if (param.shape == 'longbutton') {
            let defaultSpanName = 'mo_oauth_login_button_font';
            let defaultSpanImgName = 'mo_oauth_login_but_img_span';
            let defaultImgName = 'mo_oauth_login_but_img';
            jQuery(".mo_oauth_custom_tab_item_color").css("flex-direction", "column");
            jQuery(".mo_oauth_custom_tab_item_color").css("align-items", " center");
            jQuery("."+ defaultClassName).css("margin",param.space + "px");
            jQuery("."+ defaultClassName).css("height",param.height-8+ "px" );
            jQuery("."+ defaultClassName).css("width",param.width + "px");
            jQuery("."+ defaultClassName).css("padding","10px 8px 8px 20px");
            jQuery("."+ defaultClassName).css("font-size",param.height-10);
            jQuery("."+ defaultClassName).css("border-radius",param.curve + "px");
            jQuery("."+ defaultClassName).css("box-shadow","0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19)");
            if(param.scale == 'scale') {
                jQuery("." + defaultClassName).addClass("mo_oauth_btn_scale");
            }
            else {
                jQuery("." + defaultClassName).removeClass("mo_oauth_btn_scale");
            }
            jQuery("."+ defaultSpanName).css("display","initial");
            jQuery("."+ defaultSpanName).css("margin-left",param.width/5.5 + "px");
            // jQuery("."+ defaultSpanName).css("font-size",(((Number(param.width))/10)-((Number(param.width))/40)) + "px");
            jQuery("."+ defaultSpanName).css("font-size",14 + "px");
            jQuery("."+ defaultSpanImgName).css("vertical-align","7px");
            jQuery("."+ defaultImgName).css("vertical-align",15-(param.height/2)+"px");
            jQuery("."+ defaultImgName).css("height",param.height-8 + "px");
            jQuery("."+ defaultImgName).css("width",param.height-8 + "px");


        }else{
            let defaultSpanName = 'mo_oauth_login_button_font';
            let defaultSpanImgName = 'mo_oauth_login_but_img_span';
            let defaultImgName = 'mo_oauth_login_but_img';
            jQuery(".mo_oauth_custom_tab_item_color").css("flex-flow", "row wrap");
            jQuery(".mo_oauth_custom_tab_item_color").css("align-items", " flex-start");
            jQuery("."+ defaultClassName).css("margin",param.space + "px");
            jQuery("."+ defaultClassName).css("height",param.size+ "px" );
            jQuery("."+ defaultClassName).css("width",param.size + "px");
            jQuery("."+ defaultClassName).css("padding","8px");
            jQuery("."+ defaultClassName).css("box-shadow","0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19)");
            jQuery("."+ defaultClassName).css("font-size",param.size+ "px");
            jQuery(".mo_oauth_lock_pad").css("padding","8px 3px 8px 13px");

            if(param.scale == 'scale') {
                jQuery("." + defaultClassName).addClass("mo_oauth_btn_scale");
            }
            else {
                jQuery("." + defaultClassName).removeClass("mo_oauth_btn_scale");
            }
            jQuery("."+ defaultSpanName).css("display","none");
            jQuery("."+ defaultImgName).css("vertical-align",15-(param.height/2)+"px");
            jQuery("."+ defaultImgName).css("height",param.size + "px");
            jQuery("."+ defaultImgName).css("width",param.size  + "px");

            if(param.shape == 'square')
                jQuery("."+ defaultClassName).css("border-radius","0px");
            else if(param.shape == 'oval')
                jQuery("."+ defaultClassName).css("border-radius","10px");
            else if(param.shape == 'circle')
                jQuery("."+ defaultClassName).css("border-radius","999px");



        }
    }
    else if(param.theme== 'customhover') {
        let defaultClassName = 'mo_oauth_custom_hover_icon_preview';
        jQuery("."+ defaultClassName).hover(function() {
            jQuery(this).css("background-color", param.customColor);
            jQuery(this).css("color", "#FFFFFF");
        }, function() {
            jQuery(this).css("background-color", "#FFFFFF");
            jQuery(this).css("color", param.customColor);
        });
        if (param.shape == 'longbutton') {
            let defaultSpanName = 'mo_oauth_login_button_font';
            let defaultSpanImgName = 'mo_oauth_login_but_img_span';
            let defaultImgName = 'mo_oauth_login_but_custom_img';
            jQuery(".mo_oauth_custom_tab_item_color").css("flex-direction", "column");
            jQuery(".mo_oauth_custom_tab_item_color").css("align-items", " center");
            jQuery("."+ defaultClassName).css("color",param.customColor);
            jQuery("."+ defaultClassName + ":hover").css("background","purple");
            jQuery("."+ defaultClassName).css("margin",param.space + "px");
            jQuery("."+ defaultClassName).css("height",param.height-8+ "px" );
            jQuery("."+ defaultClassName).css("width",param.width + "px");
            jQuery("."+ defaultClassName).css("padding","10px 8px 8px 20px");
            jQuery("."+ defaultClassName).css("font-size",param.height-10);
            jQuery("."+ defaultClassName).css("border-radius",param.curve + "px");
            if(param.scale == 'scale') {
                jQuery("." + defaultClassName).addClass("mo_oauth_btn_scale");
            }
            else {
                jQuery("." + defaultClassName).removeClass("mo_oauth_btn_scale");
            }
            if(param.shadow == 'shadow')
                jQuery("."+ defaultClassName).css("box-shadow","0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19)");
            else
                jQuery("."+ defaultClassName).css("box-shadow","none");

            jQuery("."+ defaultSpanName).css("display","initial");
            jQuery("."+ defaultSpanName).css("margin-left",param.width/5.5 + "px");
            // jQuery("."+ defaultSpanName).css("font-size",(((Number(param.width))/10)-((Number(param.width))/40)) + "px");
            jQuery("."+ defaultSpanName).css("font-size",14 + "px");
            jQuery("."+ defaultSpanImgName).css("vertical-align","7px");
            jQuery("."+ defaultImgName).css("vertical-align",15-(param.height/2)+"px");
            jQuery("."+ defaultImgName).css("height",param.height-8 + "px");
            jQuery("."+ defaultImgName).css("width",param.height-8 + "px");
            jQuery("."+ defaultImgName).css("border-radius", "999px");
            jQuery("."+ defaultImgName).css("background", param.customColor);


        }else{
            let defaultSpanName = 'mo_oauth_login_button_font';
            let defaultSpanImgName = 'mo_oauth_login_but_img_span';
            let defaultImgName = 'mo_oauth_login_but_custom_img';
            jQuery(".mo_oauth_custom_tab_item_color").css("flex-flow", "row wrap");
            jQuery(".mo_oauth_custom_tab_item_color").css("align-items", " flex-start");
            jQuery("."+ defaultClassName).css("color",param.customColor);
            jQuery("."+ defaultClassName + ":hover").css("background","purple");
            jQuery("."+ defaultClassName).css("margin",param.space + "px");
            jQuery("."+ defaultClassName).css("height",param.size+ "px" );
            jQuery("."+ defaultClassName).css("width",param.size + "px");
            jQuery("."+ defaultClassName).css("padding","8px");
            jQuery("."+ defaultClassName).css("font-size",param.size+ "px");
            jQuery(".mo_oauth_lock_pad").css("padding","8px 3px 8px 13px");

            if(param.scale == 'scale') {
                jQuery("." + defaultClassName).addClass("mo_oauth_btn_scale");
            }
            else {
                jQuery("." + defaultClassName).removeClass("mo_oauth_btn_scale");
            }
            if(param.shadow == 'shadow')
                jQuery("."+ defaultClassName).css("box-shadow","0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19)");
            else
                jQuery("."+ defaultClassName).css("box-shadow","none");

            jQuery("."+ defaultSpanName).css("display","none");
            jQuery("."+ defaultImgName).css("vertical-align",15-(param.height/2)+"px");
            jQuery("."+ defaultImgName).css("height",param.size + "px");
            jQuery("."+ defaultImgName).css("width",param.size  + "px");
            jQuery("."+ defaultImgName).css("border-radius", "999px");
            jQuery("."+ defaultImgName).css("background", param.customColor);
            if(param.shape == 'square')
                jQuery("."+ defaultClassName).css("border-radius","0px");
            else if(param.shape == 'oval')
                jQuery("."+ defaultClassName).css("border-radius","10px");
            else if(param.shape == 'circle')
                jQuery("."+ defaultClassName).css("border-radius","999px");



        }
    }
    else if(param.theme== 'smart') {
        let defaultClassName = 'mo_oauth_smart_icon_preview';
        jQuery(".mo_oauth_custom_icon_preview").css("display","none");
        if (param.shape == 'longbutton') {
            let defaultSpanName = 'mo_oauth_login_button_font';
            let defaultSpanImgName = 'mo_oauth_login_but_img_span';
            let defaultImgName = 'mo_oauth_login_but_img';
            jQuery(".mo_oauth_custom_tab_item_color").css("flex-direction", "column");
            jQuery(".mo_oauth_custom_tab_item_color").css("align-items", " center");
            jQuery("."+ defaultClassName).css("margin",param.space + "px");
            jQuery("."+ defaultClassName).css("height",param.height-8+ "px" );
            jQuery("."+ defaultClassName).css("width",param.width + "px");
            jQuery("."+ defaultClassName).css("color","#FFFFFF");
            jQuery("."+ defaultClassName).css("background","linear-gradient(45deg,"+param.smartColor1+","+param.smartColor2+")");
            jQuery("."+ defaultClassName).css("padding","10px 8px 8px 20px");
            jQuery("."+ defaultClassName).css("font-size",param.height-10);
            jQuery("."+ defaultClassName).css("border-radius",param.curve + "px");

            if(param.scale == 'scale') {
                jQuery("." + defaultClassName).addClass("mo_oauth_btn_scale");
            }
            else {
                jQuery("." + defaultClassName).removeClass("mo_oauth_btn_scale");
            }
            if(param.shadow == 'shadow')
                jQuery("."+ defaultClassName).css("box-shadow","0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19)");
            else
                jQuery("."+ defaultClassName).css("box-shadow","none");

            jQuery("."+ defaultSpanName).css("display","initial");
            jQuery("."+ defaultSpanName).css("margin-left",param.width/5.5 + "px");
            // jQuery("."+ defaultSpanName).css("font-size",(((Number(param.width))/10)-((Number(param.width))/40)) + "px");
            jQuery("."+ defaultSpanName).css("font-size",14 + "px");
            jQuery("."+ defaultSpanImgName).css("vertical-align","7px");
            jQuery("."+ defaultImgName).css("vertical-align",15-(param.height/2)+"px");
            jQuery("."+ defaultImgName).css("height",param.height-8 + "px");
            jQuery("."+ defaultImgName).css("width",param.height-8 + "px");
            jQuery("."+ defaultImgName).css("background","transperent");


        }else{
            let defaultSpanName = 'mo_oauth_login_button_font';
            let defaultSpanImgName = 'mo_oauth_login_but_img_span';
            let defaultImgName = 'mo_oauth_login_but_img';
            jQuery(".mo_oauth_custom_tab_item_color").css("flex-flow", "row wrap");
            jQuery(".mo_oauth_custom_tab_item_color").css("align-items", " flex-start");
            jQuery("."+ defaultClassName).css("margin",param.space + "px");
            jQuery("."+ defaultClassName).css("height",param.size+ "px" );
            jQuery("."+ defaultClassName).css("width",param.size + "px");
            jQuery("."+ defaultClassName).css("padding","8px");
            jQuery("."+ defaultClassName).css("font-size",param.size+ "px");
            jQuery(".mo_oauth_lock_pad").css("padding","8px 3px 8px 13px");
            jQuery("."+ defaultClassName).css("color","#FFFFFF");
            jQuery("."+ defaultClassName).css("background","linear-gradient(45deg,"+param.smartColor1+","+param.smartColor2+")");

            if(param.scale == 'scale') {
                jQuery("." + defaultClassName).addClass("mo_oauth_btn_scale");
            }
            else {
                jQuery("." + defaultClassName).removeClass("mo_oauth_btn_scale");
            }
            if(param.shadow == 'shadow')
                jQuery("."+ defaultClassName).css("box-shadow","0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19)");
            else
                jQuery("."+ defaultClassName).css("box-shadow","none");

            jQuery("."+ defaultSpanName).css("display","none");
            jQuery("."+ defaultImgName).css("vertical-align",15-(param.height/2)+"px");
            jQuery("."+ defaultImgName).css("height",param.size + "px");
            jQuery("."+ defaultImgName).css("width",param.size  + "px");
            jQuery("."+ defaultImgName).css("background","transperent");

            if(param.shape == 'square')
                jQuery("."+ defaultClassName).css("border-radius","0px");
            else if(param.shape == 'oval')
                jQuery("."+ defaultClassName).css("border-radius","10px");
            else if(param.shape == 'circle')
                jQuery("."+ defaultClassName).css("border-radius","999px");



        }
    }
    else if(param.theme=='previous'){
        let defaultClassName = 'mo_oauth_previous_icon_preview';
        jQuery(".mo_oauth_custom_icon_preview").css("display","none");
        if (param.shape == 'longbutton') {
            let defaultSpanName = 'mo_oauth_login_button_font';
            let defaultSpanImgName = 'mo_oauth_login_but_img_span';
            let defaultImgName = 'mo_oauth_login_but_img';
            jQuery(".mo_oauth_custom_tab_item_color").css("flex-direction", "column");
            jQuery(".mo_oauth_custom_tab_item_color").css("align-items", " center");
            jQuery("."+ defaultClassName).css("margin",param.space + "px");
            jQuery("."+ defaultClassName).css("height",param.height-8+ "px" );
            jQuery("."+ defaultClassName).css("width",param.width + "px");
            jQuery("."+ defaultClassName).css("padding","10px 8px 8px 20px");
            jQuery("."+ defaultClassName).css("font-size",param.height-10);
            jQuery("."+ defaultClassName).css("border-radius",param.curve + "px");
            jQuery("."+ defaultClassName).css("color","white");
            jQuery("."+ defaultClassName).css("background","#337ab7");

            if(param.scale == 'scale') {
                jQuery("." + defaultClassName).addClass("mo_oauth_btn_scale");
            }
            else {
                jQuery("." + defaultClassName).removeClass("mo_oauth_btn_scale");
            }
            if(param.shadow == 'shadow')
            jQuery("."+ defaultClassName).css("box-shadow","0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19)");
            else
            jQuery("."+ defaultClassName).css("box-shadow","none");

            jQuery("."+ defaultSpanName).css("display","initial");
            jQuery("."+ defaultSpanName).css("margin-left",param.width/5.5 + "px");
            // jQuery("."+ defaultSpanName).css("font-size",(((Number(param.width))/10)-((Number(param.width))/40)) + "px");
            jQuery("."+ defaultSpanName).css("font-size",14 + "px");
            jQuery("."+ defaultSpanImgName).css("vertical-align","7px");
            jQuery("."+ defaultImgName).css("vertical-align",15-(param.height/2)+"px");
            jQuery("."+ defaultImgName).css("height",param.height-8 + "px");
            jQuery("."+ defaultImgName).css("width",param.height-8 + "px");
            jQuery("."+ defaultImgName).css("background","transparent");


        }else{
            let defaultSpanName = 'mo_oauth_login_button_font';
            let defaultSpanImgName = 'mo_oauth_login_but_img_span';
            let defaultImgName = 'mo_oauth_login_but_img';
            jQuery(".mo_oauth_custom_tab_item_color").css("flex-flow", "row wrap");
            jQuery(".mo_oauth_custom_tab_item_color").css("align-items", " flex-start");
            jQuery("."+ defaultClassName).css("margin",param.space + "px");
            jQuery("."+ defaultClassName).css("height",param.size+ "px" );
            jQuery("."+ defaultClassName).css("width",param.size + "px");
            jQuery("."+ defaultClassName).css("padding","8px");
            jQuery("."+ defaultClassName).css("font-size",param.size+ "px");
            jQuery(".mo_oauth_lock_pad").css("padding","8px");
            jQuery("."+ defaultClassName).css("color","white");
            jQuery("."+ defaultClassName).css("background","#337ab7");

            if(param.scale == 'scale') {
                jQuery("." + defaultClassName).addClass("mo_oauth_btn_scale");
            }
            else {
                jQuery("." + defaultClassName).removeClass("mo_oauth_btn_scale");
            }
            if(param.shadow == 'shadow')
                jQuery("."+ defaultClassName).css("box-shadow","0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19)");
            else
                jQuery("."+ defaultClassName).css("box-shadow","none");

            jQuery("."+ defaultSpanName).css("display","none");
            jQuery("."+ defaultImgName).css("vertical-align",15-(param.size/2)+"px");
            jQuery("."+ defaultImgName).css("height",param.size + "px");
            jQuery("."+ defaultImgName).css("width",param.size  + "px");
            jQuery("."+ defaultImgName).css("background","transperent");

            if(param.shape == 'square')
                jQuery("."+ defaultClassName).css("border-radius","0px");
            else if(param.shape == 'oval')
                jQuery("."+ defaultClassName).css("border-radius","10px");
            else if(param.shape == 'circle')
                jQuery("."+ defaultClassName).css("border-radius","999px");

        }
    }

}

}

function getArg(){
    var param= {
        'theme': selectLoginTheme(),
        'shape': selectLoginShape(),
        'width': document.getElementById('mo_oauth_icon_width').value,
        'height': document.getElementById('mo_oauth_icon_height').value,
        'size': document.getElementById('mo_oauth_icon_size').value,
        'space': document.getElementById('mo_oauth_icon_margin').value,
        'color': document.getElementById('mo_oauth_icon_color').value,
        'customColor': document.getElementById('mo_oauth_icon_custom_color').value,
        'smartColor1': document.getElementById('mo_oauth_icon_smart_color_1').value,
        'smartColor2': document.getElementById('mo_oauth_icon_smart_color_2').value,
        'curve': document.getElementById('mo_oauth_icon_curve').value,
        'scale': jQuery('input[name=mo_oauth_icon_effect_scale]:checked', '#form-common').val(),
        'shadow': jQuery('input[name=mo_oauth_icon_effect_shadow]:checked', '#form-common').val(),
        'customCss': (jQuery('#mo_oauth_icon_configure_css').val() == '' ?  false : true)

    }
    return param;
}
    function selectLoginTheme(){ return jQuery('input[name=mo_oauth_icon_theme]:checked', '#form-common').val();}
    function selectLoginShape(){return jQuery('input[name=mo_oauth_icon_shape]:checked', '#form-common').val();}
    function moOauthShapeHandler(){
        let shapeValue = jQuery('input[name=mo_oauth_icon_shape]:checked', '#form-common').val();
        if (shapeValue == 'longbutton') {
            jQuery("#mo_oauth_longbutton_parameter").css("display", "block");
            jQuery("#mo_oauth_button_parameter").css("display", "none");
        }else {
            jQuery("#mo_oauth_longbutton_parameter").css("display", "none");
            jQuery("#mo_oauth_button_parameter").css("display", "block");
        }
    }
    function moOauthThemeSelector(param){
        if(param == 'default'){
            jQuery(".mo_oauth_default_icon_preview").show();
            jQuery(".mo_oauth_custom_icon_preview").hide();
            jQuery(".mo_oauth_white_icon_preview").hide();
            jQuery(".mo_oauth_hover_icon_preview").hide();
            jQuery(".mo_oauth_custom_hover_icon_preview").hide();
            jQuery(".mo_oauth_smart_icon_preview").hide();
            jQuery(".mo_oauth_previous_icon_preview").hide();
        }
        else if(param == 'custom'){
            jQuery(".mo_oauth_default_icon_preview").hide();
            jQuery(".mo_oauth_custom_icon_preview").show();
            jQuery(".mo_oauth_white_icon_preview").hide();
            jQuery(".mo_oauth_hover_icon_preview").hide();
            jQuery(".mo_oauth_custom_hover_icon_preview").hide();
            jQuery(".mo_oauth_smart_icon_preview").hide();
            jQuery(".mo_oauth_previous_icon_preview").hide();
        }
        else if(param == 'white'){
            jQuery(".mo_oauth_default_icon_preview").hide();
            jQuery(".mo_oauth_custom_icon_preview").hide();
            jQuery(".mo_oauth_white_icon_preview").show();
            jQuery(".mo_oauth_hover_icon_preview").hide();
            jQuery(".mo_oauth_custom_hover_icon_preview").hide();
            jQuery(".mo_oauth_smart_icon_preview").hide();
            jQuery(".mo_oauth_previous_icon_preview").hide();
        }
        else if(param == 'hover'){
            jQuery(".mo_oauth_default_icon_preview").hide();
            jQuery(".mo_oauth_custom_icon_preview").hide();
            jQuery(".mo_oauth_white_icon_preview").hide();
            jQuery(".mo_oauth_hover_icon_preview").show();
            jQuery(".mo_oauth_custom_hover_icon_preview").hide();
            jQuery(".mo_oauth_smart_icon_preview").hide();
            jQuery(".mo_oauth_previous_icon_preview").hide();
        }
        else if(param == 'customhover'){
            jQuery(".mo_oauth_default_icon_preview").hide();
            jQuery(".mo_oauth_custom_icon_preview").hide();
            jQuery(".mo_oauth_white_icon_preview").hide();
            jQuery(".mo_oauth_hover_icon_preview").hide();
            jQuery(".mo_oauth_custom_hover_icon_preview").show();
            jQuery(".mo_oauth_smart_icon_preview").hide();
            jQuery(".mo_oauth_previous_icon_preview").hide();
        }
        else if(param == 'smart'){
            jQuery(".mo_oauth_default_icon_preview").hide();
            jQuery(".mo_oauth_custom_icon_preview").hide();
            jQuery(".mo_oauth_white_icon_preview").hide();
            jQuery(".mo_oauth_hover_icon_preview").hide();
            jQuery(".mo_oauth_custom_hover_icon_preview").hide();
            jQuery(".mo_oauth_smart_icon_preview").show();
            jQuery(".mo_oauth_previous_icon_preview").hide();
        }
        else if(param == 'previous'){
            jQuery(".mo_oauth_default_icon_preview").hide();
            jQuery(".mo_oauth_custom_icon_preview").hide();
            jQuery(".mo_oauth_white_icon_preview").hide();
            jQuery(".mo_oauth_hover_icon_preview").hide();
            jQuery(".mo_oauth_custom_hover_icon_preview").hide();
            jQuery(".mo_oauth_smart_icon_preview").hide();
            jQuery(".mo_oauth_previous_icon_preview").show();
        }
    }
    moOauthIconsPreview(getArg());
    moOauthShapeHandler();
    moOauthThemeSelector(selectLoginTheme());

    function moLoginHeightIncrement(e,t,r,a,i){
        var h,s,c=!1,_=a;s=function(){
            "add"==t&&r.value<50?r.value++:"subtract"==t&&r.value>35&&r.value--,h=setTimeout(s,_),_>20&&(_*=i),c||(document.onmouseup=function(){clearTimeout(h),document.onmouseup=null,c=!1,_=a},c=!0)},e.onmousedown=s}
    function moLoginIconIncrement(e,t,r,a,i){
        var h,s,c=!1,_=a;s=function(){
            "add"==t&&r.value<60?r.value++:"subtract"==t&&r.value>20&&r.value--,h=setTimeout(s,_),_>20&&(_*=i),c||(document.onmouseup=function(){clearTimeout(h),document.onmouseup=null,c=!1,_=a},c=!0)},e.onmousedown=s}
    function moLoginIconSpaceIncrement(e,t,r,a,i){
        var h,s,c=!1,_=a;s=function(){
            "add"==t&&r.value<30?r.value++:"subtract"==t&&r.value>0&&r.value--,h=setTimeout(s,_),_>20&&(_*=i),c||(document.onmouseup=function(){clearTimeout(h),document.onmouseup=null,c=!1,_=a},c=!0)},e.onmousedown=s}
    function moLoginWidthIncrement(e,t,r,a,i){
        var h,s,c=!1,_=a;s=function(){
            "add"==t&&r.value<500?r.value++:"subtract"==t&&r.value>50&&r.value--,h=setTimeout(s,_),_>20&&(_*=i),c||(document.onmouseup=function(){clearTimeout(h),document.onmouseup=null,c=!1,_=a},c=!0)},e.onmousedown=s}
    function moLoginCurveIncrement(e,t,r,a,i){
        var h,s,c=!1,_=a;s=function(){
            "add"==t&&r.value<100?r.value++:"subtract"==t&&r.value>0&&r.value--,h=setTimeout(s,_),_>20&&(_*=i),c||(document.onmouseup=function(){clearTimeout(h),document.onmouseup=null,c=!1,_=a},c=!0)},e.onmousedown=s}

    moLoginHeightIncrement(document.getElementById('mo_oauth_height_plus'), "add", document.getElementById('mo_oauth_icon_height'), 300, 0.7);
    moLoginHeightIncrement(document.getElementById('mo_oauth_height_minus'), "subtract", document.getElementById('mo_oauth_icon_height'), 300, 0.7);
    moLoginIconIncrement(document.getElementById('mo_oauth_icon_plus'), "add", document.getElementById('mo_oauth_icon_size'), 300, 0.7);
    moLoginIconIncrement(document.getElementById('mo_oauth_icon_minus'), "subtract", document.getElementById('mo_oauth_icon_size'), 300, 0.7);
    moLoginIconSpaceIncrement(document.getElementById('mo_oauth_space_icon_plus'), "add", document.getElementById('mo_oauth_icon_margin'), 300, 0.7);
    moLoginIconSpaceIncrement(document.getElementById('mo_oauth_space_icon_minus'), "subtract", document.getElementById('mo_oauth_icon_margin'), 300, 0.7);
    moLoginWidthIncrement(document.getElementById('mo_oauth_width_plus'), "add", document.getElementById('mo_oauth_icon_width'), 300, 0.7);
    moLoginWidthIncrement(document.getElementById('mo_oauth_width_minus'), "subtract", document.getElementById('mo_oauth_icon_width'), 300, 0.7);
    moLoginCurveIncrement(document.getElementById('mo_oauth_curve_plus'), "add", document.getElementById('mo_oauth_icon_curve'), 300, 0.7);
    moLoginCurveIncrement(document.getElementById('mo_oauth_curve_minus'), "subtract", document.getElementById('mo_oauth_icon_curve'), 300, 0.7);