<?php
/*
Plugin Name: JCC Payment Gateway Redirect
Description: JCC Payment Gateway Redirect Plugin For WooCommerce
Author: B.S.E. Business Solution Enteprises LTD
Author URI: https://www.bse.com.cy
Version: 1.0.7
 * WC requires at least: 2.6.14
 * WC tested up to: 4.2.0
 * Text Domain: JCC_Payment_Redirect
Disclaimer: Use at your own risk. No warranty expressed or implied is provided.
 */
/*
GNU LESSER GENERAL PUBLIC LICENSE
Version 3, 29 June 2007

Copyright (C) 2007 Free Software Foundation, Inc. <https://fsf.org/>
Everyone is permitted to copy and distribute verbatim copies
of this license document, but changing it is not allowed.

This version of the GNU Lesser General Public License incorporates
the terms and conditions of version 3 of the GNU General Public
License, supplemented by the additional permissions listed below.

0. Additional Definitions.

As used herein, "this License" refers to version 3 of the GNU Lesser
General Public License, and the "GNU GPL" refers to version 3 of the GNU
General Public License.

"The Library" refers to a covered work governed by this License,
other than an Application or a Combined Work as defined below.

An "Application" is any work that makes use of an interface provided
by the Library, but which is not otherwise based on the Library.
Defining a subclass of a class defined by the Library is deemed a mode
of using an interface provided by the Library.

A "Combined Work" is a work produced by combining or linking an
Application with the Library.  The particular version of the Library
with which the Combined Work was made is also called the "Linked
Version".

The "Minimal Corresponding Source" for a Combined Work means the
Corresponding Source for the Combined Work, excluding any source code
for portions of the Combined Work that, considered in isolation, are
based on the Application, and not on the Linked Version.

The "Corresponding Application Code" for a Combined Work means the
object code and/or source code for the Application, including any data
and utility programs needed for reproducing the Combined Work from the
Application, but excluding the System Libraries of the Combined Work.

1. Exception to Section 3 of the GNU GPL.

You may convey a covered work under sections 3 and 4 of this License
without being bound by section 3 of the GNU GPL.

2. Conveying Modified Versions.

If you modify a copy of the Library, and, in your modifications, a
facility refers to a function or data to be supplied by an Application
that uses the facility (other than as an argument passed when the
facility is invoked), then you may convey a copy of the modified
version:

a) under this License, provided that you make a good faith effort to
ensure that, in the event an Application does not supply the
function or data, the facility still operates, and performs
whatever part of its purpose remains meaningful, or

b) under the GNU GPL, with none of the additional permissions of
this License applicable to that copy.

3. Object Code Incorporating Material from Library Header Files.

The object code form of an Application may incorporate material from
a header file that is part of the Library.  You may convey such object
code under terms of your choice, provided that, if the incorporated
material is not limited to numerical parameters, data structure
layouts and accessors, or small macros, inline functions and templates
(ten or fewer lines in length), you do both of the following:

a) Give prominent notice with each copy of the object code that the
Library is used in it and that the Library and its use are
covered by this License.

b) Accompany the object code with a copy of the GNU GPL and this license
document.

4. Combined Works.

You may convey a Combined Work under terms of your choice that,
taken together, effectively do not restrict modification of the
portions of the Library contained in the Combined Work and reverse
engineering for debugging such modifications, if you also do each of
the following:

a) Give prominent notice with each copy of the Combined Work that
the Library is used in it and that the Library and its use are
covered by this License.

b) Accompany the Combined Work with a copy of the GNU GPL and this license
document.

c) For a Combined Work that displays copyright notices during
execution, include the copyright notice for the Library among
these notices, as well as a reference directing the user to the
copies of the GNU GPL and this license document.

d) Do one of the following:

0) Convey the Minimal Corresponding Source under the terms of this
License, and the Corresponding Application Code in a form
suitable for, and under terms that permit, the user to
recombine or relink the Application with a modified version of
the Linked Version to produce a modified Combined Work, in the
manner specified by section 6 of the GNU GPL for conveying
Corresponding Source.

1) Use a suitable shared library mechanism for linking with the
Library.  A suitable mechanism is one that (a) uses at run time
a copy of the Library already present on the user's computer
system, and (b) will operate properly with a modified version
of the Library that is interface-compatible with the Linked
Version.

e) Provide Installation Information, but only if you would otherwise
be required to provide such information under section 6 of the
GNU GPL, and only to the extent that such information is
necessary to install and execute a modified version of the
Combined Work produced by recombining or relinking the
Application with a modified version of the Linked Version. (If
you use option 4d0, the Installation Information must accompany
the Minimal Corresponding Source and Corresponding Application
Code. If you use option 4d1, you must provide the Installation
Information in the manner specified by section 6 of the GNU GPL
for conveying Corresponding Source.)

5. Combined Libraries.

You may place library facilities that are a work based on the
Library side by side in a single library together with other library
facilities that are not Applications and are not covered by this
License, and convey such a combined library under terms of your
choice, if you do both of the following:

a) Accompany the combined library with a copy of the same work based
on the Library, uncombined with any other library facilities,
conveyed under the terms of this License.

b) Give prominent notice with the combined library that part of it
is a work based on the Library, and explaining where to find the
accompanying uncombined form of the same work.

6. Revised Versions of the GNU Lesser General Public License.

The Free Software Foundation may publish revised and/or new versions
of the GNU Lesser General Public License from time to time. Such new
versions will be similar in spirit to the present version, but may
differ in detail to address new problems or concerns.

Each version is given a distinguishing version number. If the
Library as you received it specifies that a certain numbered version
of the GNU Lesser General Public License "or any later version"
applies to it, you have the option of following the terms and
conditions either of that published version or of any later version
published by the Free Software Foundation. If the Library as you
received it does not specify a version number of the GNU Lesser
General Public License, you may choose any version of the GNU Lesser
General Public License ever published by the Free Software Foundation.

If the Library as you received it specifies that a proxy can decide
whether future versions of the GNU Lesser General Public License shall
apply, that proxy's public statement of acceptance of any version is
permanent authorization for you to choose that version for the
Library.

Copyright: © 2019 B.S.E. Business Solution Enterprises LTD (email : info@bse.com.cy). All Rights Reserved.
 */

