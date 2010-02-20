#!/usr/bin/env php
<?php
/**
 * ����setMember������
 *
 * @copyright   Copyright 2009-2010, Chunsheng Wang
 * @author      chunsheng.wang <wwccss@gmail.com>
 * @package     Testing
 * @version     $Id$
 * @link        http://www.zentao.cn
 * @license     http://opensource.org/licenses/lgpl-3.0.html LGPL
 */
include '../../helper.class.php';
$config = new stdClass();

/* ����һά���Ե��޸ġ�*/
$config->user = 'wwccss';
helper::setMember('config', 'user', 'chunsheng');
echo $config->user . "\n";

/* ��ֵ�ı������е�˫���š�*/
$config->name = 'wwccss';
helper::setMember('config', 'name', "wang'chun\"sheng");
echo $config->name . "\n";

/* ��ֵ�ı���Ϊһ�����顣*/
$config->users = array(1,2,3);
helper::setMember('config', 'users', array('a', 'b', 'c'));
print_r($config->users);

/* ��ֵ�ı���Ϊһ������*/
$config->obj = array(1,2,3);
helper::setMember('config', 'obj', new stdClass());
print_r($config->obj);

/* ���Զ�ά���Ե��޸ġ�*/
$config->db->host = 'localhost';
$config->db->user = 'wwccss';
$config->db->param = array();
helper::setMember('config', 'db.host', "localhost");
helper::setMember('config', 'db.user', "chunsheng'.wang");
helper::setMember('config', 'db.param', array('1', '2', '3'));
echo $config->db->host . "\n";
echo $config->db->user . "\n";
print_r($config->db->param);
?>
