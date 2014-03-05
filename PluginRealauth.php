<?php
require_once 'modules/admin/models/GatewayPlugin.php';
require_once 'library/CE/XmlFunctions.php';
require_once 'modules/billing/models/class.gateway.plugin.php';
require_once 'library/CE/NE_Network.php';
require_once 'modules/billing/models/Currency.php';
require_once 'modules/billing/models/CreditCard.php';

class PluginRealauth extends GatewayPlugin
{
    function getVariables()
    {
        $variables = array (
            /*T*/'Plugin Name'/*/T*/ => array (
                                'type'          =>'hidden',
                                'description'   =>/*T*/'How CE sees this plugin (not to be confused with the Signup Name)'/*/T*/,
                                'value'         =>/*T*/'RealAuth Gateway'/*/T*/
                               ),
            /*T*/'RealAuth Merchant ID'/*/T*/ => array (
                                'type'          =>'text',
                                'description'   =>/*T*/'Please enter your Realex Payments Assigned Merchant ID Here.'/*/T*/,
                                'value'         =>''
                               ),
            /*T*/'RealAuth Shared Secret'/*/T*/ => array (
                                'type'          =>'password',
                                'description'   =>/*T*/'Please enter your Realex Payments Shared Secret Here.'/*/T*/,
                                'value'         =>''
                               ),
            /*T*/'RealAuth Subaccount'/*/T*/ => array (
                                'type'          =>'text',
                                'description'   =>/*T*/'Please enter your Realex Payments Subaccount to use Here.'/*/T*/,
                                'value'         =>''
                               ),
            /*T*/'Accept CC Number'/*/T*/ => array (
                                'type'          =>'hidden',
                                'description'   =>/*T*/'Selecting YES allows the entering of CC numbers when using this plugin type. No will prevent entering of cc information'/*/T*/,
                                'value'         =>'1'
                               ),

            /*T*/'Visa'/*/T*/ => array (
                                'type'          =>'yesno',
                                'description'   =>/*T*/'Select YES to allow Visa card acceptance with this plugin.  No will prevent this card type.'/*/T*/,
                                'value'         =>'1'
                               ),
            /*T*/'MasterCard'/*/T*/ => array (
                                'type'          =>'yesno',
                                'description'   =>/*T*/'Select YES to allow MasterCard acceptance with this plugin. No will prevent this card type.'/*/T*/,
                                'value'         =>'1'
                               ),
            /*T*/'AmericanExpress'/*/T*/ => array (
                                'type'          =>'yesno',
                                'description'   =>/*T*/'Select YES to allow American Express card acceptance with this plugin. No will prevent this card type.'/*/T*/,
                                'value'         =>'1'
                               ),

            /*T*/'LaserCard'/*/T*/ => array (
                                'type'          =>'yesno',
                                'description'   =>/*T*/'Select YES to allow LaserCard card acceptance with this plugin. No will prevent this card type.'/*/T*/,
                                'value'         =>'1'
                               ),
            /*T*/'DinersClub'/*/T*/ => array (
                                'type'          =>'yesno',
                                'description'   =>/*T*/'Select YES to allow Diners Club card acceptance with this plugin. No will prevent this card type.'/*/T*/,
                                'value'         =>'1'
                               ),
//            /*T*/'Switch'/*/T*/ => array (
//                                'type'          =>'yesno',
//                                'description'   =>/*T*/'Select YES to allow Switch card acceptance with this plugin. No will prevent this card type.'/*/T*/,
//                                'value'         =>'1'
//                               ),

            /*T*/'Invoice After Signup'/*/T*/ => array (
                                'type'          =>'yesno',
                                'description'   =>/*T*/'Select YES if you want an invoice sent to the customer after signup is complete.'/*/T*/,
                                'value'         =>'1'
                               ),
            /*T*/'Signup Name'/*/T*/ => array (
                                'type'          =>'text',
                                'description'   =>/*T*/'Select the name to display in the signup process for this payment type. Example: eCheck or Credit Card.'/*/T*/,
                                'value'         =>'Credit Card'
                               ),
            /*T*/'Payment Form Title'/*/T*/ => array(
                                'type'          =>'text',
                                'description'   =>/*T*/'The Payment Form will only be displayed when extra user input is required, i.e. when using Verified By Visa, MaterCard SecureCode or DialVerify.'/*/T*/,
                                'value'         =>''
                               ),
            /*T*/'Payment Form Header'/*/T*/ => array(
                                'type'          =>'textarea',
                                'description'   =>/*T*/'The Payment Form will only be displayed when extra user input is required, i.e. when using Verified By Visa, MaterCard SecureCode or DialVerify. HTML is accepted.'/*/T*/,
                                'value'         =>''
                               ),
            /*T*/'Receipt Page Header'/*/T*/ => array(
                                'type'          =>'textarea',
                                'description'   =>/*T*/'The Receipt Page will only be displayed when extra user input is required, i.e. when using Verified By Visa, MaterCard SecureCode or DialVerify. HTML is accepted.'/*/T*/,
                                'value'         =>''
                               ),
            /*T*/'Receipt Page Footer'/*/T*/ => array(
                                'type'          =>'textarea',
                                'description'   =>/*T*/'The Receipt Footer will only be displayed when extra user input is required, i.e. when using Verified By Visa, MaterCard SecureCode or DialVerify. HTML is accepted.'/*/T*/,
                                'value'         =>''
                               ),
            /*T*/'Dummy Plugin'/*/T*/ => array (
                                'type'          =>'hidden',
                                'description'   =>/*T*/'1 = Only used to specify a billing type for a customer. 0 = full fledged plugin requiring complete functions'/*/T*/,
                                'value'         =>'0'
                               ),
            /*T*/'Auto Payment'/*/T*/ => array (
                                'type'          =>'hidden',
                                'description'   =>/*T*/'No description'/*/T*/,
                                'value'         =>'1'
                               ),
            /*T*/'30 Day Billing'/*/T*/ => array (
                                'type'          =>'hidden',
                                'description'   =>/*T*/'Select YES if you want ClientExec to treat monthly billing by 30 day intervals.  If you select NO then the same day will be used to determine intervals.'/*/T*/,
                                'value'         =>'0'
                               ),
            /*T*/"Check CVV2"/*/T*/ => array (
                                "type"          =>"hidden",
                                "description"   =>/*T*/"Select YES if you want to accept CVV2 for this plugin."/*/T*/,
                                "value"         =>"0"
                               ),
//            /*T*/"Check Issue Number"/*/T*/ => array (
//                                "type"          =>"hidden",
//                                "description"   =>/*T*/"Please enter a list of the credit card types, separated by comma, that you want to accept Issue Number for this plugin."/*/T*/,
//                                "value"         =>"Switch" //"Visa,MasterCard,AmericanExpress,Discover,LaserCard,DinersClub,Switch"
//                               ),
        );

        return $variables;
    }

