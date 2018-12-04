<?php
/***************************************************************************
 *                                                                          *
 *   (c) 2004 Vladimir V. Kalynyak, Alexey V. Vinokurov, Ilya M. Shalnev    *
 *                                                                          *
 * This  is  commercial  software,  only  users  who have purchased a valid *
 * license  and  accept  to the terms of the  License Agreement can install *
 * and use this program.                                                    *
 *                                                                          *
 ****************************************************************************
 * PLEASE READ THE FULL TEXT  OF THE SOFTWARE  LICENSE   AGREEMENT  IN  THE *
 * "copyright.txt" FILE PROVIDED WITH THIS DISTRIBUTION PACKAGE.            *
 ****************************************************************************/
 
namespace Tygh\Replain;

class BuildUrl
{
    const PUNYCODE_PREFIX = 'xn--';
    
    protected $domain = '';
    protected $punycoded_domain = '';
    public $prepared_url = '';
    
    public function __construct($domain)
    {
        $this->domain = $domain;
    }
    
    public function punyEncode()
    {
        $domain = $this->domain;

        if ($domain && !self::isPunycoded($domain)) {
            try {
                $idn = new \Net_IDNA2();
                $this->setPunycoded($idn->encode($domain));
            } catch (\InvalidArgumentException $e) {}
        }
        
        return true;
    }    
    
    protected static function isPunycoded($domain)
    {
        $has_prefix = strpos($domain, self::PUNYCODE_PREFIX) === 0;
        $has_content = strpos($domain, '.' . self::PUNYCODE_PREFIX) !== false;

        return $has_prefix || $has_content;
    }
    
    public function getPunycoded()
    {
        return $this->punycoded_domain;
    }
    
    protected function setPunycoded($url)
    {
        $this->punycoded_domain = $url;
    }
    
    public function prepareDomain()
    {
        $domain = $this->punycoded_domain? $this->punycoded_domain : $this->domain;
        $this->prepared_url = preg_replace('/\./', '_', $domain);
    }
    
    public function getPreparedUrl()
    {
        return $this->prepared_url;
    }
}