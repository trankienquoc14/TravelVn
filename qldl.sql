-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: mysql-35ac1384-doantram0901-18fb.h.aivencloud.com    Database: defaultdb
-- ------------------------------------------------------
-- Server version	8.0.45

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `qldl`
--



--
-- Table structure for table `blogs`
--

DROP TABLE IF EXISTS `blogs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blogs` (
  `blog_id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `short_desc` text COLLATE utf8mb4_unicode_ci,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`blog_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blogs`
--

LOCK TABLES `blogs` WRITE;
/*!40000 ALTER TABLE `blogs` DISABLE KEYS */;
INSERT INTO `blogs` VALUES (1,'Kinh nghiệm du lịch Sapa tự túc từ A-Z mùa săn mây','kinh-nghiem-du-lich-sapa-tu-tuc-tu-a-z-mua-san-may','Kinh nghiệm','blog1.jpg','Hướng dẫn chi tiết cách di chuyển, đặt phòng và các điểm check-in không thể bỏ lỡ khi đến Sapa mùa săn mây.','<p>Sapa luôn là điểm đến hấp dẫn du khách trong và ngoài nước. Để có một chuyến đi săn mây trọn vẹn, bạn nên ghé thăm vào khoảng tháng 9 đến tháng 11. Đừng quên mang theo áo ấm và giày thể thao để tiện di chuyển nhé!</p><ul><li>Đỉnh Fansipan</li><li>Bản Cát Cát</li><li>Cổng trời Ô Quy Hồ</li></ul>','2026-04-20 01:30:00'),(2,'Top 10 món ngon đặc sản Phú Quốc nhất định phải thử','top-10-mon-ngon-dac-san-phu-quoc-nhat-dinh-phai-thu','Ẩm thực','15-dac-san-phu-quoc.jpg','Bún quậy, gỏi cá trích, nhum biển... Khám phá bản đồ ẩm thực làm say đắm du khách tại Đảo Ngọc.','<p>Đến Phú Quốc mà không thưởng thức hải sản thì quả là một thiếu sót lớn. Gỏi cá trích cuốn bánh tráng chấm nước mắm chua ngọt, hay bát bún quậy Kiến Xây trứ danh chắc chắn sẽ làm hài lòng những thực khách khó tính nhất.</p>','2026-04-18 07:15:00'),(3,'Lịch trình phá đảo Đà Nẵng - Hội An 4 ngày 3 đêm','lich-trinh-pha-dao-da-nang-hoi-an-4-ngay-3-dem','Điểm đến','danang_hoian.jpg','Gợi ý lịch trình di chuyển tối ưu nhất để bạn khám phá trọn vẹn hai thành phố di sản miền Trung.','<p>Hành trình 4 ngày 3 đêm là khoảng thời gian lý tưởng để bạn khám phá sự sôi động của Đà Nẵng và nét cổ kính của Hội An. Ngày 1: Khám phá Bán đảo Sơn Trà. Ngày 2: Vui chơi tại Bà Nà Hills. Ngày 3: Tắm biển Mỹ Khê và di chuyển vào Hội An. Ngày 4: Dạo quanh phố cổ và mua sắm.</p>','2026-04-15 02:00:00'),(4,'Bí kíp xếp hành lý gọn nhẹ cho tour du lịch dài ngày','bi-kip-xep-hanh-ly-gon-nhe-cho-tour-du-lich-dai-ngay','Mẹo hay','blog2.png','Áp dụng ngay nguyên tắc cuộn tròn và sử dụng túi chiết để vali của bạn luôn gọn gàng, tiện lợi.','<p>Để tối ưu không gian vali, hãy cuộn tròn quần áo thay vì gấp phẳng. Sử dụng các túi chiết mỹ phẩm nhỏ gọn và tận dụng khoảng trống bên trong giày để nhét tất. Đừng quên mang theo một chiếc túi zip dự phòng để đựng đồ bẩn nhé!</p>','2026-04-10 09:45:00'),(6,'Khám phá Đảo Ngọc Phú Quốc 3 Ngày 2 Đêm: Trọn Bộ Bí Kíp Cho Người Mới','kham-pha-dao-ngoc-phu-quoc-3-ngay-2-dem-tron-bo-bi-kip-cho-nguoi-moi','Kinh nghiệm','1777383011_blog_phuquoc.webp','Bỏ túi ngay lịch trình chi tiết khám phá Phú Quốc 3 ngày 2 đêm. Từ những bãi biển xanh ngắt vắng người, đến những khu chợ đêm sầm uất và các món hải sản địa phương ăn là ghiền!','<p><strong>Phú Quốc</strong> - hòn đảo ngọc xinh đẹp nằm ở cực Nam Tổ quốc luôn là điểm đến hấp dẫn du khách trong và ngoài nước. Nếu bạn đang lên kế hoạch cho chuyến đi đầu tiên đến đây, đừng bỏ qua lịch trình 3 ngày 2 đêm cực kỳ tối ưu này của TravelVN nhé!</p>\r\n\r\n<h3>Ngày 1: Nhận phòng - Khám phá Bắc Đảo - Săn hoàng hôn</h3>\r\n<ul>\r\n    <li><strong>Sáng:</strong> Đáp chuyến bay đến Phú Quốc. Khởi hành về khách sạn khu vực Dương Đông để gửi hành lý. Vui chơi tại khu vực VinWonders và Safari.</li>\r\n    <li><strong>Trưa:</strong> Thưởng thức đặc sản bún quậy Kiến Xây trứ danh với phần chả tôm mực tươi rói.</li>\r\n    <li><strong>Chiều:</strong> Di chuyển đến OCSEN Beach Bar & Club hoặc Sunset Sanato để ngắm hoàng hôn rực rỡ nhất Việt Nam.</li>\r\n    <li><strong>Tối:</strong> Dạo Chợ đêm Phú Quốc, thưởng thức hải sản nướng mỡ hành và đậu phộng Chou Chou.</li>\r\n</ul>\r\n\r\n<h3>Ngày 2: Tour 4 đảo - Lặn ngắm san hô - Cáp treo Hòn Thơm</h3>\r\n<p>Đây là ngày bạn sẽ dành trọn vẹn cho biển cả. Hãy đặt ngay một tour cano khám phá các hòn đảo nhỏ phía Nam:</p>\r\n<ul>\r\n    <li><strong>Hòn Móng Tay & Hòn Gầm Ghì:</strong> Trải nghiệm lặn ngắm san hô tự nhiên tuyệt đẹp với làn nước trong vắt nhìn thấy đáy.</li>\r\n    <li><strong>Hòn Mây Rút:</strong> Nghỉ ngơi, tắm biển và chụp những bức ảnh check-in sống ảo cực \"chill\" với xích đu vô cực.</li>\r\n    <li><strong>Chiều muộn:</strong> Trải nghiệm tuyến cáp treo vượt biển dài nhất thế giới từ Hòn Thơm về lại ga An Thới.</li>\r\n</ul>\r\n\r\n<h3>Ngày 3: Mua sắm đặc sản - Tạm biệt Đảo Ngọc</h3>\r\n<p>Sáng ngày cuối cùng, hãy thuê xe máy dạo quanh thị trấn, ghé thăm nhà thùng nước mắm, vườn tiêu hoặc cơ sở sản xuất ngọc trai để mua quà cho người thân. Tầm 11h, bạn làm thủ tục trả phòng và di chuyển ra sân bay.</p>\r\n\r\n<hr>\r\n\r\n<p><em><strong>Lưu ý nhỏ từ TravelVN:</strong> Thời điểm lý tưởng nhất để vi vu Phú Quốc là từ tháng 11 đến tháng 4 năm sau (mùa khô). Đừng quên chuẩn bị kem chống nắng và một chiếc máy ảnh đầy pin nhé! Bạn có thể đặt ngay các <strong><a href=\"#\">Tour Phú Quốc trọn gói</a></strong> của chúng tôi để nhận ưu đãi bất ngờ!</em></p>','2026-04-28 10:39:12');
/*!40000 ALTER TABLE `blogs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bookings` (
  `booking_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `departure_id` int NOT NULL,
  `customer_name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pickup_address` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `booking_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `number_of_people` int DEFAULT NULL,
  `total_price` decimal(12,2) DEFAULT NULL,
  `note` text COLLATE utf8mb4_general_ci,
  `status` enum('pending','confirmed','cancelled','refunded','completed','checked_in') COLLATE utf8mb4_general_ci DEFAULT 'pending',
  PRIMARY KEY (`booking_id`),
  KEY `user_id` (`user_id`),
  KEY `departure_id` (`departure_id`),
  CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`departure_id`) REFERENCES `departures` (`departure_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookings`
--

LOCK TABLES `bookings` WRITE;
/*!40000 ALTER TABLE `bookings` DISABLE KEYS */;
INSERT INTO `bookings` VALUES (1,3,1,'Phạm Mỹ Linh','linhpm@gmail.com','0987654321','Tự đến Điểm hẹn tập trung','2026-05-18 13:14:55',1,2000.00,'--- YÊU CẦU HỦY BỞI KHÁCH HÀNG ---\nLÝ DO HỦY: Sức khỏe.\nTHÔNG TIN NHẬN HOÀN TIỀN: Ngân hàng momo, STK: 0375452033, Chủ thẻ: đoàn thị trâm.','refunded'),(2,3,1,'Phạm Mỹ Linh','linhpm@gmail.com','0987654321','Tự đến Điểm hẹn tập trung','2026-05-18 14:58:20',1,2000.00,'--- YÊU CẦU HỦY BỞI KHÁCH HÀNG ---\nLÝ DO HỦY: Đặt nhầm.\nTHÔNG TIN NHẬN HOÀN TIỀN: Ngân hàng momo, STK: 0375452033, Chủ thẻ: đoàn thị trâm.\n\n--- YÊU CẦU HỦY BỞI KHÁCH HÀNG ---\nLÝ DO HỦY: Sức khỏe.\nTHÔNG TIN NHẬN HOÀN TIỀN: Ngân hàng momo, STK: 0375452033, Chủ thẻ: đoàn thị trâm.','refunded'),(3,3,1,'Phạm Mỹ Linh','linhpm@gmail.com','0987654321','Tự đến Điểm hẹn tập trung','2026-05-18 15:14:45',1,2000.00,'--- YÊU CẦU HỦY BỞI KHÁCH HÀNG ---\nLÝ DO HỦY: Đặt nhầm.','cancelled'),(4,3,1,'Phạm Mỹ Linh','linhpm@gmail.com','0987654321','Tự đến Điểm hẹn tập trung','2026-05-18 15:16:00',1,2000.00,'--- YÊU CẦU HỦY BỞI KHÁCH HÀNG ---\nLÝ DO HỦY: Sức khỏe.','cancelled'),(5,3,2,'Phạm Mỹ Linh','linhpm@gmail.com','0987654321','Tự đến Điểm hẹn tập trung','2026-05-19 14:37:50',1,2000.00,'','confirmed'),(6,3,2,'Phạm Mỹ Linh','linhpm@gmail.com','0987654321','Tự đến Điểm hẹn tập trung','2026-05-19 17:12:45',1,2000.00,'','confirmed'),(7,3,2,'Phạm Mỹ Linh','linhpm@gmail.com','0987654321','Tự đến Điểm hẹn tập trung','2026-05-19 17:24:45',1,2000.00,'','confirmed'),(8,6,2,'6aye','atwbd12@gmail.com','0987654321','Tự đến Điểm hẹn tập trung','2026-05-19 17:40:16',1,2000.00,'--- YÊU CẦU HỦY BỞI KHÁCH HÀNG ---\nLÝ DO HỦY: Đặt nhầm.\nTHÔNG TIN NHẬN HOÀN TIỀN: Ngân hàng momo, STK: 0375452033, Chủ thẻ: đoàn thị trâm.','refunded'),(9,6,3,'6aye','atwbd12@gmail.com','0987654321','Tự đến Điểm hẹn tập trung','2026-05-19 17:50:28',1,2000.00,'','confirmed');
/*!40000 ALTER TABLE `bookings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chat_messages`
--

DROP TABLE IF EXISTS `chat_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chat_messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `session_id` varchar(100) NOT NULL,
  `departure_id` int DEFAULT NULL,
  `sender_type` enum('customer','admin','guide','tour_manager') NOT NULL,
  `sender_name` varchar(150) DEFAULT NULL,
  `message` text,
  `file_url` text,
  `message_type` enum('text','image','audio','location','file') DEFAULT 'text',
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chat_messages`
--

LOCK TABLES `chat_messages` WRITE;
/*!40000 ALTER TABLE `chat_messages` DISABLE KEYS */;
INSERT INTO `chat_messages` VALUES (1,'user_3',NULL,'customer','Phạm Mỹ Linh','cgdsgds',NULL,'text',1,'2026-05-20 03:18:51'),(2,'user_3',NULL,'admin','TravelVN Bot','🤖 Xin chào! TravelVN đã nhận được tin nhắn của bạn. Chúng tôi sẽ phản hồi sớm nhất ❤️',NULL,'text',1,'2026-05-20 03:18:51'),(3,'user_3',NULL,'customer','Phạm Mỹ Linh',NULL,'https://res.cloudinary.com/dooiu0qs0/image/upload/v1779248223/travelvn/chat/j5g26pyeq1uf2dye2hwa.jpg','image',1,'2026-05-20 03:37:04'),(11,'user_3',NULL,'customer','Phạm Mỹ Linh',NULL,'https://res.cloudinary.com/dooiu0qs0/video/upload/v1779251714/travelvn/chat/audio/cftohi7lkqgysw78frhl.webm','audio',1,'2026-05-20 04:35:15'),(12,'user_3',2,'customer','Phạm Mỹ Linh',NULL,'https://res.cloudinary.com/dooiu0qs0/video/upload/v1779252784/travelvn/chat/audio/bqdvhqm8mgse3bpjgfdj.webm','audio',1,'2026-05-20 04:53:04'),(13,'guest_8c5ba7a6a65cdde85f5b65a8d9021af3',NULL,'customer','Khách vãng lai',NULL,'https://res.cloudinary.com/dooiu0qs0/video/upload/v1779253416/travelvn/chat/audio/erdrnjmvvsegzfrfvinf.webm','audio',1,'2026-05-20 05:03:37'),(14,'guest_8c5ba7a6a65cdde85f5b65a8d9021af3',NULL,'customer','Khách vãng lai',NULL,'https://res.cloudinary.com/dooiu0qs0/video/upload/v1779253475/travelvn/chat/audio/b0qbnjx6oe74jnsesyxm.webm','audio',1,'2026-05-20 05:04:36'),(15,'guest_8c5ba7a6a65cdde85f5b65a8d9021af3',NULL,'customer','Khách vãng lai',NULL,'https://res.cloudinary.com/dooiu0qs0/image/upload/v1779253492/travelvn/chat/eyvemcm8attyyszdtedb.jpg','image',1,'2026-05-20 05:04:52'),(16,'user_3',2,'customer','Phạm Mỹ Linh',NULL,'https://res.cloudinary.com/dooiu0qs0/video/upload/v1779253580/travelvn/chat/audio/vasnkwo6ksetrm49jmpz.webm','audio',1,'2026-05-20 05:06:21'),(18,'guest_4c8021ab5ba9fbd9bea0a6e25aff65de',NULL,'customer','Khách vãng lai',NULL,'https://res.cloudinary.com/dooiu0qs0/image/upload/v1779804812/travelvn/chat/kjbdmutcvo7qsamta49o.jpg','image',1,'2026-05-26 14:13:33'),(19,'guest_4c8021ab5ba9fbd9bea0a6e25aff65de',NULL,'customer','Khách vãng lai',NULL,'https://res.cloudinary.com/dooiu0qs0/image/upload/v1779805489/travelvn/chat/exdhhvtzcnmpgqjqn97t.jpg','image',1,'2026-05-26 14:24:50');
/*!40000 ALTER TABLE `chat_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `checkins`
--

DROP TABLE IF EXISTS `checkins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `checkins` (
  `checkin_id` int NOT NULL AUTO_INCREMENT,
  `booking_id` int DEFAULT NULL,
  `staff_id` int DEFAULT NULL,
  `checkin_time` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`checkin_id`),
  KEY `booking_id` (`booking_id`),
  KEY `staff_id` (`staff_id`),
  CONSTRAINT `checkins_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`),
  CONSTRAINT `checkins_ibfk_2` FOREIGN KEY (`staff_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `checkins`
--

LOCK TABLES `checkins` WRITE;
/*!40000 ALTER TABLE `checkins` DISABLE KEYS */;
INSERT INTO `checkins` VALUES (1,5,2,'2026-05-19 17:10:43');
/*!40000 ALTER TABLE `checkins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departure_guides`
--

DROP TABLE IF EXISTS `departure_guides`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `departure_guides` (
  `id` int NOT NULL AUTO_INCREMENT,
  `departure_id` int DEFAULT NULL,
  `guide_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `departure_id` (`departure_id`),
  KEY `guide_id` (`guide_id`),
  CONSTRAINT `departure_guides_ibfk_1` FOREIGN KEY (`departure_id`) REFERENCES `departures` (`departure_id`),
  CONSTRAINT `departure_guides_ibfk_2` FOREIGN KEY (`guide_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departure_guides`
--

LOCK TABLES `departure_guides` WRITE;
/*!40000 ALTER TABLE `departure_guides` DISABLE KEYS */;
INSERT INTO `departure_guides` VALUES (6,4,9),(7,3,2),(9,6,9),(10,7,2),(11,8,2),(15,10,2),(16,5,2),(17,9,2),(21,11,2),(24,2,2),(25,3,2),(26,1,2);
/*!40000 ALTER TABLE `departure_guides` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departures`
--

DROP TABLE IF EXISTS `departures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `departures` (
  `departure_id` int NOT NULL AUTO_INCREMENT,
  `tour_id` int NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `max_seats` int DEFAULT NULL,
  `available_seats` int DEFAULT NULL,
  `booked_seats` int DEFAULT '0',
  `status` enum('upcoming','ongoing','completed','cancelled') COLLATE utf8mb4_general_ci DEFAULT 'upcoming',
  PRIMARY KEY (`departure_id`),
  KEY `tour_id` (`tour_id`),
  CONSTRAINT `departures_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`tour_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departures`
--

LOCK TABLES `departures` WRITE;
/*!40000 ALTER TABLE `departures` DISABLE KEYS */;
INSERT INTO `departures` VALUES (1,11,'2026-05-28','2026-05-30',10,10,0,'upcoming'),(2,2,'2026-06-01','2026-06-03',10,7,3,'upcoming'),(3,11,'2026-07-04','2026-07-05',10,9,1,'upcoming');
/*!40000 ALTER TABLE `departures` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `notification_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `booking_id` int DEFAULT NULL,
  `message` text COLLATE utf8mb4_general_ci,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`notification_id`),
  KEY `user_id` (`user_id`),
  KEY `booking_id` (`booking_id`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `partners`
--

DROP TABLE IF EXISTS `partners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `partners` (
  `partner_id` int NOT NULL AUTO_INCREMENT,
  `partner_name` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `contact_person` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`partner_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `partners`
--

LOCK TABLES `partners` WRITE;
/*!40000 ALTER TABLE `partners` DISABLE KEYS */;
INSERT INTO `partners` VALUES (1,'Vietravel','Nguyễn Văn B','0901234567','contact12@vietravel.com','190 Pasteur, Quận 3, TP.HCM','2026-03-17 00:15:54'),(2,'Saigontourist','Lê Thị B','0908889999','info@saigontourist.net','45 Lê Thánh Tôn, Quận 1, TP.HCM','2026-03-17 00:15:54'),(4,'QKTTravel','Nguyễn Văn K','0345675124','qktravel12@gmail.com','190 Pasteur, Quận 3, TP.HCM','2026-03-22 05:59:04');
/*!40000 ALTER TABLE `partners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `payment_id` int NOT NULL AUTO_INCREMENT,
  `booking_id` int NOT NULL,
  `amount` decimal(12,2) DEFAULT NULL,
  `payment_method` varchar(50) COLLATE utf8mb4_general_ci DEFAULT 'QR',
  `payment_status` enum('pending','paid','failed','refunded') COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `transaction_code` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `payment_date` datetime DEFAULT NULL,
  PRIMARY KEY (`payment_id`),
  KEY `booking_id` (`booking_id`),
  CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES (1,1,2000.00,'qr','paid','TXN1779110095279',NULL),(2,2,2000.00,'qr','paid','TXN1779116300181',NULL),(3,3,2000.00,'cod','pending',NULL,NULL),(4,4,2000.00,'cod','pending',NULL,NULL),(5,5,2000.00,'cod','paid',NULL,NULL),(6,6,2000.00,'qr','paid','TXN1779185565979',NULL),(7,7,2000.00,'qr','paid','TXN1779186285882',NULL),(8,8,2000.00,'qr','paid','TXN1779187216600',NULL),(9,9,2000.00,'qr','paid','TXN1779187828414',NULL);
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reviews` (
  `review_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `booking_id` int NOT NULL,
  `tour_id` int DEFAULT NULL,
  `rating` int DEFAULT NULL,
  `comment` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`review_id`),
  KEY `user_id` (`user_id`),
  KEY `tour_id` (`tour_id`),
  CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`tour_id`),
  CONSTRAINT `reviews_chk_1` CHECK ((`rating` between 1 and 5))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tour_guides`
--

DROP TABLE IF EXISTS `tour_guides`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tour_guides` (
  `assignment_id` int NOT NULL AUTO_INCREMENT,
  `departure_id` int NOT NULL,
  `staff_id` int NOT NULL,
  PRIMARY KEY (`assignment_id`),
  KEY `departure_id` (`departure_id`),
  KEY `staff_id` (`staff_id`),
  CONSTRAINT `tour_guides_ibfk_1` FOREIGN KEY (`departure_id`) REFERENCES `departures` (`departure_id`),
  CONSTRAINT `tour_guides_ibfk_2` FOREIGN KEY (`staff_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tour_guides`
--

LOCK TABLES `tour_guides` WRITE;
/*!40000 ALTER TABLE `tour_guides` DISABLE KEYS */;
/*!40000 ALTER TABLE `tour_guides` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tour_schedules`
--

DROP TABLE IF EXISTS `tour_schedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tour_schedules` (
  `schedule_id` int NOT NULL AUTO_INCREMENT,
  `tour_id` int NOT NULL,
  `day_number` int DEFAULT NULL,
  `location` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `activity` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`schedule_id`),
  KEY `tour_id` (`tour_id`),
  CONSTRAINT `tour_schedules_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`tour_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tour_schedules`
--

LOCK TABLES `tour_schedules` WRITE;
/*!40000 ALTER TABLE `tour_schedules` DISABLE KEYS */;
INSERT INTO `tour_schedules` VALUES (1,1,1,'Đà Nẵng','Đón sân bay, tham quan Bán đảo Sơn Trà, tắm biển Mỹ Khê.'),(2,1,2,'Bà Nà Hills','Vui chơi tại Fantasy Park, check-in Cầu Vàng.'),(3,1,3,'Hội An','Tham quan Phố cổ, mua sắm đặc sản và tiễn khách ra sân bay.'),(4,2,1,'Phú Quốc','Tham quan Dinh Cậu, vườn tiêu và nhà thùng nước mắm.'),(5,2,2,'Nam Đảo','Lặn ngắm san hô, tham quan Hòn Móng Tay.'),(6,2,3,'VinWonders','Vui chơi công viên giải trí, thủy cung.'),(7,2,4,'Phú Quốc','Tự do mua sắm đặc sản, tiễn khách.'),(8,3,1,'Đà Lạt','Tham quan Thác Datanla, Thiền Viện Trúc Lâm.'),(9,3,2,'Langbiang','Chinh phục đỉnh Langbiang, giao lưu cồng chiêng.'),(10,3,3,'Chợ Đà Lạt','Mua sắm đặc sản và check-out.'),(11,4,1,'Nha Trang','Tham quan Tháp Bà Ponagar, tắm biển.'),(12,4,2,'Tour đảo','Đi cano tham quan Hòn Mun, lặn biển.'),(13,4,3,'Tắm bùn','Trải nghiệm tắm bùn khoáng nóng.'),(14,5,1,'Bản Cát Cát','Khám phá bản làng người H’Mông.'),(15,5,2,'Fansipan','Đi cáp treo chinh phục đỉnh Fansipan.'),(16,5,3,'Sapa','Tham quan chợ Sapa, mua sắm.'),(17,6,1,'Hạ Long','Du thuyền tham quan vịnh Hạ Long.'),(18,6,2,'Hang Sửng Sốt','Tham quan hang động và trở về.'),(19,7,1,'Đại Nội','Tham quan Hoàng Thành Huế.'),(20,7,2,'Chùa Thiên Mụ','Tham quan chùa, nghe giới thiệu lịch sử.'),(21,8,1,'Nghĩa trang Hàng Dương','Viếng mộ cô Sáu, tham quan di tích.'),(22,8,2,'Biển Côn Đảo','Tắm biển, nghỉ dưỡng.'),(23,8,3,'Côn Đảo','Mua sắm đặc sản, kết thúc tour.');
/*!40000 ALTER TABLE `tour_schedules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tours`
--

DROP TABLE IF EXISTS `tours`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tours` (
  `tour_id` int NOT NULL AUTO_INCREMENT,
  `partner_id` int DEFAULT NULL,
  `tour_name` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `destination` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `price` decimal(12,2) DEFAULT NULL,
  `discount_percent` int DEFAULT '0',
  `duration` int DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_general_ci DEFAULT 'active',
  `created_by` int DEFAULT NULL,
  `hotel` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `include_service` text COLLATE utf8mb4_general_ci,
  `exclude_service` text COLLATE utf8mb4_general_ci,
  `itinerary` text COLLATE utf8mb4_general_ci,
  `image` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `slug` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`tour_id`),
  KEY `partner_id` (`partner_id`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `tours_ibfk_1` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`partner_id`),
  CONSTRAINT `tours_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tours`
--

LOCK TABLES `tours` WRITE;
/*!40000 ALTER TABLE `tours` DISABLE KEYS */;
INSERT INTO `tours` VALUES (1,1,'Tour Đà Nẵng - Hội An 3N2Đ','Đà Nẵng','Trải nghiệm chuyến du lịch tuyệt vời tại Đà Nẵng.',3600.00,15,3,'active',1,'3 - 4 sao','Xe đưa đón tham quan tại điểm đến, Khách sạn, Ăn uống, Hướng dẫn viên','Chi phí cá nhân, Thuế VAT, Vé máy bay/Tàu xe khứ hồi di chuyển đến điểm đón','Ngày 1: Đà Nẵng - Bà Nà Hills | Ngày 2: Hội An | Ngày 3: Sơn Trà - Ngũ Hành Sơn','tour1.png','2026-03-20 15:34:26','tour-da-nang-hoi-an-3n2d'),(2,2,'Tour Khám phá Đảo Ngọc Phú Quốc','Phú Quốc','Nghỉ dưỡng 4 sao, lặn ngắm san hô tại Nam Đảo.',2000.00,20,4,'active',1,'4 sao','Xe đưa đón tham quan tại điểm đến, Khách sạn, Ăn uống, Hướng dẫn viên','Chi phí cá nhân, Thuế VAT, Vé máy bay/Tàu xe khứ hồi di chuyển đến điểm đón','Ngày 1: Đến Phú Quốc | Ngày 2: VinWonders | Ngày 3: Lặn biển | Ngày 4: Trở về','tour2.png','2026-03-20 15:34:26','tour-kham-pha-dao-ngoc-phu-quoc'),(3,1,'Tour Đà Lạt Mộng Mơ 3N2Đ','Đà Lạt','Tham quan thành phố ngàn hoa với khí hậu mát mẻ.',2900000.00,20,3,'active',1,'3 sao','Xe đưa đón tham quan tại điểm đến, Khách sạn, Ăn sáng, Vé tham quan','Chi phí cá nhân, Vé máy bay/Tàu xe khứ hồi di chuyển đến điểm đón','Ngày 1: Thác Datanla | Ngày 2: Langbiang | Ngày 3: Chợ Đà Lạt','tour3.png','2026-03-20 15:34:26','tour-da-lat-mong-mo-3n2d'),(4,2,'Tour Nha Trang Biển Xanh 3N2Đ','Nha Trang','Khám phá biển đảo và các khu vui chơi tại Nha Trang.',3300000.00,10,3,'active',1,'4 sao','Xe đưa đón tham quan tại điểm đến, Khách sạn, Ăn uống, Cano ra đảo','Chi phí cá nhân, Thuế VAT, Vé máy bay/Tàu xe khứ hồi di chuyển đến điểm đón','Ngày 1: City Tour | Ngày 2: Tour đảo | Ngày 3: Tắm bùn','tour4.png','2026-03-20 15:34:26','tour-nha-trang-bien-xanh-3n2d'),(5,1,'Tour Sapa - Fansipan 3N2Đ','Sapa','Chinh phục Fansipan và khám phá văn hóa dân tộc.',4100000.00,20,3,'active',1,'3 sao','Xe, Khách sạn, Vé cáp treo, HDV','Chi phí cá nhân, Vé máy bay/Tàu xe khứ hồi di chuyển đến điểm đón','Ngày 1: Bản Cát Cát | Ngày 2: Fansipan | Ngày 3: Chợ Sapa','tour5.png','2026-03-20 15:34:26','tour-sapa-fansipan-3n2d'),(6,1,'Tour Hà Nội - Hạ Long 2N1Đ','Hạ Long','Du thuyền vịnh Hạ Long - kỳ quan thiên nhiên thế giới.',2500000.00,15,2,'active',1,'Du thuyền','Xe đưa đón tham quan tại điểm đến, Vé tham quan, Ăn uống','Chi phí cá nhân, Vé máy bay/Tàu xe khứ hồi di chuyển đến điểm đón','Ngày 1: Hà Nội - Hạ Long | Ngày 2: Hang Sửng Sốt - Trở về','tour6.png','2026-03-20 15:34:26','tour-ha-noi-ha-long-2n1d'),(7,2,'Tour Cố Đô Huế 2N1Đ','Huế','Khám phá di tích lịch sử và văn hóa Huế.',2200000.00,15,2,'active',1,'3 sao','Xe đưa đón tham quan tại điểm đến, Khách sạn, Vé tham quan','Chi phí cá nhân, Vé máy bay/Tàu xe khứ hồi di chuyển đến điểm đón','Ngày 1: Đại Nội | Ngày 2: Chùa Thiên Mụ','tour7.png','2026-03-20 15:34:26','tour-co-do-hue-2n1d'),(8,1,'Tour Côn Đảo 3N2Đ','Côn Đảo','Du lịch tâm linh và nghỉ dưỡng biển đảo.',4800000.00,0,3,'active',1,'4 sao','Khách sạn, Xe đưa đón tham quan tại điểm đến, HDV địa phương, Bảo hiểm du lịch','Chi phí cá nhân, Vé máy bay/Tàu xe khứ hồi di chuyển đến điểm đón','Ngày 1: Nghĩa trang Hàng Dương | Ngày 2: Tắm biển | Ngày 3: Trở về','tour8.png','2026-03-20 15:34:26','tour-con-dao-3n2d'),(11,4,'Tour Vũng Tàu 2N1Đ','Vũng Tàu','Nghỉ dưỡng biển, tham quan tượng Chúa Kitô.',2000.00,0,2,'active',NULL,'4 sao','Xe đưa đón tham quan tại điểm đến, Khách sạn, Ăn sáng','Chi phí cá nhân, Vé máy bay/Tàu xe khứ hồi di chuyển đến điểm đón','Ngày 1: Tắm biển | Ngày 2: Tham quan','1774159445_vungtau.webp','2026-03-22 13:04:05','tour-vung-tau-2n1d');
/*!40000 ALTER TABLE `tours` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('customer','tour_manager','guide','admin') COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_general_ci DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Trần Kiến Quốc','trankienquoc@gmail.com','0326753674','e10adc3949ba59abbe56e057f20f883e','admin','active','2026-03-16 01:38:25'),(2,'Lý Hải Nam','namlh@gmail.com','0912345678','$2y$10$v/WSy1jKCKfMrGgBiMwqj.fQju95PrUvpQ97juJUu543BC1/ndtj.','guide','active','2026-03-17 00:16:35'),(3,'Phạm Mỹ Linh','linhpm@gmail.com','0987654321','e10adc3949ba59abbe56e057f20f883e','customer','active','2026-03-17 00:16:35'),(4,'Hoàng Gia Bảo','baohg@gmail.com','0933445566','e10adc3949ba59abbe56e057f20f883e','guide','active','2026-03-17 00:16:35'),(5,'TranKienQuoc','trankienquoc12@gmail.com',NULL,'$2y$10$v/WSy1jKCKfMrGgBiMwqj.fQju95PrUvpQ97juJUu543BC1/ndtj.','tour_manager','active','2026-03-17 08:01:03'),(6,'6aye','atwbd12@gmail.com',NULL,'$2y$10$T.PnqQ8oMu1jsXaVeHO78.kbIVfLF7yJpi0YZAXULJIexACfqTLnG','customer','active','2026-03-18 02:04:15'),(7,'QKT1','trankienquoc12102004@gmail.com','0987654321','$2y$10$liIOybR9WT1TcFnSG4LsbeEdsQe4k3IvfyGda1opo3DOSQswwMSAO','customer','active','2026-03-20 14:55:55'),(8,'Administrator','admin@gmail.com','','$2y$10$5/Zqv5EgceqkgeE7zCh0o.KCo.vSA6a0jMkafdDMIHrkDd4Jenr8q','admin','active','2026-03-20 19:42:41'),(9,'Trần Văn K','tranvank@gmail.com','0345675124','$2y$10$S/Grk8n4TIH9idUOzXiHS.ihpUGLjG3gesDHRj5MsRkqQHTLuDDje','guide','active','2026-03-22 06:17:12'),(10,'Tour_manager','manager@gmail.com','0345675124','$2y$10$7eA91p4YpQgXpfoC48F9ZOnRR14qCwz.1WatrpnGtLZAjLsz1e12m','tour_manager','active','2026-03-22 14:09:51');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'defaultdb'
--

--
-- Dumping routines for database 'defaultdb'
--

--
-- Current Database: `defaultdb`
--



--
-- Table structure for table `blogs`
--

DROP TABLE IF EXISTS `blogs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blogs` (
  `blog_id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `short_desc` text COLLATE utf8mb4_unicode_ci,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`blog_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blogs`
--

LOCK TABLES `blogs` WRITE;
/*!40000 ALTER TABLE `blogs` DISABLE KEYS */;
INSERT INTO `blogs` VALUES (1,'Kinh nghiệm du lịch Sapa tự túc từ A-Z mùa săn mây','kinh-nghiem-du-lich-sapa-tu-tuc-tu-a-z-mua-san-may','Kinh nghiệm','blog1.jpg','Hướng dẫn chi tiết cách di chuyển, đặt phòng và các điểm check-in không thể bỏ lỡ khi đến Sapa mùa săn mây.','<p>Sapa luôn là điểm đến hấp dẫn du khách trong và ngoài nước. Để có một chuyến đi săn mây trọn vẹn, bạn nên ghé thăm vào khoảng tháng 9 đến tháng 11. Đừng quên mang theo áo ấm và giày thể thao để tiện di chuyển nhé!</p><ul><li>Đỉnh Fansipan</li><li>Bản Cát Cát</li><li>Cổng trời Ô Quy Hồ</li></ul>','2026-04-20 01:30:00'),(2,'Top 10 món ngon đặc sản Phú Quốc nhất định phải thử','top-10-mon-ngon-dac-san-phu-quoc-nhat-dinh-phai-thu','Ẩm thực','15-dac-san-phu-quoc.jpg','Bún quậy, gỏi cá trích, nhum biển... Khám phá bản đồ ẩm thực làm say đắm du khách tại Đảo Ngọc.','<p>Đến Phú Quốc mà không thưởng thức hải sản thì quả là một thiếu sót lớn. Gỏi cá trích cuốn bánh tráng chấm nước mắm chua ngọt, hay bát bún quậy Kiến Xây trứ danh chắc chắn sẽ làm hài lòng những thực khách khó tính nhất.</p>','2026-04-18 07:15:00'),(3,'Lịch trình phá đảo Đà Nẵng - Hội An 4 ngày 3 đêm','lich-trinh-pha-dao-da-nang-hoi-an-4-ngay-3-dem','Điểm đến','danang_hoian.jpg','Gợi ý lịch trình di chuyển tối ưu nhất để bạn khám phá trọn vẹn hai thành phố di sản miền Trung.','<p>Hành trình 4 ngày 3 đêm là khoảng thời gian lý tưởng để bạn khám phá sự sôi động của Đà Nẵng và nét cổ kính của Hội An. Ngày 1: Khám phá Bán đảo Sơn Trà. Ngày 2: Vui chơi tại Bà Nà Hills. Ngày 3: Tắm biển Mỹ Khê và di chuyển vào Hội An. Ngày 4: Dạo quanh phố cổ và mua sắm.</p>','2026-04-15 02:00:00'),(4,'Bí kíp xếp hành lý gọn nhẹ cho tour du lịch dài ngày','bi-kip-xep-hanh-ly-gon-nhe-cho-tour-du-lich-dai-ngay','Mẹo hay','blog2.png','Áp dụng ngay nguyên tắc cuộn tròn và sử dụng túi chiết để vali của bạn luôn gọn gàng, tiện lợi.','<p>Để tối ưu không gian vali, hãy cuộn tròn quần áo thay vì gấp phẳng. Sử dụng các túi chiết mỹ phẩm nhỏ gọn và tận dụng khoảng trống bên trong giày để nhét tất. Đừng quên mang theo một chiếc túi zip dự phòng để đựng đồ bẩn nhé!</p>','2026-04-10 09:45:00'),(6,'Khám phá Đảo Ngọc Phú Quốc 3 Ngày 2 Đêm: Trọn Bộ Bí Kíp Cho Người Mới','kham-pha-dao-ngoc-phu-quoc-3-ngay-2-dem-tron-bo-bi-kip-cho-nguoi-moi','Kinh nghiệm','1777383011_blog_phuquoc.webp','Bỏ túi ngay lịch trình chi tiết khám phá Phú Quốc 3 ngày 2 đêm. Từ những bãi biển xanh ngắt vắng người, đến những khu chợ đêm sầm uất và các món hải sản địa phương ăn là ghiền!','<p><strong>Phú Quốc</strong> - hòn đảo ngọc xinh đẹp nằm ở cực Nam Tổ quốc luôn là điểm đến hấp dẫn du khách trong và ngoài nước. Nếu bạn đang lên kế hoạch cho chuyến đi đầu tiên đến đây, đừng bỏ qua lịch trình 3 ngày 2 đêm cực kỳ tối ưu này của TravelVN nhé!</p>\r\n\r\n<h3>Ngày 1: Nhận phòng - Khám phá Bắc Đảo - Săn hoàng hôn</h3>\r\n<ul>\r\n    <li><strong>Sáng:</strong> Đáp chuyến bay đến Phú Quốc. Khởi hành về khách sạn khu vực Dương Đông để gửi hành lý. Vui chơi tại khu vực VinWonders và Safari.</li>\r\n    <li><strong>Trưa:</strong> Thưởng thức đặc sản bún quậy Kiến Xây trứ danh với phần chả tôm mực tươi rói.</li>\r\n    <li><strong>Chiều:</strong> Di chuyển đến OCSEN Beach Bar & Club hoặc Sunset Sanato để ngắm hoàng hôn rực rỡ nhất Việt Nam.</li>\r\n    <li><strong>Tối:</strong> Dạo Chợ đêm Phú Quốc, thưởng thức hải sản nướng mỡ hành và đậu phộng Chou Chou.</li>\r\n</ul>\r\n\r\n<h3>Ngày 2: Tour 4 đảo - Lặn ngắm san hô - Cáp treo Hòn Thơm</h3>\r\n<p>Đây là ngày bạn sẽ dành trọn vẹn cho biển cả. Hãy đặt ngay một tour cano khám phá các hòn đảo nhỏ phía Nam:</p>\r\n<ul>\r\n    <li><strong>Hòn Móng Tay & Hòn Gầm Ghì:</strong> Trải nghiệm lặn ngắm san hô tự nhiên tuyệt đẹp với làn nước trong vắt nhìn thấy đáy.</li>\r\n    <li><strong>Hòn Mây Rút:</strong> Nghỉ ngơi, tắm biển và chụp những bức ảnh check-in sống ảo cực \"chill\" với xích đu vô cực.</li>\r\n    <li><strong>Chiều muộn:</strong> Trải nghiệm tuyến cáp treo vượt biển dài nhất thế giới từ Hòn Thơm về lại ga An Thới.</li>\r\n</ul>\r\n\r\n<h3>Ngày 3: Mua sắm đặc sản - Tạm biệt Đảo Ngọc</h3>\r\n<p>Sáng ngày cuối cùng, hãy thuê xe máy dạo quanh thị trấn, ghé thăm nhà thùng nước mắm, vườn tiêu hoặc cơ sở sản xuất ngọc trai để mua quà cho người thân. Tầm 11h, bạn làm thủ tục trả phòng và di chuyển ra sân bay.</p>\r\n\r\n<hr>\r\n\r\n<p><em><strong>Lưu ý nhỏ từ TravelVN:</strong> Thời điểm lý tưởng nhất để vi vu Phú Quốc là từ tháng 11 đến tháng 4 năm sau (mùa khô). Đừng quên chuẩn bị kem chống nắng và một chiếc máy ảnh đầy pin nhé! Bạn có thể đặt ngay các <strong><a href=\"#\">Tour Phú Quốc trọn gói</a></strong> của chúng tôi để nhận ưu đãi bất ngờ!</em></p>','2026-04-28 10:39:12');
/*!40000 ALTER TABLE `blogs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bookings` (
  `booking_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `departure_id` int NOT NULL,
  `customer_name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pickup_address` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `booking_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `number_of_people` int DEFAULT NULL,
  `total_price` decimal(12,2) DEFAULT NULL,
  `note` text COLLATE utf8mb4_general_ci,
  `status` enum('pending','confirmed','cancelled','refunded','completed','checked_in') COLLATE utf8mb4_general_ci DEFAULT 'pending',
  PRIMARY KEY (`booking_id`),
  KEY `user_id` (`user_id`),
  KEY `departure_id` (`departure_id`),
  CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`departure_id`) REFERENCES `departures` (`departure_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookings`
--

LOCK TABLES `bookings` WRITE;
/*!40000 ALTER TABLE `bookings` DISABLE KEYS */;
INSERT INTO `bookings` VALUES (1,3,1,'Phạm Mỹ Linh','linhpm@gmail.com','0987654321','Tự đến Điểm hẹn tập trung','2026-05-18 13:14:55',1,2000.00,'--- YÊU CẦU HỦY BỞI KHÁCH HÀNG ---\nLÝ DO HỦY: Sức khỏe.\nTHÔNG TIN NHẬN HOÀN TIỀN: Ngân hàng momo, STK: 0375452033, Chủ thẻ: đoàn thị trâm.','refunded'),(2,3,1,'Phạm Mỹ Linh','linhpm@gmail.com','0987654321','Tự đến Điểm hẹn tập trung','2026-05-18 14:58:20',1,2000.00,'--- YÊU CẦU HỦY BỞI KHÁCH HÀNG ---\nLÝ DO HỦY: Đặt nhầm.\nTHÔNG TIN NHẬN HOÀN TIỀN: Ngân hàng momo, STK: 0375452033, Chủ thẻ: đoàn thị trâm.\n\n--- YÊU CẦU HỦY BỞI KHÁCH HÀNG ---\nLÝ DO HỦY: Sức khỏe.\nTHÔNG TIN NHẬN HOÀN TIỀN: Ngân hàng momo, STK: 0375452033, Chủ thẻ: đoàn thị trâm.','refunded'),(3,3,1,'Phạm Mỹ Linh','linhpm@gmail.com','0987654321','Tự đến Điểm hẹn tập trung','2026-05-18 15:14:45',1,2000.00,'--- YÊU CẦU HỦY BỞI KHÁCH HÀNG ---\nLÝ DO HỦY: Đặt nhầm.','cancelled'),(4,3,1,'Phạm Mỹ Linh','linhpm@gmail.com','0987654321','Tự đến Điểm hẹn tập trung','2026-05-18 15:16:00',1,2000.00,'--- YÊU CẦU HỦY BỞI KHÁCH HÀNG ---\nLÝ DO HỦY: Sức khỏe.','cancelled'),(5,3,2,'Phạm Mỹ Linh','linhpm@gmail.com','0987654321','Tự đến Điểm hẹn tập trung','2026-05-19 14:37:50',1,2000.00,'','confirmed'),(6,3,2,'Phạm Mỹ Linh','linhpm@gmail.com','0987654321','Tự đến Điểm hẹn tập trung','2026-05-19 17:12:45',1,2000.00,'','confirmed'),(7,3,2,'Phạm Mỹ Linh','linhpm@gmail.com','0987654321','Tự đến Điểm hẹn tập trung','2026-05-19 17:24:45',1,2000.00,'','confirmed'),(8,6,2,'6aye','atwbd12@gmail.com','0987654321','Tự đến Điểm hẹn tập trung','2026-05-19 17:40:16',1,2000.00,'--- YÊU CẦU HỦY BỞI KHÁCH HÀNG ---\nLÝ DO HỦY: Đặt nhầm.\nTHÔNG TIN NHẬN HOÀN TIỀN: Ngân hàng momo, STK: 0375452033, Chủ thẻ: đoàn thị trâm.','refunded'),(9,6,3,'6aye','atwbd12@gmail.com','0987654321','Tự đến Điểm hẹn tập trung','2026-05-19 17:50:28',1,2000.00,'','confirmed');
/*!40000 ALTER TABLE `bookings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chat_messages`
--

DROP TABLE IF EXISTS `chat_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chat_messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `session_id` varchar(100) NOT NULL,
  `departure_id` int DEFAULT NULL,
  `sender_type` enum('customer','admin','guide','tour_manager') NOT NULL,
  `sender_name` varchar(150) DEFAULT NULL,
  `message` text,
  `file_url` text,
  `message_type` enum('text','image','audio','location','file') DEFAULT 'text',
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chat_messages`
--

LOCK TABLES `chat_messages` WRITE;
/*!40000 ALTER TABLE `chat_messages` DISABLE KEYS */;
INSERT INTO `chat_messages` VALUES (1,'user_3',NULL,'customer','Phạm Mỹ Linh','cgdsgds',NULL,'text',1,'2026-05-20 03:18:51'),(2,'user_3',NULL,'admin','TravelVN Bot','🤖 Xin chào! TravelVN đã nhận được tin nhắn của bạn. Chúng tôi sẽ phản hồi sớm nhất ❤️',NULL,'text',1,'2026-05-20 03:18:51'),(3,'user_3',NULL,'customer','Phạm Mỹ Linh',NULL,'https://res.cloudinary.com/dooiu0qs0/image/upload/v1779248223/travelvn/chat/j5g26pyeq1uf2dye2hwa.jpg','image',1,'2026-05-20 03:37:04'),(11,'user_3',NULL,'customer','Phạm Mỹ Linh',NULL,'https://res.cloudinary.com/dooiu0qs0/video/upload/v1779251714/travelvn/chat/audio/cftohi7lkqgysw78frhl.webm','audio',1,'2026-05-20 04:35:15'),(12,'user_3',2,'customer','Phạm Mỹ Linh',NULL,'https://res.cloudinary.com/dooiu0qs0/video/upload/v1779252784/travelvn/chat/audio/bqdvhqm8mgse3bpjgfdj.webm','audio',1,'2026-05-20 04:53:04'),(13,'guest_8c5ba7a6a65cdde85f5b65a8d9021af3',NULL,'customer','Khách vãng lai',NULL,'https://res.cloudinary.com/dooiu0qs0/video/upload/v1779253416/travelvn/chat/audio/erdrnjmvvsegzfrfvinf.webm','audio',1,'2026-05-20 05:03:37'),(14,'guest_8c5ba7a6a65cdde85f5b65a8d9021af3',NULL,'customer','Khách vãng lai',NULL,'https://res.cloudinary.com/dooiu0qs0/video/upload/v1779253475/travelvn/chat/audio/b0qbnjx6oe74jnsesyxm.webm','audio',1,'2026-05-20 05:04:36'),(15,'guest_8c5ba7a6a65cdde85f5b65a8d9021af3',NULL,'customer','Khách vãng lai',NULL,'https://res.cloudinary.com/dooiu0qs0/image/upload/v1779253492/travelvn/chat/eyvemcm8attyyszdtedb.jpg','image',1,'2026-05-20 05:04:52'),(16,'user_3',2,'customer','Phạm Mỹ Linh',NULL,'https://res.cloudinary.com/dooiu0qs0/video/upload/v1779253580/travelvn/chat/audio/vasnkwo6ksetrm49jmpz.webm','audio',1,'2026-05-20 05:06:21'),(18,'guest_4c8021ab5ba9fbd9bea0a6e25aff65de',NULL,'customer','Khách vãng lai',NULL,'https://res.cloudinary.com/dooiu0qs0/image/upload/v1779804812/travelvn/chat/kjbdmutcvo7qsamta49o.jpg','image',1,'2026-05-26 14:13:33'),(19,'guest_4c8021ab5ba9fbd9bea0a6e25aff65de',NULL,'customer','Khách vãng lai',NULL,'https://res.cloudinary.com/dooiu0qs0/image/upload/v1779805489/travelvn/chat/exdhhvtzcnmpgqjqn97t.jpg','image',1,'2026-05-26 14:24:50');
/*!40000 ALTER TABLE `chat_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `checkins`
--

DROP TABLE IF EXISTS `checkins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `checkins` (
  `checkin_id` int NOT NULL AUTO_INCREMENT,
  `booking_id` int DEFAULT NULL,
  `staff_id` int DEFAULT NULL,
  `checkin_time` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`checkin_id`),
  KEY `booking_id` (`booking_id`),
  KEY `staff_id` (`staff_id`),
  CONSTRAINT `checkins_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`),
  CONSTRAINT `checkins_ibfk_2` FOREIGN KEY (`staff_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `checkins`
--

LOCK TABLES `checkins` WRITE;
/*!40000 ALTER TABLE `checkins` DISABLE KEYS */;
INSERT INTO `checkins` VALUES (1,5,2,'2026-05-19 17:10:43');
/*!40000 ALTER TABLE `checkins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departure_guides`
--

DROP TABLE IF EXISTS `departure_guides`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `departure_guides` (
  `id` int NOT NULL AUTO_INCREMENT,
  `departure_id` int DEFAULT NULL,
  `guide_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `departure_id` (`departure_id`),
  KEY `guide_id` (`guide_id`),
  CONSTRAINT `departure_guides_ibfk_1` FOREIGN KEY (`departure_id`) REFERENCES `departures` (`departure_id`),
  CONSTRAINT `departure_guides_ibfk_2` FOREIGN KEY (`guide_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departure_guides`
--

LOCK TABLES `departure_guides` WRITE;
/*!40000 ALTER TABLE `departure_guides` DISABLE KEYS */;
INSERT INTO `departure_guides` VALUES (6,4,9),(7,3,2),(9,6,9),(10,7,2),(11,8,2),(15,10,2),(16,5,2),(17,9,2),(21,11,2),(24,2,2),(25,3,2),(26,1,2);
/*!40000 ALTER TABLE `departure_guides` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departures`
--

DROP TABLE IF EXISTS `departures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `departures` (
  `departure_id` int NOT NULL AUTO_INCREMENT,
  `tour_id` int NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `max_seats` int DEFAULT NULL,
  `available_seats` int DEFAULT NULL,
  `booked_seats` int DEFAULT '0',
  `status` enum('upcoming','ongoing','completed','cancelled') COLLATE utf8mb4_general_ci DEFAULT 'upcoming',
  PRIMARY KEY (`departure_id`),
  KEY `tour_id` (`tour_id`),
  CONSTRAINT `departures_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`tour_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departures`
--

LOCK TABLES `departures` WRITE;
/*!40000 ALTER TABLE `departures` DISABLE KEYS */;
INSERT INTO `departures` VALUES (1,11,'2026-05-28','2026-05-30',10,10,0,'upcoming'),(2,2,'2026-06-01','2026-06-03',10,7,3,'upcoming'),(3,11,'2026-07-04','2026-07-05',10,9,1,'upcoming');
/*!40000 ALTER TABLE `departures` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `notification_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `booking_id` int DEFAULT NULL,
  `message` text COLLATE utf8mb4_general_ci,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`notification_id`),
  KEY `user_id` (`user_id`),
  KEY `booking_id` (`booking_id`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `partners`
--

DROP TABLE IF EXISTS `partners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `partners` (
  `partner_id` int NOT NULL AUTO_INCREMENT,
  `partner_name` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `contact_person` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`partner_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `partners`
--

LOCK TABLES `partners` WRITE;
/*!40000 ALTER TABLE `partners` DISABLE KEYS */;
INSERT INTO `partners` VALUES (1,'Vietravel','Nguyễn Văn B','0901234567','contact12@vietravel.com','190 Pasteur, Quận 3, TP.HCM','2026-03-17 00:15:54'),(2,'Saigontourist','Lê Thị B','0908889999','info@saigontourist.net','45 Lê Thánh Tôn, Quận 1, TP.HCM','2026-03-17 00:15:54'),(4,'QKTTravel','Nguyễn Văn K','0345675124','qktravel12@gmail.com','190 Pasteur, Quận 3, TP.HCM','2026-03-22 05:59:04');
/*!40000 ALTER TABLE `partners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `payment_id` int NOT NULL AUTO_INCREMENT,
  `booking_id` int NOT NULL,
  `amount` decimal(12,2) DEFAULT NULL,
  `payment_method` varchar(50) COLLATE utf8mb4_general_ci DEFAULT 'QR',
  `payment_status` enum('pending','paid','failed','refunded') COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `transaction_code` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `payment_date` datetime DEFAULT NULL,
  PRIMARY KEY (`payment_id`),
  KEY `booking_id` (`booking_id`),
  CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES (1,1,2000.00,'qr','paid','TXN1779110095279',NULL),(2,2,2000.00,'qr','paid','TXN1779116300181',NULL),(3,3,2000.00,'cod','pending',NULL,NULL),(4,4,2000.00,'cod','pending',NULL,NULL),(5,5,2000.00,'cod','paid',NULL,NULL),(6,6,2000.00,'qr','paid','TXN1779185565979',NULL),(7,7,2000.00,'qr','paid','TXN1779186285882',NULL),(8,8,2000.00,'qr','paid','TXN1779187216600',NULL),(9,9,2000.00,'qr','paid','TXN1779187828414',NULL);
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reviews` (
  `review_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `booking_id` int NOT NULL,
  `tour_id` int DEFAULT NULL,
  `rating` int DEFAULT NULL,
  `comment` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`review_id`),
  KEY `user_id` (`user_id`),
  KEY `tour_id` (`tour_id`),
  CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`tour_id`),
  CONSTRAINT `reviews_chk_1` CHECK ((`rating` between 1 and 5))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tour_guides`
--

DROP TABLE IF EXISTS `tour_guides`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tour_guides` (
  `assignment_id` int NOT NULL AUTO_INCREMENT,
  `departure_id` int NOT NULL,
  `staff_id` int NOT NULL,
  PRIMARY KEY (`assignment_id`),
  KEY `departure_id` (`departure_id`),
  KEY `staff_id` (`staff_id`),
  CONSTRAINT `tour_guides_ibfk_1` FOREIGN KEY (`departure_id`) REFERENCES `departures` (`departure_id`),
  CONSTRAINT `tour_guides_ibfk_2` FOREIGN KEY (`staff_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tour_guides`
--

LOCK TABLES `tour_guides` WRITE;
/*!40000 ALTER TABLE `tour_guides` DISABLE KEYS */;
/*!40000 ALTER TABLE `tour_guides` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tour_schedules`
--

DROP TABLE IF EXISTS `tour_schedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tour_schedules` (
  `schedule_id` int NOT NULL AUTO_INCREMENT,
  `tour_id` int NOT NULL,
  `day_number` int DEFAULT NULL,
  `location` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `activity` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`schedule_id`),
  KEY `tour_id` (`tour_id`),
  CONSTRAINT `tour_schedules_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`tour_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tour_schedules`
--

LOCK TABLES `tour_schedules` WRITE;
/*!40000 ALTER TABLE `tour_schedules` DISABLE KEYS */;
INSERT INTO `tour_schedules` VALUES (1,1,1,'Đà Nẵng','Đón sân bay, tham quan Bán đảo Sơn Trà, tắm biển Mỹ Khê.'),(2,1,2,'Bà Nà Hills','Vui chơi tại Fantasy Park, check-in Cầu Vàng.'),(3,1,3,'Hội An','Tham quan Phố cổ, mua sắm đặc sản và tiễn khách ra sân bay.'),(4,2,1,'Phú Quốc','Tham quan Dinh Cậu, vườn tiêu và nhà thùng nước mắm.'),(5,2,2,'Nam Đảo','Lặn ngắm san hô, tham quan Hòn Móng Tay.'),(6,2,3,'VinWonders','Vui chơi công viên giải trí, thủy cung.'),(7,2,4,'Phú Quốc','Tự do mua sắm đặc sản, tiễn khách.'),(8,3,1,'Đà Lạt','Tham quan Thác Datanla, Thiền Viện Trúc Lâm.'),(9,3,2,'Langbiang','Chinh phục đỉnh Langbiang, giao lưu cồng chiêng.'),(10,3,3,'Chợ Đà Lạt','Mua sắm đặc sản và check-out.'),(11,4,1,'Nha Trang','Tham quan Tháp Bà Ponagar, tắm biển.'),(12,4,2,'Tour đảo','Đi cano tham quan Hòn Mun, lặn biển.'),(13,4,3,'Tắm bùn','Trải nghiệm tắm bùn khoáng nóng.'),(14,5,1,'Bản Cát Cát','Khám phá bản làng người H’Mông.'),(15,5,2,'Fansipan','Đi cáp treo chinh phục đỉnh Fansipan.'),(16,5,3,'Sapa','Tham quan chợ Sapa, mua sắm.'),(17,6,1,'Hạ Long','Du thuyền tham quan vịnh Hạ Long.'),(18,6,2,'Hang Sửng Sốt','Tham quan hang động và trở về.'),(19,7,1,'Đại Nội','Tham quan Hoàng Thành Huế.'),(20,7,2,'Chùa Thiên Mụ','Tham quan chùa, nghe giới thiệu lịch sử.'),(21,8,1,'Nghĩa trang Hàng Dương','Viếng mộ cô Sáu, tham quan di tích.'),(22,8,2,'Biển Côn Đảo','Tắm biển, nghỉ dưỡng.'),(23,8,3,'Côn Đảo','Mua sắm đặc sản, kết thúc tour.');
/*!40000 ALTER TABLE `tour_schedules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tours`
--

DROP TABLE IF EXISTS `tours`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tours` (
  `tour_id` int NOT NULL AUTO_INCREMENT,
  `partner_id` int DEFAULT NULL,
  `tour_name` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `destination` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `price` decimal(12,2) DEFAULT NULL,
  `discount_percent` int DEFAULT '0',
  `duration` int DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_general_ci DEFAULT 'active',
  `created_by` int DEFAULT NULL,
  `hotel` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `include_service` text COLLATE utf8mb4_general_ci,
  `exclude_service` text COLLATE utf8mb4_general_ci,
  `itinerary` text COLLATE utf8mb4_general_ci,
  `image` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `slug` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`tour_id`),
  KEY `partner_id` (`partner_id`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `tours_ibfk_1` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`partner_id`),
  CONSTRAINT `tours_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tours`
--

LOCK TABLES `tours` WRITE;
/*!40000 ALTER TABLE `tours` DISABLE KEYS */;
INSERT INTO `tours` VALUES (1,1,'Tour Đà Nẵng - Hội An 3N2Đ','Đà Nẵng','Trải nghiệm chuyến du lịch tuyệt vời tại Đà Nẵng.',3600.00,15,3,'active',1,'3 - 4 sao','Xe đưa đón tham quan tại điểm đến, Khách sạn, Ăn uống, Hướng dẫn viên','Chi phí cá nhân, Thuế VAT, Vé máy bay/Tàu xe khứ hồi di chuyển đến điểm đón','Ngày 1: Đà Nẵng - Bà Nà Hills | Ngày 2: Hội An | Ngày 3: Sơn Trà - Ngũ Hành Sơn','tour1.png','2026-03-20 15:34:26','tour-da-nang-hoi-an-3n2d'),(2,2,'Tour Khám phá Đảo Ngọc Phú Quốc','Phú Quốc','Nghỉ dưỡng 4 sao, lặn ngắm san hô tại Nam Đảo.',2000.00,20,4,'active',1,'4 sao','Xe đưa đón tham quan tại điểm đến, Khách sạn, Ăn uống, Hướng dẫn viên','Chi phí cá nhân, Thuế VAT, Vé máy bay/Tàu xe khứ hồi di chuyển đến điểm đón','Ngày 1: Đến Phú Quốc | Ngày 2: VinWonders | Ngày 3: Lặn biển | Ngày 4: Trở về','tour2.png','2026-03-20 15:34:26','tour-kham-pha-dao-ngoc-phu-quoc'),(3,1,'Tour Đà Lạt Mộng Mơ 3N2Đ','Đà Lạt','Tham quan thành phố ngàn hoa với khí hậu mát mẻ.',2900000.00,20,3,'active',1,'3 sao','Xe đưa đón tham quan tại điểm đến, Khách sạn, Ăn sáng, Vé tham quan','Chi phí cá nhân, Vé máy bay/Tàu xe khứ hồi di chuyển đến điểm đón','Ngày 1: Thác Datanla | Ngày 2: Langbiang | Ngày 3: Chợ Đà Lạt','tour3.png','2026-03-20 15:34:26','tour-da-lat-mong-mo-3n2d'),(4,2,'Tour Nha Trang Biển Xanh 3N2Đ','Nha Trang','Khám phá biển đảo và các khu vui chơi tại Nha Trang.',3300000.00,10,3,'active',1,'4 sao','Xe đưa đón tham quan tại điểm đến, Khách sạn, Ăn uống, Cano ra đảo','Chi phí cá nhân, Thuế VAT, Vé máy bay/Tàu xe khứ hồi di chuyển đến điểm đón','Ngày 1: City Tour | Ngày 2: Tour đảo | Ngày 3: Tắm bùn','tour4.png','2026-03-20 15:34:26','tour-nha-trang-bien-xanh-3n2d'),(5,1,'Tour Sapa - Fansipan 3N2Đ','Sapa','Chinh phục Fansipan và khám phá văn hóa dân tộc.',4100000.00,20,3,'active',1,'3 sao','Xe, Khách sạn, Vé cáp treo, HDV','Chi phí cá nhân, Vé máy bay/Tàu xe khứ hồi di chuyển đến điểm đón','Ngày 1: Bản Cát Cát | Ngày 2: Fansipan | Ngày 3: Chợ Sapa','tour5.png','2026-03-20 15:34:26','tour-sapa-fansipan-3n2d'),(6,1,'Tour Hà Nội - Hạ Long 2N1Đ','Hạ Long','Du thuyền vịnh Hạ Long - kỳ quan thiên nhiên thế giới.',2500000.00,15,2,'active',1,'Du thuyền','Xe đưa đón tham quan tại điểm đến, Vé tham quan, Ăn uống','Chi phí cá nhân, Vé máy bay/Tàu xe khứ hồi di chuyển đến điểm đón','Ngày 1: Hà Nội - Hạ Long | Ngày 2: Hang Sửng Sốt - Trở về','tour6.png','2026-03-20 15:34:26','tour-ha-noi-ha-long-2n1d'),(7,2,'Tour Cố Đô Huế 2N1Đ','Huế','Khám phá di tích lịch sử và văn hóa Huế.',2200000.00,15,2,'active',1,'3 sao','Xe đưa đón tham quan tại điểm đến, Khách sạn, Vé tham quan','Chi phí cá nhân, Vé máy bay/Tàu xe khứ hồi di chuyển đến điểm đón','Ngày 1: Đại Nội | Ngày 2: Chùa Thiên Mụ','tour7.png','2026-03-20 15:34:26','tour-co-do-hue-2n1d'),(8,1,'Tour Côn Đảo 3N2Đ','Côn Đảo','Du lịch tâm linh và nghỉ dưỡng biển đảo.',4800000.00,0,3,'active',1,'4 sao','Khách sạn, Xe đưa đón tham quan tại điểm đến, HDV địa phương, Bảo hiểm du lịch','Chi phí cá nhân, Vé máy bay/Tàu xe khứ hồi di chuyển đến điểm đón','Ngày 1: Nghĩa trang Hàng Dương | Ngày 2: Tắm biển | Ngày 3: Trở về','tour8.png','2026-03-20 15:34:26','tour-con-dao-3n2d'),(11,4,'Tour Vũng Tàu 2N1Đ','Vũng Tàu','Nghỉ dưỡng biển, tham quan tượng Chúa Kitô.',2000.00,0,2,'active',NULL,'4 sao','Xe đưa đón tham quan tại điểm đến, Khách sạn, Ăn sáng','Chi phí cá nhân, Vé máy bay/Tàu xe khứ hồi di chuyển đến điểm đón','Ngày 1: Tắm biển | Ngày 2: Tham quan','1774159445_vungtau.webp','2026-03-22 13:04:05','tour-vung-tau-2n1d');
/*!40000 ALTER TABLE `tours` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('customer','tour_manager','guide','admin') COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_general_ci DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Trần Kiến Quốc','trankienquoc@gmail.com','0326753674','e10adc3949ba59abbe56e057f20f883e','admin','active','2026-03-16 01:38:25'),(2,'Lý Hải Nam','namlh@gmail.com','0912345678','$2y$10$v/WSy1jKCKfMrGgBiMwqj.fQju95PrUvpQ97juJUu543BC1/ndtj.','guide','active','2026-03-17 00:16:35'),(3,'Phạm Mỹ Linh','linhpm@gmail.com','0987654321','e10adc3949ba59abbe56e057f20f883e','customer','active','2026-03-17 00:16:35'),(4,'Hoàng Gia Bảo','baohg@gmail.com','0933445566','e10adc3949ba59abbe56e057f20f883e','guide','active','2026-03-17 00:16:35'),(5,'TranKienQuoc','trankienquoc12@gmail.com',NULL,'$2y$10$v/WSy1jKCKfMrGgBiMwqj.fQju95PrUvpQ97juJUu543BC1/ndtj.','tour_manager','active','2026-03-17 08:01:03'),(6,'6aye','atwbd12@gmail.com',NULL,'$2y$10$T.PnqQ8oMu1jsXaVeHO78.kbIVfLF7yJpi0YZAXULJIexACfqTLnG','customer','active','2026-03-18 02:04:15'),(7,'QKT1','trankienquoc12102004@gmail.com','0987654321','$2y$10$liIOybR9WT1TcFnSG4LsbeEdsQe4k3IvfyGda1opo3DOSQswwMSAO','customer','active','2026-03-20 14:55:55'),(8,'Administrator','admin@gmail.com','','$2y$10$5/Zqv5EgceqkgeE7zCh0o.KCo.vSA6a0jMkafdDMIHrkDd4Jenr8q','admin','active','2026-03-20 19:42:41'),(9,'Trần Văn K','tranvank@gmail.com','0345675124','$2y$10$S/Grk8n4TIH9idUOzXiHS.ihpUGLjG3gesDHRj5MsRkqQHTLuDDje','guide','active','2026-03-22 06:17:12'),(10,'Tour_manager','manager@gmail.com','0345675124','$2y$10$7eA91p4YpQgXpfoC48F9ZOnRR14qCwz.1WatrpnGtLZAjLsz1e12m','tour_manager','active','2026-03-22 14:09:51');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'defaultdb'
--

--
-- Dumping routines for database 'defaultdb'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-28 21:08:49
