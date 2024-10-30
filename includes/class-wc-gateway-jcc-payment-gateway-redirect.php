<?php
if (!defined('ABSPATH')) {
    exit;
}
class WC_Gateway_JCC_Payment_Redirect extends WC_Payment_Gateway
{
    public function __construct()
    {
        if (!function_exists('write_log')) {
            function write_log($log)
            {
                if (is_array($log) || is_object($log)) {
                    error_log(print_r($log, true));
                } else {
                    error_log($log);
                }
            }
        }
        $this->id = 'jccpaymentgatewayredirect';
        $this->method_title = __('JCC', 'JCC_Payment_Redirect');
        $this->method_description = __('', 'JCC_Payment_Redirect');
        $this->has_fields = true;
        $this->view_transaction_url = '';
        $this->supports = array(

        );

        $this->init_form_fields();
        $this->init_settings();

        $this->title = __($this->get_option('title'), 'JCC_Payment_Redirect');
        $this->description = __($this->get_option('description'), 'JCC_Payment_Redirect');
        $this->enabled = $this->get_option('enabled');
        $this->merchantid = $this->get_option('merchantid');
        $this->acquirerid = $this->get_option('acquirerid');
        $this->password = $this->get_option('password');
        $this->responseurl = $this->get_option('responseurl');
        $this->requesturl = $this->get_option('requesturl');

        add_action('woocommerce_receipt_jccpaymentgatewayredirect', array($this, 'thankyou'));
        add_action('woocommerce_api_' . strtolower(get_class($this)), array(&$this, 'jcc_callback'));

        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
        
 

        
    }
    
