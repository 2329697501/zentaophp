<?php
/**
 * The model class file of ZenTaoPHP.
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
 * ģ�ͻ��ࡣ
 * 
 * @package ZenTaoPHP
 */
class model
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
     * ��model����ģ������֡�
     * 
     * @var string
     * @access protected
     */
    protected $moduleName;

    /**
     * ��model����ģ�����ڵ�·����
     * 
     * @var string
     * @access protected
     */
    protected $modulePath;

    /**
     * ģ���Ӧ�������ļ���
     * 
     * @var string
     * @access protected
     */
    protected $moduleConfig;

    /**
     * ģ��������ļ���
     * 
     * @var string
     * @access protected
     */
    protected $moduleLang;

    /**
     * ��Ϣ������������¼ĳһ�������ķ�����Ϣ��
     * 
     * @var string
     * @access protected
     */
    protected $message;

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
     * ���캯����
     *
     * 1. ����ȫ�ֱ�����ʹ֮����ͨ����Ա���Է��ʡ�
     * 2. ���õ�ǰģ���·�������á����Ե���Ϣ����������Ӧ���ļ���
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        global $app, $config, $lang, $dbh;
        $this->app    = $app;
        $this->config = $config;
        $this->lang   = $lang;
        $this->dbh    = $dbh;

        $this->setModuleName();
        $this->setModulePath();
        $this->setModuleConfig();
        $this->loadModuleConfig();
        $this->setModuleLang();
        $this->loadModuleLang();

        if(isset($config->db->dao)   and $config->db->dao)   $this->loadDAO();
        if(isset($config->super2OBJ) and $config->super2OBJ) $this->setSuperVars();
    }

    /**
     * ����ģ�������������е�model�滻����Ϊģ������
     * û��ʹ��$app->getModule()��������Ϊ�����ص��ǵ�ǰ���õ�ģ�顣
     * ����һ�������У���ǰģ���control�ļ����п��ܻ��������ģ���model��
     * 
     * @access protected
     * @return void
     */
    protected function setModuleName()
    {
        $parentClass = get_parent_class($this);
        $selfClass   = get_class($this);
        $className   = $parentClass == 'model' ? $selfClass : $parentClass;
        $this->moduleName = strtolower(str_ireplace('Model', '', $className));
    }

    /**
     * ����ģ��������·����
     * 
     * @access protected
     * @return void
     */
    protected function setModulePath()
    {
        $this->modulePath = $this->app->getModuleRoot() . $this->moduleName . $this->app->getPathFix();
    }

    /**
     * ����ģ��������ļ���
     * 
     * @access protected
     * @return void
     */
    protected function setModuleConfig()
    {
        $this->moduleConfig = $this->modulePath. 'config.php';
    }

    /**
     * ����ģ��������ļ���
     * 
     * @access protected
     * @return void
     */
    protected function loadModuleConfig()
    {
        if(file_exists($this->moduleConfig)) $this->app->loadConfig($this->moduleName);
    }

    /**
     * ����ģ��������ļ���
     * 
     * @access protected
     * @return void
     */
    protected function setModuleLang()
    {
        $this->moduleLang = $this->modulePath. 'lang' . $this->app->getPathFix() . $this->app->getClientLang() . '.php';
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

    /**
     * ����ĳһ��ģ���model�ļ���
     * 
     * @param   string  $moduleName     ģ�����֣����Ϊ�գ���ȡ��ǰ��ģ������Ϊmodel����
     * @access  public
     * @return  void
     */
    public function loadModel($moduleName)
    {
        if(empty($moduleName)) return false;
        $modelFile = $this->setModelFile($moduleName);
        if(!file_exists($modelFile)) return false;

        $modelClass = $moduleName. 'Model';
        helper::import($modelFile);
        
        if(!class_exists($modelClass)) $this->app->error(" The model $modelClass not found", __FILE__, __LINE__, $exit = true);
        $this->$moduleName = new $modelClass();
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
        $modelFile = $this->app->getModuleRoot() . strtolower(trim($moduleName)) . $this->app->getPathFix() . 'model.php';
        return $modelFile;
    }

    /**
     * ����ģ��������ļ���
     * 
     * @access protected
     * @return void
     */
    protected function loadModuleLang()
    {
        if(file_exists($this->moduleLang)) $this->app->loadLang($this->moduleName);
    }

    /**
     * ��ȡ���µ���Ϣ��¼��
     * 
     * @access public
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * ������Ϣ��¼��
     * 
     * @param string $message 
     * @access protected
     * @return void
     */
    protected function setMessage($message)
    {
        $this->message = $message;
    }
    
    /**
     * ׷����Ϣ��¼��
     * 
     * @param string $message 
     * @access protected
     * @return void
     */
    protected function appendMessage($message)
    {
        $this->message .= $message;
    }

    //-------------------- ���ݿ������Ӧ�ķ�����--------------------//

    /**
     * ����DAO�࣬�����ض���
     * 
     * @access private
     * @return void
     */
    private function loadDAO()
    {
        $this->dao = $this->app->loadClass('dao');
    }

    /**
     * ����key=>value��ʽ�����顣
     * 
     * @param string $sql           Ҫִ�е�sql��䡣 
     * @param string $keyField      key�ֶ�����
     * @param string $valueField    value�ֶ�����
     * @access protected
     * @return void
     */
    public function fetchPairs($sql, $keyField = '', $valueField = '')
    {
        $pairs = array();
        $stmt  = $this->dbh->query($sql, PDO::FETCH_ASSOC);
        $ready = false;
        while($row = $stmt->fetch())
        {
            if(!$ready)
            {
                if(empty($keyField)) $keyField = key($row);
                if(empty($valueField)) 
                {
                    end($row);
                    $valueField = key($row);
                }
                $ready = true;
            }
            $pairs[$row[$keyField]] = $row[$valueField];
        }
        return $pairs;
    }
}    
