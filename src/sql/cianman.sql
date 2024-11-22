CREATE DATABASE  IF NOT EXISTS `cianman` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `cianman`;
-- MySQL dump 10.13  Distrib 8.0.38, for Win64 (x86_64)
--
-- Host: localhost    Database: cianman
-- ------------------------------------------------------
-- Server version	8.2.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `carrinho`
--

DROP TABLE IF EXISTS `carrinho`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `carrinho` (
  `id` int NOT NULL AUTO_INCREMENT,
  `clienteCpf` varchar(14) DEFAULT NULL,
  `imovelId` int DEFAULT NULL,
  `quantidade` int DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `clienteCpf` (`clienteCpf`),
  KEY `imovelId` (`imovelId`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carrinho`
--

LOCK TABLES `carrinho` WRITE;
/*!40000 ALTER TABLE `carrinho` DISABLE KEYS */;
INSERT INTO `carrinho` VALUES (1,'36',8,1),(2,'36',8,1),(3,'36',3,1),(4,'36',6,1),(5,'36',6,1),(6,'43',6,1),(7,'43',1,1),(9,'405',5,1),(11,'890',2,1),(12,'890',6,1),(14,'028.703.700-24',7,1),(16,'405.060.702-80',17,1),(17,'405.060.702-80',1,1),(19,'405.060.702-80',19,1);
/*!40000 ALTER TABLE `carrinho` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clientes`
--

DROP TABLE IF EXISTS `clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clientes` (
  `cpf` varchar(14) NOT NULL,
  `nome` varchar(50) DEFAULT NULL,
  `cep` varchar(9) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `dataNasc` date DEFAULT NULL,
  `senha` varchar(20) DEFAULT NULL,
  `telefone` varchar(17) DEFAULT NULL,
  PRIMARY KEY (`cpf`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientes`
--

LOCK TABLES `clientes` WRITE;
/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;
INSERT INTO `clientes` VALUES ('042.665.650-46','Anne Gabrielle Rufino Dornelles','97010-081','annegdornelles@gmail.com','2008-05-19','123','(55) 9 9920-1025'),('035.743.580-08','Cintia','87011-209','cintia@gmail.com','2007-04-04','345','(55) 9 9877-6932'),('405.060.702-80','Amanda Cecchin Denardin','90765-432','amandacdenardin@gmail.com','2007-10-15','789','(55) 9 9620-1241'),('890.446.620-04','Veronica Rufino','970100-88','veronicasrd@gmail.com','1973-04-25','annelinda','(55) 9 9616-4296'),('028.703.700-24','Lorenzo Guerra','97015-110','lorenzo@gmail.com','2007-09-25','456','(55) 9 9195-3459'),('690.446.6723-4','Nicanor','97010-090','nicanor@gmail.com','2000-01-13','123','(55) 9 9920-1024');
/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `corretores`
--

DROP TABLE IF EXISTS `corretores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `corretores` (
  `creci` varchar(5) NOT NULL,
  `funcionariosId` int DEFAULT NULL,
  PRIMARY KEY (`creci`),
  KEY `funcionariosId` (`funcionariosId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `corretores`
--

LOCK TABLES `corretores` WRITE;
/*!40000 ALTER TABLE `corretores` DISABLE KEYS */;
INSERT INTO `corretores` VALUES ('12.54',1),('45.47',2);
/*!40000 ALTER TABLE `corretores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `favoritos`
--

DROP TABLE IF EXISTS `favoritos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `favoritos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `clienteCpf` varchar(14) DEFAULT NULL,
  `imovelId` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `clienteCpf` (`clienteCpf`),
  KEY `imovelId` (`imovelId`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favoritos`
--

LOCK TABLES `favoritos` WRITE;
/*!40000 ALTER TABLE `favoritos` DISABLE KEYS */;
INSERT INTO `favoritos` VALUES (1,'43',1),(2,'43',1),(3,'43',8),(4,'43',8),(5,'43',8),(6,'43',6),(10,'405',3),(11,'405.060.702-80',17),(12,'405.060.702-80',1),(13,'405.060.702-80',18),(14,'405.060.702-80',2),(15,'405.060.702-80',19);
/*!40000 ALTER TABLE `favoritos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `funcionarios`
--

DROP TABLE IF EXISTS `funcionarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `funcionarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cpf` varchar(14) DEFAULT NULL,
  `nome` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `senha` varchar(20) DEFAULT NULL,
  `telefone` varchar(17) DEFAULT NULL,
  `salario` float DEFAULT NULL,
  `numImoveisResponsavel` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `funcionarios`
--

LOCK TABLES `funcionarios` WRITE;
/*!40000 ALTER TABLE `funcionarios` DISABLE KEYS */;
INSERT INTO `funcionarios` VALUES (1,'841.443.655-32','Maria Ribeiro','maria@cianman.com','123','(55)99627-8777',1500,3),(2,'789.765.433-32','João Lemos','jlemos@cianman.com','345','(55) 9 9876-4532',3000,4);
/*!40000 ALTER TABLE `funcionarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `imagens`
--

DROP TABLE IF EXISTS `imagens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `imagens` (
  `id` int NOT NULL AUTO_INCREMENT,
  `imovelId` int DEFAULT NULL,
  `link` varchar(200) DEFAULT NULL,
  `descricao` text,
  PRIMARY KEY (`id`),
  KEY `imovelId` (`imovelId`)
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `imagens`
--

LOCK TABLES `imagens` WRITE;
/*!40000 ALTER TABLE `imagens` DISABLE KEYS */;
INSERT INTO `imagens` VALUES (1,1,'src/img/imovel1','Vista exterior de um prédio cor marrom clara, com três janelas em cada parede.'),(2,1,'src/img/cozinhaimovel1.jpg','Cozinha decorada com tons claros,possuindo uma  parede revestida de tijolos branco um armário branco com marrom, incluindo um Cooktop e forno embutido no armário, algumas gavetas e uma bancada espaçosa. Também há duas prateleiras, e um armário aéreo.'),(3,1,'src/img/quartoimovel1.jpg','Quarto relativamente grande com paredes brancas. Na imagem, uma cama de casal está no meio do quarto, sobre ela, almofadas coloridas, e em ambos os lados da cama cabeceiras pretas carregam abajures igualmente pretos.'),(4,1,'src/img/banheiroimovel1.jpg','Banheiro espaçoso, com decoração moderna com cores neutras. Possuindo uma banheira, armário aéreo em cima do vaso sanitário e ao lado o armário da pia na cor branca. Também há um espelho redondo na parede, dois lustres nas cores amarelas para tornar o ambiente mais aconchegante e um papel de parede com fundo branco e listras pretas.'),(5,2,'src/img/imovel2campo.jpg','Grama verde com algumas palmeiras e o telhado de uma casa. Vista aérea.'),(6,2,'src/img/imovel2.jpg','Uma casa com aspecto de abandonada está posicionada no centro da foto. Céu cinza e grama amarelada.'),(7,3,'src/img/imovel3.jpg','Prédio laranja com sacadas'),(8,3,'src/img/quarto1imovel3.jpg','Paredes com tons claros e ao centro uma cama de casal, com almofadas beges. O espaço é largo, abaixo da cama está um tapete cinza e acima um quadro retangular na horizontal que possui imagens de flores. Nos dois lados da camas há cabeceiras feitas de madeira, bem como duas janelas. Na parede próximo a entrada, há uma escrivaninha de madeira e acima um espelho.'),(9,3,'src/img/quarto2imovel3.jpg','Paredes beges, na parede mais ao fundo há uma janela fechada por uma cortina branca. A cama está posicionada pouco a frente da janela. A cama é de casal e ao seu lado, está um sofá cinza que encosta na parede. Na frente do sofá, um pequeno armário de madeira.'),(10,3,'src/img/banheiroimovel3.jpg','Cozinha com uma ilha no centro, que contém armários. Há armários brancos encostados e encobrindo a parede marrom clara. Há fornos e fogões posicionados junto aos armários.'),(11,4,'src/img/imovel4.jpg','A casa marrom é o ponto central da imagem, ela está na beira de um lago e acima há uma montanha encoberta por uma grande neblina, é possível avistar uma igreja.'),(12,5,'src/img/imovel5.jpg','Casa branca com telhado marrom, de 2 andares e 8 janelas vista de frente. Na frente da casa há grama alta'),(13,7,'src/img/imovel7.jpg','Pequena casa branca desgastada. Ao fundo, uma grande porção de terreno.'),(14,6,'src/img/imovel6.jpg','Vista da rua de um prédio azul com listras azuis. 5 andares, há uma passagem com uma cerca entre um prédio e outro.'),(15,8,'src/img/imovel8.jpg','Casa azul clara de 2 andares, com uma varanda e 3 janelas vendo da rua. Há uma escada com corrimões brancos para chegar até a porta de entrada.'),(16,18,'uploads/673b672aa18cd_pexels-expect-best-79873-323780.jpg','Casa com aspectos modernos, dois andares, porta de entrada de madeira, cor branca, sacada de vidro.'),(17,17,'uploads/673a807c13df9_pexels-scottwebb-1029599.jpg','Casa tradicional de cor branca e telhado em formato de triângulo verde, na frente da casa há uma cerca branca e atrás árvores com flores amarelas. 3 janelas na vista da rua, e elas como a porta são pintadas de cor vinho.'),(18,19,'uploads/673f42ec00ad0_pexels-photo-1457842.jpeg','Sala de estar espaçosa, com decoração moderna. Decorada em tons cinzas com branco, sofá grande e cinza, com um armario  para colocar a tv, quadros de decoração e janelas grandes com vista para o mar. '),(20,19,'uploads/673f5ae8db3a2_pexels-marywhitneyph-90317.jpg','Quarto com três janelas com cortinas brancas, paredes cinzas e na foto no canto entre duas paredes há uma poltrona amarela com uma almofada também amarela por cima, ao seu lado está uma cabeceira preta e amarela. A cama está com lençois rosas e três almofadas. No pé da cama está uma mesa feita de cadeira.'),(21,7,'uploads/673f5e0618d45_pexels-marywhitneyph-90317.jpg','Quarto com três janelas com cortinas brancas, paredes cinzas e na foto no canto entre duas paredes há uma poltrona amarela com uma almofada também amarela por cima, ao seu lado está uma cabeceira preta e amarela. A cama está com lençois rosas e três almofadas. No pé da cama está uma mesa feita de cadeira.'),(22,7,'uploads/673f5ec7a5fcb_pexels-marywhitneyph-90317.jpg','.'),(23,8,'uploads/673f5ec7a6943_pexels-fotoaibe-1743229.jpg','.'),(27,17,'uploads/673f6055bc1c1_pexels-fotoaibe-1743229.jpg','.'),(28,6,'uploads/673f609772553_pexels-marywhitneyph-90317.jpg','.'),(29,6,'uploads/673f6097731c9_pexels-fotoaibe-1743229.jpg','.'),(30,21,'uploads/673f66cb46ab3_pexels-marywhitneyph-90317.jpg','.'),(31,21,'uploads/673f66cb4756a_pexels-fotoaibe-1743229.jpg','.'),(32,22,'uploads/673f66dd5e875_pexels-marywhitneyph-90317.jpg','.'),(33,22,'uploads/673f66dd5f90f_pexels-fotoaibe-1743229.jpg','.'),(34,23,'uploads/673f68b392a57_pexels-binyaminmellish-106399.jpg','Casa de dois andares, duas garagens, casa cinza.'),(35,5,NULL,NULL);
/*!40000 ALTER TABLE `imagens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `imoveis`
--

DROP TABLE IF EXISTS `imoveis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `imoveis` (
  `id` int NOT NULL AUTO_INCREMENT,
  `url` varchar(200) DEFAULT NULL,
  `funcionariosId` int DEFAULT NULL,
  `cep` varchar(9) DEFAULT NULL,
  `tamanho` int DEFAULT NULL,
  `valor` float DEFAULT NULL,
  `numQuartos` int DEFAULT NULL,
  `bairro` varchar(50) DEFAULT NULL,
  `cidade` varchar(20) DEFAULT NULL,
  `numCasa` int DEFAULT NULL,
  `logradouro` varchar(50) DEFAULT NULL,
  `tipo` varchar(30) DEFAULT NULL,
  `compraAluga` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `funcionariosId` (`funcionariosId`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `imoveis`
--

LOCK TABLES `imoveis` WRITE;
/*!40000 ALTER TABLE `imoveis` DISABLE KEYS */;
INSERT INTO `imoveis` VALUES (1,'src/img/imovel1.jpg',1,'97105-052',45,1200,2,'Camobi','Santa Maria',304,'Av. Roraima','Apartamento Residencial','aluga'),(2,'src/img/imovel2.jpg',1,'87111-523',100,1000000,0,'Camobi','Santa Maria',100,'RSC-287','Terreno','Compra'),(3,'src/img/imovel3.jpg',1,'97801-023',54,1500,2,'Centro','Caxias do Sul',301,'Rua Bento Gonçalves','Sala Comercial','Aluguel'),(4,'src/img/imovel4.jpg',1,'92320-750',60,1500000,3,'Harmonia','Canoas',3097,'Av. Rio dos Sinos','Casa Residencial','Compra'),(5,'src/img/imovel5.jpg',2,'37234-081',100,2000000,4,'Centro','Santa Maria',201,'Rua General Neto','Casa Residencial','compra'),(7,'src//img/imovel7.jpg',2,'97140-000',80,1800000,0,'Pains','Santa Maria',441,'Distrito Pains','Terreno','Compra'),(8,'src/img/imovel8.jpg',2,'96015-430',60,2000,4,'Centro','Pelotas',101,'Largo Antônio Gomes da Silva','Casa','Aluguel'),(18,'uploads/673b672aa18cd_pexels-expect-best-79873-323780.jpg',1,'97010021',200,3000000,4,'Camobi','Santa Maria',307,'Condomínio Amaral','Casa Residencial','Compra'),(19,NULL,1,'92010',40,1400,2,'Centro','Canoas',300,'Avenida Victor Barreto','Apartamento Residencial','Aluguel'),(6,'src/img/imovel6.jpg',2,'90040-190',50,1300,2,'Cidade Baixa','Porto Alegre',353,'Av. Venâncio Aires','Apartamento Residencial','Aluguel'),(17,'uploads/673a807c13df9_pexels-scottwebb-1029599.jpg',1,'97060001',30,1500,1,'Centro','Santa Maria',1062,'Av. Nossa Senhora da Medianeira','Casa Residencial','Aluguel'),(23,NULL,1,'96010',80,2000000,3,'Centro','Pelotas',441,'Passeio Aracy Brasil Dias','Casa residencial','Compra');
/*!40000 ALTER TABLE `imoveis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proprietarios`
--

DROP TABLE IF EXISTS `proprietarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `proprietarios` (
  `cpf` varchar(14) NOT NULL,
  `funcionariosId` int DEFAULT NULL,
  `nome` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `telefone` varchar(17) DEFAULT NULL,
  PRIMARY KEY (`cpf`),
  KEY `funcionariosId` (`funcionariosId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proprietarios`
--

LOCK TABLES `proprietarios` WRITE;
/*!40000 ALTER TABLE `proprietarios` DISABLE KEYS */;
/*!40000 ALTER TABLE `proprietarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `venda`
--

DROP TABLE IF EXISTS `venda`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `venda` (
  `clientesCpf` varchar(14) DEFAULT NULL,
  `funcionariosId` int DEFAULT NULL,
  `id` int NOT NULL AUTO_INCREMENT,
  `valor` float DEFAULT NULL,
  `dataVenda` date DEFAULT NULL,
  `imovelId` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `clientesCpf` (`clientesCpf`),
  KEY `funcionariosId` (`funcionariosId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `venda`
--

LOCK TABLES `venda` WRITE;
/*!40000 ALTER TABLE `venda` DISABLE KEYS */;
/*!40000 ALTER TABLE `venda` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-11-22  0:40:47
