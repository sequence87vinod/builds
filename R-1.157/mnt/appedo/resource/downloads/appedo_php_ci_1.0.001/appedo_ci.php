<?php

/*
 * Author      : tech
 * Date        : 20-Mar-2015
 * Modified    : 04-Apr-2015
 * Description : Custom Instrumentation (CI)                                                              
 */

class Appedo {

    var $guid, $appedo_userdetails, $env, $agent_type, $send_data_to_server;
    var $ci_url = "@RUM_COLLECTOR_URL@/ciCollector";

    public function __construct($guid, $env, $send_data_to_server = 'Y') {
        $this->guid = $guid;
        $this->agent_type = "PHP";
        $this->env = $env;
        $this->send_data_to_server = strtoupper($send_data_to_server);

        if (!function_exists('curl_version') && $this->send_data_to_server === 'Y') {
            die('To execute the Appedo CI, enable the php_curl library / extension');
        }
    }

    /*
     * 	Used For Setting the Userdetails. The user can set any parameters
     *  @param ARRAY $params
     *  Example 
     *  array("user_name" => $user_name_from_session,
     *          "user_email_id" => $user_email_id)
     */

    public function setUserProps($params) {

        $this->appedo_userdetails = $params;
    }

    /*
     * 	Used to send the details to collector server.
     * 	The params are received as array and converted to JSON before posting to the server
     *  @param STRING $event_name
     *  @param STRING $event_method
     *  @param ARRAY  $params
     */

