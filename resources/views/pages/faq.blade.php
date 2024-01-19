@extends('layouts.app',['title' => 'FAQ', 'active' => 'faq'])

@section('content')
    <!-- section hero -->
    <section class="faq-hero bg-light">
        <div class="container">
            <div class="row">
                <div class="col-md-7 mx-auto ">
                    <div class="content text-center">

                        <h1 class="fw-600">Frequently asked questions</h1>
                        <form id="faq-search" class="form-search-hotel" action="{{route('faq.search')}}">
                            <i class="fa fal fa-search"></i>
                            <input id="question" name="question" class="form-control" type="text" placeholder="Search Questions">

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>


    <section class="faq-accordian">
        <div class="container">
            <div class="bg-white">  <!-- Accordion -->
                <div id="accordionExample" class="accordion  ">

                    @include('components.faq-accordians')

                </div><!-- End -->
            </div>
        </div>
    </section>
@endsection

@push('custom-script')
    <script type="text/javascript">

        $(document).ready(function() {
            var timer;
            $('#question').on('keyup', function() {
                clearTimeout(timer);
                var searchTerm = $(this).val();
                // if (searchTerm.length >= 3) {
                    timer = setTimeout(function() {
                        // var data = $('#faq-search').serializeArray();
                        var formURL = $('#faq-search').attr("action");
                        $.ajax({
                            url: formURL,
                            type: 'GET',
                            data: { question: searchTerm },
                            success: function(data) {
                                $('#accordionExample').html(data);
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.log('Error: ' + errorThrown);
                            }
                        });
                    }, 500); // Set a delay of 500ms before making the AJAX request
                // } else {
                //     $('#accordionExample').empty();
                // }
            });
        });
    </script>

@endpush
