<?php
/**
 * The router, config and lang class file of ZenTaoPHP.
 *
 * ZenTaoPHP is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * ZenTaoPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoPHP.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @copyright   Copyright 2009, Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@gmail.com>
 * @package     ZenTaoPHP
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
/**
 * ·���࣬Ҳ�������������ĵ��ࡣ
 * 
 * @package ZenTaoPHP
 */
class router
{
    /**
     * �ļ�ϵͳ��·���ָ�����
     * 
     * @var string
     * @access private
     */
    private $pathFix;

    /**
     * Ӧ�õĻ�׼·����
     *
     * @var string
     * @access private
     */
    private $basePath;

    /**
     * ��ܻ����ļ����ڵ�·����
     * 
     * @var string
     * @access private
     */
    private $frameRoot;

    /**
     * ���������libraryĿ¼��
     * 
     * @var string
     * @access private
     */
    private $coreLibRoot;

    /**
     * ��ǰӦ�ó������ڵ�Ŀ¼��
     * 
     * @var string
     * @access private
     */
    private $appRoot;

    /**
     * Ӧ�ó����libraryĿ¼��
     * 
     * @var string
     * @access private
     */
    private $appLibRoot;

    /**
     * �����ļ����ڵĸ�Ŀ¼��
     * 
     * @var string
     * @access private
     */
    private $cacheRoot;

    /**
     * �����ļ����ڵĸ�Ŀ¼��
     * 
     * @var string
     * @access private
     */
    private $configRoot;

    /**
     * ����ģ�����ڵĸ�Ŀ¼��
     * 
     * @var string
     * @access private
     */
    private $moduleRoot;

    /**
     * �����ļ����ڵĸ�Ŀ¼��
     * 
     * @var string
     * @access private
     */
    private $themeRoot;

    /**
     * �û���ʹ�õ����ԡ�
     * 
     * @var string
     * @access private
     */
    private $clientLang;

    /**
     * �û���ʹ�õ����⡣
     * 
     * @var string
     * @access private
     */
    private $clientTheme;

    /**
     * ��ǰ��Ҫ���ص�ģ�����ơ�
     * 
     * @var string
     * @access private
     */
    private $moduleName;

    /**
     * ��ǰģ������Ӧ�Ŀ������ļ���
     * 
     * @var string
     * @access private
     */
    private $controlFile;

    /**
     * ��ǰģ������Ӧ�����������Ŀ������ļ���
     * 
     * @var string
     * @access private
     */
    private $myControlFile;

    /**
     * ��Ҫ���õķ�����
     * 
     * @var string
     * @access private
     */
    private $methodName;

    /**
     * ��ǰ�����URI��
     * 
     * @var string
     * @access private
     */
    private $URI;

    /**
     * Ҫ���ݸ������÷����Ĳ�����
     * 
     * @var array
     * @access private
     */
    private $params;

    /**
     * ��ͼ��ʽ��
     * 
     * @var string
     * @access private
     */
    private $viewType;

    /**
     * ���ö���
     * 
     * @var string
     * @access private
     */
    public $config;

    /**
     * ���Զ���
     * 
     * @var string
     * @access private
     */
    public $lang;

    /**
     * ���ݿ���ʶ���
     * 
     * @var string
     * @access private
     */
    public $dbh;

    /**
     * POST����
     * 
     * @var ojbect
     * @access public
     */
    public $post;

    /**
     * session����
     * 
     * @var ojbect
     * @access public
     */
    public $session;

    /**
     * server����
     * 
     * @var ojbect
     * @access public
     */
    public $server;

    /**
     * global����
     * 
     * @var ojbect
     * @access public
     */
    public $global;

    /**
     * ���캯����
     * 
     * ��Ҫ��ɸ���·�����������á�ע�⣬�ù��캯��Ϊ˽�к�����Ӧ��ʹ��createApp������ʵ����·�ɶ���
     *
     * @param string $appName   Ӧ�õ����ƣ����û��ָ��$appRoot������ϵͳ�����$appName������Ӧ�õĸ�Ŀ¼��
     * @param string $appRoot   Ӧ�����ڵĸ�Ŀ¼��
     * @access protected
     * @return void
     */
    protected function __construct($appName = 'demo', $appRoot = '')
    {
        $this->setPathFix();
        $this->setBasePath();
        $this->setFrameRoot();
        $this->setCoreLibRoot();
        $this->setAppRoot($appName, $appRoot);
        $this->setAppLibRoot();
        $this->setCacheRoot();
        $this->setConfigRoot();
        $this->setModuleRoot();
        $this->setThemeRoot();
    }

