<div class="col-md-6 @if(!isset($full)) col-lg-4 @endif margin-30px-bottom">
    <a href="{{route('packages.view', $package->slug)}}"
       class="card  shadow radius-4 bg-img box-hover cover-background border-0 p-4 h-100"
       data-background="{{$package->image_url}}">
        <div class="mt-auto position-relative z-index-9">
            <h5 class="text-white">{{$package->title}}</h5>
            <hr class="border-color-light-white">
            {{--                            <p>Join the fun as Nigeria’s hit girl-squad come face-to-face with their male counterparts in Dubai for the ultimate face-off challenge.</p>--}}
            <div class="position-relative z-index-9 text-white">
                <div class="spaced aligned">
                    <div><span class="ti-location-pin text-primary"></span> {{$package->location}}
                    </div>
                    <span>₦{{$package->amount}}/person</span>
                </div>
            </div>
        </div>
    </a>
</div>
