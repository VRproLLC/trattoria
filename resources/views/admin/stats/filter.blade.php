<form class="form-inline">
    <div class="form-group mx-sm-3 mb-2">
        <label for="staticEmail2" class="sr-only">Дата с</label>
        <input type="date" name="date_from" class="form-control" id="staticEmail2" placeholder="Дата с" value="{{request('date_from', \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d'))}}">
    </div>
    <div class="form-group mx-sm-3 mb-2">
        <label for="inputPassword2" class="sr-only">Дата по</label>
        <input type="date" name="date_to" class="form-control" id="inputPassword2" placeholder="Дата по"
               value="{{request('date_to', \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d'))}}">
    </div>


    @if($organization->count() > 1)
        <div class="form-group mx-sm-3 mb-2">
            <label for="organization" class="sr-only">Ресторан</label>
            <select class="form-control" style="width: 100%;" id="organization" name="organization">
                <option value="0">Все</option>
                @foreach($organization as $item)
                    <option value="{{ $item->id }}"
                            @if(request('organization') == $item->id ) selected @endif>{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
    @endif
    <button type="submit" class="btn btn-primary mb-2">Фильтровать</button>
    @if(!empty(request('date_from')) || !empty(request('date_to')))
        <a href="/admin" class="btn btn-warning mb-2">Сбросить</a>
    @endif
</form>
