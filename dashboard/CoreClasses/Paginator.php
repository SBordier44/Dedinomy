<?php
namespace Dedinomy;

if(!defined('SECURE_LINK')) die('Direct access is not authorized');

class Paginator
{
    private $_conn;
    private $_limit = 10;
    private $_page;
    private $_query;
    private $_total;
    private static $_instance;

    public static function getInstance()
    {
        if (!self::$_instance) {
            $class = __CLASS__;
            self::$_instance = new $class;
        }
        return self::$_instance;
    }

    public function __construct()
    {
        $this->_conn = Database::getInstance();
        $this->_page = (isset($_GET['page']))?$_GET['page']:1;
    }

    public function init($query, $opt=[]){
        $this->_query = $query;
        $rs = $this->_conn->query($this->_query, $opt);
        $this->_total = $rs->rowCount();
        $Settings = Settings::getInstance();
        if($Settings->get('admin_perpage')){
            $this->_limit = $Settings->get('admin_perpage');
        }
    }

    public function getData() {
        $query          = $this->_query . " LIMIT " . ( ( $this->_page - 1 ) * $this->_limit ) . ", $this->_limit";
        $result         = new \stdClass();
        $result->page   = $this->_page;
        $result->limit  = $this->_limit;
        $result->total  = $this->_total;
        $result->data   = $this->_conn->query($query)->fetchAll();
        return $result;
    }

    public function createLinks($links) {
        if(!$this->_total) return '';
        $last       = ceil( $this->_total / $this->_limit );
        $start      = ( ( $this->_page - $links ) > 0 ) ? $this->_page - $links : 1;
        $end        = ( ( $this->_page + $links ) < $last ) ? $this->_page + $links : $last;
        $html       = '<div style="text-align: center;">';
        $html       .= '<ul class="pagination pagination-sm">';
        $class      = ( $this->_page == 1 ) ? "disabled" : "";
        ($this->_page == 1)? $link="<a><<</a>":$link='<a href="?action='.$_GET['action'].'&page=' . ( $this->_page - 1 ) . '"><<</a>';
        $html       .= '<li class="' . $class . '">'.$link.'</li>';
        if ( $start > 1 ) {
            $html   .= '<li><a href="?action='.$_GET['action'].'&page=1">1</a></li>';
            $html   .= '<li class="disabled"><span>...</span></li>';
        }
        for ( $i = $start ; $i <= $end; $i++ ) {
            $class  = ( $this->_page == $i ) ? "active" : "";
            ($this->_page == $i)? $link="<a>$i</a>":$link='<a href="?action='.$_GET['action'].'&page=' . $i . '">'.$i.'</a>';
            $html   .= '<li class="' . $class . '">'.$link.'</li>';
        }
        if ( $end < $last ) {
            $html   .= '<li class="disabled"><span>...</span></li>';
            $html   .= '<li><a href="?action='.$_GET['action'].'&page=' . $last . '">' . $last . '</a></li>';
        }
        $class      = ( $this->_page == $last ) ? "disabled" : "";
        ($this->_page == $last)? $link="<a>>></a>":$link='<a href="?action='.$_GET['action'].'&page=' . ( $this->_page + 1 ) . '">>></a>';

        $html       .= '<li class="' . $class . '">'.$link.'</li>';
        $html       .= '</ul></div>';
        return $html;
    }
}