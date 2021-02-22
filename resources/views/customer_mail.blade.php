Hola {{ $customer_details['name'] }} <br/>
      Gracias por ordenar con nosotros <br/>
      <br/>
        Su orden ha sido creada.
        <br/><br/>
        ---------- Detalles de la orden----------<br/>
        Order ID : {{ $order_details['Codigo'] }}<br/>
        Usuario : {{ $order_details['Nombre'] }}<br/>
        Telefono : {{ $order_details['Teléfono'] }}<br/>
        Direccion : {{ $order_details['Dirección'] }}<br/>
        Localidad : {{ $order_details['Localidad'] }}<br/>
        Hora de entrega :<br/>
        Desde: {{ $order_details['Hora de entrega desde'] }}<br/>
        Hasta: {{ $order_details['Hora de entrega hasta'] }}<br/>
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
        Para mas detalles acceda a su cuenta en nuestro sitio o contáctenos <br/>
        <br/> Gracias por elegir ser parte de la familia Abracadabra!!!<br/>
		email : {{ $customer_details['email'] }}

