@if($filmHistory->decreased_hd_rent)
-{{$filmHistory->getDiscount('track_hd_rental_price')}}% Rent Price Drop: ${{$filmHistory->film->track_hd_rental_price}} {{ ($filmHistory->track_hd_rental_price_last) ? '(was: $'.$filmHistory->track_hd_rental_price_last.')' : ''  }} [HD version]
@elseif($filmHistory->decreased_sd_rent)
-{{$filmHistory->getDiscount('track_rental_price')}}% Rent Price Drop: ${{$filmHistory->film->track_rental_price}} {{ ($filmHistory->track_rental_price_last) ? '(was: $'.$filmHistory->track_rental_price_last.')' : ''}} [SD version]
@endif
@if($filmHistory->available_hd_rent)
Rent Availible: ${{$filmHistory->film->track_hd_rental_price}} [HD version]
@elseif($filmHistory->available_sd_rent)
Rent Availible: ${{$filmHistory->film->track_rental_price}} [SD version]
@endif
@if($filmHistory->decreased_hd)
-{{$filmHistory->getDiscount('track_hd_price')}}% Price Drop: ${{$filmHistory->film->track_hd_price}} {{ ($filmHistory->track_hd_price_last) ? '(was: $'.$filmHistory->track_hd_price_last.')' : ''}} [HD version]
@elseif($filmHistory->decreased_sd)
-{{$filmHistory->getDiscount('track_price')}}% Price Drop: ${{$filmHistory->film->track_price}} {{ ($filmHistory->track_sd_price_last) ? '(was: $'.$filmHistory->track_sd_price_last.')' : ''}} [SD version]
@endif
@if($filmHistory->available_hd)
Availible: ${{$filmHistory->film->track_hd_price}} [HD version]
@elseif($filmHistory->available_sd)
Availible: ${{$filmHistory->film->track_price}} [SD version]
@endif

Film: {{$filmHistory->film->track_name}}

Description: {!! $filmHistory->film->long_description !!}