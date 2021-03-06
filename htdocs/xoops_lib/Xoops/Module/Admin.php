<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

namespace Xoops\Module;
use Xoops\Core\Helper\HeaderCommands;

/**
 * Xoops ModuleAdmin Classes
 *
 * @category  Xoops\Module\Admin
 * @package   Admin
 * @author    Mage Grégory (AKA Mage)
 * @copyright 2013-2014 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      xoops.org
 * @since     2.6.0
 */
class Admin
{
    /**
     * Set module directory
     *
     * @var string
     */
    public $tplModule = 'system';

    /**
     * Template call for each render parts
     *
     * @var array
     */
    public $tplFile = array(
        'index' => 'admin_index.tpl',
        'about' => 'admin_about.tpl',
        'infobox' => 'admin_infobox.tpl',
        'bread' => 'admin_breadcrumb.tpl',
        'button' => 'admin_buttons.tpl',
        'tips' => 'admin_tips.tpl',
        'nav'   => 'admin_navigation.tpl',
    );

    /**
     * Tips to display in admin page
     *
     * @var string
     */
    private $tips = '';

    /**
     * List of button
     *
     * @var array
     */
    private $itemButton = array();

    /**
     * List of Info Box
     *
     * @var array
     */
    private $itemInfoBox = array();

    /**
     * List of line of an Info Box
     *
     * @var array
     */
    private $itemConfigBoxLine = array();

    /**
     * Breadcrumb data
     *
     * @var array
     */
    private $bread = array();

    /**
     * Current module object
     *
     * @var XoopsModule $module
     */
    private $module = null;

    /**
     * Constructor
     */
    public function __construct()
    {
        $xoops = \Xoops::getInstance();
        $this->module = $xoops->module;
        $xoops->theme()->addStylesheet('media/xoops/css/moduladmin.css');
    }

    /**
     * Add breadcrumb menu
     *
     * @param string $title title
     * @param string $link  url
     * @param bool   $home  is home
     *
     * @return void
     */
    public function addBreadcrumbLink($title = '', $link = '', $params = array())
    {
        $breadCrumb = \Xoops\Core\Helper\Breadcrumb::getInstance();
        $breadCrumb->addCrumb($title, $link, $params);
    }

    /**
     * Add config line
     *
     * @param string $value line value - a string or array of values
     * @param string $type  type of line default, folder, chmod, extension, module
     *
     * @return bool
     */
    public function addConfigBoxLine($value = '', $type = 'default')
    {
        switch ($type) {
            default:
            case "default":
                $this->itemConfigBoxLine[] = array('type' => $type, 'text' => $value);
                break;

            case "folder":
                if (!is_dir($value)) {
                    $this->itemConfigBoxLine[] = array(
                        'type' => 'error', 'text' => sprintf(\XoopsLocale::EF_FOLDER_DOES_NOT_EXIST, $value)
                    );
                } else {
                    $this->itemConfigBoxLine[] = array(
                        'type' => 'accept', 'text' => sprintf(\XoopsLocale::SF_FOLDER_EXISTS, $value)
                    );
                }
                break;

            case "chmod":
                if (is_dir($value[0])) {
                    if (substr(decoct(fileperms($value[0])), 2) != $value[1]) {
                        $this->itemConfigBoxLine[] = array(
                            'type' => 'error',
                            'text' => sprintf(
                                \XoopsLocale::EF_FOLDER_MUST_BE_WITH_CHMOD,
                                $value[0],
                                $value[1],
                                substr(decoct(fileperms($value[0])), 2)
                            )
                        );
                    } else {
                        $this->itemConfigBoxLine[] = array(
                            'type' => 'accept',
                            'text' => sprintf(
                                \XoopsLocale::EF_FOLDER_MUST_BE_WITH_CHMOD,
                                $value[0],
                                $value[1],
                                substr(decoct(fileperms($value[0])), 2)
                            )
                        );
                    }
                }
                break;

            case "extension":
                $xoops = \Xoops::getInstance();
                if (is_array($value)) {
                    $text = $value[0];
                    $type = $value[1];
                } else {
                    $text = $value;
                    $type = 'error';
                }
                if ($xoops->isActiveModule($text) == false) {
                    $this->itemConfigBoxLine[] = array(
                        'type' => $type, 'text' => sprintf(\XoopsLocale::EF_EXTENSION_IS_NOT_INSTALLED, $text)
                    );
                } else {
                    $this->itemConfigBoxLine[] = array(
                        'type' => 'accept', 'text' => sprintf(\XoopsLocale::SF_EXTENSION_IS_INSTALLED, $text)
                    );
                }
                break;

            case "module":
                $xoops = \Xoops::getInstance();
                if (is_array($value)) {
                    $text = $value[0];
                    $type = $value[1];
                } else {
                    $text = $value;
                    $type = 'error';
                }
                if ($xoops->isActiveModule($text) == false) {
                    $this->itemConfigBoxLine[] = array(
                        'type' => $type, 'text' => sprintf(\XoopsLocale::F_MODULE_IS_NOT_INSTALLED, $text)
                    );
                } else {
                    $this->itemConfigBoxLine[] = array(
                        'type' => 'accept', 'text' => sprintf(\XoopsLocale::F_MODULE_IS_INSTALLED, $text)
                    );
                }
                break;

            case "service":
                $xoops = \Xoops::getInstance();
                if (is_array($value)) {
                    $text = $value[0];
                    $type = $value[1];
                } else {
                    $text = $value;
                    $type = 'error';
                }
                if ($xoops->service($text)->isAvailable()) {
                    $this->itemConfigBoxLine[] = array(
                        'type' => 'accept', 'text' => sprintf(\XoopsLocale::SF_SERVICE_IS_INSTALLED, $text)
                    );
                } else {
                    $this->itemConfigBoxLine[] = array(
                        'type' => $type, 'text' => sprintf(\XoopsLocale::EF_SERVICE_IS_NOT_INSTALLED, $text)
                    );
                }
                break;

        }
        return true;
    }

