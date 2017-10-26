Название фильма: {{$filmHistory->film->track_name}}

@if($filmHistory->available_hd)
    Доступна HD врсия: {{$filmHistory->film->track_hd_price}} руб.
@elseif($filmHistory->available_sd)
    Доступна SD врсия: {{$filmHistory->film->track_price}} руб.
@endif

@if($filmHistory->decreased_hd)
    HD версия упала в цене: {{$filmHistory->film->track_hd_price}} руб. (Предыдущая: {{$filmHistory->track_hd_price_last}} руб.)
@elseif($filmHistory->decreased_sd)
    SD версия упала в цене: {{$filmHistory->film->track_price}} руб. (Предыдущая: {{$filmHistory->track_sd_price_last}} руб.)
@endif

@if($filmHistory->available_hd_rent)
    Доступна аренда HD версии: {{$filmHistory->film->track_hd_rental_price}} руб.
@elseif($filmHistory->available_sd_rent)
    Доступна аренда SD версии: {{$filmHistory->film->track_rental_price}} руб.
@endif

@if($filmHistory->decreased_hd_rent)
    Упала цена аренды HD версии: {{$filmHistory->film->track_hd_rental_price}} руб. (Предыдущая: {{$filmHistory->track_hd_rental_price_last}} руб.)
@elseif($filmHistory->decreased_sd_rent)
    Упала цена аренды SD версии: {{$filmHistory->film->track_rental_price}} руб. (Предыдущая: {{$filmHistory->track_rental_price_last}} руб.)
@endif

Описание: {!! $filmHistory->film->long_description !!}

Ссылка на скачивание фильма: {{ $filmHistory->film->track_view_url }}