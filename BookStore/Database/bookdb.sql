-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 03, 2024 at 04:17 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bookdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `additional_images`
--

CREATE TABLE `additional_images` (
  `additional_images_ID` int(11) NOT NULL,
  `Product_ID` int(11) NOT NULL,
  `Image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `additional_images`
--

INSERT INTO `additional_images` (`additional_images_ID`, `Product_ID`, `Image`) VALUES
(1, 1, 'tro-chuyen-khi-yeu-b04.jpg'),
(2, 1, 'tro-chuyen-khi-yeu-mockup.jpg'),
(3, 2, 'dong-luc-va-nhan-cach-b04.jpg'),
(4, 2, 'dong-luc-va-nhan-cach-mockup.jpg'),
(5, 4, 'duong-xa-nang-moi-b04.jpg'),
(6, 4, 'duong-xa-nang-moi-mockup.jpg'),
(7, 5, 'da-chanh-tuyet-mockup.jpg'),
(8, 6, 'cau-dung-khoc-to-cung-cau-ngam-hoa-b04.jpg'),
(9, 6, 'cau-dung-khoc-to-cung-cau-ngam-hoa-mockup.jpg'),
(10, 7, 'dat-ten-thuong-hieu-mockup.jpg'),
(11, 8, 'marketing-bang-su-that-b04.jpg'),
(12, 8, 'marketing-bang-su-that-mockup.jpg'),
(15, 17, 'shin-cau-be-but-chi-tien-len-bo-robot-b04.jpg'),
(16, 17, 'shin-cau-be-but-chi-tien-len-bo-robot-mockup.jpg'),
(17, 19, 'khong-gia-dinh-nao-hoan-hao-b04.jpg'),
(18, 19, 'khong-gia-dinh-nao-hoan-hao-mockup.jpg'),
(19, 20, 'pho-va-cac-mon-nuoc-tb-2024-b04.jpg'),
(20, 20, 'pho-va-cac-mon-nuoc-tb-2024-mockup.jpg'),
(21, 20, 'pho-va-cac-mon-nuoc-tb-2024-mockup-02.jpg'),
(22, 20, 'pho-va-cac-mon-nuoc-tb-2024-mockup-03.jpg'),
(23, 21, 'an-trai-cay-phai-dung-cach-mockup.jpg'),
(24, 21, 'an-trai-cay-phai-dung-cach-mockup-02.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `UserName` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Product_ID` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `Category_ID` int(11) NOT NULL,
  `Category_Name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`Category_ID`, `Category_Name`) VALUES
(1, 'Văn Học'),
(2, 'Kinh Tế - Kinh Doanh'),
(3, 'Thiếu Nhi'),
(4, 'Tạp Chí'),
(5, 'Truyện Tranh - Manga - Comic'),
(6, 'Thường Thức - Đời Sống'),
(7, 'Tâm Lý - Giáo Dục');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `Order_ID` int(11) NOT NULL,
  `Username` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Recipient` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Address` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Phone_Number` varchar(11) NOT NULL,
  `Total_Price` decimal(10,0) NOT NULL,
  `Type_Payment` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_detail`
--

CREATE TABLE `order_detail` (
  `Order_ID` int(11) NOT NULL,
  `Product_ID` int(11) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `Price` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `Product_ID` int(11) NOT NULL,
  `Title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Author` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Image` varchar(255) NOT NULL,
  `Price` decimal(10,0) NOT NULL,
  `Discount` int(11) NOT NULL,
  `price_after_discount` decimal(10,0) NOT NULL,
  `Publisher` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Language` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Stars` float NOT NULL DEFAULT 0,
  `Category_ID` int(11) NOT NULL,
  `Sold` int(11) NOT NULL DEFAULT 0,
  `IsActive` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`Product_ID`, `Title`, `Author`, `Image`, `Price`, `Discount`, `price_after_discount`, `Publisher`, `Description`, `Language`, `Stars`, `Category_ID`, `Sold`, `IsActive`) VALUES
