/*
Navicat MySQL Data Transfer

Source Server         : LocalHost
Source Server Version : 80027
Source Host           : localhost:3306
Source Database       : dasdas

Target Server Type    : MYSQL
Target Server Version : 80027
File Encoding         : 65001

Date: 2023-10-05 16:18:57
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for reuniao
-- ----------------------------
DROP TABLE IF EXISTS `reuniao`;
CREATE TABLE `reuniao` (
  `cd_reuniao` int NOT NULL,
  `cd_obra` int DEFAULT NULL,
  `assunto` varchar(512) DEFAULT NULL,
  `dt_prevista` datetime DEFAULT NULL,
  `ds_reuniao` longtext,
  `cd_usuario_solicitante` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `situacao` varchar(1) DEFAULT NULL,
  `dt_confirmacao` datetime DEFAULT NULL,
  `cd_usuario_confirmacao` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`cd_reuniao`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of reuniao
-- ----------------------------