    /**
     * ����һ��Ӧ�á�
     * 
     * <code>
     * <?php
     * $demo = router::createApp('demo');
     * ?>
     * ����ָ��demoӦ�����ڵ�Ŀ¼��
     * <?php
     * $demo = router::createApp('demo', '/home/app/demo');
     * ?>
     * </code>
     * @param string $appName   Ӧ�õ�����
     * @param string $appRoot   Ӧ�����ڵĸ�Ŀ¼������Ϊ�ա�
     * @param string $className �������ƣ�����router����һ�����࣬Ȼ����ø÷���ʱ������ָ���ò�����
     * @static
     * @access public
     * @return void
     */
    public static function createApp($appName = 'demo', $appRoot = '', $className = 'router')
    {
        if(empty($className)) $className = __CLASS__;
        return new $className($appName, $appRoot);
    }

    //-------------------- ·����صķ�����--------------------//

    /**
     * ����·���ָ�����������á�
     * 
     * @access protected
     * @return void
     */
    protected function setPathFix()
    {
        $this->pathFix = DIRECTORY_SEPARATOR;
    }
    
    /**
     * ��������������ڵĸ�Ŀ¼��
     *
     * @access protected
     * @return void
     */
    protected function setBasePath()
    {
        $this->basePath = realpath(dirname(dirname(__FILE__))) . $this->pathFix;
    }
    
    /**
     * ���ÿ�ܺ������ļ����ڵĸ�Ŀ¼��
     * 
     * @access protected
     * @return void
     */
    protected function setFrameRoot()
    {
        $this->frameRoot = $this->basePath . 'framework' . $this->pathFix;
    }

    /**
     * ����coreLib�ļ��ĸ�Ŀ¼��
     * 
     * @access protected
     * @return void
     */
    protected function setCoreLibRoot()
    {
        $this->coreLibRoot = $this->basePath . 'lib' . $this->pathFix;
    }

    /**
     * ����Ӧ�ó������ڵĸ�Ŀ¼��
     *
     * Ĭ������������appName�����м��㣬���ָ����appRoot��ֱ����֮��
     * ͨ�����ֻ��ƣ���ܺ�Ӧ�ÿ��Էֿ�����
     *
     * @param string $appName 
     * @param string $appRoot 
     * @access protected
     * @return void
     */
    protected function setAppRoot($appName = 'demo', $appRoot = '')
    {
        if(empty($appRoot))
        {
            $this->appRoot = $this->basePath . 'app' . $this->pathFix . $appName . $this->pathFix;
        }
        else
        {
            $this->appRoot = realpath($appRoot) . $this->pathFix;
        }
        if(!is_dir($this->appRoot)) $this->error("The app you call not noud in {$this->appRoot}", __FILE__, __LINE__, $exit = true);
    }

    /**
     * ����appLib�ļ��ĸ�Ŀ¼��
     * 
     * @access protected
     * @return void
     */
    protected function setAppLibRoot()
    {
        $this->appLibRoot = $this->appRoot . 'lib' . $this->pathFix;
    }

    /**
     * ���û����ļ����ڵĸ�Ŀ¼��
     * 
     * @access protected
     * @return void
     */
    protected function setCacheRoot()
    {
        $this->cacheRoot = $this->appRoot . 'cache' . $this->pathFix;
    }

    /**
     * ���������ļ����ڵĸ�Ŀ¼��
     * 
     * @access protected
     * @return void
     */
    protected function setConfigRoot()
    {
        $this->configRoot = $this->appRoot . 'config' . $this->pathFix;
    }

    /**
     * ����module���ڵĸ�Ŀ¼��
     * 
     * @access protected
     * @return void
     */
    protected function setModuleRoot()
    {
        $this->moduleRoot = $this->appRoot . 'module' . $this->pathFix;
    }

    /**
     * ���ÿͻ��������ļ����ڵĸ�Ŀ¼��
     * 
     * @access protected
     * @return void
     */
    protected function setThemeRoot()
    {
        $this->themeRoot = $this->appRoot . 'www' . $this->pathFix . 'theme' . $this->pathFix;
    }

