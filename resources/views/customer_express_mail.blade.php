Hola {{ $customer_details['name'] }} <br/>
      Gracias por ordenar con nosotros <br/>
      <br/>
      Su orden ha sido creada.
        <br/><br/>
        ---------- Detalles de la orden----------<br/>
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
        Para mas detalles acceda a su cuenta en nuestro sitio o contáctenos <br/>
        <br/> Gracias por elegir ser parte de la familia Abracadabra!!!<br/>
		email : {{ $customer_details['email'] }}

