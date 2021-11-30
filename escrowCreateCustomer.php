<?php
$curl = curl_init();
inlcude('curl_config.php');   
 CURLOPT_POSTFIELDS => json_encode(
        array(
            'phone_number' => '00000000000',
            'first_name' => 'Test',
            'last_name' => 'Tester',
            'middle_name' => 'T',
            'address' => array(
                'city' => 'Testville',
                'post_code' => '00000',
                'country' => 'US',
                'line2' => '',
                'line1' => '123 Any Street',
                'state' => 'SC',
            ),
            'email' => 'test@test.com',
        )
    )
));

$output = curl_exec($curl);
echo $output;
curl_close($curl);
?>