(1, 'Trò Chuyện Khi ', 'Vanessa Marin, Xander Marin', 'tro-chuyen-khi-yeu.jpg', 165000, 0, 165000, 'NXB Thanh Niên', 'Có những điều rất cần phải nói ra những bạn lại sợ khiến “nửa kia” của mình tổn thương, và rồi bạn quyết định không nói nữa và để cho mối quan hệ của mình xuất hiện những rạn nứt. Nhưng khi một lời phê bình được đưa ra, đó cũng chính là lúc bạn khiến nửa kia của mình tự ti đến mức không thể cứu vãn được.\r\n\r\nCó hai kiểu người trên thế giới: những người cần phải có cảm giác kết nối về mặt cảm xúc để có thể làm tình, và những người cần phải làm tình để cảm thấy được kết nối. Trong một vòng xoay nghiệt ngã của số phận, hầu hết các mối quan hệ đều được cấu thành từ hai người thuộc hai kiểu nói trên. Điều mà chúng ta cần có khi lại trái ngược với điều mà người ấy của chúng ta cần thành ra có rất nhiều cặp đôi cảm thấy vô cùng bế tắc.\r\n\r\nTrò chuyện khi “yêu” sẽ đưa ra những hướng tiếp cận mới để những cặp đôi có thể gia tăng được sự kết nối trong mối quan hệ của mình. Vượt lên trên những câu chuyện về tình dục, cuốn sách sẽ khiến các bạn nhận ra rằng:\r\n\r\n“Điều tốt đẹp nhất bạn có thể mang lại cho đời sống tình dục của mình thậm chí chẳng liên quan gì tới việc trút bỏ áo quần.”', 'Việt', 0.5, 7, 0, 1),
(2, 'Động Lực Và Nhân Cách', 'Abraham H. Maslow', 'dong-luc-va-nhan-cach.jpg', 195000, 0, 195000, 'NXB Dân Trí', 'Cuốn sách này đưa người đọc bước vào một hành trình khám phá bản thân với những phân tích sâu sắc quan điểm của nhiều nhà tâm lý học nổi tiếng như Freud, Jung, đồng thời trình bày quan điểm đột phá của chính tác giả Maslow về nhu cầu của con người.\r\n\r\nKhác với các nhà tâm lý học trước mình, Maslow cho rằng các nhu cầu không xung đột lẫn nhau mà được xếp thành một hệ thống phân cấp với tầng thấp nhất là các nhu cầu sinh lý như không khí, thức ăn, nước uống; tầng thứ hai là các nhu cầu tâm lý như an toàn, được yêu thương, được tôn trọng; và cao nhất là nhu cầu “tự hiện thực hóa”. Đây là nhu cầu đặc biệt chỉ có ở con người, một động lực bẩm sinh ở mỗi cá nhân trong việc phát huy tiềm năng cao nhất và trở thành phiên bản tốt nhất của chính mình, không phải hành động vì phần thưởng từ bên ngoài.\r\n\r\nVới quan điểm lấy người khỏe mạnh làm trọng tâm nghiên cứu thay vì người ốm bệnh, Maslow không chỉ trả lời câu hỏi “Con người là gì?” mà còn trả lời câu hỏi “Con người sẽ trở thành gì?”. Lý thuyết tiến bộ của ông có ý nghĩa quan trọng trọng việc tìm hiểu động lực tại nơi làm việc, cũng như thấy trước được việc tìm kiếm sự hưng phấn và phát hay khả năng bản thân sẽ trở thành động lực cao hơn tiền bạc trong công việc.', 'Việt', 2, 7, 0, 1),
(3, 'Tâm Lý Học Thật Kỳ Diệu', 'Trương Hân', 'tam-ly-hoc-that-ky-dieu.jpg', 210000, 0, 210000, 'NXB Phụ Nữ', 'Chúng ta hãy cùng nhau trải nghiệm sự kỳ diệu của tâm lý học!\r\n\r\nHiểu thấu lòng người - Trang bị cho bạn những kỹ năng nhìn người để thấu hiểu bản chất đối phương, dễ dàng xử lý các tình huống và sàng lọc các mối quan hệ.\r\n\r\nChuẩn mực xã hội - Những nguyên tắc, khuôn mẫu hành vi, thái độ, định kiế tự hình thành hoặc được thừa nhận, công khai, dùng để đo đạc và phán xét hành vi, hoạt động, tác phong, thói ứng xử, quan niệm, thái độ của cá nhân hoặc cộng đồng.\r\n\r\nNâng cao nhận thức - Nhìn nhận bản thân một cách khách quan, chấp nhận những điểm yếu của bản thân để tìm phương án sửa chữa tốt nhất. Hình thành thói quen thích nghi với môi trường mới và những điều mới mẻ, nâng cao kỹ năng số\r\n\r\nPhát triển suốt đời - Xem xét cách suy nghĩ, cảm xúc, hành thay đổi trong suốt cuộc đời của một con người.', 'Việt', 1, 7, 8, 1),
(4, 'Đường Xa Nắng Mới', 'Nguyễn Tường Bách', 'duong-xa-nang-moi.jpg', 223000, 20, 178400, 'NXB Hội Nhà Văn', 'Đường xa nắng mới của Nguyễn Tường Bách không chỉ đưa độc giả phiêu lãng khắp nơi qua những chuyến du hành thú vị, mà còn chia sẻ với người đọc những trải nghiệm sâu sắc trên con đường ngao du thế giới bên ngoài để chứng nghiệm những đổi thay trong nội tâm.\r\n\r\nHơn phân nửa tập sách là bút ký ghi lại cuộc hành hương chiêm bái núi Kailash (Tây Tạng) ở độ cao trên 5.000m do tác giả và 21 người Việt Nam cùng tổ chức đi vào tháng 8-2011. Do vậy, tập bút ký vừa mang tính thời sự vừa ướp đậm những trải nghiệm tâm linh với nét nhìn tinh tế trong mỗi sự vật.\r\n\r\nThông tin tác giả\r\n\r\nNGUYỄN TƯỜNG BÁCH - Sinh năm 1948 tại Thừa Thiên Huế; Du học tại Đức năm 1967; Tốt nghiệp Kỹ sư Xây dựng năm 1975; Tốt nghiệp Tiến sĩ Kỹ thuật (Dr.-lng) năm 1980 và làm việc cho một số công ty ở Đức;\r\n\r\n- Hiện sống tại CHLB Đức;\r\n\r\n- Dịch giả của: Con đường mây trắng (Anagarika Govinda), Đối diện cuộc đời (Jiddu Krishnamurti), Sư tử tuyết bờm xanh (Surya Das), Thiên trong nghệ thuật bắn cung (Eugen Herrigel), Đạo của vật lý (Fritjof Capra)...,\r\n\r\n- Tác giả của các tập truyện, bút ký, tản văn, tiểu luận: Mùi hương trầm, Mộng đời bất tuyệt, Lưới trời ai dệt, Đêm qua sân trước một cành mai, Đường rộng thênh thang.', 'Việt', 2, 1, 7, 1),
(5, 'Đá Chanh Tuyết', 'Má Bánh Bao', 'da-chanh-tuyet.jpg', 189000, 0, 189000, 'NXB Hà Nội', 'Trường tôi có một hot boy phố núi rất nổi tiếng. Cậu ta đẹp trai, nhà bán vàng, nhưng lại ăn chơi, lười học và hẹn hò với rất nhiều bạn gái xinh xắn sở hữu tính cách quái đản.\r\n\r\nCho đến một ngày tôi không còn thấy bạn hot boy họ Trịnh đó tham gia vào các buổi ăn chơi náo loạn cùng đám anh em, cũng không còn mập mờ dây dưa với các bạn nữ.\r\n\r\nTheo lời đồn từ những chiếc miệng chạy bằng bánh tráng trộn và trà sữa, hiện giờ bạn Huy Anh đã kịp thời “giác ngộ”, chuyên tâm học hành, quyết tâm đậu đại học Bách Khoa vì muốn theo đuổi sự nghiệp.\r\n\r\nMãi về sau, tôi mới biết “sự nghiệp” của Huy Anh có tên là Mộc Miên, cao 1m60, mắt tam bạch màu hổ phách, tóc dài cháy nắng, ngoan hiền, hiểu chuyện và đặc biệt là bạn thân “thầm kín” hơn bốn năm trời, người khiến cậu ta biến thành bộ dạng buông không được, bỏ không xong.', 'Việt', 1, 1, 3, 1),
(6, 'Cậu Đừng Khóc, Tớ Cùng Cậu Đi Ngắm Hoa', 'Lạc Yến', 'cau-dung-khoc-to-cung-cau-ngam-hoa.jpg', 102000, 0, 102000, 'NXB Văn Học', 'Sẽ luôn có những ngày mà khi cậu mở mắt thức dậy, điều đầu tiên cậu cảm thấy chính là sợ hãi thế giới này. Cậu không muốn bước chân xuống giường, không muốn làm bất cứ chuyện gì cả. Cậu vẫn ôm ấp giấc mơ ngốc nghếch là được làm một con sâu lười đến hết cuộc đời. Thế nhưng, thực tế cuộc sống lúc nào cũng nghiệt ngã hơn mong ước. Cậu vẫn phải dậy, vẫn phải tiếp tục những công việc dang dở từ hôm qua, bắt đầu một ngày mới không có nhiều năng lượng.\r\n\r\nSẽ luôn có những ngày mà cậu cảm thấy cả thế giới giống như bỏ quên mình. Cậu lướt mạng xã hội trong vô thức, không một tin nhắn, chẳng một cuộc gọi. Cậu vẫn là cậu, thế nhưng lại chẳng có ai bận tâm đến. Chỉ cần lên Facebook là nhìn thấy mọi người đi chơi, chia sẻ cuộc sống thú vị của họ, lòng cậu lại khó chịu, lại ước giá mà có ai đó ở bên.\r\n\r\nSẽ luôn có những ngày mà cậu cảm thấy lạc lõng giữa thế giới này. Cậu không biết nên đi đâu, nên làm gì, cậu trốn trong một góc phòng, suy nghĩ vẩn vơ về những mối quan hệ xung quanh. Cuối cùng phát hiện ra, bản thân, lại chẳng có lấy một người đủ tin tưởng để chia sẻ mọi chuyện.\r\n\r\nNếu cậu đang lạc lõng trong những ngày chẳng mong muốn như thế hãy lật giở từng trang trong cuốn sách “CẬU ĐỪNG KHÓC, TỚ CÙNG CẬU ĐI NGẮM HOA”. Chẳng hứa sẽ chữa lành ngay vết thương trong lòng cậu nhưng chắc chắn sẽ xoa dịu tâm hồn đầy chơi vơi đang muốn bật khóc của cậu. Chẳng mong gì nhiều, chỉ mong cậu có thể bình yên đi qua những ngày như thế.\r\n\r\nBởi vì cuộc đời rất đẹp, đừng từ bỏ nó dễ dàng vì bất kì chuyện gì. Bởi có những thời điểm như thế nên càng biết trân quý cuộc sống của bản thân ở thời điểm hiện tại. Mọi khó khăn rồi cũng sẽ qua, lạc đường cũng chẳng còn đáng sợ như trước.\r\n\r\nXuyên suốt 232 trang sách, tác giả Lạc Yến gửi gắm từng lời an ủi, khích lệ bạn: “Mỗi khi tưởng chừng như không thể cố gắng được nữa, hãy ngẩng mặt lên trời. Trời xanh đẹp như thế, chắc chắn giúp cậu có niềm tin để giải quyết mọi chuyện. Chúng ta hãy cùng nhau cố lên nhé!”', 'Việt', 4, 1, 0, 1),
(7, 'Đặt Tên Thương Hiệu', 'Leader Thanh', 'dat-ten-thuong-hieu.jpg', 100000, 0, 100000, 'NXB Thế Giới', 'TẦM QUAN TRỌNG CỦA TÊN THƯƠNG HIỆU\r\n\r\nTên thương hiệu là một thành tố cực kỳ quan trọng của thương hiệu, vì nó chính là sự khác biệt đầu tiên và lớn nhất giữa sản phẩm/ dịch vụ này với sản phẩm/ dịch vụ khác cùng loại.\r\n\r\nTrong dài hạn, thương hiệu cũng chỉ tồn tại dưới dạng một cái tên.\r\n\r\nVà theo thời gian, nó cũng chính là tài sản lớn nhất của doanh nghiệp.\r\n\r\nCái tên có thể làm nên một thương hiệu mạnh. Vì vậy sở hữu một tên độc đáo và hấp dẫn sẽ là một lợi thế rất lớn. Nó không chỉ giúp bạn khác biệt với đối thủ cạnh tranh mà còn gây thiện cảm và kết nối với khách hàng mục tiêu một cách đầy cảm xúc.\r\n\r\nChính vì vậy đặt tên thương hiệu bao giờ cũng là \'việc trọng tâm\' của bất kì một kế hoạch phát triển thương hiệu nào.\r\n\r\nBẠN ĐÃ HIỂU ĐÚNG VỀ THƯƠNG HIỆU?\r\n\r\n80% mọi người chưa hiểu đúng về thương hiệu và nhầm lẫn giữa hai khái niệm: thương hiệu và nhãn hiệu. Đây là phát biểu của chuyên gia thương hiệu số 1 Việt Nam.\r\n\r\nVà rất... rất... rất nhiều người nghĩ rằng: thương hiệu chính là cái tên, cái logo, cái biểu tượng.\r\n\r\nKhông chỉ Việt Nam mà thế giới cũng lẫn lộn giữa hai khái niệm này (brand và trademark).\r\n\r\nVậy thương hiệu là gì, bạn đã chắc chắn hiểu đúng về nó chưa?\r\n\r\nVà thương hiệu khác nhãn hiệu ở những điểm gì?\r\n\r\nThương hiệu đã xuất hiện và ra đời như thế nào trong lịch sử buôn bán của con người?\r\n\r\nCó những dạng thương hiệu nào? Và \'thương hiệu doanh nghiệp\' thì gồm mấy loại?\r\n\r\nTất cả những điều trên sẽ được giải đáp một cách rõ ràng nhất, chi tiết nhất và dễ hiểu nhất thông qua cuốn sách \"Đặt tên thương hiệu\" của tác giả Leader THANH.', 'Việt', 3.5, 2, 0, 1),
(8, 'Marketing Bằng Sự Thật', 'David Gallagher, John O\'Brien', 'marketing-bang-su-that.jpg', 158000, 0, 158000, 'NXB Hồng Đức', 'MARKETING BẰNG SỰ THẬT là cuốn cẩm nang dành cho các giám đốc điều hành muốn biết cách truyền đạt hành động và mục đích rộng lớn hơn cho các bên liên quan với thế giới bên ngoài, kể cả trong những thời điểm không chắc chắn và khó khăn như hiện tại.\r\n\r\nTrong những năm gần đây, sức mạnh của nguồn dữ liệu và trí tuệ nhân tạo cùng với các công nghệ khác đã thúc đẩy phát triển hoạt động kinh doanh, vì những lý do chính đáng. Nhưng mặt khác, dữ liệu và công nghệ không thể thay thế sự thật về con người.\r\n\r\nBằng cách hiểu rõ và nhấn mạnh hơn về nhu cầu của con người trong cả hoạt động kinh doanh và truyền thông thương hiệu, bạn sẽ thay đổi cách mà nhân viên, khách hàng, nhà cung cấp và nhà đầu tư nhận định và đánh giá doanh nghiệp của bạn. Trước hết, bạn sẽ thay đổi cách tư duy và cách hành xử; sau đó, những gì bạn làm để tiếp thị, giao tiếp và bán sản phẩm, kể cả doanh nghiệp và sản phẩm của bạn sẽ trở nên tự nhiên và rõ ràng nhất.\r\n\r\nNếu bạn đã mở lòng, sẵn sàng vượt ra khỏi quy chuẩn để mạnh dạn bước vào tương lai đầy biến động, không chắc chắn, hỗn loạn và mơ hồ, đây là cuốn sách dành cho bạn.', 'Việt', 0, 2, 0, 1),
(10, 'Bộ Sách Tri Thức Bách Khoa - Những Điều Em Muốn Biết? - Combo 1 (Bộ 10 cuốn)', 'Nhiều Tác Giả', 'bo-sach-tri-thuc-bach-khoa-nhung-kien-thuc-co-ban-combo-1.jpg', 195000, 0, 195000, 'NXB Thanh Hóa', 'Xung quanh bé biết bao điều kỳ thú và trẻ nhỏ luôn tò mò về thế giới để tìm hiểu, khám phá. Bộ sách Cùng Bé Khám Phá Thế Giới Xung Quanh sẽ giúp bố mẹ hướng dẫn các bé nhận biết thế giới xung quanh như thế nào qua những hình vẽ trực quan chân thật, sống động kích thích thị giác cùng nhiều câu đố, đơn giản, dễ nghe dễ hiểu để khuyến khích bé không ngần ngại hay e dè trong việc tìm hiểu, khám phá thế giới. Bộ sách Cùng Bé Khám Phá Thế Giới Xung Quanh là bộ sách song ngữ vì thế sẽ giúp trẻ làm quen với tiếng Anh, phát triển tư duy khái niệm về đồ vật, động vật, hoa, thế giới.', 'Việt', 0, 3, 0, 1),
(11, 'Bộ Sách Tri Thức Bách Khoa - Khám Phá Thế Giới Quanh Ta - Combo 3 (Bộ 10 cuốn)', 'Nhiều Tác Giả', 'bo-sach-tri-thuc-bach-khoa-nhung-kien-thuc-co-ban-combo-3.jpg', 195000, 0, 195000, 'Nhiều Tác Giả', 'Xung quanh bé biết bao điều kỳ thú và trẻ nhỏ luôn tò mò về thế giới để tìm hiểu, khám phá. Bộ sách Cùng Bé Khám Phá Thế Giới Xung Quanh sẽ giúp bố mẹ hướng dẫn các bé nhận biết thế giới xung quanh như thế nào qua những hình vẽ trực quan chân thật, sống động kích thích thị giác cùng nhiều câu đố, đơn giản, dễ nghe dễ hiểu để khuyến khích bé không ngần ngại hay e dè trong việc tìm hiểu, khám phá thế giới. Bộ sách Cùng Bé Khám Phá Thế Giới Xung Quanh là bộ sách song ngữ vì thế sẽ giúp trẻ làm quen với tiếng Anh, phát triển tư duy khái niệm về đồ vật, động vật, hoa, thế giới.', 'Việt', 2, 3, 0, 1),
(12, 'Bộ Sách Tri Thức Bách Khoa - Những Kiến Thức Cơ Bản - Combo 2 (Bộ 10 cuốn)', 'Nhiều Tác Giả', 'bo-sach-tri-thuc-bach-khoa-nhung-kien-thuc-co-ban-combo-2.jpg', 195000, 0, 195000, 'NXB Thanh Hóa', 'Xung quanh bé biết bao điều kỳ thú và trẻ nhỏ luôn tò mò về thế giới để tìm hiểu, khám phá. Bộ sách Cùng Bé Khám Phá Thế Giới Xung Quanh sẽ giúp bố mẹ hướng dẫn các bé nhận biết thế giới xung quanh như thế nào qua những hình vẽ trực quan chân thật, sống động kích thích thị giác cùng nhiều câu đố, đơn giản, dễ nghe dễ hiểu để khuyến khích bé không ngần ngại hay e dè trong việc tìm hiểu, khám phá thế giới. Bộ sách Cùng Bé Khám Phá Thế Giới Xung Quanh là bộ sách song ngữ vì thế sẽ giúp trẻ làm quen với tiếng Anh, phát triển tư duy khái niệm về đồ vật, động vật, hoa, thế giới.', 'Việt', 3, 3, 0, 1),
(13, 'Hoa Học Trò - Số 1406', 'Nhiều Tác Giả', 'hoa-hoc-tro-so-1406.jpg', 20000, 0, 20000, 'Hoa Học Trò', 'Hàng loạt thông tin, câu chuyện hấp dẫn trong tháng Tư này đang chờ teen tại Hoa Học Trò 1406 đấy! Khám phá ngay thôi nào!\r\n\r\nBạn có muốn trở thành \"vệ thần\" của người tiêu dùng Gen Z?\r\n\r\nKhông còn cái thời teen hay bị “phân biệt đối xử” khi sử dụng dịch vụ, phải nuốt ấm ức vào bụng. Trong thời đại của các công ty social listening (nghiên cứu dư luận xã hội), mỗi lượt thích, bình luận, hay chia sẻ của bạn đều có sức nặng với các quyết định kinh doanh của các nhãn hàng. Đây cũng chính là mảnh đất nghề nghiệp đầy hứa hẹn cho bạn phát triển đấy, cùng tìm hiểu trên Hoa Học Trò 1406 nào!\r\n\r\nChậm lại một chút trước trào lưu Speed-up\r\n\r\nNhững ca khúc được đẩy nhanh tốc độ, thêm hiệu ứng Cap Cut “giật giật” không chỉ dẫn đầu xu hướng TikTok mà còn đang mở ra những phương thức sản xuất và phát hành âm nhạc mới. Liệu có nên tua ngược trào lưu tua nhanh?\r\n\r\nBắt lấy \"cơ hội tháng Tư\" từ các trường Đại học\r\n\r\nTrong tháng Tư này, các bạn học sinh sẽ có cơ hội được miễn lệ phí nộp hồ sơ và nhận những suất học bổng siêu chất lượng từ VinUniversity, ĐH Swinburne Việt Nam và Đại học RMIT. Hãy cùng nhà Hoa tìm hiểu thông tin chi tiết về những cơ hội trong tháng khởi động mùa tuyển sinh này nhé!\r\n\r\nHè đến rồi, \"detox\" TikTok thôi!\r\n\r\nGiữ ngôi đầu bảng trong các ứng dụng mạng xã hội hiện nay nhưng TikTok cũng liên tục vướng phải lùm xùm về việc kiểm định nội dung, không chủ động ngăn chặn những video độc hại, phản cảm. Ngày càng nhiều những trào lưu nhảm nhí, thậm chí là nguy hiểm được lan truyền trên nền tảng này, đòi hỏi người dùng phải tỉnh táo, tự tạo bộ lọc cho mình nếu không muốn rơi vào “bẫy” vui ảo, họa thật.', 'Việt', 2.5, 4, 0, 1),
(14, 'Thiên Thần Nhỏ - Ấn Phẩm Của Báo Tiền Phong (Số 479)', 'Nhiều Tác Giả', 'thien-than-nho-so-479.jpg', 24000, 0, 24000, 'Nhiều Tác Giả', 'Bên cạnh những môn học quen thuộc ở trường, chúng ta cũng rất nên học kỹ năng tìm kiếm và tự tạo hạnh phúc cho chính mình đấy! Vì dù học hành giỏi giang mà không cảm nhận được hạnh phúc thì cũng không ổn chút nào.\r\n\r\nMỹ thuật hạnh phúc cùng Olympians\r\n\r\nTạm gác những giờ Mỹ thuật vẽ tranh chân dung, phong cảnh, tĩnh vật như thông thường, các bạn tween trường The Olympia Schools (Hà Nội) đang được tham gia vào một dự án siêu vui và ý nghĩa đó là vẽ mảnh ghép hạnh phúc.\r\n\r\nCác Olympians sẽ cùng nhau phác họa hạnh phúc của mình qua những bức tranh nhỏ, ghép lại với nhau để tạ nên một bức tường hạnh phúc \"siêu to khổng lồ\". Bức tường hạnh phúc hứa hẹn sẽ là một không gian ấm áp riêng có của tween The Olympia Schools, nơi các bạn học sinh có thể tự hào giới thiệu với gia đình, bạn bè khi họ đến thăm trường đấy!\r\n\r\nTạm biệt những thói quen không giúp bạn hạnh phúc\r\n\r\nVí dụ như vừa ngồi WC vừa nghịch điện thoại/xem sách, báo vì chúng ta thường ngồi lâu hơn cần thiết. Cứ kéo dài như vậy, khả năng bạn mắc… bệnh trĩ là rất cao. Tốt nhất, khi vào nhà vệ sinh, bạn chỉ nên ở trong đó khoảng 10 phút là vừa đẹp.\r\n\r\nNgồi vắt chéo chân lại khiến sức khỏe của tween khóc mếu. Bởi khi ngồi vắt chéo chân, mạch máu ở chân không lưu thông được dẫn đến đau cổ, đau lưng và cột sống còn non của tween sẽ bị biến dạng.\r\n\r\nNói chuyện thế nào cho đáng yêu hơn?\r\n\r\nAi chẳng có lúc phải nhờ người khác giúp đỡ mình. Nhưng thay vì nói câu khẳng định, bạn hãy thêm đuôi “được không” để biến nó thành một câu hỏi đi. Chẳng hạn đừng nói “Ngày mai mang truyện Lớp Học Mật Ngữ cho tớ mượn nhé” mà đổi thành “Ngày mai cậu mang truyện cho tớ mượn được không?”. Thay vì đề nghị “mẹ nấu mỳ Ý đi” thì bạn nhỏ nhẹ “mẹ ơi con muốn ăn mỳ Ý được không?, cùng một ý nghĩa nhưng lại khiến mẹ thấy vui vẻ vào bếp ngay đó.\r\n\r\nThêm từ “được không” chẳng tốn sức mà hiệu quả vô cùng, vì câu nhờ vả của bạn khiến đối phương thấy được tôn trọng và vì thế không thể nào từ chối bạn được đâu.', 'Việt', 0, 4, 0, 1),
(15, 'Hoa Học Trò - Số 1404', 'Nhiều Tác Giả', 'hoa-hoc-tro-so-1404.jpg', 20000, 0, 20000, 'Hoa Học Trò', 'Hoa Học Trò 1404 mang đến cho bạn đọc những câu chuyện hấp dẫn về đời sống, \"vũ trụ\" giải trí của teen cùng thông tin hay ho trong thế giới muôn màu nhất định không thể bỏ qua!\r\n\r\nThế giới mạng xã hội chào đón những thay đổi\r\n\r\nĐầu năm nay, những ông lớn trong thế giới mạng xã hội dành cho Gen Z như Facebook, Instagram và TikTok đều công bố một số thay đổi lớn. Chúng có ảnh hưởng ra sao đến bạn và có thể giúp gì cho hội content creator? Cùng Hoa Học Trò 1404 \"khui\" những thông tin thú vị nha!\r\n\r\nCông thức ẵm học bổng du học Châu Á từ các \"thợ săn\"\r\n\r\nNhật Bản, Trung Quốc, Hàn Quốc và Singapore đã trở thành những “bến đỗ” mơ ước của Gen Z trên hành trình chinh phục tri thức, đặc biệt là khi ngày càng có nhiều suất học bổng “rủng rỉnh” dành cho du học sinh tại các quốc gia này. Để có thể chạm đến những “tấm vé” danh giá này một cách hiệu quả, hãy cùng lắng nghe các bí quyết từ các “chiến thần” học bổng châu Á nhé!\r\n\r\nThư Bangkok: Bật mí sức hút của dòng phim BL từ xứ sở chùa vàng\r\n\r\nTrong Lễ hội Countdown 2023 hoành tráng nhất lịch sử Central World (Bangkok), chiếu trực tiếp đến quảng trường Thời đại tại New York (Mỹ), PP Krit và Billkin (nổi lên từ bộ phim I Told Sunset about You) đứng ngang hàng với các ngôi sao tên tuổi đình đám như Bi Rain và các nhà lãnh đạo của Thái Lan để cùng đếm ngược khoảnh khắc đón năm mới.\r\n\r\n\"Lúc ấy mình mới cảm nhận được sức ảnh hưởng khủng khiếp của dòng phim Boys’ Love (BL), không chỉ riêng với khán giả Thái Lan, mà còn trên phạm vi toàn cầu\" - nếu bạn cũng là fan của dòng phim này thì hãy đọc ngay \"tâm sự\" của Lân Bảo trên báo Hoa Học Trò 1404 nhé.\r\n\r\nTranh cãi trong tuyển sinh vào 10: Cơ hội và thách thức cho teen?\r\n\r\nNhiều tỉnh, thành trên cả nước đã công bố kế hoạch tuyển sinh lớp 10, trong đó có thêm những phương án tuyển sinh mới khiến kỳ thi đầu cấp này trở thành đề tài được teen và phụ huynh hết sức quan tâm. Vậy teen mình cần có phương pháp học ra sao để tránh rơi vào thế bị động?', 'Việt', 0, 4, 0, 1),
(16, 'Thám Tử Lừng Danh Conan - Hanzawa Chàng Hung Thủ Số Nhọ - Tập 7', 'Mayuko Kanba, Gosho Aoyama', 'conan-hanzawa-chang-hung-thu-so-nho-tap-7.jpg', 25000, 0, 25000, 'NXB Kim Đồng', 'Beika - nơi được mệnh danh là lãnh địa của thần chết, vì muốn trả thù “gã đó” - tức Kudo Shinichi, mà ngày nào Hanzawa cũng cất công luyện cách ám sát ở khu phố nguy hiểm ấy. Nhưng trong quãng thời gian này, hắn vẫn cùng bạn thân từ nhỏ Saki đi biển!? Băn khoăn giữa tình yêu và tội ác, chả lẽ đây chính là số phận của hắn ở Beika…?', 'Việt', 0, 5, 0, 1),
(17, 'Shin Cậu Bé Bút Chì Truyện Dài - Tập 13: Tiến Lên Bố Robot!!', 'Kazuki Nakashima, Kenta Aiba, Yoshito Usui', 'shin-cau-be-but-chi-tien-len-bo-robot.jpg', 50000, 0, 50000, 'NXB Kim Đồng', 'Với tài năng kể chuyện hấp dẫn, tác giả đã biến các trang sách của mình thành những sân chơi ngập tràn tiếng cười của những cô bé, cậu bé hồn nhiên và một thế giới tuổi thơ đa sắc màu. Những bài học giáo dục nhẹ nhàng, thấm thía cũng được lồng ghép một cách khéo léo trong từng tình huống truyện. Có thể Shin là một cậu bé cá tính, hiếu động. Có thể những trò tinh nghịch của Shin đôi khi quá trớn, chẳng chừa một ai. Nhưng sau những \"sự cố\" do Shin gây ra, người lớn thấy mình cần \"quan tâm\" đến trẻ con nhiều hơn nữa, các bạn đọc nhỏ tuổi chắc hẳn cũng được dịp nhìn nhận lại bản thân, để phân biệt điều tốt điều xấu trong cuộc sống.', 'Việt', 0, 5, 0, 1),
(18, 'One-Punch Man - Tập 27', 'One, Yusuke Murata', 'one-punchman-tap-27.jpg', 25000, 0, 25000, 'NXB Kim Đồng', 'Đụng độ quái nhân gia nhiệt, một trong những đầu lĩnh của hội Quái Nhân, các anh hùng cấp S thật sự rơi vào bế tắc… Trong lúc đó, Tatsumaki vẫn và đang truy lùng chân tướng của Gyoro Gyoro, còn Siêu Hợp Kim Đen Bóng và Garo thì quần nhau té lửa, chỉ có Saitama là lạc lối trong mê cung dưới lòng đất…!?', 'Việt', 0, 5, 0, 1),
(19, 'Không Gia Đình Nào Hoàn Hảo', 'Lucy Blake', 'khong-gia-dinh-nao-hoan-hao.jpg', 110000, 0, 110000, 'NXB Phụ Nữ', 'Bạn định nghĩa gia đình là gì?\r\n\r\nGia đình là nơi ẩn náu khỏi những thăng trầm của cuộc sống hằng ngày. Gia đình giống như một tấm lưới an toàn, chở che và bảo vệ.\r\n\r\nGia đình là yêu thương. Và không giống như bất kì tình yêu thương nào khác, đó là tình yêu thương vô điều kiện. Cho dù có làm gì hay nói gì, bạn cũng không thể tìm được lời giải thích xác đáng cho tình yêu thương giữa các thành viên trong gia đình.\r\n\r\nNhưng thực tế là Không Gia Đình Nào Hoàn Hảo vì gia đình nào cũng có những mâu thuẫn, xung đột và bất đồng.\r\n\r\nBạn sẽ rất ngạc nhiên rằng có rất nhiều người như bạn. Chỉ khi CHẤP NHẬN sự thật đó, dám nói ra và đối mặt với nó thì chúng ta mới được giải phóng khỏi nỗi đau khổ âm thầm của mình, tích cực tìm kiếm các giải pháp và sự giúp đỡ để cải thiện hoàn cảnh. Như vậy chúng ta mới có thể sống và phát triển trong một môi trường gia đình hạnh phúc thực sự.\r\n\r\nBạn học được gì từ cuốn sách Không Gia Đình Nào Hoàn Hảo?\r\n\r\nCuốn sách Không Gia Đình Nào Hoàn Hảo sẽ giúp bạn loại bỏ cảm giác lo âu, sự hoài nghi. Khắc phục cả việc lí tưởng hóa lẫn tiêu cực hóa, cuốn sách sẽ thay đổi cách chúng ta nghĩ và viết về gia đình. Đây là cuốn sách hữu ích cho bất kì ai quan tâm đến việc tìm hiểu thêm về gia đình của chính họ.', 'Việt', 0, 6, 0, 1),
(20, 'Phở Và Các Món Nước (Tái bản năm 2024)', 'Bùi Thị Sương', 'pho-va-cac-mon-nuoc-tb-2024.jpg', 116000, 0, 116000, 'NXB Phụ Nữ', 'Phở và các món nấu có nước dùng từ lâu đã rất quen thuộc với người Việt, mang đậm đặc trưng văn hóa ẩm thực của từng vùng miền. Các món ăn này vừa như thức ăn nhanh, lại cũng rất cân đối, hài hòa về giá trị dinh dưỡng – chỉ trong một tô bún hay tô phở, tô hủ tiếu... chúng ta có chất đạm, có rau xanh, tinh bột... đủ để ăn no và thật ngon lành, hấp dẫn. Ngày nay, ngoài món phở đã trở nên nổi tiếng trong nước và trên thế giới, các món nấu có nước dùng khác cũng đang dần vượt khỏi địa lý vùng miền và “hội nhập”. Ta có thể thưởng thức bún bò Huế, mì Quảng, bánh canh Trảng Bàng, bún cá Kiên Giang ở rất nhiều nơi trên lãnh thổ Việt Nam.\r\n\r\nXin được giới thiệu với bạn đọc xa gần những món ăn có nước dùng đặc sắc của người Việt, với mong muốn các món ăn này ngày một lan xa, lan rộng bởi sự hấp dẫn, độc đáo và dinh dưỡng của chúng.\r\n\r\n“Một số món ăn khác được giới thiệu trong sách như: phở bò, phở gà, bánh đa cua Hải Phòng, bún riêu cua, bún thang, bún ốc, bún măng vịt,… của miền Bắc. Miền Trung đặc sắc với bún cá Đà Nẵng, Cao lầu, bánh canh Nam Phổ, bánh canh chả cá Nha Trang,… Hay ngọt ngào hương vị miền Nam với hủ tiếu Mỹ Tho, bún suông, bún nước lèo Trà Vinh, bánh canh cua, bún cá Campuchia,…”', 'Việt', 0, 6, 0, 1),
(21, 'Ăn Trái Cây Phải Đúng Cách', 'Vương Húc Phong', 'an-trai-cay-phai-dung-cach.jpg', 139000, 0, 139000, 'NXB Thế Giới', 'BẠN CÓ CHẮC RẰNG MÌNH ĐANG ĂN TRÁI CÂY ĐÚNG CÁCH KHÔNG?\r\n\r\nBạn nghĩ rằng ăn trái cây sau bữa ăn là tốt?\r\n\r\nBạn thấy ăn trái cây trực tiếp hay xay ra uống thì cũng đều như nhau?\r\n\r\nBạn ăn trái cây để giảm cân?\r\n\r\n…\r\n\r\nThực tế thì mỗi loại trái cây đều sẽ có những đặc tính, công dụng khác nhau, vì vậy, chúng ta cần phải tìm hiểu xem loại nào phù hợp với cơ thể mình. Và tất tần tật mọi kiến thức về trái cây đều sẽ được giải đáp trong cuốn sách “Ăn trái cây phải đúng cách”.\r\n\r\n“Ăn trái cây phải đúng cách” được viết bởi Vương Húc Phong - Chủ tịch điều hành hiệp hội Y tế, Dinh dưỡng và Ẩm thực Thủ đô. Cuốn sách không chỉ là một nguồn kiến thức quý báu về các loại trái cây, mà còn là một hành trình khám phá những hiểu lầm phổ biến mà nhiều người thường mắc phải khi ăn. Đọc qua từng trang sách, 90% người đọc không khỏi bất ngờ khi biết bản thân cũng từng gặp phải những sai lầm cơ bản này như “Hay ăn trái cây vào buổi tối“ hay “Giảm cân bằng trái cây”...\r\n\r\nNgoài ra, cuốn sách còn được chia thành 10 chương, ở mỗi chương, tác giả đều chỉ rõ các trường hợp nào nên lựa chọn loại trái cây nào cho hợp lý, dùng trái cây để chữa bệnh và  đưa ra lời khuyên cụ thể để giúp cơ thể bạn hấp thụ trái cây một cách hiệu quả nhất. Việc sử dụng trái cây hàng ngày cần được điều chỉnh lại khoa học hơn để đảm bảo rằng chúng ta đang nhận được tất cả các lợi ích dinh dưỡng mà trái cây mang lại.\r\n\r\nVới “Ăn trái cây phải đúng cách”, bạn sẽ có cơ hội khám phá những điều mới mẻ về trái cây, từ đó cải thiện chế độ ăn uống của mình và tận hưởng lợi ích to lớn mà chúng mang lại cho sức khỏe và cơ thể.', 'Việt', 0, 6, 0, 1);

