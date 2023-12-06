# Local Installation Guide

> _If at any point you run into an issue, please refer back to the repo's [main README](../README.md) and follow the WP VIP Local Development Guidebook._

1. Clone repo:  
`git clone https://github.com/Good-Bids/goodbids.git`
2. Configure Remote WPVIP Repo:   
`git remote add wpvip git@github.com:wpcomvip/goodbids.git`
3. Install VIP CLI: [Setup Guide](https://docs.wpvip.com/technical-references/vip-cli/installing-vip-cli/)
4. Create WP VIP Dev Environment:  
`vip dev-env create --slug=goodbids --multisite`  
   **When prompted, here are the recommended responses:**
   * WordPress site title: `GoodBids Dev`
   * Multisite: `true`
   * PHP version to use: `8.1`
   * WordPress - Which version would you like: 
      `6.4 or latest`
   * How would you like to source vip-go-mu-plugins: `Demo - Automatically fetched vip-go-mu-plugins`
   * How would you like to source application code: `Custom`: `/Absolute/Path/To/Local/Repo`
   * Enable Elasticsearch (needed by Enterprise Search)?: `false`
   * Enable phpMyAdmin: `optional`
   * Enable XDebug: `true`
   * Enable Mailpit: `false`
   * Enable Photon: `false`

If needed, here is a quick reference on restarting your environment:
```
vip dev-env --slug=goodbids start
vip dev-env --slug=goodbids stop
```

### Install Dependencies

For ACF Pro, copy `auth.json.dist` to `auth.json` in `client-mu-plugins/goodbids` and use config from [ACF's Website](https://www.advancedcustomfields.com/my-account/view-licenses/). (Credentials are in 1Password)

Then, run `composer install` in the following directories:
1. `root`: Run at project root.
2. `client-mu-plugins/goodbids`: GoodBids MU Plugin

## Local Environment URL

[http://goodbids.vipdev.lndo.site/](http://goodbids.vipdev.lndo.site/)

