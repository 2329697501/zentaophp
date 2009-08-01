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
 * @copyright   Copyright 2009, Chunsheng Wang
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
     * ��¼��ֵ��view�����б�����
     * 
     * @var array
     * @access private
     */
    private $vars = array();

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
    public function __construct()
    {
        /* ����ȫ�ֶ��󣬲���ֵ��*/
        global $app, $config, $lang, $dbh;
        $this->app        = $app;
        $this->config     = $config;
        $this->lang       = $lang;
        $this->dbh        = $dbh;
        $this->pathFix    = $this->app->getPathFix();
        $this->moduleName = $this->app->getModuleName();
        $this->modulePath = $this->app->getModuleRoot() . $this->moduleName . $this->pathFix;

        /* �Զ����ص�ǰģ���model�ļ���*/
        $this->loadModel();

        /* �Զ���$config��$lang��ֵ��ģ���С�*/
        $this->assign('lang',   $lang);
        $this->assign('config', $config);
    }

    //-------------------- model��صķ�����--------------------//

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
        if(!class_exists($modelClass)) $this->app->error(" The model $modelClass not found", __FILE__, __LINE__, $exit = true);
        $this->$moduleName = new $modelClass();
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
        $viewFile = $this->app->getModuleRoot() . $moduleName . $this->pathFix . 'view' . $this->pathFix . $methodName . '.' . $this->app->getViewType() . '.php';
        if(!file_exists($viewFile)) $this->app->error("the view file $viewFile not found", __FILE__, __LINE__, $exit = true);
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
        $this->vars[$name] = $value;
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
        if(empty($methodName)) $methodName = $this->app->getMethodName();
        $viewFile = $this->setViewFile($moduleName, $methodName);

        /* �л�����ͼ�ļ����ڵ�Ŀ¼���Ա�֤��ͼ�ļ��еİ���·����Ч��*/
        $currentPWD = getcwd();
        chdir(dirname($viewFile));

        extract($this->vars);
        ob_start();
        include $viewFile;
        $this->output .= ob_get_contents();
        ob_end_clean();

        /* ���Ҫ�л���ԭ����Ŀ¼��*/
        chdir($currentPWD);
    }

    /**
     * ��ȡ��ͼ���ݡ�
     * 
     * ���Խ�ĳһ����ͼ�ļ���������Ϊ�ַ������ء� 
     *
     * @param   string  $moduleName    ģ������
     * @param   string  $methodName    ��������
     * @param   bool    $clear         �Ƿ����ԭ������ͼ���ݡ�
     * @access  public
     * @return  string
     */
    public function fetch($moduleName = '', $methodName = '', $clear = false)
    {
        if($clear) $this->clear();
        if(empty($this->output)) $this->parse($moduleName, $methodName);
        return $this->output;
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
    }

    /**
     * ����ĳһ��ģ��ĳ�����������ӡ�
     * 
     * @param   string  $moduleName    ģ������
     * @param   string  $methodName    ��������
     * @param   mixed   $vars          Ҫ���ݵĲ��������������飬array('var1'=>'value1')��Ҳ������var1=value1&var2=value2����ʽ��
     * @access  public
     * @return  string
     */
    public function createLink($moduleName, $methodName = 'index', $vars = array())
    {
        if(empty($moduleName)) $moduleName = $this->moduleName;
        return helper::createLink($moduleName, $methodName, $vars);
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
