#!/usr/bin/env php
<?php
/**
 * ѹ������ļ����ԡ�
 *
 * @copyright   Copyright 2009-2010, Chunsheng Wang
 * @author      chunsheng.wang <wwccss@gmail.com>
 * @package     Testing
 * @version     $Id:
 * @link        http://www.zentao.cn
 * @license     http://opensource.org/licenses/lgpl-3.0.html LGPL
 */
chdir('../');
$allFile = '../../framework/all.class.php';
unlink($allFile);

/* ѹ��֮�����ļ��Ƿ���ڣ�������﷨�Ƿ��д�*/
`./ztphp compress/compressFramework`;
var_dump(file_exists($allFile));
echo `php -l $allFile`;
?>
