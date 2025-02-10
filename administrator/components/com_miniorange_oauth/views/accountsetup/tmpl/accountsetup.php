<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_miniorange_oauth
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
Use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Document\HtmlDocument;
use Joomla\CMS\Router\Route;

$document = Factory::getApplication()->getDocument();
$document->addScript(Uri::base() . 'components/com_miniorange_oauth/assets/js/bootstrap.js');
$document->addScript(Uri::base() . 'components/com_miniorange_oauth/assets/js/myscript.js');
$document->addStyleSheet(Uri::base() . 'components/com_miniorange_oauth/assets/css/miniorange_oauth.css');
$document->addStyleSheet('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');

HTMLHelper::_('jquery.framework');

?>  
<?php
if (MoOAuthUtility::is_curl_installed() == 0) { ?>
    <p class="mo_oauth_red_color">(<?php echo Text::_('COM_MINIORANGE_OAUTH_WARNING');?>: <a href="http://php.net/manual/en/curl.installation.php" target="_blank"><?php echo Text::_('COM_MINIORANGE_OAUTH_PHP_CURL');?>)</p>
    <?php
}
$active_tab = Factory::getApplication()->input->get->getArray();
$oauth_active_tab = isset($active_tab['tab-panel']) && !empty($active_tab['tab-panel']) ? $active_tab['tab-panel'] : 'configuration';
global $license_tab_link;
$license_tab_link="index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=license";
$current_user = Factory::getUser();
if(!PluginHelper::isEnabled('system', 'miniorangeoauth')) {
    ?>
    <div id="system-message-container">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <div class="alert alert-error">
            <h4 class="alert-heading"><?php echo Text::_('COM_MINIORANGE_OAUTH_WARNING');?></h4>
            <div class="alert-message">
                <h4><?php echo Text::_('COM_MINIORANGE_OAUTH_WARNING_TEXT');?>
                </h4>
                <h4><?php echo Text::_('COM_MINIORANGE_OAUTH_STEPS');?></h4>
                <ul>
                    <li><?php echo Text::_('COM_MINIORANGE_OAUTH_STEPS_S1');?></li>
                    <li><?php echo Text::_('COM_MINIORANGE_OAUTH_STEPS_S2');?></li>
                    <li><?php echo Text::_('COM_MINIORANGE_OAUTH_STEPS_S3');?></li>
                </ul>
            </div>
        </div>
    </div>
<?php } ?>

<div id="TC_Modal" class="TC_modal">
    <div class="modal-content">
        <div class="row">
            <h5 class="col-sm-11"><?php echo Text::_('COM_MINIORANGE_OAUTH_TERMS_AND_CONDITIONS');?></h5>
            <span class="col-sm-1 close" onclick="closeModel()"><span>&times;</span></span>
        </div>
        <div>
            <hr>
            <ul> 
                <li><?php echo Text::_('COM_MINIORANGE_OAUTH_TERMS_AND_CONDITIONS1');?></li>
                <li><?php echo Text::_('COM_MINIORANGE_OAUTH_TERMS_AND_CONDITIONS2');?></li>
                <li><?php echo Text::_('COM_MINIORANGE_OAUTH_TERMS_AND_CONDITIONS3');?></li>
                <li><?php echo Text::_('COM_MINIORANGE_OAUTH_TERMS_AND_CONDITIONS4');?></li>
                <li>
                    <form method="post" name="f" action="<?php echo Route::_('index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.saveAdminMail'); ?>" >
                        <?php
                            $dVar=new JConfig(); 
                            $check_email = $dVar->mailfrom;
                            $call= new MoOauthCustomer();
                            $result=$call->getAccountDetails();
                            if($result['contact_admin_email']!=NULL)
                            {
                                $check_email =$result['contact_admin_email'];
                            }
                        ?>
                        <div class="row mt-3">
                            <div class="col-sm-5">
                                <input type="email" name="oauth_client_admin_email"  class="form-control" placeholder="<?php echo $check_email;?>">
                            </div>
                            <div class="col-sm-3">
                                <input type="submit" class="btn mo_oauth_all_btn">
                            </div>
                        </div>                            
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>

<div class="row mo_oauth_navbar">
    <div class="col-sm-12">
        <button id="mo_TC"  onclick="show_TC_modal()" class="btn px-4 py-1 mo_oauth_nav_btn"> <i class="fa-solid fa-file-shield"></i> T&C</button>
        <a class="btn px-4 py-1 mo_oauth_nav_btn" href="<?php echo Uri::base()?>index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=support">
            <i class="fa-solid fa-envelope"></i>
            <?php echo Text::_('COM_MINIORANGE_OAUTH_SUPPORT');?>
        </a>
        <a class="btn px-4 py-1 mo_heading_export_btn" href="index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=exportConfiguration"><?php echo Text::_('COM_MINIORANGE_EXPORT_IMPORT');?></a>
    </div>
</div>

<div class="container-fluid p-0">
    <div class="row p-0 mx-2">
        <div id="mo_oauth_nav_parent" class="col-sm-12 p-0 m-0 mo_oauth_display-flex">
            <a id="configtab" class="p-3  mo_nav-tab mo_nav_tab_<?php echo $oauth_active_tab == 'configuration' ? 'active' : ''; ?>" href="#configuration" onclick="add_css_tab('#configtab');" data-toggle="tab">
                <span><i class="fa-solid fa-bars"> </i></span>
                <?php echo Text::_('COM_MINIORANGE_OAUTH_TAB1_CONFIGURE_OAUTH');?>
            </a>
            <a id="attributetab" class="p-3 mo_nav-tab mo_nav_tab_<?php echo $oauth_active_tab == 'attrrolemapping' ? 'active' : ''; ?>" href="#attrrolemapping" onclick="add_css_tab('#attributetab');" data-toggle="tab">
                <span><i class="fa-solid fa-address-card"></i></span>
                <?php echo Text::_('COM_MINIORANGE_OAUTH_USER_ATTRIBUTE_SETTINGS');?>
            </a>
            <a id= "advancetab" class="p-3 mo_nav-tab mo_nav_tab_<?php echo $oauth_active_tab == 'loginlogoutsettings' ? 'active' : ''; ?>" href="#loginlogoutsettings" onclick="add_css_tab('#advancetab');" data-toggle="tab">
                <span><i class="fa-solid fa-gears"></i></span>
                <?php echo Text::_('COM_MINIORANGE_OAUTH_ADVANCE_SETTINGS');?>
                <span><sup><img class="crown_img_small ml-2" src="<?php echo Uri::base();?>/components/com_miniorange_oauth/assets/images/crown.webp"></a></sup></span>
            </a>
            <a id="licensetab" class="p-3 mo_nav-tab mo_nav_tab_<?php echo $oauth_active_tab == 'license' ? 'active' : ''; ?>" href="#licensing-plans" onclick="add_css_tab('#licensetab');" data-toggle="tab">
                <span><i class="fa-solid fa-coins"></i></span>
                <?php echo Text::_('COM_MINIORANGE_OAUTH_TAB5_LICENSING_PLANS');?>
            </a>
            <a id="proxytab" class="p-3 mo_nav-tab mo_nav_tab_<?php echo $oauth_active_tab == 'proxy' ? 'active' : ''; ?>" href="#proxy-setup" onclick="add_css_tab('#proxytab');" data-toggle="tab">
         <span><i class="fa-solid fa-network-wired"></i></span>
         <?php echo "Proxy Setup" ?>
           </a>
        </div>
    </div>
</div>
<div class="tab-content mx-2 mo_container" id="myTabContent">
        <div id="configuration" class="tab-pane <?php echo $oauth_active_tab == 'configuration' ? 'active' : ''; ?>">
            <div class="row">
                <div class="col-sm-12">
                    <?php 
                        moOAuthConfiguration();
                    ?>
                </div>
            </div>
        </div>
        <div id="attrrolemapping" class="tab-pane <?php echo $oauth_active_tab == 'attrrolemapping' ? 'active' : ''; ?>">
            <div class="row">
                <div class="col-sm-12">
                    <?php attributerole(); ?>
                </div>
            </div>
        </div>
        <div id="loginlogoutsettings" class="tab-pane <?php echo $oauth_active_tab == 'loginlogoutsettings' ? 'active' : ''; ?>">
            <div class="row">
                <div class="col-sm-12">
                    <?php loginlogoutsettings(); ?>
                </div>
            </div>
        </div>
        <div id="exportConfiguration" class="tab-pane <?php echo $oauth_active_tab == 'exportConfiguration' ? 'active' : ''; ?>">
            <div class="row">
                <div class="col-sm-12" >
                    <?php exportConfiguration();?>
                </div>
            </div>
        </div>
        <div id="proxy-setup" class="tab-pane <?php echo $oauth_active_tab == 'proxy' ? 'active' : ''; ?>">
            <div class="row">
                <div class="col-sm-12">
                    <?php proxy_setup(); ?>
                </div>
            </div>
        </div>
        <div id="support" class="tab-pane <?php echo $oauth_active_tab == 'support' ? 'active' : ''; ?>">
            <div class="row">
                <div class="col-sm-12">
                    <?php support();   ?>
                </div>
            </div>
        </div>
        <div id="licensing-plans" class="tab-pane <?php echo $oauth_active_tab == 'license' ? 'active' : ''; ?>">
            <div class="row">
                <div class="col-sm-12">
                    <?php echo mo_oauth_licensing_plan(); ?>
                </div>
            </div>
        </div>
      
</div>
    </div>
