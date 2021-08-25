Hola {{ $customer_details['name'] }} <br/>
      Gracias por ordenar con nosotros <br/>
      <br/>
      Su orden ha sido creada.
        <br/><br/>
        ---------- Detalles de la orden----------<br/>
        Order ID : {{ $order_details['Codigo'] }}<br/>
        Buscar En : {{ $order_details['Buscar en'] }}<br/>
        Entregar En : {{ $order_details['Entregar en'] }}<br/>
        Teléfono : {{ $order_details['Teléfono'] }}<br/>
        Pedido : {{ $order_details['Pedido'] }}<br/>
        Especificaciones de la Pedido : {{ $order_details['Mensaje'] }}<br/>
        Localidad Destinatario : {{ $order_details['Localidad Destinatario'] }}<br/>
        <br/>
        <br/>
        Para mas detalles acceda a su cuenta en nuestro sitio o contáctenos <br/>
        <br/> Gracias por elegir ser parte de la familia Abracadabra!!!<br/>
		email : {{ $customer_details['email'] }}

