--
-- Table structure for table `#__ccm_cms`
--

CREATE TABLE IF NOT EXISTS `#__ccm_cms` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `cms_name` varchar(100) NOT NULL DEFAULT '',
  `documents` JSON DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table `#__ccm_cms`
INSERT INTO `#__ccm_cms` (`id`, `cms_name`) VALUES
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
