<script>


var i = 1;
var count = 1;
$(document).ready(function() {


    $(document).on("click", ".add-select", function () {
        count++;
        var html = '';

console.log('cout: '+count);

html += "<tr>";

html += "<td>";
html +=
    '<select class="form-select item_category" data-width="100%" data-id="'+count+'" tabindex="-1" aria-hidden="true" id="item_category'+count+'" name="item_category[]" required>';
html += '<option value="">-- Select Country --</option>';
html += '@foreach ($item_category as $item_cat)';
html += '@if (Request::old("item_category") == $item_cat->item_category_id )';
html += '<option value="{{ $item_cat->item_category_id }}" selected>';
html += ' {{ $item_cat->item_category_name }}';
html += "</option>";
html += '@else';
html += "<option value='{{ $item_cat->item_category_id }}'>";
html += '{{ $item_cat->item_category_name }}';
html += '</option>';
html +=  '@endif';
html += '@endforeach';
html += "</select>";
html += "</td>";

html += "<td>";
html += '<select class="form-select item_subcategory" ';
html += 'data-width="100%"  data-id="'+count+'" tabindex="-1" aria-hidden="true" ';
html += 'id="item_subcategory'+count+'" name="item_subcategory[]" required> ';
html += '<option value="" selected>Select value</option>';
html += '</select>';
html += "</td>";

html += "<td>";
html += '<select class="form-select item_name" ';
html += 'data-width="100%"  data-id="'+count+'" tabindex="-1" aria-hidden="true" ';
html += 'id="item_name'+count+'" name="item_name[]" required> ';
html += '<option value="" selected>Select value</option>';
html += '</select>';
html += "</td>";

html +=
    '<td><input type="number" name="quatity[]" placeholder="Enter title"  class="form-control" aria-label="Group" required></td>';
html +='<td>';
html += ' <div class="input-group date timepicker1" id="end_dt_2" data-target-input="nearest">';
html +=  '<input class="form-control datetimepicker-input datetimepicker"';
html += 'name="delivery_date[]" type="text" placeholder="dd/mm/yyyy" data-target="#end_dt_2">';
// html += 'data-options=';
html += '<span class="input-group-addon" data-target="#end_dt_2" data-toggle="datetimepicker"><span class="fa fa-calendar"></span></span>'
// html += '{"disableMobile":true,"dateFormat":"d/m/Y"}';
// html += '>';
html += '</div></td>';


html +=
    '<td><input type="text" name="comment[]" placeholder="Enter title"  class="form-control" aria-label="Group" required></td>';
html +=
    '<td><button class="btn btn-phoenix-danger w-100 remove"><i class="fa fa-trash"></i></button></td>';
html += "</tr>";

$(function () {
  $('.timepicker1').datetimepicker({
    format: 'DD/MM/YYYY',
  });
});

console.log(html);
$("#productTable").append(html);
});

    // this will get the site category select list after changes in site type field
    $(document).on("change", "#floatingSiteType", function() {
        let id = $(this).val();
        console.log('venue type value: ' + id)
        // clear all dependent select fields
        $("#floatingSiteCategory").empty();
        $("#floatingSiteCode").empty();
        $.ajax({
            method: "POST",
            url: "{{ route('main.get.sitecategory') }}",
            data: {
                venue_type_id: id,
                _token: "{{csrf_token()}}",
            },
            success: function(res) {
                // console.log('made it to succes')
                if (res.status == "success") {
                    let all_options =
                        "<option value=''>Select site category</option>";
                    let all_site_categories = res.all_site_categories;
                    // console.log(all_site_categories)
                    $.each(all_site_categories, function(index, value) {
                        all_options +=
                            "<option value='" +
                            value.site_category_id +
                            "'>" +
                            value.venue_name +
                            "</option>";
                    });
                    $(".getSiteCategory").html(all_options);
                }
            },
        });
    });

    // this will get the site category select list after changes in site type field
    $(document).on("change", "#floatingSiteCategory", function() {
        let id = $(this).val();
         console.log('site category id: '+ id)
        $("#floatingSiteCode").empty();
        $.ajax({
            method: "POST",
            url: "{{ route('main.get.sitecode') }}",
            data: {
                venue_id: id,
                _token: "{{csrf_token()}}",
            },
            success: function(res) {
                // console.log('made it to success')
                if (res.status == "success") {
                    let all_options =
                        "<option value=''>Select site code</option>";
                    let all_site_codes = res.all_site_codes;
                    // console.log(all_site_categories)
                    $.each(all_site_codes, function(index, value) {
                        all_options +=
                            "<option value='" +
                            value.site_id +
                            "'>" +
                            value.site_code +
                            "</option>";
                    });
                    $(".getSiteCode").html(all_options);
                }
            },
        });
    });

    // this will get the site category select list after changes in site type field
    $(document).on("change", "#floatingSiteCode", function() {
        let id = $(this).val();
        // console.log('site id: ' + id)
        $("#floatingSiteName").empty();
        $.ajax({
            method: "POST",
            url: "{{ route('main.get.sitename') }}",
            data: {
                site_id: id,
                _token: "{{csrf_token()}}",
            },
            success: function(res) {
                // console.log('made it to success')
                if (res.status == "success") {
                    let all_options =
                        "<option value=''>Select site code</option>";
                    let all_site_codes = res.all_site_codes;
                    console.log(all_site_codes)
                    $.each(all_site_codes, function(index, value) {
                        all_options +=
                            "<option value='" +
                            value.site_id +
                            "' >" +
                            value.site_name +
                            "</option>";
                    });
                    $(".getSiteName").html(all_options);
                }
            },
        });
    });

    // this will get the subcategory select list after changes in logical space category
    $(document).on("change", "#floatingLogicalSpaceCategory", function() {
        let id = $(this).val();
        console.log('site id: ' + id)
        $("#floatingLogicalspaceSubcategory").empty();
        $.ajax({
            method: "POST",
            url: "{{ route('main.get.ls_subcat') }}",
            data: {
                category_id: id,
                _token: "{{csrf_token()}}",
            },
            success: function(res) {
                // console.log('made it to success')
                if (res.status == "success") {
                    let all_options =
                        "<option value='' selected>Select site code</option>";
                    let all_ls_subcat = res.all_ls_subcat;
                    console.log(all_ls_subcat)
                    $.each(all_ls_subcat, function(index, value) {
                        all_options +=
                            "<option value='" +
                            value.subcat_id +
                            "' >" +
                            value.subcat_name +
                            "</option>";
                    });
                    $(".getLogicalSpaceSubcategory").html(all_options);
                }
            },
        });
    });

    // this will get the logical space name select list after changes in logical space subcategory
    $(document).on("change", "#floatingLogicalspaceSubcategory", function() {
        let id = $(this).val();
        console.log('subcat id: ' + id)
        $("#floatingLogicalspaceName").empty();
        $.ajax({
            method: "POST",
            url: "{{ route('main.get.lsname') }}",
            data: {
                subcat_id: id,
                _token: "{{csrf_token()}}",
            },
            success: function(res) {
                // console.log('made it to success')
                if (res.status == "success") {
                    let all_options =
                        "<option value='' selected>Select site code</option>";
                    let all_ls_name = res.all_ls_name;
                    console.log(all_ls_name)
                    $.each(all_ls_name, function(index, value) {
                        all_options +=
                            "<option value='" +
                            value.logical_space_id +
                            "' >" +
                            value.logical_space_name +
                            "</option>";
                    });
                    $(".getLogicalSpaceName").html(all_options);
                }
            },
        });
    });


    $(document).on("change", "#floatingLogicalspaceName", function() {
        let id = $(this).val();
        console.log('subcat id: ' + id)
        $("#floatingLogicalspaceCode").empty();
        $.ajax({
            method: "POST",
            url: "{{ route('main.get.lscode') }}",
            data: {
                logical_space_id: id,
                _token: "{{csrf_token()}}",
            },
            success: function(res) {
                if (res.status == "success") {
                    let all_options =
                        "<option value='' selected>Select site code</option>";
                    let all_ls_code = res.all_ls_code;
                    console.log(all_ls_code)
                    $.each(all_ls_code, function(index, value) {
                        all_options +=
                            "<option value='" +
                            value.logical_space_id +
                            "' >" +
                            value.logical_space_code +
                            "</option>";
                    });
                    $(".getLogicalSpaceCode").html(all_options);
                }
            },
        });
    });

    $(document).on("change", ".item_category", function() {
        let id = $(this).val();
        console.log('subcat id: ' + id)
        let sequence_id = $(this).data('id');
        console.log('sequence_idxx: '+sequence_id);
        $("#item_subcategory").empty();
        $.ajax({
            method: "POST",
            url: "{{ route('main.get.itemsubcategory') }}",
            data: {
                item_category_id: id,
                _token: "{{csrf_token()}}",
            },
            success: function(res) {
                if (res.status == "success") {
                    let all_options =
                        "<option value='' selected>Select site code</option>";
                    let all_item_subcategory = res.all_item_subcategory;
                    console.log(all_item_subcategory)
                    $.each(all_item_subcategory, function(index, value) {
                        all_options +=
                            "<option value='" +
                            value.item_subcat_id +
                            "' >" +
                            value.item_subcat_name +
                            "</option>";
                    });
                    $("#item_subcategory"+sequence_id).html(all_options);
                }
            },
        });
    });

    $(document).on("change", ".item_subcategory", function() {
        let id = $(this).val();
        let sequence_id = $(this).data('id');
        console.log('subcat id: ' + id)
        $("#item_name").empty();
        $.ajax({
            method: "POST",
            url: "{{ route('main.get.itemname') }}",
            data: {
                item_subcat_id: id,
                _token: "{{csrf_token()}}",
            },
            success: function(res) {
                if (res.status == "success") {
                    let all_options =
                        "<option value='' selected>Select site code</option>";
                    let all_item_name = res.all_item_name;
                    console.log(all_item_name)
                    $.each(all_item_name, function(index, value) {
                        all_options +=
                            "<option value='" +
                            value.product_id +
                            "' >" +
                            value.product_name +
                            "</option>";
                    });
                    $("#item_name"+sequence_id).html(all_options);
                }
            },
        });
    });

});  // main
</script>
