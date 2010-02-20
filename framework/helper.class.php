<?php
/**
 * The helper class file of ZenTaoPHP.
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
 * ��������󣬴���Ÿ�������Ĺ��߷�����
 *
 * @package ZenTaoPHP
 */
class helper
{
    /**
     * Ϊһ����������ĳһ�����ԣ�����key�����ǡ�father.child������ʽ��
     * 
     * <code>
     * <?php
     * $lang->db->user = 'wwccss';
     * helper::setMember('lang', 'db.user', 'chunsheng.wang');
     * ?>
     * </code>
     * @param string    $objName    �����������
     * @param string    $key        Ҫ���õ����ԣ�������father.child����ʽ��
     * @param mixed     $value      Ҫ���õ�ֵ��
     * @static
     * @access public
     * @return void
     */
    static public function setMember($objName, $key, $value)
    {
        global $$objName;
        if(!is_object($$objName) or empty($key)) return false;
        $key   = str_replace('.', '->', $key);
        $value = serialize($value);
        $code  = ("\$${objName}->{$key}=unserialize(<<<EOT\n$value\nEOT\n);");
        eval($code);
    }

    /**
     * ����ĳһ��ģ��ĳ�����������ӡ�
     * 
     * ��control���жԴ˷��������˷�װ��������control������ֱ�ӵ���createLink������
     * <code>
     * <?php
     * helper::createLink('hello', 'index', 'var1=value1&var2=value2');
     * helper::createLink('hello', 'index', array('var1' => 'value1', 'var2' => 'value2');
     * ?>
     * </code>
     * @param string    $moduleName     ģ������
     * @param string    $methodName     ��������
     * @param mixed     $vars           Ҫ���ݸ�method�����ĸ������������������飬Ҳ������var1=value2&var2=value2����ʽ��
     * @param string    $viewType       ��չ����ʽ��
     * @static
     * @access public
     * @return string
     */
    static public function createLink($moduleName, $methodName = 'index', $vars = '', $viewType = '')
    {
        global $app, $config;

        $link = $config->webRoot;
        if($config->requestType == 'GET')
        {
            if(strpos($_SERVER['SCRIPT_NAME'], 'index.php') === false)
            {
                $link = $_SERVER['SCRIPT_NAME'];
            }
        }

        if(empty($viewType)) $viewType = $app->getViewType();

        /* ������ݽ�����vars�������飬���Խ�������������ʽ��*/
        if(!is_array($vars)) parse_str($vars, $vars);
        if($config->requestType == 'PATH_INFO')
        {
            $link .= "$moduleName{$config->requestFix}$methodName";
            if($config->pathType == 'full')
            {
                foreach($vars as $key => $value) $link .= "{$config->requestFix}$key{$config->requestFix}$value";
            }
            else
            {
                foreach($vars as $value) $link .= "{$config->requestFix}$value";
            }    
            /* ������ʵ���/index/index.html����Ϊ/index.html��*/
            if($moduleName == $config->default->module and $methodName == $config->default->method) $link = $config->webRoot . 'index';
            $link .= '.' . $viewType;
        }
        elseif($config->requestType == 'GET')
        {
            $link .= "?{$config->moduleVar}=$moduleName&{$config->methodVar}=$methodName";
            if($viewType != 'html') $link .= "&{$config->viewVar}=" . $viewType;
            foreach($vars as $key => $value) $link .= "&$key=$value";
        }
        return $link;
    }

    /**
     * ��һ������ת�ɶ����ʽ���˺���ֻ�Ƿ�����䣬��Ҫeval��
     * 
     * <code>
     * <?php
     * $config['user'] = 'wwccss';
     * eval(helper::array2Object($config, 'configobj');
     * print_r($configobj);
     * ?>
     * </code>
     * @param array     $array          Ҫת�������顣
     * @param string    $objName        Ҫת���ɵĶ�������֡�
     * @param string    $memberPath     ��Ա����·�����ʼΪ�գ��Ӹ���ʼ��
     * @param bool      $firstRun       �Ƿ��ǵ�һ�����С�
     * @static
     * @access public
     * @return void
     */
    static public function array2Object($array, $objName, $memberPath = '', $firstRun = true)
    {
        if($firstRun)
        {
            if(!is_array($array) or empty($array)) return false;
        }    
        static $code = '';
        $keys = array_keys($array);
        foreach($keys as $keyNO => $key)
        {
            $value = $array[$key];
            if(is_int($key)) $key = 'item' . $key;
            $memberID = $memberPath . '->' . $key;
            if(!is_array($value))
            {
                $value = addslashes($value);
                $code .= "\$$objName$memberID='$value';\n";
            }
            else
            {
                helper::array2object($value, $objName, $memberID, $firstRun = false);
            }
        }
        return $code;
    }

    /**
     * ����һ���ļ���router.class.php��control.class.php�а����ļ���ͨ���˺��������ã�������֤�ļ������ظ����ء�
     * 
     * @param string    $file   Ҫ�������ļ���·���� 
     * @static
     * @access public
     * @return void
     */
    static public function import($file)
    {
        if(!file_exists($file)) return false;
        static $includedFiles = array();
        if(!isset($includedFiles[$file]))
        {
            include $file;
            $includedFiles[$file] = true;
        }
    }

    /**
     * ����SQL��ѯ�е�IN(a,b,c)���ִ��롣
     * 
     * @param   misc    $ids   id�б����������飬Ҳ������ʹ�ö��Ÿ������ַ����� 
     * @static
     * @access  public
     * @return  string
     */
    static function dbIN($ids)
    {
        if(is_array($ids)) return "IN ('" . join("','", $ids) . "')";
        return "IN ('" . str_replace(',', "','", str_replace(' ', '',$ids)) . "')";
    }

    /**
     * ���ɶԿ�ܰ�ȫ��base64encode����
     * 
     * @param   string  $string   Ҫ������ַ����б�
     * @static
     * @access  public
     * @return  string
     */
    static function safe64Encode($string)
    {
        return strtr(base64_encode($string), '+/=', '');
    }

    /**
     * ���롣
     * 
     * @param   string  $string   Ҫ������ַ����б�
     * @static
     * @access  public
     * @return  string
     */
    static function safe64Decode($string)
    {
        return base64_decode(strtr($string, '', '+/='));
    }

    /**
     *  �����������ڵĲ
     * 
     * @param   date  $date1   ��һ��ʱ��
     * @param   date  $date2   �ڶ���ʱ��
     * @access  public
     * @return  string
     */
    static function diffDate($date1, $date2)
    {
        return round((strtotime($date1) - strtotime($date2)) / 86400, 0);
    }
}

/* �������������ɶ��ڲ����������ӡ� */
function inLink($methodName = 'index', $vars = '', $viewType = '')
{
    global $app;
    return helper::createLink($app->getModuleName(), $methodName, $vars, $viewType);
}
