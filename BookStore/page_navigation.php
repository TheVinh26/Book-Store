<?php
class Pager
{
    function findStart($limit)
    {
        if ((!isset($_GET['page']))  || ($_GET['page']=="1")) {
            $start = 0;
            $_GET['page'] = 1 ;
        } else {
            $start = ($_GET['page'] - 1) * $limit;
        }
        return $start;
    }
    
    function findPages($count, $limit)  
    {
        $pages = (($count % $limit)== 0) ? $count / $limit : floor($count / $limit) + 1;
        return $pages;
    }
    
    function pageList($curPage, $pages, $sort = null)
{
    $params = $_GET;
    unset($params['page']); // Remove existing page parameter
    $paramStr = http_build_query($params); // Rebuild query string without page parameter

    $page_list = "";
    if (($curPage != 1) && ($curPage)) {
        $page_list .= "<a href=\"" . $_SERVER['PHP_SELF'] . "?" . ($paramStr ? $paramStr . '&' : '') . "page=1" . ($sort ? "&sort=$sort" : "") . "\" title=\"Trang đầu\">First |</a>";
    }
    if (($curPage - 1) > 0) {
        $page_list .= "<a href=\"" . $_SERVER['PHP_SELF'] . "?" . ($paramStr ? $paramStr . '&' : '') . "page=" . ($curPage - 1) . ($sort ? "&sort=$sort" : "") . "\" title=\"Về trang trước\"> Previous | </a>";
    }
    $vtdau = max($curPage - 2, 1);
    $vtcuoi = min($curPage + 2, $pages);

    for ($i = $vtdau; $i <= $vtcuoi; $i++) {
        if ($i == $curPage) {
            $page_list .= "<span>&nbsp" . $i . "&nbsp</span>";
        } else {
            $page_list .= "<a href='" . $_SERVER['PHP_SELF'] . "?" . ($paramStr ? $paramStr . '&' : '') . "page=" . $i . ($sort ? "&sort=$sort" : "") . "' title='Trang " . $i . "'>" . $i . "</a>";
        }
    }

    if ($curPage + 1 <= $pages) {
        $page_list .= "<a href=\"" . $_SERVER['PHP_SELF'] . "?" . ($paramStr ? $paramStr . '&' : '') . "page=" . ($curPage + 1) . ($sort ? "&sort=$sort" : "") . "\" title=\"Đến trang sau\">| Next  </a>";
        $page_list .= "<a href=\"" . $_SERVER['PHP_SELF'] . "?" . ($paramStr ? $paramStr . '&' : '') . "page=" . $pages . ($sort ? "&sort=$sort" : "") . "\" title=\"Đến trang cuối\"> | Last</a>";
    }
    return $page_list;
}
    
    function nextPrev($curpage, $pages) // Hiển thị 2 nút trước sau
    {
        $next_prev = "";
        if ($curpage - 1 < 0) {
            $next_prev .= "Về trang trước";
        } else {
            $next_prev .= "<a href=\"" . $_SERVER['PHP_SELF'] . "?page=" . ($curpage - 1) . "\">Về trang trước</a>"; 
        }
        $next_prev .="  |  ";
        if (($curpage + 1) > $pages) {
            $next_prev .= "Đến trang sau";
        } else {
            $next_prev .= "<a href=\"" . $_SERVER['PHP_SELF'] . "?page=" . ($curpage + 1) . "\">Đến trang sau</a>"; 
        }
        return $next_prev;
    }
}
?>
