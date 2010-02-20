#!/usr/bin/env php
<?php
/**
 * ����import����
 *
 * @copyright   Copyright 2009-2010, Chunsheng Wang
 * @author      chunsheng.wang <wwccss@gmail.com>
 * @package     Testing
 * @version     $Id$
 * @link        http://www.zentao.cn
 * @license     http://opensource.org/licenses/lgpl-3.0.html LGPL
 */
include '../../helper.class.php';

/* �״ΰ�����*/
helper::import('import1.php');
printIncluded();

/* �ظ�������*/
helper::import('import1.php');
printIncluded();

/* �����ڶ����ļ���*/
helper::import('import2.php');
printIncluded();

/* ���������ڵ��ļ���*/
var_dump(helper::import('noexits.php'));

/**
 * ֻ��ӡ�����ļ����ļ�����
 * 
 * @access public
 * @return void
 */
function printIncluded()
{
    $files = get_included_files();
    foreach($files as $file)
    {
        echo basename($file) . "\n";
    }
    echo "\n";
}
?>
