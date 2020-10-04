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
                                        <p class="h1">Toribio Tejada Import</p>
                                        <address style="font-size: .8rem;font-weight: 500; line-height: .9rem">
                                            C/ Simón Hdez, Edificio Anacaona Apt. B4, Santiago de los Caballeros.<br>
                                            809-966-6448<br>
                                            toribiotejadaimport@gmail.com
                                        </address>
                                    </div>
                                    <div class="col-12 mb-3" style="
                            font-size: 1rem;
                            line-height: 1.3rem;
                            font-weight: 500;
                            padding: 1rem;
                            border: 1px solid #e2e3e6;
                            border-radius: 3px;
                        ">
                                        <div class="row">
                                            <div class="col-6">
                                                Cliente: {{{ $lead['customer']['parsed_address'][0] ?? '' }}}<br>
                                                Dirección: {{{ $lead['customer']['parsed_address'][1] ?? '' }}}<br>
                                                Provincia/Ciudad: {{{ $lead['customer']['parsed_address'][3] ?? '' }}}<br>
                                                Teléfono: {{ $lead['customer']['parsed_address'][2] ?? '' }}
                                            </div>
                                            <div class="col-6 text-center">
                                                <h1 class="mt-4"><b>{{{ $lead['package_quantity'] }}}</b> bulto[s].</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 p-0">
                                        <div style="
                            border: 1px solid #e2e3e6;
                            border-radius: 3px;">
                                            <div class="table-responsive">
                                                <table class="table table-sm table-striped  table-vcenter card-table"
                                                       style="font-size: 1rem;">
                                                    <thead>
                                                    <tr>
                                                        <th>Factura</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach ($lead['delivery_lead_details'] as $delivery_lead_detail)
                                                    <tr>
                                                        <td class="font-weight-bold">{{{ $delivery_lead_detail['invoice']['display_name'] }}}</td>
                                                    </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body" style="color: #1d1d1d">
                                <div class="row">
                                    <div class="col-12 text-center">
                                        <p class="h1">Toribio Tejada Import</p>
                                        <address style="font-size: .8rem;font-weight: 500; line-height: .9rem">
                                            C/ Simón Hdez, Edificio Anacaona Apt. B4, Santiago de los Caballeros.<br>
                                            809-966-6448<br>
                                            toribiotejadaimport@gmail.com
                                        </address>
                                    </div>
                                    <div class="col-12 mb-3" style="
                            font-size: 1rem;
                            line-height: 1.3rem;
                            font-weight: 500;
                            padding: 1rem;
                            border: 1px solid #e2e3e6;
                            border-radius: 3px;
                        ">
                                        <div class="row">
                                            <div class="col-6">
                                                Cliente: {{{ $lead['customer']['parsed_address'][0] ?? '' }}}<br>
                                                Dirección: {{{ $lead['customer']['parsed_address'][1] ?? '' }}}<br>
                                                Provincia/Ciudad: {{{ $lead['customer']['parsed_address'][3] ?? '' }}}<br>
                                                Teléfono: {{ $lead['customer']['parsed_address'][2] ?? '' }}
                                            </div>
                                            <div class="col-6 text-center">
                                                <h1 class="mt-4"><b>{{{ $lead['package_quantity'] }}}</b> bulto[s].</h1>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 p-0">
                                        <div style="
                            border: 1px solid #e2e3e6;
                            border-radius: 3px;">
                                            <div class="table-responsive">
                                                <table class="table table-sm table-striped  table-vcenter card-table"
                                                       style="font-size: 1rem;">
                                                    <thead>
                                                    <tr>
                                                        <th>Factura</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach ($lead['delivery_lead_details'] as $delivery_lead_detail)
                                                        <tr>
                                                            <td class="font-weight-bold">{{{ $delivery_lead_detail['invoice']['display_name'] }}}</td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
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
