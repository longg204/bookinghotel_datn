@extends('layouts.app')
@section('content')
    <main>

        <section class="swiper-container js-swiper-slider swiper-number-pagination slideshow"
            data-settings='{
        "autoplay": {
          "delay": 5000
        },
        "slidesPerView": 1,
        "effect": "fade",
        "loop": true
      }'>
            <div class="swiper-wrapper">
                @foreach ($slides as $slide)
                    <div class="swiper-slide">
                        <div class="overflow-hidden position-relative h-100">
                            <div class="slideshow-character position-absolute bottom-0 pos_right-center">
                                <img loading="lazy" src="{{ asset('uploads/slide') }}/{{ $slide->image }}" width="842"
                                    height="733" alt="Woman Fashion 1"
                                    class="slideshow-character__img animate animate_fade animate_btt animate_delay-9 w-auto h-auto" />
                                <div class="character_markup type2">
                                    <p
                                        class="text-uppercase font-sofia mark-grey-color animate animate_fade animate_btt animate_delay-10 mb-0">
                                        {{ $slide->tagline }}</p>
                                </div>
                            </div>
                            <div class="slideshow-text container position-absolute start-50 top-50 translate-middle">
                                <h6
                                    class="text_dash text-uppercase fs-base fw-medium animate animate_fade animate_btt animate_delay-3">
                                    New Arrivals</h6>
                                <h2 class="h1 fw-normal mb-0 animate animate_fade animate_btt animate_delay-5">
                                    {{ $slide->title }}</h2>
                                <h2 class="h1 fw-bold animate animate_fade animate_btt animate_delay-5">
                                    {{ $slide->tagline }}</h2>
                                <a href="/{{ $slide->link }}"
                                    class="btn-link btn-link_lg default-underline fw-medium animate animate_fade animate_btt animate_delay-7">Room
                                    Now</a>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>


            <div class="container">
                <div
                    class="slideshow-pagination slideshow-number-pagination d-flex align-items-center position-absolute bottom-0 mb-5">
                </div>
            </div>
        </section>
        <div class="container mw-1620 bg-white border-radius-10">
            <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>
            <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>

            <section class="hot-deals container">
                <h2 class="section-title text-center mb-3 pb-xl-3 mb-xl-4">Hot Deals</h2>
                <div class="row">
                    <div
                        class="col-md-6 col-lg-4 col-xl-20per d-flex align-items-center flex-column justify-content-center py-4 align-items-md-start">
                        <h2>Spring Sale</h2>
                        <h2 class="fw-bold">Up to 30% Off</h2>

                        <div class="position-relative d-flex align-items-center text-center pt-xxl-4 js-countdown mb-3"
                            data-date="18-3-2024" data-time="06:50">
                            <div class="day countdown-unit"><span class="countdown-num d-block"></span> <span
                                    class="countdown-word text-uppercase text-secondary">Days</span></div>

                            <div class="hour countdown-unit"><span class="countdown-num d-block"></span> <span
                                    class="countdown-word text-uppercase text-secondary">Hours</span></div>

                            <div class="min countdown-unit"><span class="countdown-num d-block"></span> <span
                                    class="countdown-word text-uppercase text-secondary">Mins</span></div>

                            <div class="sec countdown-unit"><span class="countdown-num d-block"></span> <span
                                    class="countdown-word text-uppercase text-secondary">Sec</span></div>
                        </div>

                        <a href="#" class="btn-link default-underline text-uppercase fw-medium mt-3">View All</a>
                    </div>
                    <div class="col-md-6 col-lg-8 col-xl-80per">
                        <div class="position-relative">
                            <div class="swiper-container js-swiper-slider" data-settings='{
                                        "autoplay": {
                                            "delay": 2000
                                        },
                                        "slidesPerView": 4,
                                        "slidesPerGroup": 4,
                                        "effect": "none",
                                        "loop": false,
                                        "breakpoints": {
                                            "320": {
                                            "slidesPerView": 2,
                                            "slidesPerGroup": 2,
                                            "spaceBetween": 14
                                            },
                                            "768": {
                                            "slidesPerView": 2,
                                            "slidesPerGroup": 3,
                                            "spaceBetween": 24
                                            },
                                            "992": {
                                            "slidesPerView": 3,
                                            "slidesPerGroup": 1,
                                            "spaceBetween": 30,
                                            "pagination": false
                                            },
                                            "1200": {
                                            "slidesPerView": 4,
                                            "slidesPerGroup": 1,
                                            "spaceBetween": 30,
                                            "pagination": false
                                            }
                                        }
                                        }'>

