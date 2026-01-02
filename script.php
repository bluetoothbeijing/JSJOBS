<?php

/** @Copyright Copyright (C) 2011
 * @license		GNU/GPL http://www.gnu.org/copyleft/gpl.html
 + Created by:	Ahmad Bilal
 * Company:		Buruj Solutions
 + Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	Oct 22, 2011
 ^
 + Project:		JS Documentation 
*/
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Installer\InstallerHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Installer\Installer;

class com_JSJobsInstallerScript
{

	/**
	 * method to install the component
	 *
	 * @return void
	 */
	function install($parent) 
	{

		// $parent is the class calling this method
		$parent->getParent()->setRedirectURL('index.php?option=com_jsjobs&c=postinstallation&view=postinstallation&layout=stepone');
	}

	/**
	 * method to uninstall the component
	 *
	 * @return void
	 */
	function uninstall($parent) 
	{
		// $parent is the class calling this method
		echo '<p>' . Text::_('JS Jobs Uninstall Successfully') . '</p>';
	}

	/**
	 * method to update the component
	 *
	 * @return void
	 */
	function update($parent) {

		
	}

	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @return void
	 */
	function preflight($type, $parent) 
	{
	}

	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @return void
	 */
	function postflight($type, $parent) 
	{

		//jimport('joomla.installer.helper');
		$installer = new Installer();
		$installer->setOverwrite(true);
		//$installer->_overwrite = true;
		$response = [
		    'success' => true,
		    'messages' => []
		];

		try {
		    $installer = new Installer();
		    $installer->setOverwrite(true);

		    $ext_path = JPATH_ADMINISTRATOR . '/components/com_jsjobs/extensions/';

		    // Ensure the path exists
		    if (!is_dir($ext_path)) {
		        throw new Exception('Extension path not found.');
		    }

		    $extensions = [
		        'modules/mod_jsjobslogin.zip' => 'JS Jobs Login Module',
		        'plugins/jsjobsregister.zip'=>'JS Jobs Register Plugin'
		    ];

		    foreach ($extensions as $ext => $extname) {
		        $packagePath = $ext_path . $ext;

		        // Check if zip exists
		        if (!file_exists($packagePath)) {
		             $response['messages'][] = "Error: File $ext not found.";
		             continue;
		        }

		        // Unpack
		        $package = InstallerHelper::unpack($packagePath);

		        if (!$package) {
		            $response['messages'][] = "Error: Could not unpack $extname.";
		            continue;
		        }

		        // Install
		        if ($installer->install($package['dir'])) {
		            $response['messages'][] = "$extname successfully installed.";
		        } else {
		            $response['messages'][] = "Error: Could not install $extname.";
		        }

		        // Cleanup
		        InstallerHelper::cleanupInstall($packagePath, $package['dir']);
		    }

		} catch (Exception $e) {
		    $response['success'] = false;
		    $response['messages'][] = 'Exception: ' . $e->getMessage();
		}


			//echo "<br /><br /><font color='green'><strong>Installing plugins</strong></font>";
			/*
			$ext_plugin_path = JPATH_ADMINISTRATOR.'/components/com_jsjobs/extensions/plugins/';
			$extensions = array( 
				'jsjobsregister.zip'=>'JS Jobs Register Plugin'

			 );
				 
			 foreach( $extensions as $ext => $extname ){
				  $package = InstallerHelper::unpack( $ext_plugin_path.$ext );
				  if( $installer->install( $package['dir'] ) ){
					echo "<br /><font color='green'>$extname successfully installed.</font>";
				  }else{
					echo "<br /><font color='red'>ERROR: Could not install the $extname. Please install manually.</font>";
				  }
				InstallerHelper::cleanupInstall( $ext_plugin_path.$ext, $package['dir'] ); 
			}
		*/	
		$update_sql_path = JPATH_ADMINISTRATOR.'/components/com_jsjobs/sql/updates/mysql/';
		$sql_files = glob($update_sql_path.'/*');  
   
		// Deleting all the files in the list 
		foreach($sql_files as $file) { 
			if(is_file($file))  
				// Delete the given file 
				unlink($file);  
		} 
		

	}



}

