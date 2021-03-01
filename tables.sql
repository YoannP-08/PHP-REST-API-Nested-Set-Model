--
-- Database: `docebo`
--

DROP DATABASE IF EXISTS docebo;
CREATE DATABASE IF NOT EXISTS docebo;
USE docebo;

-- --------------------------------------------------------

--
-- Table structure for table `node_tree`
--

CREATE TABLE IF NOT EXISTS `node_tree` (
    `idNode` INT NOT NULL AUTO_INCREMENT,
    `level` INT NOT NULL,
    `iLeft` INT NOT NULL,
    `iRight` INT NOT NULL,
    PRIMARY KEY(`idNode`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Table structure for `node_tree_names`
--

CREATE TABLE IF NOT EXISTS `node_tree_names` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `idNode` INT,
    `language` ENUM('english', 'italian'),
    `nodeName` VARCHAR(255) NOT NULL,
    PRIMARY KEY(`id`),
    FOREIGN KEY(`idNode`) REFERENCES node_tree(idNode)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;