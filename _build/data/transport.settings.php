<?php
/**
 * systemSettings transport file for ipcountryredirect extra
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
/* @var xPDOObject[] $systemSettings */


$systemSettings = array();

$systemSettings[1] = $modx->newObject('modSystemSetting');
$systemSettings[1]->fromArray(array (
  'key' => 'ipcr.apikey',
  'value' => '',
  'xtype' => 'textfield',
  'namespace' => 'ipcountryredirect',
  'area' => 'API',
  'name' => 'API-Key',
  'description' => 'Optional API-Key. Get your Key at: <a href="https://www.iplocate.io/" target="_blank">https://www.iplocate.io/</a>',
), '', true, true);
$systemSettings[2] = $modx->newObject('modSystemSetting');
$systemSettings[2]->fromArray(array (
  'key' => 'ipcr.anonymizeip',
  'value' => '0',
  'xtype' => 'combo-boolean',
  'namespace' => 'ipcountryredirect',
  'area' => 'Global Settings',
  'name' => 'IP anonymize',
  'description' => 'Anonymize IP-Address',
), '', true, true);
return $systemSettings;
