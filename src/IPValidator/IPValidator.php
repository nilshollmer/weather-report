<?php

namespace Nihl\IPValidator;

class IPValidator
{
    /**
     * Fetch users IP-address
     *
     * @param array $server $_SERVER-array or any array with
     *
     * @return string $userIP User IP-address
     */
    public function getUserIP($server)
    {
        if (!empty($server['HTTP_CLIENT_IP'])) {
            $userIP = $server['HTTP_CLIENT_IP'];
        } elseif (!empty($server['HTTP_X_FORWARDED_FOR'])) {
            $userIP = $server['HTTP_X_FORWARDED_FOR'];
        } else {
            $userIP = $server['REMOTE_ADDR'];
        }
        return $userIP;
    }


    /**
     * Use regular expression to check if input matches ip4 or ip6 syntax
     *
     * @param string
     *
     * @return string
     */
    public function pregMatchIP($ipToValidate)
    {
        $ip4regex = "((^\s*((([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]))\s*$))";
        $ip6regex = "((^\s*((([0-9A-Fa-f]{1,4}:){7}([0-9A-Fa-f]{1,4}|:))|(([0-9A-Fa-f]{1,4}:){6}(:[0-9A-Fa-f]{1,4}|((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){5}(((:[0-9A-Fa-f]{1,4}){1,2})|:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){4}(((:[0-9A-Fa-f]{1,4}){1,3})|((:[0-9A-Fa-f]{1,4})?:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){3}(((:[0-9A-Fa-f]{1,4}){1,4})|((:[0-9A-Fa-f]{1,4}){0,2}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){2}(((:[0-9A-Fa-f]{1,4}){1,5})|((:[0-9A-Fa-f]{1,4}){0,3}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){1}(((:[0-9A-Fa-f]{1,4}){1,6})|((:[0-9A-Fa-f]{1,4}){0,4}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(:(((:[0-9A-Fa-f]{1,4}){1,7})|((:[0-9A-Fa-f]{1,4}){0,5}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:)))(%.+)?\s*$))";

        // Check if $ip matches ip4 syntax
        if (preg_match($ip4regex, $ipToValidate)) {
            return "ip4";
        }

        // Check if $ip matches ip6 syntax
        if (preg_match($ip6regex, $ipToValidate)) {
            return "ip6";
        }

        return "";
    }


    /**
     * Check if incomping IP-address is valid
     *
     * @param string
     *
     * @return array
     */
    public function validateIP($ipToValidate)
    {
        $type = $this->pregMatchIP($ipToValidate);
        $match = $type ? true : false;
        $domain = $match ? gethostbyaddr($ipToValidate) : null;

        return [
            "ip" => $ipToValidate,
            "message" => $match ? "Adressen är en giltig ${type}-adress!" : "Adressen är ogiltig!",
            "match" => $match,
            "type" => $type,
            "domain" => $domain
        ];
    }
}
