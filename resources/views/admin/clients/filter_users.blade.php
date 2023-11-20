<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Фильтр</h3>
                <div class="box-tools pull-right">
                </div><!-- /.box-tools -->
            </div><!-- /.box-header -->
            <div class="box-body" style="display: block;">
                <form class="form-inline">
                    <div class="form-group mx-sm-3 mb-2">
                        <label for="organization" class="sr-only">Номер телефона</label>
                        <input type="tel" id="number" name="number" value="{{ request('number') }}" class="form-control name" placeholder="Номер телефона" onkeypress="validate(event)">
                    </div>
                    <button type="submit" class="btn btn-primary mb-2">Фильтровать</button>
                    @if(!empty(request('organization')) || !empty(request('number')))
                        <a href="{{  route('admin.clients') }}" class="btn btn-warning mb-2">Сбросить</a>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('/script/jquery.maskedinput.min.js') }}"></script>
<script>
    $(document).ready(function () {
        $('input[type="tel"]').mask("+38 (999) 999 99 99");
    });
</script>
