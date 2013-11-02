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
                if(download_file("http://cdn.opensly.com/series/".$raw['id_media'].".jpg", 'thumbs/'.$raw['id_media'].".jpg")) {
                    $thumb = "downloads/thumbs/".$raw['id_media'].".jpg";
                } else {
                    $thumb = "";
                }
                $this->insert(
                    array(
                        'pk_i_id' => $raw['idm'],
                        's_name' => $raw['title'],
                        's_code' => $raw['id_media'],
                        'e_media_type' => '1',
                        's_thumbnail' => $thumb
                    )
                );
            }
            $episode_data = Episode::newInstance()->lastFromShow($raw['idm']);
            $season = isset($episode_data['i_season'])?$episode_data['i_season']:0;
            $episode = isset($episode_data['i_episode'])?$episode_data['i_episode']:0;
            $seasons = array_reverse($raw['episodes'], true);
            $epm = Episode::newInstance();
            $stop = false;
            foreach($seasons as $k => $episodes) {
                $episodes = array_reverse($episodes, true);
                foreach($episodes as $ep) {
                    if($ep['season']!=$season || $ep['episode']!=$episode) {
                        $epm->insert(
                            array(
                                'pk_i_id' => $ep['idc'],
                                'fk_i_media_id' => $raw['idm'],
                                's_name' => $ep['title'],
                                'i_season' => $ep['season'],
                                'i_episode' => $ep['episode'],
                                'b_has_links' => $ep['has_links'],
                                's_status' => 'PENDING',
                                's_download_url' => '',
                                's_downloaded_path' => ''
                            )
                        );
                    } else {
                        $stop = true;
                        break;
                    }
                }
                if($stop) { break; };
            }
            //print_r($raw);
        }

    }
?>