    function singlepayment($params)
    {
        return $this->autopayment($params);
    }

    function autopayment($params)
    {
        $cPlugin = new Plugin($params['invoiceNumber'], "realauth", $this->user);
        $cPlugin->setAmount($params['invoiceTotal']);

        //Creates timestamp that is needed to make up orderid
        $timestamp = strftime("%Y%m%d%H%M%S");

        //You can use any alphanumeric combination for the orderid.Although each transaction must have a unique orderid.
        $orderid = $timestamp."-".$params['invoiceNumber'];

        $cPlugin->setTransactionID($orderid);

        if (isset($params['refund']) && $params['refund']) {
            $isRefund = true;
            $cPlugin->setAction('refund');

            $response = $this->PlaceRealexRefund($params, $timestamp, $orderid);
        }else{
            $isRefund = false;
            $cPlugin->setAction('charge');

            $response = $this->PlaceRealexPayment($params, $timestamp, $orderid);
        }

        if(!$response){
              $cPlugin->PaymentRejected($this->user->lang("There was not response from RealAuth. Please double check your information"));
              return $this->user->lang("There was not response from RealAuth. Please double check your information");
        }

        if (is_a($response, 'CE_Error')){
            $cPlugin->PaymentRejected($this->user->lang("There was an error performing this operation.")." ".$response->getMessage());
            return $this->user->lang("There was an error performing this operation.")." ".$response->getMessage();
        }

        if(isset($response['response']['#']['result'][0]['#'])){
            if ($isRefund){
                if($response['response']['#']['result'][0]['#'] == '00'){
                    $cPlugin->PaymentAccepted($params['invoiceTotal'], "RealAuth refund of {$params['invoiceTotal']} was successfully processed.", $orderid);
                    return array('AMOUNT' => $params['invoiceTotal']);
                }else{
                    $rejectDetails = '';

                    if(isset($response['response']['#']['message'][0]['#'])){
                        $rejectDetails = ' '.$response['response']['#']['message'][0]['#'];
                    }

                    $cPlugin->PaymentRejected("RealAuth refund of {$params['invoiceTotal']} was rejected.".$rejectDetails);
                    return 'Refund rejected by credit card gateway provider';
                }
            }else{
                if($response['response']['#']['result'][0]['#'] == '00'){
                    $cPlugin->PaymentAccepted($params['invoiceTotal'], "RealAuth payment of {$params['invoiceTotal']} was accepted.", $orderid);
                    return '';
                }else{
                    $rejectDetails = '';

                    if(isset($response['response']['#']['message'][0]['#'])){
                        $rejectDetails = ' '.$response['response']['#']['message'][0]['#'];
                    }

                    $cPlugin->PaymentRejected("RealAuth payment of {$params['invoiceTotal']} was rejected.".$rejectDetails);
                    return 'Payment rejected by credit card gateway provider';
                }
            }
        }else{
            $cPlugin->PaymentRejected($this->user->lang("The response from RealAuth was not recognized."));
            return $this->user->lang("The response from RealAuth was not recognized.");
        }
    }

