<?php
if (! defined( 'ABSPATH' )) {
    exit;
}

return apply_filters( 'wc_settings',
    array(
        'enabled' => array(
            'title'       => __( 'Enable/Disable:', 'JCC_Payment_Redirect' ),
            'label'       => __( 'Enable JCC Redirect', 'JCC_Payment_Redirect' ),
            'type'        => 'checkbox',
            'description' => '',
            'default'     => 'no',
        ),
        'title' => array(
            'title'       => __( 'Title:', 'JCC_Payment_Redirect' ),
            'type'        => 'text',
            'description' => __( 'This is what users see as a payment method at checkout', 'JCC_Payment_Redirect' ),
            'default'     => __( 'JCC Payment Gateway Redirect', 'JCC_Payment_Redirect' ),
            'desc_tip'    => false,
        ),
        'description' => array(
            'title'       => __( 'Description:', 'JCC_Payment_Redirect' ),
            'type'        => 'text',
            'description' => __( 'This is what users see after they select JCC option at checkout', 'JCC_Payment_Redirect' ),
            'default'     => __( 'Pay via JCC. Fast and secure credit card payments', 'JCC_Payment_Redirect' ),
            'desc_tip'    => false,
        ),
        'merchantid' => array(
            'title'       => __( 'Merchant ID:', 'JCC_Payment_Redirect'),
            'type' => 'text',
            'description' => __('Enter the merchant ID provided by JCC', 'JCC_Payment_Redirect'),
            'default'     => '',
            'desc_tip'   => false,
        ),
        'acquirerid' => array(
            'title'       => __( 'Acquirer ID:', 'JCC_Payment_Redirect'),
            'type' => 'text',
            'description' => __('Enter the acquirer ID provided by JCC. In almost all cases this should be 402971', 'JCC_Payment_Redirect'),
            'default'     => '402971',
            'desc_tip'   => false,
        ),
         'password' => array(
            'title'       => __( 'Password:', 'JCC_Payment_Redirect'),
            'type' => 'password',
            'description' => __('Enter the password provided by JCC', 'JCC_Payment_Redirect'),
            'default'     => __('', 'JCC_Payment_Redirect'),
            'desc_tip'   => false,
            
        ),

        'requesturl' => array(
            'title'       => __( 'Request URL:', 'JCC_Payment_Redirect'),
            'type' => 'requesturl',
            'description' => __('Enter the JCC\'s Request URL. ', 'JCC_Payment_Redirect'),
            'default'     => 'https://tjccpg.jccsecure.com/EcomPayment/RedirectAuthLink',
            'desc_tip'   => false,
            'width'      => '250px',
        ),
        'responseurl' => array(
            'title'       => __( 'Response URL:', 'JCC_Payment_Redirect'),
            'type' => 'responseurl',
            'description' => __('Enter the response URL which will capture the response by JCC for every transaction. Should be HTTPS', 'JCC_Payment_Redirect'),
            'default'     => get_site_url() . '/wc-api/WC_Gateway_JCC_Payment_Redirect/',
            'desc_tip'   => false,
        ),
        
        
    )
);
