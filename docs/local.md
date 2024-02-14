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
   - WordPress site title: `GoodBids Dev`
   - Multisite: `true`
   - PHP version to use: `8.1`
   - WordPress - Which version would you like:
     `6.4 or latest`
   - How would you like to source vip-go-mu-plugins: `Demo - Automatically fetched vip-go-mu-plugins`
   - How would you like to source application code: `Custom`: `/Absolute/Path/To/Local/Repo`
   - Enable Elasticsearch (needed by Enterprise Search)?: `false`
   - Enable phpMyAdmin: `optional`
   - Enable XDebug: `true`
   - Enable Mailpit: `false`
   - Enable Photon: `false`

If needed, here is a quick reference on restarting your environment:

```bash
vip dev-env --slug=goodbids start
npm run dev
vip dev-env --slug=goodbids stop

# Alternatively
bin/start
bin/start-vite
bin/stop
```

### Install Dependencies

For ACF Pro, copy `auth.json.dist` to `auth.json` in `client-mu-plugins/goodbids` and use config from [ACF's Website](https://www.advancedcustomfields.com/my-account/view-licenses/). (Credentials are in 1Password)

Then, run `composer install` in the following directories:

1. `root`: Run at project root.
2. `client-mu-plugins/goodbids`: GoodBids MU Plugin

Finally, run `npm install` from the root directory

Alternatively, running `bin/install` will install all composer and npm dependencies.

### Set up Environment Variables
Duplicate the file `client-mu-plugins/vip-env-vars.example.php` and rename it to `vip-env-vars.local.php`. Fill in any
env vars marked with `TODO`.  

## Local Environment URL

[http://goodbids.vipdev.lndo.site/](http://goodbids.vipdev.lndo.site/)

## Error Logs

Run the following command to [watch the logs](https://docs.wpvip.com/technical-references/vip-local-development-environment/#h-php):

```sh
vip dev-env logs --service=php --follow --slug=goodbids
```

## IntelliSense Set Up

Because the repo does not have the core WordPress files, IntelliSense does not know any of the WordPress functions/variables.

In order to get IntelliSense to work you will need to do these steps:

1. Clone the [vip-go-mu-plugins](https://github.com/Automattic/vip-go-mu-plugins)
2. Include the path to your local `vip-go-mu-plugins` directory in your PHP includes path.

   - If you are using Visual Studio Code and have the Extensions [PHP Intelephense](https://marketplace.visualstudio.com/items?itemName=bmewburn.vscode-intelephense-client) then go to the extensions settings.
   - [PhpStorm Instructions](https://www.jetbrains.com/help/phpstorm/configuring-include-paths.html) on adding include paths.

3. Next include the WordPress Core directory that was installed using VIP CLI. It should be at `/Absolute/Path/To/.local/share/vip/dev-environment/goodbids`
4. You may need to reload your code editor once you have added both include paths.

## Code Standards

We are using `WordPress-VIP-Go` as the code standard. Follow the install instructions on [WP VIP](https://docs.wpvip.com/how-tos/php_codesniffer/) to set up `phpcs`.

Once that is all set up, copy the file `phpcs.xml.dist` in the goodbids repo and save it as `phpcs.xml`

Set your `phpcs` standard to point to `/Absolute/Path/To/goodbids/.phpcs.xml",`

## Local Config Override

You can override the default local config by adding a `client-mu-plugins/goodbids/config.local.json` file. This will allow you to override specific settings without risk of committing to the repository.
