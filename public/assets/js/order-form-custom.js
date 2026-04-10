// console.log("here order-form-custom.php");

var i = 0;
var count = 0;

$(".btn-del-select").hide();
// $(document).on("click", ".add-select", function () {
//     count++;
//     var html = "";
//     html += "<tr>";

//     html += "<td>";
//     html +=
//         '<select class="form-select" data-width="100%" data-select2-id="1" tabindex="-1" aria-hidden="true" id="item_category" name="item_category[' +
//         count +
//         ']" required>';
//     html += '<option value="">-- Select Country --</option>';
//     html += "@foreach ($item_category as $item_cat)";
//     html += '@if (Request::old("item_category") == $item_cat->item_category_id )';
//     html += '<option value="{{ $item_cat->id }}" selected>';
//     html += ' {{ $item_cat->item_category_id }}';
//     html += "</option>";
//     html += "@else";
//     html += '<option value="{{ $item_cat->id }}">';
//     html += "{{ $item_cat->item_category_id }}";
//     html += "</option>";
//     html += " @endif";
//     html += "@endforeach";
//     html += "</select>";
//     html += "</td>";

//     html +=
//         '<td><input type="text" name="item_subcategory[' +
//         count +
//         '][title]" placeholder="Enter title"  class="form-control" aria-label="Group" required></td>';
//     html +=
//         '<td><input type="text" name="item_name[' +
//         count +
//         '][title]" placeholder="Enter title"  class="form-control" aria-label="Group" required></td>';
//     html +=
//         '<td><input type="text" name="quatity[' +
//         count +
//         '][title]" placeholder="Enter title"  class="form-control" aria-label="Group" required></td>';
//     html +=
//         '<td><input type="text" name="delivery_date[' +
//         count +
//         '][title]" placeholder="Enter title"  class="form-control" aria-label="Group" required></td>';
//     html +=
//         '<td><input type="text" name="comment[' +
//         count +
//         '][title]" placeholder="Enter title"  class="form-control" aria-label="Group" required></td>';
//     html +=
//         '<td><button class="btn btn-phoenix-danger w-100 remove"><i class="fa fa-trash"></i></button></td>';
//     html += "</tr>";

//     console.log(html);
//     $("#productTable").append(html);
// });

$(document).on("click", ".remove", function () {
    // console.log('remove')
    $(this).closest("tr").remove();
});

document
    .getElementById("flexSwitchCheckDefault")
    .addEventListener("change", (e) => {
        this.checkboxValue = e.target.checked ? "Y" : "N";
         console.log(this.checkboxValue)
    });

console.log("token initialization");
$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});


