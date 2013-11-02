<?php

    class Episode extends DAO
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
            $this->setTableName('t_episode');
            $this->setPrimaryKey('pk_i_id');
            $array_fields = array(
                'pk_i_id',
                'fk_i_media_id',
                's_name',
                'i_season',
                'i_episode',
                'b_has_links',
                's_status',
                's_download_url',
                's_downloaded_path'
            );
            $this->setFields($array_fields);
        }


        function lastFromShow($id) {
            $this->dao->select("*");
            $this->dao->from($this->getTableName());
            $this->dao->where('fk_i_media_id', $id);
            $this->dao->orderBy("i_season DESC, i_episode DESC");
            $this->dao->limit(1);
            $result = $this->dao->get();
            if($result===false) { return array(); }
            return $result->row();
        }

    }
?>