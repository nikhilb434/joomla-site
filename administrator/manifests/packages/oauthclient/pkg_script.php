<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Language\Text;

/**
 * Script file of miniorange_oauth_plugin.
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
class pkg_oauthclientInstallerScript
{
    /**
     * This method is called after a component is installed.
     *
     * @param  \stdClass $parent - Parent object calling this method.
     *
     * @return void
     */
    public function install($parent) 
    {

            
    }

    /**
     * This method is called after a component is uninstalled.
     *
     * @param  \stdClass $parent - Parent object calling this method.
     *
     * @return void
     */
    public function uninstall($parent) 
    {
        //echo '<p>' . Text::_('COM_HELLOWORLD_UNINSTALL_TEXT') . '</p>';
    }

    /**
     * This method is called after a component is updated.
     *
     * @param  \stdClass $parent - Parent object calling object.
     *
     * @return void
     */
    public function update($parent) 
    {
        //echo '<p>' . Text::sprintf('COM_HELLOWORLD_UPDATE_TEXT', $parent->get('manifest')->version) . '</p>';
    }

    /**
     * Runs just before any installation action is performed on the component.
     * Verifications and pre-requisites should run in this function.
     *
     * @param  string    $type   - Type of PreFlight action. Possible values are:
     *                           - * install
     *                           - * update
     *                           - * discover_install
     * @param  \stdClass $parent - Parent object calling object.
     *
     * @return void
     */
    public function preflight($type, $parent) 
    {
        //echo '<p>' . Text::_('COM_HELLOWORLD_PREFLIGHT_' . $type . '_TEXT') . '</p>';
    }

    /**
     * Runs right after any installation action is performed on the component.
     *
     * @param  string    $type   - Type of PostFlight action. Possible values are:
     *                           - * install
     *                           - * update
     *                           - * discover_install
     * @param  \stdClass $parent - Parent object calling object.
     *
     * @return void
     */
    function postflight($type, $parent) 
    {
       // echo '<p>' . Text::_('COM_HELLOWORLD_POSTFLIGHT_' . $type . '_TEXT') . '</p>';
       if ($type == 'uninstall') {
        return true;
        }
       $this->showInstallMessage('');
    }

    protected function showInstallMessage($messages=array()) {
        ?>
        <style>
        
        .mo-row {
            width: 100%;
            display: block;
            margin-bottom: 2%;
        }
    
        .mo-row:after {
            clear: both;
            display: block;
            content: "";
        }

        .btn {
        display: inline-block;
        font-weight: 300;
        text-align: center;
        vertical-align: middle;
        user-select: none;
        background-color: transparent;
        border: 1px solid transparent;
        padding: 4px 12px;
        font-size: 0.85rem;
        line-height: 1.5;
        border-radius: 0.25rem;
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        } 
       
        .btn-cstm {
        background: #001b4c;
        border: none;
        font-size: 1.1rem;
        padding: 0.3rem 1.5rem;
        color: #fff !important;
        cursor: pointer;
      }
            
            /* Dark background button styles */
            :root[data-color-scheme=dark] {
                .btn-cstm {
                    color: white;
                    background-color: #000000;
                    border-color:1px solid #ffffff; 
                }

                .btn-cstm:hover {
                    background-color: #000000;
                    border-color: #ffffff; 
                }
            }
		
    </style>
   
    <h3>Steps to use the OAuth Client plugin.</h3>
    <ul>
    <li>Click on <b>Components</b></li>
    <li>Click on <b>miniOrange OAuth Client</b> and select <b>Configure OAuth</b> tab</li>
    <li>You can start configuring.</li>
    </ul>
    	<div class="mo-row">
            <a class="btn btn-cstm"  href="index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=configuration">Start Using miniOrange OAuth Client plugin</a>
            <a class="btn btn-cstm"  href="https://plugins.miniorange.com/guide-to-enable-joomla-oauth-client" target="_blank">Read the miniOrange documents</a>
		    <a class="btn btn-cstm"  href="https://www.miniorange.com/contact" target="_blank">Get Support!</a>
        </div>
        <?php
    }
  
}