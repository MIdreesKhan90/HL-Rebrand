<!-- Accordion items -->
@if(count($faqs) > 0)
    @foreach($faqs as $faq )
        <div class="card">
            <div id="heading{{$loop->index}}" class="card-header border-0">
                <h2 class="mb-0">
                    <button type="button" data-toggle="collapse" data-target="#collapse{{$loop->index}}" aria-expanded="true"
                            aria-controls="collapse{{$loop->index}}"
                            class="btn btn-link text-dark font-weight-bold text-uppercase collapsible-link">{{$faq->question}}</button>
                </h2>
            </div>
            <div id="collapse{{$loop->index}}" aria-labelledby="heading{{$loop->index}}" data-parent="#accordionExample" class="collapse @if($loop->first) show @endif">
                <div class="card-body">
                    {!! $faq->answer !!}
                </div>
            </div>
        </div>
    @endforeach
@else
    <div class="card">
        <h1>No result!!!</h1>
    </div>
@endif

<!-- End -->
