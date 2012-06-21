<?php

require_once('inc/plugins/FilePluginBase.php');
require_once('inc/plugins/sfvchecker/Sfv.php');

class SfvChecker extends FilePluginBase
{

	function __construct($dir, $filename)
	{
		parent::__construct($dir, $filename);
	}

	static function isvalidaction($dir, $filename)
	{
		if ( ends_with($filename, 'sfv', false) )
			return true;

		return false;
	}

	static function getaction($dir, $filename)
	{
		$cfg = Configuration::get_instance()->get_cfg();
		return "<a href=\"javascript:loadpopup('Sfv Checker', 'dispatcher.php?plugin=sfvchecker&amp;action=passplugindata&amp;subaction=filemanagement&amp;dir=".urlencode($dir)."&amp;filename=".urlencode($filename)."', 'Loading...');centerPopup();loadPopup();\"><img src=\"themes/".$cfg['theme']."/images/dir/sfv.png\" /></a>";
	}

	function fileaction()
	{
		//convert and set variables
		$cfg = Configuration::get_instance()->get_cfg();
		$fulldir = $cfg['rewrite_path'].urldecode($this->dir);
		$filename = urldecode($this->filename);
		$fullname = tfb_shellencode($this->dir.$this->filename);

		if (!file_exists($fulldir . $filename)) { // TODO: create check if dir is ending with slash or not
			AuditAction($cfg["constants"]["error"], "SFV file does not exist: $filename");
			exit();
		}

		$inst = new Sfv($fulldir, $filename);
		if ( is_object($inst) ) {
			if (isset($_REQUEST['cleanup']))
				$inst->cleanup();
			elseif (isset($_REQUEST['restart'])) {
				$inst->cleanup();
				$inst->startsfvcheck();
				print("<br><a href=\"javascript:loadpopup('SFV Checker', 'dispatcher.php?plugin=sfvchecker&action=passplugindata&subaction=filemanagement&cleanup=true&dir=" .urlencode($this->dir). "&filename=" .urlencode($this->filename). "','Loading...');\">Cleanup control files</a>");
			} else {
				$inst->startsfvcheck();
				print("<br><a href=\"javascript:loadpopup('SFV Checker', 'dispatcher.php?plugin=sfvchecker&action=passplugindata&subaction=filemanagement&cleanup=true&dir=" .urlencode($this->dir). "&filename=" .urlencode($this->filename). "','Loading...');\">Cleanup control files</a>");
				print("<br><a href=\"javascript:loadpopup('SFV Checker', 'dispatcher.php?plugin=sfvchecker&action=passplugindata&subaction=filemanagement&restart=true&dir=" .urlencode($this->dir). "&filename=" .urlencode($this->filename). "','Loading...');\">Restart SFV Check</a>");
			}
		} else
			AuditAction($cfg["constants"]["error"], "Uncompression for this file is not supported: $this->filename");
	
	}
	
}

?>
