Saludos {{ $customer_details['name'] }} <br/>
      Su orden a sido cancelada por el siguiente motivo <br/>
      <br/>
       Detalles de la cancelación de la orden
        <br/><br/>

        {{ $order_details['Message_Cancel'] }}<br/>

        ---------- Detalles de la Orden----------
        Order ID : {{ $order_details['Codigo'] }}<br/>
        Nombre del Remitente : {{ $order_details['Nombre Remitente'] }}<br/>
        Dirección del Remitente : {{ $order_details['Dirección Remitente'] }}<br/>
        Móvil del Remitente : {{ $order_details['Móvil Remitente'] }}<br/>
        Teléfono del Remitente : {{ $order_details['Teléfono Remitente'] }}<br/>
        Localidad del Remitente : {{ $order_details['Localidad Remitente'] }}<br/>
        Nombre del Destinatario : {{ $order_details['Nombre Destinatario'] }}<br/>
        Dirección del Destinatario : {{ $order_details['Dirección Destinatario'] }}<br/>
        Móvil de Destinatario : {{ $order_details['Móvil Destinatario'] }}<br/>
        Localidad del Destinatario : {{ $order_details['Localidad Destinatario'] }}<br/>
        Detalles del Objeto : {{ $order_details['Detalles Objeto'] }}<br/>
        Peso : {{ $order_details['Peso'] }}<br/>
        Mensaje : {{ $order_details['Mensaje'] }}<br/>
        Costo de Transportación : {{ $order_details['Costo de Transportacion'] }}<br/>
        <br/>
        <br/>
        <br/>
        Para mas detalles acceda al sistema <br/>
        O contacte a nuestro personal<br/> Buen dia!!!<br/>
		email : {{ $customer_details['email'] }}
