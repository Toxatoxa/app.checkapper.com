{{$filmHistory->film->track_name}}
@if($filmHistory->decreased_hd_rent)
-{{$filmHistory->getDiscount('track_hd_rental_price')}}% HD Rent Price Drop: ${{$filmHistory->film->track_hd_rental_price}} (was: ${{$filmHistory->track_hd_rental_price_last}})
@elseif($filmHistory->decreased_sd_rent)
-{{$filmHistory->getDiscount('track_rental_price')}}% SD Rent Price Drop: ${{$filmHistory->film->track_rental_price}} (was: ${{$filmHistory->track_rental_price_last}})
@elseif($filmHistory->available_hd_rent)
HD Rent Availible: ${{$filmHistory->film->track_hd_rental_price}}
@elseif($filmHistory->available_sd_rent)
SD Rent Availible: ${{$filmHistory->film->track_rental_price}}
@elseif($filmHistory->decreased_hd)
-{{$filmHistory->getDiscount('track_hd_price')}}% HD Price Drop: ${{$filmHistory->film->track_hd_price}} (was: ${{$filmHistory->track_hd_price_last}})
@elseif($filmHistory->decreased_sd)
-{{$filmHistory->getDiscount('track_price')}}% SD Price Drop: ${{$filmHistory->film->track_price}} (was: ${{$filmHistory->track_sd_price_last}})
@elseif($filmHistory->available_hd)
HD Availible: ${{$filmHistory->film->track_hd_price}}
@elseif($filmHistory->available_sd)
SD Availible: ${{$filmHistory->film->track_price}}
@endif
{{$filmHistory->film->affiliate_link}}







