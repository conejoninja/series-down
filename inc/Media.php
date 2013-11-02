<?php

    class Media extends DAO
    {
        private static $instance;

        public static function newInstance()
        {
            if( !self::$instance instanceof self ) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        function __construct()
        {
            parent::__construct();
            $this->setTableName('t_media');
            $this->setPrimaryKey('pk_i_id');
            $array_fields = array(
                'pk_i_id',
                's_name',
                's_code',
                'e_media_type',
                's_thumbnail'
            );
            $this->setFields($array_fields);
        }

        function updateShow($raw) {
            $show = $this->findByPrimaryKey($raw['idm']);
            if($show) {
            } else {
                $this->insert(
                    array(
                        'pk_i_id' => $raw['idm'],
                        's_name' => $raw['title'],
                        's_code' => $raw['id_media'],
                        'e_media_type' => '1',
                        's_thumbnail' => ''
                    )
                );
            }
            $episodes = Episode::newInstance()->show($raw['idm']);
        }

    }
?>