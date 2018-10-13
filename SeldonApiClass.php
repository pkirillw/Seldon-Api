<?php
/**
 * Created by PhpStorm.
 * User: pkirillw
 * Date: 14.10.18
 * Time: 2:47
 */

class SeldonApiClass
{
    /**
     *
     */
    const HTTP_HEADER = array('Content-Type: application/x-www-form-urlencoded', 'Connection: keep-alive'),
        COOKIE_FILE = 'cookie.txt',
        COOKIE_JAR = 'cookie.txt',
        SERVER_URL = '';

    /**
     * @var array
     */


    /**
     * SeldonApi constructor.
     */
    public function __construct()
    {

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
        $this->api('login', 'POST', $params);
        return true;
    }

    /**
     * @return bool
     */
    public function logout()
    {
        $this->api('logout', 'GET');
        return true;
    }

    /**
     * @param $url
     * @param $type
     * @param string $params
     * @return mixed
     */
    public function api($url, $type, $params = "")
    {

        $url = self::SERVER_URL . $url;
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
        $out = curl_exec($curl);
        curl_close($curl);
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

    /**
     * @param $inn
     * @param $ogrnip
     * @return mixed
     */
    public function addToMyCompanies($inn, $ogrnip)
    {
        if (empty($inn)) {
            return $this->api('add_to_my_companies', 'GET', ['ogrn' => $ogrnip]);
        }
        return $this->api('add_to_my_companies', 'GET', ['inn' => $inn]);

    }

    /**
     * @param $inn
     * @return mixed
     */
    public function getCompanyCard($inn)
    {

        $data = $this->api('get_company_card', 'GET', ['inn' => $inn]);

        return $data;
    }

    /**
     * @param $inn
     * @return mixed
     */
    public function getCompanyOwners($inn)
    {

        $data = $this->api('get_company_owners', 'GET', ['inn' => $inn, 'history' => 1]);

        return $data;
    }

    /**
     * @param $inn
     * @return mixed
     */
    public function findPerson($inn)
    {

        $data = $this->api('find_person', 'GET', ['inn' => $inn]);

        return $data;
    }

    /**
     * @param $inn
     * @param $type
     * @return mixed|string
     */
    public function getCompanyCourtCases($inn, $type)
    {
        if ($type == 'company') {
            $data = $this->api('get_company_court_cases', 'GET', ['inn' => $inn, 'type' => 1, 'pageSize' => 100, 'pageIndex' => 1]);
        } elseif ($type == 'ip') {
            $data = $this->api('get_ip_court_cases', 'GET', ['ogrnip' => $inn, 'type' => 1, 'pageSize' => 100, 'pageIndex' => 1]);
        } else {
            return '';
        }


        return $data;
    }

    /**
     * @param $inn
     * @param $type
     * @return mixed|string
     */
    public function getCompanyBailiffs($inn, $type)
    {
        if ($type == 'company') {
            $data = $this->api('get_company_bailiffs', 'GET', ['inn' => $inn]);
        } elseif ($type == 'ip') {

            $data = $this->api('get_ip_bailiffs', 'GET', ['ogrnip' => $inn]);
        } else {
            return '';
        }


        return $data;
    }

    /**
     * @return mixed
     */
    public function getCompanyFinancialReportDates($inn)
    {
        $data = $this->api('get_company_financial_report_dates', 'GET', ['inn' => $inn, 'history' => 1]);
        return $data;
    }

    public function getCompanyAggregates($inn)
    {
        $data = $this->api('get_company_aggregates', 'GET', ['inn' => $inn]);
        return $data;
    }

    public function getCompanyFinancialReport($ogrn, $date)
    {
        $data = $this->api('get_company_financial_report', 'GET', ['ogrn' => $ogrn, 'date' => $date]);
        return $data;

    }
}