<!-- start cart  -->
          @php
            $currency = getCurrentCurrency();
            @endphp
   <cart :currency="{{ $currency }}"></cart>
<!-- end cart  -->
