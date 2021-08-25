Saludos {{ $customer_details['name'] }} <br/>
      Gracias por ser parte del equipo Abracadabra <br/>
      <br/>
       Detalles de la orden que le ha sido asignada
        <br/><br/>
        ---------- Detalles de la Orden----------
        Order ID : {{ $order_details['Codigo'] }}<br/>
        Nombre : {{ $order_details['Nombre'] }}<br/>
        Teléfono : {{ $order_details['Teléfono'] }}<br/>
        Dirección : {{ $order_details['Dirección'] }}<br/>
        Localidad : {{ $order_details['Localidad'] }}<br/>
        Hora de entrega desde : {{ $order_details['Hora de entrega desde'] }}<br/>
        Hora de entrega hasta : {{ $order_details['Hora de entrega hasta'] }}<br/>
        Mensaje : {{ $order_details['Mensaje'] }}<br/>
        Costo de Transportación : {{ $order_details['Costo de Transportacion'] }}<br/>

        ---------- Detalles de la compra----------<br/>

        @foreach ( $order_details['Productos'] as $item)
        <tr>
          <td>{{$item->name}}</td>
          <td>{{$item->quantity}}</td>
          <td>{{$item->total}}</td>
        </tr>
        @endforeach
        <br/>
        <br/>
        <br/>
        Para mas detalles de la orden asignada acceda al sistema <br/>
        O contacte a su supervisor<br/> Buen dia!!!<br/>
		email : {{ $customer_details['email'] }}
