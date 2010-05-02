<?php
/**
 * The config file of ZenTaoPHP.
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
$config->version     = '1.0.STABLE.090620'; // �汾�ţ������޸ġ�
$config->debug       = true;              // �Ƿ��debug���ܡ�
$config->webRoot     = '/';               // web��վ�ĸ�Ŀ¼��
$config->encoding    = 'UTF-8';           // ��վ�ı��롣
$config->cookiePath  = '/';               // cookie����Ч·����
$config->cookieLife  = time() + 2592000;  // cookie���������ڡ�

$config->requestType = 'PATH_INFO';       // ��λ�ȡ��ǰ�������Ϣ����ѡֵ��PATH_INFO|GET
$config->pathType    = 'clean';           // requestType=PATH_INFO: ����url�ĸ�ʽ����ѡֵΪfull|clean��full��ʽ����в������ƣ�clean��ֻ��ȡֵ��
$config->requestFix  = '/';               // requestType=PATH_INFO: ����url�ķָ�������ѡֵΪб�ߡ��»��ߡ����š�����������ʽ������SEO��
$config->moduleVar   = 'm';               // requestType=GET: ģ���������
$config->methodVar   = 'f';               // requestType=GET: ������������
$config->viewVar     = 't';               // requestType=GET: ģ���������

$config->views       = ',html,xml,json,txt,csv,doc,pdf,'; // ֧�ֵ���ͼ�б�
$config->langs       = 'zh-cn,zh-tw,zh-hk,en';            // ֧�ֵ������б�
$config->themes      = 'default';                         // ֧�ֵ������б�

$config->default->view   = 'html';                      // Ĭ�ϵ���ͼ��ʽ��
$config->default->lang   = 'zh-cn';                     // Ĭ�ϵ����ԡ�
$config->default->theme  = 'default';                   // Ĭ�ϵ����⡣
$config->default->module = 'index';                     // Ĭ�ϵ�ģ�顣��������û��ָ��ģ��ʱ�����ظ�ģ�顣
$config->default->method = 'index';                     // Ĭ�ϵķ�������������û��ָ����������ָ���ķ���������ʱ�����ø÷�����

$config->db->errorMode  = PDO::ERRMODE_WARNING;         // PDO�Ĵ���ģʽ: PDO::ERRMODE_SILENT|PDO::ERRMODE_WARNING|PDO::ERRMODE_EXCEPTION
$config->db->persistant = false;                        // �Ƿ�򿪳־����ӡ�
$config->db->driver     = 'mysql';                      // pdo���������ͣ�Ŀǰ��ʱֻ֧��mysql��
$config->db->host       = 'localhost';                  // mysql������
$config->db->port       = '3306';                       // mysql�����˿ںš�
$config->db->name       = 'zentao';                     // ���ݿ����ơ�
$config->db->user       = 'root';                       // ���ݿ��û�����
$config->db->password   = '';                           // ���롣
$config->db->encoding   = 'UTF8';                       // ���ݿ�ı��롣
