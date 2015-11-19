<?php

function sendAPI($url, $variables=null, $post=false, $headers=null)
{
    $params = http_build_query($variables);

    $curl = curl_init();
    if($post)
    {
      curl_setopt($curl,	CURLOPT_URL				, $url);
      curl_setopt($curl,	CURLOPT_POST			, 1);
      curl_setopt($curl,	CURLOPT_POSTFIELDS		, $params);
    }
    else
    {
      curl_setopt($curl,	CURLOPT_URL				, "$url?$params");
    }
    if(isset($headers))
    {
      curl_setopt($curl,	CURLOPT_HTTPHEADER		, $headers);
    }
    curl_setopt($curl,	CURLOPT_RETURNTRANSFER	, true);
    curl_setopt($curl,	CURLOPT_ENCODING 		, "gzip");
    curl_setopt($curl,	CURLOPT_SSL_VERIFYPEER	, TRUE);

    if(!$result = curl_exec($curl))
    {
      return curl_error($curl);
    }

    $data = json_decode($result);
    if($data == NULL)
    {
      return $result;
    }
    return $data;
}


 ?>
