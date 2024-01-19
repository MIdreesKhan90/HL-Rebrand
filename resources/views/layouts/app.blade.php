<x-header :title="$title ?: 'Test'" :active="$active ?: 'test'" />
@yield('content')
@yield('script')
@php
    if (request()->query('service')){
       $bannerText = 'Looking for missing items?';
       $link = '/contact';
       $linkTitle = 'Ask our team';
   }elseif (request()->segment(1) == 'faq'){
      $bannerText = "Couldn't find helpful answer?";
       $link = '/contact';
       $linkTitle = 'Ask our team';
   }else{
       $bannerText = 'GET 20% OFF ON YOUR FIRST ORDER!';
       $link = '/booking';
       $linkTitle = 'Book Now';
   }
@endphp
@if(request()->segment(1) != 'booking')
    <x-footer-banner :bannerText="$bannerText" :link="$link" :linkTitle="$linkTitle" />
@endif

<x-footer/>