    function credit($params)
    {}

//    function credit($params)
//    {
//        $params['refund'] = true;
//        return $this->autopayment($params);
//    }

    function PlaceRealexPayment($params, $timestamp, $orderid){
        $currency = new Currency($this->user);
        $amount = $currency->format($params['currencytype'], $params['invoiceTotal'], false)*pow(10, $currency->getPrecision($params["currencytype"]));

        $currencyCode = $params["currencytype"];
        $cardnumber = $params['userCCNumber'];
        $cardname = mb_substr(trim($params['userFirstName'].' '.$params['userLastName']), 0, 100);
        $cardtype = $this->getCreditCardType($cardnumber);

        $CCMo = mb_substr($params['userCCExp'], 0, 2);
        $CCYear = mb_substr($params['userCCExp'], 5, 2);
        $expdate = $CCMo.$CCYear;

        // These values will be provided to you by realex Payments, if you have not already received them please contact us
        $merchantid = $params['plugin_realauth_RealAuth Merchant ID'];
        $secret = $params['plugin_realauth_RealAuth Shared Secret'];
        $account = $params['plugin_realauth_RealAuth Subaccount'];

        $url = "https://epage.payandshop.com/epage-remote.cgi";

        $xml  = "<request type='auth' timestamp='".$timestamp."'>\n";
        $xml .= "    <merchantid>".$merchantid."</merchantid>\n";

        /*
          Represents the Realex Payments subaccount to use.
          If this element is omitted, then the default account is used.
        */
        $xml .= "    <account>".$account."</account>\n";

        /*
          Length 1-40
        */
        $xml .= "    <orderid>".$orderid."</orderid>\n";

        /*
          The amount should be in the smallest unit of the required currency
          (For example: 2000 = �20, $20 or �20).
        */
        $xml .= "    <amount currency='".$currencyCode."'>".$amount."</amount>\n";

        $xml .= "    <card>\n";
        $xml .= "        <number>".$cardnumber."</number>\n";

        /*
          Represets the card expiry date, in the format MMYY, which must be a date in the future.
        */
        $xml .= "        <expdate>".$expdate."</expdate>\n";

        /*
          VISA, MC, AMEX, LASER, DINERS, SWITCH
        */
        $xml .= "        <type>".$cardtype."</type>\n";

        /*
          Format 0-9
          Length 0-3
          Where 1 represents the issue number of a SWITCH.
          Only required if the card type is SWITCH.
        */
//        if($cardtype == 'SWITCH'){
//            $xml .= "        <issueno>1</issueno>\n";
//        }

        /*
          Length 0-100
        */
        $xml .= "        <chname>".$cardname."</chname>\n";

        /*
          The card verification details element
        */
        //$xml .= "        <cvn>\n";

        /*
          Format 0-9
          Length 3-4
          Where 123 represents the Card Verification Number (CVN), which is a
          three-digit number on the reverse of the card.
          It is called the CVC for VISA and the CVV2 for MasterCard.
          For an AMEX card, it is a four-digit number.
        */
        //$xml .= "            <number>123</number>\n";

        /*
          Where presence_indicator represents the presence of the CVN and can take the
          following four values:
            1: CVN present
            2: CVN illegible
            3: CVN not on card
            4: CVN not requested by the Merchant
        */
        //$xml .= "            <presind>presence_indicator</presind>\n";

        //$xml .= "        </cvn>\n";

        $xml .= "    </card>\n";

        $xml .= "    <comments>\n";
        $xml .= "        <comment id='1'>Customer ID: ".$params['userID']."</comment>\n";
        $xml .= "        <comment id='2'>Invoice ID: ".$params['invoiceNumber']."</comment>\n";
        $xml .= "    </comments>\n";

        $xml .= "    <autosettle flag='1'/>\n";

        /*
          Format a-f 0-9
          Length 40
          Where SHA_1_hash represents the SHA-1 hash of certain elements of the request.
          For more information, see the Realauth Developer's Guide. Either the SHA-1 hash
          or the MD5 hash can be used.
        */
        //$xml .= "    <sha1hash>SHA_1_hash</sha1hash>\n";

        // This section of code creates the md5hash that is needed
        $tmp = "$timestamp.$merchantid.$orderid.$amount.$currencyCode.$cardnumber";
        $md5hash = md5($tmp);
        $tmp = "$md5hash.$secret";
        $md5hash = md5($tmp);
        $xml .= "    <md5hash>".$md5hash."</md5hash>\n";

        $xml .= "    <tssinfo>\n";
        $xml .= "        <custnum>".$params['userID']."</custnum>\n";
        $xml .= "        <prodid>".$params['invoiceNumber']."</prodid>\n";

        /*
          Format a-z A-Z 0-9 - "" _ . , + @
          Length 0-50
          Where variable_reference represents any reference assigned to the customer,
          which can allow checking of previous transactions by this customer, through
          the use of the Realscore service.
        */
        //$xml .= "        <varref>variable_reference</varref>\n";

        /*
          Format 0-9 IP Address in X.X.X.X format
          Length [1-3].{1-3}.{1-3}.{1-3}
          Where www.xxx.yyy.zzz represents the IP address of the customer.
        */
        //$xml .= "        <custipaddress>www.xxx.yyy.zzz</custipaddress>\n";
        $xml .= "        <custipaddress>".CE_Lib::getRemoteAddr()."</custipaddress>\n";


        /*
          The billing address of the customer
        */
        $xml .= "        <address type=\"billing\">\n";

        /*
          Format a-z A-Z 0-9 "" , . - / |
          Length 0-30
          Where zip_postal_code represents the ZIP or Postal code of the billing address,
          which is useful for checking (in conjunction with the country) against a table
          of high-risk areas.
        */
        //$xml .= "            <code>zip_postal_code</code>\n";
        $xml .= "            <code>".$params['userZipcode']."</code>\n";

        /*
          Format a-z A-Z 2-character country code
          Length 2
          Where country represents the country of the billing address, which is useful for
          checking against a table of high-risk countries.
        */
        //$xml .= "            <country>country</country>\n";
        $xml .= "            <country>".$params['userCountry']."</country>\n";

        $xml .= "        </address>\n";

        /*
          The shipping address of the customer
        */
        $xml .= "        <address type=\"shipping\">\n";

        /*
          Format a-z A-Z 0-9 "" , . - / |
          Length 0-30
          Where zip_postal_code represents the ZIP or Postal code of the shipping address,
          which is useful for checking (in conjunction with the country) against a table
          of high-risk areas.
        */
        //$xml .= "            <code>zip_postal_code</code>\n";
        $xml .= "            <code>".$params['userZipcode']."</code>\n";

        /*
          Format a-z A-Z 2-character country code
          Length 2
          Where country represents the country of the shipping address, which is useful for
          checking against a table of high-risk countries.
        */
        //$xml .= "            <country>country</country>\n";
        $xml .= "            <country>".$params['userCountry']."</country>\n";

        $xml .= "        </address>\n";

        $xml .= "    </tssinfo>\n";
        $xml .= "</request>\n";

        $header  = array("POST ".$url." HTTP/1.1",
                         "Content-Length: ".strlen($xml),
                         "Content-type: text/xml; charset=UTF8",
                         "Connection: close; Keep-Alive",
                        );

        $response = NE_Network::curlRequest($this->settings, $url, $xml, $header, true);

        if ($response && !is_a($response, 'CE_Error')){
            $response = XmlFunctions::xmlize($response);
        }

        return $response;
    }

    function PlaceRealexRefund($params, $timestamp, $orderid){
        //THIS PLUGIN DOES NOT REFUND YET
    }

    function getCreditCardType($cardnumber)
    {
        $cards = array(
          'VISA'   => 'Visa',
          'MC'     => 'MasterCard',
          'AMEX'   => 'American Express',
          'LASER'  => 'LaserCard',
          'DINERS' => 'Diners Club',
          'SWITCH' => 'Switch'
        );

        $cardtype = 'UNKNOW';

        $cc = new CreditCard();
        foreach($cards AS $key => $card){
            $errornumber = '';
            $errortext   = '';

            if($cc->checkCreditCard($cardnumber, $card, $errornumber, $errortext)){
                $cardtype = $key;
                break;
            }
        }

        return $cardtype;
    }
}
?>
