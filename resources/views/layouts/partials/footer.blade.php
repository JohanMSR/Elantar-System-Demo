{{-- pied de pagina de la app --}}
<style>
.right-border {
    border-right: 2px solid rgba(0, 0, 0, 0.7); /* Adjust the width and color as needed */
    padding-right: 10px; /* Optional: Adds space between the image and the border */
    width: 80px !important;
}
.text-footer{
    display: flex;
    align-items: bottom;
    justify-content: flex-end;
    height: 100%;
    position: absolute;
    bottom: 0;
}
</style>
<footer class="footer">
    <div class="styles-footer">
        <div class="d-flex justify-content-center">
            <div style="height: 80px" class="d-flex flex-row align-items-center justify-content-center">
                <img class="right-border" src="{{ asset('img/footer.png') }}" alt="img footer">
                <div class="text-footer position-relative">
                    <p class="ms-2 mb-0 mt-3">Powered by <br> {{ env('APP_NAME_COMPANY') }} © {{ date('Y') }}</p>
                </div>                
            </div>
        </div>
    </div>
</footer>
