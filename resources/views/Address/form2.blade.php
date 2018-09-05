<!--wyświetlnia wiadomości-->
@if (Session::has('message'))
<div id="message" class="alert alert-info">{{ Session::get('message') }}</div>
@endif

<div id="addressDiv">
    <h1>Add Your New Address</h1>
    <div id="errors"> </div>

    <form id="addressForm" action="{{route('address.edit',$address->id)}}" method="post">
        @csrf
        <div class="form-group">
            <label for="street">Street address:</label>
            <input type="text" class="form-control" name="street" id="street" value="{{$address->street}}">
        </div>
        <div class="form-group">
            <label for="city">City:</label>
            <input type="text" class="form-control" name="city" id="city" value="{{$address->city}}">
        </div>
        <div class="form-group">
            <label for="zip_code">Zip-code:</label>
            <input type="text" class="form-control" name="zip_code" id="zip_code" value="{{$address->zip_code}}">
        </div>
        <div class="form-group">
            <label for="phone">Phone:</label>
            <input type="text" class="form-control" name="phone" id="phone" value="{{$address->phone}}">
        </div>
        <!--{{ csrf_field() }}-->
        <button id="submitbtn" type="submit" class="btn btn-primary" disabled>Edit Address</button>

    </form>
    <br>
</div>


<script>
    $(document).ready(function() {

        $('#addressForm input').on('change', function() {

            var formInvalid = true;

            $('input').each(function() {
                if ($(this).val() === '') {
                    formInvalid = false;
                }
            });

            if (formInvalid)
                $("#submitbtn").attr("disabled", false);

        });

        var options = {
            success: function(data) {
              getAllAddresses();
                          $("#ajaxaddressbtn").attr("disabled", false);
                $("#addressnfo").show().delay(125).hide(1000).children("span").text(data);
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
