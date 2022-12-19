<?php
defined('ABSPATH') or exit;

if ($_POST['update_pluginoptions'] == 'true') {
    live2d_options_update();
    echo '<div id="message" class="updated"><h4>设置已成功保存</a></h4></div>';
}

$models = [];
foreach (scandir(LIVE2D_PATH . '/model') as $model) {
    if (!(substr($model, 0, 1) == '.')) $models []= $model;
}

function plugin_editor_link($name) {
    $slug = basename(LIVE2D_PATH);
    $file = urlencode("$slug/data/$name.json");
    $plugin = urlencode("$slug/index.php");
    return "/wp-admin/plugin-editor.php?file=$file&plugin=$plugin";
}
?>
<style>
    input[type='color']{
        width: 25px;
        height: 25px;
        padding: .1px 2px;
    }
    textarea{
        width: 60%;
        height: 230px;
    }
</style>
<div class="wrap">
<h2>LibreLive2D 设置</h2>
<form method="POST" action="">
<input type="hidden" name="update_pluginoptions" value="true" />
    <div style="margin-left: 50px">
        <p><input type="color" name="main-color" id="main-color" value="<?php echo get_option('live2d_main_color'); ?>" /> 对话框主体颜色</p>
        <p><input type="color" name="text-color" id="text-color" value="<?php echo get_option('live2d_text_color'); ?>" /> 对话框文字颜色</p>
        <p><input type="checkbox" name="lyric" id="lyric" <?php if (get_option('live2d_lyric') == 'true') echo 'checked'; ?> /> 歌词显示</p>
        <p><input type="checkbox" name="special-tip" id="special-tip" <?php if (get_option('live2d_special_tip') == 'true') echo 'checked'; ?> /> 特殊显示</p>
        <p>模型：<select name="model" id="model">
            <?php foreach($models as $model) { ?>
                <option value="<?php echo $model; ?>" <?php if($model === get_option('live2d_model')) { echo 'selected="selected"'; } ?>>
                    <?php echo $model; ?>
                </option>
            <?php } ?>
        </select></p>
    </div>
    <input type="submit" class="button-primary" value="保存设置" style="margin: 20px 0;" />
    <br>LibreLive2D 版本 <?php echo LIVE2D_VERSION; ?> (修改自 PoiLive2D)
    <div>
        <a href="<?php echo plugin_editor_link('lyric'); ?>">编辑随机歌词列表</a> &emsp;
        <a href="<?php echo plugin_editor_link('songs'); ?>">编辑歌曲播放列表</a> &emsp;
        <a href="<?php echo plugin_editor_link('custom_messages'); ?>">编辑特殊显示配置文件</a> &emsp;
    </div>
</form>

<?php
function live2d_options_update()
{
    update_option('live2d_main_color',   $_POST['main-color']);
    update_option('live2d_text_color',   $_POST['text-color']);
    update_option('live2d_lyric',       ($_POST['lyric'] == 'on')   ? 'true' : 'false');
    update_option('live2d_special_tip', ($_POST['special-tip'] == 'on') ? 'true' : 'false');
    update_option('live2d_model',        $_POST['model']);
}
?>
