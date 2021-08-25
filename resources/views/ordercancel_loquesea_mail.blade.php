Saludos {{ $customer_details['name'] }} <br/>
      Su orden a sido cancelada por el siguiente motivo <br/>
      <br/>
       Detalles de la cancelación de la orden
        <br/><br/>

        {{ $order_details['Message_Cancel'] }}<br/>

        ---------- Detalles de la Orden----------
        Order ID : {{ $order_details['Codigo'] }}<br/>
        Buscar En : {{ $order_details['Lugar'] }}<br/>
        Entregar En : {{ $order_details['Direccion Destino'] }}<br/>
        Teléfono : {{ $order_details['Teléfono'] }}<br/>
        Pedido : {{ $order_details['Pedido'] }}<br/>
        Localidad Destinatario : {{ $order_details['Localidad Destinatario'] }}<br/>
        Costo : {{ $order_details['Costo'] }}<br/>
        Especificaciones del Pedido : {{ $order_details['Mensaje'] }}<br/>
        Especificaciones de Cancelación : {{ $order_details['Message_Cancel'] }}<br/>
        <br/>
        <br/>
        <br/>
        Para mas detalles acceda al sistema <br/>
        O contacte a nuestro personal<br/> Buen dia!!!<br/>
		email : {{ $customer_details['email'] }}
