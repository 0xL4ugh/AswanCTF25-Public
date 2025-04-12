/*
 Navicat Premium Dump SQL

 Source Server         : local
 Source Server Type    : MySQL
 Source Server Version : 80041 (8.0.41)
 Source Host           : localhost:3306
 Source Schema         : yaobank

 Target Server Type    : MySQL
 Target Server Version : 80041 (8.0.41)
 File Encoding         : 65001

 Date: 06/04/2025 02:30:40
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for bankaccounts
-- ----------------------------
DROP TABLE IF EXISTS `bankaccounts`;
CREATE TABLE `bankaccounts`  (
  `Id` int NOT NULL AUTO_INCREMENT,
  `AccountNumber` int NULL DEFAULT NULL,
  `Name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `balance` int NULL DEFAULT 100,
  `Premium` tinyint(1) NULL DEFAULT 0,
  `SubUser` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`Id`) USING BTREE,
  UNIQUE INDEX `email`(`email` ASC) USING BTREE,
  INDEX `AccountNumber`(`AccountNumber` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9051 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of bankaccounts
-- ----------------------------
INSERT INTO `bankaccounts` VALUES (1, 1, 'Admin', 'super@yao.com', 'blabla', 100, 1, 0);
INSERT INTO `bankaccounts` VALUES (9050, 500, 'Admin', 'Admin@yao.com', 'blablablabla', 100, 1, 1);

-- ----------------------------
-- Table structure for transactions
-- ----------------------------
DROP TABLE IF EXISTS `transactions`;
CREATE TABLE `transactions`  (
  `Id` int NOT NULL AUTO_INCREMENT,
  `FromAcc` int NOT NULL,
  `ToAcc` int NOT NULL,
  `Amount` int NOT NULL,
  `Date` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`) USING BTREE,
  INDEX `accounts`(`FromAcc` ASC) USING BTREE,
  INDEX `accounts2`(`ToAcc` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 68 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of transactions
-- ----------------------------

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `Id` int NOT NULL AUTO_INCREMENT,
  `UserId` int NOT NULL,
  `OwnerId` int NOT NULL,
  PRIMARY KEY (`Id`) USING BTREE,
  INDEX `subowner`(`UserId` ASC) USING BTREE,
  INDEX `owner`(`OwnerId` ASC) USING BTREE,
  CONSTRAINT `owner` FOREIGN KEY (`OwnerId`) REFERENCES `bankaccounts` (`Id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `subowner` FOREIGN KEY (`UserId`) REFERENCES `bankaccounts` (`Id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 9050, 1);

SET FOREIGN_KEY_CHECKS = 1;
