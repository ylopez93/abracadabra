Saludos {{ $customer_details['name'] }} <br/>
      Gracias por ser parte del equipo Abracadabra <br/>
      <br/>
       Detalles de la orden que le ha sido asignada
        <br/><br/>
        ---------- Detalles de la Orden----------

        Order ID : {{ $order_details['Codigo'] }}<br/>
        Municipio de Origen : {{ $order_details['Municipio de Origen'] }}<br/>
        Localidad de Origen : {{ $order_details['Localidad de Origen'] }}<br/>
        Dirección de Origen : {{ $order_details['Dirección de Origen'] }}<br/>
        Teléfono : {{ $order_details['Teléfono'] }}<br/>
        Localidad Destino : {{ $order_details['Localidad Destino'] }}<br/>
        Municipio Destino : {{ $order_details['Municipio Destino'] }}<br/>
        Dirección Destino : {{ $order_details['Dirección Destino'] }}<br/>
        Costo de Transportación : {{ $order_details['Costo de Transportacion'] }}<br/>
        <br/>
        <br/>
        <br/>
        Para mas detalles de la orden asignada acceda al sistema <br/>
        O contacte a su supervisor<br/> Buen dia!!!<br/>
		email : {{ $customer_details['email'] }}
