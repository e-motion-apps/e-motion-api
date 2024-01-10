<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            color: #333333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .header {
            background: linear-gradient(to right, #6dd5ed, #2193b0);
            color: white;
            text-align: center;
            padding: 10px 0;
        }
        .content {
            background-color: white;
            padding: 20px;
            margin: 10px;
        }
        .highlight {
            color: #2193b0;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            padding: 10px;
            font-size: small;
            color: grey;
        }
        .cta-button {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px 0;
            background-color: #2193b0;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<div class="header">
    <h1>Your Favorite Cities Update</h1>
</div>
<div class="content">
    <p>Hi,</p>
    <p>There was a change in one of your favourite cities.</p>
    <p><span class="highlight">{{$provider}}</span> has been <span class="highlight">{{$change}}</span> <span class="highlight">{{$city}}</span>.</p>
    <a href="{{$url}}" class="cta-button">Learn More</a>
</div>
<div class="footer">
    We'll update you again when there's another change.
</div>
</body>
</html>
