<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Reporte de Ventas</title>
    <link rel="stylesheet" href="{{ asset('assets/css/Report-pdf.css') }}">
</head>
<body>
<div id="page_pdf">
	<table id="factura_head">
		<tr>
			<td class="logo_factura">
				<div>
					<img src="img/logo.png">
				</div>
			</td>
			<td class="info_empresa">
				<div>
					<span class="h2">SISTEMA VENTAS SVLCR</span>
					<p>Av. Americas</p>
					<p>Teléfono: 4475896</p>
					<p>Email: SVLCR@gmail.com</p>
				</div>
			</td>
			<td class="info_factura">
				<div class="round">
                    @foreach($data as $item)
					<span class="h3">Factura</span>
					<p>No. Factura: <strong>000001</strong></p>
					<p>Fecha:  {{$fechaHoy}} </p>
					<p>Hora: 10:30am</p>
					<p>Vendedor: {{$item->user}}</p>  
                   @endforeach
				</div>
			</td>
		</tr>
	</table>
	<!--<table id="factura_cliente">
		<tr>
			<td class="info_cliente">
				<div class="round">
					<span class="h3">Cliente</span>
					<table class="datos_cliente">
						<tr>
							<td><label>Nit:</label><p>54895468</p></td>
							<td><label>Teléfono:</label> <p>7854526</p></td>
						</tr>
						<tr>
							<td><label>Nombre:</label> <p>Angel Arana Cabrera</p></td>
							<td><label>Dirección:</label> <p>Calzada Buena Vista</p></td>
						</tr>
					</table>
				</div>
			</td>

		</tr>
	</table>-->

	<table id="factura_detalle" width="100%">
			<thead>
				<tr>
					<th width="20%">FOLIO</th>
					<th class="textleft" width="15%">IMPORTE</th>
					<th class="textright" width="15%">ITEMS</th>
					<th class="textright" width="12%"> STATUS</th>
					<th>USUARIO</th>
					<th class="textcenter" width="18%"> FECHA</th>
				</tr>
			</thead>
			<tbody id="detalle_productos">
                @foreach($data as $item)
				<tr>
                    <td align="center" >{{$item->id}}</td>
                    <td align="center" >${{number_format($item->total,2)}}</td>
                    <td align="center" >{{$item->items}}</td>
                    <td align="center" >{{$item->status}}</td>
                    <td align="center" >{{$item->user}}</td>
                    <td align="center" >{{\Carbon\Carbon::parse($item->created_at)->format('d-m-Y')}}</td>
                    
                    
                </tr>
                @endforeach
			</tbody>
			<tfoot id="detalle_totales">
				<tr>
                    <td class="text-center" >
                        <span><b>TOTALES</b></span>
                    </td>
                    <td colspan="1" class="text-center">
                        <span><strong>${{number_format($data->sum('total'),2)}}</strong></span>
                    </td>
                    <td class="textcenter">
                        {{$data->sum('items')}}
                    </td>
                    <td ></td>
                </tr>
		</tfoot>
	</table>
	<div>
		<p class="nota">Si usted tiene preguntas sobre esta factura, <br>pongase en contacto con nombre, teléfono y Email</p>
		<h4 class="label_gracias">¡Gracias por su compra!</h4>
	</div>

</div>

</body>
</html>