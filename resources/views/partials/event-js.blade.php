<script>
    console.log('script of event-js.blade.php')
    $(document).ready(function() {
        // console.log('event-js.blade.php');

        $('#documentID').focus();

        // ************************************************** task progress number
        $('body').on('click', '#editTaskProgress', function(event) {
            console.log('inside sec click edit')
            // event.preventDefault();
            var id = $(this).data('id');
            var route = $(this).data('route');

            console.log('id: '+id);

            $.get('/tracki/task/progress/' + id + '/edit', function(data) {
                //  console.log('event name: ' + data);
                $.each(data, function(index, value) {
                    console.log(value[0]);
                    $('#editId').val(value[0].id);
                    $('#editEventId').val(value[0].event_id);
                    $('#editPoregessNumber').val(value[0].name);
                })

                // $('#staticBackdropLabel').html("Edit category");
                // $('#submit').val("Edit category");
                $('#editModal').modal('show');
            })
        });




        // ***************************************************** this is for the person setup pages
        $('body').on('click', '#personEditX', function(event) {
            console.log('inside click edit')
            // event.preventDefault();
            var id = $(this).data('id');
            var route = $(this).data('route');

            $.get('/tracki/setup/' + route + '/' + id + '/edit', function(data) {
                //  console.log('event name: ' + data);
                $.each(data, function(index, value) {
                    console.log(value[0]);
                    //tr += '<option value="'+value[0]+'">'+value[1]+'</option>';


                    $('#editId').val(value[0].id);
                    $('#editName').val(value[0].name);
                    $('#editEmail').val(value[0].email_address);
                    $('#editPhone').val(value[0].phone);
                    $('#editActiveFlag').val(value[0].active_flag);
                })

                // $('#staticBackdropLabel').html("Edit category");
                // $('#submit').val("Edit category");
                $('#editModal').modal('show');
            })
        });

        $('body').on('click', '#personAddX', function(event) {
            // event.preventDefault();
            // console.log('click of addEvent')
            // $('#staticBackdropLabel').html("Add");

            $('#addId').val('');
            $('#addName').val('');
            $('#addEmail').val('');
            $('#addPhone').val('');
            $('#activeFlag').val('');

            // $('#submit').val("Edit category");
            $('#addModal').modal('show');
        });

        // ***************************************************** this is for the security pages
        $('body').on('click', '#secEditX', function(event) {
            console.log('inside sec click edit')
            // event.preventDefault();
            var id = $(this).data('id');
            var route = $(this).data('route');

            $.get('/tracki/sec/' + route + '/' + id + '/edit', function(data) {
                //  console.log('event name: ' + data);
                $.each(data, function(index, value) {
                    console.log(value[0]);
                    //tr += '<option value="'+value[0]+'">'+value[1]+'</option>';


                    $('#editId').val(value[0].id);
                    $('#editName').val(value[0].name);
                    $('#editGroupName').val(value[0].group_name);
                    $('#editActiveFlag').val(value[0].active_flag);

                })

                // $('#staticBackdropLabel').html("Edit category");
                // $('#submit').val("Edit category");
                $('#editModal').modal('show');
            })
        });

        $('body').on('click', '#secAddX', function(event) {
            // event.preventDefault();
            console.log('click of addEvent')
            // $('#staticBackdropLabel').html("Add");

            $('#addId').val('');
            $('#addName').val('');
            $('#groupName').val('');

            // $('#submit').val("Edit category");
            $('#addModal').modal('show');
        })


        $('.zoom').hover(function() {
            $(this).addClass('transition');
        }, function() {
            $(this).removeClass('transition');
        });



        // ***************************************************** this is for the setup pages
        $('body').on('click', '#editX', function(event) {
            console.log('inside click edit')
            // event.preventDefault();
            var id = $(this).data('id');
            var route = $(this).data('route');
            console.log('id: ' + id);
            console.log('route: ' + route);

            $.get('/tracki/setup/' + route + '/' + id + '/edit', function(data) {
                //  console.log('event name: ' + data);
                $.each(data, function(index, value) {
                    console.log(value[0]);
                    //tr += '<option value="'+value[0]+'">'+value[1]+'</option>';
                    $('#editId').val(value[0].id);
                    $('#editName').val(value[0].name);
                    $('#editActiveFlag').val(value[0].active_flag);
                })

                // $('#staticBackdropLabel').html("Edit category");
                // $('#submit').val("Edit category");
                $('#editModal').modal('show');
            })
        });

        $('body').on('click', '#addX', function(event) {
            // event.preventDefault();
            console.log('click of addEvent')
            // $('#staticBackdropLabel').html("Add");

            $('#addId').val('');
            $('#addName').val('');
            $('#activeFlag').val('');

            // $('#submit').val("Edit category");
            $('#addModal').modal('show');
        })


        $('.zoom').hover(function() {
            $(this).addClass('transition');
        }, function() {
            $(this).removeClass('transition');
        });



        //*********************************************** */ this is for attendance pages
        $('body').on('click', '#editXAttend', function(event) {
            console.log('inside click edit editXAttend')
            // event.preventDefault();
            var id = $(this).data('id');
            var route = $(this).data('route');

            $.get('/tracki/' + route + '/list/' + id + '/edit', function(data) {
                //  console.log('event name: ' + data);
                $.each(data, function(index, value) {
                    console.log(value[0]);
                    //tr += '<option value="'+value[0]+'">'+value[1]+'</option>';

                    $('#editId').val(value[0].id);
                    $('#editFirstName').val(value[0].first_name);
                    $('#editLasttName').val(value[0].last_name);
                    $('#editEmailAddress').val(value[0].email_address);
                    $('#editPhoneNumber').val(value[0].phone_number);
                    $('#editTypeId').val(value[0].type_id);
                    $('#editActiveFlag').val(value[0].active_flag);
                })

                // $('#staticBackdropLabel').html("Edit category");
                // $('#submit').val("Edit category");
                $('#editModal').modal('show');
            })
        });

        $('body').on('click', '#addX', function(event) {
            // event.preventDefault();
            console.log('click of addEvent')
            // $('#staticBackdropLabel').html("Add");

            $('#addId').val('');
            $('#addName').val('');
            $('#activeFlag').val('');

            // $('#submit').val("Edit category");
            $('#addModal').modal('show');
        })
    });
</script>
