@if(session()->has('success'))
<div class="overlay"></div>
<div class="modal_block success_modal_block">
    <span class="close_modal"></span>
    <p class="success_text">{{session('success')}}</p>
</div>
@endif

@if(session()->has('error'))
<div class="overlay"></div>
<div class="modal_block error_modal_block">
    <span class="close_modal"></span>
    <p class="error_text">{{session('error')}}</p>
</div>
@endif


@if(session()->has('prevent_back'))
<script>
//    history.pushState(null, null, $(location).attr('href'));
//    window.addEventListener('popstate', function() {
//        history.pushState(null, null, $(location).attr('href'));
//    });
//    alert('Отработало!');
</script>
@endif




<div class="overlay sure_overlay"></div>
<div class="modal_block modal_sure">
    <span class="close_modal"></span>
    <p class="success_text">{{trans('main.cart_other_issue')}}?</p>
    <p class="mini_cart_other">{{trans('main.mini_cart_other_issue')}}.</p>
    <div class="button_ok_replace">{{trans('main.yes')}}</div>
</div>
