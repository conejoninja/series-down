<?php



/**
 * Download file using fsockopen
 *
 * @since 3.0
 * @param type $sourceFile
 * @param type $fileout
 */
function download_fsockopen($sourceFile, $fileout = null)
{
    // parse URL
    $aUrl = parse_url($sourceFile);
    $host = $aUrl['host'];
    if ('localhost' == strtolower($host))
        $host = '127.0.0.1';

    $link = $aUrl['path'] . ( isset($aUrl['query']) ? '?' . $aUrl['query'] : '' );

    if (empty($link))
        $link .= '/';

    $fp = @fsockopen($host, 80, $errno, $errstr, 30);
    if (!$fp) {
        return false;
    } else {
        $ua  = $_SERVER['HTTP_USER_AGENT'];
        $out = "GET $link HTTP/1.1\r\n";
        $out .= "Host: $host\r\n";
        $out .= "User-Agent: $ua\r\n";
        $out .= "Connection: Close\r\n\r\n";
        $out .= "\r\n";
        fwrite($fp, $out);

        $contents = '';
        while (!feof($fp)) {
            $contents.= fgets($fp, 1024);
        }

        fclose($fp);

        // check redirections ?
        // if (redirections) then do request again
        $aResult = processResponse($contents);
        $headers = processHeaders($aResult['headers']);

        $location = @$headers['location'];
        if (isset($location) && $location != "") {
            $aUrl = parse_url($headers['location']);

            $host = $aUrl['host'];
            if ('localhost' == strtolower($host))
                $host = '127.0.0.1';

            $requestPath = $aUrl['path'] . ( isset($aUrl['query']) ? '?' . $aUrl['query'] : '' );

            if (empty($requestPath))
                $requestPath .= '/';

            download_fsockopen($host, $requestPath, $fileout);
        } else {
            $body = $aResult['body'];
            $transferEncoding = @$headers['transfer-encoding'];
            if($transferEncoding == 'chunked' ) {
                $body = http_chunked_decode($aResult['body']);
            }
            if($fileout!=null) {
                $ff = @fopen($fileout, 'w+');
                if($ff!==FALSE) {
                    fwrite($ff, $body);
                    fclose($ff);
                    return true;
                } else {
                    return false;
                }
            } else {
                return $body;
            }
        }
    }
}

function download_file($sourceFile, $downloadedFile) {
    if(strpos($downloadedFile, "../")!==false) {
        return false;
    }

    if ( test_curl() ) {
        @set_time_limit(0);
        $fp = @fopen(BASE_PATH.'downloads/'.$downloadedFile, 'w+');
        if($fp) {
            $ch = curl_init($sourceFile);
            @curl_setopt($ch, CURLOPT_TIMEOUT, 50);
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
            curl_setopt($ch, CURLOPT_FILE, $fp);
            @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_exec($ch);
            curl_close($ch);
            fclose($fp);
            return true;
        } else {
            return false;
        }
    } else if (testFsockopen()) { // test curl/fsockopen
        $downloadedFile = BASE_PATH.'downloads/'.$downloadedFile;
        download_fsockopen($sourceFile, $downloadedFile);
        return true;
    }
    return false;
}



/**
 * Returns true if there is curl on system environment
 *
 * @return type
 */
function test_curl() { return !(!function_exists('curl_init') || !function_exists('curl_exec')); }

?>