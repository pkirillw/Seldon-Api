<?php
/**
 * Created by PhpStorm.
 * User: pkirillw
 * Date: 14.10.18
 * Time: 2:47
 */

namespace Seldon;

use Seldon\Exceptions\SeldonException;

class SeldonApiClass
{
    /**
     *
     */
    const HTTP_HEADER = array('Content-Type: application/x-www-form-urlencoded', 'Connection: keep-alive'),
        COOKIE_FILE = 'cookie.txt',
        COOKIE_JAR = 'cookie.txt',
        SERVER_URL = 'https://basis.myseldon.com/api/rest/';
    /**
     * @var array
     */
    private $personalUser = array(
        'UserName' => '',
        'Password' => ''
    );

    /**
     * @var array
     */
    private $universalUser = array(
        'UserName' => '',
        'Password' => ''
    );

    private $proxyUrl = '';

    private $useProxy = false;

    private $isAssoc = false;


    /**
     * SeldonApiClass constructor.
     */
    public function __construct()
    {

    }

    /**
     * @return array
     */
    public function getPersonalUser()
    {
        return $this->personalUser;
    }

    /**
     * @param array $personalUser
     */
    public function setPersonalUser($personalUser)
    {
        $this->personalUser = $personalUser;
    }

    /**
     * @return array
     */
    public function getUniversalUser()
    {
        return $this->universalUser;
    }

    /**
     * @param array $universalUser
     */
    public function setUniversalUser($universalUser)
    {
        $this->universalUser = $universalUser;
    }

    /**
     * @return string
     */
    public function getProxyUrl()
    {
        return $this->proxyUrl;
    }

    /**
     * @param string $proxyUrl
     */
    public function setProxyUrl($proxyUrl)
    {
        if (!empty($proxyUrl)) {
            $this->useProxy = true;
        }
        $this->proxyUrl = $proxyUrl;
    }

    /**
     * @return bool
     */
    public function isAssoc()
    {
        return $this->isAssoc;
    }

    /**
     * @param bool $isAssoc
     */
    public function setIsAssoc($isAssoc)
    {
        $this->isAssoc = $isAssoc;
    }

    /**
     * @param $username
     * @param $password
     * @return bool
     */
    public function login($username, $password)
    {
        $params = [
            'UserName' => $username,
            'Password' => $password
        ];
        $this->call('login', 'POST', $params);
        return true;
    }

    /**
     * @return bool
     */
    public function logout()
    {
        $this->call('logout', 'GET');
        return true;
    }

    /**
     * @param $url
     * @param $type
     * @param string $params
     * @return mixed
     */
    public function call($url, $type, $params = "")
    {
        if ($this->useProxy) {
            $url = $this->proxyUrl . $url;
        } else {
            $url = self::SERVER_URL . $url;
        }
        if (!empty($params) && $type == 'GET') {
            $url .= '?' . http_build_query($params);
        }
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $type);

        if (!empty($params) && $type == 'POST') {
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
        }

        curl_setopt($curl, CURLOPT_HTTPHEADER, self::HTTP_HEADER);
        curl_setopt($curl, CURLOPT_COOKIEFILE, dirname(__FILE__) . '/' . self::COOKIE_FILE);
        curl_setopt($curl, CURLOPT_COOKIEJAR, dirname(__FILE__) . '/' . self::COOKIE_JAR);
        $out = curl_exec($curl);

        $out = json_decode($out, true);
        curl_close($curl);
        if ($out['status']['methodStatus'] == 'Error') {
            throw new SeldonException($out['status']['paramsCheckError']['name'], $out['status']['paramsCheckError']['code'], $out);
        }
        return $out;
    }


    /**
     * @return bool
     */
    public function loginUni()
    {
        return $this->login($this->universalUser['UserName'], $this->universalUser['Password']);
    }

    /**
     * @return bool
     */
    public function loginPer()
    {
        return $this->login($this->personalUser['UserName'], $this->personalUser['Password']);
    }


}