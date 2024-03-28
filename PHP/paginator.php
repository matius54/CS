<?php
    require_once "./db.php";
    require_once "./utils.php";
    
    class Paginator {
        private static $ItemsPerPage = 10;
        private static $pageKey = "page";
        public array $items;
        public string $navigator;

        public function __construct(string $sql, array $args = []){
            [$this->items, $this->navigator] = $this->paginate($sql, $args);
        }
        
        private static function getPage(int $lastPage) : int {
            $page = URL::decode(self::$pageKey);
            if(is_string($page) || is_double($page)) $page = intval($page);
            if($page === null || !is_int($page)) $page = 1;
            if($page < 1) $page = 1;
            if($page > $lastPage) $page = $lastPage;
            return $page;
        }

        public static function paginate(string $sql, array $args) : array {
            $name = self::sqlSelect2tableName($sql);
            if($name === null)throw new Exception("paginator: name for the table not found");
            [$itemCount, $lastPage] = self::getTotalPages($name);
            $page = self::getPage($lastPage);

            $items = $lastPage ? self::paginateQuery($page, $sql, $args) : [];
            $navigator = self::show($page, $lastPage, $itemCount);
            return [$items, $navigator];
        }

        private static function paginateQuery(int $page,string $sql, array $args = []) : array {
            $limit = self::$ItemsPerPage;
            $offset = ($page-1) * self::$ItemsPerPage;

            $db = DB::getInstance();
            $db->execute($sql." LIMIT ? OFFSET ?",array_merge($args,[$limit, $offset]));
            return $db->fetchAll(htmlspecialchars: true);
        }

        private static function getTotalPages(string $name) : array {
            $db = DB::getInstance();
            $db->execute("SELECT COUNT(*) FROM $name");
            if($response = $db->fetch()){
                $itemCount = $response["COUNT(*)"];
                $lastPage = ceil($itemCount / self::$ItemsPerPage);
                return [
                    intval($itemCount),
                    intval($lastPage)
                ];
            }
        }

        private static function show(int $page, int $lastPage, int|null $itemCount = null) : string {
            if($lastPage === 0) return "<i>Lista vacía.</i>";
            $html = "<i>Mostrando página $page de $lastPage";
            if($itemCount !== null) $html .= ", con un total de $itemCount elementos";
            $html .= ".</i>";
            $html .= "<ul class=\"navigator\">";
            if($page > 1){
                $html .= "<li><a href=\"".URL::URIquery([self::$pageKey => 1])."\" title=\"Primera página\">&lt;&lt;</a></li>";
                $html .= "<li><a href=\"".URL::URIquery([self::$pageKey => $page - 1])."\" title=\"Página anterior\">&lt;</a></li>";
            }
            for($i = 1; $i <= $lastPage; $i++) {
                $html .= "<li><a ";
                if($page === $i){
                    $html .= "class=selected ";
                }else{
                    $html .= "href=\"".URL::URIquery([self::$pageKey => $i])."\" ";
                }
                $html .= "title=\"Página $i\">$i</a></li>";
            }
            if($page < $lastPage){
                $html .= "<li><a href=\"".URL::URIquery([self::$pageKey => $page + 1])."\" title=\"Página siguiente\">&gt;</a></li>";
                $html .= "<li><a href=\"".URL::URIquery([self::$pageKey => $lastPage])."\" title=\"Última página\">&gt;&gt;</a></li>";
            }
            $html .= "</ul>";
            return $html;
        }

        private static function sqlSelect2tableName($sql){
            $sql = explode(" ",$sql);
            foreach ($sql as $key => $value) {
                if(strtoupper($value) === "FROM") return $sql[$key + 1];
            }
            return null;
        }
    }
?>