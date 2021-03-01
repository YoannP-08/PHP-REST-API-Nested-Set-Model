<?php 

    class Department {
        // Defining DB Properties
        private $conn;
        private $table_tree = 'node_tree';
        private $table_tree_names = 'node_tree_names';

        // Creating Constructor with DB
        public function __construct ($db) {
            $this->conn = $db;
        }

        // Get Department
        public function read($id, $language, $search_keyword, $page_num, $page_size) {
            // Creating table with all necessary information
            // idNode, iLeft, iRight, language, nodeName
            $joined_tables = "SELECT
                                node_tree.*, node_tree_names.language, node_tree_names.nodeName
                            FROM 
                                $this->table_tree AS node_tree
                            INNER JOIN
                                $this->table_tree_names AS node_tree_names ON node_tree.idNode = node_tree_names.idNode
                        ";
            
            if ($search_keyword === 'null') {
                // Creating query if search_query param is not set
                $query = "SELECT
                        node.idNode,
                        node.iLeft,
                        node.iRight,
                        node.language,
                        node.nodeName
                    FROM 
                        ( $joined_tables ) AS node,
                        ( $joined_tables ) AS parent
                    WHERE parent.idNode = :id
                    AND node.language = :lang
                    AND node.iLeft BETWEEN parent.iLeft AND parent.iRight
                    GROUP BY node.iLeft
                    ORDER BY node.iLeft ASC
                    LIMIT 5 OFFSET :page_number
                ";
            } else {
                // Creating query if search_keyword param is set
                $query = "SELECT
                        node.idNode,
                        node.iLeft,
                        node.iRight,
                        node.language,
                        node.nodeName
                    FROM 
                        ( $joined_tables ) AS node,
                        ( $joined_tables ) AS parent
                    WHERE parent.idNode = :id
                    AND node.language = :lang
                    AND node.iLeft BETWEEN parent.iLeft AND parent.iRight
                    AND node.nodeName LIKE :search
                    GROUP BY node.iLeft
                    ORDER BY node.iLeft ASC
                    LIMIT 5 OFFSET :page_number
                ";
            }
            
            // Preparing statement
            $stmt = $this->conn->prepare($query);
            
            // Binding Params
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':lang', $language);
            $stmt->bindValue('page_number', ($page_num * 5), PDO::PARAM_INT);
            // $stmt->bindValue('page_size', $page_size, PDO::PARAM_INT);
            
            if ($search_keyword !== 'null') {
                $stmt->bindValue(':search', "%$search_keyword%", PDO::PARAM_STR);
            }

            // Executing query
            $stmt->execute();            

            return $stmt;
        }
    };
?>