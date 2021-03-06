<?php
/**
 * This class collects data which is used to create some usage statistics.
 *
 * The collected data is - after authorization of the administrator - submitted
 * to phpMyFAQ.de. For privacy reasons we try to collect only data which aren't private
 * or don't give any information which might help to identify the user.
 *
 * PHP Version 5.2
 *
 * This Source Code Form is subject to the terms of the Mozilla Public License,
 * v. 2.0. If a copy of the MPL was not distributed with this file, You can
 * obtain one at http://mozilla.org/MPL/2.0/.
 *
 * @category  phpMyFAQ
 * @package   PMF_Questionnaire_Data
 * @author    Johannes Schlueter <johannes@php.net>
 * @author    Thorsten Rinne <thorsten@phpmyfaq.de>
 * @copyright 2007-2012 phpMyFAQ Team
 * @license   http://www.mozilla.org/MPL/2.0/ Mozilla Public License Version 2.0
 * @link      http://www.phpmyfaq.de
 * @since     2007-03-17
 */

/**
 * PMF_Questionnaire_Data
 *
 * @category  phpMyFAQ
 * @package   PMF_Questionnaire_Data
 * @author    Johannes Schlueter <johannes@php.net>
 * @author    Thorsten Rinne <thorsten@phpmyfaq.de>
 * @copyright 2007-2012 phpMyFAQ Team
 * @license   http://www.mozilla.org/MPL/2.0/ Mozilla Public License Version 2.0
 * @link      http://www.phpmyfaq.de
 * @since     2007-03-17
 */
class PMF_Questionnaire_Data
{
    /**
     * Array with data
     *
     * @var array
     */
    private $data = array();
    
    /**
     * Array with configuration data
     *
     * @var array
     */
    private $config = array();
    
    /**
     * Old version number
     *
     * @var string
     */
    private $oldversion = '0';

    /**
     * Constructor.
     *
     * @param  array  $config     Array of configuration items
     * @param  string $oldversion Old version number
     *
     * @return void
     */
    function __construct(Array $config, $oldversion = '0')
    {
        $this->config               = $config;
        $this->config['oldversion'] = $oldversion;
    }

    /**
     * Get data as an array.
     *
     * @return array
     */
    public function get()
    {
        if (!$this->data) {
            $this->collect();
        }

        return $this->data;
    }

    /**
     * Collect info into the data property.
     *
     * @return void
     */
    public function collect()
    {
        $this->data = array(
            'phpMyFAQ' => $this->getPmfInfo(),
            'PHP'      => $this->getPHPInfo(),
            'System'   => $this->getSystemInfo());
    }

    /**
     * Get data about this phpMyFAQ installation.
     *
     * @return  array
     */
    public function getPMFInfo()
    {
        // oldversion isn't a real PMF config option and it is just used by this class
        $settings = array(
            'main.currentVersion',
            'oldversion',
            'main.language',
            'security.permLevel',
            'main.languageDetection',
            'security.ldapSupport');

        return array_intersect_key($this->config, array_flip($settings));
    }

    /**
     * Get data about the PHP runtime setup.
     *
     * @return array
     */
    public function getPHPInfo()
    {
        return array(
            'version'                     => PHP_VERSION,
            'sapi'                        => PHP_SAPI,
            'int_size'                    => defined('PHP_INT_SIZE') ? PHP_INT_SIZE : '',
            'safe_mode'                   => (int)ini_get('safe_mode'),
            'open_basedir'                => (int)ini_get('open_basedir'),
            'memory_limit'                => ini_get('memory_limit'),
            'allow_url_fopen'             => (int)ini_get('allow_url_fopen'),
            'allow_url_include'           => (int)ini_get('allow_url_include'),
            'file_uploads'                => (int)ini_get('file_uploads'),
            'upload_max_filesize'         => ini_get('upload_max_filesize'),
            'post_max_size'               => ini_get('post_max_size'),
            'disable_functions'           => ini_get('disable_functions'),
            'disable_classes'             => ini_get('disable_classes'),
            'enable_dl'                   => (int)ini_get('enable_dl'),
            'magic_quotes_gpc'            => (int)ini_get('magic_quotes_gpc'),
            'register_globals'            => (int)ini_get('register_globals'),
            'filter.default'              => ini_get('filter.default'),
            'zend.ze1_compatibility_mode' => (int)ini_get('zend.ze1_compatibility_mode'),
            'unicode.semantics'           => (int)ini_get('unicode.semantics'),
            'zend_thread_safty'           => (int)function_exists('zend_thread_id'),
            'extensions'                  => get_loaded_extensions());
    }

    /**
     * Get data about the general system information, like OS or IP (shortened).
     *
     * @return array
     */
    public function getSystemInfo()
    {
        // Start discovering the IPv4 server address, if available
        $serverAddress = '0.0.0.0';
        if (isset($_SERVER['SERVER_ADDR'])) {
            $serverAddress = $_SERVER['SERVER_ADDR'];
        }
        // Running on IIS?
        if (isset($_SERVER['LOCAL_ADDR'])) {
            $serverAddress = $_SERVER['LOCAL_ADDR'];
        }

        $aIPAddress = explode('.', $serverAddress); // only works with IPv4

        return array(
            'os'    => PHP_OS,
            'httpd' => $_SERVER['SERVER_SOFTWARE'],
            'ip'    => ''
        );
    }
}

/**
 * Output the data as an HTML Definition List.
 *
 * @param  mixed  $value Value
 * @param  string $key   Key
 * @param  string $ident Identian
 *
 * @return  void
 */
function data_printer($value, $key, $ident = "\n\t")
{
    echo $ident, '<dt>', htmlentities($key), '</dt>', $ident, "\t", '<dd>';
    if (is_array($value)) {
        echo '<dl>';
        array_walk($value, 'data_printer', $ident."\t");
        echo $ident, "\t", '</dl>';
    } else {
        echo htmlentities($value);
    }
    echo '</dd>';
}
