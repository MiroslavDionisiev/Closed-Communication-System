USE ccs_db;

SET
  @student1 = UUID();
SET
  @student2 = UUID();

SET
  @teacher = UUID();

SET
  @chat_room = UUID();

INSERT INTO
  users
VALUES
  (@student1, "student1", "student1@fmi.bg", "$2y$10$YlaZpxZFz0ZF.BA8w59Hj.n/teGfiKPnbi4lUejqZNVLdWU8QlJwO", "USER", "Student"),
  (@student2, "student2", "student2@fmi.bg", "$2y$10$Fc3my1/Jms2YeWx4o88oFu2NOmh5hb4/YOBqKTZ.oLAyuoaFZH.U6", "USER", "Student"),
  (@teacher, "teacher", "teacher@fmi.bg", "$2y$10$fjlex0bGsR0/JGGWDvYhbufR4NU3myAYer6J7q0578C3AzWOq0P.2", "ADMIN", "Teacher");

INSERT INTO
  students
VALUES
  (@student1, "FACULTY1", 1, "Software Degradation", "Mahalata"),
  (@student2, "FACULTY2", 1, "Software Degradation", "Mahalata");

INSERT INTO
  teachers
VALUES
  (@teacher);

INSERT INTO
  chat_rooms(chatRoomId, chatRoomName)
VALUES
  (@chat_room, "chatroom");

INSERT INTO
  user_chats
VALUES
  (UUID(), @student1, @chat_room, CURRENT_TIMESTAMP, TRUE);

INSERT INTO
  messages(userId, chatRoomId, messageContent, messageIsDisabled)
VALUES
  (@student1, @chat_room, "Veni Vidi Vici", TRUE);
