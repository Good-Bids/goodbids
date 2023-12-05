# Local Install
Clone repo: `git clone https://github.com/Good-Bids/goodbids.git`

Install VIP CLI:
  - Make sure you are running the minimum installed version of Node.js v18 and npm v8: `node -v && npm -v`
	- Install VIP-CLI `npm install -g @automattic/vip`
	- [Authenticate VIP-CLI](https://docs.wpvip.com/technical-references/vip-cli/installing-vip-cli/#h-authenticate-vip-cli)

Go to your local repo directory and run `vip dev-env create --slug=goodbids`

Which will ask you a bunch of options:

```
WordPress site title: GoodBids Dev
Multisite: true
PHP version to use: 8.1
WordPress - Which version would you like: 6.4 or latest
How would you like to source vip-go-mu-plugins: image
  Use: vip-go-mu-plugins
How would you like to source application code: .
Enable Elasticsearch (needed by Enterprise Search)?: false
Enable phpMyAdmin: optional
Enable XDebug: true
Enable Mailpit: false
Enable Photon: false
```
One the install has run then update the url for the app code to pull from your local files `vip dev-env update --app-code=/Users/[LOCAL-DIRECTORY] --slug=goodbids`

After you have update the `app-code` stop the server and then restart it

```
vip dev-env --slug=goodbids start
vip dev-env --slug=goodbids stop
```

Then you should able to go to [http://goodbids.vipdev.lndo.site/](http://goodbids.vipdev.lndo.site/ )