<?php
function getAppJason(){
    return '{	
        "azure": {
            "label":"Azure AD", "type":"oauth", "image":"azure.png", "scope": "openid email profile", "authorize": "https://login.microsoftonline.com/{tenant}/oauth2/v2.0/authorize", "token": "https://login.microsoftonline.com/{tenant}/oauth2/v2.0/token", "userinfo":"https://graph.microsoft.com/beta/me", "guide":"https://plugins.miniorange.com/azure-ad-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-windowslive"
        },
        "azureb2c": {
            "label":"Azure B2C", "type":"openidconnect", "image":"azure.png", "scope": "openid email", "authorize": "https://{tenant}.b2clogin.com/{tenant}.onmicrosoft.com/{policy}/oauth2/v2.0/authorize", "token": "https://{tenant}.b2clogin.com/{tenant}.onmicrosoft.com/{policy}/oauth2/v2.0/token", "userinfo": "", "guide":"https://plugins.miniorange.com/azure-ad-b2c-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-windowslive"
        },
        "cognito": {
            "label":"AWS Cognito", "type":"oauth", "image":"cognito.png", "scope": "openid", "authorize": "https://{domain}/oauth2/authorize", "token": "https://{domain}/oauth2/token", "userinfo": "https://{domain}/oauth2/userInfo", "guide":"https://plugins.miniorange.com/aws-cognito-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-amazon"
        },
        "adfs": {
            "label":"ADFS", "type":"openidconnect", "image":"adfs.png", "scope": "openid", "authorize": "https://{domain}/adfs/oauth2/authorize/", "token": "https://{domain}/adfs/oauth2/token/", "userinfo": "", "guide":"", "logo_class":"fa fa-windowslive"
        },
        "whmcs": {
            "label":"WHMCS", "type":"oauth", "image":"whmcs.png", "scope": "openid profile email", "authorize": "https://{domain}/oauth/authorize.php", "token": "https://{domain}/oauth/token.php", "userinfo": "https://{domain}/oauth/userinfo.php?access_token=", "guide":"https://plugins.miniorange.com/whmcs-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-lock"
        },
        "keycloak": {
            "label":"keycloak", "type":"openidconnect", "image":"keycloak.png", "scope": "openid", "authorize": "https://{domain}/realms/{realm}/protocol/openid-connect/auth", "token": "https://{domain}/realms/{realm}/protocol/openid-connect/token", "userinfo": "{domain}/realms/{realm}/protocol/openid-connect/userinfo", "guide":"https://plugins.miniorange.com/keycloak-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-lock"
        },
        "slack": {
            "label":"Slack", "type":"oauth", "image":"slack.png", "scope": "users.profile:read", "authorize": "https://slack.com/oauth/authorize", "token": "https://slack.com/api/oauth.access", "userinfo": "https://slack.com/api/users.profile.get", "guide":"https://plugins.miniorange.com/slack-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-slack"
        },
        "discord": {
            "label":"Discord", "type":"oauth", "image":"discord.png", "scope": "identify email", "authorize": "https://discordapp.com/api/oauth2/authorize", "token": "https://discordapp.com/api/oauth2/token", "userinfo": "https://discordapp.com/api/users/@me", "guide":"https://plugins.miniorange.com/discord-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-lock"
        },
        "invisioncommunity": {
            "label":"Invision Community", "type":"oauth", "image":"invis.png", "scope": "email", "authorize": "{domain}/oauth/authorize/", "token": "https://{domain}/oauth/token/", "userinfo": "https://{domain}/oauth/me", "guide":"https://plugins.miniorange.com/joomla-oauth-sign-on-sso-using-invision-community", "logo_class":"fa fa-lock"
        },
        "bitrix24": {
            "label":"Bitrix24", "type":"oauth", "image":"bitrix24.png", "scope": "user", "authorize": "https://{accountid}.bitrix24.com/oauth/authorize", "token": "https://{accountid}.bitrix24.com/oauth/token", "userinfo": "https://{accountid}.bitrix24.com/rest/user.current.json?auth=", "guide":"https://plugins.miniorange.com/bitrix24-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-clock-o"
        },
        "wso2": {
            "label":"WSO2", "type":"oauth", "image":"wso2.png", "scope": "openid", "authorize": "https://{domain}/wso2/oauth2/authorize", "token": "https://{domain}/wso2/oauth2/token", "userinfo": "https://{domain}/wso2/oauth2/userinfo", "guide":"https://plugins.miniorange.com/wso2-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-lock"
        },
        "okta": {
            "label":"Okta", "type":"openidconnect", "image":"okta.png", "scope": "openid email profile", "authorize": "https://{domain}/oauth2/default/v1/authorize", "token": "https://{domain}/oauth2/default/v1/token", "userinfo": "", "guide":"https://plugins.miniorange.com/login-with-okta-using-joomla", "logo_class":"fa fa-lock"
        },
        "onelogin": {
            "label":"OneLogin", "type":"openidconnect", "image":"onelogin.png", "scope": "openid", "authorize": "https://{domain}/oidc/auth", "token": "https://{domain}/oidc/token", "userinfo": "", "guide":"https://plugins.miniorange.com/onelogin-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-lock"
        },
        "gapps": {
            "label":"Google", "type":"oauth", "image":"google.png", "scope": "email", "authorize": "https://accounts.google.com/o/oauth2/auth", "token": "https://www.googleapis.com/oauth2/v4/token", "userinfo": "https://www.googleapis.com/oauth2/v1/userinfo", "guide":"https://plugins.miniorange.com/google-apps-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-google-plus"
        },
        "fbapps": {
            "label":"Facebook", "type":"oauth", "image":"facebook.png", "scope": "public_profile email", "authorize": "https://www.facebook.com/dialog/oauth", "token": "https://graph.facebook.com/v2.8/oauth/access_token", "userinfo": "https://graph.facebook.com/me/?fields=id,name,email,age_range,first_name,gender,last_name,link", "guide":"https://plugins.miniorange.com/facebook-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-facebook"
        },
        "gluu": {
            "label":"Gluu Server", "type":"oauth", "image":"gluu.png", "scope": "openid", "authorize": "http://{domain}/oxauth/restv1/authorize", "token": "http://{domain}/oxauth/restv1/token", "userinfo": "http:///{domain}/oxauth/restv1/userinfo", "guide":"https://plugins.miniorange.com/gluu-server-single-sign-on-sso-joomla-login-using-gluu", "logo_class":"fa fa-lock"
        },
        "linkedin": {
            "label":"LinkedIn", "type":"oauth", "image":"linkedin.png", "scope": "openid email profile", "authorize": "https://www.linkedin.com/oauth/v2/authorization", "token": "https://www.linkedin.com/oauth/v2/accessToken", "userinfo": "https://api.linkedin.com/v2/me", "guide":"https://plugins.miniorange.com/linkedin-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-linkedin-square"
        },
        "strava": {
            "label":"Strava", "type":"oauth", "image":"strava.png", "scope": "public", "authorize": "https://www.strava.com/oauth/authorize", "token": "https://www.strava.com/oauth/token", "userinfo": "https://www.strava.com/api/v3/athlete", "guide":"https://plugins.miniorange.com/strava-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-lock"
        },
        "fitbit": {
            "label":"FitBit", "type":"oauth", "image":"fitbit.png", "scope": "profile", "authorize": "https://www.fitbit.com/oauth2/authorize", "token": "https://api.fitbit.com/oauth2/token", "userinfo": "https://www.fitbit.com/1/user", "guide":"https://plugins.miniorange.com/fitbit-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-lock"
        },
        "box": {
            "label":"Box", "type":"oauth", "image":"box.png", "scope": "root_readwrite", "authorize": "https://account.box.com/api/oauth2/authorize", "token": "https://api.box.com/oauth2/token", "userinfo": "https://api.box.com/2.0/users/me", "guide":"https://plugins.miniorange.com/box-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-lock"
        },
        "github": {
            "label":"GitHub", "type":"oauth", "image":"github.png", "scope": "user repo", "authorize": "https://github.com/login/oauth/authorize", "token": "https://github.com/login/oauth/access_token", "userinfo": "https://api.github.com/user", "guide":"https://plugins.miniorange.com/github-single-sign-on-sso-joomla-oauth-openid-connect", "logo_class":"fa fa-github"
        },
        "gitlab": {
            "label":"GitLab", "type":"oauth", "image":"gitlab.png", "scope": "read_user", "authorize": "https://gitlab.com/oauth/authorize", "token": "http://gitlab.com/oauth/token", "userinfo": "https://gitlab.com/api/v4/user", "guide":"https://plugins.miniorange.com/gitlab-single-sign-on-sso-joomla-oauth-openid-connect", "logo_class":"fa fa-gitlab"
        },
        "clever": {
            "label":"Clever", "type":"oauth", "image":"clever.png", "scope": "read:students read:teachers read:user_id", "authorize": "https://clever.com/oauth/authorize", "token": "https://clever.com/oauth/tokens", "userinfo": "https://api.clever.com/v1.1/me", "guide":"https://plugins.miniorange.com/clever-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-lock"
        },
        "salesforce": {
            "label":"Salesforce", "type":"oauth", "image":"salesforce.png", "scope": "email", "authorize": "https://login.salesforce.com/services/oauth2/authorize", "token": "https://login.salesforce.com/services/oauth2/token", "userinfo": "https://login.salesforce.com/services/oauth2/userinfo", "guide":"https://plugins.miniorange.com/salesforce-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-lock"
        },
        "reddit": {
            "label":"Reddit", "type":"oauth", "image":"reddit.png", "scope": "identity", "authorize": "https://www.reddit.com/api/v1/authorize", "token": "https://www.reddit.com/api/v1/access_token", "userinfo": "https://www.reddit.com/api/v1/me", "guide":"https://plugins.miniorange.com/reddit-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-reddit"
        },
        "paypal": {
            "label":"PayPal", "type":"openidconnect", "image":"paypal.png", "scope": "openid", "authorize": "https://www.paypal.com/signin/authorize", "token": "https://api.paypal.com/v1/oauth2/token", "userinfo": "", "guide":"https://plugins.miniorange.com/paypal-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-paypal"
        },
        "swiss-rx-login": {
            "label":"Swiss RX Login", "type":"openidconnect", "image":"swiss-rx-login.png", "scope": "anonymous", "authorize": "https://www.swiss-rx-login.ch/oauth/authorize", "token": "https://swiss-rx-login.ch/oauth/token", "userinfo": "", "guide":"", "logo_class":"fa fa-lock"
        },
        "yahoo": {
            "label":"Yahoo", "type":"openidconnect", "image":"yahoo.png", "scope": "openid", "authorize": "https://api.login.yahoo.com/oauth2/request_auth", "token": "https://api.login.yahoo.com/oauth2/get_token", "userinfo": "", "guide":"https://plugins.miniorange.com/yahoo-single-sign-on-sso-joomla-oauth-openid-connect", "logo_class":"fa fa-yahoo"
        },
        "spotify": {
            "label":"Spotify", "type":"oauth", "image":"spotify.png", "scope": "user-read-private user-read-email", "authorize": "https://accounts.spotify.com/authorize", "token": "https://accounts.spotify.com/api/token", "userinfo": "https://api.spotify.com/v1/me", "guide":"https://plugins.miniorange.com/spotify-single-sign-on-sso-joomla-oauth-openid-connect", "logo_class":"fa fa-spotify"
        },
        "eveonlinenew": {
            "label":"Eve Online", "type":"oauth", "image":"eveonline.png", "scope": "publicData", "authorize": "https://login.eveonline.com/oauth/authorize", "token": "https://login.eveonline.com/oauth/token", "userinfo": "https://esi.evetech.net/verify", "guide":"https://plugins.miniorange.com/oauth-openid-connect-single-sign-on-sso-into-joomla-using-eve-online", "logo_class":"fa fa-lock"
        },
        "vkontakte": {
            "label":"VKontakte", "type":"oauth", "image":"vk.png", "scope": "openid", "authorize": "https://oauth.vk.com/authorize", "token": "https://oauth.vk.com/access_token", "userinfo": "https://api.vk.com/method/users.get?fields=id,name,email,age_range,first_name,gender,last_name,link&access_token=", "guide":"https://plugins.miniorange.com/vkontakte-single-sign-on-sso-joomla-oauth-openid-connect", "logo_class":"fa fa-vk"
        },
        "pinterest": {
            "label":"Pinterest", "type":"oauth", "image":"pinterest.png", "scope": "read_public", "authorize": "https://api.pinterest.com/oauth/", "token": "https://api.pinterest.com/v1/oauth/token", "userinfo": "https://api.pinterest.com/v1/me/", "guide":"https://plugins.miniorange.com/pinterest-single-sign-on-sso-joomla-oauth-openid-connect", "logo_class":"fa fa-pinterest"
        },
        "vimeo": {
            "label":"Vimeo", "type":"oauth", "image":"vimeo.png", "scope": "public", "authorize": "https://api.vimeo.com/oauth/authorize", "token": "https://api.vimeo.com/oauth/access_token", "userinfo": "https://api.vimeo.com/me", "guide":"https://plugins.miniorange.com/vimeo-single-sign-on-sso-joomla-oauth-openid-connect", "logo_class":"fa fa-vimeo"
        },
        "deviantart": {
            "label":"DeviantArt", "type":"oauth", "image":"devart.png", "scope": "browse", "authorize": "https://www.deviantart.com/oauth2/authorize", "token": "https://www.deviantart.com/oauth2/token", "userinfo": "https://www.deviantart.com/api/v1/oauth2/user/profile", "guide":"https://plugins.miniorange.com/deviantart-sso-single-sign-on-into-joomla-using-oauth-openid-connect", "logo_class":"fa fa-deviantart"
        },
        "dailymotion": {
            "label":"Dailymotion", "type":"oauth", "image":"dailymotion.png", "scope": "email", "authorize": "https://www.dailymotion.com/oauth/authorize", "token": "https://api.dailymotion.com/oauth/token", "userinfo": "https://api.dailymotion.com/user/me?fields=id,username,email,first_name,last_name", "guide":"https://plugins.miniorange.com/dailymotion-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-lock"
        },
        "meetup": {
            "label":"Meetup", "type":"oauth", "image":"meetup.png", "scope": "basic", "authorize": "https://secure.meetup.com/oauth2/authorize", "token": "https://secure.meetup.com/oauth2/access", "userinfo": "https://api.meetup.com/members/self", "guide":"", "logo_class":"fa fa-lock"
        },
        "autodesk": {
            "label":"Autodesk", "type":"oauth", "image":"autodesk.png", "scope": "user:read user-profile:read", "authorize": "https://developer.api.autodesk.com/authentication/v1/authorize", "token": "https://developer.api.autodesk.com/authentication/v1/gettoken", "userinfo": "https://developer.api.autodesk.com/userprofile/v1/users/@me", "guide":"https://plugins.miniorange.com/autodesk-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-lock"
        },
        "zendesk": {
            "label":"Zendesk", "type":"oauth", "image":"zendesk.png", "scope": "read write", "authorize": "https://{domain}/oauth/authorizations/new", "token": "https://{domain}/oauth/tokens", "userinfo": "https://{domain}/api/v2/users", "guide":"https://plugins.miniorange.com/login-with-zendesk-as-an-oauth-openid-connect-server", "logo_class":"fa fa-lock"
        },
        "laravel": {
            "label":"Laravel", "type":"oauth", "image":"laravel.png", "scope": "", "authorize": "http://{domain}/oauth/authorize", "token": "http://{domain}/oauth/token", "userinfo": "http://{domain}}/api/user/get", "guide":"https://plugins.miniorange.com/login-with-joomla-oauth-sign-on-sso-using-laravel-passport", "logo_class":"fa fa-lock"
        },
        "identityserver": {
            "label":"Identity Server", "type":"oauth", "image":"identityserver.png", "scope": "openid", "authorize": "https://{domain}/connect/authorize", "token": "https://{domain}/connect/token", "userinfo": "https://{domain}/connect/introspect", "guide":"https://plugins.miniorange.com/identityserver3-oauth-openid-connect-single-sign-on-sso-into-joomla-identityserver3-sso-login", "logo_class":"fa fa-lock"
        },
        "nextcloud": {
            "label":"Nextcloud", "type":"oauth", "image":"nextcloud.png", "scope": "user:read:email", "authorize": "https://{domain}/index.php/apps/oauth2/authorize", "token": "https://{domain}/index.php/apps/oauth2/api/v1/token", "userinfo": "https://{domain}/ocs/v2.php/cloud/user?format=json", "guide":"https://plugins.miniorange.com/joomla-oauth-sign-on-sso-using-nextcloud", "logo_class":"fa fa-lock"
        },
        "twitch": {
            "label":"Twitch", "type":"oauth", "image":"twitch.png", "scope": "Analytics:read:extensions", "authorize": "https://id.twitch.tv/oauth2/authorize", "token": "https://id.twitch.tv/oauth2/token", "userinfo": "https://id.twitch.tv/oauth2/userinfo", "guide":"https://plugins.miniorange.com/twitch-single-sign-on-sso-joomla-oauth-openid-connect", "logo_class":"fa fa-lock"
        },
        "wildApricot": {
            "label":"Wild Apricot", "type":"oauth", "image":"wildApricot.png", "scope": "auto", "authorize": "https://{domain}/sys/login/OAuthLogin", "token": "https://oauth.wildapricot.org/auth/token", "userinfo": "https://api.wildapricot.org/v2.1/accounts/{accountid}/contacts/me", "guide":"https://plugins.miniorange.com/wildapricot-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-lock"
        },
        "connect2id": {
            "label":"Connect2id", "type":"oauth", "image":"connect2id.png", "scope": "openid", "authorize": "https://c2id.com/login", "token": "https://{domain}/token", "userinfo": "https://{domain}/userinfo", "guide":"https://plugins.miniorange.com/connect2id-single-sign-on-sso-joomla-oauth-openid-connect", "logo_class":"fa fa-lock"
        },
        "miniorange": {
            "label":"miniOrange", "type":"oauth", "image":"miniorange.png", "scope": "openid", "authorize": "https://login.xecurify.com/moas/idp/openidsso", "token": "https://login.xecurify.com/moas/rest/oauth/token", "userinfo": "https://logins.xecurify.com/moas/rest/oauth/getuserinfo", "guide":"https://plugins.miniorange.com/miniorange-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-lock"
        },
        "orcid": {
            "label":"ORCID", "type":"openidconnect", "image":"orcid.png", "scope": "openid", "authorize": "https://orcid.org/oauth/authorize", "token": "https://orcid.org/oauth/token", "userinfo": "", "guide":"https://plugins.miniorange.com/orcid-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-lock"
        },
        "diaspora": {
            "label":"Diaspora", "type":"openidconnect", "image":"diaspora.png", "scope": "openid", "authorize": "https://{domain}/api/openid_connect/authorizations/new", "token": "https://{domain}/api/openid_connect/access_tokens", "userinfo": "", "guide":"", "logo_class":"fa fa-lock"
        },
        "timezynk": {
            "label":"Timezynk", "type":"oauth", "image":"timezynk.png", "scope": "read:user", "authorize": "https://api.timezynk.com/api/oauth2/v1/auth", "token": "https://api.timezynk.com/api/oauth2/v1/token", "userinfo": "https://api.timezynk.com/api/oauth2/v1/userinfo", "guide":"", "logo_class":"fa fa-lock"
        },
        "Amazon": {
            "label":"Amazon", "type":"oauth", "image":"cognito.png", "scope": "profile", "authorize": "https://www.amazon.com/ap/oa", "token": "https://api.amazon.com/auth/o2/token", "userinfo": "https://api.amazon.com/user/profile", "guide":"https://plugins.miniorange.com/amazon-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-lock"
        },
        "Office 365": {
            "label":"Office 365", "type":"oauth", "image":"microsoft.webp", "scope": "openid email profile", "authorize": "https://login.microsoftonline.com/{tenant}/oauth2/v2.0/authorize", "token": "https://login.microsoftonline.com/{tenant}/oauth2/v2.0/token", "userinfo": "https://graph.microsoft.com/beta/me", "guide":"https://plugins.miniorange.com/joomla-oauth-single-sign-on-sso-using-office365", "logo_class":"fa fa-lock"
        },
        "Instagram": {
            "label":"Instagram", "type":"oauth", "image":"instagram.png", "scope": "user_profile user_media", "authorize": "https://api.instagram.com/oauth/authorize", "token": "https://api.instagram.com/oauth/access_token", "userinfo": "https://graph.instagram.com/me?fields=id,username&access_token=", "guide":"https://plugins.miniorange.com/instagram-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-lock"
        },
        "Line":{
            "label":"Line", "type":"oauth", "image":"line.webp", "scope": "profile openid email", "authorize": "https://access.line.me/oauth2/v2.1/authorize", "token": "https://api.line.me/oauth2/v2.1/token", "userinfo": "https://api.line.me/v2/profile", "guide":"https://plugins.miniorange.com/line-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-lock"
        },
        "PingFederate": {
            "label":"PingFederate", "type":"oauth", "image":"ping.webp", "scope": "openid", "authorize": "https://{domain}/as/authorization.oauth2", "token": "https://{domain}/as/token.oauth2", "userinfo": "https://{domain}/idp/userinfo.oauth2", "guide":"https://plugins.miniorange.com/ping-federate-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-lock"
        },
        "OpenAthens": {
            "label":"OpenAthens", "type":"oauth", "image":"openathens.webp", "scope": "openid", "authorize": "https://sp.openathens.net/oauth2/authorize", "token": "https://sp.openathens.net/oauth2/token", "userinfo": "https://sp.openathens.net/oauth2/userInfo", "guide":"https://plugins.miniorange.com/openathens-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-lock"
        },
        "Intuit": {
            "label":"Intuit", "type":"oauth", "image":"intuit.webp", "scope": "openid email profile", "authorize": "https://appcenter.intuit.com/connect/oauth2", "token": "https://oauth.platform.intuit.com/oauth2/v1/tokens/bearer", "userinfo": "https://accounts.platform.intuit.com/v1/openid_connect/userinfo", "guide":"https://plugins.miniorange.com/oauth-openid-connect-single-sign-on-sso-into-joomla-using-intuit", "logo_class":"fa fa-lock"
        },
        "Twitter": {
            "label":"Twitter", "type":"oauth", "image":"twitter-logo.webp", "scope": "email", "authorize": "https://api.twitter.com/oauth/authorize", "token": "https://api.twitter.com/oauth2/token", "userinfo": "https://api.twitter.com/1.1/users/show.json?screen_name=here-comes-twitter-screen-name", "guide":"https://plugins.miniorange.com/twitter-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-lock"
        },
        "WordPress": {
            "label":"WordPress", "type":"oauth", "image":"intuit.webp", "scope": "profile openid email custom", "authorize": "http://{site_base_url}/wp-json/moserver/authorize", "token": "http://{site_base_url}/wp-json/moserver/token", "userinfo": "http://{site_base_url}/wp-json/moserver/resource", "guide":"https://plugins.miniorange.com/oauth-openid-connect-single-sign-on-sso-into-joomla-using-wordpress", "logo_class":"fa fa-lock"
        },
        "Subscribestar": {
            "label":"Subscribestar", "type":"oauth", "image":"Subscriberstar-logo.png", "scope": "user.read user.email.read", "authorize": "https://www.subscribestar.com/oauth2/authorize", "token": "https://www.subscribestar.com/oauth2/token", "userinfo": "https://www.subscribestar.com/api/graphql/v1?query={user{name,email}}", "guide":"https://plugins.miniorange.com/subscribestar-oauth-openid-connect-single-sign-on-sso-into-joomla-subscribestar-sso-login", "logo_class":"fa fa-lock"
        },
        "Classlink": {
            "label":"Classlink", "type":"oauth", "image":"classlink.webp", "scope": "email profile oneroster full", "authorize": "https://launchpad.classlink.com/oauth2/v2/auth", "token": "https://launchpad.classlink.com/oauth2/v2/token", "userinfo": "https://nodeapi.classlink.com/v2/my/info", "guide":"https://plugins.miniorange.com/classlink-oauth-sso-openid-connect-single-sign-on-in-joomla-classlink-sso-login", "logo_class":"fa fa-lock"
        },
        "HP": {
            "label":"HP", "type":"oauth", "image":"hp-logo.webp", "scope": "read", "authorize": "https://{hp_domain}/v1/oauth/authorize", "token": "https://{hp_domain}/v1/oauth/token", "userinfo": "https://{hp_domain}/v1/userinfo", "guide":"https://plugins.miniorange.com/hp-sso-single-sign-on-into-joomla-using-oauth-openid-connect", "logo_class":"fa fa-lock"
        },
        "Basecamp": {
            "label":"Basecamp", "type":"oauth", "image":"basecamp-logo.webp", "scope": "openid", "authorize": "https://launchpad.37signals.com/authorization/new?type=web_server", "token": "https://launchpad.37signals.com/authorization/token?type=web_server", "userinfo": "https://launchpad.37signals.com/authorization.json", "guide":"https://plugins.miniorange.com/basecamp-oauth-and-openid-connect-single-sign-on-sso-login", "logo_class":"fa fa-lock"
        },
        "Feide": {
            "label":"Feide", "type":"oauth", "image":"feide-logo.webp", "scope": "openid", "authorize": "https://auth.dataporten.no/oauth/authorization", "token": "https://auth.dataporten.no/oauth/token", "userinfo": "https://auth.dataporten.no/openid/userinfo", "guide":"https://plugins.miniorange.com/feide-sso-single-sign-on-into-joomla-using-oauth-openid-connect", "logo_class":"fa fa-lock"
        },
        "Freja EID": {
            "label":"Freja EID", "type":"openidconnect", "image":"frejaeid-logo.webp", "scope": "openid profile email", "authorize": "https://oidc.prod.frejaeid.com/oidc/authorize", "token": "https://oidc.prod.frejaeid.com/oidc/token", "userinfo": "", "guide":"https://plugins.miniorange.com/freja-eid-sso-single-sign-on-into-joomla-using-oauth-openid-connect", "logo_class":"fa fa-lock"
        },
        "ServiceNow": {
            "label":"ServiceNow", "type":"oauth", "image":"servicenow-logo.webp", "scope": "email profile", "authorize": "https://{your-servicenow-domain}/oauth_auth.do", "token": "https://{your-servicenow-domain}/oauth_token.do", "userinfo": "https://{your-servicenow-domain}/{base-api-path}?access_token=", "guide":"https://plugins.miniorange.com/servicenow-sso-single-sign-on-into-joomla-using-oauth-openid-connect", "logo_class":"fa fa-lock"
        },
        "IMIS": {
            "label":"IMIS", "type":"oauth", "image":"imis-logo.webp", "scope": "openid", "authorize": "https://{your-imis-domain}/sso-pages/Aurora-SSO-Redirect.aspx", "token": "https://{your-imis-domain}/token", "userinfo": "https://{your-imis-domain}/api/iqa?queryname=$/Bearer_Info_Aurora", "guide":"https://plugins.miniorange.com/imis-sso-single-sign-on-into-joomla-using-oauth-openid-connect", "logo_class":"fa fa-lock"
        },
        "OpenedX": {
            "label":"OpenedX", "type":"oauth", "image":"openedx-logo.webp", "scope": "email profile", "authorize": "https://{your-domain}/oauth2/authorize", "token": "https://{your-domain}/oauth2/access_token", "userinfo": "https://{your-domain}/api/mobile/v1/my_user_info", "guide":"https://plugins.miniorange.com/open-edx-sso-single-sign-on-into-joomla-using-oauth-openid-connect", "logo_class":"fa fa-lock"
        },
        "Elvanto": {
            "label":"Elvanto", "type":"openidconnect", "image":"elvanto-logo.webp", "scope": "ManagePeople", "authorize": "https://api.elvanto.com/oauth?", "token": "https://api.elvanto.com/oauth/token", "userinfo": "", "guide":"https://plugins.miniorange.com/elvanto-sso-single-sign-on-into-joomla-using-oauth-openid-connect", "logo_class":"fa fa-lock"
        },
        "DigitalOcean": {
            "label":"DigitalOcean", "type":"oauth", "image":"digitalocean-logo.webp", "scope": "read", "authorize": "https://cloud.digitalocean.com/v1/oauth/authorize", "token": "https://cloud.digitalocean.com/v1/oauth/token", "userinfo": "https://api.digitalocean.com/v2/account", "guide":"https://plugins.miniorange.com/digital-ocean-sso-single-sign-on-into-joomla-using-oauth-openid-connect", "logo_class":"fa fa-lock"
        },
        "UNA": {
            "label":"UNA", "type":"openidconnect", "image":"una-logo.webp", "scope": "basic", "authorize": "https://{site-url}.una.io/oauth2/authorize?", "token": "https://{site-url}.una.io/oauth2/access_token", "userinfo": "", "guide":"https://plugins.miniorange.com/una-sso-single-sign-on-into-joomla-using-oauth-openid-connect", "logo_class":"fa fa-lock"
        },
        "MemberClicks": {
			"label":"MemberClicks", "type":"oauth", "image":"memberclicks-logo.webp", "scope": "read write", "authorize": "https://{orgId}.memberclicks.net/oauth/v1/authorize", "token": "https://{orgId}.memberclicks.net/oauth/v1/token", "userinfo": "https://{orgId}.memberclicks.net/api/v1/profile/me", "guide":"https://plugins.miniorange.com/memberclicks-sso-single-sign-on-into-joomla-using-oauth-openid-connect", "logo_class":"fa fa-lock"
		},
		"MineCraft": {
			"label":"MineCraft", "type":"openidconnect", "image":"minecraft-logo.webp", "scope": "openid", "authorize": "https://login.live.com/oauth20_authorize.srf", "token": "https://login.live.com/oauth20_token.srf", "userinfo": "", "guide":"https://plugins.miniorange.com/minecraft-sso-single-sign-on-into-joomla-using-oauth-openid-connect", "logo_class":"fa fa-lock"
		},
		"Neon CRM": {
			"label":"Neon CRM", "type":"oauth", "image":"neon-logo.webp", "scope": "openid", "authorize": "https://{your Neon CRM organization id}.z2systems.com/np/oauth/auth", "token": "https://{your Neon CRM organization id}.z2systems.com/np/oauth/token", "userinfo": "https://api.neoncrm.com/neonws/services/api/account/retrieveIndividualAccount?accountId=", "guide":"https://plugins.miniorange.com/neoncrm-sso-single-sign-on-into-joomla-using-oauth-openid-connect", "logo_class":"fa fa-lock"
		},
		"Canvas": {
			"label":"Canvas", "type":"oauth", "image":"canvas-logo.webp", "scope": "openid profile", "authorize": "https://{your-site-url}/login/oauth2/auth", "token": "https://{your-site-url}/login/oauth2/token", "userinfo": "https://{your-site-url}/login/v2.1/users/self", "guide":"https://plugins.miniorange.com/canvas-sso-single-sign-on-into-joomla-using-oauth-openid-connect", "logo_class":"fa fa-lock"
		},
		"Ticketmaster": {
			"label":"Ticketmaster", "type":"openidconnect", "image":"ticketmaster-logo.webp", "scope": "openid email", "authorize": "https://auth.ticketmaster.com/as/authorization.oauth2", "token": "https://auth.ticketmaster.com/as/token.oauth2", "userinfo": "", "guide":"https://plugins.miniorange.com/ticketmaster-sso-single-sign-on-into-joomla-using-oauth-openid-connect", "logo_class":"fa fa-lock"
		},
		"Mindbody": {
			"label":"Mindbody", "type":"openidconnect", "image":"mindbody-logo.webp", "scope": "email profile openid", "authorize": "https://signin.mindbodyonline.com/connect/authorize", "token": "https://signin.mindbodyonline.com/connect/token", "userinfo": "", "guide":"https://plugins.miniorange.com/mindbody-sso-single-sign-on-into-joomla-using-oauth-openid-connect", "logo_class":"fa fa-lock"
		},
		"iGov": {
			"label":"iGov", "type":"openidconnect", "image":"iGov-logo.webp", "scope": "openid profile", "authorize": "https://idp.government.gov/oidc/authorization", "token": "https://idp.government.gov/token", "userinfo": "", "guide":"https://plugins.miniorange.com/igov-sso-single-sign-on-into-joomla-using-oauth-openid-connect", "logo_class":"fa fa-lock"
		},
		"LearnWorlds": {
			"label":"LearnWorlds", "type":"openidconnect", "image":"learnworlds-logo.webp", "scope": "openid profile", "authorize": "https://api.learnworlds.com/oauth", "token": "https://api.learnworlds.com/oauth2/access_token", "userinfo": "", "guide":"https://plugins.miniorange.com/learnworlds-sso-single-sign-on-into-joomla-using-oauth-openid-connect", "logo_class":"fa fa-lock"
		},
		"Otoy": {
			"label":"Otoy", "type":"oauth", "image":"otoy-logo.webp", "scope": "openid", "authorize": "https://account.otoy.com/oauth/authorize", "token": "https://account.otoy.com/oauth/token", "userinfo": "https://account.otoy.com/api/v1/user.json", "guide":"https://plugins.miniorange.com/otoy-sso-single-sign-on-into-joomla-using-oauth-openid-connect", "logo_class":"fa fa-lock"
		},
        "other": {
            "label":"Custom OAuth", "type":"oauth", "image":"customapp.png", "scope": "", "authorize": "", "token": "", "userinfo": "", "guide":"", "logo_class":"fa fa-lock"
        },
        "openidconnect": {
            "label":"Custom OpenID Connect App", "type":"openidconnect", "image":"customapp.png", "scope": "", "authorize": "", "token": "", "userinfo": "", "guide":"", "logo_class":"fa fa-lock"
        }
    }';
}
function getAppData()
{
    return '{
        "azure": {
            "0":"both","1":"Tenant"
        },
        "azureb2c": {
            "0":"both","1":"Tenant,Policy"
        },
        "cognito": {
            "0":"both","1": "Domain"
        },
        "adfs": {
            "0":"both","1":"Domain"
        },
        "whmcs": {
            "0":"both","1":"Domain"
        },
        "keycloak": {
            "0":"both","1":"Domain,Realm"
        },
        "invisioncommunity": {
            "0":"both","1":"Domain"
        },
        "bitrix24": {
            "0":"both","1":"Domain"
        },
        "wso2": {
            "0":"both","1":"Domain"
        },
        "okta": {
            "0":"header","1":"Domain"
        },
        "onelogin": {
            "0":"both","1":"Domain"
        },
        "gluu": {
            "0":"both","1": "Domain" 
        },
        "zendesk": {
            "0":"both","1":"Domain"
        },
        "laravel": {
            "0":"both","1":"Domain"
        },
        "identityserver": {
            "0":"both","1":"Domain"
        },
        "nextcloud": {
            "0":"both","1":"Domain"
        },
        "wildApricot": {
            "0":"both","1":"Domain,AccountId"
        },
        "connect2id": {
            "0":"both","1":"Domain"
        },
        "diaspora": {
            "0":"both","1":"Domain" 
        },
        "Office 365": {
            "0":"both","1":"Tenant" 
        },
        "PingFederate": {
            "0":"both","1":"Domain"
        },
        "HP": {
            "0":"both","1":"Domain"
        },
        "Neon CRM": {
            "0":"both","1":"Domain"
        },
        "Canvas": {
            "0":"both","1":"Domain"
        },
        "UNA": {
            "0":"both","1":"Domain"
        },
        "OpenedX": {
            "0":"both","1":"Domain"
        },
        "ServiceNow": {
            "0":"both","1":"Domain"
        },
        "WordPress": {
            "0":"both","1":"Domain"
        },
        "MemberClicks": {
            "0":"both","1":"Domain"
        },
        "IMIS": {
            "0":"both","1":"Domain"
        }
    }';
}
function selectAppByIcon()
{
    $appArray = json_decode(getAppJason(),TRUE); 
    $ImagePath=Uri::base().'components/com_miniorange_oauth/assets/images/';
    $imageTableHtml = "<table id='moAuthAppsTable'>";
    $i=1;
    $PreConfiguredApps = array_slice($appArray,0,count($appArray)-2);
    foreach ($PreConfiguredApps as $key => $value) 
    {
        $img=$ImagePath.$value['image'];
        if($i%6==1){
            $imageTableHtml.='<tr>';
        }
        $imageTableHtml=$imageTableHtml."<td class='border' moAuthAppSelector='".$value['label']."'><a class='select_app' href='".Route::_('index.php?option=com_miniorange_oauth&view=accountsetup&moAuthAddApp='.$key)."''><div><img class='mo_oauth_img_resize' src='".$img."'><br><p>".$value['label']."</p></div></a></td>";
        if($i%6==0 || $i==count($appArray)){
            $imageTableHtml.='</tr>';
        }
        $i++;
    }
    $imageTableHtml.='</table>';
    ?> 
    <div class="container-fluid m-0 p-0">
    <div class="row m-1 my-3 ">
        <div class="col-sm-12 mt-4">
            <div class="row">
                <div class="col-sm-11 m-0 p-0">
                    <input type="text" class="form-control" name="appsearch" id="moAuthAppsearchInput" value="" placeholder="<?php echo Text::_('COM_MINIORANGE_OAUTH_SELECT_APP');?>">
                </div>
                <div class="col-sm-1 m-0 p-0 pt-2 border mo_oauth_search_btn text-center align-middle">
                    <span class="mo_oauth_icon_search"><i class="fa-solid fa-magnifying-glass"></i></span>
                </div>
            </div>
        </div>
        <div class="col-sm-12 mt-4">
            <?php
                echo $imageTableHtml;
            ?>
        </div>
        <div class="col-sm-12 mt-4">
            <div class="row">
                <div class="col-sm-12 my-2">
                    <h6><?php echo Text::_('COM_MINIORANGE_OAUTH_CUSTOM_APPLICATIONS');?></h6>
                    <br>
                    <span class="p-1"><?php echo Text::_('COM_MINIORANGE_OAUTH_CUSTOM_APPLICATIONS_NOTE');?></span>
                </div>
                <div class="col-sm-6 my-5 text-center" moAuthAppSelector='moCustomOuth2App'>
                    <a class="mo_oauth_select_app" href="<?php echo Route::_('index.php?option=com_miniorange_oauth&view=accountsetup&moAuthAddApp=other');?>">
                        <div class="mo_oauth_border" >
                            <img class='mo_oauth_img_resize' alt="" src="<?php echo  $ImagePath.$appArray['other']['image']; ?>"><br><p><?php echo $appArray['other']['label'];?></p>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6 my-5 text-center"  moAuthAppSelector='moCustomOpenIdConnectApp'>
                    <a class="mo_oauth_select_app" href="<?php echo Route::_('index.php?option=com_miniorange_oauth&view=accountsetup&moAuthAddApp=openidconnect');?>">
                        <div class="mo_oauth_border">
                            <img class='mo_oauth_img_resize' alt="" src="<?php echo  $ImagePath.$appArray['openidconnect']['image']; ?>"><br><p><?php echo $appArray['openidconnect']['label'];?></p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    </div>
    <?php
}
function selectCustomApp()
{
    $appArray = json_decode(getAppJason(),TRUE);
    $ImagePath=Uri::base().'components/com_miniorange_oauth/assets/images/';
    ?> 
    <div class="row m-1 my-3">
        <div class="col-sm-12 my-2">
            <h6><?php echo Text::_('COM_MINIORANGE_OAUTH_CUSTOM_APPLICATIONS');?></h6>
            <br>
            <span class="p-1"><?php echo Text::_('COM_MINIORANGE_OAUTH_CUSTOM_APPLICATIONS_NOTE');?></span>
        </div>
        <div class="col-sm-6 my-5 text-center" moAuthAppSelector='moCustomOuth2App'>
            <a class="mo_oauth_select_app" href="<?php echo Route::_('index.php?option=com_miniorange_oauth&view=accountsetup&moAuthAddApp=other');?>">
                <div class=" border mo_oauth_border">
                    <img class='mo_oauth_img_resize' alt="" src="<?php echo  $ImagePath.$appArray['other']['image']; ?>"><br><p><?php echo $appArray['other']['label'];?></p>
                </div>
            </a>
        </div>
        <div class="col-sm-6 my-5 text-center"  moAuthAppSelector='moCustomOpenIdConnectApp'>
            <a class="mo_oauth_select_app" href="<?php echo Route::_('index.php?option=com_miniorange_oauth&view=accountsetup&moAuthAddApp=openidconnect');?>">
                <div>
                    <img class='mo_oauth_img_resize' alt="" src="<?php echo  $ImagePath.$appArray['openidconnect']['image']; ?>"><br><p><?php echo $appArray['openidconnect']['label'];?></p>
                </div>
            </a>
        </div>
    </div>
    <?php
}
function getAppDetails(){
    $db = Factory::getDbo();
    $query = $db->getQuery(true);
    $query->select('*');
    $query->from($db->quoteName('#__miniorange_oauth_config'));
    $query->where($db->quoteName('id') . " = 1");
    $db->setQuery($query);
    return $db->loadAssoc();
}
function configuration($OauthApp,$appLabel)
{
    global $license_tab_link;
    $attribute = getAppDetails();
    $appJson = json_decode(getAppJason(),true);
    $appData = json_decode(getAppData(),true);

    if($appJson[$appLabel]["guide"]!="")
    {
        $guide=$appJson[$appLabel]["guide"];
    }
    else
    {
        $guide="https://plugins.miniorange.com/guide-to-enable-joomla-oauth-client";
    }
    $mo_oauth_app = $appLabel;
    $custom_app = "";
    $client_id = "";
    $client_secret = "";
    $redirecturi = Uri::root();
    $email_attr = "";
    $first_name_attr = "";
    $isAppConfigured = FALSE;
    $mo_oauth_in_header = "checked=true";
    $mo_oauth_in_body   = "";
    $login_link_check="1";
    $sso_enable = isset($attribute['sso_enable']) ? $attribute['sso_enable'] : '1';
    if(isset($attribute['in_header_or_body']))
    {
        if( $attribute['in_header_or_body']=='inBody' ){
            $mo_oauth_in_header = "";
            $mo_oauth_in_body   = "checked=true";
        }
        else if($attribute['in_header_or_body']=='inHeader' ){
            $mo_oauth_in_header = "checked=true";
            $mo_oauth_in_body   = "";
        }
        else if( $attribute['in_header_or_body']=='both' ){
            $mo_oauth_in_header = "checked=true";
            $mo_oauth_in_body   = "checked=true";
        }
    }
    else
    {
        if( isset($appData[$appLabel]) && $appData[$appLabel][0]=='both' ){
            $mo_oauth_in_header = "checked=true";
            $mo_oauth_in_body   = "checked=true";
        }
        else if(isset($appData['appLabel']) && $appData['appLabel'][0]=='inBody' ){
            $mo_oauth_in_header = "";
            $mo_oauth_in_body   = "checked=true";
        }
        else if(isset($appData['appLabel']) && $appData['appLabel'][0]=='inHeader' )
        {
            $mo_oauth_in_header = "checked=true";
            $mo_oauth_in_body   = "";
        }
    }
    if (isset($attribute['client_id'])) 
    {
        $mo_oauth_app = empty($attribute['appname'])?$appLabel:$attribute['appname'];
        $custom_app = $attribute['custom_app'];
        $client_id = $attribute['client_id'];
        $client_secret = $attribute['client_secret'];
        $isAppConfigured = empty($client_id) || empty($client_secret) || empty($custom_app)||empty($attribute['redirecturi'])?FALSE:TRUE;
        $step1Check = empty($attribute['redirecturi'])?FALSE:TRUE;
        $step2Check = empty($client_id) || empty($client_secret) || empty($custom_app)||empty($attribute['redirecturi'])?FALSE:TRUE;
        $app_scope = empty($attribute['app_scope'])?$OauthApp['scope']:$attribute['app_scope'];
        $authorize_endpoint = empty($attribute['authorize_endpoint'])?NULL:$attribute['authorize_endpoint'];
        $access_token_endpoint = empty($attribute['access_token_endpoint'])?NULL:$attribute['access_token_endpoint'];
        $user_info_endpoint = empty($attribute['user_info_endpoint'])?NULL:$attribute['user_info_endpoint'];
        $email_attr = $attribute['email_attr'];
        $first_name_attr = $attribute['first_name_attr'];
        $attributesNames = $attribute['test_attribute_name'];
        $step3Check = empty($email_attr)?FALSE:TRUE;
        $redirecturi = explode('//',Uri::root())[1];
        $attributesNames = explode(",",$attributesNames);
    }
    // echo "/n printing attribute = ";
    // var_dump($attribute);
    $get =Factory::getApplication()->input->get->getArray();
    $progress = isset($get['progress'])?$get['progress']:"step1";
    ?>
    <div class="container-fluid m-0 p-0">
    <div class="row m-0 p-1">
        <div class="col-sm-2 m-0 p-0 mo_oauth_border_right" >
            <div class="row m-0 p-0">
                <div class="col-sm-12 m-0 p-0">
                    <div onclick = "changeSubMenu(this , '#mo_redirectUrl_setting')" <?php echo(($progress=='step1')?'class="mo_sub_menu mo_sub_menu_active"':'class="mo_sub_menu"'); ?> >
                        <span><?php echo Text::_('COM_MINIORANGE_OAUTH_STEP1');?></span> <span class="float-right"><i class="text-success fa-solid fa-circle-check" <?php echo($step1Check?'style="display:block"':'style="display:none"'); ?> ></i></span>
                    </div>
                </div>
            </div>
            <div class="row m-0 p-0">
                <div class="col-sm-12 m-0 p-0">
                    <div <?php if(1){echo "onclick = \"changeSubMenu(this,'#mo_client_setting')\" ";}else{echo "style='cursor:not-allowed;'";}?> title="Configure the Step 1 First" <?php echo(($progress=='step2')?'class="mo_sub_menu mo_sub_menu_active"':'class="mo_sub_menu"'); ?>>
                        <span><?php echo Text::_('COM_MINIORANGE_OAUTH_STEP2');?></span></span> <span class="float-right"><i class=" text-success fa-solid fa-circle-check" <?php echo($step2Check?'style="display:block"':'style="display:none"'); ?>></i></span>
                    </div>
                </div>
            </div>
            <div class="row m-0 p-0">
                <div class="col-sm-12 m-0 p-0">
                    <div <?php if($client_secret!=""){echo "onclick = \"changeSubMenu(this,'#mo_attribute_setting')\" ";}else{echo "style='cursor:not-allowed'";}?> title="Configure the Step 2 First" <?php echo(($progress=='step3')?'class="mo_sub_menu mo_sub_menu_active"':'class="mo_sub_menu"'); ?>>
                        <span><?php echo Text::_('COM_MINIORANGE_OAUTH_STEP3');?></span></span> <span class="float-right"><i class=" text-success fa-solid fa-circle-check" <?php echo($step3Check?'style="display:block"':'style="display:none"'); ?>></i></span>
                    </div>
                </div>
            </div>
            <div class="row m-0 p-0">
                <div  class="col-sm-12 m-0 p-0">
                    <div <?php if($email_attr!=""){echo "onclick = \"changeSubMenu(this,'#mo_sso_url')\" ";}else{echo "style='cursor:not-allowed'";}?> title="Configure the Step 3 first" <?php echo (($progress=='step4')?'class="mo_sub_menu mo_sub_menu_active"':'class="mo_sub_menu"'); ?>>
                        <span>Step 4 <small>[SSO URL]</small></span></span>
                    </div>
                </div>
            </div>
            <hr class="mo_oauth_bg_black">
            <div class="row m-0 p-0">
                <div  class="col-sm-12 m-0 p-0">
                    <div onclick = "changeSubMenu(this,'#mo_advance_setting')" class="mo_sub_menu">
                        <span><?php echo Text::_('COM_MINIORANGE_OAUTH_ADVANCE_SETTINGS');?></span>
                    </div>
                </div>
            </div>
            <div class="row m-0 p-0">
                <div  class="col-sm-12 m-0 p-0">
                    <div onclick = "changeSubMenu(this,'#mo_importexport_setting')" class="mo_sub_menu">
                        <span><?php echo Text::_('COM_MINIORANGE_OAUTH_IMPORT');?></span>
                    </div>
                </div>
            </div>
            <div class="row m-0 p-0">
                <div  class="col-sm-12 m-0 p-0">
                    <div onclick = "changeSubMenu(this,'#mo_premium_feature')" class="mo_sub_menu">
                        <span><?php echo Text::_('COM_MINIORANGE_OAUTH_PRMIUM_FEATURE');?> </span><span><sup><img class="crown_img_small ml-2" src="<?php echo Uri::base();?>/components/com_miniorange_oauth/assets/images/crown.webp"></a></sup></span>
                    </div>
                </div>
            </div>
            <div class="row m-0 mt-3 p-0">
                <div  class="col-sm-12 m-0 p-0">
                    <div class="text-center">
                        <?php  echo "<a href='index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.clearConfig'
                                    class='btn pb-1 btn-danger'>".Text::_('COM_MINIORANGE_OAUTH_DELETE_APPLICATION')."</a>";
                                ?> 
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-10">
            <div class="row m-0 p-0 my-3" <?php echo (($progress=='step1')?'style="display:block"':'style="display:none"'); ?> id="mo_redirectUrl_setting">
                <div class="col-sm-12" id="mo_oauth_attributemapping">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-lg-6 col-sm-5">
                                    <h5 class="element"><?php echo Text::_('COM_MINIORANGE_OAUTH_CONFIG');?>
                                        <a href="https://developers.miniorange.com/docs/oauth-joomla/configuration-attributes" target="_blank" class="mo_handbook" ><sup><i class="fa-regular fa-circle-question" title="What is Callback URL?"></i></sup></a>
                                    </h5>
                                </div>
                                <div class="col-lg-6 col-sm-7">
                                    <a href="<?php echo $guide;?>" target="_blank" class="mo_oauth_input btn mo_oauth_all_btn"><span><i class="fa fa-file"></i></span><?php echo Text::_('COM_MINIORANGE_OAUTH_GUIDE');?></a>
                                    <a href="https://www.youtube.com/playlist?list=PL2vweZ-PcNpd8-9AvYGYrYx_hXn2vSIsc" target="_blank" class="mo_oauth_input mx-1 btn mo_oauth_all_btn"><span><i class="fa-brands fa-youtube"></i></span><?php echo Text::_('COM_MINIORANGE_OAUTH_VIDEO_SET');?></a>
                                </div>
                            </div>
                            <br>
                        </div>

                        <br><br>
                        <div class="col-sm-12">
                            <div class="row mt-3 p-3">
                                <div class="col-sm-12">
                                    <div class="row mt-3">
                                        <div class="col-sm-3">
                                            <strong><span class="mo_oauth_highlight">*</span><?php echo Text::_('COM_MINIORANGE_OAUTH_APPLICATION');?></strong>
                                        </div>
                                        <div class="col-sm-8">
                                            <?php echo "<span class='mo_oauth_label'>".$OauthApp['label']."</span>";?>
                                            <input type="hidden" name="mo_oauth_app_name" value="<?php echo $mo_oauth_app; ?>">
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-sm-3">
                                            <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_CALLBACK_URL');?></strong>
                                        </div>
                                        <div class="col-sm-8 m-0">
                                            <form id="oauth_config_form_step1" method="post" action="<?php echo Route::_('index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.saveConfig'); ?>">
                                                <input type="hidden" name="mo_oauth_app_name" value="<?php echo $mo_oauth_app; ?>">
                                                <input type="hidden" name="oauth_config_form_step1" value="true">
                                                <div class="row m-0 p-0">
                                                    <div class="col-sm-3 m-0 p-0">
                                                        <select class="d-inline-block mo-form-control mo-form-control-select" name="callbackurlhttp" id="callbackurlhttp">
                                                            <option value="http://" selected>http</option>
                                                            <option value="https://">https</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-9 m-0 p-0">
                                                        <input class="form-control" id="callbackurl" name="callbackurl" type="text" readonly  value='<?php echo $redirecturi; ?>'>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-sm-1">
                                            <em class="fa fa-pull-right fa-lg fa-copy mo_copy copytooltip mo_oauth_copy_btn" onclick="copyToClipboard('#callbackurl','#callbackurlhttp');" ;>
                                                <span class="copytooltiptext">Copied!</span> 
                                            </em>
                                        </div>
                                        <div class="col-sm-12 mt-2">
                                            <small><?php echo Text::_('COM_MINIORANGE_OAUTH_CALLBACK_URL_NOTE');?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="row mt-4">
                                <div class="col-sm-12 mt-3 text-right">
                                    <button name="send_query" onclick="callbackURLFormSubmit()" class="btn mo_oauth_all_btn p-2 px-4 mb-3"><?php echo Text::_('COM_MINIORANGE_OAUTH_SAVE_N_NEXT');?> <i class="fa-solid fa-arrow-right"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>  
                </div>
            </div>
            <div class="row m-1 my-3" <?php echo (($progress=='step2')?'style="display:block"':'style="display:none"'); ?> id="mo_client_setting">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-5">
                            <h5 class="element"> <?php echo Text::_('COM_MINIORANGE_OAUTH_CONFIG');?>
                                <a href="https://developers.miniorange.com/docs/oauth-joomla/configuration-attributes" target="_blank" class="mo_handbook" ><sup><i class="fa-regular fa-circle-question" title="What is Client ID or Client Secret?"></i></sup></a>
                            </h5>
                        </div>
                        <div class="col-sm-7">
                            <a href="<?php echo $guide;?>" target="_blank" class="mo_oauth_input btn mo_oauth_all_btn"><span><i class="fa fa-file"></i></span><?php echo Text::_('COM_MINIORANGE_OAUTH_GUIDE');?></a>
                            <a href="https://www.youtube.com/playlist?list=PL2vweZ-PcNpd8-9AvYGYrYx_hXn2vSIsc" target="_blank" class="mo_oauth_input mx-1 btn mo_oauth_all_btn"><span><i class="fa-brands fa-youtube"></i></span><?php echo Text::_('COM_MINIORANGE_OAUTH_VIDEO_SET');?></a>
                        </div>
                    </div>
                    
                    <hr>
                </div>
                <div class="col-sm-12 mt-5">
                    <form id="oauth_config_form_step2" name="" method="post" action="<?php echo Route::_('index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.saveConfig'); ?>">
                        <input type="hidden" name="oauth_config_form_step2" value="true">                   
                        <div class="row m-1 mt-3">
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <input type="hidden" id="mo_oauth_custom_app_name" name="mo_oauth_custom_app_name" value='<?php echo $OauthApp['label']; ?>' required>
                                        <input type="hidden" name="moOauthAppName" value="<?php echo $appLabel; ?>">
                                        <input type="hidden" name="mo_oauth_app_name" value="<?php echo $mo_oauth_app; ?>">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-sm-3">
                                        <strong><span class="mo_oauth_highlight">*</span><?php echo Text::_('COM_MINIORANGE_OAUTH_CLIENT_ID'); ?></strong>
                                    </div>
                                    <div class="col-sm-8">
                                        <input placeholder="<?php echo Text::_('COM_MINIORANGE_OAUTH_CLIENT_ID_PLACEHOLDER');?>" class="form-control" required="" type="text" name="mo_oauth_client_id" id="mo_oauth_client_id" value='<?php echo $client_id; ?>'>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-sm-3">
                                        <strong><span class="mo_oauth_highlight">*</span><?php echo Text::_('COM_MINIORANGE_OAUTH_CLIENT_SECRET'); ?></strong>
                                    </div>
                                    <div class="col-sm-8">
                                        <input placeholder="<?php echo Text::_('COM_MINIORANGE_OAUTH_CLIENT_SECRET_PLACEHOLDER');?>" class="form-control" type="text" id="mo_oauth_client_secret" name="mo_oauth_client_secret" value='<?php echo $client_secret; ?>'>
                                    </div>
                                </div>
                                <?php 
                                    if($authorize_endpoint==NULL)
                                    {   
                                        if(isset($appData[$appLabel]))
                                        {                    
                                            $fields = explode(",",$appData[$appLabel]['1']);
                                            foreach($fields as $key => $value)
                                            {
                                                if($value == 'Tenant')
                                                {
                                                    $placeholder = Text::_('COM_MINIORANGE_OAUTH_ENTER_THE_TENANT_ID');
                                                }
                                                else if( $value=='Domain')
                                                {
                                                    $placeholder = Text::_('COM_MINIORANGE_OAUTH_ENTER_THE_DOMAIN');
                                                }
                                                else
                                                {
                                                    $placeholder = Text::_('COM_MINIORANGE_OAUTH_ENTER_THE_DETAILS').$value ;
                                                }
                                                echo '<div class="row mt-3"><div class="col-sm-3">
                                                <strong><span class="mo_oauth_highlight">*</span>'.$value.'</strong>
                                                </div>
                                                <div class="col-sm-7">
                                                    <input class="form-control" placeholder="'.$placeholder.'" type="text" id="" name="'.$value.'" value="" required>
                                                </div></div>';
                                            }
                                        }
                                        else
                                        { ?>
                                            <div class="row mt-3">
                                                <div class="col-sm-3">
                                                    <strong><span class="mo_oauth_highlight">*</span><?php echo Text::_('COM_MINIORANGE_OAUTH_APP_SCOPE');?></strong>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input class="form-control" placeholder="<?php echo Text::_('COM_MINIORANGE_OAUTH_APP_SCOPE_PLACEHOLDER');?>" type="text" id="mo_oauth_scope" name="mo_oauth_scope" value='<?php echo $app_scope ?>' required>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-sm-3">
                                                    <strong><span class="mo_oauth_highlight">*</span><?php echo Text::_('COM_MINIORANGE_OAUTH_AUTHORIZE_ENDPOINT');?></strong>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input class="form-control" placeholder="<?php echo Text::_('COM_MINIORANGE_OAUTH_AUTHORIZE_ENDPOINT_PLACEHOLDER');?>" type="text" id="mo_oauth_authorizeurl" name="mo_oauth_authorizeurl" value='<?php echo $appJson[$appLabel]["authorize"] ?>' required>
                                                </div>
                                                <div class="col-sm-1">
                                                    <em class="fa fa-pull-right fa-lg fa-copy mo_copy copytooltip mo_oauth_copy_btn" ; onclick="copyToClipboard1('#mo_oauth_authorizeurl');";>
                                                        <span class="copytooltiptext"><?php echo Text::_('COM_MINIORANGE_OAUTH_COPIED');?></span>
                                                    </em>
                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-sm-3">
                                                    <strong><span class="mo_oauth_highlight">*</span><?php echo Text::_('COM_MINIORANGE_OAUTH_TOKEN_ENDPOINT'); ?></strong>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input class="form-control" placeholder="<?php echo Text::_('COM_MINIORANGE_OAUTH_TOKEN_ENDPOINT_PLACEHOLDER');?>" type="text" id="mo_oauth_accesstokenurl" name="mo_oauth_accesstokenurl" value='<?php echo $appJson[$appLabel]['token']; ?>' required>
                                                </div>
                                                <div class="col-sm-1">
                                                    <em class="fa fa-pull-right fa-lg fa-copy mo_copy copytooltip mo_oauth_copy_btn" onclick="copyToClipboard1('#mo_oauth_accesstokenurl');";>
                                                        <span class="copytooltiptext"><?php echo Text::_('COM_MINIORANGE_OAUTH_COPIED');?></span>
                                                    </em>
                                                </div>
                                            </div>                           
                                            <?php 
                                                if(!isset($OauthApp['type']) || $OauthApp['type']=='oauth'){?>
                                                    <div class="row mt-3" id="mo_oauth_resourceownerdetailsurl_div">
                                                        <div class="col-sm-3">
                                                            <strong><span class="mo_oauth_highlight">*</span><?php echo Text::_('COM_MINIORANGE_OAUTH_INFO_ENDPOINT'); ?></strong>
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <input class="form-control" placeholder="<?php echo Text::_('COM_MINIORANGE_OAUTH_INFO_ENDPOINT_PLACEHOLDER');?>" type="text" id="mo_oauth_resourceownerdetailsurl" name="mo_oauth_resourceownerdetailsurl" value='<?php echo $appJson[$appLabel]['userinfo']; ?>' required>
                                                        </div>
                                                        <div class="col-sm-1">
                                                            <em class="fa fa-pull-right fa-lg fa-copy mo_copy copytooltip mo_oauth_copy_btn" onclick="copyToClipboard1('#mo_oauth_resourceownerdetailsurl');";>
                                                                <span class="copytooltiptext"><?php echo Text::_('COM_MINIORANGE_OAUTH_COPIED');?></span>
                                                            </em>
                                                        </div>
                                                    </div>
                                            <?php }
                                        }
                                    }
                                    else
                                    { ?>
                                        <div class="row mt-3">
                                            <div class="col-sm-3">
                                                <strong><span class="mo_oauth_highlight">*</span><?php echo Text::_('COM_MINIORANGE_OAUTH_APP_SCOPE');?></strong>
                                            </div>
                                            <div class="col-sm-7">
                                                <input class="form-control" placeholder="<?php echo Text::_('COM_MINIORANGE_OAUTH_APP_SCOPE_PLACEHOLDER');?>" type="text" id="mo_oauth_scope" name="mo_oauth_scope" value='<?php echo $app_scope ?>' required>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-sm-3">
                                                <strong><span class="mo_oauth_highlight">*</span><?php echo Text::_('COM_MINIORANGE_OAUTH_AUTHORIZE_ENDPOINT'); ?></strong>
                                            </div>
                                            <div class="col-sm-7">
                                                <input class="form-control" type="text" id="mo_oauth_authorizeurl" name="mo_oauth_authorizeurl" value='<?php echo $authorize_endpoint; ?>' required>
                                            </div>
                                            <div class="col-sm-1">
                                                <em class="fa fa-pull-right fa-lg fa-copy mo_copy copytooltip mo_oauth_copy_btn" ; onclick="copyToClipboard1('#mo_oauth_authorizeurl');";>
                                                    <span class="copytooltiptext"><?php echo Text::_('COM_MINIORANGE_OAUTH_COPIED');?></span>
                                                </em>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-sm-3">
                                                <strong><span class="mo_oauth_highlight">*</span><?php echo Text::_('COM_MINIORANGE_OAUTH_TOKEN_ENDPOINT'); ?></strong>
                                            </div>
                                            <div class="col-sm-7">
                                                <input class="form-control" type="text" id="mo_oauth_accesstokenurl" name="mo_oauth_accesstokenurl" value='<?php echo $access_token_endpoint; ?>' required>
                                            </div>
                                            <div class="col-sm-1">
                                                <em class="fa fa-pull-right fa-lg fa-copy mo_copy copytooltip mo_oauth_copy_btn" onclick="copyToClipboard1('#mo_oauth_accesstokenurl');";>
                                                    <span class="copytooltiptext"><?php echo Text::_('COM_MINIORANGE_OAUTH_COPIED');?></span>
                                                </em>
                                            </div>
                                        </div>
                                        <?php 
                                            if(!isset($OauthApp['type']) || $OauthApp['type']=='oauth'){?>
                                                <div class="row mt-3" id="mo_oauth_resourceownerdetailsurl_div">
                                                    <div class="col-sm-3">
                                                        <strong><span class="mo_oauth_highlight">*</span><?php echo Text::_('COM_MINIORANGE_OAUTH_INFO_ENDPOINT'); ?></strong>
                                                    </div>
                                                    <div class="col-sm-7">
                                                        <input class="form-control" type="text" id="mo_oauth_resourceownerdetailsurl" name="mo_oauth_resourceownerdetailsurl" value='<?php echo $user_info_endpoint; ?>' required>
                                                    </div>
                                                    <div class="col-sm-1">
                                                        <em class="fa fa-pull-right fa-lg fa-copy mo_copy copytooltip mo_oauth_copy_btn" onclick="copyToClipboard1('#mo_oauth_resourceownerdetailsurl');";>
                                                            <span class="copytooltiptext"><?php echo Text::_('COM_MINIORANGE_OAUTH_COPIED');?></span>
                                                        </em>
                                                    </div>
                                                </div>
                                        <?php }
                                    }
                                ?>    
                                <div class="row mt-3">
                                    <div class="col-sm-3">
                                        <b><?php echo Text::_('COM_MINIORANGE_OAUTH_SET_CLIENT_CREDENTIALS');?></b>
                                    </div>
                                    <div class="form-check form-switch col-lg-2 col-sm-4 mx-4">
                                        <input type="checkbox" class='mo_oauth_checkbox form-check-input' name="mo_oauth_in_header" value="1" <?php echo " ".$mo_oauth_in_header; ?>>&nbsp;<?php echo Text::_('COM_MINIORANGE_OAUTH_SET_CREDENTIAL_IN_HEADER');?>
                                    </div>
                                    <div class="form-check form-switch col-lg col-sm-3">
                                        <input type="checkbox" class="mo_table_textbox mo_oauth_checkbox form-check-input" name="mo_oauth_body" value="1" <?php echo " ".$mo_oauth_in_body; ?> >&nbsp; <?php echo Text::_('COM_MINIORANGE_OAUTH_SET_CREDENTIAL_IN_BODY');?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>        
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-3 text-right">
                            <button class="btn mo_oauth_all_btn p-2 px-4 mb-3" onclick="submitOAuthConfigForm()"><?php echo Text::_('COM_MINIORANGE_OAUTH_SAVE_CONFIG');?></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row m-1 my-3" <?php echo (($progress=='step3')?'style="display:block"':'style="display:none"'); ?> id="mo_attribute_setting">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-8">
                            <h5 class="element"> <?php echo Text::_('COM_MINIORANGE_OAUTH_CONFIG');?>
                                <a href="https://developers.miniorange.com/docs/oauth-joomla/configuration-attributes" target="_blank" class="mo_handbook" ><sup><i class="fa-regular fa-circle-question" title="What is basic attribute mapping?"></i></sup></a>
                            </h5>
                        </div>
                        <div class="col-sm-4">
                            <a href="<?php echo $guide;?>" target="_blank" class=" float-right mx-1 btn mo_oauth_all_btn"><span><i class="fa fa-file"></i></span> <?php echo Text::_('COM_MINIORANGE_OAUTH_GUIDE');?></a>
                            <a href="https://www.youtube.com/playlist?list=PL2vweZ-PcNpd8-9AvYGYrYx_hXn2vSIsc" target="_blank" class=" float-right mx-1 btn mo_oauth_all_btn"><span><i class="fa-brands fa-youtube"></i></span> <?php echo Text::_('COM_MINIORANGE_OAUTH_VIDEO_SET');?></a>
                        </div>
                    </div>
                    <hr>
                </div>
                <div class="col-sm-12 mt-5">
                   <div class="row mt-3">
                        <div class="col-sm-3">
                            <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_TEST_CONFIG');?></strong>
                        </div>
                        <div class="col-sm-7">
                            <button class="btn mo_oauth_all_btn p-2 px-4 mb-3" onclick="testConfiguration()"><?php echo Text::_('COM_MINIORANGE_OAUTH_TEST_CONFIG');?></button>
                        </div>
                        <div class="col-sm-12 mb-5">
                            <br>
                            <span>
                               <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_TEST_CONFIG_NOTE');?> </strong> <?php echo Text::_('COM_MINIORANGE_OAUTH_TEST_CONFIG_NOTE_1');?>
                            </span>
                        </div>
                    </div>
                    <form id="oauth_mapping_form" name="oauth_config_form" method="post" action="<?php echo Route::_('index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.saveMapping'); ?>">
                        <div class="row mt-3">
                            <div class="col-sm-3">
                                <strong><span class="mo_oauth_highlight">*</span><?php echo Text::_('COM_MINIORANGE_OAUTH_EMAIL_ATTR'); ?></strong>
                            </div>
                            <div class="col-sm-7">
                                <?php
                                    if (count($attributesNames) != 0 && count($attributesNames) != 1 ) {
                                        ?>
                                        <select required class="mo-form-control mo-form-control-select h-100" name="mo_oauth_email_attr" id="mo_oauth_email_attr">
                                            <option value="none" selected><?php echo Text::_('COM_MINIORANGE_OAUTH_EMAIL_ATTR_NOTE');?></option>
                                            <?php
                                                foreach($attributesNames as $key => $value)
                                                {
                                                    if($value == $email_attr)
                                                    {
                                                        $checked = "selected";
                                                    }
                                                    else
                                                    {
                                                        $checked = "";
                                                    }
                                                    if($value!="")
                                                        echo"<option ".$checked." value='".$value."'>".$value."</option>";
                                                }
                                            ?>
                                        </select>
                                        <?php
                                    } else {
                                        ?>
                                        <input type="text" name="" class="form-control" disabled placeholder=" <?php echo Text::_('COM_MINIORANGE_OAUTH_TEST_CONFIG_NOTE_2');?> " id="">
                                        <?php
                                    }
                                ?>
                               
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-sm-3">
                                <strong><span class="mo_oauth_highlight">*</span><?php echo Text::_('COM_MINIORANGE_OAUTH_FIRST_NAME_ATTR'); ?></strong>
                            </div>
                            <div class="col-sm-7">
                                <?php
                                    if (count($attributesNames) != 0 && count($attributesNames) != 1 ) 
                                    {
                                        ?>
                                        <select required class="mo-form-control mo-form-control-select h-100" name="mo_oauth_first_name_attr" id="mo_oauth_first_name_attr">
                                            <option value="none" selected><?php echo Text::_('COM_MINIORANGE_OAUTH_FIRST_NAME_ATTR_NOTE');?></option>
                                            <?php
                                                foreach($attributesNames as $key => $value)
                                                {
                                                    if($value == $first_name_attr)
                                                    {
                                                        $checked = "selected";
                                                    }
                                                    else
                                                    {
                                                        $checked = "";
                                                    }
                                                    if($value!="")
                                                    echo"<option ".$checked." value='".$value."'>".$value."</option>";
                                                }
                                            ?>
                                        </select>
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                        <input type="text" name="" class="form-control" disabled placeholder="<?php echo Text::_('COM_MINIORANGE_OAUTH_TEST_CONFIG_NOTE_2'); ?>" id="">
                                        <?php
                                    }
                                ?>
                                
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-sm-12 mt-3 text-right">
                                <input type="submit" name="send_query" class="btn mo_oauth_all_btn p-2 mb-3" value="<?php echo Text::_('COM_MINIORANGE_OAUTH_FINISH_CONFIG'); ?>">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row m-1 my-3 " <?php echo (($progress=='step4')?'style="display:block"':'style="display:none"'); ?> id="mo_sso_url">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-8">
                            <h5 class="element">
                                <?php echo Text::_('COM_MINIORANGE_OAUTH_CONFIG'); ?>
                                <a href="https://developers.miniorange.com/docs/oauth-joomla/configuration-attributes" target="_blank" class="mo_handbook" ><sup><i class="fa-regular fa-circle-question" title="What is SSO URl?"></i></sup></a>
                            </h5>
                        </div>
                        <div class="col-sm-4">
                            <a href="<?php echo $guide;?>" target="_blank" class=" float-right mx-1 btn mo_oauth_all_btn"><span><i class="fa fa-file"></i></span> <?php echo Text::_('COM_MINIORANGE_OAUTH_GUIDE'); ?></a>
                            <a href="https://www.youtube.com/playlist?list=PL2vweZ-PcNpd8-9AvYGYrYx_hXn2vSIsc" target="_blank" class=" float-right mx-1 btn mo_oauth_all_btn"><span><i class="fa-brands fa-youtube"></i></span> <?php echo Text::_('COM_MINIORANGE_OAUTH_VIDEO_SET'); ?></a>
                        </div>
                    </div>
                    <hr>
                </div>
                <div class=" col-sm-12 mt-5">
                    <div class="row mt-3 mb-5">
                        <div class="col-sm-12 mb-3">
                            <?php echo Text::_('COM_MINIORANGE_OAUTH_LOGIN_URL_NOTE');?>
                        </div>
                        <div class="col-sm-3">
                            <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_LOGIN_URL');?></strong>
                        </div>
                        <div class="col-sm-8">
                            <input class="form-control" id="loginUrl" type="text" readonly="true" value='<?php echo Uri::root() . '?morequest=oauthredirect&app_name=' . $mo_oauth_app; ?>'>
                        </div>
                        <div class="col-sm-1">
                            <em class="fa fa-pull-right fa-lg fa-copy mo_copy copytooltip mo_oauth_copy_btn" onclick="copyToClipboard1('loginUrl');";>
                                <span class="copytooltiptext"><?php echo Text::_('COM_MINIORANGE_OAUTH_COPIED'); ?></span>
                            </em>
                        </div>
                    </div>
                    <div class="row mt-3 mb-5">
                        <div class="col-sm-12">
                            <hr>
                            <h4><u><?php echo Text::_('COM_MINIORANGE_OAUTH_STEP_LOGIN'); ?></u></h4>
                            <br>
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <td class="w-15"><strong><?php echo Text::_('COM_MINIORANGE_OAUTH_STEP_LOGIN_1'); ?></strong></td>
                                    <td><?php echo Text::_('COM_MINIORANGE_OAUTH_STEP_LOGIN_1_TEXT'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong><?php echo Text::_('COM_MINIORANGE_OAUTH_STEP_LOGIN_2'); ?></strong> </td>
                                    <td><?php echo Text::_('COM_MINIORANGE_OAUTH_STEP_LOGIN_2_TEXT'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_STEP_LOGIN_3'); ?></strong>
                                    </td>
                                    <td><?php echo Text::_('COM_MINIORANGE_OAUTH_STEP_LOGIN_3_TEXT'); ?>

                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_STEP_LOGIN_4'); ?></strong>
                                    </td>
                                    <td><?php echo Text::_('COM_MINIORANGE_OAUTH_STEP_LOGIN_4_TEXT'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_STEP_LOGIN_5'); ?></strong>
                                    </td>
                                    <td><?php echo Text::_('COM_MINIORANGE_OAUTH_STEP_LOGIN_5_TEXT'); ?></td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_STEP_LOGIN_6'); ?></strong>
                                    </td>
                                    <td><?php echo Text::_('COM_MINIORANGE_OAUTH_STEP_LOGIN_6_TEXT'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_STEP_LOGIN_7'); ?></strong>
                                    </td>
                                    <td><?php echo Text::_('COM_MINIORANGE_OAUTH_STEP_LOGIN_7_TEXT'); ?></td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_STEP_LOGIN_8'); ?></strong>
                                    </td>
                                    <td><?php echo Text::_('COM_MINIORANGE_OAUTH_STEP_LOGIN_8_TEXT'); ?>

                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_STEP_LOGIN_9'); ?></strong>
                                    </td>
                                    <td><?php echo Text::_('COM_MINIORANGE_OAUTH_STEP_LOGIN_9_TEXT'); ?>

                                    </td>
                                </tr>
                                <tr>
                                    <td><strong><?php echo Text::_('COM_MINIORANGE_OAUTH_STEP_LOGIN_10'); ?></strong></td>
                                    <td><?php echo Text::_('COM_MINIORANGE_OAUTH_STEP_LOGIN_10_TEXT'); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row m-1 my-3 mo_oauth_display-none" id="mo_premium_feature">
                <div class="col-sm-12">
                    <h5 class="element"> <?php echo Text::_('COM_MINIORANGE_OAUTH_ADD_FEATURES'); ?>
                        <a href="https://developers.miniorange.com/docs/oauth-joomla/overview-oauth" target="_blank" class="mo_handbook" ><sup><i class="fa-regular fa-circle-question" title="Know more about premium feature"></i></sup></a>
                    </h5>
                    <hr>
                </div>
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-5">
                            <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_ADD_FEATURES_PKCE'); ?></strong>:
                        </div>
                        <div class="col-sm-7">
                            <div class="form-check form-switch">
                                <label id=" mo_oauth_switch">
                                    <input class="form-check-input" type="checkbox" disabled/>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-5">
                            <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_ADD_FEATURES_JWT'); ?></strong>:
                        </div>
                        <div class="col-sm-7">
                            <div class="form-check form-switch">
                                <label id="mo_oauth_switch">
                                    <input class="form-check-input" type="checkbox" disabled/>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-5">
                            <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_JWT_ALGO'); ?></strong>
                        </div>
                        <div class="col-sm-7">
                            <select readonly class="mo-form-control mo-form-control-select">
                                <option value="HSA"><?php echo Text::_('COM_MINIORANGE_OAUTH_JWT_ALGO_HSA'); ?></option>
                                <option value="RSA"><?php echo Text::_('COM_MINIORANGE_OAUTH_JWT_ALGO_RSA'); ?></option>
                            </select>
                        </div>
                    </div><br>
                    <div class="row">
                        <div class="col-sm-5">
                            <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_JWKS_URI'); ?></strong>
                        </div>
                        <div class="col-sm-7">
                            <input class="mo_security_textfield form-control " required type="text" placeholder="<?php echo Text::_('COM_MINIORANGE_OAUTH_JKWS_ENTER'); ?>" disabled="disabled" value="" />
                        </div>
                    </div><br>
                    <div class="row">
                        <div class="col-sm-5">
                            <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_GRANT_TYPE'); ?></strong>
                        </div>
                        <div class="col-sm-7">
                            <select readonly class="mo-form-control mo-form-control-select">
                                <option value="code"><?php echo Text::_('COM_MINIORANGE_OAUTH_GRANT_TYPE1'); ?></option>
                                <option value="implicit"><?php echo Text::_('COM_MINIORANGE_OAUTH_GRANT_TYPE2'); ?></option>
                                <option value="password"><?php echo Text::_('COM_MINIORANGE_OAUTH_GRANT_TYPE3'); ?></option>
                                <option value="client"><?php echo Text::_('COM_MINIORANGE_OAUTH_GRANT_TYPE4'); ?></option>
                                <option value="refresh"><?php echo Text::_('COM_MINIORANGE_OAUTH_GRANT_TYPE5'); ?></option>
                            </select>
                        </div>
                    </div><br>
                </div>
            </div>

            <div class="row m-1 my-3 mo_oauth_display-none" id="mo_advance_setting">
                <form method="POST" action="<?php echo Route::_('index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.enableSSO'); ?>">
                    <div class="col-sm-12">
                        <h5 class="element"> 
                            <?php echo Text::_('COM_MINIORANGE_OAUTH_ADVANCE_SETTINGS'); ?>
                            <a href="https://developers.miniorange.com/docs/oauth-joomla/overview-oauth" 
                               target="_blank" class="mo_handbook">
                                <sup><i class="fa-regular fa-circle-question" title="Know more about premium feature"></i></sup>
                            </a>
                        </h5>
                        <hr>
                    </div>

                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-5">
                                <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_SSO_ENABLE'); ?></strong>:
                            </div>
                            <div class="col-sm-7">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" value="1" name="mo_oauth_enable_sso" 
                                           id="mo_oauth_enable_sso" <?php echo ($sso_enable ? 'checked' : ''); ?> />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="col-sm-12 mt-3">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>


            <div class="row m-1 my-3 mo_oauth_display-none" id="mo_importexport_setting">
                <div class="col-sm-12">
                    <?php moImportAndExport()?>
                </div>
            </div>
        </div>
    </div>
    <script>
        function testConfiguration() {
            var appname = "<?php echo $appLabel; ?>";
            var winl = ( screen.width - 400 ) / 2,
            wint = ( screen.height - 800 ) / 2,
            winprops = 'height=' + 600 +
            ',width=' + 800 +
            ',top=' + wint +
            ',left=' + winl +
            ',scrollbars=1'+
            ',resizable';
            var myWindow = window.open('<?php echo Uri::root();?>' + '?morequest=testattrmappingconfig&app=' + appname, "Test Attribute Configuration", winprops);
            var timer = setInterval(function() {   
            if(myWindow.closed) {  
                clearInterval(timer);  
                location.reload();
            }  
            }, 1); 
        }
    </script>
    </div>
    <?php
}
function attributerole()
{
    global $license_tab_link;
    $attribute = getAppDetails();
    $email = isset($attribute['email_attr'])?$attribute['email_attr']:"";
    $username = isset($attribute['first_name_attr'])?$attribute['first_name_attr']:"";
    ?>
    <div class="row m-0 p-1">
        <div class="col-sm-2 m-0 p-0 mo_oauth_border_right">
            <div class="row m-0 p-0">
                <div class="col-sm-12 m-0 p-0">
                    <div onclick = "changeSubMenu(this , '#mo_basic_mapping')" class="mo_sub_menu mo_sub_menu_active">
                        <span><?php echo Text::_('COM_MINIORANGE_OAUTH_BASIC_ATT'); ?></span>
                    </div>
                </div>
            </div>
            <div class="row m-0 p-0">
                <div class="col-sm-12 m-0 p-0">
                    <div onclick = "changeSubMenu(this,'#mo_profile_mapping')" class="mo_sub_menu">
                        <span><?php echo Text::_('COM_MINIORANGE_OAUTH_PROFILE_ATT'); ?></span><sup><img class="crown_img_small ml-2" src="<?php echo Uri::base();?>/components/com_miniorange_oauth/assets/images/crown.webp"></a></sup>
                    </div>
                </div>
            </div>
            <div class="row m-0 p-0">
                <div class="col-sm-12 m-0 p-0">
                    <div onclick = "changeSubMenu(this,'#mo_field_mapping')" class="mo_sub_menu">
                        <span><?php echo Text::_('COM_MINIORANGE_OAUTH_FIELD_ATT'); ?></span><sup><img class="crown_img_small ml-2" src="<?php echo Uri::base();?>/components/com_miniorange_oauth/assets/images/crown.webp"></a></sup>
                    </div>
                </div>
            </div>
            <div class="row m-0 p-0">
                <div  class="col-sm-12 m-0 p-0">
                    <div onclick = "changeSubMenu(this,'#mo_group_mapping')" class="mo_sub_menu">
                        <span><?php echo Text::_('COM_MINIORANGE_OAUTH_GROUPS'); ?></span><sup><img class="crown_img_small ml-2" src="<?php echo Uri::base();?>/components/com_miniorange_oauth/assets/images/crown.webp"></a></sup>
                    </div>
                </div>
            </div>
            <div class="row m-0 p-0">
                <div  class="col-sm-12 m-0 p-0">
                    <div onclick = "changeSubMenu(this,'#mo_advance_mapping')" class="mo_sub_menu">
                        <span><?php echo Text::_('COM_MINIORANGE_OAUTH_ADD_SETTINGS'); ?></span><sup><img class="crown_img_small ml-2" src="<?php echo Uri::base();?>/components/com_miniorange_oauth/assets/images/crown.webp"></a></sup>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-10">
            <div class="row m-1 my-3" id="mo_basic_mapping">
                <div class="col-sm-12 mt-2" id="mo_oauth_attributemapping">
                    <div class="row mt-2">
                        <div class="col-sm-12">
                            <h5 class="element"><?php echo Text::_('COM_MINIORANGE_OAUTH_USER_ATT'); ?>
                                <a href="https://developers.miniorange.com/docs/oauth-joomla/attribute-mapping" target="_blank" class="mo_handbook" ><sup><i class="fa-regular fa-circle-question mo_oauth_icon_color" title="What is basic attribute mapping?"></i></sup></a>
                            </h5>
                            <br>
                        </div>
                        <br><br>
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-12"><?php echo Text::_('COM_MINIORANGE_OAUTH_USER_ATT_TEXT'); ?></div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-3">
                                    <label for=""><span class="mo_oauth_highlight">*</span><?php echo Text::_('COM_MINIORANGE_OAUTH_USERNAME'); ?></label>
                                </div>
                                <div class="col-sm-9">
                                    <input class="form-control" readonly type="text" id="mo_oauth_uname_attr" name="mo_oauth_uname_attr" value='<?php echo $username?>' placeholder="<?php echo Text::_('COM_MINIORANGE_OAUTH_USERNAME_PLACE'); ?>" required>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-3">
                                    <label for=""><span class="mo_oauth_highlight">*</span><?php echo Text::_('COM_MINIORANGE_OAUTH_EMAIL'); ?>:</label>
                                </div>
                                <div class="col-sm-9">
                                    <input class="form-control" readonly type="text" name="mo_oauth_email_attr" value='<?php echo $email?>' placeholder="<?php echo Text::_('COM_MINIORANGE_OAUTH_USERNAME_PLACE'); ?>" required>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-3">
                                    <label for="">
                                        <span class="mo_oauth_highlight">*</span><?php echo Text::_('COM_MINIORANGE_OAUTH_DISPLAY'); ?>
                                    </label>    
                                </div>
                                <div class="col-sm-9">
                                    <input class="form-control" disabled type="text"  id="mo_oauth_dname_attr" name="mo_oauth_dname_attr" placeholder="<?php echo Text::_('COM_MINIORANGE_OAUTH_USERNAME_PLACE'); ?>" value=''>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-sm-12 mt-3 text-right">
                                    <input type="submit" disabled class="btn mo_oauth_cursor mo_oauth_all_btn p-1" name="send_query" value='<?php echo Text::_('COM_MINIORANGE_OAUTH_SAVE_ATTRIBUTE_MAPPING');?>'/>
                                </div>
                            </div>
                        </div>
                    </div>  
                </div>
            </div>
            <div class="row m-1 my-3 mo_oauth_display-none" id="mo_profile_mapping">
                <div class="col-sm-12 mt-3">
                    <h5 class="element"> 
                        <?php echo Text::_('COM_MINIORANGE_OAUTH_MAP_JOOMLA_USER_PROFILE_ATTRIBUTES');?>
                        <a href="https://developers.miniorange.com/docs/oauth-joomla/attribute-mapping" target="_blank" class="mo_handbook" ><sup><i class="fa-regular fa-circle-question" title="What is Profile attribute mapping?"></i></sup></a>
                    </h5>
                    <hr>
                </div>
                <div class="col-sm-12 mt-2">
                    <p class="alert alert-info " ><?php echo Text::_('COM_MINIORANGE_OAUTH_MAP_JOOMLA_USER_PROFILE_ATTRIBUTES_NOTE');?> <a href='<?php echo $license_tab_link;?>' class='mo_oauth_coming_soon_features premium'><strong>Premium </a> </strong>and <a href='<?php echo $license_tab_link;?>' class='mo_oauth_coming_soon_features premium'> <strong>Enterprise</strong></a> versions of plugin.</p>
                </div>
                <div class="col-sm-12 my-4">
                    <div class="row my-3">
                        <div class="col-sm-12">
                            <input type="button" class="btn mo_oauth_input mo_oauth_all_btn px-3 mx-1" disabled="true"  value="+" />
                            <input type="button" class="btn mo_oauth_input btn-danger px-5 mx-1" disabled="true" value="<?php echo Text::_('COM_MINIORANGE_OAUTH_CLEAR_ALL'); ?>" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_USER_PROFILE_ATTRIBUTE');?></strong>
                        </div>
                        <div class="col-sm-6">
                            <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_SERVER_ATTRIBUTE');?></strong>
                        </div>
                    </div>
                    <div class="row m-0 p-0 my-3">
                        <div class="col-sm-6">
                            <select class="mo-form-control mo-form-control-select" readonly>
                                <option value=""><?php echo Text::_('COM_MINIORANGE_OAUTH_S_USER_PROFILE_ATTRIBUTE'); ?></option>
                                <option value=""><?php echo Text::_('COM_MINIORANGE_OAUTH_ADD1'); ?></option>
                                <option value=""><?php echo Text::_('COM_MINIORANGE_OAUTH_ADD2'); ?></option>
                                <option value=""><?php echo Text::_('COM_MINIORANGE_OAUTH_CITY'); ?></option>
                                <option value=""><?php echo Text::_('COM_MINIORANGE_OAUTH_REGION'); ?></option>
                                <option value=""><?php echo Text::_('COM_MINIORANGE_OAUTH_COUNTRY'); ?></option>
                                <option value=""><?php echo Text::_('COM_MINIORANGE_OAUTH_PIN'); ?></option>
                                <option value=""><?php echo Text::_('COM_MINIORANGE_OAUTH_PHONE'); ?></option>
                                <option value=""><?php echo Text::_('COM_MINIORANGE_OAUTH_WEBSITE'); ?></option>
                                <option value=""><?php echo Text::_('COM_MINIORANGE_OAUTH_FAV_BOOK'); ?></option>
                                <option value=""><?php echo Text::_('COM_MINIORANGE_OAUTH_ABOUT_ME'); ?></option>
                                <option value=""><?php echo Text::_('COM_MINIORANGE_OAUTH_DOB'); ?></option>
                            </select>
                        </div>
                        <div class="col-sm-5">
                            <input type="text" placeholder="<?php echo Text::_('COM_MINIORANGE_OAUTH_DISPLAY_NOTE'); ?>"  class="form-control" disabled="disabled"/>
                        </div>
                        <div class="col-sm-1">
                           <input type="button" class="btn float-right btn-secondary px-3 mx-1" disabled="true" value="-" />
                        </div>
                    </div>
                    <div class="row m-0 p-0 my-3">
                        <div class="col-sm-6">
                            <select class="mo-form-control mo-form-control-select" readonly>
                                <option value=""><?php echo Text::_('COM_MINIORANGE_OAUTH_S_USER_PROFILE_ATTRIBUTE'); ?></option>
                                <option value=""><?php echo Text::_('COM_MINIORANGE_OAUTH_ADD1'); ?></option>
                                <option value=""><?php echo Text::_('COM_MINIORANGE_OAUTH_ADD2'); ?></option>
                                <option value=""><?php echo Text::_('COM_MINIORANGE_OAUTH_CITY'); ?></option>
                                <option value=""><?php echo Text::_('COM_MINIORANGE_OAUTH_REGION'); ?></option>
                                <option value=""><?php echo Text::_('COM_MINIORANGE_OAUTH_COUNTRY'); ?></option>
                                <option value=""><?php echo Text::_('COM_MINIORANGE_OAUTH_PIN'); ?></option>
                                <option value=""><?php echo Text::_('COM_MINIORANGE_OAUTH_PHONE'); ?></option>
                                <option value=""><?php echo Text::_('COM_MINIORANGE_OAUTH_WEBSITE'); ?></option>
                                <option value=""><?php echo Text::_('COM_MINIORANGE_OAUTH_FAV_BOOK'); ?></option>
                                <option value=""><?php echo Text::_('COM_MINIORANGE_OAUTH_ABOUT_ME'); ?></option>
                                <option value=""><?php echo Text::_('COM_MINIORANGE_OAUTH_DOB'); ?></option>
                            </select>
                        </div>
                        <div class="col-sm-5">
                            <input type="text" placeholder="<?php echo Text::_('COM_MINIORANGE_OAUTH_MAP'); ?>"  class="form-control" disabled="disabled"/>
                        </div>
                        <div class="col-sm-1">
                           <input type="button" class="btn float-right btn-secondary px-3 mx-1" disabled="true" value="-" />
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-3 text-right">
                            <input type="submit" name="send_query" value=' <?php echo Text::_('COM_MINIORANGE_OAUTH_SAVE_PROFILE_ATTRIBUTE_MAPPING');?>' disabled class="btn mo_oauth_all_btn p-2 mb-3 mo_oauth_cursor"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row m-1 my-3 mo_oauth_display-none" id="mo_field_mapping">
                <div class="col-sm-12 mt-3">
                    <h5 class="element">
                        <?php echo Text::_('COM_MINIORANGE_OAUTH_MAP_JOOMLA_USER_FIELD_ATTRIBUTES');?>
                        <a href="https://developers.miniorange.com/docs/oauth-joomla/attribute-mapping" target="_blank" class="mo_handbook" ><sup><i class="fa-regular fa-circle-question" title="What is Field attribute mapping?"></i></sup></a>
                    </h5>
                    <hr>
                </div>
                <div class="col-sm-12 mt-2">
                    <p class="alert alert-info "><?php echo Text::_('COM_MINIORANGE_OAUTH_MAP_JOOMLA_USER_FIELD_ATTRIBUTES_NOTE');?></p>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <input type="button" class="btn mo_oauth_input mo_oauth_all_btn mx-1"  value="+" disabled/>
                        <input type="button" class="btn mo_oauth_input btn-danger mx-1" value="<?php echo Text::_('COM_MINIORANGE_OAUTH_USER_CLEAR_ALL');?>" disabled/>
                    </div>
                </div>
                <div class="col-sm-12 my-3">
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_USER_FIELD_ATTRIBUTE');?></strong>
                        </div>
                        <div class="col-sm-6">
                            <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_SERVER_ATTRIBUTE');?></strong>
                        </div>
                    </div>
                    <div class="row m-0 p-0 my-3">
                        <div class="col-sm-6">
                            <input class="form-control" type="text" placeholder="<?php echo Text::_('COM_MINIORANGE_OAUTH_DISPLAY_NOTE2'); ?>" disabled/>
                        </div>
                        <div class="col-sm-5">
                            <input class="form-control" type="text" disabled placeholder="<?php echo Text::_('COM_MINIORANGE_OAUTH_DISPLAY_NOTE'); ?>"  />
                        </div>
                        <div class="col-sm-1">
                           <input type="button" class="btn float-right btn-secondary px-3 mx-1" disabled="true" value="-" />
                        </div>
                    </div>
                    <div class="row m-0 p-0 my-3">
                        <div class="col-sm-6">
                            <input class="form-control" type="text" placeholder="<?php echo Text::_('COM_MINIORANGE_OAUTH_DISPLAY_NOTE2'); ?>" disabled/>
                        </div>
                        <div class="col-sm-5">
                            <input class="form-control" type="text" disabled placeholder="<?php echo Text::_('COM_MINIORANGE_OAUTH_DISPLAY_NOTE'); ?>" />
                        </div>
                        <div class="col-sm-1">
                           <input type="button" class="btn float-right btn-secondary px-3 mx-1" disabled="true" value="-" />
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-3 text-right">
                            <input type="submit" name="send_query" value='<?php echo Text::_('COM_MINIORANGE_OAUTH_SAVE_FIELD_ATTRIBUTE_MAPPING');?>' disabled class="btn mo_oauth_all_btn p-2 mb-3 mo_oauth_cursor"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row m-1 my-3 mo_oauth_display-none" id="mo_group_mapping">
                <div class="col-sm-12">
                    <div class="row my-3">
                        <div class="col-sm-12 mt-3">
                            <h5 class="element">
                                <?php echo Text::_('COM_MINIORANGE_OAUTH_GROUP_MAPPING');?>
                                <a href="https://developers.miniorange.com/docs/oauth-joomla/role-mapping" target="_blank" class="mo_handbook" ><sup><i class="fa-regular fa-circle-question" title="What is Group/Role mapping?"></i></sup></a>
                            </h5>
                            <hr>
                            <p><?php echo Text::_('COM_MINIORANGE_OAUTH_GROUP_MAPPING_NOTE');?></p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 my-4">
                    <div class="row">
                        <div class="col-sm-4">
                            <p><?php echo Text::_('COM_MINIORANGE_OAUTH_SELECT_DEFAULT_GROUP_FOR_NEW_USER');?></p>
                        </div>
                        <div class="col-sm-8">
                            <?php
                                $db = Factory::getDbo();
                                $db->setQuery($db->getQuery(true)
                                    ->select('*')
                                    ->from("#__usergroups")
                                );
                                $groups = $db->loadRowList();

                                echo '<select class="mo-form-control mo-form-control-select mo_oauth_cursor-pointer" readonly name="mapping_value_default" id="default_group_mapping">';

                                foreach ($groups as $group)
                                {
                                    if ($group[4] != 'Super Users'&&$group[4] != 'Public'&&$group[4] != 'Guest')
                                        echo '<option selected="selected" value = "' . $group[0] . '">' . $group[4] . '</option>';
                                }
                            ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 mt-2">
                    <div class="row mt-2">
                        <div class="col-sm-4">
                            <p><?php echo Text::_('COM_MINIORANGE_OAUTH_GROUP_ATTRIBUTE_NAMES');?></p>
                        </div>
                        <div class="col-sm-8">
                            <input class="form-control" placeholder="<?php echo Text::_('COM_MINIORANGE_OAUTH_GROUP_ATTRIBUTE_NAMES_PLACEHOLDER');?>" type="text" id="mo_oauth_gname_attr" name="mo_oauth_gname_attr" value='' disabled>
                        </div>
                    </div>
                    <hr class="bg-dark">
                </div>
                <div class=" col-sm-12 my-2">
                    <div class="row mt-3">
                        <div class="col-sm-4">
                            <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_GROUP_NAME_IN_JOOMLA');?></strong>
                        </div>
                        <div class="col-sm-8">
                            <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_GROUP_ROLE_NAME_IN_CONFIGURED_APP');?></strong>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <?php
                            $user_role = array();
                            if (empty($role_mapping_key_value)) {
                                foreach ($groups as $group) {
                                    if ($group[4] != 'Super Users') {
                                        echo '<div class="col-sm-4 mt-2">' . $group[4] . '</div><div class="col-sm-8 mt-2"><input class="form-control"  disabled type="text" id="oauth_group_attr_values' . $group[0] . '" name="oauth_group_attr_values' . $group[0] . '" value= "" placeholder="'.Text::_('COM_MINIORANGE_OAUTH_GROUP_ROLE_NAME_IN_CONFIGURED_APP_PLACEHOLDER'). $group[4] . '" "' . ' /></div>';
                                    }
                                }
                            }
                            else
                            {
                                foreach ($groups as $group)
                                {
                                    if ($group[4] != 'Super Users')
                                    {
                                        $role_value = array_key_exists($group[0], $role_mapping_key_value) ? $role_mapping_key_value[$group[0]] : "";
                                        echo '<div class="col-sm-4 offset-sm-1"><strong>' . $group[4] . '</strong></div><div class="col-sm-6"><input  class="form-control"  disabled type="text" id="oauth_group_attr_values' . $group[0] . '" name="oauth_group_attr_values' . $group[0] . '" value= "' . $role_value . '" placeholder="'.Text::_('COM_MINIORANGE_OAUTH_GROUP_ROLE_NAME_IN_CONFIGURED_APP_PLACEHOLDER'). $group[4] . '" "' . ' /></div>';
                                    }
                                }
                            }
                        ?>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="row mt-4">
                        <div class="col-sm-12 mt-3 text-right">
                            <input type="submit" name="send_query" value='<?php echo Text::_('COM_MINIORANGE_SAVE_GROUP_MAPPING');?>' disabled class="btn mo_oauth_all_btn p-2 px-4 mb-3 mo_oauth-cursor"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row m-1 my-3 mo_oauth_display-none" id="mo_advance_mapping">
                <div class="col-sm-12">
                    <div class="row my-3">
                        <div class="col-sm-12 mt-3">
                            <h5 class="element">
                                <?php echo Text::_('COM_MINIORANGE_OAUTH_ADD_SETTINGS');?>
                            </h5>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="row my-3">
                        <div class="col-sm-12 mt-3">
                            <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_GROUP_ATT_MAPPING');?></strong>
                            <hr class="bg-dark">
                        </div>
                    </div>
                </div>
                
                <div class="col-sm-12 m-0 p-0 mt-2">
                    <div class="row mt-3">
                        <div class="col-sm-12">
                            <div class="form-check form-switch">
                            <label id=" mo_oauth_switch">
                                <input disabled type="checkbox" class="form-check-input" name=" mo_oauth_custom_checkbox" id=" mo_oauth_check">
                            </label> <?php echo Text::_('COM_MINIORANGE_OAUTH_TEXT_FILE');?>
                            </div>
                            <br>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 m-0 p-0">
                    <div class="row my-3">
                        <div class="col-sm-12 mt-3">
                            <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_GROUP_MAPPING');?></strong>
                            <hr class="bg-dark">
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 m-0 p-0">
                    <div class="row mt-3">
                        <div class="col-sm-12">
                            <div class="form-check form-switch">
                                <label id=" mo_oauth_switch">
                                    <input disabled type="checkbox" class="form-check-input" name=" mo_oauth_custom_checkbox" id=" mo_oauth_check">
                                </label> <?php echo Text::_('COM_MINIORANGE_OAUTH_DO_NOT_UPDATE_EXISTING_USER_GROUPS');?>
                                <br>
                                <label id=" mo_oauth_switch">
                                    <input disabled type="checkbox" class="form-check-input" name=" mo_oauth_custom_checkbox" id=" mo_oauth_check">
                                </label> <?php echo Text::_('COM_MINIORANGE_OAUTH_DO_NOT_UPDATE_EXISTING_USER_GROUPS_AND_NEWLY_MAPPED_ROLES');?>
                                <br>
                                <label id=" mo_oauth_switch">
                                    <input disabled type="checkbox" class="form-check-input" name=" mo_oauth_custom_checkbox" id=" mo_oauth_check">
                                </label> <?php echo Text::_('COM_MINIORANGE_OAUTH_DO_NOT_AUTO_CREATE_USERS_IF_ROLES_NOT_MAPPED');?>
                                <br>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="row mt-4">
                        <div class="col-sm-12 mt-3 text-right">
                            <input type="submit" disabled name="send_query" value='<?php echo Text::_('COM_MINIORANGE_OAUTH_SAVE_ADD_SETTINGS');?>' class="btn mo_oauth_all_btn p-2 px-4 mb-3 mo_oauth_cursor"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}

//proxy settings saved//
function proxy_setup()
{
    // Fetch saved proxy configuration from the database
    $db = Factory::getDbo();
    $query = $db->getQuery(true)
                ->select('*')
                ->from($db->quoteName('#__miniorange_oauth_config'));
    $db->setQuery($query);
    $proxyConfig = $db->loadObject();

    // Set default values if no config is found
    $proxy_host_name = $proxyConfig->proxy_host_name ?? '';
    $port_number = $proxyConfig->port_number ?? '';
    $username = $proxyConfig->username ?? '';
    $password = $proxyConfig->password ?? '';

    // Render the Proxy Setup Form
    ?>
<div  class="container-fluid">
    <div class="row">
     <h1 class="mo_export_heading pt-4 "><?php echo Text::_('COM_MINIORANGE_PROXY_SETUP'); ?></h1>
        <p><?php echo Text::_('COM_MINIORANGE_PROXY_SETUP_DESCRIPTION'); ?></p>
        <form action="<?php echo Route::_('index.php?option=com_miniorange_oauth&task=accountsetup.proxyConfig'); ?>" method="post" name="proxy_form">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-3">
                        <label for="mo_proxy_host">
                            <span class="mo_oauth_highlight">*</span><?php echo Text::_('COM_MINIORANGE_PROXY_HOSTNAME'); ?>:
                        </label>
                    </div>
                    <div class="col-sm-9">
                        <input class="form-control" type="text" id="mo_proxy_host" name="mo_proxy_host" value="<?php echo htmlspecialchars($proxy_host_name); ?>" placeholder="Enter Proxy Host Name" required>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-sm-3">
                        <label for="mo_proxy_port">
                            <span class="mo_oauth_highlight">*</span><?php echo Text::_('COM_MINIORANGE_PROXY_PORT'); ?>:
                        </label>
                    </div>
                    <div class="col-sm-9">
                        <input class="form-control" type="number" id="mo_proxy_port" name="mo_proxy_port" value="<?php echo htmlspecialchars($port_number); ?>" placeholder="Enter Proxy Port" required>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-sm-3">
                        <label for="mo_proxy_username"><?php echo Text::_('COM_MINIORANGE_PROXY_USERNAME'); ?>:</label>
                    </div>
                    <div class="col-sm-9">
                        <input class="form-control" type="text" id="mo_proxy_username" name="mo_proxy_username" value="<?php echo htmlspecialchars($username); ?>" placeholder="Enter Proxy Username">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-sm-3">
                        <label for="mo_proxy_password"><?php echo Text::_('COM_MINIORANGE_PROXY_PASSWORD'); ?>:</label>
                    </div>
                    <div class="col-sm-9">
                        <input class="form-control" type="password" id="mo_proxy_password" name="mo_proxy_password" value="<?php echo htmlspecialchars($password); ?>" placeholder="Enter Proxy Password">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-sm-12 mt-3 text-center mb-3">
                        <input type="submit" value=<?php echo Text::_('COM_MINIORANGE_SAVE'); ?> class="btn mo_oauth_cursor mo_oauth_all_btn p-1">
                        <input type="button" value=<?php echo Text::_('COM_MINIORANGE_RESET'); ?> onclick="window.location='<?php echo JRoute::_('index.php?option=com_miniorange_oauth&task=accountsetup.proxyConfigReset'); ?>'" class="btn mo_oauth_cursor mo_oauth_all_btn p-1">
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>   

    
    <?php
}

function moOAuthConfiguration()
{
    global $license_tab_link;
    global $license_tab_link;
    $appArray = json_decode(getAppJason(),TRUE);
    $app = Factory::getApplication();
    $get = $app->input->get->getArray();
    $attribute = getAppDetails();
    $isAppConfigured = empty($attribute['client_secret']) || empty($attribute['client_id']) || empty($attribute['custom_app'] )|| empty($attribute['redirecturi'] )?FALSE:TRUE;
    if(isset($get['moAuthAddApp']) && !empty($get['moAuthAddApp']) )
    {
        configuration($appArray[$get['moAuthAddApp']],$get['moAuthAddApp']);
        return;
    }
    else if($isAppConfigured)
    {
        configuration($appArray[$attribute['appname']],$attribute['appname']);
        return;
    }
    else
    { ?>
        <div class="row m-0 p-1">
            <div class="col-sm-2 m-0 p-0 mo_oauth_border_right">
                <div class="row m-0 p-0">
                    <div class="col-sm-12 m-0 p-0">
                        <div onclick = "changeSubMenu(this , '#mo_pre_configure_app')" class="mo_sub_menu mo_sub_menu_active">
                            <span><?php echo Text::_('COM_MINIORANGE_OAUTH_PRE_CONFIG_APPS');?></span>
                        </div>
                    </div>
                </div>
                <div class="row m-0 p-0">
                    <div class="col-sm-12 m-0 p-0">
                        <div onclick = "changeSubMenu(this,'#mo_custom_app')" class="mo_sub_menu">
                            <span><?php echo Text::_('COM_MINIORANGE_OAUTH_CUSTOM_APPLICATION');?></span>
                        </div>
                    </div>
                </div>
                <div class="row m-0 p-0">
                    <div class="col-sm-12 m-0 p-0">
                        <div onclick = "changeSubMenu(this,'#mo_multiple_provider')" class="mo_sub_menu">
                            <span><?php echo Text::_('COM_MINIORANGE_OAUTH_ADD_MORE_APPS');?></span><sup><img class="crown_img_small ml-2" src="<?php echo Uri::base();?>/components/com_miniorange_oauth/assets/images/crown.webp"></a></sup>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-10">
                <div class="row m-1 my-3" id="mo_pre_configure_app">
                    <?php selectAppByIcon() ;?>
                </div>
                <div class="row m-1 my-3 mo_oauth_display-none" id="mo_custom_app">
                    <?php selectCustomApp(); ?>
                </div>
                <div class="row m-1 my-3 mo_oauth_display-none" id="mo_multiple_provider">
                    <div class="col-sm-12 alert-info p-5 my-4">
                        <?php echo Text::_('COM_MINIORANGE_OAUTH_FUNCTIONALITY');?><strong>joomlasupport@xecurify.com</strong>.
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}

function grant_type_settings() {
    global $license_tab_link;
    ?>
    <div class="row mr-1 my-3 ">
        <div class="col-sm-12 mt-4">
            <h3 class="mo_oauth_display-none"><?php echo Text::_('COM_MINIORANGE_OAUTH_GRANT_SETTINGS');?><sup><code><small><a href="<?php echo $license_tab_link;?>"  rel="noopener noreferrer">[PREMIUM,ENTERPRISE]</a></small></code></sup></h3>
            <hr>
        </div>
        <div class="col-sm-12 mt-2">
            <h4><?php echo Text::_('COM_MINIORANGE_OAUTH_S_GRANT_TYPE');?></h4>
        </div>
        <div class="col-sm-12 mt-2 grant_types">
            <div class="form-check form-switch">
            <input checked disabled type="checkbox" class="form-check-input">&emsp;<strong><?php echo Text::_('COM_MINIORANGE_OAUTH_AUTH_CODE_GRANT');?></strong>&emsp;<code><small>[DEFAULT]</small></code>
            <blockquote><?php echo Text::_('COM_MINIORANGE_OAUTH_CODE_TEXT');?></blockquote>
            <input disabled type="checkbox" class="form-check-input">&emsp;<strong><?php echo Text::_('COM_MINIORANGE_OAUTH_IMPLICIT_GRANT');?></strong>
            <blockquote><?php echo Text::_('COM_MINIORANGE_OAUTH_CODE_TEXT2');?></blockquote>
            <input disabled type="checkbox" class="form-check-input">&emsp;<strong><?php echo Text::_('COM_MINIORANGE_OAUTH_PWD_GRANT');?></strong>
            <blockquote><?php echo Text::_('COM_MINIORANGE_OAUTH_CODE_TEXT3');?></blockquote>
            <input disabled type="checkbox" class="form-check-input">&emsp;<strong><?php echo Text::_('COM_MINIORANGE_OAUTH_REFRESH_TOKEN_GRANT');?></strong>
            <blockquote><?php echo Text::_('COM_MINIORANGE_OAUTH_CODE_TEXT4');?></blockquote>
            </div>
        </div>
        <div class="col-sm-12 mt-2">
            <hr>
            <h3 class="mo_oauth_display-inline"><?php echo Text::_('COM_MINIORANGE_OAUTH_JWT_VALID');?><sup><code><small><a href="<?php echo $license_tab_link;?>"  rel="noopener noreferrer">[PREMIUM,ENTERPRISE]</a></small></code></sup></h3>
            <hr>
        </div>
        <div class="col-sm-12 mt-2 form-check form-switch">
            <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_JWT_VERIFY');?></strong>
            <input type="checkbox"class="form-check-input" value="" disabled/>
        </div>
        <div class="col-sm-12 mt-2">
            <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_JWT_ALGO');?></strong>
            <select disabled>
                <option><?php echo Text::_('COM_MINIORANGE_OAUTH_JWT_ALGO_HSA');?></option>
                <option><?php echo Text::_('COM_MINIORANGE_OAUTH_JWT_ALGO_RSA');?></option>
            </select> 
        </div>
        <div class="col-sm-12 my-2">
            <div class="notes">
                <hr /><?php echo Text::_('COM_MINIORANGE_OAUTH_CODE_TEXT5');?>
                <a href="<?php echo $license_tab_link;?>" rel="noopener noreferrer"><?php echo Text::_('COM_MINIORANGE_OAUTH_CODE_TEXT6');?></a> <?php echo Text::_('COM_MINIORANGE_OAUTH_CODE_TEXT7');?>
            </div>
        </div>
    </div>
    <?php
}

function loginlogoutsettings()
{
    global $license_tab_link;
    ?>
    <div class="row m-0 p-1">
        <div class="col-sm-2 m-0 p-0 mo_oauth_border_right">
            <div class="row m-0 p-0">
                <div class="col-sm-12 m-0 p-0">
                    <div onclick = "changeSubMenu(this,'#mo_general_setting')" class="mo_sub_menu mo_sub_menu_active">
                        <span><?php echo Text::_('COM_MINIORANGE_OAUTH_ADD_SETTINGS');?></span>
                    </div>
                </div>
            </div>
            <div class="row m-0 p-0">
                <div class="col-sm-12 m-0 p-0">
                    <div onclick = "changeSubMenu(this,'#mo_domain_restriction')" class="mo_sub_menu">
                        <span><?php echo Text::_('COM_MINIORANGE_OAUTH_CODE_DOMAIN_REST');?></span>
                    </div>
                </div>
            </div>
            <div class="row m-0 p-0">
                <div class="col-sm-12 m-0 p-0">
                    <div onclick = "changeSubMenu(this,'#mo_redirect_url')" class="mo_sub_menu">
                        <span><?php echo Text::_('COM_MINIORANGE_OAUTH_REDIRECT_URLS');?></span>
                    </div>
                </div>
            </div>
            <div class="row m-0 p-0">
                <div  class="col-sm-12 m-0 p-0">
                    <div onclick = "changeSubMenu(this,'#mo_backdoor_url')" class="mo_sub_menu">
                        <span><?php echo Text::_('COM_MINIORANGE_OAUTH_BACKDOOR_URL');?></span>
                    </div>
                </div>
            </div>
            <div class="row m-0 p-0">
                <div  class="col-sm-12 m-0 p-0">
                    <div onclick = "changeSubMenu(this,'#mo_slo_setting')" class="mo_sub_menu">
                        <span><?php echo Text::_('COM_MINIORANGE_OAUTH_SINGLE_LOGOUT');?></span>
                    </div>
                </div>
            </div>
            <div class="row m-0 p-0">
                <div  class="col-sm-12 m-0 p-0">
                    <div onclick = "changeSubMenu(this,'#mo_oauth_token')" class="mo_sub_menu">
                        <span><?php echo Text::_('COM_MINIORANGE_OAUTH_FETCH_ACCESS_TOKEN');?></span>
                    </div>
                </div>
            </div>
            <div class="row m-0 p-0">
                <div  class="col-sm-12 m-0 p-0">
                    <div onclick = "changeSubMenu(this,'#mo_custom_sso_button')" class="mo_sub_menu">
                        <span><?php echo Text::_('COM_MINIORANGE_OAUTH_CUSTOM_SSO_BUTTON');?></span>
                    </div>
                </div>
            </div>
            <div class="row m-0 p-0">
                <div  class="col-sm-12 m-0 p-0">
                    <div onclick = "changeSubMenu(this,'#mo_user_analytics')" class="mo_sub_menu">
                        <span><?php echo Text::_('COM_MINIORANGE_OAUTH_SSO_REPORT');?></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-10">
            <div class="row m-1 my-3" id="mo_general_setting">
                <div class="col-sm-12 mt-2" id="mo_oauth_attributemapping">
                    <div class="row mt-2">
                        <div class="col-sm-12">
                            <h5 class="element">
                                <?php echo Text::_('COM_MINIORANGE_OAUTH_ADD_SETTINGS');?> <sup><img class="crown_img_small ml-2" src="<?php echo Uri::base();?>/components/com_miniorange_oauth/assets/images/crown.webp"></a></sup>
                                <a href="https://developers.miniorange.com/docs/oauth-joomla/advanced-setting" target="_blank" class="mo_handbook" ><sup><i class="fa-regular fa-circle-question" title="Know more about additional setting in OAuth Client"></i></sup></a>
                            </h5>
                            <br>
                        </div>

                        <div class="col-sm-12 ">
                            <div class="row mt-3">
                                <div class="col-sm-12">
                                    <div class="form-check form-switch">
                                    <label id=" mo_oauth_switch">
                                        <input type="checkbox" class="form-check-input" name="mo_oauth_auto_redirect" id="mo_oauth_auto_redirect" value="1" disabled/>
                                    </label> <span><?php echo Text::_('COM_MINIORANGE_OAUTH_RESTRICT_ANNONYMOUS_ACCESS');?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="row mt-3">
                                <div class="col-sm-12">
                                    <div class="form-check form-switch">
                                    <label id=" mo_oauth_switch">
                                        <input type="checkbox" class="form-check-input" name="mo_oauth_enable_log" id="mo_oauth_enable_log" value="1" disabled/>
                                    </label> <?php echo Text::_('COM_MINIORANGE_OAUTH_RESTRICT_ANNONYMOUS_ACCESS_TEXT');?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="row mt-3">
                                <div class="col-sm-12 mt-3 text-right">
                                    <input type="submit" name="send_query" value='<?php echo Text::_('COM_MINIORANGE_OAUTH_SAVE_SETTINGS');?>' disabled class="btn mo_oauth_all_btn p-2 px-4 mb-3 mo_oauth_cursor"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row m-1 my-3 mo_oauth_display-none" id="mo_domain_restriction">
                <div class="col-sm-12 mt-3">
                    <h5 class="element"> 
                        <?php echo Text::_('COM_MINIORANGE_OAUTH_DOMAIN_SETTINGS');?><sup><img class="crown_img_small ml-2" src="<?php echo Uri::base();?>/components/com_miniorange_oauth/assets/images/crown.webp"></a></sup>
                        <a href="https://developers.miniorange.com/docs/oauth-joomla/advanced-setting" target="_blank" class="mo_handbook" ><sup><i class="fa-regular fa-circle-question" title="What is Domain restriction?"></i></sup></a>
                    </h5>
                    <hr>
                </div>
                <div class="col-sm-12 mt-2">
                    <p class="alert alert-info "> <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_TEST_CONFIG_NOTE');?></strong><?php echo Text::_('COM_MINIORANGE_OAUTH_RESTRICTED_DOMAINS_TEXT');?>
                </div>
                <div class="col-sm-12 my-4">
                    <div class="row m-1 mt-5">
                        <div class="col-sm-3">
                            <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_RESTRICTED_DOMAINS');?></strong>
                        </div>
                        <div class="col-sm-8">
                            <textarea class="col-sm-12" name="" id="" rows="6" id="mo_oauth_restricted_domains" name="mo_oauth_restricted_domains" value='' disabled placeholder="<?php echo Text::_('COM_MINIORANGE_OAUTH_RESTRICTED_DOMAINS_NAME_NOTE');?>"></textarea>
                            <p><em><?php echo Text::_('COM_MINIORANGE_OAUTH_RESTRICTED_DOMAINS_NOTE');?></em></p>
                        </div>
                    </div>
                    <div class="row m-1 mt-2">
                        <div class="col-sm-3">
                            <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_ALLOWED_DOMAINS');?></strong>
                        </div>
                        <div class="col-sm-8">
                            <textarea class="col-sm-12" name="" id="" rows="6" value='' disabled placeholder="<?php echo Text::_('COM_MINIORANGE_OAUTH_RESTRICTED_DOMAINS_NAME_NOTE');?>"></textarea>
                            <p><em><?php echo Text::_('COM_MINIORANGE_OAUTH_ALLOWED_DOMAINS_NOTE');?></em></p>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-3 text-right">
                            <input type="submit" disabled name="send_query" value='<?php echo Text::_('COM_MINIORANGE_OAUTH_SAVE_DOMAIN_RESTRICTION');?>' class="btn mo_oauth_all_btn p-2 mb-3 mo_oauth_cursor"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row m-1 my-3 mo_oauth_display-none" id="mo_redirect_url">
                <div class="col-sm-12 mt-3">
                    <h5 class="element"><?php echo Text::_('COM_MINIORANGE_OAUTH_REDIRECT_SSO_URL');?>
                    <sup><img class="crown_img_small ml-2" src="<?php echo Uri::base();?>/components/com_miniorange_oauth/assets/images/crown.webp"></a></sup>
                        <a href="https://developers.miniorange.com/docs/oauth-joomla/advanced-setting" target="_blank" class="mo_handbook" ><sup><i class="fa-regular fa-circle-question" title="Know more about Redirect URls feature"></i></sup></a>
                    </h5>
                    <hr>
                </div>
                <div class="col-sm-12 mt-2">
                    <p class="alert alert-info " ><strong><?php echo Text::_('COM_MINIORANGE_OAUTH_TEST_CONFIG_NOTE');?></strong><?php echo Text::_('COM_MINIORANGE_OAUTH_RESTRICTED_DOMAINS_NOTE2');?> </p>
                </div>
                <div class="col-sm-12 my-3">
                    <div class="row m-1 mt-5">
                        <div class="col-sm-3">
                            <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_LOGIN_REDIRECT_URL');?></strong>
                        </div>
                        <div class="col-sm-8">
                            <input class="form-control" type="text" value='' disabled placeholder="<?php echo Text::_('COM_MINIORANGE_OAUTH_LOGIN_REDIRECT_URL_NOTE_SSO');?>">
                            <p><em><?php echo Text::_('COM_MINIORANGE_OAUTH_LOGIN_REDIRECT_URL_NOTE');?></em></p>
                        </div>
                    </div>
                    <div class="row m-1 mt-2">
                        <div class="col-sm-3">
                            <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_LOGOUT_REDIRECT_URL');?></strong>
                        </div>
                        <div class="col-sm-8">
                            <input class="form-control" type="text" value='' disabled placeholder="<?php echo Text::_('COM_MINIORANGE_OAUTH_LOGIN_REDIRECT_URL_NOTE_SSO');?>">
                            <p><em><?php echo Text::_('COM_MINIORANGE_OAUTH_LOGOUT_REDIRECT_URL_NOTE');?></em></p>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-3 text-right">
                            <input type="submit" disabled name="send_query" value='<?php echo Text::_('COM_MINIORANGE_OAUTH_SAVE_REDIRECT_URL');?>' class="btn mo_oauth_all_btn p-2 mb-3 mo_oauth-cursor"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row m-1 my-3 mo_oauth_display-none"id="mo_backdoor_url">
                <div class="col-sm-12">
                    <div class="row my-3">
                        <div class="col-sm-12 mt-3">
                            <h5 class="element">
                                <?php echo Text::_('COM_MINIORANGE_OAUTH_CUSTOMIZE_LOGIN_URL');?><sup><img class="crown_img_small ml-2" src="<?php echo Uri::base();?>/components/com_miniorange_oauth/assets/images/crown.webp"></a></sup>
                                <a href="https://developers.miniorange.com/docs/oauth-joomla/advanced-setting" target="_blank" class="mo_handbook" ><sup><i class="fa-regular fa-circle-question" title="What is Backdoor URL?"></i></sup></a>
                            </h5>
                            <hr>
                            <p><?php echo Text::_('COM_MINIORANGE_OAUTH_NOTE_ADMIN');?></p>
                        </div>
                    </div>
                </div>
                <div class=" col-sm-12 my-2">
                    <div class="row">
                        <div class="col-sm-4">
                            <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_CUSTOM_LOGIN_URL');?><code>/administrator</code> </strong>):
                        </div>
                        <div class="col-sm-8">
                            <div class="form-check form-switch">
                            <label id=" mo_oauth_switch">
                                <input class="form-check-input" type="checkbox" disabled/>
                            </label>
                            </div>
                        </div>
                    </div><br>
                    <div class="row">
                        <div class="col-sm-4">
                            <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_ACCESS');?></strong>
                        </div>
                        <div class="col-sm-8">
                            <input class="mo_security_textfield admin_log_url form-control" required type="text" name="access_lgn_urlky" placeholder="<?php echo Text::_('COM_MINIORANGE_OAUTH_ENTER_LOGIN_KEY');?>" disabled="disabled" value="" />
                        </div>
                    </div><br>
                    <div class="row">
                        <div class="col-sm-4">
                            <strong> <?php echo Text::_('COM_MINIORANGE_OAUTH_CURR_LOGIN');?></strong>
                        </div>
                        <div class="col-sm-8">
                            <?php echo Uri::base(); ?>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-4">
                            <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_ALU');?></strong>
                        </div>
                        <div class="col-sm-8">
                            <?php echo Uri::base().'?{accessKey}'; ?>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-4">
                            <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_NOTE_ADMIN_FAIL');?></strong>
                        </div>
                        <div class="col-sm-8">
                            <select class="mo_security_dropdown redirect_after_failure mo-form-control mo-form-control-select" id="failure_response" name="after_adm_failure_response" disabled="disabled">
                                <option value="redirect_homepage" ><?php echo Text::_('COM_MINIORANGE_OAUTH_NOTE_HOMEPAGE');?></option>
                                <option value="404_custom_message" ><?php echo Text::_('COM_MINIORANGE_OAUTH_NOTE_404');?></option>
                                <option value="custom_redirect_url" ><?php echo Text::_('COM_MINIORANGE_OAUTH_NOTE_REDIRECT');?></option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3 mo_oauth_display-none" id="custom_fail_dest">
                        <div class="col-sm-4">
                            <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_NOTE_ADMIN_REDIRECT_FAIL');?></strong>
                        </div>
                        <div class="col-sm-8">
                            <input class="form-control mo_security_textfield col-sm-3" type="text" disabled="disabled" name="custom_failure_destination" disabled="disabled" value=""/>
                        </div>
                    </div>
                    <div class="row mt-3 mo_oauth_display-none" id="custom_message">
                        <div class="col-sm-4">
                            <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_CUSTOM_MSG');?></strong>
                        </div>
                        <div class="col-sm-8">
                            <textarea class="form-control mo_security_textfield col-sm-3" disabled="disabled" name="custom_message_after_fail"></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="row mt-4">
                        <div class="col-sm-12 mt-3 text-right">
                            <input type="submit" disabled name="send_query" value=' <?php echo Text::_('COM_MINIORANGE_OAUTH_SAVE_GROUP_MAPPING');?>' class="btn mo_oauth_all_btn p-2 px-4 mb-3 mo_oauth_cursor"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row m-1 my-3 mo_oauth_display-none" id="mo_slo_setting">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-12 mt-1">
                            <h5 class="element">
                                <?php echo Text::_('COM_MINIORANGE_OAUTH_SINGLE_LOGOUT_SET');?><sup><img class="crown_img_small ml-2" src="<?php echo Uri::base();?>/components/com_miniorange_oauth/assets/images/crown.webp"></a></sup>
                                <a href="https://developers.miniorange.com/docs/oauth-joomla/advanced-setting" target="_blank" class="mo_handbook" ><sup><i class="fa-regular fa-circle-question" title="What is Single Logout Feature?"></i></sup></a>
                            </h5>
                        </div>
                    </div>
                </div><br><br>
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-5 col-lg-4">
                            <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_ENABLE_SINGLE_LOGOUT');?></strong>:
                        </div>
                        <div class="col-sm-7 col-lg-8">
                        <div class="form-check form-switch">
                            <label id=" mo_oauth_switch">
                                <input class="form-check-input" type="checkbox" disabled/>
                            </label>
                            </div>
                        </div>
                    </div><br>

                    <div class="row">
                        <div class="col-sm-5 col-lg-4">
                            <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_FRONTCHANNEL_LOGOUT');?></strong>
                        </div>
                        <div class="col-sm-7 col-lg-8">
                            <input class="mo_security_textfield form-control" required type="text" placeholder="<?php echo Text::_('COM_MINIORANGE_OAUTH_KEY');?>" disabled="disabled" value="" />
                        </div>
                    </div><br>
                    <div class="row">
                        <div class="col-sm-5 col-lg-4">
                            <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_BACKCHANNEL_LOGOUT');?></strong>
                        </div>
                        <div class="col-sm-7 col-lg-8">
                            <input class="mo_security_textfield form-control " required type="text" placeholder="<?php echo Text::_('COM_MINIORANGE_OAUTH_KEY');?>" disabled="disabled" value="" />
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="row mt-4">
                        <div class="col-sm-12 mt-3 text-right">
                            <input type="submit" name="send_query" value='<?php echo Text::_('COM_MINIORANGE_OAUTH_SAVE_SINGLE_LOGOUT');?>' disabled class="btn mo_oauth_all_btn p-2 px-4 mb-3 mo_oauth_cursor"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row m-1 my-3 mo_oauth_display-none" id="mo_oauth_token">
                <div class="col-sm-12">
                    <div class="row my-3">
                        <div class="col-sm-12 mt-3">
                            <h5 class="element">
                                <?php echo Text::_('COM_MINIORANGE_OAUTH_ACC_TOKEN_STORAGE');?> <sup><img class="crown_img_small ml-2" src="<?php echo Uri::base();?>/components/com_miniorange_oauth/assets/images/crown.webp"></a></sup>
                                <a href="https://developers.miniorange.com/docs/oauth-joomla/advanced-setting" target="_blank" class="mo_handbook" ><sup><i class="fa-regular fa-circle-question" title="How to get Access Token?"></i></sup></a>
                            </h5>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-12 mt-3">
                            <p><?php echo Text::_('COM_MINIORANGE_OAUTH_PLEASE_TXT');?></p>
                            <hr class="bg-dark">
                        </div>
                    </div>
                </div>
                
                <div class="col-sm-12 mt-2">
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="form-check form-switch">
                            <label id=" mo_oauth_switch">
                                <input disabled type="checkbox" class="form-check-input" name=" mo_oauth_custom_checkbox" id=" mo_oauth_check">
                            </label><?php echo Text::_('COM_MINIORANGE_OAUTH_COOKIE');?>
                            </div>
                            <br>
                        </div>
                        <div class="col-sm-7">
                            <input disabled type="text" class="form-control" placeholder="<?php echo Text::_('COM_MINIORANGE_OAUTH_COOKIE_NAME');?>">
                            <br>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 mt-2">
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="form-check form-switch">
                            <label id=" mo_oauth_switch">
                                <input disabled type="checkbox" class="form-check-input" name=" mo_oauth_custom_checkbox" id=" mo_oauth_check">
                            </label><?php echo Text::_('COM_MINIORANGE_OAUTH_HTTP');?>
                            </div>
                            <br>
                        </div>
                        <div class="col-sm-7">
                            <input disabled type="text" class="form-control" placeholder="<?php echo Text::_('COM_MINIORANGE_OAUTH_COOKIE_NAME_1');?>">
                            <br>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 mt-2">
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="form-check form-switch">
                            <label class=" mo_oauth_switch" id=" mo_oauth_switch">
                                <input disabled type="checkbox" class="form-check-input" name=" mo_oauth_custom_checkbox" id=" mo_oauth_check">
                            </label> <?php echo Text::_('COM_MINIORANGE_OAUTH_LOCAL_STORAGE');?>
                            </div>
                            <br>
                        </div>
                        <div class="col-sm-7">
                            <input disabled type="text" class="form-control" placeholder="<?php echo Text::_('COM_MINIORANGE_OAUTH_VARIABLE_NAME');?>">
                            <br>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="row mt-2">
                        <div class="col-sm-12 mt-3 text-right">
                            <input type="submit" disabled name="send_query" value='<?php echo Text::_('COM_MINIORANGE_OAUTH_SAVE_SETTINGS');?>' class="btn mo_oauth_all_btn p-2 px-4 mb-3 mo_oauth_cursor"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row m-1 my-3 mo_oauth_display-none" id="mo_custom_sso_button">
                <div class="col-sm-12">
                    <div class="row my-3">
                        <div class="col-sm-12 mt-3">
                            <h5 class="element">
                                <?php echo Text::_('COM_MINIORANGE_OAUTH_CUSTOMIZE_ICON');?> <sup><img class="crown_img_small ml-2" src="<?php echo Uri::base();?>/components/com_miniorange_oauth/assets/images/crown.webp"></a></sup>
                                <a href="https://developers.miniorange.com/docs/oauth-joomla/advanced-setting" target="_blank" class="mo_handbook" ><sup><i class="fa-regular fa-circle-question" title="How to create a SSO button and Customize it?"></i></sup></a>
                            </h5>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="row m-1 mt-3">
                        <div class="col-sm-12">
                            <p class="highlight"> <?php echo Text::_('COM_MINIORANGE_OAUTH_CUSTOMIZE_ICON_NOTE');?></p>
                        </div>
                        <div class="col-sm-12">
                            <div class="row my-2">
                                <div class="col-sm-4">
                                    <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_CUSTOMIZE_ICON_WIDTH');?></strong>
                                </div>
                                <div class="col-sm-8">
                                    <input class="form-control" disabled type="text" placeholder="e.g. 200px or 100%">
                                </div>
                            </div>
                            <div class="row my-2">
                                <div class="col-sm-4">
                                    <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_CUSTOMIZE_ICON_HEIGHT');?></strong>
                                </div>
                                <div class="col-sm-8">
                                    <input class="form-control" disabled type="text"  placeholder="e.g. 50px or auto">
                                </div>
                            </div>
                            <div class="row my-2">
                                <div class="col-sm-4">
                                    <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_CUSTOMIZE_ICON_MARGINS');?></strong>
                                </div>
                                <div class="col-sm-8">
                                    <input class="form-control" disabled type="text" placeholder="e.g. 2px 0px or auto">
                                </div>
                            </div>
                            <div class="row my-2">
                                <div class="col-sm-4">
                                    <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_CUSTOMIZE_ICON_CSS');?></strong>
                                </div>
                                <div class="col-sm-8">
                                    <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_CUSTOMIZE_ICON_CSS_EXAMPLE');?></strong>
                                    <textarea disabled type="text" class="form-control mo_oauth_textarea"  rows="6">.oauthloginbutton{background: #7272dc;height:40px;padding:8px;text-align:center;color:#fff;}</textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_CUSTOMIZE_ICON_BUTTON');?></strong>
                                </div>
                                <div class="col-sm-8">
                                    <input class="form-control mo_oauth_textarea" disabled type="text" placeholder ="<?php echo Text::_('COM_MINIORANGE_OAUTH_LOGOUT');?>"> <?php echo Text::_('COM_MINIORANGE_OAUTH_CUSTOMIZE_ICON_BUTTON_EXAMPLE');?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-sm-12 mt-3 text-right">
                            <input type="submit" disabled name="send_query" value='<?php echo Text::_('COM_MINIORANGE_OAUTH_SAVE_CUSTOMIZE_ICON_SETTINGS');?>' class="btn mo_oauth_all_btn p-2 px-4 mb-3 mo_oauth_cursor"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row m-1 my-3 mo_oauth_display-none" id="mo_user_analytics">
                <div class="col-sm-12">
                    <div class="row my-3">
                        <div class="col-sm-12 mt-3">
                            <h5 class="element">
                                <?php echo Text::_('COM_MINIORANGE_OAUTH_USER_ANALYTICS_AND_TRANSACTION_REPORTS');?> <sup><img class="crown_img_small ml-2" src="<?php echo Uri::base();?>/components/com_miniorange_oauth/assets/images/crown.webp"></a></sup>
                                <a href="https://developers.miniorange.com/docs/oauth-joomla/advanced-setting" target="_blank" class="mo_handbook" ><sup><i class="fa-regular fa-circle-question" title="What is User analytics?"></i></sup></a>
                            </h5>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="row mt-3">
                        <div class="col-sm-12">
                            <div class="row mt-2">
                                <div class="col-sm-12">
                                    <input disabled type="button" class="btn btn-danger mo_oauth_input m-1" id="cleartext" value="<?php echo Text::_('COM_MINIORANGE_OAUTH_USER_ANALYTICS_AND_TRANSACTION_REPORTS_CLEAR_REPORTS');?>" />
                                    <input disabled type="button" class="btn mo_oauth_all_btn mo_oauth_input m-1" id="refreshtext" value="<?php echo Text::_('COM_MINIORANGE_OAUTH_USER_ANALYTICS_AND_TRANSACTION_REPORTS_REFRESH');?>" />
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-12 table-responsive">
                                    <table class="table table-striped table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <th><?php echo Text::_('COM_MINIORANGE_OAUTH_USER_ANALYTICS_AND_TRANSACTION_REPORTS_USERNAME');?></th>
                                                <th><?php echo Text::_('COM_MINIORANGE_OAUTH_USER_ANALYTICS_AND_TRANSACTION_REPORTS_APPLICATION');?></th>
                                                <th><?php echo Text::_('COM_MINIORANGE_OAUTH_USER_ANALYTICS_AND_TRANSACTION_REPORTS_STATUS');?></th>
                                                <th><?php echo Text::_('COM_MINIORANGE_OAUTH_USER_ANALYTICS_AND_TRANSACTION_REPORTS_LOGIN_TIMESTAMP');?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr><td></td><td></td><td></td><td></td></tr>
                                            <tr><td></td><td></td><td></td><td></td></tr>
                                            <tr><td></td><td></td><td></td><td></td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}

function support()
{
    global $license_tab_link;
    ?>
    <div class="row m-0 p-1" >
        <div class="col-sm-2 m-0 p-0 mo_oauth_border_right">
            <div class="row m-0 p-0">
                <div class="col-sm-12 m-0 p-0">
                    <div onclick = "changeSubMenu(this , '#mo_general_support')" class="mo_sub_menu mo_sub_menu_active">
                        <span><?php echo Text::_('COM_MINIORANGE_OAUTH_GENERAL_QUERY');?></span>
                    </div>

                </div>
            </div>
            <div class="row m-0 p-0">
                <div class="col-sm-12 m-0 p-0">
                    <div onclick = "changeSubMenu(this,'#mo_request_demo')" class="mo_sub_menu">
                        <span><?php echo Text::_('COM_MINIORANGE_OAUTH_REQUEST_DEMO_TRIAL');?></span>
                    </div>
                </div>
            </div>
            <div class="row m-0 p-0">
                <div class="col-sm-12 m-0 p-0">
                    <div onclick = "changeSubMenu(this,'#mo_screen_share')" class="mo_sub_menu">
                        <span><?php echo Text::_('COM_MINIORANGE_OAUTH_SETUP_SCREEN');?></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-10">

            <div class="row m-1 my-3" id="mo_general_support">
                <div class="col-sm-12 mt-2" id="mo_oauth_attributemapping">
                    <div class="row mt-2">
                        <div class="col-sm-12">
                            <h5 class="element">
                                <?php echo Text::_('COM_MINIORANGE_OAUTH_SUPPORT');?>
                            </h5>
                            <br>
                        </div>
                        <br><br>
                        <div class="col-sm-12 mt-2">
                            <div class="row m-2">
                                <?php
                                    
                                    $current_user = Factory::getUser();
                                    $result = MoOAuthUtility::getCustomerDetails();
                                    $admin_email = empty(trim($result['email']))?$current_user->email:$result['email'];
                                    $user_email= new MoOauthCustomer();
                                    $result=$user_email->getAccountDetails();
                                    if($result['contact_admin_email']!=NULL)
                                    {
                                        $admin_email =$result['contact_admin_email'];
                                    }
                                    $admin_phone = $result['admin_phone'];
                                    
                                ?>
                                <form name="f" class="col-sm-12" method="post" action="<?php echo Route::_('index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.contactUs'); ?>">
                                    <div class="col-sm-12">
                                        <p class="mo_oauth_p"><?php echo Text::_('COM_MINIORANGE_OAUTH_CONTACT_US_DETAILS');?></p>
                                        <br>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="row mt-2">
                                            <div class="col-sm-3 offset-1">
                                                <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_EMAIL');?>:<span class="mo_oauth_highlight">*</span></strong>
                                            </div>
                                            <div class="col-lg-6 col-sm-8">
                                                <input type="email" class="form-control oauth-table mo_oauth_textbox" name="query_email" value="<?php echo $admin_email?>" placeholder="<?php echo Text::_('COM_MINIORANGE_OAUTH_EMAIL_PLACEHOLDER');?>" required />
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-sm-3 offset-1"> <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_MOBILE_NO');?> :</strong></div>
                                            <div class="col-lg-6 col-sm-8">
                                                <input type="number" class="form-control oauth-table mo_oauth_textbox" name="query_phone" value="<?php echo $admin_phone ?>" placeholder="<?php echo Text::_('COM_MINIORANGE_OAUTH_MOBILE_NO_PLACEHOLDER');?>"/>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-sm-3 offset-1"><strong><?php echo Text::_('COM_MINIORANGE_OAUTH_QUERY');?>:</strong><span class="mo_oauth_highlight">*</span></div>
                                            <div class="col-lg-6 col-sm-8">
                                                <textarea class="px-2 form-control mo_oauth_textbox col-sm-12" name="query" rows="4" placeholder="<?php echo Text::_('COM_MINIORANGE_OAUTH_QUERY_PLACEHOLDER');?>" required></textarea>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-sm-3 offset-1"></div>
                                            <div class="col-sm-6">
                                                <div class="form-check form-switch">
                                                    <input id="mo_oauth_query_withconfig"  type="checkbox" class="form-check-input" name="mo_oauth_query_withconfig" value="1" > <?php echo Text::_('COM_MINIORANGE_OAUTH_SEND_CONFIGURATION');?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row my-4 text-center">
                                            <div class="col-sm-12">
                                                <input type="submit" name="send_query"  value="<?php echo Text::_('COM_MINIORANGE_OAUTH_SUBMIT_QUERY');?>" class="btn mo_oauth_all_btn px-3"/>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <br/>              
                            </div>
                        </div>
                    </div>  
                </div>
            </div>
            <div class="row m-1 my-3 mo_oauth_display-none" id="mo_request_demo">
                <div class="col-sm-12 mt-3">
                    <h5 class="element">
                        <?php echo Text::_('COM_MINIORANGE_OAUTH_DEMO_TITLE');?>
                    </h5>
                    <hr>
                </div>
                <div class="col-sm-12 my-4">
                    <div class="row m-2">
                        <div class="col-sm-12">
                            <div class="mo_oauth_p">
                                <?php echo Text::_('COM_MINIORANGE_OAUTH_REQUEST_DEMO_NOTE');?>
                            </div><br>
                        </div>
                        <div class="col-sm-12">
                            <form id="demo_request" name="demo_request" method="post" action="<?php echo Route::_('index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.requestForDemoPlan'); ?>">
                                <div class="row mt-2">
                                    <div class="col-sm-3 offset-1">
                                        <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_REQUEST_DEMO_EMAIL');?><span class="mo_oauth_highlight">*</span></strong>
                                    </div>
                                    <div class="col-lg-6 col-sm-8">
                                        <input required class="form-control mo_oauth_textbox" onblur="validateEmail(this)" type="email" name="email" placeholder="person@example.com" value="<?php echo $admin_email ?>"/>
                                        <p class="mo_oauth_display-none mo_oauth_red_color" id="email_error">Invalid Email</p>
                                    </div>
                                </div>

                                <div class="row mt-2 my-1">
                                    <div class="col-sm-3 offset-1">
                                        <p> <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_REQUEST_FOR');?><span class="mo_oauth_highlight">*</span></strong></p>
                                    </div>
                                    <div class="col-sm-4">
                                        <label><input type="radio" name="demo" class="mx-2" value="Trial of 7 days" CHECKED><?php echo Text::_('COM_MINIORANGE_OAUTH_TRIAL');?></label>
                                    </div>
                                    <div class="col-sm-4">
                                        <label><input type="radio" name="demo" class="mx-2"  value="Demo" ><?php echo Text::_('COM_MINIORANGE_OAUTH_DEMO');?></label>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-sm-3 offset-1">
                                        <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_REQUEST_TRIAL_DEMO');?>:<span class="mo_oauth_highlight">*</span></strong>
                                    </div>
                                    <div class="col-lg-6 col-sm-8">
                                        <select required class="mo-form-control mo-form-control-select mo_oauth_textbox" name="plan" id="rfd_id">
                                            <option value=""><?php echo Text::_('COM_MINIORANGE_OAUTH_DEMO_SELECT');?></option>
                                            <option value="Joomla OAuth Client Standard Plugin"><?php echo Text::_('COM_MINIORANGE_OAUTH_CLIENT_STANDARD_PLUGIN');?></option>
                                            <option value="Joomla OAuth Client Premium Plugin"><?php echo Text::_('COM_MINIORANGE_OAUTH_CLIENT_PREMIUM_PLUGIN');?></option>
                                            <option value="Joomla OAuth Client Enterprise Plugin"><?php echo Text::_('COM_MINIORANGE_OAUTH_CLIENT_ENTERPRISE_PLUGIN');?></option>
                                            <option value="Not Sure"><?php echo Text::_('COM_MINIORANGE_OAUTH_NOT_SURE');?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-sm-3 offset-1">
                                        <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_DEMO_DESCRIPTION');?>:<span class="mo_oauth_highlight">*</span></strong>
                                    </div>
                                    <div class="col-lg-6 col-sm-8">
                                        <textarea class="px-2 form-control mo_oauth_textbox col-sm-12" required type="text" name="description" rows="4" placeholder="<?php echo Text::_('COM_MINIORANGE_OAUTH_DEMO_DESCRIPTION_PLACEHOLDER');?>" value=""></textarea>
                                    </div>
                                </div>
                                <div class="row my-4 text-center">
                                    <div class="col-sm-12">
                                        <input type="submit" name="submit" value="<?php echo Text::_('COM_MINIORANGE_OAUTH_SUBMIT_DEMO_REQUEST');?>" class="btn mo_oauth_all_btn px-3"/>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="row m-1 my-3 mo_oauth_display-none" id="mo_screen_share">
                <div class="col-sm-12 mt-3">
                    <h5 class="element">
                        <?php echo Text::_('COM_MINIORANGE_OAUTH_SETUP_MEETING');?>
                    </h5>
                    <hr>
                </div>
                <div class="col-sm-12 my-3">
                    <?php
                        $arrContextOptions=array(
                            "ssl"=>array(
                                "verify_peer"=>false,
                                "verify_peer_name"=>false,
                            ),
                        );  
                        
                        $strJsonFileContents = file_get_contents(Uri::root()."/administrator/components/com_miniorange_oauth/assets/json/timezones.json",false,stream_context_create($arrContextOptions));
                        $timezoneJsonArray = json_decode($strJsonFileContents, true);

                    ?>
                    <form name="f" method="post" action="<?php echo Route::_('index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.callContactUs'); ?>">
                        <div class="row">
                            <div class="col-sm-12 px-5">
                                <p  class="mo_oauth_p"><?php echo Text::_('COM_MINIORANGE_OAUTH_SETUP_CALL_NOTE');?></p>
                            </div>
                            <div class="col-sm-12">
                                <div class="row mt-2">
                                    <div class="col-sm-3 offset-1">
                                        <strong><span class="mo_oauth_highlight">*</span><?php echo Text::_('COM_MINIORANGE_OAUTH_SETUP_CALL_EMAIL');?></strong>
                                    </div>
                                    <div class="col-sm-6">
                                        <input class="form-control px-3 mo_oauth_textbox"  type="email" placeholder="user@example.com"  name="mo_oauth_setup_call_email" value="<?php echo $admin_email; ?>"  required>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-sm-3 offset-1">
                                        <strong><span class="mo_oauth_highlight">*</span><?php echo Text::_('COM_MINIORANGE_OAUTH_SETUP_CALL_ISSUE');?></strong>
                                    </div>
                                    <div class="col-sm-6">
                                        <select id="issue_dropdown"  class="mo_callsetup_table_textbox mo-form-control mo-form-control-select mo_oauth_textbox" name="mo_oauth_setup_call_issue" required>
                                            <option disabled selected><?php echo Text::_('COM_MINIORANGE_OAUTH_SETUP_CALL_SELECT_ISSUE');?></option>
                                            <option id="sso_setup_issue"><?php echo Text::_('COM_MINIORANGE_OAUTH_SETUP_CALL_SSO_SETUP_ISSUE');?></option>
                                            <option><?php echo Text::_('COM_MINIORANGE_OAUTH_SETUP_CALL_CUSTOM_REQUIREMENT');?></option>
                                            <option id="other_issue"><?php echo Text::_('COM_MINIORANGE_OAUTH_SETUP_OTHER');?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-sm-3 offset-1">
                                        <strong><span class="mo_oauth_highlight">*</span><?php echo Text::_('COM_MINIORANGE_OAUTH_SETUP_CALL_DATE');?></td></strong>
                                    </div>
                                    <div class="col-sm-6">
                                        <input class="form-control mo_callsetup_table_textbox mo_oauth_textbox" name="mo_oauth_setup_call_date" type="date" id="calldate" required>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-sm-3 offset-1">
                                        <strong><span class="mo_oauth_highlight">*</span><?php echo Text::_('COM_MINIORANGE_OAUTH_SETUP_CALL_TIME');?></td></strong>
                                    </div>
                                    <div class="col-sm-6">
                                        <select class="mo_callsetup_table_textbox px-2 mo_oauth_textbox col-sm-12 mo-form-control mo-form-control-select" name="mo_oauth_setup_call_timezone" id="timezone" required>
                                        <?php
                                            foreach($timezoneJsonArray as $data)
                                            {
                                                echo "<option>".$data."</option>";
                                            }
                                        ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-sm-3 offset-1">
                                        <strong><span id="required_mark" class="mo_oauth_display-none mo_oauth_red_color">*</span><?php echo Text::_('COM_MINIORANGE_OAUTH_SETUP_CALL_DESCRIPTION');?></strong>
                                    </div>
                                    <div class="col-sm-6">
                                        <textarea id="issue_description" rows="4" class="mo_callsetup_table_textbox px-2 mo_oauth_textbox col-sm-12" name="mo_oauth_setup_call_desc" minlength="15" placeholder="<?php echo Text::_('COM_MINIORANGE_OAUTH_SETUP_CALL_DESCRIPTION_PLACEHOLDER');?>" rows="4"></textarea>
                                    </div>
                                </div>
                                <div class="row my-4 text-center">
                                    <div class="col-sm-12">
                                        <input type="submit" name="send_query"  value="<?php echo Text::_('COM_MINIORANGE_OAUTH_SETUP_CALL_SUBMIT_QUERY');?>" class="btn mo_oauth_all_btn px-3">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Old Code -->
    <div class="row m-1 mt-3 d-none ">
        <div class="col-sm-12 mt-4">
            <h3>
                <?php echo Text::_('COM_MINIORANGE_OAUTH_SUPPORT_FEATURE');?>
                <span class="float-right" id="mini-icons">
                    <a href="https://faq.miniorange.com/kb/oauth-openid-connect/" target="_blank" class="btn btn-success py-1"><?php echo Text::_('COM_MINIORANGE_OAUTH_FAQS');?></a>
                    <a href="https://plugins.miniorange.com/joomla-oauth-client" target="_blank" title="Website" class="mo_oauth_support_icon"><em class="fa fa-globe mo_oauth_icon_color"></em></a>
                    <a href="https://www.miniorange.com/contact" target="_blank" title="Contact-Us" class="mo_oauth_support_icon"><em class="fa fa-comment mo_oauth_icon_color"></em></a>
                    <a href="https://extensions.joomla.org/extension/miniorange-oauth-client/" target="_blank" title="Rate us" class="mo_oauth_support_icon"><em class="fa fa-star mo_oauth_icon_color"></em></a>
                </span>
            </h3>
            <hr>
        </div>
        <div class="col-sm-12 mt-2">
            <details open>
                <summary><?php echo Text::_('COM_MINIORANGE_OAUTH_SUPPORT');?></summary>
                    <hr>
                    <div class="row m-2">
                        <?php
                            
                            $current_user = Factory::getUser();
                            $result = MoOAuthUtility::getCustomerDetails();
                            $admin_email = empty(trim($result['email']))?$current_user->email:$result['email'];
                            $user_email= new MoOauthCustomer();
                            $result=$user_email->getAccountDetails();
                            if($result['contact_admin_email']!=NULL)
                            {
                                $admin_email =$result['contact_admin_email'];
                            }
                            $admin_phone = $result['admin_phone'];
                            
                        ?>
                        <form name="f" method="post" action="<?php echo Route::_('index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.contactUs'); ?>">
                            <div class="col-sm-12">
                                <p class="mo_oauth_p"><?php echo Text::_('COM_MINIORANGE_OAUTH_CONTACT_US_DETAILS');?></p>
                                <br>
                            </div>
                            <div class="col-sm-12">
                                <div class="row mt-2">
                                    <div class="col-sm-3 offset-1">
                                        <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_EMAIL');?>:<span class="mo_oauth_highlight">*</span></strong>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="email" class="form-control oauth-table mo_oauth_textbox" name="query_email" value="<?php echo $admin_email?>" placeholder="<?php echo Text::_('COM_MINIORANGE_OAUTH_EMAIL_PLACEHOLDER');?>" required />
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-sm-3 offset-1"> <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_MOBILE_NO');?> :</strong></div>
                                    <div class="col-sm-6">
                                        <input type="number" class="form-control oauth-table mo_oauth_textbox" name="query_phone" value="<?php echo $admin_phone ?>" placeholder="<?php echo Text::_('COM_MINIORANGE_OAUTH_MOBILE_NO_PLACEHOLDER');?>"/>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-sm-3 offset-1"><strong><?php echo Text::_('COM_MINIORANGE_OAUTH_QUERY');?>:</strong><span class="mo_oauth_highlight">*</span></div>
                                    <div class="col-sm-6">
                                        <textarea class="px-2 mo_oauth_textbox col-sm-6" name="query" rows="4" placeholder="<?php echo Text::_('COM_MINIORANGE_OAUTH_QUERY_PLACEHOLDER');?>" required></textarea>
                                    </div>
                                </div>
                                <div class="row mt-2">
									<div class="col-sm-3 offset-1"></div>
									<div class="col-sm-6">
                                        <div class="form-check form-switch">
										<input id="mo_oauth_query_withconfig"  type="checkbox" class="form-check-input" name="mo_oauth_query_withconfig" value="1" > <?php echo Text::_('COM_MINIORANGE_OAUTH_SEND_CONFIGURATION');?>
                                        </div>
									</div>
								</div>
                                <div class="row my-4 text-center">
                                    <div class="col-sm-12">
                                        <input type="submit" name="send_query"  value="<?php echo Text::_('COM_MINIORANGE_OAUTH_SUBMIT_QUERY');?>" class="btn btn-success"/>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <br/>              
                    </div>
            </details>
            <details>
                <summary><?php echo Text::_('COM_MINIORANGE_OAUTH_REQUEST_DEMO');?></summary>
                <hr>
                <div class="row m-2">
                    <div class="col-sm-12">
                        <div class="mo_oauth_p">
                            <?php echo Text::_('COM_MINIORANGE_OAUTH_REQUEST_DEMO_NOTE');?>
                        </div><br>
                    </div>
                    <div class="col-sm-12">
                        <form id="demo_request" name="demo_request" method="post" action="<?php echo Route::_('index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.requestForDemoPlan'); ?>">
                            <div class="row mt-2">
                                <div class="col-sm-3 offset-1">
                                    <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_REQUEST_DEMO_EMAIL');?><span class="mo_oauth_highlight">*</span></strong>
                                </div>
                                <div class="col-sm-6">
                                    <input required class="form-control mo_oauth_textbox" onblur="validateEmail(this)" type="email" name="email" placeholder="person@example.com" value="<?php echo $admin_email ?>"/>
                                    <p class="mo_oauth_display-none mo_oauth_red_color" id="email_error">Invalid Email</p>
                                </div>
                            </div>

                            <div class="row mt-2 my-1">
                                <div class="col-sm-3 offset-1">
                                    <p> <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_REQUEST_FOR');?><span class="mo_oauth_highlight">*</span></strong></p>
                                </div>
                                <div class="col-sm-3">
                                    <label><input type="radio" name="demo" class="mx-2" value="Trial of 7 days" CHECKED><?php echo Text::_('COM_MINIORANGE_OAUTH_TRIAL');?></label>
                                </div>
                                <div class="col-sm-3">
                                    <label><input type="radio" name="demo" class="mx-2"  value="Demo" ><?php echo Text::_('COM_MINIORANGE_OAUTH_DEMO');?></label>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-sm-3 offset-1">
                                    <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_REQUEST_TRIAL_DEMO');?>:<span class="mo_oauth_highlight">*</span></strong>
                                </div>
                                <div class="col-sm-6">
                                    <select required class="mo-form-control mo-form-control-select mo_oauth_textbox" name="plan" id="rfd_id">
                                        <option value=""><?php echo Text::_('COM_MINIORANGE_OAUTH_DEMO_SELECT');?></option>
                                        <option value="Joomla OAuth Client Standard Plugin"><?php echo Text::_('COM_MINIORANGE_OAUTH_CLIENT_STANDARD_PLUGIN');?></option>
                                        <option value="Joomla OAuth Client Premium Plugin"><?php echo Text::_('COM_MINIORANGE_OAUTH_CLIENT_PREMIUM_PLUGIN');?></option>
                                        <option value="Joomla OAuth Client Enterprise Plugin"><?php echo Text::_('COM_MINIORANGE_OAUTH_CLIENT_ENTERPRISE_PLUGIN');?></option>
                                        <option value="Not Sure"><?php echo Text::_('COM_MINIORANGE_OAUTH_NOT_SURE');?></option>
                                    </select>
                                </div>

                            </div>
                            <div class="row mt-2">
                                <div class="col-sm-3 offset-1">
                                    <strong><?php echo Text::_('COM_MINIORANGE_OAUTH_DEMO_DESCRIPTION');?>:<span class="mo_oauth_highlight">*</span></strong>
                                </div>
                                <div class="col-sm-6">
                                    <textarea class="px-2 mo_oauth_textbox col-sm-6" required type="text" name="description" rows="4" placeholder="<?php echo Text::_('COM_MINIORANGE_OAUTH_DEMO_DESCRIPTION_PLACEHOLDER');?>" value=""></textarea>
                                </div>
                            </div>
                            <div class="row my-4 text-center">
                                <div class="col-sm-12">
                                    <input type="submit" name="submit" value="<?php echo Text::_('COM_MINIORANGE_OAUTH_SUBMIT_DEMO_REQUEST');?>" class="btn btn-success"/>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                </div>
            </details>
            <details>
                <summary><?php echo Text::_('COM_MINIORANGE_OAUTH_SETUP_CALL');?></summary>
                <hr>
                <?php
                    $arrContextOptions=array(
                        "ssl"=>array(
                            "verify_peer"=>false,
                            "verify_peer_name"=>false,
                        ),
                    );  
                    
                    $strJsonFileContents = file_get_contents(Uri::root()."/administrator/components/com_miniorange_oauth/assets/json/timezones.json",false,stream_context_create($arrContextOptions));
                    $timezoneJsonArray = json_decode($strJsonFileContents, true);

                ?>
                <form name="f" method="post" action="<?php echo Route::_('index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.callContactUs'); ?>">
                    <div class="row">
                        <div class="col-sm-12 px-5">
                            <p class="mo_oauth_p"><?php echo Text::_('COM_MINIORANGE_OAUTH_SETUP_CALL_NOTE');?></p>
                        </div>
                        <div class="col-sm-12">
                            <div class="row mt-2">
                                <div class="col-sm-3 offset-1">
                                    <strong><span class="mo_oauth_highlight">*</span><?php echo Text::_('COM_MINIORANGE_OAUTH_SETUP_CALL_EMAIL');?></strong>
                                </div>
                                <div class="col-sm-6">
                                    <input class="form-control px-3 mo_oauth_textbox"  type="email" placeholder="user@example.com"  name="mo_oauth_setup_call_email" value="<?php echo $admin_email; ?>"  required>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-sm-3 offset-1">
                                    <strong><span class="mo_oauth_highlight">*</span><?php echo Text::_('COM_MINIORANGE_OAUTH_SETUP_CALL_ISSUE');?></strong>
                                </div>
                                <div class="col-sm-6">
                                    <select id="issue_dropdown"  class="mo_callsetup_table_textbox mo-form-control mo-form-control-select mo_oauth_textbox" name="mo_oauth_setup_call_issue" required>
                                        <option disabled selected><?php echo Text::_('COM_MINIORANGE_OAUTH_SETUP_CALL_SELECT_ISSUE');?></option>
                                        <option id="sso_setup_issue"><?php echo Text::_('COM_MINIORANGE_OAUTH_SETUP_CALL_SSO_SETUP_ISSUE');?></option>
                                        <option><?php echo Text::_('COM_MINIORANGE_OAUTH_SETUP_CALL_CUSTOM_REQUIREMENT');?></option>
                                        <option id="other_issue"><?php echo Text::_('COM_MINIORANGE_OAUTH_SETUP_OTHER');?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-sm-3 offset-1">
                                    <strong><span class="mo_oauth_highlight">*</span><?php echo Text::_('COM_MINIORANGE_OAUTH_SETUP_CALL_DATE');?></td></strong>
                                </div>
                                <div class="col-sm-6">
                                    <input class="form-control mo_callsetup_table_textbox mo_oauth_textbox" name="mo_oauth_setup_call_date" type="date" id="calldate" required>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-sm-3 offset-1">
                                    <strong><span class="mo_oauth_highlight">*</span><?php echo Text::_('COM_MINIORANGE_OAUTH_SETUP_CALL_TIME');?></td></strong>
                                </div>
                                <div class="col-sm-6">
                                    <select class="mo_callsetup_table_textbox px-2 mo_oauth_textbox col-sm-6" name="mo_oauth_setup_call_timezone" id="timezone" required>
                                    <?php
                                        foreach($timezoneJsonArray as $data)
                                        {
                                            echo "<option>".$data."</option>";
                                        }
                                    ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-sm-3 offset-1">
                                    <strong><span id="required_mark" class="mo_oauth_display-none mo_oauth_red_color">*</span><?php echo Text::_('COM_MINIORANGE_OAUTH_SETUP_CALL_DESCRIPTION');?></strong>
                                </div>
                                <div class="col-sm-6">
                                    <textarea id="issue_description" rows="4" class="mo_callsetup_table_textbox px-2 mo_oauth_textbox col-sm-6" name="mo_oauth_setup_call_desc" minlength="15" placeholder="<?php echo Text::_('COM_MINIORANGE_OAUTH_SETUP_CALL_DESCRIPTION_PLACEHOLDER');?>" rows="4"></textarea>
                                </div>
                            </div>
                            <div class="row my-4 text-center">
                                <div class="col-sm-12">
                                    <input type="submit" name="send_query"  value="<?php echo Text::_('COM_MINIORANGE_OAUTH_SETUP_CALL_SUBMIT_QUERY');?>" class="btn btn-success">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                
            </details>
        </div>
    </div>
    <?php
}

function mo_oauth_licensing_plan()
{
    $db = Factory::getDbo();
    $query = $db->getQuery(true);
    $query->select('*');
    $query->from($db->quoteName('#__miniorange_oauth_customer'));
    $query->where($db->quoteName('id')." = 1");
    $db->setQuery($query);
    $useremail = $db->loadAssoc();
    global $license_tab_link;

    if(isset($useremail))
        $user_email =$useremail['email'];
    else
        $user_email="xyz";
	?>
    <div id="myModal" class="modal">
        <div class="modal-content text-center">
            <span class="close" onclick="upgradeClose()">&times;</span><br><br><br>
            <p class="mo_oauth_license_text"><?php echo Text::_('COM_MINIORANGE_OAUTH_LICENSE_TEXT');?> </p>
            <br><br>
            <a href="<?php echo Uri::base()?>index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=account" class="btn mo_oauth_all_btn"><?php echo Text::_('COM_MINIORANGE_OAUTH_REGISTER_LOGIN');?></a>
        </div>
    </div>  
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-12 my-4">
                    <div class="mo_oauth_pricing_wrapper">
                        <div class="mo_oauth_pricing_table">
                            <div class="mo_oauth_pricing_table_content">
                                <div class="mo_oauth_pricing_table_price text-center">
                                    <h4 class="my-3 py-1 text-light"><strong><?php echo Text::_('COM_MINIORANGE_OAUTH_FREE_PLAN'); ?></strong></h4>
                                </div>
                                <div class="mo_oauth_pricing_table_head my-4">
                                    <p><br></p>
                                </div> 
                                <div class="mo_oauth_pricing_table_price_value text-center">
                                    <h4 class="my-4 text-light"> $0 <br></h4>
                                </div>
                                <div class="mo_oauth_sign-up mt-5 align-center">
                                    <a href="https://www.miniorange.com/contact" target="_blank" class="btn bordered radius"><?php echo Text::_('COM_MINIORANGE_OAUTH_BUY_NOW'); ?></a>
                                </div>
                                <div class="mt-2">
                                    <ul class="m-0 p-0">
                                        <?php echo Text::_('COM_MINIORANGE_FEATURE_COMPARISION_BASIC_PLAN_FEATURES');?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="mo_oauth_pricing_table">
                            <div class="mo_oauth_pricing_table_content">
                                <div class="mo_oauth_pricing_table_price text-center">
                                    <h4 class="my-3 py-1 text-light"><strong><?php echo Text::_('COM_MINIORANGE_FEATURE_COMPARISION_STANDARD_PLAN');?></strong></h4>
                                </div>    
                                <div class="mo_oauth_pricing_table_head my-4">
                                    <p ><br></p>
                                </div> 
                                <div class="mo_oauth_pricing_table_price_value text-center">
                                    <h4 class="my-4 text-light"><?php echo Text::_('COM_MINIORANGE_STANDARD');?> <br><small><small><?php echo Text::_('COM_MINIORANGE_OAUTH_PER_YEAR');?></small></small></h4>
                                </div>
                                <div class="mo_oauth_sign-up mt-5">
                                    <a href="https://portal.miniorange.com/initializepayment?requestOrigin=joomla_oauth_client_standard_plan" target="_blank" class="btn bordered radius"> <?php echo Text::_('COM_MINIORANGE_OAUTH_BUY_NOW'); ?></a>
                                </div>
                                <div class="mt-2">
                                    <ul class="m-0 p-0 ">
                                        <li class='py-3 mo_oauth_height'><strong><?php echo Text::_('COM_MINIORANGE_OAUTH_ALL_FREE_FEATURE');?> </strong><strong>+</strong></li>
                                        <?php echo Text::_('COM_MINIORANGE_FEATURE_COMPARISION_STANDARD_PLAN_FEATURES');?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="mo_oauth_pricing_table">
                            <div class="mo_oauth_pricing_table_content">
                                <div class="mo_oauth_pricing_table_price text-center">
                                    <h4 class="my-3 py-1 text-light"><strong><?php echo Text::_('COM_MINIORANGE_FEATURE_COMPARISION_PREMIUM_PLAN');?></strong></h4>
                                </div>    
                                <div class="mo_oauth_pricing_table_head my-4">
                                    <p><br></p>
                                </div> 
                                <div class="mo_oauth_pricing_table_price_value text-center">
                                    <h4 class="my-4 text-light"><strong><?php echo Text::_('COM_MINIORANGE_FEATURE_COMPARISION_PREMIUM_COST');?></strong>  <br><small><small><small><?php echo Text::_('COM_MINIORANGE_OAUTH_PER_YEAR');?></small></small></h4>
                                </div>
                                <div class="col-sm-12">
                                    <div class="mo_oauth_sign-up mt-5">
                                        <a href="https://portal.miniorange.com/initializepayment?requestOrigin=joomla_oauth_client_premium_plan" target="_blank" class="btn bordered radius"><?php echo Text::_('COM_MINIORANGE_OAUTH_BUY_NOW'); ?></a>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <ul class="m-0 p-0">
                                        <li class='py-3 mo_oauth_height'><strong><?php echo Text::_('COM_MINIORANGE_OAUTH_ALL_STD_FEATURES');?></strong><strong>+</strong></li>
                                        <?php echo Text::_('COM_MINIORANGE_FEATURE_COMPARISION_PREMIUM_FEATURES');?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="mo_oauth_pricing_table">   
                            <div class="mo_oauth_pricing_table_content">
                                <div class="mo_oauth_pricing_table_price text-center">
                                    <h4 class="my-3 py-1 text-light"><strong><?php echo Text::_('COM_MINIORANGE_FEATURE_COMPARISION_ENTERPRISE_PLAN');?></strong></h4>
                                </div>    
                                <div class="mo_oauth_pricing_table_head my-4">
                                    <p><br></p>
                                </div> 
                                <div class="mo_oauth_pricing_table_price_value text-center">
                                    <h4 class="my-4 text-light"><strong><?php echo Text::_('COM_MINIORANGE_FEATURE_COMPARISION_ENTERPRISE_PLAN_COST');?></strong><br><small><small><small><?php echo Text::_('COM_MINIORANGE_OAUTH_PER_YEAR');?></small></small></h4>
                                </div>
                                <div class="col-sm-12">
                                    <div class="mo_oauth_sign-up mt-5">
                                        <a href="https://portal.miniorange.com/initializepayment?requestOrigin=joomla_oauth_client_enterprise_plan" target="_blank" class="btn bordered radius"><?php echo Text::_('COM_MINIORANGE_OAUTH_BUY_NOW'); ?></a>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <ul class="m-0 p-0">
                                        <li class="py-3 mo_oauth_height"><strong><?php echo Text::_('COM_MINIORANGE_OAUTH_ALL_PREMIUM_FEATURE');?></strong><strong>+</strong></li>
                                        <?php echo Text::_('COM_MINIORANGE_FEATURE_COMPARISION_ENTERPRISE_FEATURES');?>
                                    </ul>
                                </div>
                            </div>         
                        </div>         
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-11 mx-4">
                    <small>
                        <strong><?php echo Text::_('COM_MINIORANGE_FEATURE_COMPARISION_ENTERPRISE_PLAN_TEXT');?>** </strong>
                    </small>
                </div>
            </div>
        </div>
    </div>	
	<?php
}

function moImportAndExport()
{
    ?>
    <div class="row  mr-1  py-3 px-2 mo_tab_border" id="import_export_form">
        <div class="col-sm-12">
            <h3>
                <?php echo Text::_('COM_MINIORANGE_OAUTH_IE_CONFIG');?><sup><a href="https://developers.miniorange.com/docs/joomla/saml-sso/saml-import-export-configuration" target="_blank" class="mo_saml_know_more" title="Know more about this feature"><div class="fa fa-question-circle-o"></div></a></sup>
                <hr>
            </h3>
        </div>
        <div class="col-sm-12 mt-3">
            <div class="row">
                <div class="col-8">
                    <strong><?php echo Text::_('COM_MINIORANGE_FEATURE_COMPARISION_DOWNLOAD_CONFIG');?></strong>
                </div> 
                <div class="col-4">
                    <a href='index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.exportConfiguration' class="btn mo_oauth_all_btn float-right"><?php echo Text::_('COM_MINIORANGE_OAUTH_EXPORT_CONFIGURATION');?></a>
                </div>
            </div>
        </div> 
        <div class="col-sm-12 mt-3"><hr>
            <strong><?php echo Text::_('COM_MINIORANGE_FEATURE_COMPARISION_UPLOAD_CONFIGURATION');?></strong> <span><sup><img class="crown_img_small ml-2" src="<?php echo Uri::base();?>/components/com_miniorange_oauth/assets/images/crown.webp"></a></sup></span>
        </div>
        <div class="col-12 mt-3">
            <div class="row">
                <div class="col-8">
                    <input type="file" class="form-control-file d-inline" name="configuration_file" disabled="disabled">
                </div>
                <div class="col-4">
                    <input id="mo_sp_exp_importconfig" type="submit" disabled="disabled" name="submit" class="btn mo_oauth_all_btn mo_oauth_input" value="<?php echo Text::_('COM_MINIORANGE_OAUTH_IMPORT_CONFIGURATION');?>"/>
                </div>
            </div>
        </div>
    </div>
    <?php
}


function exportConfiguration(){
    ?>
<div class="container-fluid m-0">
    <div class="row mo_ldap_tab_theme">
        <div class="export-configuration">
            <h3 class="mo_export_heading pt-4"><?php echo Text::_('COM_MINIORANGE_EXPORT_CONFIGURATION'); ?></h3>
            <p>
                <?php echo Text::_('COM_MINIORANGE_EXPORT_CONFIGURATION_TEXT'); ?>
            </p>
            <form action="<?php echo Route::_('index.php?option=com_miniorange_oauth&task=accountsetup.exportConfig'); ?>" method="post">
                <button type="submit" class="btn mo_export_blue_buttons mo_export_white_color"><?php echo Text::_('COM_MINIORANGE_EXPORT_CONFIG'); ?></button>
            </form>
            <?php echo HTMLHelper::_('form.token'); ?>
            <hr>
            <h3 class="mo_export_heading pt-4"><?php echo Text::_('COM_MINIORANGE_IMPORT_CONFIGURATION'); ?></h3>
            <p>
                <?php echo Text::_('COM_MINIORANGE_IMPORT_CONFIGURATION_TEXT'); ?>
            </p>
            <input type="file" id="fileInput" name="file" accept=".json" style="display: none;" onchange="displayFileName()">
            <button type="button" class="btn mo_export_blue_buttons mo_export_white_color mb-4" onclick="document.getElementById('fileInput').click();" disabled>
                <i class="fas fa-upload p-1"></i>
            </button>
            <span class="file-name" id="fileName"></span>
            <button type="submit" class="btn mo_export_blue_buttons mo_export_white_color mb-4"><?php echo Text::_('COM_MINIORANGE_IMPORT_CONFIG'); ?></button>
        </div>
    </div>


</div>
    <?php
}


?>