<?php 
echo <<<EOT
<?php
/**
 * The router file of {$claim[appName]}.
 *
{$claim[license]}  
 *
 * @copyright   {$claim[copyright]}
 * @author      {$claim[author]}
 * @package     {$claim[appName]}
 * @version     \$Id\$
 * @link        {$claim[website]}
 */

/* ��¼�ʼ��ʱ�䡣*/
\$timeStart = _getTime();

/* ������������ļ���*/
include '../../../framework/router.class.php';
include '../../../framework/control.class.php';
include '../../../framework/model.class.php';
include '../../../framework/helper.class.php';

/* ���zentao�����ͨ��pear��ʽ��װ�ģ����Խ������ע�͵����������������䡣
//include 'zentao/framework/router.class.php';
//include 'zentao/framework/control.class.php';
//include 'zentao/framework/model.class.php';
//include 'zentao/framework/helper.class.php';

/* ʵ����·�ɶ��󣬲��������ã����ӵ����ݿ⡣*/
\$app    = router::createApp('{$claim[appName]}', dirname(dirname(__FILE__)));
\$config = \$app->loadConfig('common');
\$dbh    = \$app->connectDB();

/* ���ÿͻ�����ʹ�õ����ԡ����*/
\$app->setClientLang();
\$app->setClientTheme();
\$lang = \$app->loadLang('common');

/* �������󣬼�����Ӧ��ģ�顣*/
\$app->parseRequest();
\$app->loadModule();


/* Debug��Ϣ�����ҳ���ִ��ʱ����ڴ�ռ�á�*/
\$timeUsed = round(_getTime() - \$timeStart, 4) * 1000;
\$memory   = round(memory_get_usage() / 1024, 1);

echo <<<EOD
<div style='text-align:center'>
  Powered By <a href='http://www.zentao.cn' target='_blank'>ZenTaoPHP</a>.
EOD;
if(\$config->debug)
{
    echo " Time:\$timeUsed ms; Mem</strong>:\$memory KB \n";
}
echo '</div>';
EOD;

/* ��ȡϵͳʱ�䣬΢��Ϊ��λ��*/
function _getTime()
{
    list(\$usec, \$sec) = explode(" ", microtime());
    return ((float)\$usec + (float)\$sec);
}
EOT;
