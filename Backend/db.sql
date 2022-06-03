CREATE DATABASE IF NOT EXISTS ccs_db;

USE ccs_db;

CREATE TABLE
  IF NOT EXISTS users(
    userId CHAR(36) PRIMARY KEY DEFAULT UUID(),
    userName VARCHAR(50) NOT NULL,
    userEmail VARCHAR(100) NOT NULL UNIQUE,
    userPassword VARCHAR(60) NOT NULL,
    userRole VARCHAR(50) NOT NULL
  );

CREATE TABLE
  IF NOT EXISTS students(
    userId CHAR(36) PRIMARY KEY,
    studentFacultyNumber VARCHAR(50) UNIQUE NOT NULL,
    studentYear INT,
    studentSpeciality VARCHAR(50),
    studentFaculty VARCHAR(50),
    CONSTRAINT fk_students__users FOREIGN KEY(userId) REFERENCES users(userId) ON DELETE CASCADE
  );

CREATE TABLE
  IF NOT EXISTS teachers(
    userId CHAR(36) PRIMARY KEY,
    CONSTRAINT fk_teachers__users FOREIGN KEY(userId) REFERENCES users(userId) ON DELETE CASCADE
  );

CREATE TABLE
  IF NOT EXISTS chat_rooms(
    chatRoomId CHAR(36) PRIMARY KEY DEFAULT UUID(),
    chatRoomName VARCHAR(50) NOT NULL UNIQUE,
    chatRoomAvailabilityDate DATETIME DEFAULT NULL
  );

CREATE TABLE
  IF NOT EXISTS user_chats(
    userChatId CHAR(36) PRIMARY KEY DEFAULT UUID(),
    userId CHAR(36) NOT NULL,
    chatRoomId CHAR(36) NOT NULL,
    userChatLastSeen TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    userChatIsAnonymous BOOLEAN DEFAULT TRUE NOT NULL,
    CONSTRAINT fk_user_chats__users FOREIGN KEY(userId) REFERENCES users(userId) ON DELETE CASCADE,
    CONSTRAINT fk_user_chats__chat_rooms FOREIGN KEY(chatRoomId) REFERENCES chat_rooms(chatRoomId) ON DELETE CASCADE,
    UNIQUE(userId, chatRoomId)
  );

CREATE TABLE
  IF NOT EXISTS messages(
    messageId CHAR(36) PRIMARY KEY DEFAULT UUID(),
    userId CHAR(36) NOT NULL,
    chatRoomId CHAR(36) NOT NULL,
    messageContent VARCHAR(2000),
    messageTimestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    messageIsDisabled BOOLEAN DEFAULT FALSE,
    CONSTRAINT fk_messages__users FOREIGN KEY(userId) REFERENCES users(userId) ON DELETE CASCADE,
    CONSTRAINT fk_messages__chat_rooms FOREIGN KEY(chatRoomId) REFERENCES chat_rooms(chatRoomId) ON DELETE CASCADE
  );

INSERT INTO
  users(userEmail, userPassword, userName, userRole)
VALUES
  (
    'admin@fmi.bg',
    '$2y$10$paihtzq7QNZ7LagK7nTx9ezh3oSyZxSyTQNQWWZ67HeqZHWXEVhTe',
    'Admin Adminchev',
    'ADMIN'
  );
