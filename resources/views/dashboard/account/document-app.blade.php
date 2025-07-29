<div class="container">
    <h3>Probando el backend</h3>
    <div>
        @if($message)
            El mensaje: {{$message}}
        @endif`
    </div>    
    <form id="form_document_app" method="POST" 
    action={{route('document.store')}}
    enctype="multipart/form-data"
    >
        @csrf
        <input type="text" id="app_document_id" name="co_aplicacion" value="{{$co_aplicacion}}">
        
        <div class="mb-3">
            <label for="tx_url_img_compago1" class="form-label">Comporbante de Ingresos 1</label>
            <input type="file" class="form-control" id="tx_url_img_compago1" name="tx_url_img_compago1" accept=".jpg, .jpeg, .png, .gif, .pdf">
        </div>
        
        
        <div class="mb-3">
            <label for="tx_url_img_compago2" class="form-label">Comporbante de Ingresos 2</label>
            <input type="file" class="form-control" id="tx_url_img_compago2" name="tx_url_img_compago2" accept=".jpg, .jpeg, .png, .gif, .pdf">
        </div>
        
        <div class="mb-3">
            <label for="tx_url_img_compago3" class="form-label">Comporbante de Ingresos 3</label>
            <input type="file" class="form-control" id="tx_url_img_compago3" name="tx_url_img_compago3" accept=".jpg, .jpeg, .png, .gif, .pdf">
        </div>
        {{--
        <div class="mb-3">
            <label for="tx_url_img_checknull" class="form-label">Cheque Anulado</label>
            <input type="file" class="form-control" id="tx_url_img_checknull" name="tx_url_img_checknull" accept=".jpg, .jpeg, .png, .gif, .pdf">
        </div>
        
        <div class="mb-3">
            <label for="tx_url_img_compropiedad" class="form-label">Comporbante de Propiedad</label>
            <input type="file" class="form-control" id="tx_url_img_compropiedad" name="tx_url_img_compropiedad" accept=".jpg, .jpeg, .png, .gif, .pdf">
        </div>
        
        <div class="mb-3">
            <label for="tx_url_img_declaraimpuesto" class="form-label">Declaracion de Impuestos</label>
            <input type="file" class="form-control" id="tx_url_img_declaraimpuesto" name="tx_url_img_declaraimpuesto" accept=".jpg, .jpeg, .png, .gif, .pdf">
        </div>        
        --}}
        <button type="submit" class="btn btn-primary">Enviar</button>
    </form>
</div>