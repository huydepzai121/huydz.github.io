<?php

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

if (!nv_function_exists('nv_weather_block')) {

    function nv_weather_block($block_config) {
        global $db, $module_data, $global_config;

        $block_theme = (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/blocks/global.block_weather.tpl'))
            ? $global_config['site_theme']
            : 'default';

        $xtpl = new XTemplate('global.block_weather.tpl', NV_ROOTDIR . '/themes/' . $block_theme . '/blocks');

        $sql = "SELECT " . NV_PREFIXLANG . "_" . $module_data . ".id, " .
            NV_PREFIXLANG . "_" . $module_data . "_city.name as city_name, date_forecast, " .
            NV_PREFIXLANG . "_" . $module_data . "_time_period.time_period, description, wind_speed, " .
            "temperature_note, temperature_value, weight, avatar " .
            "FROM " . NV_PREFIXLANG . "_" . $module_data . " " .
            "INNER JOIN " . NV_PREFIXLANG . "_" . $module_data . "_city ON " .
            NV_PREFIXLANG . "_" . $module_data . ".id_city=" . NV_PREFIXLANG . "_" . $module_data . "_city.id " .
            "INNER JOIN " . NV_PREFIXLANG . "_" . $module_data . "_time_period ON " .
            NV_PREFIXLANG . "_" . $module_data . ".id_time_period=" . NV_PREFIXLANG . "_" . $module_data . "_time_period.id " .
            "ORDER BY " . NV_PREFIXLANG . "_" . $module_data . ".weight ASC";

        $stmt = $db->prepare($sql);
        $stmt->execute();

        while ($row = $stmt->fetch()) {
            $xtpl->assign('WEATHER', $row);
            $xtpl->parse('main.loop');
        }

        $xtpl->parse('main');
        return $xtpl->text('main');
    }
}

if (defined('NV_SYSTEM')) {
    global $site_mods, $module_name, $block_config;
    $content = nv_weather_block($block_config);
}
