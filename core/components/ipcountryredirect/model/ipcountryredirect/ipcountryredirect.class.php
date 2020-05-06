<?php
/*
 * ipCountryRedirect
 *
 * A ip-address based country redirector
 *
 *
 * @author Jan Dähne, Quadro <jan.daehne@quadro-system.de>
 */

class ipCountryRedirect {


    /**
     * ipCountryRedirect constructor
     *
     * @param MODX $modx A reference to the MODX instance.
     * @param array $options An array of options. Optional.
     */
     public function __construct(MODX $modx, array $config = array())
    {
        // init modx
        $this->modx = $modx;

        // system settings
        $this->apikey = $this->modx->getOption('ipcr.apikey');
        $this->anonymizeip = $this->modx->getOption('ipcr.anonymizeip');
    }


    // get ip
    public function getIP()
    {
        // get the client ip address
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = false;

        // anonymize ip if set in system settings
        if ($this->anonymizeip == true) {
            $ipaddress = $this->anonymizeIP($ipaddress);
        }

        return $ipaddress;
    }



    // anonymize ip
    public function anonymizeIP($ipaddress) {
        $packedAddress = inet_pton($ipaddress);
        $ipv4NetMask = "255.255.255.0";
        $ipv6NetMask = "ffff:ffff:ffff:ffff:0000:0000:0000:0000";

        if (strlen($packedAddress) == 4) {
            return inet_ntop(inet_pton($ipaddress) & inet_pton($ipv4NetMask));
        } elseif (strlen($packedAddress) == 16) {
            return inet_ntop(inet_pton($ipaddress) & inet_pton($ipv6NetMask));
        }

        return false;
    }




    // get context countries settings
    public function getContextsCountries()
    {
        // get all contexts
        $contextKeys = array();
        $query = $this->modx->newQuery('modContext', array('key:NOT IN' => array('mgr')));
        $query->select($this->modx->getSelectColumns('modContext', 'modContext', '', array('key')));

        if ($query->prepare() && $query->stmt->execute()) {
            $contextKeys = $query->stmt->fetchAll(PDO::FETCH_COLUMN);
        }

        // get all defined countries for contexts from context setting "ipcr.countries"
        $countries = array();
        foreach($contextKeys as $key) {
            $ctx = $this->modx->getContext($key);

            $siteStart = explode(',', $ctx->getOption('ipcr.countries', null, 'default'));

            if (is_array($siteStart)) {
                foreach ($siteStart as $country) {
                    if (empty($country)) continue;
                    $countries[$country] = $ctx->getOption('site_start', null, 'default');
                }
            }
        }

        return $countries;
    }


    // redirect to default context
    public function redirectToDefaultContext()
    {
        $defaultContext = $this->modx->getOption('default_context');

        if ($defaultContext == $this->modx->context->key) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, '[ipCountryRedirect] Default context is the same context as current context. (default:' . $defaultContext . ' / current:' . $this->modx->context->key . ')');
            return;
        }

        $ctx = $this->modx->getContext($defaultContext);
        $rid = $ctx->getOption('site_start', null, 'default');
        $url = $this->modx->makeUrl($rid, '', array('cc' => 'default'));
        $this->modx->sendRedirect($url);
    }


    // get user country-code
    public function getUserCountryCode()
    {
        $ipaddress = $this->getIP();

        // get location infos via api
        $apikey = (!empty($apikey)) ? '?apikey=' . $apikey : '';
        $res = @file_get_contents('https://www.iplocate.io/api/lookup/' . $ipaddress . $apikey);
        $res = json_decode($res, true);

        // if country-code is empty redirect to default context
        if (empty($res['country_code'])) {
            return;
        }

        return strtolower($res['country_code']);
    }


    // redirect to context
    public function redirectToContext($countryCode, $countries)
    {
        // check if country-code is set in any context
        $rid = $countries[strtolower($countryCode)];

        if (empty($rid)) {
            return;
        }

        $url = $this->modx->makeUrl($rid);
        $this->modx->sendRedirect($url);
    }

}
