<!--wyświetlnia wiadomości-->
@if (Session::has('message'))
<div id="message" class="alert alert-info">{{ Session::get('message') }}</div>
@endif

<div id="addressDiv">
    <h1>Add Your New Address</h1>
    <div id="errors"> </div>

    <form id="addressForm" action="{{route('address.update',$address->id)}}" method="post">
        @csrf
            <input name="_method" type="hidden" value="PUT">

        <input name="addressID" type="hidden" class="form-control" value="address{{$address->id}}">
        <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" class="form-control" name="name" id="name" value="{{$address->name}}" required>
    </div>
        <div class="form-group">
            <label for="street">Street address:</label>
            <input type="text" class="form-control" name="street" id="street" value="{{$address->street}}" required>
        </div>
        <div class="form-group">
            <label for="city">City:</label>
            <input type="text" class="form-control" name="city" id="city" value="{{$address->city}}" required>
        </div>
        <div class="form-group">
            <label for="zip_code">Zip-code:</label>
            <input type="text" class="form-control" name="zip_code" id="zip_code" value="{{$address->zip_code}}" required>
        </div>
        <div class="form-group">
            <label for="phone">Phone:</label>
            <input type="text" class="form-control" name="phone" id="phone" value="{{$address->phone}}" required>
        </div>

        <button id="submitbtn" type="submit" class="btn btn-primary" disabled>Edit Address</button>

    </form>
    <br>
</div>


<script>
    $(document).ready(function() {

        var address = $("input[name='addressID']").val();

        $('#addressForm input').on('change', function() {

            var formInvalid = true;

            $(this).each(function() {
                if ($(this).val() === '') {
                    formInvalid = false;
                }
            });

            if (formInvalid)
                $("#submitbtn").attr("disabled", false);

        });



        var options = {
            success: function(data) {
                $('#' + address).replaceWith(data);

                $("#ajaxaddressbtn").attr("disabled", false);
                                $("#addressnfo").show().delay(125).hide(1000).children("span").text("Address sucessfully edited!");
                $("#addressDiv").delay(125).hide(1000);
            },

            error: function(data) {
                $('.alert alert-danger').removeClass('alert alert-danger');
                var errors = data.responseJSON.errors;
                var html = '';
                for (var e in errors) {
                    $("input[name='" + e + "']").addClass("alert alert-danger");
                    html += errors[e][0] + '<br>';
                }

                $('#errors').html(html).addClass("alert alert-danger");

            }

        };

        $("#addressForm").ajaxForm(options);


    });

</script>