    /**
     * Add Info box
     *
     * @param string $title title
     * @param string $type  type
     * @param string $extra extra
     *
     * @return bool
     */
    public function addInfoBox($title, $type = 'default', $extra = '')
    {
        $ret['title'] = $title;
        $ret['type'] = $type;
        $ret['extra'] = $extra;
        $this->itemInfoBox[] = $ret;
        return true;
    }

    /**
     * Add line to the info box
     *
     * @param string $text  title
     * @param string $type  type
     * @param string $color color
     *
     * @return bool
     */
    public function addInfoBoxLine($text = '', $type = 'default', $color = 'inherit')
    {
        $ret = array();
        $ret['text'] = $text;
        $ret['color'] = $color;

        foreach (array_keys($this->itemInfoBox) as $i) {
            if ($this->itemInfoBox[$i]['type'] == $type) {
                $this->itemInfoBox[$i]['line'][] = $ret;
                unset($ret);
            }
        }
        return true;
    }

    /**
     * Add Item button
     *
     * @param string $title title
     * @param string $link  link
     * @param string $icon  icon
     * @param string $extra extra
     *
     * @return bool
     */
    public function addItemButton($title, $link, $icon = 'add', $extra = '', $color = 'default')
    {
        $ret['title']   = $title;
        $ret['link']    = $link;
        $ret['icon']    = $icon;
        $ret['color']   = $color;
        $ret['extra']   = $extra;
        $this->itemButton[] = $ret;
        return true;
    }

    /**
     * Add a tips
     *
     * @param string $text text
     *
     * @return void
     */
    public function addTips($text = '')
    {
        $alerts = \Xoops\Core\Helper\GuiAlerts::getInstance();
        $alerts->addInfo($text, 'xicon-info', $this->module ? $this->module->name() : '');
    }

    /**
     * Add CSS classes to BODY element.
     *
     * @param $class
     */
    public function addBodyClass($class){
        $xoops = \Xoops::getInstance();
        $xoops->theme()->addBodyClass($class);
    }

    /**
     * Add a tool to Xoops toolbar. This is an interface of
     * \Xoops\Core\Helper\Toolbar::add_tool() method.
     *
     * @param string $caption
     * @param array  $attributes
     */
    public function addTool($caption, $icon, $attributes){
        \Xoops\Core\Helper\Toolbar::getInstance()->addTool($caption, $icon, $attributes);
    }

    /**
     * Construct template path
     *
     * @param string $type type
     *
     * @return string
     */
    private function getTplPath($type = '')
    {
        return 'admin:' . $this->tplModule . '/' . $this->tplFile[$type];
    }

