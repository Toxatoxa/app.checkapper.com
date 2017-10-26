<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Facebook meta -->
    <meta property="og:title" content="{{$filmsHistory->seo_title}}">
    <meta property="og:description" content="{{$filmsHistory->seo_description}}">
    <meta property="og:type" content="video.movie">
    <meta property="og:url" content="{{Request::url()}}">
    <meta property="og:site_name" content="{{config('app.name')}}">
    <meta property="og:image" content="{{$filmsHistory->film->thumb->medium}}">
    <meta property="og:image:width" content="240">
    <meta property="og:image:height" content="360">
    <meta property="fb:app_id" content="379654049033142">
    <!-- End Facebook meta -->

    <!-- Twitter meta -->
    <meta name="twitter:title" content="{{$filmsHistory->seo_title}}"/>
    <meta name="twitter:description" content="{{$filmsHistory->seo_description}}"/>
    <meta name="twitter:site" content="@iTunesChecker"/>
    <meta name="twitter:domain" content="{{Request::url()}}"/>
    <meta name="twitter:card" content="summary_large_image"/>
    <meta name="twitter:image" content="{{$filmsHistory->film->thumb->medium}}"/>
    <!-- End Twitter meta -->

    <meta name="description" content="{{$filmsHistory->seo_description}}">

    <title>{{$filmsHistory->seo_title}}</title>
</head>
<body>
<div>
    <script>
        window.location = "{{ config('app.url').'/update/'.$hash.'?ct='.$campaign }}";
    </script>
</div>
</body>
</html>