<?php
require_once("Rest.inc.php");
ini_set('memory_limit', '1024M');
class API extends REST
{
    public $data = "";

    const DB_SERVER = "localhost";
    const DB_USER = "root";
    const DB_PASSWORD = "";
    const DBmobi = "ipadrbg";

    public function __construct()
    {
        parent::__construct();        // Init parent contructor
        $this->dbConnect();            // Initiate Database connection
    }

    /*
	*  Connect to Database
	*/
    private function dbConnect()
    {
        $this->mobi = new mysqli(self::DB_SERVER, self::DB_USER, self::DB_PASSWORD, self::DBmobi);
    }

    public function processApi()
    {
        $func = strtolower(trim(str_replace("/", "", $_REQUEST['x'])));
        if ((int)method_exists($this, $func) > 0)
            $this->$func();
        else
            $this->response('', 404);
    }

    private static function get_user_agent()
    {
        return  $_SERVER['HTTP_USER_AGENT'];
    }
    public static function get_ip()
    {
        $mainIp = '';
        if (getenv('HTTP_CLIENT_IP'))
            $mainIp = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $mainIp = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $mainIp = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $mainIp = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $mainIp = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $mainIp = getenv('REMOTE_ADDR');
        else
            $mainIp = 'UNKNOWN';
        return $mainIp;
    }
    public static function get_os()
    {
        $user_agent = self::get_user_agent();
        $os_platform    =   "Unknown OS Platform";
        $os_array       =   array(
            '/windows nt 10/i'         =>  'Windows 10',
            '/windows nt 6.3/i'     =>  'Windows 8.1',
            '/windows nt 6.2/i'     =>  'Windows 8',
            '/windows nt 6.1/i'     =>  'Windows 7',
            '/windows nt 6.0/i'     =>  'Windows Vista',
            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
            '/windows nt 5.1/i'     =>  'Windows XP',
            '/windows xp/i'         =>  'Windows XP',
            '/windows nt 5.0/i'     =>  'Windows 2000',
            '/windows me/i'         =>  'Windows ME',
            '/win98/i'              =>  'Windows 98',
            '/win95/i'              =>  'Windows 95',
            '/win16/i'              =>  'Windows 3.11',
            '/macintosh|mac os x/i' =>  'Mac OS X',
            '/mac_powerpc/i'        =>  'Mac OS 9',
            '/linux/i'              =>  'Linux',
            '/ubuntu/i'             =>  'Ubuntu',
            '/iphone/i'             =>  'iPhone',
            '/ipod/i'               =>  'iPod',
            '/ipad/i'               =>  'iPad',
            '/android/i'            =>  'Android',
            '/blackberry/i'         =>  'BlackBerry',
            '/webos/i'              =>  'Mobile'
        );
        foreach ($os_array as $regex => $value) {
            if (preg_match($regex, $user_agent)) {
                $os_platform    =   $value;
            }
        }
        return $os_platform;
    }
    public static function  get_browser()
    {
        $user_agent = self::get_user_agent();
        $browser        =   "Unknown Browser";
        $browser_array  =   array(
            '/msie/i'       =>  'Internet Explorer',
            '/Trident/i'    =>  'Internet Explorer',
            '/firefox/i'    =>  'Firefox',
            '/safari/i'     =>  'Safari',
            '/chrome/i'     =>  'Chrome',
            '/edge/i'       =>  'Edge',
            '/opera/i'      =>  'Opera',
            '/netscape/i'   =>  'Netscape',
            '/maxthon/i'    =>  'Maxthon',
            '/konqueror/i'  =>  'Konqueror',
            '/ubrowser/i'   =>  'UC Browser',
            '/mobile/i'     =>  'Handheld Browser'
        );
        foreach ($browser_array as $regex => $value) {
            if (preg_match($regex, $user_agent)) {
                $browser    =   $value;
            }
        }
        return $browser;
    }
    public static function  get_device()
    {
        $tablet_browser = 0;
        $mobile_browser = 0;
        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
            $tablet_browser++;
        }
        if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
            $mobile_browser++;
        }
        if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']), 'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
            $mobile_browser++;
        }
        $mobile_ua = strtolower(substr(self::get_user_agent(), 0, 4));
        $mobile_agents = array(
            'w3c ', 'acs-', 'alav', 'alca', 'amoi', 'audi', 'avan', 'benq', 'bird', 'blac',
            'blaz', 'brew', 'cell', 'cldc', 'cmd-', 'dang', 'doco', 'eric', 'hipt', 'inno',
            'ipaq', 'java', 'jigs', 'kddi', 'keji', 'leno', 'lg-c', 'lg-d', 'lg-g', 'lge-',
            'maui', 'maxo', 'midp', 'mits', 'mmef', 'mobi', 'mot-', 'moto', 'mwbp', 'nec-',
            'newt', 'noki', 'palm', 'pana', 'pant', 'phil', 'play', 'port', 'prox',
            'qwap', 'sage', 'sams', 'sany', 'sch-', 'sec-', 'send', 'seri', 'sgh-', 'shar',
            'sie-', 'siem', 'smal', 'smar', 'sony', 'sph-', 'symb', 't-mo', 'teli', 'tim-',
            'tosh', 'tsm-', 'upg1', 'upsi', 'vk-v', 'voda', 'wap-', 'wapa', 'wapi', 'wapp',
            'wapr', 'webc', 'winw', 'winw', 'xda ', 'xda-'
        );
        if (in_array($mobile_ua, $mobile_agents)) {
            $mobile_browser++;
        }
        if (strpos(strtolower(self::get_user_agent()), 'opera mini') > 0) {
            $mobile_browser++;
            //Check for tablets on opera mini alternative headers
            $stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA']) ? $_SERVER['HTTP_X_OPERAMINI_PHONE_UA'] : (isset($_SERVER['HTTP_DEVICE_STOCK_UA']) ? $_SERVER['HTTP_DEVICE_STOCK_UA'] : ''));
            if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
                $tablet_browser++;
            }
        }
        if ($tablet_browser > 0) {
            // do something for tablet devices
            return 'Tablet';
        } else if ($mobile_browser > 0) {
            // do something for mobile devices
            return 'Mobile';
        } else {
            // do something for everything else
            return 'Computer';
        }
    }

    // by age
    private function getCountByAge()
    {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $agefrom = (int)$this->_request["agefrom"];
        $ageto = (int)$this->_request["ageto"];
        $date = (string)$this->_request["date"];

        $stmt = "SELECT
            cx.AppDateSet AS appt_set,
            COUNT(cx.ClinixRID) AS clinix_count
            FROM clinix cx
            WHERE cx.AppDateSet = '$date' 
            AND (cx.AppDateAge BETWEEN '$agefrom' AND '$ageto');";
        $qry = $this->mobi->query($stmt) or die($this->mobi->error . __LINE__);

        if ($qry->num_rows > 0) {
            while ($row = $qry->fetch_assoc()) {
                $result[] = $row;
            }
            $this->response($this->json($result), 200); // send user details
        }
        $this->response('', 204);
    }
    // by sex
    private function getCountSex()
    {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $sex = $this->_request["sex"];
        $date = $this->_request["date"];

        $stmt = "SELECT
            COUNT(cx.ClinixRID) AS clinix_count
            FROM clinix cx
            LEFT JOIN px_data px ON px.PxRID = cx.PXRID
            WHERE cx.AppDateSet = '$date' AND px.Sex = '$sex';";
        $qry = $this->mobi->query($stmt) or die($this->mobi->error . __LINE__);

        if ($qry->num_rows > 0) {
            while ($row = $qry->fetch_assoc()) {
                $result[] = $row;
            }
            $this->response($this->json($result), 200); // send user details
        }
        $this->response('', 204);
    }
    // by marital status
    private function getCountByStatus()
    {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $status = $this->_request["status"];
        $date = $this->_request["date"];

        $stmt = "SELECT
            COUNT(cx.ClinixRID) AS clinix_count
            FROM clinix cx
            LEFT JOIN px_data px ON px.PxRID = cx.PxRID
            WHERE cx.AppDateSet = '$date' AND px.MaritalStatus = '$status';";
        $qry = $this->mobi->query($stmt) or die($this->mobi->error . __LINE__);

        if ($qry->num_rows > 0) {
            while ($row = $qry->fetch_assoc()) {
                $result[] = $row;
            }
            $this->response($this->json($result), 200); // send user details
        }
        $this->response('', 204);
    }
    // by doctors
    private function getCountByDoctors()
    {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $doc_id = $this->_request["doc_id"];
        $date = $this->_request["date"];
        $doc_id = $doc_id  == 0 ? "" : "AND cx.DokPxRID = '$doc_id'";

        $stmt = "SELECT
            COUNT(cx.ClinixRID) AS clinix_count
            FROM clinix cx
            WHERE cx.AppDateSet = '$date' $doc_id ;";
        $qry = $this->mobi->query($stmt) or die($this->mobi->error . __LINE__);

        if ($qry->num_rows > 0) {
            while ($row = $qry->fetch_assoc()) {
                $result[] = $row;
            }
            $this->response($this->json($result), 200); // send user details
        }
        $this->response('', 204);
    }

    // doctor lists
    private function getDoctorList()
    {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }

        $stmt = "SELECT
            u.PxRID AS px_id,
            u.UserName AS username,
            u.UserType AS usertype,
            px.LastName AS lastname,
            px.FirstName AS firstname,
            px.MiddleName AS middlename
            FROM users u
            LEFT JOIN px_data px ON px.PxRID = u.PxRID
            WHERE u.UserType = 'DOCTOR' AND u.userTypeRID = 2;";
        $qry = $this->mobi->query($stmt) or die($this->mobi->error . __LINE__);

        if ($qry->num_rows > 0) {
            while ($row = $qry->fetch_assoc()) {
                $result[] = $row;
            }
            $this->response($this->json($result), 200); // send user details
        }
        $this->response('', 204);
    }
    // patient list base on filter
    private function getPatientList()
    {
        if ($this->get_request_method() != "GET") {
            $this->response('', 406);
        }
        $dateFrom = (string)$this->_request["dateFrom"];
        $dateTo = (string)$this->_request["dateTo"];
        $agefrom = (int)$this->_request["agefrom"];
        $ageto = (int)$this->_request["ageto"];
        $sex = (string)$this->_request["sex"];
        $status = (string)$this->_request["status"];
        $doc_id = (int)$this->_request["doc_id"];

        $sexFilter =  $sex == "" ? "" : "AND px.Sex = '$sex'";
        $ageFilter =  $agefrom == 0  || $ageto == 0 ? "" : "AND (cx.AppDateAge BETWEEN '$agefrom' AND '$ageto')";
        $statusFilter =  $status == "" ? "" : "AND px.MaritalStatus = '$status'";
        $docFilter =  $doc_id == 0 ? "" : "AND cx.DokPxRID = '$doc_id'";

        $stmt = "SELECT
            cx.ClinixRID AS clinix_id,
            cx.PxRID AS px_id, 
            px.LastName AS lastname,
            px.FirstName AS firstname, 
            px.MiddleName AS middlename,
            px.Sex AS sex,
            px.RegDateAge AS age,
            cx.DokPxRID AS doctor_id,
            cx.Dok AS doctor,
            cx.ApptType AS appt_type,
            cx.AppDateSet AS appt_date,
            cx.AppDateAge AS appt_age,
            px.MaritalStatus AS status_m
            FROM 
            clinix cx
            LEFT JOIN px_data px ON px.PxRID = cx.PxRID
            WHERE (cx.AppDateSet BETWEEN '$dateFrom' AND '$dateTo')
            $sexFilter $ageFilter $statusFilter $docFilter;";
        $qry = $this->mobi->query($stmt) or die($this->mobi->error . __LINE__);

        if ($qry->num_rows > 0) {
            while ($row = $qry->fetch_assoc()) {
                $result[] = $row;
            }
            $this->response($this->json($result), 200); // send user details
        }
        $this->response('', 204);
    }


    /** 
     *Encode array into JSON
     */
    private function json($data)
    {
        if (is_array($data)) {
            return json_encode($data, JSON_NUMERIC_CHECK);
        }
    }
}
// initaite api process
$api = new API;
$api->processApi();