    /**
     * renderBreadcrumb
     *
     * @return string
     */
    public function renderBreadcrumb()
    {
        /*$xoops = \Xoops::getInstance();
        $xoops->tpl()->assign('xo_admin_breadcrumb', $this->bread);
        return $xoops->tpl()->fetch($this->getTplPath('bread'));*/
        \Xoops\Core\Helper\Breadcrumb::getInstance()->render();
    }

    /**
     * displayBreadcrumb
     *
     * @return void
     */
    public function displayBreadcrumb()
    {
        return true; //echo $this->renderBreadcrumb();
    }

    /**
     * Render all items buttons
     *
     * @param string $position  position
     * @param string $delimiter delimiter
     *
     * @return string
     */
    public function renderButton($align = '')
    {
        $xoops = \Xoops::getInstance();
        $xoops->tpl()->assign(array('xo_admin_buttons' => $this->itemButton,'xo_buttons_align' => $align));
        return $xoops->tpl()->fetch($this->getTplPath('button'));
    }

    /**
     * displayButton
     *
     * @param string $position  position
     * @param string $delimiter delimiter
     *
     * @return void
     */
    public function displayButton($position = "floatright", $delimiter = "&nbsp;")
    {
        echo $this->renderButton($position, $delimiter);
    }

    /**
     * Render InfoBox
     *
     * @return string
     */
    public function renderInfoBox()
    {
        $xoops = \Xoops::getInstance();
        $xoops->tpl()->assign('xo_admin_box', $this->itemInfoBox);
        return $xoops->tpl()->fetch($this->getTplPath('infobox'));
    }

    /**
     * displayInfoBox
     *
     * @return void
     */
    public function displayInfoBox()
    {
        echo $this->renderInfoBox();
    }

    /**
     * Render index page for admin
     *
     * @return string
     */
    public function renderIndex()
    {
        $xoops = \Xoops::getInstance();
        $this->addBodyClass('xo-admin-index');
        $this->module->loadAdminMenu();
        foreach ($this->module->adminmenu as $menu){
            $is_absolute = preg_match('/^[(https?|ftps?|ed2k)\:\/\/|\/]/i', $menu['link']);
            $this->addTool(
                $menu['title'],
                $menu['icon'],
                array(
                    'href' => $is_absolute ? $menu['link'] : $xoops->url("modules/" . $xoops->module->dirname() . '/' . $menu['link'])
                )
            );
        }

        if ($this->module->getInfo('help')) {
            $help = array();
            $this->addTool(
                \XoopsLocale::HELP,
                'xicon-help',
                array(
                    'href' => '../system/help.php?mid=' . $this->module->getVar('mid', 's')
                        . "&amp;" . $this->module->getInfo('help')
                )
            );
        }

        $xoops->tpl()->assign('xo_admin_box', $this->itemInfoBox);

        // If you use a config label
        if ($this->module->getInfo('min_php') || $this->module->getInfo('min_xoops')
            || !empty($this->itemConfigBoxLine)
        ) {
            // PHP version
            if ($this->module->getInfo('min_php')) {
                if (0 >= version_compare(phpversion(), $this->module->getInfo('min_php'))) {
                    $this->addConfigBoxLine(
                        sprintf(
                            \XoopsLocale::F_MINIMUM_PHP_VERSION_REQUIRED,
                            $this->module->getInfo('min_php'),
                            phpversion()
                        ),
                        'error'
                    );
                } else {
                    $this->addConfigBoxLine(
                        sprintf(
                            \XoopsLocale::F_MINIMUM_PHP_VERSION_REQUIRED,
                            $this->module->getInfo('min_php'),
                            phpversion()
                        ),
                        'accept'
                    );
                }
            }
            // Database version
            // @todo this needs a major rethink for Doctrine
            // a specific driver might be required, but Doctrine obscures specific version
            $dbarray = $this->module->getInfo('min_db');
            if ($dbarray[\XoopsBaseConfig::get('db-type')]) {
                switch (\XoopsBaseConfig::get('db-type')) {
                    case "mysql":
                        $dbCurrentVersion = mysql_get_server_info();
                        break;
                    case "mysqli":
                        $dbCurrentVersion = mysqli_get_server_info();
                        break;
                    case "pdo":
                        $dbCurrentVersion = $xoops->db()->getAttribute(PDO::ATTR_SERVER_VERSION);
                        break;
                    default:
                        $dbCurrentVersion = '0';
                        break;
                }
                $currentVerParts = explode('.', (string)$dbCurrentVersion);
                $iCurrentVerParts = array_map('intval', $currentVerParts);
                $dbRequiredVersion = $dbarray[\XoopsBaseConfig::get('db-type')];
                $reqVerParts = explode('.', (string)$dbRequiredVersion);
                $iReqVerParts = array_map('intval', $reqVerParts);
                $icount = $j = count($iReqVerParts);
                $reqVer = $curVer = 0;
                for ($i = 0; $i < $icount; ++$i) {
                    $j--;
                    $reqVer += $iReqVerParts[$i] * pow(10, $j);
                    if (isset($iCurrentVerParts[$i])) {
                        $curVer += $iCurrentVerParts[$i] * pow(10, $j);
                    } else {
                        $curVer = $curVer * pow(10, $j);
                    }
                }
                if ($reqVer > $curVer) {
                    $this->addConfigBoxLine(
                        sprintf(
                            strtoupper(\XoopsBaseConfig::get('db-type')) . ' '
                            . \XoopsLocale::F_MINIMUM_DATABASE_VERSION_REQUIRED,
                            $dbRequiredVersion,
                            $dbCurrentVersion
                        ),
                        'error'
                    );
                } else {
                    $this->addConfigBoxLine(
                        sprintf(
                            strtoupper(\XoopsBaseConfig::get('db-type')) . ' ' . \XoopsLocale::F_MINIMUM_DATABASE_VERSION_REQUIRED,
                            $dbRequiredVersion,
                            $dbCurrentVersion
                        ),
                        'accept'
                    );
                }
            }

            // xoops version
            if ($this->module->getInfo('min_xoops')) {
                $xoopsVersion = substr(\Xoops::VERSION, 6); // skip 'XOOPS ' prefix
                if (version_compare($xoopsVersion, $this->module->getInfo('min_xoops')) >= 0) {
                    $this->addConfigBoxLine(
                        sprintf(
                            \XoopsLocale::F_MINIMUM_XOOPS_VERSION_REQUIRED,
                            $this->module->getInfo('min_xoops'),
                            $xoopsVersion
                        ),
                        'error'
                    );
                } else {
                    $this->addConfigBoxLine(
                        sprintf(
                            \XoopsLocale::F_MINIMUM_XOOPS_VERSION_REQUIRED,
                            $this->module->getInfo('min_xoops'),
                            $xoopsVersion
                        ),
                        'accept'
                    );
                }
            }
            $xoops->tpl()->assign('xo_admin_index_config', $this->itemConfigBoxLine);
        }
        return $xoops->tpl()->fetch($this->getTplPath('index'));
    }