    public function triggerEvent($event_name, $event_method, $params) {
        try {
            /*
              xmlhttp.send("ci_params="+JSON.stringify(params)+"&user_params="+JSON.stringify(appedo_userDetails)+"&guid="+APPEDO_UI_GUID+"&agent_type="+AGENT_TYPE+"&env="+EVN
              +"&eventName="+event_name+"&eventMethod="+event_method+"&browserName="+user.browser.name+"&deviceType="
              +user.device.type+"&OS="+user.os.name+"&devicename="+user.device.name+"&merchantname="+user.device.family);
             */
            if ($this->send_data_to_server === 'Y') {

                $browser_detail = $this->parse_user_agent();

                $postparams = 'ci_params=' . json_encode($params)
                        . '&user_params=' . json_encode($this->appedo_userdetails)
                        . '&guid=' . $this->guid
                        . '&agent_type=' . $this->agent_type
                        . '&env=' . $this->env
                        . '&eventName=' . $event_name
                        . '&eventMethod=' . $event_method
                        . '&browserName=' . $browser_detail['browser'] . ' ' . $browser_detail['version']
                        . '&deviceType=' . $browser_detail['device_type']
                        . '&OS=' . $browser_detail['os_platform']
                        . '&devicename='
                        . '&merchantname=';

                $curlHandler = curl_init();
                curl_setopt($curlHandler, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curlHandler, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
                curl_setopt($curlHandler, CURLOPT_POST, true);
                curl_setopt($curlHandler, CURLOPT_POSTFIELDS, $postparams);
                curl_setopt($curlHandler, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curlHandler, CURLOPT_TIMEOUT, 100);
                curl_setopt($curlHandler, CURLOPT_URL, $this->ci_url);

                $curlResponse = curl_exec($curlHandler);
                $curlError = curl_error($curlHandler);
                $curlInfo = curl_getinfo($curlHandler, CURLINFO_HTTP_CODE);

                curl_close($curlHandler);
                //if any error then return false;            
                if ($curlError) {
                    return false;
                } else {
                    return true;
                }
            }
        } catch (Exception $ex) {
            //Error message is sent to PHP System log. Check the error log directive set in php.ini
            error_log('Curl error: ' . $ex, 0);
        }
    }

    /*
     * 	Used to in the starting of a transaction
     *  reassigns the recevied event name and event method to send to completetransaction method
     *  @param STRING $event_name
     *  @param STRING $event_method
     *  @param ARRAY  $params
     */

    public function startTransaction($event_name, $event_method, $params) {

        if ($this->send_data_to_server === 'Y') {

            $params['eventName'] = $event_name;
            $params['eventMethod'] = $event_method;

            $params['eventStartTime'] = time(); //current Unix timestamp        

            return $params;
        }
    }

    /*
     * 	Used to in the completion of a transaction and submit the same to collector    
     *  @param ARRAY  $params
     */

    public function completeTransaction($params) {

        if ($this->send_data_to_server === 'Y') {
            $event_name = $params['eventName'];
            $event_method = $params['eventMethod'];

            //Unset the event and method details from the array
            unset($params['eventName']);
            unset($params['eventMethod']);

            $params['eventEndTime'] = time(); //current Unix timestamp
            //Call the triggerEvent method to submit the collected info        
            return $this->triggerEvent($event_name, $event_method, $params);
        }
    }

    /**
     * Parses a user agent string into its important parts
     *
     * @param string|null $u_agent User agent string to parse or null. Uses $_SERVER['HTTP_USER_AGENT'] on NULL
     * @throws InvalidArgumentException on not having a proper user agent to parse.
     * @return string[] an array with browser, version and platform keys
     */
    function parse_user_agent($u_agent = null) {
        if (is_null($u_agent)) {
            if (isset($_SERVER['HTTP_USER_AGENT'])) {
                $u_agent = $_SERVER['HTTP_USER_AGENT'];
            } else {
                throw new \InvalidArgumentException('parse_user_agent requires a user agent');
            }
        }

        $platform = null;
        $browser = null;
        $version = null;
        $device_type = null;
        $os_platform = null;

        $empty = array('platform' => $platform, 'browser' => $browser, 'version' => $version, 'device_type' => $device_type, 'os_platform' => $os_platform);

        if (!$u_agent)
            return $empty;

        if (preg_match('/\((.*?)\)/im', $u_agent, $parent_matches)) {

            preg_match_all('/(?P<platform>BB\d+;|Android|CrOS|Tizen|iPhone|iPad|Linux|Macintosh|Windows(\ Phone)?|Silk|linux-gnu|BlackBerry|PlayBook|(New\ )?Nintendo\ (WiiU?|3DS)|Xbox(\ One)?)
				(?:\ [^;]*)?
				(?:;|$)/imx', $parent_matches[1], $result, PREG_PATTERN_ORDER);

            $priority = array('Android', 'Xbox One', 'Xbox', 'Tizen');
            $result['platform'] = array_unique($result['platform']);
            if (count($result['platform']) > 1) {
                if ($keys = array_intersect($priority, $result['platform'])) {
                    $platform = reset($keys);
                } else {
                    $platform = $result['platform'][0];
                }
                $device_type = 'Mobile';
            } elseif (isset($result['platform'][0])) {
                $platform = $result['platform'][0];
                $device_type = 'Desktop';
            }
        }

        if ($platform == 'linux-gnu') {
            $platform = 'Linux';
        } elseif ($platform == 'CrOS') {
            $platform = 'Chrome OS';
        }

        preg_match_all('%(?P<browser>Camino|Kindle(\ Fire\ Build)?|Firefox|Iceweasel|Safari|MSIE|Trident|AppleWebKit|TizenBrowser|Chrome|
			Vivaldi|IEMobile|Opera|OPR|Silk|Midori|Edge|
			Baiduspider|Googlebot|YandexBot|bingbot|Lynx|Version|Wget|curl|
			NintendoBrowser|PLAYSTATION\ (\d|Vita)+)
			(?:\)?;?)
			(?:(?:[:/ ])(?P<version>[0-9A-Z.]+)|/(?:[A-Z]*))%ix', $u_agent, $result, PREG_PATTERN_ORDER);

        // If nothing matched, return null (to avoid undefined index errors)
        if (!isset($result['browser'][0]) || !isset($result['version'][0])) {
            if (!$platform && preg_match('%^(?!Mozilla)(?P<browser>[A-Z0-9\-]+)(/(?P<version>[0-9A-Z.]+))?([;| ]\ ?.*)?$%ix', $u_agent, $result)
            ) {
                return array('platform' => null, 'browser' => $result['browser'], 'version' => isset($result['version']) ? $result['version'] ? : null : null, 'device_type' => $device_type, 'os_platform' => $os_platform);
            }

            return $empty;
        }

        if (preg_match('/rv:(?P<version>[0-9A-Z.]+)/si', $u_agent, $rv_result)) {
            $rv_result = $rv_result['version'];
        }

        $browser = $result['browser'][0];
        $version = $result['version'][0];

        $find = function ( $search, &$key ) use ( $result ) {
            $xkey = array_search(strtolower($search), array_map('strtolower', $result['browser']));
            if ($xkey !== false) {
                $key = $xkey;

                return true;
            }

            return false;
        };

        $key = 0;
        $ekey = 0;
        if ($browser == 'Iceweasel') {
            $browser = 'Firefox';
        } elseif ($find('Playstation Vita', $key)) {
            $platform = 'PlayStation Vita';
            $browser = 'Browser';
        } elseif ($find('Kindle Fire Build', $key) || $find('Silk', $key)) {
            $browser = $result['browser'][$key] == 'Silk' ? 'Silk' : 'Kindle';
            $platform = 'Kindle Fire';
            if (!($version = $result['version'][$key]) || !is_numeric($version[0])) {
                $version = $result['version'][array_search('Version', $result['browser'])];
            }
        } elseif ($find('NintendoBrowser', $key) || $platform == 'Nintendo 3DS') {
            $browser = 'NintendoBrowser';
            $version = $result['version'][$key];
        } elseif ($find('Kindle', $key)) {
            $browser = $result['browser'][$key];
            $platform = 'Kindle';
            $version = $result['version'][$key];
        } elseif ($find('OPR', $key)) {
            $browser = 'Opera Next';
            $version = $result['version'][$key];
        } elseif ($find('Opera', $key)) {
            $browser = 'Opera';
            $find('Version', $key);
            $version = $result['version'][$key];
        } elseif ($find('Midori', $key)) {
            $browser = 'Midori';
            $version = $result['version'][$key];
        } elseif ($browser == 'MSIE' || ($rv_result && $find('Trident', $key)) || $find('Edge', $ekey)) {
            $browser = 'MSIE';
            if ($find('IEMobile', $key)) {
                $browser = 'IEMobile';
                $version = $result['version'][$key];
            } elseif ($ekey) {
                $version = $result['version'][$ekey];
            } else {
                $version = $rv_result ? : $result['version'][$key];
            }
        } elseif ($find('Vivaldi', $key)) {
            $browser = 'Vivaldi';
            $version = $result['version'][$key];
        } elseif ($find('Chrome', $key)) {
            $browser = 'Chrome';
            $version = $result['version'][$key];
        } elseif ($browser == 'AppleWebKit') {
            if (($platform == 'Android' && !($key = 0))) {
                $browser = 'Android Browser';
            } elseif (strpos($platform, 'BB') === 0) {
                $browser = 'BlackBerry Browser';
                $platform = 'BlackBerry';
            } elseif ($platform == 'BlackBerry' || $platform == 'PlayBook') {
                $browser = 'BlackBerry Browser';
            } elseif ($find('Safari', $key)) {
                $browser = 'Safari';
            } elseif ($find('TizenBrowser', $key)) {
                $browser = 'TizenBrowser';
            }

            $find('Version', $key);

            $version = $result['version'][$key];
        } elseif ($key = preg_grep('/playstation \d/i', array_map('strtolower', $result['browser']))) {
            $key = reset($key);

            $platform = 'PlayStation ' . preg_replace('/[^\d]/i', '', $key);
            $browser = 'NetFront';
        }

        $os_platform = "Unknown OS Platform";

        // Get the Operating System Platform

        if (preg_match('/windows|win32/i', $u_agent)) {

            $os_platform = 'Windows';

            if (preg_match('/windows nt 6.2/i', $u_agent)) {

                $os_platform .= " 8";
            } else if (preg_match('/windows nt 6.1/i', $u_agent)) {

                $os_platform .= " 7";
            } else if (preg_match('/windows nt 6.0/i', $u_agent)) {

                $os_platform .= " Vista";
            } else if (preg_match('/windows nt 5.2/i', $u_agent)) {

                $os_platform .= " Server 2003/XP x64";
            } else if (preg_match('/windows nt 5.1/i', $u_agent) || preg_match('/windows xp/i', $u_agent)) {

                $os_platform .= " XP";
            } else if (preg_match('/windows nt 5.0/i', $u_agent)) {

                $os_platform .= " 2000";
            } else if (preg_match('/windows me/i', $u_agent)) {

                $os_platform .= " ME";
            } else if (preg_match('/win98/i', $u_agent)) {

                $os_platform .= " 98";
            } else if (preg_match('/win95/i', $u_agent)) {

                $os_platform .= " 95";
            } else if (preg_match('/win16/i', $u_agent)) {

                $os_platform .= " 3.11";
            }
        } else if (preg_match('/macintosh|mac os x/i', $u_agent)) {

            $os_platform = 'Mac';

            if (preg_match('/macintosh/i', $u_agent)) {

                $os_platform .= " OS X";
            } else if (preg_match('/mac_powerpc/i', $u_agent)) {

                $os_platform .= " OS 9";
            }
        } else if (preg_match('/linux/i', $u_agent)) {

            $os_platform = "Linux";
        }

        // Override if matched

        if (preg_match('/iphone/i', $u_agent)) {

            $os_platform = "iPhone";
        } else if (preg_match('/android/i', $u_agent)) {

            $os_platform = "Android";
        } else if (preg_match('/blackberry/i', $u_agent)) {

            $os_platform = "BlackBerry";
        } else if (preg_match('/webos/i', $u_agent)) {

            $os_platform = "Mobile";
        } else if (preg_match('/ipod/i', $u_agent)) {

            $os_platform = "iPod";
        } else if (preg_match('/ipad/i', $u_agent)) {

            $os_platform = "iPad";
        }

        return array('platform' => $platform ? : null,
            'browser' => $browser ? : null,
            'version' => $version ? : null,
            'device_type' => $device_type ? : null,
            'os_platform' => $os_platform ? : null);
    }

}

?>