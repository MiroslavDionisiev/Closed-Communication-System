CREATE DATABASE IF NOT EXISTS ccs_db;

USE ccs_db;

CREATE TABLE
  IF NOT EXISTS users(
    id VARCHAR(16) PRIMARY KEY DEFAULT UUID(),
    name VARCHAR(50) NOT NULL,
    password VARCHAR(60) NOT NULL,
    year INT NOT NULL,
    speciality VARCHAR(50) NOT NULL,
    faculty VARCHAR(50) NOT NULL,
    role VARCHAR(50) NOT NULL
  );

CREATE TABLE
  IF NOT EXISTS chat_rooms(
    id VARCHAR(16) PRIMARY KEY DEFAULT UUID(),
    name VARCHAR(50) NOT NULL,
    availability_date DATETIME DEFAULT NULL,
    is_active BOOLEAN DEFAULT TRUE NOT NULL
  );

CREATE TABLE
  IF NOT EXISTS user_chats(
    id VARCHAR(16) PRIMARY KEY DEFAULT UUID(),
    user_id VARCHAR(16) NOT NULL,
    chat_id VARCHAR(16) NOT NULL,
    is_anonymous BOOLEAN DEFAULT TRUE NOT NULL,

    CONSTRAINT fk_user_chats__users FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_user_chats__chat_rooms FOREIGN KEY(chat_id) REFERENCES chat_rooms(id) ON DELETE CASCADE,
    UNIQUE(user_id, chat_id)
  );

CREATE TABLE
  IF NOT EXISTS messages(
    id VARCHAR(16) PRIMARY KEY DEFAULT UUID(),
    user_id VARCHAR(16) NOT NULL,
    chat_id VARCHAR(16) NOT NULL,
    content VARCHAR(2000),
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_disabled BOOLEAN DEFAULT TRUE NOT NULL,

    CONSTRAINT fk_messages__users FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_messages__chat_rooms FOREIGN KEY(chat_id) REFERENCES chat_rooms(id) ON DELETE CASCADE
  );