    /**
     * ���ó�ȫ�ֱ�����
     * 
     * @access protected
     * @return void
     */
    public function setSuperVars()
    {
        if(isset($this->config->super2OBJ) and $this->config->super2OBJ)
        {
            $this->post    = new super('post');
            $this->server  = new super('server');
            $this->session = new super('session');
            $this->global  = new super('global');
        }
    }

    /**
     * ����·���ָ�����
     * 
     * @access public
     * @return string
     */
    public function getPathFix()
    {
        return $this->pathFix;
    }

    /**
     * ����������ܵ����ڵ�Ŀ¼��
     * 
     * @access public
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }
    
    /**
     * ���ؿ�ܺ������ļ����ڵĸ�Ŀ¼��
     * 
     * @access public
     * @return string
     */
    public function getFrameRoot()
    {
        return $this->frameRoot;
    }

    /**
     * ����lib�ļ����ڵĸ�Ŀ¼��
     * 
     * @access public
     * @return string
     */
    public function getCoreLibRoot()
    {
        return $this->coreLibRoot;
    }

    /**
     * ����Ӧ�ó������ڵĸ�Ŀ¼��
     * 
     * @access public
     * @return string
     */
    public function getAppRoot()
    {
        return $this->appRoot;
    }
    
    /**
     * ����appLib�ļ����ڵĸ�Ŀ¼��
     * 
     * @access public
     * @return string
     */
    public function getAppLibRoot()
    {
        return $this->appLibRoot;
    }

    /**
     * ���ػ����ļ����ڵĸ�Ŀ¼��
     * 
     * @access public
     * @return string
     */
    public function getCacheRoot()
    {
        return $this->cacheRoot;
    } 

    /**
     * ���������ļ����ڵĸ�Ŀ¼��
     * 
     * @access public
     * @return string
     */
    public function getConfigRoot()
    {
        return $this->configRoot;
    }

    /**
     * ����ģ���ļ����ڵĸ�Ŀ¼��
     * 
     * @access public
     * @return string
     */
    public function getModuleRoot()
    {
        return $this->moduleRoot;
    }

    /**
     * ���������ļ����ڵĸ�Ŀ¼��
     * 
     * @access public
     * @return string
     */
    public function getThemeRoot()
    {
        return $this->themeRoot;
    }

    //-------------------- �ͻ��˻������á�--------------------//

    /**
     * ���ÿͻ�����ʹ�õ����ԡ�
     * 
     * ����ֹ�ָ�������Ե�ѡ������ֹ�ָ��Ϊ����
     * Ȼ���ٲ���session�����Ƿ��еǼǣ�
     * Ȼ���ٿ�cookie�����Ƿ��еǼǡ�
     * Ȼ���ٲ鿴�����֧�ֵ����ԣ�
     * ���ͨ������ȡ��������ѡ���ϵͳ֧�ֵĲ�ƥ�䣬��ʹ��Ĭ�ϵ����ԡ�
     *
     * @param   string $lang  ����zh-cn|zh-tw|zh-hk|en��
     * @access  public
     * @return  void
     */
    public function setClientLang($lang = '')
    {
        if(!empty($lang))
        {
            $this->clientLang = $lang;
        }
        elseif(isset($_SESSION['lang']))
        {
            $this->clientLang = $_SESSION['lang'];
        }
        elseif(isset($_COOKIE['lang']))
        {
            $this->clientLang = $_COOKIE['lang'];
        }    
        elseif(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
        {
            $this->clientLang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, strpos($_SERVER['HTTP_ACCEPT_LANGUAGE'], ','));
        }
        if(!empty($this->clientLang))
        {
            $this->clientLang = strtolower($this->clientLang);
            if(strpos($this->config->langs, $this->clientLang) === false)
            {
                $this->clientLang = $this->config->default->lang;
            }
        }    
        else
        {
            $this->clientLang = $this->config->default->lang;
        }
        setcookie('lang', $this->clientLang, $this->config->cookieLife, $this->config->cookiePath);
    }

    /**
     * ���ؿͻ���ʹ�õ����ԡ�
     * 
     * @access public
     * @return string
     */
    public function getClientLang()
    {
        return $this->clientLang;
    }

