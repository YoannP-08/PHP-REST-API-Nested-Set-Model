<?php

    class DepartmentController {
        // Defining properties
        private $id;
        private $language;
        private $search_keyword;
        private $page_num;
        private $page_size;

        // Initialising regex variable to verify
        // if user input is strictly an integer
        private $intRegex = '/^[0-9]*$/'; 


        public function __construct($id, $lang, $search, $page_num, $page_size) {
            $this->id = $id;
            // Convert string to lowercase - case insensitive
            $this->language = strtolower($lang);
            $this->search_keyword = strtolower($search);
            $this->page_num = $page_num;
            $this->page_size = $page_size;
        }

        public function checkId() {
            // Verifying if ID param is set
            if (!isset($this->id) || $this->id === '') {
                die('Missing mandatory param "node_id".');
            };

            // Verifying if ID is an integer
            if (preg_match($this->intRegex, $this->id) === 1) {
                $this->id = intval($this->id);
                return $this->id;
            } else {
                die("Input \"node_id\" $this->id. Param must be an integer.");
            };
        }

        public function checkLanguage() {
            // Verifying if LANGUAGE param is set
            if (!isset($this->language) || $this->language === '') {
                die('Missing mandatory param "language".');
            };

            // Verifying if Language is within the available options
            $lanArr = ['english', 'italian'];
            if (in_array($this->language, $lanArr)) {
                $this->language = strval($this->language);
                return $this->language;
            } else {
                die("Input \"language\" $this->language. Language parameter options are \"English\" or \"Italian\".");
            };
        }

        public function checkSearchKeyword() {
            // Removing special characters to prevent SQL Injection
            $new_search_keyword = preg_replace('/[^a-zA-Z0-9\s]/', '', $this->search_keyword);
            return $new_search_keyword;
        }

        public function checkPageNum() {
            // Verifying if Page Number is an integer
            if (preg_match($this->intRegex, $this->page_num) === 1) {
                $this->page_num = intval($this->page_num);
                return $this->page_num;
            } else {
                die("Input \"page_num\" $this->page_num. Invalid page number requested.");
            };
        }

        public function checkPageSize() {
            // Verifying if Page Size is an integer
            if (preg_match($this->intRegex, $this->page_size) === 1) {
                $this->page_size = intval($this->page_size);
                
                // Verifying if Page_Size is between 0 and 1000
                if (0 < $this->page_size && $this->page_size <= 1000) {
                    return $this->page_size;
                } else {
                    die("Input \"page_size\" $this->page_size. Invalid page size requested. Page_size must be between 0 and 1000.");
                }
            } else {
                die("Input \"page_size\" $this->page_size. Param must be an integer.");
            };
        }
    };
?>