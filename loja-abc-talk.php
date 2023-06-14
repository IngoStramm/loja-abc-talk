<?php

/**
 * Plugin Name: Loja ABC Talk
 * Plugin URI: https://agencialaf.com
 * Description: Descrição do Loja ABC Talk.
 * Version: 0.0.8
 * Author: Ingo Stramm
 * Text Domain: labct
 * License: GPLv2
 */

defined('ABSPATH') or die('No script kiddies please!');

define('LABCT_DIR', plugin_dir_path(__FILE__));
define('LABCT_URL', plugin_dir_url(__FILE__));

function labct_debug($debug)
{
    echo '<pre>';
    var_dump($debug);
    echo '</pre>';
}

require_once 'tgm/tgm.php';
require_once 'classes/classes.php';
require_once 'scripts.php';
require_once 'custom-post-type.php';
require_once 'cmb2.php';
require_once 'function.php';
require_once 'shortcode.php';

require 'plugin-update-checker-4.10/plugin-update-checker.php';
$updateChecker = Puc_v4_Factory::buildUpdateChecker(
    'https://raw.githubusercontent.com/IngoStramm/loja-abc-talk/main/info.json',
    __FILE__,
    'labct'
);
