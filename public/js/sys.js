$(document).ready( function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    $('#btnMenuPrincipal').click( function (e) {
        e.preventDefault();
        $('#sidebar').toggleClass('active');
    });
    
    $('#btn-guardar').click(function(e){
        e.preventDefault();
        try {
            $('#dialogoCuerpo form').trigger('submit');
        }
        catch(ex){}
    });
});

$(document).on('submit', 'form.xhr', function(e) {
    e.preventDefault();
    beforeAjax();
    var datos = new FormData(this);
    var destino = 'contenido';
    var url = $(this).attr('action');
    var metodo = $(this).attr('method') ? $(this).attr('method') : 'post';
    var formulario=$(this);
    
    if ( $(this).attr('target') ) {
        destino = $(this).attr('target');
    }
    $('#'+destino).html('<span class="mdi mdi-loading mdi-spin"></span>');
    formulario.find('button,input[type="submit"]').prop('disabled', true);
    
    $.ajax(
        {
            url     : url,
            method  : metodo,
            data    : datos,
            processData : false,
            contentType : false
        }
    )
    .done( function (html) {
        $('#' + destino).html(html);
        afterAjax();
    })
    .fail( function (jqXHR, statusCode) {
        $('#'+destino).html('<strong class="text-error">ERROR: ' 
        + jqXHR.statusText + '</strong>');
    })
    .always(function(){
        formulario.find('button,input[type="submit"]').prop('disabled', false);
    });
})

$(document).on('click', 'a.xhr', function(e) {
    e.preventDefault();
    beforeAjax();
    var __target = $('#contenido');
    
    if ( $(this).attr('target') ) {
        __target = $('#' + $(this).attr('target'));
    }
    
    
    __target.html('<span class="mdi mdi-loading mdi-spin"></span>');
    $.get($(this).attr('href'), function(data) {
        __target.html(data);
        afterAjax();
    })
    .fail( function (jqXHR, textStatus, errorThrown) {
        __target.html(jqXHR.statusText);

        /*
        readyState
        responseXML and/or responseText when the underlying request responded with xml and/or text, respectively
        status
        statusText
        abort( [ statusText ] )
        getAllResponseHeaders() as a string
        getResponseHeader( name )
        overrideMimeType( mimeType )
        setRequestHeader( name, value )
        statusCode( callbacksByStatusCode )
         */
    });
});

function beforeAjax(){
    $('[data-toggle="tooltip"]').tooltip('hide');
}

function afterAjax() {
    $('[data-toggle="tooltip"]').tooltip('dispose');
    $('[data-toggle="tooltip"]').tooltip();
}

function hacerPost(url, params, destino) {
    var dest = 'contenido';
    if ( destino ) {
        dest = destino;
    }
    
    $.post(url, params, function(html){
        $('#'+dest).html(html);
        afterAjax();
    })
    .fail(function(jqXHR, statusMessage){
        $('#'+dest).html('ERROR: ' + jqXHR.statusText);
    });
}

function hacerGet(url, destino) {
    var dest = 'contenido';
    if ( destino ) {
        dest = destino;
    }
    
    $('#'+dest).html('<span class="mdi mdi-loading mdi-spin"></span>');
    
    $.get(url, function(html){
        $('#'+dest).html(html);
        afterAjax();
    })
    .fail(function(jqXHR, statusMessage){
        $('#'+dest).html('ERROR: ' + jqXHR.statusText);
    });
}