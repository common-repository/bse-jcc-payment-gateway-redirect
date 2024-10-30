<?php

if (! defined( 'ABSPATH' )) {
    exit;
}
class WC_Gateway_JCC_Payment_Gateway_Redirect_Request
{
    protected $requestparams = array();
    protected $gateway;
    protected $notify_url;
    public $values;
    public function __construct($gateway)
    {
        $this->gateway    = $gateway;
        $this->notify_url = WC()->api_request_url( $this->gateway->responseurl );
        if (! function_exists('write_log')) {
            function write_log($log)
            {
                if (is_array( $log ) || is_object( $log )) {
                    error_log( print_r( $log, true ) );
                } else {
                    error_log( $log );
                }
            }
        }
    }

    public function get_request_url($order)
    {
        $url = $this->gateway->requesturl;
        $args = http_build_query( $this->get_args( $order ) );
        return plugins_url('/includes/sendrequest.php', dirname(__FILE__)) . '?key='. $order->get_order_number() . '&no=' . $order;
    }

    private function formatamount($total)
    {
        $total = number_format ( $total, 2, ".", "" );
        $tobereplace = array(".", ",");
        $replacement   = array("", "");
        $rtotal = str_replace($tobereplace, $replacement, $total);
        $rtotal = str_pad($rtotal, 12, "0", STR_PAD_LEFT);
        return $rtotal;
    }
    public function get_args($order)
    {
        $orderID = $order->get_order_number();
        $formattedPurchaseAmt = $this->formatamount($order->get_total());
        $currency = '978';
        $toEncrypt = $this->gateway->password.$this->gateway->merchantid.$this->gateway->acquirerid.$orderID.$formattedPurchaseAmt.$currency;
        $sha1Signature = sha1($toEncrypt);
        return array(
                'Version' => '1.0.0',
                'MerID' => $this->gateway->merchantid,
                'AcqID' => $this->gateway->acquirerid,
                'MerRespURL' => $this->gateway->responseurl,
                'PurchaseAmt' => $formattedPurchaseAmt,
                'PurchaseCurrency' => $currency,
                'PurchaseCurrencyExponent' => '2',
                'OrderID' => $orderID,
                'CaptureFlag' => 'A',
                'Signature' => base64_encode(pack("H*", $sha1Signature)),
                'SignatureMethod' => 'SHA1',
                'trxType' => 'N'
        );
    }
    public function getform($order)
    {
       $this->values = $this->get_args($order);
        $this->values = apply_filters('submitformoptions_JCC_BSE',$this->values);
        $requesturl = $this->gateway->requesturl;
        $requesturl = apply_filters('filter_requesturl_jcc_bse',$requesturl,$this->values);
        ob_start();
        echo '<form method="post" style="display:none" name="paymentForm" id="paymentForm" action="' . $requesturl . '">';
        foreach($this->values as $key => $value) {
            echo '<input type="hidden" name="' . $key . '" value="'. $value .'"><br>';
        } 
        echo '</form>';
        return ob_get_clean();
    }
}
