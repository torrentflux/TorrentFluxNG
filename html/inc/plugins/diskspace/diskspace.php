<?php

require_once('inc/plugins/PluginInterface.php');

class DiskspaceInfo implements PluginInterface
{

	function __construct()
	{
		;
	}
	
	function show()
	{
		print( $this->getDiskspaceUi() );
	}

	function getConfiguration()
	{
		;
	}
	
	function setConfiguration($configArray)
	{
		;
	}

	function getDiskspaceUi() {
		$cfg = Configuration::get_instance()->get_cfg();
		
		$output = "";
		$diskspaceusage = getDriveSpace( $cfg['download_path'] );
		if ( $diskspaceusage > $cfg['diskusagewarninglevel'] ) $diskspacecolor = "#ff0000";
		else $diskspacecolor = '#33cc33';
		$output = $diskspaceusage . '% disk usage (' . formatBytesTokBMBGBTB( disk_free_space($cfg['download_path']) ) . "/" . formatBytesTokBMBGBTB( disk_total_space($cfg['download_path']) ) . ")";
		$output .= '<script type="text/javascript" src="js/diskspace.js"></script>';
		$output .= '<script type="text/javascript">drawProgressBar(\'' . $diskspacecolor . '\', 300, ' . $diskspaceusage . ');</script>';
		$output .= '<link rel="stylesheet" type="text/css" href="css/diskspace.css" />';
		
		return $output;
	}

}

?>
