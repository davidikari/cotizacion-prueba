<!doctype html>
<html>
    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta charset="utf-8">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Untitled</title>
        <link rel="stylesheet" href="css/app.css">
        <link rel="author" href="humans.txt">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    </head>
    
    <body>
        @include('navbar')
        
            <div class="container">
                <div class="blanco">
                <!-- Contenido de la mitad blanca de la pantalla -->
                    <div>
                        <div>
                            <br>
                            <h3>CALCULÁ TU ENVÍO</h3>
                            <p>Ingresa las dimensiones  y el peso de tu paquete para calcular el costo de envío.</p>
                        </div>
                        <br>  
                        <div>
                            <div style="display: inline-block;">
                                <label for="origen" style="display: block; font-size: 12px;">
                                    <b>CP Origen</b>
                                </label>
                                <input type="text" id="origen" placeholder="Ej: 5500" style=" border: solid 1px argb(5, 5, 5, 0.5);">        
                            </div>
                            <div style="display: inline-block;">
                                <label for="destino" style="display: block; font-size: 12px;">
                                    <b>CP Destino</b>
                                </label>
                                <input type="text" id="destino" placeholder="Ej: 5500">    
                            </div>
                        </div>
                        <br>
                        <div>
                            <div style="display: inline-block; align: center">
                                <div style="display: flex;">
                                    <img src="{{ asset('images/kilo.png') }}" alt="Imagen" style="width: 50px; height: 50px; margin-right: 5px;">
                                    <div style="display: inline-block;">
                                        <label for="kilos" style="display: block; font-size: 12px;">
                                            <b>Kilos</b>
                                        </label>
                                        <input type="text" id="kilos" placeholder="Ej: 5 kg"> 
                                    </div>
                                </div>
                                       
                            </div>
                            <div style="display: inline-block;visibility: hidden;">
                                <label for="" style="display: block; font-size: 12px;">
                                    <b>abc</b>
                                </label>
                                <input type="text" id="" placeholder="Ej: 5500">    
                            </div>
                        </div>
                        <br>
                        <div style="text-align: center;">
                            <button class="btn btn-danger rounded-pill px-5" onclick="validarCampos()">Cotizar</button>   
                        </div>
                    </div>
                </div>
                <div class="gris">
                    <img src="{{ asset('images/fondo.png') }}" alt="">
                </div>
            </div>
        
        @include('footer')
    </body> 
    
</html>

<style>
    .container {
    display: flex;
    /*height: 100vh;  Esta propiedad asegura que el contenedor ocupe toda la altura de la pantalla */
}

.container > div {
    flex: 1;
}

.blanco {
    background-color: white;
}

/* Estilos para la mitad derecha de la pantalla (gris) */
.gris {
    background-color: #f2f2f2;
}
input{
    border: solid 1px;
    border-color: grey;
}
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">
    function validarCampos() {

        const inputNumero1 = document.getElementById('origen');
        const inputNumero2 = document.getElementById('destino');
        const inputNumero3 = document.getElementById('kilos');
        

        
        if (inputNumero1.value === '' || isNaN(inputNumero1.value)) {
            inputNumero1.value ='';
            alert('El campo CP '+inputNumero1.id+' no es válido. Debe ser un número.');
            return;
        }else{
            if (inputNumero2.value === '' || isNaN(inputNumero2.value)) {
                inputNumero2.value ='';
                alert('El campo CP '+inputNumero2.id+' no es válido. Debe ser un número.');
                return;
            }else{
                if (inputNumero3.value === '' || isNaN(inputNumero3.value)) {
                    inputNumero3.value ='';
                    alert('El campo Kilos no es válido. Debe ser un número.');
                    return;
                }else{
                    enviarDatos(inputNumero1.value, inputNumero2.value, inputNumero3.value);
                }
            }
        } 
    }
    function enviarDatos(origen, destino, kilos) {
        var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        $.ajax({
            url: '/enviar-datos',
            type: 'POST',
            data: { origen: origen, destino: destino, kilos: kilos, _token: token },
            success: function(response) {
                
                console.log('Respuesta del servidor:', response);
            },
            error: function(error) {
                
                console.error('Error en la solicitud:', error);
            }
        });
    }



</script>