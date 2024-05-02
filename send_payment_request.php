<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = 'https://api.sandbox.hit-pay.com/v1/payment-requests';
    $apiKey = 'f5d54345544d31c216e0b23b8cb19a75a46cdf7d5e7ea7fac0ea5e35003b47ba';
    $data = array(
        'email' => 'hikmadhnoor@gmail.com',
        'redirect_url' => 'https://hikmadh.com/success',
        'reference_number' => 'REF123',
        'webhook' => 'https://hikmadh.com/webhook',
        'currency' => 'INR',
        'amount' => '500'
    );

    $options = array(
        'http' => array(
            'header' => "Content-type: application/x-www-form-urlencoded\r\n"
                . "X-BUSINESS-API-KEY: $apiKey\r\n"
                . "X-Requested-With: XMLHttpRequest\r\n",
            'method' => 'POST',
            'content' => http_build_query($data)
        )
    );

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result === false) {
        echo json_encode(array('error' => 'Unable to send payment request.'));
    } else {
        $responseData = json_decode($result, true);
        echo json_encode(array('url' => $responseData['url']));
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Request</title>
</head>

<body>
    <button id="paymentButton">Send Payment Request</button>
    <div id="paymentResponse"></div>

    <script>
        document.getElementById('paymentButton').addEventListener('click', function() {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var response = JSON.parse(xhr.responseText);
                    document.getElementById('paymentResponse').innerHTML = 'Payment URL: ' + response.url;
                    // Open the payment URL in a new tab
                    window.open(response.url, '_blank');
                }
            };
            xhr.send();
        });
    </script>
</body>

</html>