    /**
     * ���ÿͻ�����ʹ�õ����⡣�߼�ͬsetClientLang();
     *
     * ����������Ӧ����ʽ��ͼƬ���ļ�Ӧ�������www/theme/����Ŀ¼��š�Ŀ¼�����־��Ƿ������֡�
     * 
     * @param   string $theme   ������
     * @access  public
     * @return  void
     */
    public function setClientTheme($theme = '')
    {
        if(!empty($theme))
        {
            $this->clientTheme = $theme;
        }
        elseif(isset($_SESSION['theme']))
        {
            $this->clientTheme = $_SESSION['theme'];
        }
        elseif(isset($_COOKIE['theme']))
        {
            $this->clientTheme = $_COOKIE['theme'];
        }    
        if(!empty($this->clientTheme))
        {
            $this->clientTheme = strtolower($this->clientTheme);
            if(strpos($this->config->themes, $this->clientTheme) === false)
            {
                $this->clientTheme = $this->config->default->theme;
            }
        }    
        else
        {
            $this->clientTheme = $this->config->default->theme;
        }
        setcookie('theme', $this->clientTheme, $this->config->cookieLife, $this->config->cookiePath);
    }

    /**
     * ���ؿͻ�����ʹ�õ����⡣
     * 
     * @access public
     * @return string
     */
    public function getClientTheme()
    {
        return $this->config->webRoot . 'theme/' . $this->clientTheme . '/';
    }

    /**
     * ����web�ĸ�Ŀ¼��
     * 
     * @access public
     * @return string
     */
    public function getWebRoot()
    {
        return $this->config->webRoot;
    }

    //-------------------- ��������--------------------//

    /**
     * �������󣬷�ΪPATH_INFO��GET����ģʽ��
     * 
     * @access public
     * @return void
     */
    public function parseRequest()
    {
        if($this->config->requestType == 'PATH_INFO')
        {
            $this->parsePathInfo();
            $this->setRouteByPathInfo();
        }
        elseif($this->config->requestType == 'GET')
        {
            $this->parseGET();
            $this->setRouteByGET();
        }
        else
        {
            $this->error("The request type {$this->config->requestType} not supported", __FILE__, __LINE__, $exit = true);
        }
    }

    /**
     * �������еó�PATH_INFO��Ϣ�� 
     * 
     * @access public
     * @return void
     */
    public function parsePathInfo()
    {
        $pathInfo = $this->getPathInfo('PATH_INFO');
        if(!empty($pathInfo))
        {
            $dotPos = strpos($pathInfo, '.');
            if($dotPos)
            {
                $this->URI      = substr($pathInfo, 0, $dotPos);
                $this->viewType = substr($pathInfo, $dotPos + 1);
                if(strpos($this->config->views, ',' . $this->viewType . ',') === false)
                {
                    $this->viewType = $this->config->default->view;
                }
            }
            else
            {
                $this->URI      = $pathInfo;
                $this->viewType = $this->config->default->view;
            }
        }
        else
        {
            $this->viewType = $this->config->default->view;
        }
    }

    /**
     * ������������env����_SERVER�����л�ȡĳ��PATH_INFO�ı��֡�
     * 
     * @param   string  $varName     Ŀǰ֧��PATH_INFO
     * @access  private
     * @return  string
     */
    private function getPathInfo($varName)
    {
        $value = @getenv($varName);
        if(isset($_SERVER[$varName])) $value = $_SERVER[$varName];
        return trim($value, '/');
    }

    /**
     * ����ͨ��GET��ʽ���ݹ����Ĳ�����
     * 
     * @access private
     * @return void
     */
    private function parseGET()
    {
        if(isset($_GET[$this->config->viewVar]))
        {
            $this->viewType = $_GET[$this->config->viewVar]; 
            if(strpos($this->config->views, ',' . $this->viewType . ',') === false)
            {
                $this->viewType = $this->config->default->view;
            }
        }
        else
        {
            $this->viewType = $this->config->default->view;
        }
        $this->URI = $_SERVER['REQUEST_URI'];
    }
    
    /**
     * ���ص�ǰ�����URI��
     * 
     * @access public
     * @return string
     */
    public function getURI($full = false)
    {
        if($full) return $this->config->webRoot . $this->URI . '.' . $this->viewType;
        return $this->URI;
    }

    /**
     * ���ص�ǰ�����viewType��
     * 
     * @access public
     * @return string
     */
    public function getViewType()
    {
        return $this->viewType;
    }

    //-------------------- ·����صķ�����--------------------//

