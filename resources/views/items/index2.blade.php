@extends('layouts.master')
@section('title')
Colours
@endsection
@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css"/>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
@endsection
@section('sidebar')
<div class="w3-sidebar w3-bar-block" style="width:10%"> 
    <h3 class="w3-bar-item">Filtry</h3>
    <h4 class="w3-bar-item">Tagi</h4>
    <form action="{{route('datatables.filtered')}}" method="post">
        @csrf
        @foreach($tags as $tag)
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="exampleCheck1" name="tags[]" value="{{ $tag->friend_name }}">
            <label class="form-check-label" for="exampleCheck1">{{ $tag->name }}</label>
        </div>
        @endforeach

        <h4 class="w3-bar-item">Kategorie</h4>
        @foreach($categories as $cat)
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="exampleCheck1" name="categories[]" value="{{ $cat->name }}">
            <label class="form-check-label" for="exampleCheck1">{{ $cat->name }}</label>
        </div>
        @endforeach
        <br>
        <input class="btn btn-dark" type="submit" value="Filtruj" />
    </form>
</div>
@endsection

@section('content')


<div style="margin-left:5%">
    <div class="w3-container w3-teal">
        <h1>Kolorki</h1>
    </div>

    <table class="table table-active table-bordered" id="items-table">
        <thead> 
            <tr>
                <th>ID</th>
                <th>NAME</th>
                <th>PRICE</th>
                <th>CATEGORY</th>
                <th>PROMOS</th>
                <th>AMOUNT</th>

            </tr>
        </thead>
    </table>
</div>
@stop

@push('scripts')

<!-- DataTables -->
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>

<script>
$(function () {
    $('#items-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: 'http://127.0.0.1:8000/items2/datatables.data',
        columns: [
            {data: 'id'},
            {data: 'name'},
            {data: 'price'},
            {data: 'category.name'},
            {data: 'tags[].name'},
//              {defaultContent: "<button>ADD</button>"}
//              {defaultContent: "<a class='btn btn-info' href="{{ route('item2.addToCart', 'id' ) }}">ADD</a>"}
            {data: 'id',
                render: function (data, type, row) {
                    return '<input id="input' + data + '" type="number"><a class=add2cart href="{{ route('item'.(auth()->id() ? '2' : '').'.addToCart', ':data')}}"> add to cart</a>'.replace(':data', data);
                }},
        ],
        "columnDefs": [
            {"orderable": false, "targets": 4}
        ]

    });
});</script>


<script>
    $('#items-table').on('click', 'a.add2cart', function (e) {
//         If this method is called, the default action of the event will not be triggered.
//        e.preventDefault();

//        var cd = this.href.match(/^http(s)?:\/\/(www\.)?127.0.0.1:8000\/items2\/[0-9]+/)[0];
//        var id = cd.substring(29);

        var id = this.href.match(/\d+$/)[0];
//        console.log(id);

        var url = this.href + '/' + getInputValue(id);
//        console.log(url);
        //przekierowanie
//        window.location = url;
        $.get(url, function (data) {
            //            $(".result").html(data);
            //            alert("Successfully added to Your Own Cart");
            //            autoClose: 'cancelAction|8000',
        });
        return false;
    });</script>


<script>
    function getInputValue(numb) {
        return $('#input' + numb).val();
        var value = document.getElementById('input' + numb).value;
        return value;
    }
</script>
@endpush

