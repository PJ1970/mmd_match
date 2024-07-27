<?php
/**
 * PaypalPro Class
 * Helps to make credit card payment by PayPal Payments Pro
 * 
 * Author: CodexWorld
 * Author Email: contact@codexworld.com
 * Author URL: http://www.codexworld.com
 * Tutorial URL: http://www.codexworld.com/paypal-pro-payment-gateway-integration-in-php/
 */
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Routing\Router;
use Cake\Core\Configure;
use App\Model\Table\SettingsTable as Settings;



class PaypalproComponent extends Component 
{
    //Configuration Options
    var $apiUsername = 'abhinav-business_api1.braintechnosys.com';
    var $apiPassword = 'VRUJ4TVQ8R7H9CXX';
    var $apiSignature = 'AFcWxV21C7fd0v3bYYYRCpSSRl31AkeGvzLwDxkm74Bqu84j8pIoRBUd';
    var $apiEndpoint = 'https://api-3t.sandbox.paypal.com/nvp';
    var $subject = '';
    var $authToken = '';
    var $authSignature = '';
    var $authTimestamp = '';
    var $useProxy = FALSE;
    var $proxyHost = '127.0.0.1';
    var $proxyPort = 808;
    var $paypalURL = 'https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token=';
    var $version = '70.0';
    var $ackSuccess = 'SUCCESS';
    var $ackSuccessWarning = 'SUCCESSWITHWARNING';
	
	protected $errorMessages = array();
    