    /**
     * ���ع�����commonģ�顣
     *
     * �ù���ģ����������һЩ���õ����񣬱�������session�������û�Ȩ����֤�ȡ�
     * �÷���û���Զ����ã������Ҫ��������router�ļ����Լ�������߼���
     * ����Ҫע����Ǹ÷�������Ӧ����lang, config, dbh�ȶ���������֮��
     *
     * @access public
     * @return object
     */
    public function loadCommon()
    {
        $this->setModuleName('common');
        if($this->setControlFile($exitIfNone = false))
        {
            include $this->controlFile;
            if(class_exists('common'))
            {
                return new common();
            }    
            else
            {
                return false;
            }
        }
    }

    /**
     * ����Ҫ���õ�ģ�顣
     * 
     * @param   string $moduleName  ģ�����֡�
     * @access  public
     * @return  void
     */
    public function setModuleName($moduleName = '')
    {
        $this->moduleName = strtolower($moduleName);
    }

    /**
     * ����Ҫ���صĿ������ļ���
     * 
     * @param   bool    $exitIfNone     ���û�з��ֿ������ļ����Ƿ��˳���Ĭ��ֵtrue��
     * @access  public
     * @return  bool
     */
    public function setControlFile($exitIfNone = true)
    {
        $this->controlFile = $this->moduleRoot . $this->moduleName . $this->pathFix . 'control.php';
        if(!file_exists($this->controlFile))
        {
            $this->error("the control file $this->controlFile not found.", __FILE__, __LINE__, $exitIfNone);
            return false;
        }    
        return true;
    }
    
    /**
     * �ж��Ƿ���������Ŀ��������ļ���
     * 
     * @access  public
     * @return  bool
     */
    public function setMyControlFile()
    {
        $this->myControlFile = $this->moduleRoot . $this->moduleName . $this->pathFix . 'mycontrol.php'; 
        if(file_exists($this->myControlFile)) return true;
        return false;
    }

    /**
     * ����Ҫ���õķ�����
     * 
     * @param string $methodName    ���õķ������� 
     * @access public
     * @return void
     */
    public function setMethodName($methodName = '')
    {
        $this->methodName = strtolower($methodName);
    }

    /**
     * ����PATH_INFO��Ϣ����·�ɡ�
     * 
     * @access public
     * @return void
     */
    public function setRouteByPathInfo()
    {
        if(!empty($this->URI))
        {
            /* URL�к��в�����Ϣ��*/
            if(strpos($this->URI, $this->config->requestFix) !== false)
            {
                $items = explode($this->config->requestFix, $this->URI);
                $this->setModuleName($items[0]);
                $this->setMethodName($items[1]);
            }    
            else
            {
                $this->setModuleName($this->URI);
                $this->setMethodName($this->config->default->method); // ȡĬ�ϵķ�����
            }
        }
        else
        {    
            $this->setModuleName($this->config->default->module);   // ȡĬ�ϵ�ģ�顣
            $this->setMethodName($this->config->default->method);   // ȡĬ�ϵķ�����
        }
        $this->setControlFile();
    }

    /**
     * ͨ��GET����������·����Ϣ��
     * 
     * @access public
     * @return void
     */
    public function setRouteByGET()
    {
        $moduleName = isset($_GET[$this->config->moduleVar]) ? strtolower($_GET[$this->config->moduleVar]) : $this->config->default->module;
        $methodName = isset($_GET[$this->config->methodVar]) ? strtolower($_GET[$this->config->methodVar]) : $this->config->default->method;
        $this->setModuleName($moduleName);
        $this->setControlFile();
        $this->setMethodName($methodName);
    }

    /**
     * ����ģ�顣
     * 
     * @access public
     * @return void
     */
    public function loadModule()
    {
        chdir(dirname($this->controlFile));
        include $this->controlFile;
        $moduleName = $this->moduleName;
        if($this->setMyControlFile())
        {
            include $this->myControlFile;
            $moduleName = 'my' . $moduleName;
        }
        $methodName = $this->methodName;
        if(!class_exists($moduleName)) $this->error("the control $moduleName not found", __FILE__, __LINE__, $exit = true);
        $module = new $moduleName();
        if(!method_exists($module, $methodName)) $this->error("the module $moduleName has no $methodName method", __FILE__, __LINE__, $exit = true);

        /* ��ȡ�����Ĳ������塣*/
        $defaultParams = array();
        $methodReflect = new reflectionMethod($moduleName, $methodName);
        foreach($methodReflect->getParameters() as $param)
        {
            $name    = $param->getName();
            $default = $param->isDefaultValueAvailable() ? $param->getDefaultValue() : '_NOT_SET';
            $defaultParams[$name] = $default;
        }

        if($this->config->requestType == 'PATH_INFO')
        {
            $this->setParamsByPathInfo($defaultParams);
        }
        elseif($this->config->requestType == 'GET')
        {
            $this->setParamsByGET($defaultParams);
        }

        call_user_func_array(array(&$module, $methodName), $this->params);
        return $module;
    }

