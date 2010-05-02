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
 * @copyright   Copyright 2009-2010 �ൺ�����촴����Ƽ����޹�˾(www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPHP
 * @version     $Id$
 * @link        http://www.zentaoms.com
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
     * get����
     * 
     * @var ojbect
     * @access public
     */
    public $get;

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
     * cookie����
     * 
     * @var ojbect
     * @access public
     */
    public $cookie;

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

        $moduleName = $this->getModuleName();
        $this->app->loadLang($moduleName,   $exit = false);
        $this->app->loadConfig($moduleName, $exit = false);
     
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
    protected function getModuleName()
    {
        $parentClass = get_parent_class($this);
        $selfClass   = get_class($this);
        $className   = $parentClass == 'model' ? $selfClass : $parentClass;
        return strtolower(str_ireplace(array('ext', 'Model'), '', $className));
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
        $this->get     = $this->app->get;
        $this->server  = $this->app->server;
        $this->cookie  = $this->app->cookie;
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
        $modelFile = helper::setModelFile($moduleName);
        if(!file_exists($modelFile)) return false;

        helper::import($modelFile);
        $modelClass = class_exists('ext' . $moduleName. 'model') ? 'ext' . $moduleName . 'model' : $moduleName . 'model';
        if(!class_exists($modelClass)) $this->app->error(" The model $modelClass not found", __FILE__, __LINE__, $exit = true);

        $this->$moduleName = new $modelClass();
        return $this->$moduleName;
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

    /* ��һ����¼���Ϊ��ɾ����*/
    public function delete($table, $id)
    {
        $this->dao->update($table)->set('deleted')->eq(1)->where('id')->eq($id)->exec();
        $object = str_replace($this->config->db->prefix, '', $table);
        $this->loadModel('action')->create($object, $id, 'deleted', '', $extra = ACTIONMODEL::CAN_UNDELETED);
    }

    /* ��ӭ�Ѿ����Ϊɾ���ļ�¼��*/
    public function undelete($actionID)
    {
        $action = $this->loadModel('action')->getById($actionID);
        if($action->action != 'deleted') return;
        $table = $this->config->action->objectTables[$action->objectType];
        $this->dao->update($table)->set('deleted')->eq(0)->where('id')->eq($action->objectID)->exec();
        $this->dao->update(TABLE_ACTION)->set('extra')->eq(ACTIONMODEL::BE_UNDELETED)->where('id')->eq($actionID)->exec();
        $this->action->create($action->objectType, $action->objectID, 'undeleted');
    }
}    
