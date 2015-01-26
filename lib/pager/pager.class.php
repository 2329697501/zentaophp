<?php
/**
 * ZenTaoPHP的分页类。
 * The pager class file of ZenTaoPHP framework.
 *
 * The author disclaims copyright to this source code.  In place of
 * a legal notice, here is a blessing:
 * 
 *  May you do good and not evil.
 *  May you find forgiveness for yourself and forgive others.
 *  May you share freely, never taking more than you give.
 */
/**
 * pager类.
 * Pager class.
 * 
 * @package framework
 */
class pager
{
    /**
     * 每页的默认显示记录数。
     * The default counts of per page.
     *
     * @public int
     */
    const DEFAULT_REC_PRE_PAGE = 20;

    /**
     * 总个数。
     * The total counts.
     * 
     * @var int
     * @access public
     */
    public $recTotal;

    /**
     * 每页的记录数。
     * Record count per page.
     * 
     * @var int
     * @access public
     */
    public $recPerPage;

    /**
     * 总页面数。
     * Page count.
     * 
     * @var string
     * @access public
     */
    public $pageTotal;

    /**
     * 当前页码。
     * Current page id.
     * 
     * @var string
     * @access public
     */
    public $pageID;

    /**
     * 全局变量$app。
     * The global $app.
     * 
     * @var object
     * @access private
     */
    private $app;

    /**
     * 全局变量$lang。
     * The global $lang.
     * 
     * @var object
     * @access private
     */
    private $lang;

    /**
     * 当前的模块名。
     * Current module name.
     * 
     * @var string
     * @access private
     */
    private $moduleName;

    /**
     * 当前的方法名。
     * Current method.
     * 
     * @var string
     * @access private
     */
    private $methodName;

    /**
     * 参数信息。
     * The params.
     *
     * @private array
     */
    private $params;

    /**
     * 构造方法。
     * The construct function.
     * 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function __construct($recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->setRecTotal($recTotal);
        $this->setRecPerPage($recPerPage);
        $this->setPageTotal();
        $this->setPageID($pageID);
        $this->setApp();
        $this->setLang();
        $this->setModuleName();
        $this->setMethodName();
    }

    /**
     * 构造方法。
     * The factory function.
     * 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return object
     */
    public function init($recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        return new pager($recTotal, $recPerPage, $pageID);
    }

    /**
     * 设置总记录数。
     * Set the recTotal property.
     * 
     * @param  int    $recTotal 
     * @access public
     * @return void
     */
    public function setRecTotal($recTotal = 0)
    {
        $this->recTotal = (int)$recTotal;
    }

    /**
     * 设置每页记录数。
     * Set the recPerPage property.
     * 
     * @param  int    $recPerPage 
     * @access public
     * @return void
     */
    public function setRecPerPage($recPerPage)
    {
        $this->recPerPage = ($recPerPage > 0) ? $recPerPage : PAGER::DEFAULT_REC_PRE_PAGE;
    }

    /**
     * 设置总页数。
     * Set the pageTotal property.
     * 
     * @access public
     * @return void
     */
    public function setPageTotal()
    {
        $this->pageTotal = ceil($this->recTotal / $this->recPerPage);
    }

    /**
     * 设置页码。
     * Set the page id.
     * 
     * @param  int $pageID 
     * @access public
     * @return void
     */
    public function setPageID($pageID)
    {
        if($pageID > 0 and $pageID <= $this->pageTotal)
        {
            $this->pageID = $pageID;
        }
        else
        {
            $this->pageID = 1;
        }
    }

    /**
     * 设置全局变量$app。
     * Set the $app property;
     * 
     * @access private
     * @return void
     */
    private function setApp()
    {
        global $app;
        $this->app = $app;
    }

    /**
     * 设置全局变量$lang。
     * Set the $lang property.
     * 
     * @access private
     * @return void
     */
    private function setLang()
    {
        global $lang;
        $this->lang = $lang;
    }

