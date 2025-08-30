<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>You've got mail!</title>
    <style>
        /* Reset styles */
        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        /* Container */
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
        }
        /* Header */
        .header {
            background-color: #727372;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        /* Body */
        .body-content {
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
        }

        /* Footer */
        .footer {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container">
        <div class="header">
            <h1>Customer Message Info</h1>
        </div>
        <div class="body-content">
            <p><h3>Subject:{{$data['subject']}}</h3></p>
            <p><h3>Message:{{$data['message']}}</h3></p>

        </div>
        <div class="footer">
            <p>© Ell. All rights reserved.</p>
        </div>
    </div>

</body>
</html>
