<?php

error_reporting(E_ALL);

//create dataservice
                $dataService->updateOAuth2Token($accessToken);

//add customer
                $customerRequestObj = Customer::create(["DisplayName" => $customerName . getGUID()]);
                $customerRequestObj = $dataService->Add($customerRequestObj);

//add item
                $ItemObj = Item::create([
                "Name" => $itemName,
                "UnitPrice" => 25,
                "Type" => "Service",
                "IncomeAccountRef"=> ["value"=>  $incomeAccount->Id]
                ]);
                $resultingItemObj = $dataService->Add($ItemObj);

//create invoice using customer and item created above
                $invoiceObj = Invoice::create([
                "CustomerRef" => ["value" => $customerRequestObj>Id],
                "BillEmail" => ["Address" => "author@intuit.com"],
                "Line" => [
                        "Amount" => 100.00,
                        "DetailType" => "SalesItemLineDetail",
                        "SalesItemLineDetail" => [
                                "Qty" => 2,
                                "ItemRef" => ["value" => $resultingItemObj>Id]
                        ]

                ]);
                $resultingInvoiceObj = $dataService->Add($invoiceObj);

//send invoice email to customer
                $resultingMailObj = $dataService->sendEmail($resultingInvoiceObj,$resultingInvoiceObj->BillEmail->Address);

//receive payment for the invoice
                $paymentObj = Payment::create([
                        "CustomerRef" => ["value" => $customerRequestObj>Id],
                        "TotalAmt" => 100.00,
                        "Line" => [
                                "Amount" => 100.00,
                                "LinkedTxn" => ["TxnId" => $invoiceId,"TxnType" => "Invoice"]
                        ]
                ]);
                $dataService->Add($paymentObj);
        ?>
