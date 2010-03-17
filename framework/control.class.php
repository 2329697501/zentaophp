<?php
/**
 * The control class file of ZenTaoPHP.
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
 * @copyright   Copyright 2009-2010, Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@gmail.com>
 * @package     ZenTaoPHP
 * @version     $Id$
 * @link        http://www.zentao.cn
 */

/**
 * ���������ࡣ
 * 
 * @package ZenTaoPHP
 */
class control
{
    /**
     * ȫ�ֵ�$app����
     * 
     * @var object
     * @access protected
     */
    protected $app;

    /**
     * ȫ�ֵ�$config���� 
     * 
     * @var object
     * @access protected
     */
    protected $config;

    /**
     * ȫ�ֵ�$lang����
     * 
     * @var object
     * @access protected
     */
    protected $lang;

    /**
     * ȫ�ֵ�$dbh�����ݿ���ʾ��������
     * 
     * @var object
     * @access protected
     */
    protected $dbh;

    /**
     * dao����
     * 
     * @var object
     * @access protected
     */
    public $dao;

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
     * ����ģ������֡�
     * 
     * @var string
     * @access protected
     */
    protected $moduleName;

    /**
     * ģ��������·����
     * 
     * @var string
     * @access protected
     */
    protected $modulePath;

    /**
     * Ҫ���ص�model�ļ���
     * 
     * @var string
     * @access private
     */
    private $modelFile;

    /**
     * ������model�ļ���
     * 
     * @var string
     * @access private
     */
    private $myModelFile;

    /**
     * ��¼��ֵ��view�����б�����
     * 
     * @var object
     * @access public
     */
    public $view; 

    /**
     * ��ͼ����
     * 
     * @var string
     * @access private
     */
    private $viewType;

    /**
     * Ҫ���ص�view�ļ���
     * 
     * @var string
     * @access private
     */
    private $viewFile;

    /**
     * Ҫ��������ݡ�
     * 
     * @var string
     * @access private
     */
    private $output;

    /**
     * ·���ָ�����
     * 
     * @var string
     * @access protected
     */
    protected $pathFix;

    /**
     * ���캯����
     *
     * 1. ����ȫ�ֶ���ʹ֮����ͨ����Ա�������ʡ�
     * 2. ����ģ����Ӧ��·����Ϣ�������ض�Ӧ��model�ļ���
     * 3. �Զ���$lang��$config��ֵ��ģ�塣
     * 
     * @access public
     * @return void
     */
    public function __construct($moduleName = '', $methodName = '')
    {
        /* ����ȫ�ֶ��󣬲���ֵ��*/
        global $app, $config, $lang, $dbh;
        $this->app        = $app;
        $this->config     = $config;
        $this->lang       = $lang;
        $this->dbh        = $dbh;
        $this->pathFix    = $this->app->getPathFix();
        $this->viewType   = $this->app->getViewType();

        $this->setModuleName($moduleName);
        $this->setModulePath();
        $this->setMethodName($methodName);

        /* �Զ����ص�ǰģ���model�ļ���*/
        $this->loadModel();

        /* �Զ���$app, $config��$lang��ֵ��ģ���С�*/
        $this->assign('app',    $app);
        $this->assign('lang',   $lang);
        $this->assign('config', $config);

        if(isset($config->super2OBJ) and $config->super2OBJ) $this->setSuperVars();
    }

    //-------------------- model��صķ�����--------------------//
    //
    /* ����ģ������*/
    private function setModuleName($moduleName = '')
    {
        $this->moduleName = $moduleName ? strtolower($moduleName) : $this->app->getModuleName();
    }

    /* ����ģ��·����*/
    private function setModulePath()
    {
        $this->modulePath = $this->app->getModuleRoot() . $this->moduleName . $this->pathFix;
    }

    /* ���÷�������*/
    private function setMethodName($methodName = '')
    {
        $this->methodName = $methodName ? strtolower($methodName) : $this->app->getMethodName();
    }

    /**
     * ����model�ļ���
     * 
     * @param   string      $moduleName     ģ�����֡�
     * @access  private
     * @return void
     */
    private function setModelFile($moduleName)
    {
        $this->modelFile = $this->app->getModuleRoot() . strtolower(trim($moduleName)) . $this->pathFix . 'model.php';
        return file_exists($this->modelFile);
    }

    /**
     * ����������model�ļ���
     * 
     * @param   string      $moduleName     ģ�����֡�
     * @access  private
     * @return void
     */
    private function setMyModelFile()
    {
        $this->myModelFile = str_replace('model.php', 'mymodel.php', $this->modelFile);
        return file_exists($this->myModelFile);
    }

