<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Ordonnance</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style type="text/css">
        h2, h3 {
            text-align: center;
            color: #50D6B6;
            margin-bottom: 1%;
        }

        .header {
            display: flex;
            justify-content: space-evenly;
            justify-items: center;
            align-content: space-between;
            align-items: baseline;
            text-align: center;
        }

        .name {
            align-items: center;
            align-content: center;
            text-align: center;
        }

        .date {
            text-align: right;
        }

        .patient-line {
            margin-left: 30%;
            display: flex;
            justify-content: space-evenly;
            justify-items: center;
            align-content: space-between;
            align-items: baseline;
        }

    </style>
</head>
<body>
    <h2><u>{{ \Illuminate\Support\Str::upper($establishment->name ?? $title) }}</u></h2>
    <div class="row header">
        <div class="name col-md-4">
            <h4>Docteur {{\Illuminate\Support\Str::upper($practician->first_name." ".$practician->last_name)}}</h4>
            <p>{{$practician->specialityData->name}}</p>
        </div>
        <div class="img col-4">
            <img src="{{asset('img/logo.png')}}" alt="Logo" width="200px" height="auto">
        </div>
        <div class="arab col-md-4">

        </div>
    </div>
    <hr style="height:2px;border-width:0;color:#50D6B6;background-color:#50D6B6">
    <div class="date">
        {{ $establishment->address }} le: <b>{{ now()->toDateString() }}</b>
    </div>
    <div class="patient-line row">
        <div class="col-md-4">
            Nom: <b>{{ $patient->last_name}}</b>
        </div>
        <div class="col-md-4">
            Prenom: <b>{{ $patient->first_name}}</b>
        </div>
        <div class="col-md-4">
            Age: <b>{{ $patient->birthdate ?? "N/D"}}</b>
        </div>
    </div>
    <h3>ORDONNANCE</h3>
    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
        consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
        cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
        proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
    </p>

{{--    <footer>--}}
{{--        <hr style="height:2px;border-width:0;color:#50D6B6;background-color:#50D6B6">--}}
{{--        <p>TABIBLIB</p>--}}
{{--    </footer>--}}
</body>

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
</html>
