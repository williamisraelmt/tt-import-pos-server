<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css">
</head>

<body class="antialiased bg-white">
<div class="page">
    <div class="content">
        <div class="container-xl">
            @foreach ($leads as $key => $lead)

                <div class="row page-break">
                    <div class="col-12">
                        <div class="card mb-5">
                            @if ($key === 0)
                            <div class="card-header d-print-none">
                                <h3 class="card-title"></h3>
                                <div class="card-options">
                                    <button type="button" class="btn btn-primary" onclick="javascript:window.print();">
                                        <i
                                            class="si si-printer"></i> Imprimir
                                    </button>
                                </div>
                            </div>
                            @endif
                            <div class="card-body" style="color: #1d1d1d">
                                <div class="row">
                                    <div class="col-12 text-center">
                                        <img src="/images/TTI1.jpg" style="width: 170px;">
                                        <p class="my-1" style="font-size: 12px; font-weight: 400">Partes para generadores eléctricos y motores estacionarios<br></p>
                                        <address style="font-size: .8rem;font-weight: 500; line-height: .9rem" class="mb-2">
                                            C/ Simón Hdez, Edificio Anacaona Apt. B4, Santiago de los Caballeros.<br>
                                            809-966-6448<br>
                                            toribiotejadaimport@gmail.com<br>
                                            RNC: 131894879
                                        </address>
                                        <h2 style="border-bottom: 1px dashed rgba(110,117,130,0.4); border-top: 1px dashed rgba(110,117,130,0.4)" class="font-weight-bold">CONDUCE DE BULTOS</h2>
                                    </div>
                                    <div class="col-12" style="
                            font-size: 1rem;
                            line-height: 1.3rem;
                            font-weight: 500;
                        ">
                                        <div class="row">
                                            <div class="col-12 text-right">{{$lead['parsed_created_at']}}</div>
                                            <div class="col-7">
                                                <table class="table table-sm table-borderless">
                                                    <tbody>
                                                    <tr>
                                                        <td style="width: 100px">Cliente:</td>
                                                        <td>{{{ $lead['customer']['parsed_address'][0] ?? '' }}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Dirección:</td>
                                                        <td>{{{ $lead['customer']['parsed_address'][1] ?? '' }}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Ciudad:</td>
                                                        <td>{{{ $lead['customer']['parsed_address'][3] ?? '' }}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Teléfono:</td>
                                                        <td>{{ $lead['customer']['parsed_address'][2] ?? '' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Facturas:</td>
                                                        <td>@foreach ($lead['delivery_lead_details'] as $key => $delivery_lead_detail)
                                                                <a href="javascript:void(0)" class="text-danger">{{{ $delivery_lead_detail['invoice']['display_name'] }}}</a>
                                                                @if ($key+1 !== sizeof($lead['delivery_lead_details']))
                                                                    ,
                                                                @endif
                                                            @endforeach</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-5 text-center">
                                                <h1 class="mt-4"><b>{{{ $lead['package_quantity'] }}}</b> Bulto[s].</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6 text-center">
                                        _________________________________________<br>
                                        <b>Entregado por</b>
                                    </div>
                                    <div class="col-6 text-center">
                                        _________________________________________<br>
                                        <b>Recibido por</b>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card" >
                            <div class="card-body" style="color: #1d1d1d">
                                <div class="row">
                                    <div class="col-12 text-center">
                                        <img src="/images/TTI1.jpg" style="width: 170px;">
                                        <p class="my-1" style="font-size: 12px; font-weight: 400">Partes para generadores eléctricos y motores estacionarios<br></p>
                                        <address style="font-size: .8rem;font-weight: 500; line-height: .9rem" class="mb-2">
                                            C/ Simón Hdez, Edificio Anacaona Apt. B4, Santiago de los Caballeros.<br>
                                            809-966-6448<br>
                                            toribiotejadaimport@gmail.com<br>
                                            RNC: 131894879
                                        </address>
                                        <h2 class="font-weight-bold" style="border-bottom: 1px dashed rgba(110, 117, 130, 0.4);border-top: 1px dashed rgba(110, 117, 130, 0.4);">CONDUCE DE BULTOS</h2>
                                    </div>
                                    <div class="col-12" style="
                            font-size: 1rem;
                            line-height: 1.3rem;
                            font-weight: 500;
                        ">
                                        <div class="row">
                                            <div class="col-12 text-right">{{$lead['parsed_created_at']}}</div>
                                            <div class="col-7">
                                                <table class="table table-sm table-borderless">
                                                    <tbody>
                                                    <tr>
                                                        <td style="width: 100px">Cliente:</td>
                                                        <td>{{{ $lead['customer']['parsed_address'][0] ?? '' }}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Dirección:</td>
                                                        <td>{{{ $lead['customer']['parsed_address'][1] ?? '' }}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Ciudad:</td>
                                                        <td>{{{ $lead['customer']['parsed_address'][3] ?? '' }}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Teléfono:</td>
                                                        <td>{{ $lead['customer']['parsed_address'][2] ?? '' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Facturas:</td>
                                                        <td>@foreach ($lead['delivery_lead_details'] as $key => $delivery_lead_detail)
                                                                <a href="javascript:void(0)" class="text-danger">{{{ $delivery_lead_detail['invoice']['display_name'] }}}</a>
                                                                @if ($key+1 !== sizeof($lead['delivery_lead_details']))
                                                                    ,
                                                                @endif
                                                            @endforeach</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-5 text-center">
                                                <h1 class="mt-4"><b>{{{ $lead['package_quantity'] }}}</b> Bulto[s].</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6 text-center">
                                        _________________________________________<br>
                                        <b>Entregado por</b>
                                    </div>
                                    <div class="col-6 text-center">
                                        _________________________________________<br>
                                        <b>Recibido por</b>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
</body>
</html>
