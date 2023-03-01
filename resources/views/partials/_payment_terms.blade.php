<style>
    .modal-backdrop {
        z-index: -1;
    }
</style>
@if(isset($client))
    @if(!isset($client->payment_policy))
        <div id="app" class="container py-2">
            <div>
                <p class="lead"></p>
            </div>
            <div class="py-2">
                <div class="modal fade in" id="policyModal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                @php($terms = App\Models\TermsAndCondition::where('slug','client_payment_policy')->first())
                                <h5 class="modal-title">{{$terms->name}}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>{{$terms->info}}</p>
                            </div>
                            <div class="modal-footer">
                                <form method="POST" action="{{route('client.update.payment.policy')}}">
                                    @csrf
                                    <input type="hidden" name="user_id" value="@if(isset($client->id)){{($client->id)}}@endif">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Continue</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endif