    /**
     * displayIndex
     *
     * @return void
     */
    public function displayIndex()
    {
        echo $this->renderIndex();
    }

    /**
     * Render navigation to admin page
     *
     * @param string $menu current menu
     *
     * @return array
     */
    public function renderNavigation($menu = '')
    {
        $xoops = \Xoops::getInstance();
        $ret = array();

        $this->module->loadAdminMenu();
        foreach (array_keys($this->module->adminmenu) as $i) {
            if ($this->module->adminmenu[$i]['link'] == "admin/" . $menu || $this->module->adminmenu[$i]['link'] == $menu ) {
                /*if (\XoopsLoad::fileExists(
                    $xoops->path("/media/xoops/images/icons/32/" . $this->module->adminmenu[$i]['icon'])
                )) {
                    $this->module->adminmenu[$i]['icon'] = $xoops->url(
                        "/media/xoops/images/icons/32/"
                        . $this->module->adminmenu[$i]['icon']
                    );
                } else {
                    $this->module->adminmenu[$i]['icon'] = $xoops->url(
                        "/modules/" . $xoops->module->dirname() . "/icons/32/"
                        . $this->module->adminmenu[$i]['icon']
                    );
                }
                $xoops->tpl()->assign('xo_sys_navigation', $this->module->adminmenu[$i]);
                $ret[] = $xoops->tpl()->fetch($this->getTplPath('nav'));*/
                $this->renderModuleHeader($this->module->adminmenu[$i]['title'], '', $this->module->adminmenu[$i]['icon']);
            }
        }
        //return $ret;
    }

    public function renderModuleHeader($title, $subheading = '', $icon = ''){
        $xoops = \Xoops::getInstance();

        $header = array(
            'title'         => $title,
            'subheading'    => $subheading,
            'icon'          => $icon,
            'module'        => $this->module ? $this->module->dirname() : ''
        );
        $xoops->tpl()->assign('xo_module_header', $header);

        $commands = HeaderCommands::getInstance();
        $xoops->tpl()->assign('xo_header_commands', $commands->getArrayCopy());
    }

