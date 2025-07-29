<div class="container">
    {{--<h3>{{ $title }}</h3>--}}
    <form id="form_document_app" enctype="multipart/form-data">
        <input type="hidden" id="app_document_id" name="co_aplicacion" value="">
        
        <div class="mb-3">
            <label for="tx_url_img_compago1" class="form-label">Comprobante de Ingresos 1</label>
            <input type="file" class="form-control" id="tx_url_img_compago1" name="tx_url_img_compago1" accept=".jpg, .jpeg, .png, .gif, .pdf">
            <label id="label_tx_url_img_compago1" class="file-label small-label"></label>
        </div>
        
        <div class="mb-3">
            <label for="tx_url_img_compago2" class="form-label">Comprobante de Ingresos 2</label>
            <input type="file" class="form-control" id="tx_url_img_compago2" name="tx_url_img_compago2" accept=".jpg, .jpeg, .png, .gif, .pdf">
            <label id="label_tx_url_img_compago2" class="file-label small-label"></label>
        </div>
        
        <div class="mb-3">
            <label for="tx_url_img_compago3" class="form-label">Comprobante de Ingresos 3</label>
            <input type="file" class="form-control" id="tx_url_img_compago3" name="tx_url_img_compago3" accept=".jpg, .jpeg, .png, .gif, .pdf">
            <label id="label_tx_url_img_compago3" class="file-label small-label"></label>
        </div>
        
        <div class="mb-3">
            <label for="tx_url_img_checknull" class="form-label">Cheque Anulado</label>
            <input type="file" class="form-control" id="tx_url_img_checknull" name="tx_url_img_checknull" accept=".jpg, .jpeg, .png, .gif, .pdf">
            <label id="label_tx_url_img_checknull" class="file-label small-label"></label>
        </div>
        
        <div class="mb-3">
            <label for="tx_url_img_compropiedad" class="form-label">Comprobante de Propiedad</label>
            <input type="file" class="form-control" id="tx_url_img_compropiedad" name="tx_url_img_compropiedad" accept=".jpg, .jpeg, .png, .gif, .pdf">
            <label id="label_tx_url_img_compropiedad" class="file-label small-label"></label>
        </div>
        
        <div class="mb-3">
            <label for="tx_url_img_declaraimpuesto" class="form-label">Declaración de Impuestos</label>
            <input type="file" class="form-control" id="tx_url_img_declaraimpuesto" name="tx_url_img_declaraimpuesto" accept=".jpg, .jpeg, .png, .gif, .pdf">
            <label id="label_tx_url_img_declaraimpuesto" class="file-label small-label"></label>
        </div>
        <div class="mb-3">
            <label for="tx_url_img_otro" class="form-label">Otros Documentos</label>
            <input type="file" class="form-control" id="tx_url_img_otro" name="tx_url_img_otro" accept=".jpg, .jpeg, .png, .gif, .pdf">
            <label id="label_tx_url_img_otro" class="file-label small-label"></label>
        </div>                
        <div class="m-1 d-flex justify-content-center">
            <a id="btn_document_verify" class="btn btn-info" href="">@lang('translation.text_upload_document')</a>
        </div>
    </form>
</div>