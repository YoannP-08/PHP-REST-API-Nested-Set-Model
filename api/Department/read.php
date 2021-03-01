<?php

    include_once '../../config/Database.php';
    include_once '../../models/api/Department.php';
    include_once '../../controllers/api/DepartmentController.php';

    // Defining Headers to access REST API through
    // HTTP request
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    // Instantiating && Connecting DB
    $database = new Database();
    $db = $database->connect_db();

    // Instantiating Organisational Chart Object
    $dpt = new Department($db);

    // Getting URL Params
    $url_id = $_GET['node_id'];
    $url_lang = $_GET['language'];
    $url_search = isset($_GET['search_keyword']) ? $_GET['search_keyword'] : 'null';
    $url_page_num = isset($_GET['page_num']) ? $_GET['page_num'] : 0;
    $url_page_size = isset($_GET['page_size']) ? $_GET['page_size'] : 100;

    // Validating URL Params
    $validator = new DepartmentController($url_id, $url_lang, $url_search, $url_page_num, $url_page_size);
    $id = $validator->checkId();
    $language = $validator->checkLanguage();
    $search_keyword = $validator->checkSearchKeyword();
    $page_num = $validator->checkPageNum();
    $page_size = $validator->checkPageSize();
    
    $result = $dpt->read($id, $language, $search_keyword, $page_num, $page_size);
    $num = $result->rowCount();

    // Checking if pagination returns data
    if ($num === 0 && 0 < $page_num) {
        die('Result data is only 1 page, thus "page_num" param is either not required or too high.');
    }

    // Checking if search returns data
    if ($num === 0 && $search_keyword !== 'null') {
        die('No data for this search.');
    }

    // Checking if any nodes
    if (0 < $num) {
        // Creating nodes array output response
        $nodes_arr = array();
        $nodes_arr['data'] = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            // Calculating the number of child nodes of the parent node
            $children_count = ($iRight - $iLeft - 1) / 2;

            // Creating array element
            $dpt_item = [
                'node_id' => intval($idNode),
                'name' => $nodeName,
                'children_count' => $children_count
            ];

            // Pushing to "data"
            array_push($nodes_arr['data'], $dpt_item);
        };
        
        // Turning to JSON & output
        echo json_encode($nodes_arr);
    } else {
        echo json_encode(
            array('message' => 'Invalid node id.')
        );
    };
?>