--
-- Triggers `products`
--
DELIMITER $$
CREATE TRIGGER `calculate_price_after_discount` BEFORE INSERT ON `products` FOR EACH ROW BEGIN
    SET NEW.price_after_discount = NEW.price - (NEW.price * NEW.discount / 100);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `calculate_price_after_discount_update` BEFORE UPDATE ON `products` FOR EACH ROW BEGIN
    SET NEW.price_after_discount = NEW.price - (NEW.price * NEW.discount / 100);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `Review_ID` int(11) NOT NULL,
  `Username` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Product_ID` int(11) NOT NULL,
  `Stars` int(11) NOT NULL DEFAULT 0,
  `Comment` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`Review_ID`, `Username`, `Product_ID`, `Stars`, `Comment`, `Date`) VALUES
(18, 'Giang', 1, 0, '1', '2024-06-03'),
(19, 'Giang', 1, 0, '2', '2024-06-03'),
(20, 'Giang', 1, 0, '3', '2024-06-03'),
(21, 'Giang', 1, 0, '4', '2024-06-03'),
(22, 'Giang', 1, 0, '5', '2024-06-03'),
(23, 'Giang', 1, 0, '6', '2024-06-03'),
(24, 'Giang', 1, 0, '7', '2024-06-03'),
(25, 'Giang', 1, 0, '7', '2024-06-03'),
(26, 'Giang', 1, 0, '7', '2024-06-03'),
(27, 'Giang', 1, 4, '', '2024-06-03'),
(28, 'Giang', 1, 3, '', '2024-06-03'),
(29, 'Giang', 7, 2, '', '2024-06-03'),
(30, 'Giang', 7, 5, '', '2024-06-03');

--
-- Triggers `reviews`
--
DELIMITER $$
CREATE TRIGGER `calculate_average_rating` AFTER INSERT ON `reviews` FOR EACH ROW BEGIN
    DECLARE avg_rating DECIMAL(5,2);
    DECLARE rounded_rating DECIMAL(5,1);
    
    -- Tính tổng số sao cho sản phẩm
    SELECT AVG(Stars) INTO avg_rating FROM reviews WHERE Product_ID = NEW.Product_ID;
    
    -- Làm tròn giá trị trung bình tới 0.5 gần nhất
    SET rounded_rating = ROUND(avg_rating * 2) / 2;
    
    -- Cập nhật số sao trung bình vào bảng products
    UPDATE products SET Stars = rounded_rating WHERE Product_ID = NEW.Product_ID;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserName` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `Password` varchar(40) NOT NULL,
  `IsStaff` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserName`, `Password`, `IsStaff`) VALUES
('Admin', '123', 1),
('Giang', '123', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `additional_images`
--
ALTER TABLE `additional_images`
  ADD PRIMARY KEY (`additional_images_ID`),
  ADD KEY `Product_ID` (`Product_ID`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`UserName`,`Product_ID`),
  ADD KEY `Product_ID` (`Product_ID`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`Category_ID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`Order_ID`),
  ADD KEY `Username` (`Username`);