    /**
     * ͨ��PATH_INFO��Ϣ��������Ҫ���ݸ�control�෽���Ĳ�����
     * 
     * @param   array $defaultParams    ����������Ĭ��ֵ�б�
     * @access  public
     * @return  void
     */
    public function setParamsByPathInfo($defaultParams = array())
    {
        /* �����󴮰��շָ���ֿ���*/
        $items     = explode($this->config->requestFix, $this->URI);
        $itemCount = count($items);

        /**
         * itemsǰ������Ԫ�طֱ�ΪmoduleName��methodName����˴ӵڶ���Ԫ�ؿ�ʼ��
         * �ֱ�Ϊcleanģʽ��fullģʽ��
         */ 

        $params = array();
        if($this->config->pathType == 'clean')
        {
            for($i = 2; $i < $itemCount; $i ++)
            {
                $key = key($defaultParams);
                $params[$key] = $items[$i];
                next($defaultParams);
            }
        }
        elseif($this->config->pathType == 'full')
        {
            for($i = 2; $i < $itemCount; $i += 2)
            {
                $key   = $items[$i];
                $value = $items[$i + 1];
                $params[$key] = $value;
            }
        }
        $this->params = $this->mergeParams($defaultParams, $params);
    }

    /**
     * ͨ��GET����������Ҫ���ݸ�control����ʵĲ�����
     * 
     * @param   array   $defaultParams  ����������Ĭ��ֵ�б�
     * @access  public
     * @return  void
     */
    public function setParamsByGET($defaultParams)
    {
        unset($_GET[$this->config->moduleVar]);
        unset($_GET[$this->config->methodVar]);
        unset($_GET[$this->config->viewVar]);
        $this->params = $this->mergeParams($defaultParams, $_GET);
    }

    /**
     * �����������е�Ĭ��ֵ���û������д��ݵ�ֵ�ϲ�������
     *
     * @param   array $defaultParams     ���������еĲ���Ĭ��ֵ�б�
     * @param   array $passedParams      �û������д��ݵĲ����б�
     * @access  private
     * @return  array
     */
    private function mergeParams($defaultParams, $passedParams)
    {
        /* ������������Ƿ��ϸ�ģʽ����ΪpassedParams��˳���defaultParams��һ�µġ�*/
        if(!isset($this->config->strictParams) or $this->config->strictParams == false) 
        {
            $passedParams = array_values($passedParams);
            $i = 0;
            foreach($defaultParams as $key => $defaultValue)
            {
                if(isset($passedParams[$i]))
                {
                    $defaultParams[$key] = $passedParams[$i];
                }
                else
                {
                    if($defaultValue === '_NOT_SET') $this->error("The param '$key' should pass value. ", __FILE__, __LINE__, $exit = true);
                }
                $i ++;
            }
        }
        else
        {
            foreach($defaultParams as $key => $defaultValue)
            {
                if(isset($passedParams[$key]))
                {
                    $defaultParams[$key] = $passedParams[$key];
                }
                else
                {
                    if($defaultValue === '_NOT_SET') $this->error("The param '$key' should pass value. ", __FILE__, __LINE__, $exit = true);
                }
            }
        }
        return $defaultParams;
    }
 
    /**
     * ���ص�ǰ���õ�ģ�����ơ�
     * 
     * @access public
     * @return string
     */
    public function getModuleName()
    {
        return $this->moduleName;
    }

    /**
     * ����control�ļ�·����
     * 
     * @access public
     * @return string
     */
    public function getControlFile()
    {
        return $this->controlFile;
    }

    /**
     * ���ص�ǰ���õ�control�ķ�����
     * 
     * @access public
     * @return string
     */
    public function getMethodName()
    {
        return $this->methodName;
    }

    /**
     * ���ص�ǰ���ݵĲ�������
     * 
     * @access public
     * @return string
     */
    public function getParams()
    {
        return $this->params;
    }


    //-------------------- �����෽����-------------------//

