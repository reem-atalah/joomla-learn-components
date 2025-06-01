--
-- Table structure for table `#__ccm_cms`
--

CREATE TABLE IF NOT EXISTS `#__ccm_cms` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `credentials` varchar(255) DEFAULT NULL,
  -- `documents` JSON DEFAULT NULL,
  `content_keys_types` JSON DEFAULT NULL,
  `ccm_mapping` JSON DEFAULT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `#__ccm_cms`
INSERT INTO `#__ccm_cms` (`id`, `name`) VALUES
(1, 'Joomla'),
(2, 'WordPress'),
(3, 'Drupal'),
(4, 'Magento'),
(5, 'Shopify'),
(6, 'Blogger'),
(7, 'Wix'),
(8, 'Squarespace'),
(9, 'Weebly'),
(10, 'PrestaShop');
