<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif
        }
        #header {
            width: 100%;
            text-align: center;
            margin-bottom: 20px;
        }

        #header img {
            display: block;
            margin: 0 auto;
            width: 300px;
            height: 200px;
        }

        #header-title {
            width: 100%;
            text-align: center;
        }

        #table {
            width: 100%;
            display: flex;
            justify-content: center;
        }

        #table table{
            margin: 0 auto;
            border-collapse: collapse;
            width: 80%;
        }

        #table th, td {
            border: 1px solid black;
            padding: 20px
        }

        #table th {
            font-size: 22px;
        }

        #table td {
            text-align: center;
            font-size: 19px;
        }

        #description {
            margin: 0 auto;
            margin-top: 30px;
            width: 80%;
            text-align: justify;
        }

        #participants {
            margin: 0 auto;
            margin-top: 50px;
            width: 80%;
        }

        #participants-title {
            text-align: center;
            margin-bottom: 30px;
        }

        #participants-content{
            width: 100%;
            display: flex;
            justify-content: center;
        }

        #participants-content ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            gap: 10px;
        }

        #participants-content ul li {
            padding: 5px 10px;
            border-bottom: 1px solid #5d5d5d;
            text-align: center;
        }
    </style>
</head>
<body>
    <div id="header">
        <img src="{{ $base64Image }}" alt="">
        <div id="header-title">
            <h1>{{ $plan->title.' - '.explode('-', $plan->date)[0]}}</h1>
            <br>
            <p>{{ auth()->user()->name }}</p>
        </div>
    </div>
    <br>
    <br>
    <div id="content">
        <div id="table">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Location</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $plan->date }}</td>
                        <td>{{ $plan->location }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div id="description">
            <p>{{ $plan->description }}</p>
        </div>

        <div id="participants">
            <div id="participants-title">
                <h3>Participants</h3>
            </div>
            <div id="participants-content">
                <ul>
                    @foreach($plan->participants as $participant)
                        <li>{{ $participant->name }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
