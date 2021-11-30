<?php
echo "<h1>Escrow.com Invoicing via API</h1>";
$curl = curl_init();
include('curl_config.php');    
CURLOPT_POSTFIELDS => json_encode(
        array(
            'currency' => 'usd',
            'items' => array(
                array(
                    'description' => 'wtstest.com',
                    'schedule' => array(
                        array(
                            'payer_customer' => 'charwebsllc@gmail.com',//'me',
                            'amount' => '1000.0',
                            'beneficiary_customer' => 'mestart@startadvertising.com',
                        ),
                    ),
                    'title' => 'wtstest.com',
                    'inspection_period' => '259200',
                    'type' => 'domain_name',
                    'quantity' => '1',
                    'extra_attributes' => array(
                        'image_url' => 'https://i.ebayimg.com/images/g/RicAAOSwzO5e3DZs/s-l1600.jpg',
                        'merchant_url' => 'https://www.ebay.com'
                    ),
                ),
            ),
            'description' => 'WTS test invoice for escrow amt.',
            'parties' => array(
                array(
                    'customer' => 'charwebsllc@gmail.com',
                    'role' => 'buyer',
                ),
                array(
                    'customer' => 'mestart@startadvertising.com',
                    'role' => 'seller',
                ),
            ),
        )
    )
));

$output = curl_exec($curl);
echo $output;
curl_close($curl);
?>
