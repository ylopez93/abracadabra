Saludos {{ $customer_details['name'] }} <br/>
      Gracias por ser parte del equipo Abracadabra <br/>
      <br/>
       Detalles de la orden que le ha sido asignada
        <br/><br/>
        ---------- Detalles de la Orden----------

        Order ID : {{ $order_details['Codigo'] }}<br/>
        Buscar En : {{ $order_details['Lugar'] }}<br/>
        Entregar En : {{ $order_details['Direccion Destino'] }}<br/>
        Teléfono : {{ $order_details['Teléfono'] }}<br/>
        Pedido : {{ $order_details['Pedido'] }}<br/>
        Localidad Destinatario : {{ $order_details['Localidad Destinatario'] }}<br/>
        Costo : {{ $order_details['Costo'] }}<br/>
        Especificaciones del Pedido : {{ $order_details['Mensaje'] }}<br/>
        <br/>
        <br/>
        <br/>
        Para mas detalles de la orden asignada acceda al sistema <br/>
        O contacte a su supervisor<br/> Buen dia!!!<br/>
		email : {{ $customer_details['email'] }}
