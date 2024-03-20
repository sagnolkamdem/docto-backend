<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Vaccin</title>
    <style>
        h1, h2, h3 {
            text-align: center;
            color: #50D6B6;
            margin-bottom: 1%;
        }

        body{
            padding: 10px;
        }

        .bold{
            font-weight: bold;
        }

        .font-20{
            font-size: 20px;
        }

        .display-flex{
            display: flex;
        }
        .justify-content-center{
            justify-content: center;
        }
        .title{
            display: flex;
            justify-content: center;
            align-items: center;
            color:#50D6B6 ;
            margin-bottom: 10px;
        }

        .underline{
            text-decoration: underline #50D6B6;
        }

        .header{
            display: flex;
            justify-content: space-evenly;
            justify-items: center;
            align-content: space-between;
            align-items: baseline;
        }

        .name{
            font-size: 26px;
            font-weight: 600;
            margin-bottom: 15px;
            align-items: center;
            align-content: center;
            text-align: center;
        }

        .speciality{
            font-size: 20px;
            align-items: center;
            align-content: center;
            text-align: center;
        }

        .order_number{
            font-size: 16px;
            font-weight: 600;
            text-align: center;
        }

        .line{
            height:2px;
            border-width:0;
            background-color:#50D6B6;
            width: 74px;
            color:#50D6B6 !important ;
            margin-bottom: 20px;
        }

        .separator{
            margin-top: 20px;
            background-color:#50D6B6 !important ;
        }

        .city_date{
            font-size: 20px;
            font-weight: 500;
            margin-bottom: 30px;
            text-align: right;
        }

        .date {
            font-weight: bold;
            margin-left: 4px;
            position: relative;
        }

        .date::after {
            content: "";
            position: absolute;
            bottom: -2px; /* Ajustez la valeur selon vos besoins */
            left: 0;
            width: 100%;
            border-bottom: 1px dotted;
        }

        .info_patient{
            display: flex;
            justify-content: space-between;
        }

        .item{
            display: flex;
            justify-content: space-between;
            align-content: center;
            align-self: center;
        }
    </style>
</head>
<body>
<div class="ordonnance_container">
    <div class="title underline">
        <h1>{{\Illuminate\Support\Str::upper($establishment->name ?? $title)}}</h1>
    </div>
    <div class="row header">
        <div class="col-md-4 fr_head">
            <div class="name">Docteur {{\Illuminate\Support\Str::upper($practician->first_name." ".$practician->last_name)}}</div>
            <div class="speciality">{{$practician->specialityData->name}}</div>
            <hr class="line">
            {{--                    <div class="order_number">--}}
            {{--                        Numéro d'ordre : xxx/xxx--}}
            {{--                    </div>--}}
        </div>
        <div class="col-md-4 logo_head">
            <img src="{{ "data:image/png;base64,".base64_encode(file_get_contents('img/logo_icon.png')) }}">
        </div>
        <div class="col-md-4 arabe_head">
            <div class="name">Docteur {{\Illuminate\Support\Str::upper($practician->first_name." ".$practician->last_name)}}</div>
            <div class="speciality">{{$practician->specialityData->name}}</div>
            <hr class="line">
        </div>
    </div>
    <div class="separator"><hr></div>
    <div class="bloc_patient">
        <div class="city_date">
            <label for="">{{ $establishment->address }} le :</label>
            <label for="" class="date"> {{ now()->toDateString() }}</label>
        </div>
        <div class="info_patient">
            <div>
                <label for="">Nom: </label>
                <label for="" class="date"> {{ $patient->last_name}}</label>
            </div>
            <div>
                <label for="">Prénom : </label>
                <label for="" class="date"> {{ $patient->first_name}}</label>
            </div>
            <div>
                <label for="">Age : </label>
                <label for="" class="date"> {{ \Carbon\Carbon::now()->year - \Carbon\Carbon::create($patient->birthdate)->year ?? "N/D" }}</label>
            </div>
        </div>
    </div>
    <div class="title">
        <h2>VACCIN</h2>
    </div>
    @foreach($metadata as $drug)
        <div class="item">
            <div class="item_name">
                <label class="bold font-20">{{$drug->name}}</label>
                <div>
                    <label>{{$drug->description}}</label>
                </div>
            </div>

        </div>
    @endforeach
    

</div>
<br>
{{--        <footer class="footer">--}}
{{--            <hr style="height:2px;border-width:0;color:#50D6B6;background-color:#50D6B6">--}}
{{--            <div class="display-flex justify-content-center">--}}
{{--                Tel : +21358555445 | +21358555445--}}
{{--            </div>--}}
{{--            <div class="display-flex justify-content-center">--}}
{{--                email : +21358555445--}}
{{--            </div>--}}
{{--        </footer>--}}
</body>
</html>
