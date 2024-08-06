<?php
/**
 * Blocksy functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Blocksy
 */

if (version_compare(PHP_VERSION, '5.7.0', '<')) {
	require get_template_directory() . '/inc/php-fallback.php';
	return;
}

require_once get_template_directory() . "/custom_function/createMenuAdmin.php";
require_once get_template_directory() . "/custom_function/createTableData.php";
require_once get_template_directory() . "/custom_function/showMenuManagementUserPage.php";
require_once get_template_directory() . "/custom_function/showMenuManagementCertificate.php";
require_once get_template_directory() . "/custom_function/showMenuManagementSearch.php";
require_once get_template_directory() . "/custom_function/saveFormUserSubmit.php";
require_once get_template_directory() . "/custom_function/getCertificate.php";
require_once get_template_directory() . "/custom_function/saveCertificate.php";
require_once get_template_directory() . "/custom_function/watchCertificated.php";
require_once get_template_directory() . "/custom_function/deleteUser.php";
require_once get_template_directory() . "/custom_function/cancelCertificate.php";
require_once get_template_directory() . "/custom_function/importIdCertificated.php";
require_once get_template_directory() . "/custom_function/deleteCertificate.php";
require_once get_template_directory() . "/custom_function/updateCertificate.php";
require_once get_template_directory() . "/custom_function/addCertificate.php";
require_once get_template_directory() . "/custom_function/getUsersByName.php";
require_once get_template_directory() . "/custom_function/getUsersByCertificate.php";
require_once get_template_directory() . "/custom_function/getUsersByDate.php";
require_once get_template_directory() . "/custom_function/getUsersByDateSubmit.php";


require get_template_directory() . '/inc/init.php';