    /**
     * ����ĳһ��ģ���model�ļ���
     * 
     * @param   string  $moduleName     ģ�����֣����Ϊ�գ���ȡ��ǰ��ģ������Ϊmodel����
     * @access  public
     * @return  void
     */
    public function loadModel($moduleName = '')
    {
        /* ���û��ָ��module������ȡ��ǰ���ص�ģ�������Ϊmodel����*/
        if(empty($moduleName)) $moduleName = $this->moduleName;
        if(!$this->setModelFile($moduleName)) return false;

        $modelClass = $moduleName. 'Model';
        helper::import($this->modelFile);
        
        /* ����������model�ļ�������ء�*/
        if($this->setMyModelFile())
        {
            helper::import($this->myModelFile);
            $modelClass = 'my' . $modelClass;
        }

        if(!class_exists($modelClass)) $this->app->error(" The model $modelClass not found", __FILE__, __LINE__, $exit = true);
        $this->$moduleName = new $modelClass();
        if(isset($this->config->db->dao) and $this->config->db->dao)
        {
            $this->dao = $this->$moduleName->dao;
        }
        return $this->$moduleName;
    }

    /**
     * ���ó�ȫ�ֱ�����
     * 
     * @access protected
     * @return void
     */
    protected function setSuperVars()
    {
        $this->post    = $this->app->post;
        $this->server  = $this->app->server;
        $this->session = $this->app->session;
        $this->global  = $this->app->global;
    }

    //-------------------- ����view��صķ�����--------------------//
    /**
     * ������ͼ�ļ���
     * 
     * ĳһ��module�Ŀ��������Լ�������һ��module����ͼ�ļ���
     *
     * @param string $moduleName    ģ������
     * @param string $methodName    ��������
     * @access private
     * @return string               ��Ӧ����ͼ�ļ���
     */
    private function setViewFile($moduleName, $methodName)
    {
        $moduleName = strtolower(trim($moduleName));
        $methodName = strtolower(trim($methodName));

        $modulePath   = $this->app->getModuleRoot() . strtolower($moduleName) . $this->pathFix;
        $viewExtPath  = $modulePath . "opt{$this->pathFix}view{$this->pathFix}";

        /* ����ͼ�ļ�����չ��ͼ�ļ�����չ�����ļ���*/
        $mainViewFile = $modulePath . 'view' . $this->pathFix . $methodName . '.' . $this->viewType . '.php';
        $extViewFile  = $viewExtPath . $methodName . ".{$this->viewType}.php";
        $extHookFile  = $viewExtPath . $methodName . ".{$this->viewType}.hook.php";

        $viewFile = file_exists($extViewFile) ? $extViewFile : $mainViewFile;
        if(!file_exists($viewFile)) $this->app->error("the view file $viewFile not found", __FILE__, __LINE__, $exit = true);
        if(file_exists($extHookFile)) return array('viewFile' => $viewFile, 'hookFile' => $extHookFile);
        return $viewFile;
    }

    /**
     * ��ֵһ��������view��ͼ��
     * 
     * @param   string  $name       ��ֵ����ͼ�ļ��еı�������
     * @param   mixed   $value      ����Ӧ��ֵ��
     * @access  public
     * @return  void
     */
    public function assign($name, $value)
    {
        $this->view->$name = $value;
    }

    /**
     * ����output���ݡ�
     * 
     * @access public
     * @return void
     */
    public function clear()
    {
        $this->output = '';
    }

    /**
     * ������ͼ�ļ���
     *
     * ���û��ָ��ģ�����ͷ���������ȡ��ǰģ��ĵ�ǰ������
     *
     * @param string $moduleName    ģ������
     * @param string $methodName    ��������
     * @access public
     * @return void
     */
    public function parse($moduleName = '', $methodName = '')
    {
        if(empty($moduleName)) $moduleName = $this->moduleName;
        if(empty($methodName)) $methodName = $this->methodName;

        if($this->viewType == 'json') $this->parseJSON($moduleName, $methodName);
        if($this->viewType == 'html') $this->parseHtml($moduleName, $methodName);
        return $this->output;
    }

    /* ����JSON��ʽ�������*/
    private function parseJSON($moduleName, $methodName)
    {
        unset($this->view->app);
        unset($this->view->config);
        unset($this->view->lang);
        unset($this->view->pager);
        unset($this->view->header);
        unset($this->view->position);
        $this->output = json_encode($this->view);
    }

