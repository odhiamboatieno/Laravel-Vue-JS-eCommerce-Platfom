@extends('front.master.master')

@section('title')
 {{ $shop_info->shop_name }} | Checkout
@endsection

@section('meta')
    <!-- Twitter Card  -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:site" content="@my_twitter">
    <meta name="twitter:creator" content="@my_twitter">

    <!-- Open Graph  -->
    <meta property="og:title" content=" {{ $shop_info->shop_name }} | Checkout" />
    <meta property="og:type" content="Ecommerce Site" />
    <meta property="og:url" content="{{ route('checkout.get') }}" />
    <meta property="og:image" content="{{ url('images/setting/seo/'.$seo_info->meta_image) }}" />
    <meta property="og:description" content="{{ $seo_info->description }}" />

@endsection

@push('style')

<style>

</style>

@endpush

@section('content')

<section class="checkout">
    <div class="bg-overlay pt50 pb50">
        <div class="container">
            <form method="POST" action="{{ route('checkout.store') }}" id="#">
                @csrf
            <div class="row">
                <div class="col-lg-6 col-sm-12">
                    <div class="bg-white bg-shadow mb-2">
                        <div class="heading checkout-heading  clearfix p10">
                            <h4 class=" color-white">Shipping Information</h4>
                        </div>

                        <div class="clearfix p20">

                                <div class="form-group">

                                <div class="row">
                                <div class="col-md-6">
                                    <label for="name">Name <span class="text-danger">*
                                    @error('name')
                                    {{ $message }}
                                    @enderror
                                    </span>
                                    </label>
                                    <input value="{{ old('name') ? old('name') : Auth::user()->name }}"
                                     class="form-checkout form-control"
                                      name="name" placeholder="Name" type="text" id="name">
                                    <p class="ptsan-regular"></p>
                                </div>
                                <div class="col-md-6">
                                <label for="name">Phone <span class="text-danger">*
                                    @error('phone')
                                    {{ $message }}
                                    @enderror
                                    </span></label>
                                    <input value="{{ old('phone') ? old('phone') : Auth::user()->phone }}"
                                    class="form-checkout form-control"
                                     name="phone" placeholder="Phone" type="text" id="name">
                                    <p class="ptsan-regular"></p>
                                </div>
                                </div>

                                </div>




                                <div class="form-group select-box">
                                    <label>Delivery Area
                                    <span class="text-danger">*
                                    @error('delivery_area')
                                    {{ $message }}
                                    @enderror
                                    </span>
                                    </label>
                                    <select class="form-checkout form-control" name="delivery_area">
                                        <option value="">Select Area</option>
                                        @foreach(getLocationData() as $area)
                                        <option @if(Auth::user()->location_id == $area->id) selected @endif value="{{ $area->id }}">{{ $area->city }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="address">Address <span class="text-danger">*
                                    @error('address')
                                    {{ $message }}
                                    @enderror
                                    </span></label>
                                    <textarea rows="2" class="form-checkout form-control" name="address" placeholder="Address" id="address">{{ Auth::user()->address }}</textarea>
                                    <p class="ptsan-regular"></p>
                                </div>

                                <div class="form-group select-box">
                                 @php
                                  $slot_setting  = getDateSlotSetting();
                                 @endphp
                                 @if($slot_setting->status == 1)
                                <!-- start slot row     -->
                                <div class="row">
                                    <div class="col-md-6">
                                    <label>Expected Delivery Date
                                    <span class="text-danger">*
                                    @error('delivery_date')
                                    {{ $message }}
                                    @enderror
                                    </span>
                                    </label>
                                    <select  class="form-checkout form-control" id="dateSlot" name="delivery_date" >
                                        <option value="">Delivery Date</option>
                                        @foreach(generateDateSlot() as $date)
                                        <option  value="{{ $date }}">
                                            @if($date == date('Y-m-d'))
                                               {{ "Today" }} - {{ date('d, M',strtotime($date)) }}
                                            @elseif($date == date('Y-m-d', strtotime(date('Y-m-d'). ' + 1 days')))
                                             {{ "Tomorrow" }} - {{ date('d, M',strtotime($date)) }}
                                            @else

                                            {{ date('D d, M',strtotime($date)) }}

                                            @endif
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                    <div class="col-md-6">
                                    <label>Delivery Time
                                    <span class="text-danger">*
                                    @error('delivery_time')
                                    {{ $message }}
                                    @enderror
                                    </span>
                                    </label>
                                    <select  class="form-checkout form-control" name="delivery_time" id="timeSlot">
                                        <option value="">Delivery Time</option>
                                    </select>

                                    </div>
                                </div>
                                <!-- end of slot row  -->
                                @endif

                                </div>

                               <div class="form-group">
                                 <input id="info-update" type="checkbox"
                                  value="1"
                                  name="profile_update"
                                   class="input-radio my-radio"
                                   >
                                    <label for="info-update">
                                    Update my profile with this information
                                     </label>
                                </div>

                        </div>
                    </div>

                </div>
                <!--left-->
                <div class="col-lg-6 col-sm-12">
                    <!-- cart  -->
                    <div class="form bg-white bg-shadow">
                        <div class="heading  checkout-heading clearfix p10">
                            <h4 class="color-white">Cart Summary</h4>
                        </div>
                        <div class="p20">
                        @error('cart_total')
                    <h5 class="text-danger text-center">
                   {{ $message }}
                    <h5>
                @enderror
                            <div class="payment-form">

                            @php
            $currency = getCurrentCurrency();
            @endphp
                 <checkout-cart :currency="{{ $currency }}"></checkout-cart>
                            </div>
                        </div>
                    </div>
                    <!-- cart  -->
                                        <!--   end user info-->
                    <div class="form bg-white bg-shadow mt-2">
                        <div class="heading  checkout-heading clearfix p10">
                            <h4 class="color-white">Payment</h4>
                        </div>
                        <div class="p20">
                        @error('payment_method')
                        <span class="text-danger">
                                    {{ $message }}
                        </span>
                        @enderror
                            <div class="payment-form">

                            @foreach($payment_method as $value)
                                <div class="payment-method">
                                    <input id="payment-method-{{ str_replace(' ','-',$value->provider) }}"
                                    class="input-radio my-radio" name="payment_method"
                                     value="{{ $value->id }}" type="radio">
                                    <label for="payment-method-{{ str_replace(' ','-',$value->provider) }}">{{ ucwords(str_replace('-',' ',$value->provider)) }}</label>
                                    @if($value->id == 1)
                                    <img  src="{{ url('assets/image/payment/c-cash.jpg') }}" alt="Cash Image">
                                    @endif
                                    @if($value->id == 2)
                                    <img  src="{{ url('assets/image/payment/paypal-payment-icon.png') }}" alt="Paypal Image">
                                    @endif
                                    @if($value->id == 3)
                                    <img  src="{{ url('assets/image/payment/stripe-payment-icon.png') }}" alt="Stripe Image">
                                    @endif
                                    @if($value->id == 4)
                                    <img  src="{{ url('assets/image/payment/ssl.jpg') }}" class="img-fluid" alt="SSL  Commerz">
                                    @endif

                                    @if($value->id == 5)
                                    <img  src="{{ url('assets/image/payment/logo_invert.svg') }}" class="img-fluid" alt="Razor-Pay" style="background: #123e6c">
                                    @endif

                               <!-- card no option for stripe payment  -->
                                    @if($value->id == 3)
                                    <div class="pay-box payment_method_stripe row">
                                    <div class="col-md-12">
                                        <input type="text" name="card_no"
                                        class="form-control stripe_input"
                                        placeholder="Card No">
                                        @error('card_no')
                                        <span class="text-danger">

                                    {{ $message }}

                                    </span>
                                    @enderror
                                        <input type="text" name="cvvNumber"
                                         class="form-control stripe_input"
                                          placeholder="CVC">
                                        @error('cvvNumber')
                                        <span class="text-danger">

                                    {{ $message }}

                                    </span>
                                    @enderror
                                    </div>
                                    <div class="col-md-6 col-6">
                                    <input type="text" name="expire_month"
                                         class="form-control stripe_input"
                                          placeholder="Expired Month EX:04">
                                        @error('expire_month')
                                      <span class="text-danger">
                                        {{ $message }}
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 col-6">
                                    <input type="text" name="expire_year"
                                         class="form-control stripe_input"
                                          placeholder="Expired Year EX:2030">
                                        @error('expire_year')
                                      <span class="text-danger">
                                        {{ $message }}
                                        </span>
                                        @enderror
                                    </div>


                                    </div>
                                    @endif
                                </div>
                            @endforeach

                                <button class="button button-md theme-background color-white mb20 w-100" type="submit">Place Order</button>
                            </div>
                        </div>
                    </div>

                </div>
                <!--left-->
            </div>
            </form>
        </div>
    </div>
</section>

@endsection

@push('script')
<script>
$(document).ready(function (i) {

i('#dateSlot').on('change',function(){
var dateValue = i('#dateSlot').val();
 var  request_url =  base_url + "time-slot/date/"+dateValue;
  i.ajax({
      url : request_url,
      method : "GET",
      _token : "{{ csrf_token()  }}",
      success : function(results){
        //   console.log(result);
        i('#timeSlot').empty();
        i('#timeSlot').append('<option value="">Choose Expected Slot</option>');
        results.forEach(function(item,index){
            i('#timeSlot').append('<option value="'+item.slot_name+'">'+item.slot_name+'</option>');
        })
      }

  })
});
 });



</script>
<script src="{{ asset('public/js/front.js') }}"></script>
@endpush
