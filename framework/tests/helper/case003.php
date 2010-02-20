#!/usr/bin/env php
<?php
/**
 * ����createLink����
 *
 * @copyright   Copyright 2009-2010, Chunsheng Wang
 * @author      chunsheng.wang <wwccss@gmail.com>
 * @package     Testing
 * @version     $Id$
 * @link        http://www.zentao.cn
 * @license     http://opensource.org/licenses/lgpl-3.0.html LGPL
 */
include '../../helper.class.php';

/* ʵ����app��mock����*/
$app = new mockapp();
$app->setViewType('html');

/* ����cfg���ã�������ת��Ϊ$config����*/
$cfg['webRoot'] = '/';
$cfg['requestType'] = 'PATH_INFO';
$cfg['requestFix'] = '/';
$cfg['pathType']   = 'full';
$cfg['moduleVar']  = 'm';
$cfg['methodVar']  = 'f';
$cfg['viewVar']    = 't';
eval(helper::array2Object($cfg, 'config'));

/* PATH_INFO + FULL*/
$vars = array('k1' => 'v1', 'k2' => 'v2');
echo helper::createLink('index') . "\n";               // ֻ��ģ������
echo helper::createLink('user', 'login') . "\n";       // ���ӷ�������
echo helper::createLink('user', 'view', $vars) . "\n"; // ���Ӳ�����
$vars = 'k1=v1&k2=v2';
echo helper::createLink('user', 'view', $vars) . "\n\n"; // �����ĳ�str��ʽ��

/* PATH_INFO + CLEAN */
$config->pathType = 'clean';
$vars = array('k1' => 'v1', 'k2' => 'v2');
echo helper::createLink('index') . "\n";               // ֻ��ģ������
echo helper::createLink('user', 'login') . "\n";       // ���ӷ�������
echo helper::createLink('user', 'view', $vars) . "\n"; // ���Ӳ�����
$vars = 'k1=v1&k2=v2';
echo helper::createLink('user', 'view', $vars) . "\n\n"; // �����ĳ�str��ʽ��

/* PATH_INFO + CLEAN + REQUESTFIX */
$config->requestFix = '-';
$vars = array('k1' => 'v1', 'k2' => 'v2');
echo helper::createLink('index') . "\n";               // ֻ��ģ������
echo helper::createLink('user', 'login') . "\n";       // ���ӷ�������
echo helper::createLink('user', 'view', $vars) . "\n"; // ���Ӳ�����
$vars = 'k1=v1&k2=v2';
echo helper::createLink('user', 'view', $vars) . "\n\n"; // �����ĳ�str��ʽ��

/* GET + CLEAN */
$config->requestType = 'GET';
$vars = array('k1' => 'v1', 'k2' => 'v2');
echo helper::createLink('index') . "\n";               // ֻ��ģ������
echo helper::createLink('user', 'login') . "\n";       // ���ӷ�������
echo helper::createLink('user', 'view', $vars) . "\n"; // ���Ӳ�����
$vars = 'k1=v1&k2=v2';
echo helper::createLink('user', 'view', $vars) . "\n"; // �����ĳ�str��ʽ��

/**
 * app��mock����
 * 
 * @package Testing
 */
class mockapp
{
    private $viewType;
    public function setViewType($viewType)
    {
        $this->viewType = $viewType;
    }
    public function getViewType()
    {
        return $this->viewType;
    }
}
?>
