<?php
function getSearch($q){
    $ch = curl_init();
    $url = 'https://jam.ua/ua/search/'.$q.'?ajax=1';
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, Array("search" => htmlspecialchars($q)));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    echo $server_output = curl_exec ($ch);
    curl_close ($ch);
    preg_match_all('|<a href="(.*)"|isU', $server_output, $return);
    return ($return);
}
function get_web_page( $url, $cookiesIn = '' ){
    $options = array(
        CURLOPT_RETURNTRANSFER => true,     // return web page
        CURLOPT_HEADER         => true,     //return headers in addition to content
        CURLOPT_FOLLOWLOCATION => true,     // follow redirects
        CURLOPT_ENCODING       => "",       // handle all encodings
        CURLOPT_AUTOREFERER    => true,     // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 30,      // timeout on connect
        CURLOPT_TIMEOUT        => 30,      // timeout on response
        CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
        CURLINFO_HEADER_OUT    => true,
        CURLOPT_SSL_VERIFYPEER => true,     // Validate SSL Certificates
        CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
        CURLOPT_COOKIE         => $cookiesIn,
        CURLOPT_USERAGENT      => 'Mozilla/5.0 (iPhone; CPU iPhone OS 10_3_1 like Mac OS X) AppleWebKit/603.1.30 (KHTML, like Gecko) Version/10.0 Mobile/14E304 Safari/602.1'
    );

    $ch      = curl_init( $url );
    curl_setopt_array( $ch, $options );
    $rough_content = curl_exec( $ch );
    $err     = curl_errno( $ch );
    $errmsg  = curl_error( $ch );
    $header  = curl_getinfo( $ch );
    curl_close( $ch );

    $header_content = substr($rough_content, 0, $header['header_size']);
    $body_content = trim(str_replace($header_content, '', $rough_content));
    $pattern = "#Set-Cookie:\\s+(?<cookie>[^=]+=[^;]+)#m";
    preg_match_all($pattern, $header_content, $matches);
    $cookiesOut = implode("; ", $matches['cookie']);

    $header['errno']   = $err;
    $header['errmsg']  = $errmsg;
    $header['headers']  = $header_content;
    $header['content'] = $body_content;
    $header['cookies'] = $cookiesOut;
    $header['all'] = $rough_content;
    return $header;
}
if (isset ($_GET['id'])){
    print_r (getSearch($_GET['id']));
}