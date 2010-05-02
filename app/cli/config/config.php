<?php
/**
 * The cli config file of ZenTaoPHP.
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
$config->requestType = 'PATH_INFO';                           // ����ʽ��
$config->requestFix  = '/';
$config->cookiePath  = '/';                                   // cookie����Ч·����
$config->cookieLife  = time() + 2592000;                      // cookie���������ڡ�
$config->langs       = 'zh-cn,zh-tw,zh-hk,en';                // ֧�ֵ������б�
$config->views       = ',html,xml,json,txt,csv,doc,pdf,';     // ֧�ֵ���ͼ�б�
$config->themes      = 'default';                             // ֧�ֵ������б�

$config->default->view   = 'html';                          // Ĭ�ϵ���ͼ��ʽ��
$config->default->lang   = 'zh-cn';                         // Ĭ�ϵ����ԡ�
$config->default->theme  = 'default';                       // Ĭ�ϵ����⡣
