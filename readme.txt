=== Coinbase ===
Contributors: isaacwaller
Tags: bitcoin, coinbase, 
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Stable tag: trunk
Requires at least: 3.0
Tested up to: 3.8

A Wordpress plugin and widget that lets you accept bitcoin on your site!

== Description ==
A Wordpress plugin and widget that lets you accept bitcoin on your site! Use the `[coinbase_button]` shortcode along with the parameters on [this page](https://coinbase.com/api/doc/1.0/buttons/create.html). Example:

`[coinbase_button name="Socks" price_string="10.00" price_currency_iso="CAD"]`

A menu widget is also included.

== Installation ==

1. Visit your admin section and click Plugins -> Add New.  Then search for "Coinbase".

2. Once you’ve installed the plugin, visit the Settings -> Coinbase page, and follow the instruction to connect it with your Coinbase account.

Notice that you are only authenticating the widget to create payment buttons with your Coinbase account, and nothing else.  It won’t have access to send, receive, or do anything else with your account.

3. Now that the widget is enabled you can add a bitcoin payment button anywhere on your blog using one of two methods:

* a "short code" that looks like this:

`[coinbase_button name="Alpaca Socks" price_string="10.00" price_currency_iso="CAD"]`

You can add any customizable values as described in our documentation.  This works on any page or location of your site.

* using the WordPress "widget" which will appear in the right sidebar of your app.

== Screenshots ==

1. Using the Coinbase menu widget, you can add bitcoin buttons to your sidebar.
2. Use the coinbase_button shortcode to insert buttons into your posts.
3. Configure the plugin from the Settings menu.