<script>
    console.log('here order-form.blade.php')

    var i = 0;
    var count = 0;

    $('.btn-del-select').hide();
    $(document).on('click', '.add-select', function() {

        count++;
        var html = '';
        html += '<tr>';

        html += '<td>';
        html +=
            '<select class="form-select" data-width="100%" data-select2-id="1" tabindex="-1" aria-hidden="true" id="item_category" name="item_category[' +
            count + ']" required>';
        html += '<option value="">-- Select Country --</option>';
        html += '@foreach ($item_category as $item_cat)';
        html += '@if (Request::old("item_category") == $item_cat->item_category_id )';
        html += '<option value="{{ $item_cat->id }}" selected>';
        html += ' {{ $item_cat->item_category_id }}';
        html += '</option>';
        html += '@else';
        html += '<option value="{{ $item_cat->id }}">';
        html += '{{ $item_cat->item_category_id }}';
        html += '</option>';
        html += ' @endif';
        html += '@endforeach';
        html += '</select>';
        html += '</td>';


        html += '<td><input type="text" name="item_subcategory[' + count +
            '][title]" placeholder="Enter title"  class="form-control" aria-label="Group" required></td>'
        html += '<td><input type="text" name="item_name[' + count +
            '][title]" placeholder="Enter title"  class="form-control" aria-label="Group" required></td>'
        html += '<td><input type="text" name="quatity[' + count +
            '][title]" placeholder="Enter title"  class="form-control" aria-label="Group" required></td>'
        html += '<td><input type="text" name="delivery_date[' + count +
            '][title]" placeholder="Enter title"  class="form-control" aria-label="Group" required></td>'
        html += '<td><input type="text" name="comment[' + count +
            '][title]" placeholder="Enter title"  class="form-control" aria-label="Group" required></td>'
        html +=
            '<td><button class="btn btn-phoenix-danger w-100 remove"><i class="fa fa-trash"></i></button></td>';
        html += '</tr>'

        console.log(html)
        $('#productTable').append(html);
    });

    $(document).on('click', '.remove', function() {
        // console.log('remove')
        $(this).closest('tr').remove();
    });

    document.getElementById('flexSwitchCheckDefault').addEventListener('change', (e) => {
        this.checkboxValue = e.target.checked ? 'Y' : 'N';
        // console.log(this.checkboxValue)
    })
    </script>

    <script>
        console.log('token initialization');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    </script>
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <script>
        $(document).ready(function(){
            $(document).on('change','#floatingSiteType', function() {
                let venue_type_id = $(this).val();
                // console.log('venue type value: '+venue_type_id)
                $('#floatingSiteCategory').empty();
                $.ajax({
                    method: 'POST',
                    url: "{{ route('main.get.sitecategory') }}",
                    data: {
                        venue_type_id: venue_type_id,
                        _token: '{{csrf_token()}}'
                    },
                    success: function(res) {
                        // console.log('made it to succes')
                        if (res.status == 'success') {
                            let all_options = "<option value=''>Select Sub Category</option>";
                            let all_site_categories = res.site_categories;
                            // console.log(all_site_categories)
                            $.each(all_site_categories, function(index, value) {
                                all_options += "<option value='" + value.venue_id +
                                    "'>" + value.venue_name + "</option>";
                            });
                            $(".getSiteCategory").html(all_options);
                        }
                    }
                })
            });
        });
    </script>
