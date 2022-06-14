<?php
    defined('ABSPATH') or exit;
    add_action('wp_enqueue_scripts', 'live2d_scripts');
    function live2d_scripts()
    {
        wp_enqueue_script('live2d-jquery', LIVE2D_URL . '/assets/jquery.min.js', array('jquery'), LIVE2D_VERSION, false);
        if (!wp_is_mobile()) {
            wp_enqueue_style('live2d-base', LIVE2D_URL . '/assets/live2d.css', array(), LIVE2D_VERSION, 'all');
            wp_enqueue_script('live2d-base', LIVE2D_URL . '/assets/live2d.js', array('live2d-jquery'), LIVE2D_VERSION, true);
            wp_enqueue_script('live2d-message', LIVE2D_URL . '/assets/libre-live2d.js', array('live2d-jquery'), LIVE2D_VERSION, true);
        }
    }

    add_action('wp_head', 'live2d_head');
    function live2d_head()
    {
        if (!wp_is_mobile()) {
            require_once(LIVE2D_PATH . '/default_options.php');
            $live2d_options = array(
                "lyric"      => get_option('live2d_lyric') == "true",
                "specialTip" => get_option('live2d_special_tip') == "true",
                "mainColor"  => get_option('live2d_main_color') ? get_option('live2d_main_color') : LIVE2D_DEFAULT_MAIN_COLOR,
                "textColor"  => get_option('live2d_text_color') ? get_option('live2d_text_color') : LIVE2D_DEFAULT_TEXT_COLOR,
                "model"      => get_option('live2d_model') ? get_option('live2d_model') : LIVE2D_DEFAULT_MODEL,
                "baseUrl"    => LIVE2D_URL
            );
            ?><script>var LIVE2D_OPTIONS = <?php echo json_encode($live2d_options); ?>;</script>
            <?php
            $maincolor = hex2rgb($live2d_options["mainColor"]);
            $textcolor = hex2rgb($live2d_options["textColor"]);
            ?>
            <style>
            /*LibreLive2D PHP Generated Style*/
            .message{
                border-color: rgba(<?=$maincolor?>,.4);
                background-color:rgba(<?=$maincolor?>,.75);
                box-shadow:0 3px 15px 2px rgba(<?=$maincolor?>,.75);
                color:rgba(<?=$textcolor?>,.6);
            }
            
            .hide-button,.switch-button,.sing-button{
                border-color:rgba(<?=$maincolor?>,.4);
                background:rgba(<?=$maincolor?>,.2);
                box-shadow:0 3px 15px 2px rgba(<?=$maincolor?>,.4);
                color:rgba(<?=$textcolor?>,.6);
            }
            .hide-button:hover,.switch-button:hover,.sing-button:hover{
                border-color:rgba(<?=$maincolor?>,.6);
                background:rgba(<?=$maincolor?>,.4);
                color:rgba(<?=$textcolor?>,.8);
            }
            </style>
            <?php
        }
    }

    add_action('wp_footer', 'live2d_footer');
    function live2d_footer()
    {
        if (!wp_is_mobile()) {
            ?>
            <div id="landlord">
                <div class="message" style="opacity:0"></div>
                <canvas id="live2d" width="280" height="250" class="live2d" style="opacity:0;"></canvas>
                <div class="hide-button">隐藏</div>
                <!-- <div class="switch-button">变装</div> -->
				<div id="sing"></div>
				<div class="sing-button" id="sing-button">唱歌</div>
            </div>
        <?php
        }
    }

    function hex2rgb($hexColor)
    {
        $color = str_replace('#', '', $hexColor);
        if (strlen($color) > 3) {
            $rgb = (string)(hexdec(substr($color, 0, 2))).','.(string)(hexdec(substr($color, 2, 2))).','.(string)(hexdec(substr($color, 4, 2)));
        } else {
            $color = $hexColor;
            $r = substr($color, 0, 1) . substr($color, 0, 1);
            $g = substr($color, 1, 1) . substr($color, 1, 1);
            $b = substr($color, 2, 1) . substr($color, 2, 1);
            $rgb = (string)hexdec($r).','.(string)hexdec($g).','.(string)hexdec($b);
        }
        return $rgb;
    }
?>