                                         <?php

/**
 * ReceiveMessageByDate File Restful API PHP Sample Codes
 * 
 * PHP version 5.6.23 | 7.2.12
 * 
 * @category  PHPSampleCodes
 * @package   SampleCodes
 * @author    Pejman Kheyri <pejmankheyri@gmail.com>
 * @copyright 2018 The Ide Pardazan (ipe.ir) PHP Group. All rights reserved.
 * @license   https://sms.ir/ ipe license

 * @version   IPE: 2.0
 * @link      https://sms.ir/ Documentation of sms.ir Restful API PHP Sample Codes.
 */

/**
 * ReceiveMessageByDate Class Restful API PHP Sample Codes
 * 
 * @category  PHPSampleCodesClass
 * @package   SampleCodesClass
 * @author    Pejman Kheyri <pejmankheyri@gmail.com>
 * @copyright 2018 The Ide Pardazan (ipe.ir) PHP Group. All rights reserved.
 * @license   https://sms.ir/ ipe license
 * @link      https://sms.ir/ Documentation of sms.ir 
 */
class SmsIR_ReceiveMessageResponseByDate
{

    /**
     * Gets API Message Receive Url.
     *
     * @return string Indicates the Url
     */
    protected function getAPIMessageReceiveUrl() 
    {

        return "api/ReceiveMessage";
    }

    /**
     * Gets Api Token Url.
     *
     * @return string Indicates the Url
     */
    protected function getApiTokenUrl()
    {
        return "api/Token";
    }

    /**
     * Gets config parameters for sending request.
     *
     * @param string $APIKey    API Key
     * @param string $SecretKey Secret Key
     * @param string $APIURL    API URL
     * 
     * @return void
     */
    public function __construct($APIKey, $SecretKey, $APIURL)
    {
        $this->APIKey = $APIKey;
        $this->SecretKey = $SecretKey;
        $this->APIURL = $APIURL;
    }

    /**
     * Gets Sent Message Response By Date.
     *
     * @param string $Shamsi_FromDate     Shamsi From Date
     * @param string $Shamsi_ToDate       Shamsi To Date

     * @param string $RowsPerPage         Rows Per Page
     * @param string $RequestedPageNumber Requested Page Number
     * 
     * @return string Indicates the sent sms result
     */
    public function receiveMessageResponseByDate($Shamsi_FromDate, $Shamsi_ToDate, $RowsPerPage, $RequestedPageNumber) 
    {

        $token = $this->_getToken($this->APIKey, $this->SecretKey);
        if ($token != false) {

            $url = $this->APIURL.$this->getAPIMessageReceiveUrl()."?Shamsi_FromDate=".$Shamsi_FromDate."&Shamsi_ToDate=".$Shamsi_ToDate."&RowsPerPage=".$RowsPerPage."&RequestedPageNumber=".$RequestedPageNumber;
            $ReceiveMessageResponseByDate = $this->_execute($url, $token);

            $object = json_decode($ReceiveMessageResponseByDate);

            $result = false;
            if (is_object($object)) {
                if ($object->IsSuccessful == true) {
                    $result = $object->Messages;
                } else {
                    $result = $object->Message;
                }
            } else {
                $result = false;
            }
        } else {
            $result = false;
        }
        return $result;
    }

    /**
     * Gets token key for all web service requests.
     *
     * @return string Indicates the token key
     */
    private function _getToken()
    {
        $postData = array(

            'UserApiKey' => $this->APIKey,
            'SecretKey' => $this->SecretKey,
            'System' => 'php_rest_v_2_0'
        );
        $postString = json_encode($postData);

        $ch = curl_init($this->APIURL.$this->getApiTokenUrl());
        curl_setopt(
            $ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json'
            )
        );
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);

        $result = curl_exec($ch);
        curl_close($ch);



        $response = json_decode($result);

        $resp = false;
        $IsSuccessful = '';
        $TokenKey = '';
        if (is_object($response)) {
            $IsSuccessful = $response->IsSuccessful;
            if ($IsSuccessful == true) {
                $TokenKey = $response->TokenKey;
                $resp = $TokenKey;
            } else {

                $resp = false;

            }
        }
        return $resp;

    }


    /**
     * Executes the main method.
     *
     * @param string $url   url
     * @param string $token token string
     * 
     * @return string Indicates the curl execute result

     */
    private function _execute($url, $token)
    {
        $ch = curl_init($url);

        curl_setopt(
            $ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'x-sms-ir-secure-token: '.$token
            )
        );
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}

try {
    date_default_timezone_set("Asia/Tehran");

    // your sms.ir panel configuration
    $APIKey = "8df3946cc80665c360826a55";
    $SecretKey = "mf136800";
    $APIURL = "https://ws.sms.ir/";

    $Shamsi_FromDate = '1398/04/1';
    $Shamsi_ToDate = '1398/04/31';
    $RowsPerPage = 10;
    $RequestedPageNumber = 1;

    $SmsIR_ReceiveMessageResponseByDate = new SmsIR_ReceiveMessageResponseByDate($APIKey, $SecretKey, $APIURL);
    $ReceiveMessageResponseByDate = $SmsIR_ReceiveMessageResponseByDate->receiveMessageResponseByDate($Shamsi_FromDate, $Shamsi_ToDate, $RowsPerPage, $RequestedPageNumber);
    var_dump($ReceiveMessageResponseByDate);

} catch (Exeption $e) {
    echo 'Error ReceiveMessageResponseByDate : '.$e->getMessage();
}

?> 