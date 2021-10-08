<?php 

/* CORE APP config */
$_SSS['app']['base_dir'] = $_SERVER['DOCUMENT_ROOT'];

$_SSS['app']['tenant_folder'] = 'empresa1'; //folder name
$_SSS['app']['app_folder'] = 'app'; //folder name
$_SSS['app']['asset_folder'] = 'assets'; //folder name with a trailing slash only if there is an asset folder else leave blank

$_SSS['app']['system_path'] = $_SSS['app']['base_dir'].'/'.$_SSS['app']['app_folder'] .'/system/'; //will be sed by the CI index file
$_SSS['app']['application_path'] = $_SSS['app']['base_dir'].'/'.$_SSS['app']['app_folder'] .'/application/'; //will be used by the CI index file
$_SSS['app']['base_url'] = 'http://'.$_SERVER["SERVER_NAME"].':8080/'.$_SSS['app']['app_folder'];
$_SSS['app']['tenant_url'] = 'http://'.$_SERVER["SERVER_NAME"].':8080/'.$_SSS['app']['tenant_folder'];
///The above config variable are defined to create the basic paths and URLs required for the app to work, which are defined just below


/* Main Paths and urls */
if(! defined('APP_TENANTPATH')){
    define('APP_PATH', $_SSS['app']['base_dir'].'/'.$_SSS['app']['app_folder'].'/');//this will be used by ci_app config files to load this config file
    define('APP_TENANTPATH', $_SSS['app']['base_dir'].'/'.$_SSS['app']['tenant_folder'].'/');//this will be used by ci_app config files to load this config file
    define('APP_TENANTURL', $_SSS['app']['tenant_url'].'/');//this the path that will show on the browser and will be used for accessing the controllers and methods
    define('APP_ASSETURL', $_SSS['app']['base_url'].'/'.$_SSS['app']['asset_folder']);//this will be required to access tenant specific assets}
    define('APP_BASEURL', $_SSS['app']['base_url'].'/');//this will be required to access tenant specific assets}
}
/* DB config */
$_SSS['app']['db_host'] = 'local';
$_SSS['app']['db_user'] = 'root';
$_SSS['app']['db_pass'] = '';
$_SSS['app']['db_name'] = 'asto';
$_SSS['app']['db_driver'] = 'mysql';


/* CI specific params*/
$_SSS['ci']['db_cache'] = FALSE;

?>