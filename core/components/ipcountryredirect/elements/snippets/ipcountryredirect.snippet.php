<?php
/*
 * ipCountryRedirect
 *
 * A ip-address based country redirector
 *
 *
 * @author Jan Dähne, Quadro <jan.daehne@quadro-system.de>
 */


// load Class
$modelPath = $modx->getOption('core_path', null, MODX_CORE_PATH) . 'components/ipcountryredirect/model/ipcountryredirect/';
$modx->loadClass('ipCountryRedirect', $modelPath, true, true);


// init Class
$ipcr = new ipCountryRedirect($modx);

// get context countries settings
$countries = $ipcr->getContextsCountries();

if (empty($countries)) {
    $modx->log(modX::LOG_LEVEL_ERROR, '[ipCountryRedirect] No countries defined in context-settings (ipcr.countries).');
    $ipcr->redirectToDefaultContext();
    return;
}

// get users country code
$countryCode = $ipcr->getUserCountryCode();

if (empty($countryCode)) {
    $modx->log(modX::LOG_LEVEL_ERROR, '[ipCountryRedirect] Could not get countrycode from ip-address.');
    $ipcr->redirectToDefaultContext();
    return;
}

// redirect to context with set country-code
if (!$ipcr->redirectToContext($countryCode, $countries)) {
    //Could not find matching context/country-code.
    $ipcr->redirectToDefaultContext();
    return;
}

return;