    /* HTML��ʽ��*/
    private function parseHtml($moduleName, $methodName)
    {
        /* ������ͼ�ļ���*/
        $viewFile = $this->setViewFile($moduleName, $methodName);
        if(is_array($viewFile)) extract($viewFile);

        /* �л�����ͼ�ļ����ڵ�Ŀ¼���Ա�֤��ͼ�ļ��еİ���·����Ч��*/
        $currentPWD = getcwd();
        chdir(dirname($viewFile));

        extract((array)$this->view);
        ob_start();
        include $viewFile;
        if(isset($hookFile)) include $hookFile;
        $this->output .= ob_get_contents();
        ob_end_clean();

        /* ���Ҫ�л���ԭ����Ŀ¼��*/
        chdir($currentPWD);
    }

    /**
     * ��ȡĳһ��ģ���ĳһ�����������ݡ�
     * 
     * ���û��ָ��ģ��������ȡ��ǰģ�鵱ǰ��������ͼ�����ָ����ģ��ͷ���������ö�Ӧ��ģ�鷽������ͼ���ݡ�
     *
     * @param   string  $moduleName    ģ������
     * @param   string  $methodName    ��������
     * @param   array   $params        ����������
     * @access  public
     * @return  string
     */
    public function fetch($moduleName = '', $methodName = '', $params = array())
    {
        if($moduleName == '') $moduleName = $this->moduleName;
        if($methodName == '') $methodName = $this->methodName;
        if($moduleName == $this->moduleName and $methodName == $this->methodName) 
        {
            $this->parse($moduleName, $methodName);
            return $this->output;
        }

        /* ���ñ����õ�ģ���·������Ӧ���ļ���*/
        $modulePath        = $this->app->getModuleRoot() . strtolower($moduleName) . $this->pathFix;
        $moduleControlFile = $modulePath . 'control.php';
        $actionExtFile     = $modulePath . "opt{$this->pathFix}control{$this->pathFix}" . strtolower($methodName) . '.php';
        $file2Included     = file_exists($actionExtFile) ? $actionExtFile : $moduleControlFile;

        /* ���ؿ����ļ���*/
        if(!file_exists($file2Included)) $this->app->error("The control file $file2Included not found", __FILE__, __LINE__, $exit = true);
        chdir(dirname($file2Included));
        if($moduleName != $this->moduleName) helper::import($file2Included);
        
        /* ����Ҫ���õ�������ơ�*/
        $className = class_exists("my$moduleName") ? "my$moduleName" : $moduleName;
        if(!class_exists($className)) $this->app->error(" The class $className not found", __FILE__, __LINE__, $exit = true);

        if(!is_array($params)) parse_str($params, $params);
        $module = new $className($moduleName, $methodName);

        ob_start();
        call_user_func_array(array($module, $methodName), $params);
        $output = ob_get_contents();
        ob_end_clean();
        unset($module);
        return $output;
    }

    /**
     * ��ʾ��ͼ���ݡ� 
     * 
     * @param   string  $moduleName    ģ������
     * @param   string  $methodName    ��������
     * @access  public
     * @return  void
     */
    public function display($moduleName = '', $methodName = '')
    {
        if(empty($this->output)) $this->parse($moduleName, $methodName);
        echo $this->output;
        if($this->viewType == 'json') die();
    }

    /**
     * ����ĳһ��ģ��ĳ�����������ӡ�
     * 
     * @param   string  $moduleName    ģ������
     * @param   string  $methodName    ��������
     * @param   mixed   $vars          Ҫ���ݵĲ��������������飬array('var1'=>'value1')��Ҳ������var1=value1&var2=value2����ʽ��
     * @param   string  $viewType      ��ͼ��ʽ��
     * @access  public
     * @return  string
     */
    public function createLink($moduleName, $methodName = 'index', $vars = array(), $viewType = '')
    {
        if(empty($moduleName)) $moduleName = $this->moduleName;
        return helper::createLink($moduleName, $methodName, $vars, $viewType);
    }

    /**
     * ���ɶԱ�ģ��ĳ�����������ӡ�
     * 
     * @param   string  $methodName    ��������
     * @param   mixed   $vars          Ҫ���ݵĲ��������������飬array('var1'=>'value1')��Ҳ������var1=value1&var2=value2����ʽ��
     * @param   string  $viewType      ��ͼ��ʽ��
     * @access  public
     * @return  string
     */
    public function inlink($methodName = 'index', $vars = array(), $viewType = '')
    {
        return helper::createLink($this->moduleName, $methodName, $vars, $viewType);
    }

    /**
     * ��ת������һ��ҳ�档
     * 
     * @param   string   $url   Ҫ��ת��url��ַ��
     * @access  public
     * @return  void
     */
    public function locate($url)
    {
        header("location: $url");
        exit;
    }
}