{{--                                ------------------------hotdeals------------------------}}
                                <div class="swiper-wrapper">
                                    @foreach($hotdeals as $hotdeal)
                                        <div class="swiper-slide product-card product-card_style3">
                                            <div class="pc__img-wrapper">
                                                <a href="details.html"> <img loading="lazy"
                                                                             src="{{ asset("uploads/room") }}/{{$hotdeal->image}}"
                                                                             width="258" height="313" alt="Cropped Faux leather Jacket"
                                                                             class="pc__img" /> <img loading="lazy"
                                                                                                     src="{{ asset("uploads/room") }}/{{explode(',',$hotdeal->images )[0]}}"
                                                                                                     width="258" height="313" alt="Cropped Faux leather Jacket"
                                                                                                     class="pc__img pc__img-second" /> </a>
                                            </div>

                                            <div class="pc__info position-relative">
                                                <h6 class="pc__title"><a href="details.html">{{$hotdeal->name}}</a>
                                                </h6>
                                                <div class="product-card__price d-flex">
                                                    <span class="money price text-secondary">{{$hotdeal->regular_price}}</span>
                                                </div>

                                                <div
                                                    class="anim_appear-bottom position-absolute bottom-0 start-0 d-none d-sm-flex align-items-center bg-body">
                                                    @if(Cart::instance("cart")->content()->where("id", $hotdeal->id)->count() > 0)
                                                        <a href="{{ route("cart.index") }}"
                                                            class="btn-link btn-link_lg me-4 text-uppercase fw-medium"
                                                            data-aside="cartDrawer" title="Go to cart">Go to cart</a>
                                                    @else
                                                        <form name="addtocart-form" method="post" action="{{ route("cart.add") }}">
                                                            @csrf
                                                            <input type="hidden" name="id" value="{{ $hotdeal->id }}"/>
                                                            <input type="hidden" name="quantity" value="1"/>
                                                            <input type="hidden" name="name" value="{{ $hotdeal->name }}"/>
                                                            <input type="hidden" name="price" value="{{ $hotdeal->sale_price = "" ? $hotdeal->regular_price : $hotdeal->sale_price }}"/>
                                                            <button
                                                                class="btn-link btn-link_lg me-4 text-uppercase fw-medium"
                                                                data-aside="cartDrawer" title="Add To Cart">Add To Cart</button>
                                                        </form>
                                                    @endif
                                                    <button
                                                        class="btn-link btn-link_lg me-4 text-uppercase fw-medium js-quick-view"
                                                        data-bs-toggle="modal" data-bs-target="#quickView"
                                                        title="Quick view">

                                                        <span class="d-block d-xxl-none"><svg width="18" height="18"
                                                                                              viewBox="0 0 18 18" fill="none"
                                                                                              xmlns="http://www.w3.org/2000/svg">
                                                            <use href="#icon_view" />
                                                        </svg></span>
                                                    </button>
{{--                                                    //////////////////////////////////////////--}}
                                                        @if(Cart::instance("wishlist")->content()->where("id", $hotdeal->id)->count() > 0)
                                                            <a href="{{route('wishlist.index')}}" class="pc__btn-wl bg-transparent border-0 js-add-wishlist"
                                                                    title="Add To Wishlist">Go to wishlist
                                                            </a>
                                                        @else
                                                            <form name="addtowishlist-form" method="post" action="{{ route("wishlist.add") }}">
                                                                @csrf
                                                                <input type="hidden" name="id" value="{{ $hotdeal->id }}"/>
                                                                <input type="hidden" name="quantity" value="1"/>
                                                                <input type="hidden" name="name" value="{{ $hotdeal->name }}"/>
                                                                <input type="hidden" name="price" value="{{ $hotdeal->sale_price = "" ? $hotdeal->regular_price : $hotdeal->sale_price }}"/>
                                                                <button class="pc__btn-wl bg-transparent border-0 js-add-wishlist"
                                                                        title="Add To Wishlist">
                                                                    <svg width="16" height="16" viewBox="0 0 20 20"
                                                                         fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <use href="#icon_heart" />
                                                                    </svg>
                                                                </button>
                                                            </form>
                                                        @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach


                                </div>
                                <!-- /.swiper-wrapper -->
                            </div>
                            <!-- /.swiper-container js-swiper-slider -->
                        </div>
                        <!-- /.position-relative -->
                    </div>
                </div>
            </section>

            <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>

{{--            <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>--}}

            <section class="products-grid container">
                <h2 class="section-title text-center mb-3 pb-xl-3 mb-xl-4">Featured Rooms</h2>

                <div class="row">
                    @foreach ($rooms as $room)
                         <div class="col-6 col-md-4 col-lg-3">
                             <div class="product-card-wrapper">
                                 <div class="product-card mb-3 mb-md-4 mb-xxl-5">
                                     <div class="pc__img-wrapper">
                                         <div class="swiper-container background-img js-swiper-slider" data-settings='{"resizeObserver": true}'>
                                             <div class="swiper-wrapper">
                                                 <div class="swiper-slide">
                                                     <a href="{{ route("room.detail", $room->slug) }}">
                                                     <img loading="lazy" src="{{ asset("uploads/room") }}/{{$room->image}}" width="330" height="400" alt="{{ $room->name }}" class="pc__img">
                                                     </a>
                                                 </div>
                                                 @if($room->images)
                                                 @foreach(explode(",", $room->images) as $reviewImg)
                                                 <div class="swiper-slide">
                                                     <a href="{{ route("room.detail", $room->slug) }}">
                                                     <img loading="lazy" src="{{ asset("uploads/room") }}/{{$reviewImg}}" width="330" height="400" alt="{{ $room->name }}" class="pc__img">
                                                     </a>
                                                 </div>
                                                 @endforeach
                                                 @endif
                                             </div>
                                             <span class="pc__img-prev">
                                            <svg width="7" height="11" viewBox="0 0 7 11" xmlns="http://www.w3.org/2000/svg">
                                                <use href="#icon_prev_sm"/>
                                            </svg>
                                        </span>
                                             <span class="pc__img-next">
                                            <svg width="7" height="11" viewBox="0 0 7 11" xmlns="http://www.w3.org/2000/svg">
                                                <use href="#icon_next_sm"/>
                                            </svg>
                                        </span>
                                         </div>
                                         @if(Cart::instance("cart")->content()->where("id", $room->id)->count() > 0)
                                         <a href="{{ route("cart.index") }}" class="pc__atc btn anim_appear-bottom btn position-absolute border-0 text-uppercase fw-medium ">
                                         Go to cart
                                         </a>
                                         @else
                                         <form name="addtocart-form" method="post" action="{{ route("cart.add") }}">
                                         @csrf
                                         <input type="hidden" name="id" value="{{ $room->id }}"/>
                                         <input type="hidden" name="quantity" value="1"/>
                                         <input type="hidden" name="name" value="{{ $room->name }}"/>
                                         <input type="hidden" name="price" value="{{ $room->sale_price = "" ? $room->regular_price : $room->sale_price }}"/>
                                         <button type="submit" class="pc__atc btn anim_appear-bottom btn position-absolute border-0 text-uppercase fw-medium" data-aside="cartDrawer" title="Add To Cart">
                                             Add To Cart
                                         </button>
                                         </form>
                                         @endif

                                     </div>
                                     <div class="pc__info position-relative">
                                         <p class="pc__category">{{ $room->category->name }}</p>
                                         <h6 class="pc__title">
                                             <a href="{{ route("room.detail", $room->slug) }}">{{ $room->name }}</a>
                                         </h6>
                                         <div class="r-card__price d-flex">
                                        <span class="money price">
                                            @if($room->sale_price)
                                                <s>${{ $room->regular_price }}</s> ${{ $room->sale_price }}
                                            @else
                                                {{ $room->regular_price }}
                                            @endif
                                        </span>
                                         </div>
                                         <div class="product-card__review d-flex align-items-center">
                                             <div class="reviews-group d-flex">
                                                 <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
                                                     <use href="#icon_star"/>
                                                 </svg>
                                                 <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
                                                     <use href="#icon_star"/>
                                                 </svg>
                                                 <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
                                                     <use href="#icon_star"/>
                                                 </svg>
                                                 <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
                                                     <use href="#icon_star"/>
                                                 </svg>
                                                 <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
                                                     <use href="#icon_star"/>
                                                 </svg>
                                             </div>
                                             <span class="reviews-note text-lowercase text-secondary ms-1">8k+ reviews</span>
                                         </div>

                                         @if(Cart::instance("wishlist")->content()->where("id", $room->id)->count() > 0)
                                         <button type="submit" class="pc__btn-wl position-absolute top-0 end-0 bg-transparent border-0 js-add-wishlist filled-heart" title="Add To Wishlist">
                                             <svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                 <use href="#icon_heart"/>
                                             </svg>
                                         </button>
                                         @else
                                         <form name="addtowishlist-form" method="post" action="{{ route("wishlist.add") }}">
                                         @csrf
                                         <input type="hidden" name="id" value="{{ $room->id }}"/>
                                         <input type="hidden" name="quantity" value="1"/>
                                         <input type="hidden" name="name" value="{{ $room->name }}"/>
                                         <input type="hidden" name="price" value="{{ $room->sale_price = "" ? $room->regular_price : $room->sale_price }}"/>
                                         <button type="submit" class="pc__btn-wl position-absolute top-0 end-0 bg-transparent border-0 js-add-wishlist" title="Add To Wishlist">
                                             <svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                 <use href="#icon_heart"/>
                                             </svg>
                                         </button>
                                         </form>
                                         @endif
                                     </div>
                                 </div>
                             </div>
                        </div>
                    @endforeach
                </div>
                <!-- /.row -->

                <div class="text-center mt-2">
                    <a class="btn-link btn-link_lg default-underline text-uppercase fw-medium" href="{{ route("room.index") }}">Load More</a>
                </div>
            </section>
        </div>
        <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>
    </main>
@endsection

@push("scripts")
    <script>
        $(function () {
            $('.qty-control__increase').on("click", function () {
                $(this).closest("form").submit();
            })

            $('.qty-control__reduce').on("click", function () {
                $(this).closest("form").submit();
            })

            $('.remove-cart').on("click", function () {
                $(this).closest("form").submit();
            })
        })
    </script>
@endpush
