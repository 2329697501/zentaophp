<?php
/**
 * The demo app router file of ZenTaoPHP.
 *
 * All request should be routed by this router.
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
/* ��¼�ʼ��ʱ�䡣*/
$timeStart = _getTime();

/* ������������ļ���*/
include '../../../framework/router.class.php';
include '../../../framework/control.class.php';
include '../../../framework/model.class.php';
include '../../../framework/helper.class.php';

/* ���zentao�����ͨ��pear��ʽ��װ�ģ����Խ������ע�͵����������������䡣*/
//include 'zentao/framework/router.class.php';
//include 'zentao/framework/control.class.php';
//include 'zentao/framework/model.class.php';
//include 'zentao/framework/helper.class.php';

/* ʵ����·�ɶ��󣬲��������ã����ӵ����ݿ⣬����commonģ�顣*/
$app    = router::createApp('demo', dirname(dirname(__FILE__)));
$config = $app->loadConfig('common');
$dbh    = $app->connectDB();
$common = $app->loadCommon();

/* ���ÿͻ�����ʹ�õ����ԡ����*/
$app->setClientLang();
$app->setClientTheme();
$lang = $app->loadLang('common');

/* �������󣬼�����Ӧ��ģ�顣*/
$app->parseRequest();
$app->loadModule();

/* Debug��Ϣ�����ҳ���ִ��ʱ����ڴ�ռ�á�*/
$timeUsed = round(_getTime() - $timeStart, 4) * 1000;
$memory   = round(memory_get_usage() / 1024, 1);

echo <<<EOT
<div style='text-align:center'>
  Powered By <a href='http://www.zentaoms.com' target='_blank'>ZenTaoPHP</a>.
EOT;
if($config->debug)
{
    echo " Time:$timeUsed ms; Mem</strong>:$memory KB ";
}
echo '</div>';

/* ��ȡϵͳʱ�䣬΢��Ϊ��λ��*/
function _getTime()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}
