Saludos {{ $customer_details['name'] }} <br/>
      Su orden a sido cancelada por el siguiente motivo <br/>
      <br/>
       Detalles de la cancelación de la orden
        <br/><br/>

        {{ $order_details['Message_Cancel'] }}<br/>

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
        Para mas detalles acceda al sistema <br/>
        O contacte a nuestro personal<br/> Buen dia!!!<br/>
		email : {{ $customer_details['email'] }}
