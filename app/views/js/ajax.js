/* Enviar formularios via AJAX */
const formularios_ajax=document.querySelectorAll(".FormularioAjax");

formularios_ajax.forEach(formularios => {

    formularios.addEventListener("submit",function(e){
        
        e.preventDefault();

        Swal.fire({
            title: '¿Estás seguro?',
            text: "Quieres realizar la acción solicitada",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, realizar',
            cancelButtonText: 'No, cancelar'
        }).then((result) => {
            if (result.isConfirmed){

                let data = new FormData(this);
                let method=this.getAttribute("method");
                let action=this.getAttribute("action");

                let encabezados= new Headers();

                let config={
                    method: method,
                    headers: encabezados,
                    mode: 'cors',
                    cache: 'no-cache',
                    body: data
                };

                fetch(action,config)
                .then(respuesta => respuesta.json())
                .then(respuesta =>{ 
                    return alertas_ajax(respuesta);
                });
            }
        });

    });

});



function alertas_ajax(alerta){
    if(alerta.tipo=="simple"){

        Swal.fire({
            icon: alerta.icono,
            title: alerta.titulo,
            text: alerta.texto,
            confirmButtonText: 'Aceptar'
        });

    }else if(alerta.tipo=="recargar"){

        Swal.fire({
            icon: alerta.icono,
            title: alerta.titulo,
            text: alerta.texto,
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            if(result.isConfirmed){
                location.reload();
            }
        });

    }else if(alerta.tipo=="limpiar"){

        Swal.fire({
            icon: alerta.icono,
            title: alerta.titulo,
            text: alerta.texto,
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            if(result.isConfirmed){
                document.querySelector(".FormularioAjax").reset();
            }
        });

    }else if(alerta.tipo=="redireccionar"){

        Swal.fire({
            icon: alerta.icono,
            title: alerta.titulo,
            text: alerta.texto,
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            if(result.isConfirmed){
                window.location.href=alerta.url;
                //window.history.back();window.location.href=alerta.url;
                
            }
        });
        
    }
}