    /**
     * ����������
     * 
     * @param string    $message    ������Ϣ��
     * @param string    $file       ����������ļ�����
     * @param int       $line       ����������кš�
     * @param bool      $exit       �Ƿ���ֹ����
     * @access public
     * @return void
     */
    public function error($message, $file, $line, $exit = false)
    {
        /* ��¼������Ϣ��*/
        $log = "ERROR: $message in $file on line $line";
        if(isset($_SERVER['SCRIPT_URI'])) $log .= ", request: $_SERVER[SCRIPT_URI]";; 
        $trace = debug_backtrace();
        extract($trace[0]);
        extract($trace[1]);
        $log .= ", last called by $file on $line through function $function.";
        error_log($log);

        /* �����Ҫ��ֹ��������ʾ���ն��û�����Ļ�ϡ�*/
        if($exit)
        {
            if($this->config->debug) die($log);
            die();
        }
    }

    /**
     * ����ĳһ�����ļ���
     * 
     * �÷��������ȳ��Դ�appLibRoot������ң�Ȼ���ٵ�coreLibRoot������ҡ�
     *
     * @param mixed $className  ������ 
     * @param mixed $static     �Ƿ�Ϊ��̬�ࡣ
     * @access public
     * @return object           ������Ϊ���Ķ���
     */
    public function loadClass($className, $static = false)
    {
        $className = strtolower($className);

        /* �����ż���appLibRoot��������ļ���*/
        $classFile = $this->appLibRoot . $className;
        if(is_dir($classFile)) $classFile .= $this->pathFix . $className;
        $classFile .= '.class.php';

        if(!file_exists($classFile)) 
        {
            /* �����ż���coreLibRoot��������ļ���*/
            $classFile = $this->coreLibRoot . $className;
            if(is_dir($classFile)) $classFile .= $this->pathFix . $className;
            $classFile .= '.class.php';
            if(!file_exists($classFile)) $this->error("class file $classFile not found", __FILE__, __LINE__, $exit = true);
        }

        helper::import($classFile);

        /* ����Ǿ�̬��Ļ�������ʵ������ֱ���˳���*/
        if($static) return;

        /* ʵ���������ɶ���*/
        global $$className;
        if(!class_exists($className)) $this->error("the class $className not found in $classFile", __FILE__, __LINE__, $exit = true);
        if(!is_object($$className)) $$className = new $className();
        return $$className;
    }

    /**
     * ���������ļ�������ת��Ϊ���󣬲�������Ϊȫ�ֵ����ö���
     * 
     * ���ģ�������Ϊcommon��������õĸ�Ŀ¼���ң�������ģ�����ģ��·��������ҡ�
     *
     * @param mixed $moduleName     ģ������֡�
     * @param bool  $exitIfNone     ��������ļ������ڣ��Ƿ��˳���
     * @access public
     * @return object
     */
    public function loadConfig($moduleName, $exitIfNone = true)
    {
        /* ����ģ������Ӧ�������ļ�·����*/
        if($moduleName == 'common')
        {
            $configFile = $this->configRoot . 'config.php';
        }
        else
        {
            $configFile = $this->moduleRoot . $moduleName . $this->pathFix . 'config.php';
        }
        if(!file_exists($configFile))
        {
            self::error("config file $configFile not found", __FILE__, __LINE__, $exitIfNone);
            return;
        }

        static $loadedConfigs = array();
        if(in_array($configFile, $loadedConfigs)) return;
        $loadedConfigs[] = $configFile;

        global $config;
        if(!is_object($config)) $config = new config();
        include $configFile;
        $this->config = $config;
        return $config;
    }

    /**
     * ���������ļ�������ת��Ϊ���󣬲�������Ϊȫ�ֵ����Զ���
     * 
     * @param mixed $moduleName 
     * @access public
     * @return void
     */
    public function loadLang($moduleName)
    {
        $langFile = $this->moduleRoot . $moduleName . $this->pathFix . 'lang' . $this->pathFix . $this->clientLang . '.php';
        if(!file_exists($langFile))
        {
            self::error("language file $langFile not found", __FILE__, __LINE__);
            return false;
        }    

        static $loadedLangs = array();
        if(in_array($langFile, $loadedLangs)) return;
        $loadedLangs[] = $langFile;

        /* ����lang����*/
        global $lang;
        if(!is_object($lang)) $lang = new language();
        include $langFile;
        if(!isset($lang) or empty($lang)) return false;
        $this->lang = $lang;
        return $lang;
    }