    /**
     * displayNavigation
     *
     * @param string $menu current menu
     *
     * @return void
     */
    public function displayNavigation($menu = '')
    {
        return true;
        /*$items = $this->renderNavigation($menu);
        foreach ($items as $item) {
            echo $item;
        }*/
    }

    /**
     * Render tips to admin page
     *
     * @return string
     */
    public function renderTips()
    {
        return true;
        /*$xoops = \Xoops::getInstance();
        $xoops->tpl()->assign('xo_admin_tips', $this->tips);

        return $xoops->tpl()->fetch($this->getTplPath('tips'));*/
    }

    /**
     * displayTips
     *
     * @return void
     */
    public function displayTips()
    {
        echo $this->renderTips();
    }

    /**
     * Render about page
     *
     * @param bool $logo_xoops show logo
     *
     * @return bool|mixed|string
     */
    public function renderAbout($logo_xoops = true)
    {
        $xoops = \Xoops::getInstance();

        $date = explode('/', $this->module->getInfo('release_date'));
        $author = explode(',', $this->module->getInfo('author'));
        $nickname = explode(',', $this->module->getInfo('nickname'));
        $release_date = \XoopsLocale::formatTimestamp(mktime(0, 0, 0, $date[1], $date[2], $date[0]), 's');

        $author_list = '';
        foreach (array_keys($author) as $i) {
            $author_list .= $author[$i];
            if (isset($nickname[$i]) && $nickname[$i] != '') {
                $author_list .= " (" . $nickname[$i] . "), ";
            } else {
                $author_list .= ", ";
            }
        }
        $changelog = '';
        $language = $xoops->getConfig('locale');
        if (!is_file(
            \XoopsBaseConfig::get('root-path') . "/modules/" . $this->module->getVar("dirname")
            . "/locale/" . $language . "/changelog.txt"
        )) {
            $language = 'en_US';
        }
        $file = \XoopsBaseConfig::get('root-path') . "/modules/" . $this->module->getVar("dirname")
            . "/locale/" . $language . "/changelog.txt";
        if (is_readable($file)) {
            $changelog = utf8_encode(implode("<br />", file($file))) . "\n";
        } else {
            $file = \XoopsBaseConfig::get('root-path') . "/modules/" . $this->module->getVar("dirname") . "/docs/changelog.txt";
            if (is_readable($file)) {
                $changelog = utf8_encode(implode("<br />", file($file))) . "\n";
            }
        }
        $author_list = substr($author_list, 0, -2);

        $this->module->setInfo('release_date', $release_date);
        $this->module->setInfo('author_list', $author_list);
        if (is_array($this->module->getInfo('paypal'))) {
            $this->module->setInfo('paypal', $this->module->getInfo('paypal'));
        }
        $this->module->setInfo('changelog', $changelog);
        $xoops->tpl()->assign('module', $this->module);

        $this->addInfoBox(\XoopsLocale::MODULE_INFORMATION, 'info', 'id="xo-about"');
        $this->addInfoBoxLine(
            \XoopsLocale::C_DESCRIPTION . ' ' . $this->module->getInfo("description"),
            'info'
        );
        $this->addInfoBoxLine(
            \XoopsLocale::C_UPDATE_DATE . ' <span class="bold">'
            . \XoopsLocale::formatTimestamp($this->module->getVar("last_update"), "m")
            . '</span>',
            'info'
        );
        $this->addInfoBoxLine(
            \XoopsLocale::C_WEBSITE . ' <a class="xo-tooltip" href="http://'
            . $this->module->getInfo("module_website_url")
            . '" rel="external" title="'
            . $this->module->getInfo("module_website_name") . ' - '
            . $this->module->getInfo("module_website_url") . '">'
            . $this->module->getInfo("module_website_name") . '</a>',
            'info'
        );

        $xoops->tpl()->assign('xoops_logo', $logo_xoops);
        $xoops->tpl()->assign('xo_admin_box', $this->itemInfoBox);
        return $xoops->tpl()->fetch($this->getTplPath('about'));
    }

    /**
     * displayAbout
     *
     * @param bool $logo_xoops display logo
     *
     * @return void
     */
    public function displayAbout($logo_xoops = true)
    {
        echo $this->renderAbout($logo_xoops);
    }
}
