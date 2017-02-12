--
-- Table structure for table `gpa_list`
--

DROP TABLE IF EXISTS `gpa_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gpa_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `USER_ID` char(20) DEFAULT NULL,
  `course_id` char(10) NOT NULL,
  `grade` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=491 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;