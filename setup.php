<?php

/**
 * -------------------------------------------------------------------------
 * RoleCategoriesItil plugin for GLPI
 * Copyright (C) 2024 by the RoleCategoriesItil Development Team.
 * -------------------------------------------------------------------------
 *
 * MIT License
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * --------------------------------------------------------------------------
 */

define('PLUGIN_ROLECATEGORIESITIL_VERSION', '0.0.1');

// Minimal GLPI version, inclusive
define("PLUGIN_ROLECATEGORIESITIL_MIN_GLPI_VERSION", "10.0.0");
// Maximum GLPI version, exclusive
define("PLUGIN_ROLECATEGORIESITIL_MAX_GLPI_VERSION", "10.0.99");

/**
 * Init hooks of the plugin.
 * REQUIRED
 *
 * @return void
 */
function plugin_init_rolecategoriesitil()
{
    global $PLUGIN_HOOKS;
    Plugin::registerClass('PluginRolecategoriesitilConfig', ['addtabon' => 'Profile']);
    Plugin::registerClass('PluginRolecategoriesitilConfigitil', ['addtabon' => 'ITILCategory']);
    $PLUGIN_HOOKS['post_init']['rolecategoriesitil'] = ['PluginRolecategoriesitilItilcategories' , 'init'];
  //  $PLUGIN_HOOKS['config_page']['rolecategoriesitil'] = 'front/profile.form.php';
    //перехватываем событие после добавления категории
    $PLUGIN_HOOKS['item_add']['rolecategoriesitil']['ITILCategory']  = [
         'PluginRolecategoriesitilConfig',
         'addITILCategory'
     ];
    $PLUGIN_HOOKS['csrf_compliant']['rolecategoriesitil'] = true;
}


/**
 * Get the name and the version of the plugin
 * REQUIRED
 *
 * @return array
 */
function plugin_version_rolecategoriesitil()
{
    return [
        'name'           => 'Видимость категорий',
        'version'        => PLUGIN_ROLECATEGORIESITIL_VERSION,
        'author'         => '<a href="#">Yahin RA\'</a>',
        'license'        => 'GNUv3',
        'homepage'       => 'https://github.com/romannadym/RoleCategoriesItil',
        'requirements'   => [
            'glpi' => [
                'min' => PLUGIN_ROLECATEGORIESITIL_MIN_GLPI_VERSION,
                'max' => PLUGIN_ROLECATEGORIESITIL_MAX_GLPI_VERSION,
            ]
        ]
    ];
}

/**
 * Check pre-requisites before install
 * OPTIONNAL, but recommanded
 *
 * @return boolean
 */
function plugin_rolecategoriesitil_check_prerequisites()
{
    return true;
}

/**
 * Check configuration process
 *
 * @param boolean $verbose Whether to display message on failure. Defaults to false
 *
 * @return boolean
 */
function plugin_rolecategoriesitil_check_config($verbose = false)
{
    if (true) { // Your configuration check
        return true;
    }

    if ($verbose) {
        echo __('Installed / not configured', 'rolecategoriesitil');
    }
    return false;
}