if (!defined('ABSPATH')) {
    exit;
}
$all_plugins = apply_filters('active_plugins', get_option('active_plugins'));
if (!stripos(implode($all_plugins), 'woocommerce.php')) {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
    deactivate_plugins(plugin_basename(__FILE__), true);
    exit;
}

define('JCC_PAYMENT_GATEWAY_REDIRECT_VERSION', '1.0.7');
define('JCC_PAYMENT_GATEWAY_REDIRECT_MAIN_FILE', __FILE__);
define('JCC_PAYMENT_GATEWAY_REDIRECT_PLUGIN_URL', untrailingslashit(plugins_url(basename(plugin_dir_path(__FILE__)), basename(__FILE__))));
define('JCC_PAYMENT_GATEWAY_REDIRECT_PLUGIN_PATH', untrailingslashit(plugin_dir_path(__FILE__)));

if (!class_exists('JCC_PAYMENT_GATEWAY_REDIRECT')) {
    class JCC_PAYMENT_GATEWAY_REDIRECT
    {

        protected static $instance;
        public static function get_instance()
        {
            if (null === self::$instance) {
                self::$instance = new self();
            }
            return self::$instance;
        }
        public function __construct()
        {
            add_action('plugins_loaded', array($this, 'initialize'));

        }
        public function initialize()
        {
            include_once dirname(__FILE__) . '/includes/class-wc-gateway-jcc-payment-gateway-redirect.php';
            add_filter('woocommerce_payment_gateways', array($this, 'gatewaymethods'));
            add_filter('plugin_row_meta', array($this, 'plugin_data2'), 10, 4);
            //load_plugin_textdomain( 'JCC_Payment_Redirect', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
            add_action( 'pre_current_active_plugins', array($this,'render_scripts'),10,1 );
            if (version_compare( WC_VERSION, '4.2.0', '>' )) { 
				add_action( 'admin_notices', array($this,'unsupported') );
			}
        }
		function unsupported() {
          echo '<div class="notice notice-error"><p>JCC Payment Gateway Redirect is no longer maintained and have not been tested with your current version of WooCommerce. 
          Strongly recommended to download the supported version of the plugin by visiting 
          <a href="https://shop.bse.com.cy/product/plugin-and-support-for-jcc-payment-gateway-for-woocommerce/">the offical store of the provider</a></p></div>';
		}
        public function render_scripts($aa) {
            wp_enqueue_script( 'lightbulbjcc', plugin_dir_url(__FILE__) . '/includes/lightbulbjcc.js', array( 'jquery' ) );
        }
        public function plugin_data2($plugin_meta, $plugin_file, $plugin_data, $status)
        {
          if ($plugin_file == 'bse-jcc-payment-gateway-redirect/jcc-payment-gateway-redirect.php') {
            $plugin_meta['premium'] = '<strong><a href="https://shop.bse.com.cy/product-category/wordpress/woocommerce-plugins/" target="_blank"><span style="color:orange" class="dashicons dashicons-lightbulb lightbulbjcc"></span>Premium Extensions</a></strong>';
          }
            return $plugin_meta;
        }
        public function gatewaymethods($methods)
        {
            $methods[] = 'WC_Gateway_JCC_Payment_Redirect';
            return $methods;
        }
        public function jcc_payment_gateway_redirect_add_section($sections)
        {
            $sections['JCC_Payment_Gateway_Redirect'] = __('JCC', 'JCC_Payment_Redirect');
            return $sections;
        }
    }
    $GLOBAL['JCC_Payment_Gateway_Redirect'] = JCC_Payment_Gateway_Redirect::get_instance();
}
