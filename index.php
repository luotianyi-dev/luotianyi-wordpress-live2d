<?php
/*
Plugin Name: Wordpress LibreLive2D
Plugin URI: https://github.com/luotianyi-dev/wp-libre-live2d
Description: Open-source Wordpress Live2D Plugin
Version: 1.1.0.3
Author: Tianyi Network
Author URI: https://luotianyi.dev/
License: GPL2
*/

defined('ABSPATH') or exit;
define('LIVE2D_VERSION', '1.1.0-ng22.07.12+tianyi03');
define('LIVE2D_URL', plugins_url('', __FILE__));
define('LIVE2D_PATH', dirname(__FILE__));

add_action('admin_init', 'librelive2d_plugin_redirect');
function librelive2d_plugin_redirect()
{
    if (get_option('do_activation_redirect', false)) {
        delete_option('do_activation_redirect');
        wp_redirect(admin_url('options-general.php?page=librelive2d'));
    }
}

function librelive2d_register_plugin_settings_link($links)
{
    $settings_link = '<a href="options-general.php?page=librelive2d">设置</a>';
    array_unshift($links, $settings_link);
    return $links;
}
$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_{$plugin}", 'librelive2d_register_plugin_settings_link');

if (is_admin()) {
    add_action('admin_menu', 'librelive2d_menu');
}

function librelive2d_menu()
{
    add_options_page('LibreLive2D 控制面板', 'LibreLive2D 设置', 'administrator', 'librelive2d', 'librelive2d_pluginoptions_page');
}

function librelive2d_pluginoptions_page()
{
    require "option.php";
}

// 写入默认设置
require_once(LIVE2D_PATH . '/default_options.php');
if (!get_option('live2d_main_color'))  { update_option('live2d_main_color',  LIVE2D_DEFAULT_MAIN_COLOR); }
if (!get_option('live2d_text_color'))  { update_option('live2d_text_color',  LIVE2D_DEFAULT_TEXT_COLOR); }
if (!get_option('live2d_lyric'))       { update_option('live2d_lyric',       LIVE2D_DEFAULT_LYRIC);      }
if (!get_option('live2d_special_tip')) { update_option('live2d_special_tip', LIVE2D_DEFAULT_SP_TIP);     }
if (!get_option('live2d_model'))       { update_option('live2d_model',       LIVE2D_DEFAULT_MODEL);      }

// 写入默认文件
function librelive2d_ensure_data_created($filename) {
    if (!file_exists(LIVE2D_PATH . '/data')) mkdir(LIVE2D_PATH . '/data');
    if (!file_exists(LIVE2D_PATH . "/data/$filename")) file_put_contents(LIVE2D_PATH . "/data/$filename", file_get_contents(LIVE2D_PATH . "/data_defaults/$filename"));
}
librelive2d_ensure_data_created("songs.json");
librelive2d_ensure_data_created("lyric.json");
librelive2d_ensure_data_created("custom_messages.json");

require LIVE2D_PATH . '/main.php';
?>
