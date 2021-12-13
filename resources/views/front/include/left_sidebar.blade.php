<!-- start Sidebar  -->
@if(Route::is('login') || Route::is('register') || request()->is('password/reset'))

@else
<nav class="sidebar">
    <div class="sidebar-header">
        <h3>Category</h3>
    </div>
    <ul>
      <!-- coming from App\Helpers\helper.php  -->
        @php
         $category = frontCategory();
        @endphp
       @if(count($category) > 0 )
        @foreach($category as $value)
        <li class="{{ request()->is('product/category/'.$value->id.'/'.str_replace(' ','-',$value->category_name)) ? 'initially_selected' : 'selected' }}">
            <a title="{{ $value->category_name }}" class="parent_a" href="#">
                <img alt="{{ $value->category_name }}" src="{{ asset('images/category/icon/'.$value->icon) }}"
                style="max-width: 30px; max-height: 30px; " alt="icon" class="img-fluid">
                {{ $value->category_name }} <i class="lni-chevron-down"></i></a>
            <ul class="sub_class">

                <!-- 2nd levelstart-->
                @foreach($value->sub_category as $sub_cat)
                <li
                class="{{ request()->is('product/sub-category/'.$sub_cat->id.'/'.str_replace(' ','-',$sub_cat->sub_category_name))? 'sub_category_active active_color' : '' }}">
                <a title="{{ $sub_cat->sub_category_name }}" href="{{ count($sub_cat->sub_sub_category) > 0 ? '#' : url('product/sub-category/'.$sub_cat->id.'/'.str_replace(' ','-',$sub_cat->sub_category_name)) }}">
                {{ $sub_cat->sub_category_name }}
                @if(count($sub_cat->sub_sub_category) > 0) <i class="lni-chevron-right"> @endif</i></a>

                <!-- third level start -->
                @if($sub_cat->sub_sub_category)
                <ul  class="third_level" style="display:none;margin-left:10px;">
                @foreach($sub_cat->sub_sub_category as $level_three)
                  <li class="{{ request()->is('product/sub-sub-category/'.$level_three->id.'/'.str_replace(' ','-',$level_three->sub_sub_category_name))? 'level_three_active active_color' : '' }}">
                      <a title="{{ $level_three->sub_sub_category_name }}" href="{{ route('subsubcategory.product',['id' => $level_three->id, 'slug' => str_replace(' ','-',$level_three->sub_sub_category_name) ]) }}">
                        {{ $level_three->sub_sub_category_name }}</a></li>
                @endforeach
                </ul>
                @endif
                <!-- third level end -->

                </li>
                @endforeach
                <!-- 2nd level end  -->

            </ul>
        </li>
        @endforeach
        @endif
    </ul>
</nav>
@endif
<!-- end Sidebar  -->
