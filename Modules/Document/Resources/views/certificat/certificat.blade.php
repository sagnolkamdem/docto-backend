<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Certificat</title>
    <style>
        body{
            padding: 10px;
        }

        .display-flex {
            display: flex;
        }
        .justify-content-center {
            justify-content: center;
        }

        .justify-content-end{
            justify-content: end;
            align-items: flex-end;
            text-align: right;
        }

        .title{
            display: flex;
            justify-content: center;
            align-items: center;
            color:#50D6B6 ;
            margin-bottom: 10px;
        }

        .content {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin: 20px;
        }
        .font-26{
            font-size: 26px;
        }

        .bold{
            font-weight: bold;
        }

        .line{
            text-decoration: underline;
        }

        .mt-4{
            margin-top: 1em;
        }

        .mt-8{
            margin-top: 2em;
        }

        .mt-2{
            margin-top: 0.5em;
        }

        .underline{
            border-bottom: 2px dashed;
            width: 100%;
            margin-bottom: 30px;
        }
        .absolute {
            position: absolute;
            top: 100%;
            bottom: 10px;
        }
    </style>
</head>
<body>

    <div class="container">
       <div class="underline"></div>
       <div class="display-flex title justify-content-center bold font-26 line">{{\Illuminate\Support\Str::upper($establishment->name ?? $title)}}</div>
        <div class="display-flex title justify-content-center bold font-26 line mt-2">{{$practician->specialityData->name}}</div>
        <br> <br>

        <div class="display-flex title justify-content-center bold font-26 line" style="text-align:center">{{\Illuminate\Support\Str::upper($metadata->title ?? "Certificat Médical")}}</div>
        <div class="doctor-note">
            <br><br><br><br><br><br><br>
            <div class="content mt-8">{!! $metadata->body !!}</div>
            <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
            <div class="justify-content-end mt-4"> {{ $establishment->address }} le : {{ now()->toDateString() }}</div>
            <div class="justify-content-end bold mt-4"> Docteur {{\Illuminate\Support\Str::upper($practician->first_name." ".$practician->last_name)}}</div>
            <div class="justify-content-end bold mt-2">
                <img width="150px" height="150px" src={{"data:image/png;base64,".base64_encode($practician->signature->path ? file_get_contents(Str::replace(config('app.url')."/signature/","",$practician->signature->path)) : file_get_contents('img/logo_icon.png'))}}>
            </div>
        </div>
        <br><br>
{{--        <div class="absolute underline"></div>--}}
    </div>

</body>
</html>
