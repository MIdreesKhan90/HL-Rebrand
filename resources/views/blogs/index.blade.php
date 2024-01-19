@extends('layouts.app',['title' => 'Blogs','active' => 'blogs'])

@section('content')
    <!-- section our prices  -->
    <section class="section-pricing section-blog">
        <h1 class="text-center bg-light fw-bolder">Blogs
        </h1>
        <div class="container section p-32 -mt-90 b-radius-16 bg-white mb-64">
            <div class="row">
            @foreach($blogs as $blog)
                    <div class="col-md-4 mb-64">
                        <div class="card b-radius-16 bg-white">
                            <a href="{{ route('blog.detail',$blog->slug) }}">
                                <img src="{{Storage::url($blog->featured_image)}}" class="card-img-top b-radius-16" alt="{{$blog->title}}">
                                <div class="card-body p-32">
                                    <p class="desc color-light mb-3">{{date('M d, Y',strtotime($blog->published_date))}}</p>
                                    <h5 class="card-title fw-600 mb-3">{{$blog->title}}</h5>
                                    <p class="card-text">{!! Str::limit(strip_tags($blog->content), 45, ' ...') !!}</p>
                                </div>
                            </a>
                        </div>
                    </div>
            @endforeach

            </div>
            {{ $blogs->links() }}
        </div>



    </section>
@endsection
