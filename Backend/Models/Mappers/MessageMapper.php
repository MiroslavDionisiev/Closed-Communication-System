<?php

    namespace CCS\Models\Mappers;

    use CCS\Models\DTOs as DTOs;
    use CCS\Models\Entities as Enti;
    use CCS\Models\Mappers as Mapper;

    require_once(APP_ROOT . '/Models/DTOs/MessageDto.php');
    require_once(APP_ROOT . '/Models/Entities/Message.php');
    require_once(APP_ROOT . '/Models/DTOs/StudentDto.php');
    require_once(APP_ROOT . '/Models/Entities/Student.php');
    require_once(APP_ROOT . '/Models/DTOs/TeacherDto.php');
    require_once(APP_ROOT . '/Models/Entities/Teacher.php');
    require_once(APP_ROOT . '/Models/Mappers/StudentMapper.php');
    require_once(APP_ROOT . '/Models/Mappers/TeacherMapper.php');

    class MessageMapper {
        public static function toEntity($from) {
            if (is_array($from)) {
                $user = null;
                if (isset($from['speciality'])) {
                    $user = call_user_func('CCS\Models\Mappers\StudentMapper::toEntity', $from['user'] ?? null);
                }
                else {
                    $user = call_user_func('CCS\Models\Mappers\TeacherMapper::toEntity', $from['user'] ?? null);
                }

                return Enti\Message::fill(
                    $from['id'] ?? null,
                    $user,
                    $from['content'] ?? null,
                    $from['timestamp'] ?? null
                );
            } else if (is_object($from)) {
                $user = call_user_func('CCS\Models\Mappers\\'.$from->{'user'}->get_class().'Mapper::toEntity', $from->{'user'} ?? null);
                
                return Enti\Message::fill(
                    $from->{'id'} ?? null,
                    $user,
                    $from->{'content'} ?? null,
                    $from->{'timestamp'} ?? null
                );
            }
    
            return null;
        }

        public static function toDto($from)
        {
            if (is_array($from)) {
                $user = null;
                if (isset($from['speciality'])) {
                    $user = call_user_func('CCS\Models\Mappers\StudentMapper::toDto', $from['user'] ?? null);
                }
                else {
                    $user = call_user_func('CCS\Models\Mappers\TeacherMapper::toDto', $from['user'] ?? null);
                }

                return DTOs\MessageDto::fill(
                    $from['id'] ?? null,
                    $user,
                    $from['content'] ?? null,
                    $from['timestamp'] ?? null
                );
            } else if (is_object($from)) {
                /*$user = null;
                if (strcmp(get_class($from->{'user'}), "Student")) {
                    $user = call_user_func('CCS\Models\Mappers\StudentMapper::toDto', $from->{'user'});
                }
                else if (strcmp(get_class($from->{'user'}), "Student")) {
                    $user = call_user_func('CCS\Models\Mappers\\'.$from->{'user'}.'Mapper::toDto', $from->{'user'});
                }*/

                $user = call_user_func('CCS\Models\Mappers\\'.$from->{'user'}->get_class().'Mapper::toDto', $from->{'user'} ?? null);

                return DTOs\MessageDto::fill(
                    $from->{'id'} ?? null,
                    $user,
                    $from->{'content'} ?? null,
                    $from->{'timestamp'} ?? null
                );
            }

            return null;
        }
    }
?>
