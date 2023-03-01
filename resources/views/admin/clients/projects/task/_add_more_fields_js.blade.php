<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js">
</script>

{{--    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>--}}

<script type="text/javascript">

    if ($(".multi-select").length > 0) {
        $( $(".multi-select") ).each(function( index,element ) {
            var id = $(element).attr('id');
            var multipleCancelButton = new Choices(
                '#'+id, {
                    removeItemButton: true,
                }
            );
        });
    }
    $(document).ready(function(){
        // alert('yes');
        var html = '<tr><td><input class="form-control" type="text" name="title[]"></td>'+
            '<td><input type="date" class="form-control form-control-light" id="start_date" name="start_date[]" required autocomplete="off"></td>'+
            '<td><input type="date" class="form-control form-control-light" id="due_date" name="due_date[]" required autocomplete="off"></td>'+
            '<td> <select class="form-control form-control-light select2" name="priority" id="task-priority" required>' +
            '<option value="Low">{{ __('Low')}}</option>'+
            '<option value="Medium">{{ __('Medium')}}</option>'+
            '<option value="High">{{ __('High')}}</option></select></td>'+
            '<td class="col-2"><textarea class="form-control form-control-light" id="description" rows="3" name="description[]"></textarea></td>'+
            '<td><i class="fa fa-trash" style="font-size: 30px; color:red" name="remove" value="remove" id="remove"></i></td></tr>';
        var max =3;
        var x = 1;
        $('#add').click(function (){
            if(x < max){
                $("#table_field").append(html);
                x++;
            }else if(x === max){
                alert(`Sorry you can not add more than ` + max +` fields at a time`)
            }
        })
        $('#table_field').on('click','#remove',function(){
            $(this).closest('tr').remove();
            x--;
        })

    })
</script>