    public function __construct($config = array()){ 
        ob_start();
        session_start();
        if (count($config) > 0){
			$paypalSettings = Settings::getPaypalSettings();
			$config->apiUsername = $paypalSettings['username'];
			$config->apiPassword = $paypalSettings['password'];
			$config->apiSignature = $paypalSettings['signature'];
			$config->live = $paypalSettings['test_mode'];
			//$config->live = Configure::read('PAYPAL_MODE');
			foreach ($config as $key => $val){
                if (isset($key) && $key == 'live' && $val == 1){
                    $this->paypalURL = 'https://www.paypal.com/webscr&cmd=_express-checkout&token=';
                    $this->apiEndpoint = 'https://api-3t.paypal.com/nvp';
                }else if (isset($this->$key)){
                    $this->$key = $val;
                }
            }
        }
		
		// Sets errorMessages instance var with localization
		$this->errorMessages = array(
			10411 => __('The Express Checkout transaction has expired and the transaction needs to be restarted'),
			10412 => __('You may have made a second call for the same payment or you may have used the same invoice ID for seperate transactions.'),
			10422 => __('Please use a different funcing source.'),
			10445 => __('An error occured, please retry the transaction.'),
			10486 => __('This transaction couldn\'t be completed. Redirecting to payment gateway'),
			10500 => __('You have not agreed to the billing agreement.'),
			10501 => __('The billing agreement is disabled or inactive.'),
			10502 => __('The credit card used is expired.'),
			10505 => __('The transaction was refused because the AVS response returned the value of N, and the merchant account is not able to accept such transactions.'),
			10507 => __('The payment gateway account is restricted.'),
			10509 => __('You must submit an IP address of the buyer with each API call.'),
			10511 => __('The merchant selected a value for the PaymentAction field that is not supported.'),
			10519 => __('The credit card field was blank.'),
			10520 => __('The total amount and item amounts do not match.'),
			10534 => __('The credit card entered is currently restricted by the payment gateway.'),
			10536 => __('The merchant entered an invoice ID that is already associated with a transaction by the same merchant. Attempt with a new invoice ID'),
			10537 => __('The transaction was declined by the country filter managed by the merchant.'),
			10538 => __('The transaction was declined by the maximum amount filter managed by the merchant.'),
			10539 => __('The transaction was declined by the payment gateway.'),
			10541 => __('The credit card entered is currently restricted by the payment gateway.'),
			10544 => __('The transaction was declined by the payment gateway.'),
			10545 => __('The transaction was declined by payment gateway because of possible fraudulent activity.'),
			10546 => __('The transaction was declined by payment gateway because of possible fraudulent activity on the IP address.'),
			10548 => __('The merchant account attempting the transaction is not a business account.'),
			10549 => __('The merchant account attempting the transaction is not able to process Direct Payment transactions. '),
			10550 => __('Access to Direct Payment was disabled for your account.'),
			10552 => __('The merchant account attempting the transaction does not have a confirmed email address with the payment gateway.'),
			10553 => __('The merchant attempted a transaction where the amount exceeded the upper limit for that merchant.'),
			10554 => __('The transaction was declined because of a merchant risk filter for AVS. Specifically, the merchant has set to decline transaction when the AVS returned a no match (AVS = N).'),
			10555 => __('The transaction was declined because of a merchant risk filter for AVS. Specifically, the merchant has set the filter to decline transactions when the AVS returns a partial match.'),
			10556 => __('The transaction was declined because of a merchant risk filter for AVS. Specifically, the merchant has set the filter to decline transactions when the AVS is unsupported.'),
			10747 => __('The merchant entered an IP address that was in an invalid format. The IP address must be in a format such as 123.456.123.456.'),
			10748 => __('The merchant\'s configuration requires a CVV to be entered, but no CVV was provided with this transaction.'),
			10751 => __('The merchant provided an address either in the United States or Canada, but the state provided is not a valid state in either country.'),
			10752 => __('The transaction was declined by the issuing bank, not the payment gateway. The merchant should attempt another card.'),
			10754 => __('The transaction was declined by the payment gateway.'),
			10760 => __('The merchant\'s country of residence is not currently supported to allow Direct Payment transactions.'),
			10761 => __('The transaction was declined because the payment gateway is currently processing a transaction by the same buyer for the same amount. Can occur when a buyer submits multiple, identical transactions in quick succession.'),
			10762 => __('The CVV provided is invalid. The CVV is between 3-4 digits long.'),
			10764 => __('Please try again later. Ensure you have passed the correct CVV and address info for the buyer. If creating a recurring profile, please try again by passing a init amount of 0.'),
			12000 => __('Transaction is not compliant due to missing or invalid 3-D Secure authentication values. Check ECI, ECI3DS, CAVV, XID fields.'),
			12001 => __('Transaction is not compliant due to missing or invalid 3-D Secure authentication values. Check ECI, ECI3DS, CAVV, XID fields.'),
			15001 => __('The transaction was rejected by the payment gateway because of excessive failures over a short period of time for this credit card.'),
			15002 => __('The transaction was declined by payment gateway.'),
			15003 => __('The transaction was declined because the merchant does not have a valid commercial entity agreement on file with the payment gateway.'),
			15004 => __('The transaction was declined because the CVV entered does not match the credit card.'),
			15005 => __('The transaction was declined by the issuing bank, not the payment gateway. The merchant should attempt another card.'),
			15006 => __('The transaction was declined by the issuing bank, not the payment gateway. The merchant should attempt another card.'),
			15007 => __('The transaction was declined by the issuing bank because of an expired credit card. The merchant should attempt another card.'),
		);
		
    }
    public function nvpHeader(){
        $nvpHeaderStr = "";
    
        if((!empty($this->apiUsername)) && (!empty($this->apiPassword)) && (!empty($this->apiSignature)) && (!empty($subject))) {
            $authMode = "THIRDPARTY";
        }else if((!empty($this->apiUsername)) && (!empty($this->apiPassword)) && (!empty($this->apiSignature))) {
            $authMode = "3TOKEN";
        }elseif (!empty($this->authToken) && !empty($this->authSignature) && !empty($this->authTimestamp)) {
            $authMode = "PERMISSION";
        }elseif(!empty($subject)) {
            $authMode = "FIRSTPARTY";
        }
        
        switch($authMode) {
            case "3TOKEN" : 
                $nvpHeaderStr = "&PWD=".urlencode($this->apiPassword)."&USER=".urlencode($this->apiUsername)."&SIGNATURE=".urlencode($this->apiSignature);
                break;
            case "FIRSTPARTY" :
                $nvpHeaderStr = "&SUBJECT=".urlencode($this->subject);
                break;
            case "THIRDPARTY" :
                $nvpHeaderStr = "&PWD=".urlencode($this->apiPassword)."&USER=".urlencode($this->apiUsername)."&SIGNATURE=".urlencode($this->apiSignature)."&SUBJECT=".urlencode($subject);
                break;		
            case "PERMISSION" :
                $nvpHeaderStr = $this->formAutorization($this->authToken,$this->authSignature,$this->authTimestamp);
                break;
        }
        return $nvpHeaderStr;
    }
    
    /**
      * hashCall: Function to perform the API call to PayPal using API signature
      * @methodName is name of API  method.
      * @nvpStr is nvp string.
      * returns an associtive array containing the response from the server.
    */
    public function hashCall($methodName,$nvpStr){
        // form header string
        $nvpheader = $this->nvpHeader();

        //setting the curl parameters.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$this->apiEndpoint);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
    
        //turning off the server and peer verification(TrustManager Concept).
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POST, 1);
        
