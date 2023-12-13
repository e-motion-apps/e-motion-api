<!DOCTYPE html>
<html>
<head>
    <title>New Suggestion</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        .container {
            margin: 0 auto;
            padding: 20px;
            max-width: 600px;
            background: #f2f2f2;
            border-radius: 5px;
            text-align: center;
        }
        .suggestion {
            text-align: left;
            background: #fff;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>New Suggestion Received</h1>
    <p>From: {{ $data['name'] }}</p>
    <div class="suggestion">
        <p><strong>Suggestion:</strong></p>
        <p>{{ $data['suggestion'] }}</p>
    </div>
</div>
</body>
</html>

