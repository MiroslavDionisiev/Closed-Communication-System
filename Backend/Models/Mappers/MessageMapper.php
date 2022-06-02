<?php

    namespace CCS\Models\Mappers;

    use CCS\Models\DTOs as DTOs;
    use CCS\Models\Entities as Enti;

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
                    $user = call_user_func('CCS\Models\Mappers\StudentMapper::toEntity', $from);
                }
                else {
                    $user = call_user_func('CCS\Models\Mappers\TeacherMapper::toEntity', $from);
                }

                return Enti\Message::fill(
                    $from['id'] ?? null,
                    $user,
                    $from['content'] ?? null,
                    $from['timestamp'] ?? null,
                    $from['isDisabled'] ?? null
                );
            } else if (is_object($from)) {
                $user = null;
                if (!is_null($from->{'user'})) {
                    $className = str_replace("Entities", "Mappers", get_class($from->{'user'}));
                    $user = call_user_func($className . 'Mapper::toDto', $from->{'user'});
                }
                
                return Enti\Message::fill(
                    $from->{'id'} ?? null,
                    $user,
                    $from->{'content'} ?? null,
                    $from->{'timestamp'} ?? null,
                    $from->{'isDisabled'} ?? null
                );
            }
    
            return null;
        }

        public static function toDto($from)
        {
            if (is_array($from)) {
                $user = null;
                if (isset($from['speciality'])) {
                    $user = call_user_func('CCS\Models\Mappers\StudentMapper::toDto', $from);
                }
                else {
                    $user = call_user_func('CCS\Models\Mappers\TeacherMapper::toDto', $from);
                }

                return DTOs\MessageDto::fill(
                    $from['id'] ?? null,
                    $user,
                    $from['content'] ?? null,
                    $from['timestamp'] ?? null,
                    $from['isDisabled'] ?? null
                );
            } else if (is_object($from)) {
                $user = null;
                if (!is_null($from->{'user'})) {
                    $className = str_replace("Entities", "Mappers", get_class($from->{'user'}));
                    $user = call_user_func($className . 'Mapper::toDto', $from->{'user'});
                }

                return DTOs\MessageDto::fill(
                    $from->{'id'} ?? null,
                    $user,
                    $from->{'content'} ?? null,
                    $from->{'timestamp'} ?? null,
                    $from->{'isDisabled'} ?? null
                );
            }

            return null;
        }
    }
?>
