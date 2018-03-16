<?php
/**
 * contextSettings transport file for ipcountryredirect extra
 *
 * Copyright 2018 by Quadro - Jan Dähne <https://www.quadro-system.de>
 * Created on 03-16-2018
 *
 * @package ipcountryredirect
 * @subpackage build
 */

if (! function_exists('stripPhpTags')) {
    function stripPhpTags($filename) {
        $o = file_get_contents($filename);
        $o = str_replace('<' . '?' . 'php', '', $o);
        $o = str_replace('?>', '', $o);
        $o = trim($o);
        return $o;
    }
}
/* @var $modx modX */
/* @var $sources array */
/* @var xPDOObject[] $contextSettings */


$contextSettings = array();

$contextSettings[1] = $modx->newObject('modContextSetting');
$contextSettings[1]->fromArray(array (
  'context_key' => 'web',
  'key' => 'ipcr.countries',
  'name' => 'Redirect County-Codes',
  'description' => 'Comma separated list of county-codes that match this context. Example: de,at,ch',
  'namespace' => 'ipcountryredirect',
  'xtype' => 'textfield',
  'value' => '',
  'area' => '',
  'fk' => 'web',
), '', true, true);
return $contextSettings;
