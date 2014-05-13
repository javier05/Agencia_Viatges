/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function(){
    $('.bxslider').bxSlider();
    $('.login').click(function(){
        $('#formlog').show("slow","swing");
    });
    
    
    
});

//function procesar()  
//{  
//    $.ajax ({  
//        url:    'calcular.php',                   /* URL a invocar asíncronamente */  
//        type:   'post',                           /* Método utilizado para el requerimiento */  
//        data:   $('#formulario').serialize(),     /* Información local a enviarse con el requerimiento */  
//        success:    function(request, settings)  
//{  
//    /* Cambiar el color del texto a verde */  
//    $('#mensaje').css('color', '#0ab53a');  
//    /* Mostrar un mensaje informando el éxito sucedido */  
//    $('#mensaje').html("Operación realizada exitosamente");  
//    /* Mostrar el resultado obtenido del cálculo solicitado */  
//    $('#resultado').html(request);  
//
//},
// error:  function(request, settings)  
//        {  
//            /* Cambiar el color del texto a rojo */  
//            $('#mensaje').css('color', '#ff0e0e');  
//            /* Mostrar el mensaje de error */  
//            $('#mensaje').html('Error: ' + request.responseText);  
//            /* Limpiar cualquier resultado anterior */  
//            $('#resultado').html('Error');  
//        }  
//    });  // Fin de la invocación al método ajax  
//}  // Fin de la función procesar  