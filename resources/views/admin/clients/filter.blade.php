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
                        <label for="organization" class="sr-only">Ресторан</label>
                        <select class="form-control" style="width: 100%;" id="organization" name="organization">
                            <option value="0">Все</option>
                            @foreach($organization as $item)
                                <option value="{{ $item->id }}" @if(request('organization') == $item->id ) selected @endif>{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mx-sm-3 mb-2">
                        <label for="organization" class="sr-only">Номер заказа</label>
                        <input type="text" id="number" name="number" value="{{ request('number') }}" class="form-control name" placeholder="Номер заказа" onkeypress="validate(event)">
                    </div>
                    <button type="submit" class="btn btn-primary mb-2">Фильтровать</button>
                    @if(!empty(request('organization')) || !empty(request('number')))
                        <a href="{{  route('admin.clients.show',['id' => $user->id]) }}" class="btn btn-warning mb-2">Сбросить</a>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    function validate(evt) {
        var theEvent = evt || window.event;
        var key = theEvent.keyCode || theEvent.which;
        key = String.fromCharCode( key );
        var regex = /[0-9]|\./;
        if( !regex.test(key) ) {
            theEvent.returnValue = false;
            if(theEvent.preventDefault) theEvent.preventDefault();
        }
    }
</script>
