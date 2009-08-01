<?php 
echo <<<EOT
<?php
/**
 * The config file of {$claim[appName]}.
 *
{$claim[license]}  
 *
 * @copyright   {$claim[copyright]}
 * @author      {$claim[author]}
 * @package     {$claim[appName]}
 * @version     \$Id\$
 * @link        {$claim[website]}
 */
\$config['version']     = '1.0.BETA.090524'; // �汾�ţ������޸ġ�
\$config['debug']       = true;              // �Ƿ��debug���ܡ�
\$config['webRoot']     = '/';               // web��վ�ĸ�Ŀ¼��
\$config['encoding']    = 'UTF-8';           // ��վ�ı��롣
\$config['cookiePath']  = '/';               // cookie����Ч·����
\$config['cookieLife']  = time() + 2592000;  // cookie���������ڡ�

\$config['requestType'] = 'PATH_INFO';       // ��λ�ȡ��ǰ�������Ϣ����ѡֵ��PATH_INFO|GET
\$config['pathType']    = 'clean';           // requestType=PATH_INFO: ����url�ĸ�ʽ����ѡֵΪfull|clean��full��ʽ����в������ƣ�clean��ֻ��ȡֵ��
\$config['requestFix']  = '/';               // requestType=PATH_INFO: ����url�ķָ�������ѡֵΪб�ߡ��»��ߡ����š�����������ʽ������SEO��
\$config['moduleVar']   = 'm';               // requestType=GET: ģ���������
\$config['methodVar']   = 'f';               // requestType=GET: ������������
\$config['viewVar']     = 't';               // requestType=GET: ģ���������

\$config['views']       = ',html,xml,json,txt,csv,doc,pdf,'; // ֧�ֵ���ͼ�б�
\$config['langs']       = 'zh-cn,zh-tw,zh-hk,en';            // ֧�ֵ������б�
\$config['themes']      = 'default';                         // ֧�ֵ������б�

\$config['default']['view']   = 'html';                      // Ĭ�ϵ���ͼ��ʽ��
\$config['default']['lang']   = 'zh-cn';                     // Ĭ�ϵ����ԡ�
\$config['default']['theme']  = 'default';                   // Ĭ�ϵ����⡣
\$config['default']['module'] = 'index';                     // Ĭ�ϵ�ģ�顣��������û��ָ��ģ��ʱ�����ظ�ģ�顣
\$config['default']['method'] = 'index';                     // Ĭ�ϵķ�������������û��ָ����������ָ���ķ���������ʱ�����ø÷�����

\$config['db']['errorMode']  = PDO::ERRMODE_WARNING;         // PDO�Ĵ���ģʽ: PDO::ERRMODE_SILENT|PDO::ERRMODE_WARNING|PDO::ERRMODE_EXCEPTION
\$config['db']['persistant'] = false;                        // �Ƿ�򿪳־����ӡ�
\$config['db']['driver']     = 'mysql';                      // pdo���������ͣ�Ŀǰ��ʱֻ֧��mysql��
\$config['db']['host']       = 'localhost';                  // mysql������
\$config['db']['port']       = '3306';                       // mysql�����˿ںš�
\$config['db']['name']       = 'zentao';                     // ���ݿ����ơ�
\$config['db']['user']       = 'root';                       // ���ݿ��û�����
\$config['db']['password']   = '';                           // ���롣
\$config['db']['encoding']   = 'UTF8';                       // ���ݿ�ı��롣

EOT;