        //in case of permission APIs send headers as HTTPheders
        if(!empty($this->authToken) && !empty($this->authSignature) && !empty($this->authTimestamp))
         {
            $headers_array[] = "X-PP-AUTHORIZATION: ".$nvpheader;
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers_array);
            curl_setopt($ch, CURLOPT_HEADER, false);
        }
        else 
        {
            $nvpStr = $nvpheader.$nvpStr;
        }
        //if USE_PROXY constant set to TRUE in Constants.php, then only proxy will be enabled.
       //Set proxy name to PROXY_HOST and port number to PROXY_PORT in constants.php 
        if($this->useProxy)
            curl_setopt ($ch, CURLOPT_PROXY, $this->proxyHost.":".$this->proxyPort); 
    
        //check if version is included in $nvpStr else include the version.
        if(strlen(str_replace('VERSION=', '', strtoupper($nvpStr))) == strlen($nvpStr)) {
            $nvpStr = "&VERSION=" . urlencode($this->version) . $nvpStr;	
        }
        
        $nvpreq="METHOD=".urlencode($methodName).$nvpStr;
        //setting the nvpreq as POST FIELD to curl
        curl_setopt($ch,CURLOPT_POSTFIELDS,$nvpreq);
    
        //getting response from server
        $response = curl_exec($ch);
        //convrting NVPResponse to an Associative Array
        $nvpResArray = $this->deformatNVP($response);
        $nvpReqArray = $this->deformatNVP($nvpreq);
        $_SESSION['nvpReqArray']=$nvpReqArray;
    
        if (curl_errno($ch)) {
            die("CURL send a error during perform operation: ".curl_error($ch));
        } else {
            //closing the curl
            curl_close($ch);
        }
    
        return $nvpResArray;
    }
    
    /** This function will take NVPString and convert it to an Associative Array and it will decode the response.
     * It is usefull to search for a particular key and displaying arrays.
     * @nvpstr is NVPString.
     * @nvpArray is Associative Array.
     */
    public function deformatNVP($nvpstr){
        $intial=0;
        $nvpArray = array();
    
        while(strlen($nvpstr)){
            //postion of Key
            $keypos = strpos($nvpstr,'=');
            //position of value
            $valuepos = strpos($nvpstr,'&') ? strpos($nvpstr,'&'): strlen($nvpstr);
    
            /*getting the Key and Value values and storing in a Associative Array*/
            $keyval = substr($nvpstr,$intial,$keypos);
            $valval = substr($nvpstr,$keypos+1,$valuepos-$keypos-1);
            //decoding the respose
            $nvpArray[urldecode($keyval)] =urldecode( $valval);
            $nvpstr = substr($nvpstr,$valuepos+1,strlen($nvpstr));
         }
        return $nvpArray;
    }
    
    public function formAutorization($auth_token,$auth_signature,$auth_timestamp){
        $authString="token=".$auth_token.",signature=".$auth_signature.",timestamp=".$auth_timestamp ;
        return $authString;
    }
    
    public function paypalCall($params){
        /*
         * Construct the request string that will be sent to PayPal.
         * The variable $nvpstr contains all the variables and is a
         * name value pair string with & as a delimiter
         */
		$recurringStr = (array_key_exists("recurring",$params) && $params['recurring'] == 'Y')?'&RECURRING=Y':'';
        $nvpstr = "&PAYMENTACTION=".$params['paymentAction']."&AMT=".$params['amount']."&CREDITCARDTYPE=".$params['creditCardType']."&ACCT=".$params['creditCardNumber']."&EXPDATE=".$params['expMonth'].$params['expYear']."&CVV2=".$params['cvv']."&FIRSTNAME=".$params['firstName']."&LASTNAME=".$params['lastName']."&CITY=".$params['city']."&ZIP=".$params['zip']."&COUNTRYCODE=".$params['countryCode']."&CURRENCYCODE=".$params['currencyCode'].$recurringStr;
		
        /* Make the API call to PayPal, using API signature.
           The API response is stored in an associative array called $resArray */
        $resArray = $this->hashCall("DoDirectPayment",$nvpstr);
		
        return $resArray;
    }
	
	public function getErrorMessage($response) {
		if (array_key_exists($response['L_ERRORCODE0'], $this->errorMessages)) {
			return $this->errorMessages[$response['L_ERRORCODE0']];
		}
		return $response['L_LONGMESSAGE0'];
	}
}
?>
