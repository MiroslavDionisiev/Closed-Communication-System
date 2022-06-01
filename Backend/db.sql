CREATE DATABASE IF NOT EXISTS ccs_db;

USE ccs_db;

CREATE TABLE
  IF NOT EXISTS users(
    id CHAR(36) PRIMARY KEY DEFAULT UUID(),
    name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(60) NOT NULL,
    role VARCHAR(50) NOT NULL
  );

CREATE TABLE
  IF NOT EXISTS students(
    userId CHAR(36) PRIMARY KEY,
    facultyNumber VARCHAR(50) UNIQUE NOT NULL,
    year INT,
    speciality VARCHAR(50),
    faculty VARCHAR(50),
    CONSTRAINT fk_students__users FOREIGN KEY(userId) REFERENCES users(id) ON DELETE CASCADE
  );

CREATE TABLE
  IF NOT EXISTS teachers(
    userId CHAR(36) PRIMARY KEY,
    CONSTRAINT fk_teachers__users FOREIGN KEY(userId) REFERENCES users(id) ON DELETE CASCADE
  );

CREATE TABLE
  IF NOT EXISTS chat_rooms(
    id CHAR(36) PRIMARY KEY DEFAULT UUID(),
    name VARCHAR(50) NOT NULL UNIQUE,
    availabilityDate DATETIME DEFAULT NULL,
    isActive BOOLEAN DEFAULT TRUE NOT NULL
  );

CREATE TABLE
  IF NOT EXISTS user_chats(
    id CHAR(36) PRIMARY KEY DEFAULT UUID(),
    userId CHAR(36) NOT NULL,
    chatRoomId CHAR(36) NOT NULL,
    lastSeen TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    isAnonymous BOOLEAN DEFAULT TRUE NOT NULL,
    CONSTRAINT fk_user_chats__users FOREIGN KEY(userId) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_user_chats__chat_rooms FOREIGN KEY(chatRoomId) REFERENCES chat_rooms(id) ON DELETE CASCADE,
    UNIQUE(userId, chatRoomId)
  );

CREATE TABLE
  IF NOT EXISTS messages(
    id CHAR(36) PRIMARY KEY DEFAULT UUID(),
    userId CHAR(36) NOT NULL,
    chatRoomId CHAR(36) NOT NULL,
    content VARCHAR(2000),
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    isDisabled BOOLEAN DEFAULT FALSE,
    CONSTRAINT fk_messages__users FOREIGN KEY(userId) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_messages__chat_rooms FOREIGN KEY(chatRoomId) REFERENCES chat_rooms(id) ON DELETE CASCADE
  );
