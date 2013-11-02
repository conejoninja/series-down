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

        function download($id) {
            $ep = $this->findByPrimaryKey($id);
            $bot = Bot::newInstance();
            if($ep['s_download_url']!='') {
                $bot->snoopy->fetch($ep['s_download_url']);
                $links = json_decode($bot->snoopy->results, true);
                foreach($links as $link) {
                    if($link['host']=='StreamCloud') {
                        $bot->snoopy->fetch("http://series.ly/scripts/media/gotoLink.php?idv=".$link['idv']."&mediaType=5");
                        $submit_url = $bot->snoopy->_redirectaddr;
                        $bot->snoopy->fetch($submit_url);
                        sleep(12);
                        if(preg_match_all('|type="hidden" name="([^"]*)" value="([^"]*)"|', $bot->snoopy->results, $match)) {
                            $l = count($match[1]);
                            for($k=0;$k<$l;$k++) {
                                $submit_vars[$match[1][$k]] = $match[2][$k];
                            }
                            $submit_vars['imhuman'] = 'Watch+video+now';
                            $bot->snoopy->submit($submit_url,$submit_vars);
                            if(preg_match('|file:\s*"([^"]+)|', $bot->snoopy->results, $match)) {
                                download_file($match[1], 'capi.mp4');
                            }
                        }

                        break;
                    }
                }
            }
        }

    }
?>