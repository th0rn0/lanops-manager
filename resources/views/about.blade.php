@extends('layouts.default')

@section('page_title', 'About Us - Lans in South Yorkshire')

@section('content')
      
<div class="container">

  <div class="page-header">
    <h1>About Us</h1> 
  </div>
  <div class="page-header">
    <h3>Who We Are</h3> 
  </div>
  {!! Settings::getAboutMain() !!}
  <div class="page-header">
    <h3>Our Aim</h3> 
  </div>
  {!! Settings::getAboutOurAim() !!}
  <div class="page-header">
    <h3>The Who's Who</h3> 
  </div>
  {!! Settings::getAboutWho() !!}

</div>

@endsection