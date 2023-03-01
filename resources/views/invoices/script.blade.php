<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript" src="{{ asset('custom/js/html2pdf.bundle.min.js') }}"></script>


@auth('web')
    <?php $url = route('invoices.show',[$currentWorkspace->slug,$invoice->id]); ?>

@endauth

@auth('client')
    <?php $url = route('client.invoices.show',[$currentWorkspace->slug,$invoice->id]); ?>
@endauth

@if(!\Auth::check())
  <?php $urlnonauth = route('pay.invoice',[$currentWorkspace->slug,\Illuminate\Support\Facades\Crypt::encrypt($invoice->id)]);?>
@endif

<script>
    'use strict';

    function closeScript() {

            @if( \Auth::guard('web')->check()|| \Auth::guard('client')->check())
   
            setTimeout(function () {
                window.location.href = '{{ $url }}';
            }, 1000);

        @else
            setTimeout(function () {
                window.location.href = '{{ $urlnonauth }}';
            }, 1000);

        @endif

    }

    $(window).on('load', function () {
        var element = document.getElementById('boxes');
        var opt = {
            filename: '{{App\Models\Utility::invoiceNumberFormat($invoice->invoice_id) }}',
            image: {type: 'jpeg', quality: 1},
            html2canvas: {scale: 4, dpi: 72, letterRendering: true},
            jsPDF: {unit: 'in', format: 'A4'}
        };
        html2pdf().set(opt).from(element).save().then(closeScript);
    });

</script>