    /**
     * ���ӵ����ݿ⣬����$dbh����
     * 
     * @access public
     * @return object
     */
    public function connectDB()
    {
        global $config;
        if(!isset($config->db->driver)) self::error('no pdo driver defined, it should be mysql or sqlite', __FILE__, __LINE__, $exit = true);
        if($config->db->driver == 'mysql')
        {
            $dsn = "mysql:host={$config->db->host}; port={$config->db->port}; dbname={$config->db->name}";
        }    
        try 
        {
            $dbh = new PDO($dsn, $config->db->user, $config->db->password, array(PDO::ATTR_PERSISTENT => $config->db->persistant));
            $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, $config->db->errorMode);
            $dbh->exec("SET NAMES {$config->db->encoding}");
            $this->dbh = $dbh;
            return $dbh;
        }
        catch (PDOException $exception)
        {
            self::error($exception->getMessage(), __FILE__, __LINE__, $exit = true);
        }
    }
}

/**
 * �����ࡣ
 * 
 * @package ZenTaoPHP
 */
class config
{ 
    /**
     * ���ö����ֵ��
     * 
     * <code>
     * <?php
     * $config->set('db.user', 'wwccss'); 
     * ?>
     * </code>
     * @param   string  $key    Ҫ���õ����ԣ�������father.child����ʽ��
     * @param   mixed   $value  Ҫ���õ�ֵ��
     * @access  public
     * @return  void
     */
    public function set($key, $value)
    {
        helper::setMember('config', $key, $value);
    }
}

/**
 * �����ࡣ
 * 
 * @package ZenTaoPHP
 */
class language 
{
    /**
     * ���ö����ֵ��
     * 
     * <code>
     * <?php
     * $lang->set('version', '1.0�汾'); 
     * ?>
     * </code>
     * @param   string  $key    Ҫ���õ����ԣ�������father.child����ʽ��
     * @param   mixed   $value  Ҫ���õ�ֵ��
     * @access  public
     * @return  void
     */
    public function set($key, $value)
    {
        helper::setMember('lang', $key, $value);
    }

    /**
     * ��ӡĳһ����������ԡ�
     */
    public function show($obj, $key)
    {
        $obj = (array)$obj;
        if(isset($obj[$key])) echo $obj[$key]; else echo '';
    }
}

/**
 * ��ȫ�ֱ����ࡣ
 * 
 * @package ZenTaoPHP
 */
class super
{
    /* ���캯����*/
    public function __construct($scope)
    {
        $this->scope = $scope;
    }

    /* �������ԡ�*/
    public function set($key, $value)
    {
        if($this->scope == 'post')
        {
            $_POST[$key] = $value;
        }
        elseif($this->scope == 'get')
        {
            $_GET[$key] = $value;
        }
        elseif($this->scope == 'server')
        {
            $_SERVER[$key] = $value;
        }
        elseif($this->scope == 'cookie')
        {
            $_COOKIE[$key] = $value;
        }
        elseif($this->scope == 'session')
        {
            $_SESSION[$key] = $value;
        }
        elseif($this->scope == 'env')
        {
            $_ENV[$key] = $value;
        }
        elseif($this->scope == 'global')
        {
            $GLOBAL[$key] = $value;
        }
    }

    /* ħ��������*/
    public function __get($key)
    {
        if($this->scope == 'post')
        {
            if(isset($_POST[$key])) return $_POST[$key];
            return false;
        }
        elseif($this->scope == 'get')
        {
            if(isset($_GET[$key])) return $_GET[$key];
            return false;
        }
        elseif($this->scope == 'server')
        {
            if(isset($_SERVER[$key])) return $_SERVER[$key];
            return false;
        }
        elseif($this->scope == 'cookie')
        {
            if(isset($_COOKIE[$key])) return $_COOKIE[$key];
            return false;
        }
        elseif($this->scope == 'session')
        {
            if(isset($_SESSION[$key])) return $_SESSION[$key];
            return false;
        }
        elseif($this->scope == 'env')
        {
            if(isset($_ENV[$key])) return $_ENV[$key];
            return false;
        }
        elseif($this->scope == 'global')
        {
            if(isset($GLOBALS[$key])) return $GLOBALS[$key];
            return false;
        }
        else
        {
            return false;
        }
    }
}