    /**
     * 设置模块名。
     * Set the $moduleName property.
     * 
     * @access private
     * @return void
     */
    private function setModuleName()
    {
        $this->moduleName = $this->app->getModuleName();
    }

    /**
     * 设置方法名。
     * Set the $methodName property.
     * 
     * @access private
     * @return void
     */
    private function setMethodName()
    {
        $this->methodName = $this->app->getMethodName();
    }

    /**
     * 从请求网址中获取记录总数、每页记录数、页码。
     * Get recTotal, recPerpage, pageID from the request params, and add them to params.
     * 
     * @access private
     * @return void
     */
    private function setParams()
    {
        $this->params = $this->app->getParams();
        foreach($this->params as $key => $value)
        {
            if(strtolower($key) == 'rectotal')   $this->params[$key] = $this->recTotal;
            if(strtolower($key) == 'recperpage') $this->params[$key] = $this->recPerPage;
            if(strtolower($key) == 'pageid')     $this->params[$key] = $this->pageID;
        }
    }

    /**
     * 创建limit语句。
     * Create the limit string.
     * 
     * @access public
     * @return string
     */
    public function limit()
    {
        $limit = '';
        if($this->pageTotal > 1) $limit = ' limit ' . ($this->pageID - 1) * $this->recPerPage . ", $this->recPerPage";
        return $limit;
    }
   
    /**
     * 向页面显示分页信息。 
     * Print the pager's html.
     * 
     * @param  string $align 
     * @param  string $type 
     * @access public
     * @return void
     */
    public function show($align = 'right', $type = 'full')
    {
        echo $this->get($align, $type);
    }

    /**
     * 设置分页信息的样式。
     * Get the pager html string.
     * 
     * @param  string $align 
     * @param  string $type     the pager type, full|short|shortest
     * @access public
     * @return string
     */
    public function get($align = 'right', $type = 'full')
    {
        /* 如果记录个数为0，返回没有记录。 */
        /* If the RecTotal is zero, return with no record. */
        if($this->recTotal == 0) { return "<div style='float:$align; clear:none;' class='pager'>{$this->lang->pager->noRecord}</div>"; }

        /* Set the params. */
        $this->setParams();
        
        /* 创建前一页和后一页链接。 */
        /* Create the prePage and nextpage, all types have them. */
        $pager  = $this->createPrePage();
        $pager .= $this->createNextPage();

        /* 简单和完全模式。  The short and full type. */
        if($type !== 'shortest')
        {
            $pager  = $this->createFirstPage() . $pager;
            $pager .= $this->createLastPage();
        }

        /* 只是完全模式。   Only the full type . */
        if($type == 'full')
        {
            $pager  = $this->createDigest() . $pager;
            $pager .= $this->createGoTo();
            $pager .= $this->createRecPerPageJS();
        }

        return "<div style='float:$align; clear:none;' class='pager'>$pager</div>";
    }

    /**
     * 生成分页摘要信息。
     * Create the digest code.
     * 
     * @access private
     * @return string
     */
    private function createDigest()
    {
        return sprintf($this->lang->pager->digest, $this->recTotal, $this->createRecPerPageList(), $this->pageID, $this->pageTotal);
    }

    /**
     * 创建首页链接。
     * Create the first page.
     * 
     * @access private
     * @return string
     */
    private function createFirstPage()
    {
        if($this->pageID == 1) return $this->lang->pager->first . ' ';
        $this->params['pageID'] = 1;
        return html::a(helper::createLink($this->moduleName, $this->methodName, $this->params), $this->lang->pager->first);
    }

    /**
     * 创建前一页链接。
     * Create the pre page html.
     * 
     * @access private
     * @return string
     */
    private function createPrePage()
    {
        if($this->pageID == 1) return $this->lang->pager->pre . ' ';
        $this->params['pageID'] = $this->pageID - 1;
        return html::a(helper::createLink($this->moduleName, $this->methodName, $this->params), $this->lang->pager->pre);
    }    