    public function payment_fields() {
        parent::payment_fields();
        do_action('add_payment_fields_on_checkput');
    }
    public function admin_options()
    {
        echo '<h2>JCC';
        echo wc_back_link( __( 'Return to payments', 'woocommerce' ), admin_url( 'admin.php?page=wc-settings&tab=checkout' ) );
        $href = '<a href="https://shop.bse.com.cy/product/jcc-payment-gateway-one-click-pay-for-woocommerce-tokenization/" target="_blank">JCC Payment Gateway One Click Pay for Woocommerce (Tokenization)</a>';
        $getnow = '<a class="button-primary woocommerce-save-button" href="https://shop.bse.com.cy/product/jcc-payment-gateway-one-click-pay-for-woocommerce-tokenization/" target="_blank">See More</a>';
        echo '<div style="padding: 20px">';
        echo '<div style="display:inline-block;vertical-align:middle">';
        echo '<img width="220" height="110" src="https://shop.bse.com.cy/wp-content/uploads/2020/04/JCC-Psystems-oneclick-e1587461129862.png" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt="">';
        echo '</div>';
        echo '<div style="display:inline-block;vertical-align:middle;padding-left:20px;width:700px">';
        echo '<div>';
        echo '<h5 style="padding:0;margin:0">'. $href .'</h5>';
        echo '<p>Offers online retailers a simpler, faster way for their customers to shop and pay 24/7 with the click of a button.</p>';
        echo '<h4>Features</h4>';
        echo '<ul style="list-style:decimal;padding-left:30px;font-weight:normal;font-size:12px">';
        echo '<li>Save customers’ payment details to be used for future payments, through one click, without entering their credit card details.</li>';
        echo '<li>Allow customers to delete their saved payment details from “My Account” -> “Dashboard” and on “Checkout” page.</li>';
        echo '<li>Allow the Administrator to delete payment details of the customers from “Users”.</li>';
        echo '<li>Provides to the Administrator the following options:</li>';
        echo '<li>Enforce the saving of the payment details of customers, or</li>';
        echo '<li>Ask for the consent of the customers to save their payment details during checkout.</li>';
        echo '<li>Customers during checkout will be able to choose from saved payment details or add new payment details to perform a purchase.</li>';
        echo '</ul>';
        echo '</div>';
        echo $getnow;
        echo '</div>';
        echo '</div>';
        $menus = array(
            'jccfree' => 'JCC Free',
        );
        $menus = apply_filters('add_menus_jcc_free',$menus);
        $activetab = '';
        if (!isset($_GET['jcctab']))
        {
            $activetab = 'jccfree';
        }
        else{
            $activetab = $_GET['jcctab'];
        }
        echo '</h2><table class="form-table">';
        foreach ($menus as $key => $value) {
            $active = '';
            if ($activetab == $key) {
                $active = 'nav-tab-active';
            }
            echo '<a class="nav-tab ' . $active . '" href="' . admin_url('admin.php?page=wc-settings&tab=checkout&section=jccpaymentgatewayredirect&jcctab=' . $key) . '">' . $value .'</a>';
        }
        if ($activetab == 'jccfree') {
            echo $this->generate_settings_html();
        }
        else {
            do_action('render_menu_jcc_' . $activetab);
        }
        echo '</table>';
		return;
        echo '<div style="padding:15px">
       <label style="display:block;">Additional Information</label>
       <a type="button" class="button" style="display:block;width:120px;text-align:center;margin-top:5px" href="https://www.jcc.com.cy/become-a-merchant/" target="_blank">JCC Forms</a>
       ' . add_thickbox() . '
       <div style="display:none" id="jccpremium" TB_title="Premium Features">

            <p>
                <label style="display:block">Premium Features:</label>
                <ul style="list-style-type:circle;padding-left:15px">
                   <li>JCC merchant application form in a simple wizard</li>
                   <li>Credit Card Tokenization for easy checkout process</li>
                   <li>Reports of transactions succeeded and failed</li>
                   <li>One click refunds from within the woocommerce order panel</li>
                   <li></li>
                </ul>
            </p>
        </div>
       <a class="thickbox button" type="button" style="display:block;width:120px;text-align:center;margin-top:5px" href="#TB_inline?height=300&amp;width=400&amp;inlineId=jccpremium;" >
         Get Premium
       </a>
       </div>';
        parent::admin_options();

    }
	public function generate_h4_html($key, $data)
    {
        $field_key = $this->get_field_key($key);
        $defaults = array(
            'title' => '',
            'disabled' => false,
            'class' => '',
            'css' => '',
            'placeholder' => '',
            'type' => 'text',
            'desc_tip' => false,
            'description' => '',
            'custom_attributes' => array(),
        );
        $data = wp_parse_args($data, $defaults);
        ob_start();
        ?>
            <tr valign="top">
                <th scope="row" class="titledesc">
                    <h4><?php echo wp_kses_post($data['title']); ?></h4>
                </th>
                <td class="forminp">

                </td>
            </tr>
            <?php

        return ob_get_clean();
    }	
    public function generate_requesturl_html($key, $data)
    {
        $field_key = $this->get_field_key($key);
        $defaults = array(
            'title' => '',
            'disabled' => false,
            'class' => '',
            'css' => '',
            'placeholder' => '',
            'type' => 'text',
            'desc_tip' => false,
            'description' => '',
            'custom_attributes' => array(),
        );
        $data = wp_parse_args($data, $defaults);
        ob_start();
        ?>
            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label for="<?php echo esc_attr($field_key); ?>"><?php echo wp_kses_post($data['title']); ?> <?php echo $this->get_tooltip_html($data); // WPCS: XSS ok.        ?></label>
                </th>
                <td class="forminp">
                    <fieldset>
                        <legend class="screen-reader-text"><span><?php echo wp_kses_post($data['title']); ?></span></legend>
                        <input class="input-text regular-input <?php echo esc_attr($data['class']); ?>" type="<?php echo esc_attr($data['type']); ?>" name="<?php echo esc_attr($field_key); ?>" id="<?php echo esc_attr($field_key); ?>" style="<?php echo esc_attr($data['css']); ?>" value="<?php echo esc_attr($this->get_option($key)); ?>" placeholder="<?php echo esc_attr($data['placeholder']); ?>" <?php disabled($data['disabled'], true);?> <?php echo $this->get_custom_attribute_html($data); // WPCS: XSS ok.        ?> />
                        <input class="input-text regular-input" style="width:auto" type='button' value='Live' onclick='url("LIVE","<?php echo esc_attr($field_key); ?>")'/>
                        <input class="input-text regular-input" style="width:auto" type='button' value='Development' onclick='url("DEV","<?php echo esc_attr($field_key); ?>")'/>
                        <?php echo $this->get_description_html($data); // WPCS: XSS ok.        ?>
                        <script type="text/javascript">
                            function url(type,ID){
                                if (type == 'LIVE') {
                                    document.getElementById(ID).value = 'https://jccpg.jccsecure.com/EcomPayment/RedirectAuthLink'
                                }
                                else if (type == 'DEV'){
                                    document.getElementById(ID).value = 'https://tjccpg.jccsecure.com/EcomPayment/RedirectAuthLink'
                                }
                            }
                        </script>
                    </fieldset>
                </td>
            </tr>
            <?php

        return ob_get_clean();
    }
    public function generate_responseurl_html($key, $data)
    {
        $field_key = $this->get_field_key($key);
        $defaults = array(
            'title' => '',
            'disabled' => false,
            'class' => '',
            'css' => '',
            'placeholder' => '',
            'type' => 'text',
            'desc_tip' => false,
            'description' => '',
            'custom_attributes' => array(),
        );
        $data = wp_parse_args($data, $defaults);
        ob_start();
        ?>
            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label for="<?php echo esc_attr($field_key); ?>"><?php echo wp_kses_post($data['title']); ?> <?php echo $this->get_tooltip_html($data); // WPCS: XSS ok.        ?></label>
                </th>
                <td class="forminp">
                    <fieldset>
                        <legend class="screen-reader-text"><span><?php echo wp_kses_post($data['title']); ?></span></legend>
                        <input class="input-text regular-input <?php echo esc_attr($data['class']); ?>" type="<?php echo esc_attr($data['type']); ?>" name="<?php echo esc_attr($field_key); ?>" id="<?php echo esc_attr($field_key); ?>" style="<?php echo esc_attr($data['css']); ?>" value="<?php echo esc_attr($this->get_option($key)); ?>" placeholder="<?php echo esc_attr($data['placeholder']); ?>" <?php disabled($data['disabled'], true);?> <?php echo $this->get_custom_attribute_html($data); // WPCS: XSS ok.        ?> />
                        <input class="input-text regular-input" style="width:auto" type='button' value='Default' onclick='resurl("<?php echo esc_attr($field_key); ?>")'/>
                        <?php echo $this->get_description_html($data); // WPCS: XSS ok.        ?>
                        <script type="text/javascript">
                            function resurl(ID){
                                document.getElementById(ID).value = '<?php echo get_site_url() . '/wc-api/WC_Gateway_JCC_Payment_Redirect/'; ?>';
                            }
                        </script>
                    </fieldset>
                </td>
            </tr>
            <?php

        return ob_get_clean();
    }
    public function process_admin_options()
    {
        if (is_user_logged_in() && current_user_can('manage_options')) {
 			if (isset($_GET['jcctab']) && $_GET['jcctab'] != 'jccfree') {
                do_action('process_admin_options_jcc_' . $_GET['jcctab']);
                return true;
            }
            if (!is_ssl()) {
                unset($_POST['woocommerce_jccpaymentgatewayredirect_enabled']);
                WC_Admin_Settings::add_error(__('HTTPS is required.', 'JCC_Payment_Redirect'));
            }
            if (
                !isset($_POST['woocommerce_jccpaymentgatewayredirect_merchantid']) || empty($_POST['woocommerce_jccpaymentgatewayredirect_merchantid']) ||
                !isset($_POST['woocommerce_jccpaymentgatewayredirect_acquirerid']) || empty($_POST['woocommerce_jccpaymentgatewayredirect_acquirerid']) ||
                !isset($_POST['woocommerce_jccpaymentgatewayredirect_title']) || empty($_POST['woocommerce_jccpaymentgatewayredirect_title']) ||
                !isset($_POST['woocommerce_jccpaymentgatewayredirect_description']) || empty($_POST['woocommerce_jccpaymentgatewayredirect_description']) ||
                !isset($_POST['woocommerce_jccpaymentgatewayredirect_password']) || empty($_POST['woocommerce_jccpaymentgatewayredirect_password']) ||
                !isset($_POST['woocommerce_jccpaymentgatewayredirect_responseurl']) || empty($_POST['woocommerce_jccpaymentgatewayredirect_responseurl']) ||
                !isset($_POST['woocommerce_jccpaymentgatewayredirect_requesturl']) || empty($_POST['woocommerce_jccpaymentgatewayredirect_requesturl'])
            ) {

                unset($_POST['woocommerce_jccpaymentgatewayredirect_enabled']);
                WC_Admin_Settings::add_error(__('All fields are required.', 'JCC_Payment_Redirect'));
            }
            parent::process_admin_options();
        }
    }
    public function jcc_callback()
    {
        try {
            $data = !empty($_POST) ? $_POST : array();
            if (empty($data)) {
                exit;
            }
            if (empty($data['OrderID'])) {
                exit;
            }

            $orderID = absint($data['OrderID']);
            if (empty($orderID)) {
                exit;
            }
            $order = wc_get_order($orderID);
            if (empty($order)) {
                exit;
            }
            if ($order->needs_payment() == false) {
                exit;
            }
            $redirect_url = $this->get_return_url($order);
            if (!empty($data['ResponseCode']) && !empty($data['ReasonCode'])) {
                $jccResponseCode = intval($data['ResponseCode']);
                $jccReasonCode = intval($data['ReasonCode']);
                if (($jccResponseCode == 1 && $jccReasonCode == 1)) {
                    $jccAuthNo = $data['AuthCode'];
                    if (!empty($jccAuthNo)) {
                        include_once 'class-wc-gateway-jcc-payment-gateway-redirect-request.php';
                        $request = new WC_Gateway_JCC_Payment_Gateway_Redirect_Request($this);
                        $form = $request->get_args($order);
                        $jccRef = $_POST['ReferenceNo'];
                        $jccPaddedCardNo = $_POST['PaddedCardNo'];
                        $toEncrypt = $this->password . $this->merchantid . $this->acquirerid . $orderID . $jccResponseCode . $jccReasonCode;
                        $sha1Signature = sha1($toEncrypt);
                        $expectedsha = base64_encode(pack("H*", $sha1Signature));
                        if ($expectedsha == $data['ResponseSignature']) {
							do_action('before_process_additional_data_jcc_bse',$data);
                            $order->payment_complete($jccRef);
                            WC()->cart->empty_cart();
                            $redirect_url = add_query_arg(array('response_code' => $data['ResponseCode'], 'transaction_id' => $jccRef), $redirect_url);
                            echo $this->get_redirect_html($redirect_url);
                            exit;
                        }
                    }
                }
            }
            if (empty($data['ReasonCodeDesc'])) {
                exit;
            }
            $order->update_status('failed', $data['ReasonCodeDesc']);
            $redirect_url = add_query_arg('wc_error', $data['ReasonCodeDesc'], $order->get_checkout_payment_url(true));
            echo $this->get_redirect_html($redirect_url);
            exit;
        } catch (Exception $e) {
            $order->update_status('failed', $e->getMessage());
            $redirect_url = add_query_arg('wc_error', $e->getMessage(), $order->get_checkout_payment_url(true));
            echo $this->get_redirect_html($redirect_url);
            exit;
        }
    }
    public static function get_redirect_html($redirect_url)
    {
        return "<html><head><script language=\"javascript\">
			<!--
			window.location=\"{$redirect_url}\";
			//-->
			</script>
			</head><body><noscript><meta http-equiv=\"refresh\" content=\"1;url={$redirect_url}\"></noscript></body></html>";
    }
    public function init_form_fields()
    {
        $this->form_fields = include 'class-wc-gateway-jcc-payment-gateway-redirect-settings.php';
    }
    public function process_payment($order_id)
    {
        if ($this->enabled == true) {
            $order = wc_get_order($order_id);
            return array(
                'result' => 'success',
                'redirect' => $order->get_checkout_payment_url($order),
            );
        }
    }
    public function thankyou($order_id)
    {
        $order = wc_get_order($order_id);

        if ($order->needs_payment() == true && !isset($_GET['wc_error'])) {
            include_once 'class-wc-gateway-jcc-payment-gateway-redirect-request.php';
            $request = new WC_Gateway_JCC_Payment_Gateway_Redirect_Request($this);
            echo $request->getform($order);
            echo '<script type="text/javascript">document.forms["paymentForm"].submit();</script>';
        } else if ($order->needs_payment() == true && isset($_GET['wc_error'])) {
            include_once 'class-wc-gateway-jcc-payment-gateway-redirect-request.php';
            $request = new WC_Gateway_JCC_Payment_Gateway_Redirect_Request($this);
            echo $request->getform($order);
            echo '<button onclick="submitpaymentform()">Retry</button>';
            echo '<script type="text/javascript">function submitpaymentform() { document.forms["paymentForm"].submit(); }</script>';
            do_action('additional_buttons_thank_you_page_retry',$order->get_checkout_payment_url($order),$request,$this->requesturl);
        } else {
            echo '<script type="text/javascript">window.location = "' . $this->get_return_url($order) . '"</script>';
        }
    }
}
