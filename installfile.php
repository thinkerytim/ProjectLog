<?php
/**
 * @package     Projectlog.Administrator
 * @subpackage  com_projectlog
 *
 * @copyright   Copyright (C) 2009 - 2014 The Thinkery, LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');

class com_projectlogInstallerScript
{
    private $tmppath;
    private $plmedia;
    private $installed_mods             = array();
    private $installed_plugs            = array();
    private $release                    = '3.3.1beta';
    private $minimum_joomla_release     = '3.1';
    private $preflight_message          = null;
    private $install_message            = null;
    private $uninstall_message          = null;
    private $update_message             = null;
    private $db                         = null;
    private $plerror                    = array();

    function preflight($action, $parent)
    {
        $jversion = new JVersion();

        // get new version of PL from manifest and define class variables
        $this->release  = $parent->get("manifest")->version;
        $this->db       = JFactory::getDBO();
        $this->plmedia  = JPATH_ROOT.'/media/com_projectlog';
        $this->tmppath  = JPATH_ROOT.'/media/pltmp';

        // Find mimimum required joomla version
        $this->minimum_joomla_release = $parent->get("manifest")->attributes()->version;

        if( version_compare( $jversion->getShortVersion(), $this->minimum_joomla_release, 'lt' ) ) {
            Jerror::raise(E_WARNING, null, 'Cannot install Project Log '.$this->release.' in a Joomla release prior to '.$this->minimum_joomla_release);
            return false;
        }
        
        // abort if the component being installed is not newer than the currently installed version
        switch ($action){
            case 'update':
                $oldRelease = $this->getParam('version');
                $rel = $oldRelease . ' to ' . $this->release;
                if ( version_compare( $this->release, $oldRelease, 'lt' ) ) {
                    Jerror::raise(E_WARNING, null, 'Incorrect version sequence. Cannot upgrade Project Log ' . $rel);
                    return false;
                }
                $this->installModsPlugs($parent);
            break;
            case 'install':
                $this->installModsPlugs($parent);
                $rel = $this->release;
            break;
        }

        // check for required libraries
        $curl_exists        = (extension_loaded('curl') && function_exists('curl_init')) ? '<span class="label label-success">Enabled</span>' : '<span class="text-error">Disabled</span>';
        $gd_exists          = (extension_loaded('gd') && function_exists('gd_info')) ? '<span class="label label-success">Enabled</span>' : '<span class="text-error">Disabled</span>';
        $php_version        = (PHP_VERSION >= 5.2) ? '<span class="label label-success">'.PHP_VERSION.'</span>' : '<span class="text-error">'.PHP_VERSION.'</span>';
        $php_calendar       = extension_loaded('calendar') ? '<span class="label label-success">Enabled</span>' : '<span class="text-error">Disabled</span>';
        $php_simplexml      = extension_loaded('simplexml') ? '<span class="label label-success">Enabled</span>' : '<span class="text-error">Disabled</span>';

        // Set preflight message
        $this->preflight_message .=  '
            <h3>Preflight Status: ' . $action . ' - ' . $rel . '</h3>
            <ul>
                <li>Current PL version: <span class="label label-success">'.$this->release.'</span></li>
                <li>PHP Version: '.$php_version.'</li>
                <li>cURL Support: '.$curl_exists.'</li>
                <li>GD Support: '.$gd_exists.'</li>
                <li>SimpleXML: '.$php_simplexml.'</li>
                <li>Calendar Extension: '.$php_calendar.'</li>
            </ul>';
    }

    function install($parent)
    {
        // Define vars
        $sample_data_file       = JPATH_ADMINISTRATOR.'/components/com_projectlog/assets/install.sampledata.sql';
        $sample_data_rslt       = '<span class="label label-success">Sample data installed</span>';

        // Check if sample data file exists and execute query
        if(JFile::exists($sample_data_file)){
            if(!$this->populateDatabase($this->db, $sample_data_file)){
                $sample_data_rslt   = '<span class="text-error">Sample data not installed</span>';
            }
        }else{ // Could not find sample data file
            $sample_data_rslt   = '<span class="text-error">Sample data not installed</span>';
            $this->plerror[]    = 'Could not find sample data file - '.$sample_data_file;
        }

        // Set installation message
        $this->install_message .= '
            <h3>Installation Status:</h3>
            <p>Congratulations on your install of Project Log! The first thing to do to get started with Project Log
            is to <a href="'.JRoute::_('index.php?option=com_config&view=component&component=com_projectlog').'">configure your component</a>. After saving your
            configuration, create a new project category. A sample project, log, and document has been created on installation. After you create a category, simply edit the sample project and apply
            your newly created category. Once a category is assigned to your project, you can create a menu item to a Project Log view via your menu manager.</p>

            <ul>
                <li>Sample data execution: '.$sample_data_rslt.'</li>
            </ul>

            <h3>Media Status:</h3>
            <ul>';
                //create media folders
                $folder_array       = array('', 'docs');
                $default_files      = JFolder::files($this->tmppath);
                foreach($folder_array as $folder){
                    if(!JFolder::exists($this->plmedia.'/'.$folder)){
                        if(!JFolder::create($this->plmedia.'/'.$folder, 0755) ) {
                            $this->plerror[] = 'Could not create the <em>'.$this->plmedia.'/'.$folder.'</em> folder. Please check your media folder permissions';
                            $this->install_message .= '<li>media/com_projectlog/'.$folder.': <span class="text-error">Not created</span></li>';
                        }else{
                            $folderpath = $this->plmedia.'/'.$folder;
                            foreach( $default_files as $file ){
                                if($folder == 'docs'){ // we want to copy the sample pdf and index.html to this folder                                    
                                    JFile::copy($this->tmppath.'/'.$file, $folderpath.'/'.$file);
                                }else{ // we only want the index.html in the root projectlog folder
                                    if(JFile::getExt($file) != 'pdf'){
                                        JFile::copy($this->tmppath.'/'.$file, $folderpath.'/'.$file);
                                    }
                                }                                        
                            }
                            $this->install_message .= '<li>media/com_projectlog/'.$folder.': <span class="label label-success">Created</span></li>';
                        }
                    }else{
                        $this->install_message .= '<li>media/com_projectlog/'.$folder.': <span class="label label-success">Exists from previous install</span></li>';
                    }
                }
                JFolder::delete($this->tmppath);
        $this->install_message .= '
            </ul>';
    }

    function update($parent)
    {
        // Set update message
        $this->update_message .=  '
            <h3>Update Status</h3>
            <p>Congratulations on your update of Project Log! Please take a look at the changelog to the right
            to see what\'s new! Please post issues to the support forums at extensions.thethinkery.net</p>';
    }

    function uninstall($parent)
    {
        $this->db       = JFactory::getDBO();
        $drop_results   = array();
        $pl_uninstall_error = 0;

        $drop_array = array('pldocs'        => 'projectlog_docs',
                            'pllogs'        => 'projectlog_logs',
                            'plprojects'    => 'projectlog_projects');

        foreach($drop_array AS $key => $value)
        {
            $this->db->setQuery("DROP TABLE IF EXISTS #__".$value);
            if($this->db->execute()){
                $drop_results[$key] = '<span class="label label-success">Removed Successfully</span>';
            }else{
                $drop_results[$key] = '<span class="text-error">Not Removed</span>';
                $pl_uninstall_error++;
            }
        }

        echo '
        <div class="row-fluid">
            <div class="span5">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Table</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <td colspan="2">&nbsp;</td>
                        </tr>
                    </tfoot>
                    <tbody>
                        <tr><td class="key">Docs Table</td><td style="text-align: center !important;">'.$drop_results['pldocs'].'</td></tr>
                        <tr><td class="key">Logs Table</td><td style="text-align: center !important;">'.$drop_results['pllogs'].'</td></tr>
                        <tr><td class="key">Projects Table</td><td style="text-align: center !important;">'.$drop_results['plprojects'].'</td></tr>                        
                    </tbody>
                </table>
            </div>
            <div class="span5">
                <table class="table table-striped">
                    <tr><td valign="top"><h3>Thank you for using Project Log!</h3></td></tr>
                    <tr>
                        <td valign="top">
                            <p>Thank you for using Project Log. If you have any new feature requests we would love to hear
                            them! Please post requests in the forums at <a href="http://extensions.thethinkery.net" target="_blank">http://extensions.thethinkery.net</a>. Ideas for
                            new component features, modules, and plugins are welcome. If you have questions please post to the support forum or email
                            us at <a href="mailto:info@thethinkery.net">info@thethinkery.net</a>.</p>

                            <h4>Upgrade Instructions:</h4>
                            <p>If you are upgrading to a newer version of Project Log, please visit <a href="http://extensions.thethinkery.net" target="_blank">http://extensions.thethinkery.net</a>
                            to review upgrade instructions. All media folders and files have been preserved for use in future upgrades and can be located in your site/media/com_projectlog folder.</p>
                        </td>
                    </tr>
                </table>
            </div>
        </div>';
    }

    function postflight($action, $parent)
    {
        echo '
        <style type="text/css">
            .pllogoheader{border-bottom: solid 1px #ccc; margin-bottom: 8px;}
            .plleftcol{color: #808080; padding: 0px 10px;}
            .pllogfile{background: #ffffff !important; border: solid 1px #cccccc; padding: 5px 10px; height: 500px; overflow: auto;}
        </style>

        <div class="row-fluid pllogoheader">
            '.JHTML::_('image', 'administrator/components/com_projectlog/assets/images/projectlog_logo.png', 'Project Log :: By The Thinkery' ).'
        </div>
        <div class="row-fluid">
            <div class="span5">
                '.$this->preflight_message;

                switch ($action){
                    case "install":
                        echo $this->install_message;
                        //$this->addContentTypes();
                    break;
                    case "update":
                        echo $this->update_message;
                        //if($this->getParam('version') <= '3.1') $this->addContentTypes();
                    break;
                    case "uninstall":
                        echo $this->uninstall_message;
                    break;
                }

                if(count($this->plerror))
                {
                    JError::raise(E_WARNING, 123, 'Component was installed but some errors occurred. Please check install status below for details');
                    echo '
                        <h3>Error Status</h3>
                        <ul>';
                            foreach($this->plerror as $error){
                                echo '<li><span class="text-error">'.$error.'</span></li>';
                            }
                   echo '
                        </ul>';
                }
            echo '
            </div>
            <div class="span7">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#plchangelog" data-toggle="tab">'.JText::_('Change Log').'</a></li>';

                    if (count($this->installed_plugs))
                    {
                        echo '<li><a href="#plplugins" data-toggle="tab">'.JText::_('Plugins').'</a></li>';
                    }
                    if (count($this->installed_mods))
                    {
                        echo '<li><a href="#plmodules" data-toggle="tab">'.JText::_('Modules').'</a></li>';
                    }
            echo '
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="plchangelog">';
                            $logfile            = JPATH_ADMINISTRATOR.'/components/com_projectlog/assets/CHANGELOG.TXT';
                            if(JFile::exists($logfile))
                            {
                                $logcontent     = JFile::read($logfile);
                                $logcontent     = htmlspecialchars($logcontent, ENT_COMPAT, 'UTF-8');
                                echo '<pre style="font-size: 11px !important; color: #666; height: 600px; overflow: auto;">'.$logcontent.'</pre>';
                            }else{
                                echo 'Could not find changelog content - '.$logfile;
                            }
                        echo '
                    </div>';

                    if (count($this->installed_plugs))
                    {
                        echo '
                            <div class="tab-pane" id="plplugins">
                                <div>
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>'.JText::_('Plugin').'</th>
                                                <th>'.JText::_('Group').'</th>
                                                <th>'.JText::_('Status').'</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <td colspan="3">&nbsp;</td>
                                            </tr>
                                        </tfoot>
                                        <tbody>';
                                        foreach ($this->installed_plugs as $plugin) :
                                            $pstatus    = ($plugin['upgrade']) ? '<button class="btn btn-mini btn-success disabled"><i class="icon-thumbs-up"></i></button>' : '<button class="btn btn-mini btn-danger disabled"><i class="icon-thumbs-down"></i></button>';
                                            echo '<tr>
                                                    <td>'.$plugin['plugin'].'</td>
                                                    <td>'.$plugin['group'].'</td>
                                                    <td style="text-align: center;">'.$pstatus.'</td>
                                                  </tr>';
                                        endforeach;
                           echo '
                                        </tbody>
                                    </table>
                                </div>
                           </div>';
                    }

                    if (count($this->installed_mods))
                    {
                        echo '
                            <div class="tab-pane" id="plmodules">
                                <div>
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>'.JText::_('Module').'</th>
                                                <th>'.JText::_('Status').'</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <td colspan="2">&nbsp;</td>
                                            </tr>
                                        </tfoot>
                                        <tbody>';
                                        foreach ($this->installed_mods as $module) :
                                            $mstatus    = ($module['upgrade']) ? '<button class="btn btn-mini btn-success disabled"><i class="icon-thumbs-up"></i></button>' : '<button class="btn btn-mini btn-danger disabled"><i class="icon-thumbs-down"></i></button>';
                                            echo '<tr>
                                                    <td>'.$module['module'].'</td>
                                                    <td style="text-align: center;">'.$mstatus.'</td>
                                                  </tr>';
                                        endforeach;
                           echo '
                                        </tbody>
                                    </table>
                                </div>
                           </div>';
                    }
            echo '
            </div>
        </div>';             
    }

    function getParam( $name )
    {
        $this->db = JFactory::getDbo();
        $this->db->setQuery('SELECT manifest_cache FROM #__extensions WHERE name = "com_projectlog" AND type="component"');
        $manifest = json_decode( $this->db->loadResult(), true );
        return $manifest[$name];
    }

    function installModsPlugs($parent)
    {
        $manifest       = $parent->get("manifest");
        $parent         = $parent->getParent();
        $source         = $parent->getPath("source");

        // install plugins and modules
        $installer = new JInstaller();

        // Install plugins
        foreach($manifest->plugins->plugin as $plugin)
        {
            $attributes                 = $plugin->attributes();
            $plg                        = $source .'/'. $attributes['folder'].'/'.$attributes['plugin'];
            $new                        = ($attributes['new']) ? '&nbsp;(<span class="label label-success">New in v.'.$attributes['new'].'!</span>)' : '';
            if($installer->install($plg))
            {
                $this->installed_plugs[]    = array('plugin' => $attributes['plugin'].$new, 'group'=> $attributes['group'], 'upgrade' => true);
            }else{
                $this->installed_plugs[]    = array('plugin' => $attributes['plugin'], 'group'=> $attributes['group'], 'upgrade' => false);
                $this->plerror[] = JText::_('Error installing plugin').': '.$attributes['plugin'];
            }
        }

        // Install modules
        foreach($manifest->modules->module as $module)
        {
            $attributes             = $module->attributes();
            $mod                    = $source .'/'. $attributes['folder'].'/'.$attributes['module'];
            $new                    = ($attributes['new']) ? '&nbsp;(<span class="label label-success">New in v.'.$attributes['new'].'!</span>)' : '';
            if($installer->install($mod)){
                $this->installed_mods[] = array('module' => $attributes['module'].$new, 'upgrade' => true);
            }else{
                $this->installed_mods[] = array('module' => $attributes['module'], 'upgrade' => false);
                $this->plerror[] = JText::_('Error installing module').': '.$attributes['module'];
            }
        }
    }

    public function populateDatabase($db, $schema)
	{
		$return = true;

		// Get the contents of the schema file.
		if (!($buffer = file_get_contents($schema)))
		{
			$this->plerror[] = $db->getErrorMsg();
			return false;
		}

		// Get an array of queries from the schema and process them.
		$queries = $this->_splitQueries($buffer);
		foreach ($queries as $query)
		{
			// Trim any whitespace.
			$query = trim($query);

			// If the query isn't empty and is not a MySQL or PostgreSQL comment, execute it.
			if (!empty($query) && ($query{0} != '#') && ($query{0} != '-'))
			{
				// Execute the query.
				$db->setQuery($query);

				try
				{
					$db->execute();
				}
				catch (RuntimeException $e)
				{
					$this->plerror[] = $e->getMessage();
					$return = false;
				}
			}
		}

		return $return;
	}

    protected function _splitQueries($sql)
	{
		$buffer    = array();
		$queries   = array();
		$in_string = false;

		// Trim any whitespace.
		$sql = trim($sql);

		// Remove comment lines.
		$sql = preg_replace("/\n\#[^\n]*/", '', "\n" . $sql);

		// Remove PostgreSQL comment lines.
		$sql = preg_replace("/\n\--[^\n]*/", '', "\n" . $sql);

		// find function
		$funct = explode('CREATE OR REPLACE FUNCTION', $sql);
		// save sql before function and parse it
		$sql = $funct[0];

		// Parse the schema file to break up queries.
		for ($i = 0; $i < strlen($sql) - 1; $i++)
		{
			if ($sql[$i] == ";" && !$in_string)
			{
				$queries[] = substr($sql, 0, $i);
				$sql = substr($sql, $i + 1);
				$i = 0;
			}

			if ($in_string && ($sql[$i] == $in_string) && $buffer[1] != "\\")
			{
				$in_string = false;
			}
			elseif (!$in_string && ($sql[$i] == '"' || $sql[$i] == "'") && (!isset ($buffer[0]) || $buffer[0] != "\\"))
			{
				$in_string = $sql[$i];
			}
			if (isset ($buffer[1]))
			{
				$buffer[0] = $buffer[1];
			}
			$buffer[1] = $sql[$i];
		}

		// If the is anything left over, add it to the queries.
		if (!empty($sql))
		{
			$queries[] = $sql;
		}

		// add function part as is
		for ($f = 1; $f < count($funct); $f++)
		{
			$queries[] = 'CREATE OR REPLACE FUNCTION ' . $funct[$f];
		}

		return $queries;
	}
}