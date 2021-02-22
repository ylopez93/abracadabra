Hola {{ $customer_details['name'] }} <br/>
      Gracias por ordenar con nosotros <br/>
      <br/>
      Su orden ha sido creada.
        <br/><br/>
        ---------- Detalles de la orden----------<br/>
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
        Para mas detalles acceda a su cuenta en nuestro sitio o contáctenos <br/>
        <br/> Gracias por elegir ser parte de la familia Abracadabra!!!<br/>
		email : {{ $customer_details['email'] }}