    /**
     * 创建下一页链接。
     * Create the next page html.
     * 
     * @access private
     * @return string
     */
    private function createNextPage()
    {
        if($this->pageID == $this->pageTotal) return $this->lang->pager->next . ' ';
        $this->params['pageID'] = $this->pageID + 1;
        return html::a(helper::createLink($this->moduleName, $this->methodName, $this->params), $this->lang->pager->next);
    }

    /**
     * 创建最后一页链接。
     * Create the last page 
     * 
     * @access private
     * @return string
     */
    private function createLastPage()
    {
        if($this->pageID == $this->pageTotal) return $this->lang->pager->last . ' ';
        $this->params['pageID'] = $this->pageTotal;
        return html::a(helper::createLink($this->moduleName, $this->methodName, $this->params), $this->lang->pager->last);
    }    

    /**
     * 创建每页显示记录数的select标签。
     * Create the select object of record perpage.
     * 
     * @access private
     * @return string
     */
    private function createRecPerPageJS()
    {
        /* 
         * 替换recTotal, recPerPage, pageID为特殊的字符串，然后用js代码替换掉。
         * Replace the recTotal, recPerPage, pageID to special string, and then replace them with values by JS.
         **/
        $params = $this->params;
        foreach($params as $key => $value)
        {
            if(strtolower($key) == 'rectotal')   $params[$key] = '_recTotal_';
            if(strtolower($key) == 'recperpage') $params[$key] = '_recPerPage_';
            if(strtolower($key) == 'pageid')     $params[$key] = '_pageID_';
        }
        $vars = '';
        foreach($params as $key => $value) $vars .= "$key=$value&";
        $vars = rtrim($vars, '&');

        $js  = <<<EOT
        <script language='Javascript'>
        vars = '$vars';
        function submitPage(mode)
        {
            pageTotal  = parseInt(document.getElementById('_pageTotal').value);
            pageID     = document.getElementById('_pageID').value;
            recPerPage = document.getElementById('_recPerPage').value;
            recTotal   = document.getElementById('_recTotal').value;
            if(mode == 'changePageID')
            {
                if(pageID > pageTotal) pageID = pageTotal;
                if(pageID < 1) pageID = 1;
            }
            else if(mode == 'changeRecPerPage')
            {
                pageID = 1;
            }

            vars = vars.replace('_recTotal_', recTotal)
            vars = vars.replace('_recPerPage_', recPerPage)
            vars = vars.replace('_pageID_', pageID);
            location.href=createLink('$this->moduleName', '$this->methodName', vars);
        }
        </script>
EOT;
        return $js;
    }

    /**
     * 生成每页显示记录数的select列表。
     * Create the select list of RecPerPage. 
     * 
     * @access private
     * @return string
     */
    private function createRecPerPageList()
    {
        for($i = 5; $i <= 50; $i += 5) $range[$i] = $i;
        $range[100]  = 100;
        $range[200]  = 200;
        $range[500]  = 500;
        $range[1000] = 1000;
        return html::select('_recPerPage', $range, $this->recPerPage, "onchange='submitPage(\"changeRecPerPage\");'");
    }

    /**
     * 生成跳转到指定页码的部分。
     * Create the goto part html.
     * 
     * @access private
     * @return string
     */
    private function createGoTo()
    {
        $goToHtml  = "<input type='hidden' id='_recTotal'  value='$this->recTotal' />\n";
        $goToHtml .= "<input type='hidden' id='_pageTotal' value='$this->pageTotal' />\n";
        $goToHtml .= "<input type='text'   id='_pageID'    value='$this->pageID' style='text-align:center;width:30px;' /> \n";
        $goToHtml .= "<input type='button' id='goto'       value='{$this->lang->pager->locate}' onclick='submitPage(\"changePageID\");' />";
        return $goToHtml;
    }    
}
