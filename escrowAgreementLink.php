
<?php
$curl = curl_init();
include('curl_config.php');
    CURLOPT_CUSTOMREQUEST => 'PATCH',
    CURLOPT_POSTFIELDS => json_encode(
        array(
            'action' => 'agree',
        )
    )
));

$output = curl_exec($curl);
echo $output;
curl_close($curl);
?>