--
-- Indexes for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD PRIMARY KEY (`Order_ID`,`Product_ID`),
  ADD KEY `Product_ID` (`Product_ID`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`Product_ID`),
  ADD KEY `Category_ID` (`Category_ID`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`Review_ID`),
  ADD KEY `Username` (`Username`),
  ADD KEY `Product_ID` (`Product_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserName`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `additional_images`
--
ALTER TABLE `additional_images`
  MODIFY `additional_images_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `Category_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `Order_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `Product_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `Review_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `additional_images`
--
ALTER TABLE `additional_images`
  ADD CONSTRAINT `additional_images_ibfk_1` FOREIGN KEY (`Product_ID`) REFERENCES `products` (`Product_ID`);

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`UserName`) REFERENCES `users` (`UserName`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`Product_ID`) REFERENCES `products` (`Product_ID`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`Username`) REFERENCES `users` (`UserName`);

--
-- Constraints for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD CONSTRAINT `order_detail_ibfk_1` FOREIGN KEY (`Order_ID`) REFERENCES `orders` (`Order_ID`),
  ADD CONSTRAINT `order_detail_ibfk_2` FOREIGN KEY (`Product_ID`) REFERENCES `products` (`Product_ID`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`Category_ID`) REFERENCES `category` (`Category_ID`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`Username`) REFERENCES `users` (`UserName`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`Product_ID`) REFERENCES `products` (`Product_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
