=== Coinbase ===
Contributors: isaacwaller, aianus
Tags: bitcoin, coinbase,
License: MIT
License URI: http://opensource.org/licenses/MIT
Stable tag: trunk
Requires at least: 3.0
Tested up to: 4.3.1

A Wordpress plugin and widget that lets you accept bitcoin on your site!

== Description ==
A Wordpress plugin and widget that lets you accept bitcoin on your site! Use the `[coinbase_button]` shortcode along with the parameters on [this page](https://developers.coinbase.com/api/v2#create-checkout). Example:

`[coinbase_button name="Socks" amount="10.00" currency="CAD"]`

A menu widget is also included.

== Installation ==

0. Generate an API key with the 'wallet:checkouts:create' permission at https://coinbase.com/settings/api. For security reasons, please do not grant any other permissions to this key. If you don't have a Coinbase account, sign up at https://coinbase.com/merchants. Coinbase offers daily payouts for merchants in the United States. For more infomation on setting up payouts, see https://coinbase.com/docs/merchant_tools/payouts.

1. Visit your admin section and click Plugins -> Add New.  Then search for "Coinbase".

2. Once you’ve installed the plugin, visit the Settings -> Coinbase page and enter the credentials obtained in step 0.

3. Now that the widget is enabled you can add a bitcoin payment button anywhere on your blog using one of two methods:

* a "short code" that looks like this:

`[coinbase_button name=”Alpaca Socks” amount=”10.00” currency=”CAD”]`

You can add any customizable values as described in our documentation.  This works on any page or location of your site.

* using the WordPress "widget" which will appear in the right sidebar of your app.

== Screenshots ==

1. Install from the plugin repository
2. Configure the plugin from the Settings menu.
4. Using the Coinbase menu widget, you can add bitcoin buttons to your sidebar.
