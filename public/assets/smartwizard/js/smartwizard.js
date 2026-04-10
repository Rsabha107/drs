

// const myModal = new bootstrap.Modal(document.getElementById('confirmModal'));


function onCancel() {
  // Reset wizard
  $('#smartwizard').smartWizard("reset");

  // Reset form
  document.getElementById("form-1").reset();
  document.getElementById("form-2").reset();
  document.getElementById("form-3").reset();
  document.getElementById("form-4").reset();
}

// added by me
function convertToJson() {
    let form1 = document.getElementById("form-1");
    console.log('form: '+form1)
    let formData = {};
    for (let i = 0; i < form1.elements.length; i++) {
        let element = form1.elements[i];
        console.log('element name: '+element.name)
        if (element.type !== "submit") {
            formData[element.name] = element.value;
        }
    }

    let form2 = document.getElementById("form-2");
    console.log('form: '+form2)
    let formData2 = {};
    // let formData = {};
    for (let i = 0; i < form2.elements.length; i++) {
        let element = form2.elements[i];
        console.log('element name: '+element.name)
        if (element.type !== "submit") {
            formData2[element.name] = element.value;
        }
    }

    let form3 = document.getElementById("form-3");
    console.log('form: '+form3)
    let formData3 = {};
    for (let i = 0; i < form3.elements.length; i++) {
        let element = form3.elements[i];
        console.log('element name: '+element.name)
        if (element.type !== "submit") {
            formData3[element.name] = element.value;
        }
    }

    let jsonData = formData;
    let jsonData2 = formData2;
    let jsonData3 = formData3;
    let jsonOutput = document.getElementById("jsonOutput");

    console.log(jsonData);
    console.log(jsonData2);
    console.log(jsonData3);

    $.ajax({
        url: "/tracki/employee/store",
        type: "POST",
        dataType: "json",
        data: jsonData,
        // headers: {
        //     "X-CSRF-TOKEN": $('input[name="_token"]').attr("value"),
        // },
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        async: false,
        success: function (response) {
            console.log(response);
            toastr.success(response["message"]);
            $('#smartwizard').smartWizard("reset");

            // Reset form
            document.getElementById("form-1").reset();
            document.getElementById("form-2").reset();
            document.getElementById("form-3").reset();
            document.getElementById("form-4").reset();
        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr.status);
            console.log(thrownError);
        },
    });

    // jsonOutput.innerHTML = "<pre>" + jsonData + "</pre>";
}
// end of added by me

function onConfirm() {
  let form = document.getElementById('form-4');
  if (form) {
    if (!form.checkValidity()) {
      form.classList.add('was-validated');
      $('#smartwizard').smartWizard("setState", [3], 'error');
      $("#smartwizard").smartWizard('fixHeight');
      return false;
    }

    $('#smartwizard').smartWizard("unsetState", [3], 'error');
    // convertToJson();
    // $("#add_employee_modal").modal("hide");
    // myModal.show();
  }
}

function closeModal() {
  // Reset wizard
  $('#smartwizard').smartWizard("reset");

  // Reset form
  document.getElementById("form-1").reset();
  document.getElementById("form-2").reset();
  document.getElementById("form-3").reset();
  document.getElementById("form-4").reset();

  myModal.hide();
}

function showConfirm() {
  const name = $('#first-name').val() + ' ' + $('#last-name').val();
  const products = $('#sel-products').val();
  const shipping = $('#address').val() + ' ' + $('#state').val() + ' ' + $('#zip').val();
  let html = `
          <div class="row">
            <div class="col">
              <h4 class="mb-3-">Customer Details</h4>
              <hr class="my-2">
              <div class="row g-3 align-items-center">
                <div class="col-auto">
                  <label class="col-form-label">Name</label>
                </div>
                <div class="col-auto">
                  <span class="form-text-">${name}</span>
                </div>
              </div>
            </div>
            <div class="col">
              <h4 class="mt-3-">Shipping</h4>
              <hr class="my-2">
              <div class="row g-3 align-items-center">
                <div class="col-auto">
                  <span class="form-text-">${shipping}</span>
                </div>
              </div>
            </div>
          </div>


          <h4 class="mt-3">Products</h4>
          <hr class="my-2">
          <div class="row g-3 align-items-center">
            <div class="col-auto">
              <span class="form-text-">${products}</span>
            </div>
          </div>

          `;
  $("#order-details").html(html);
  $('#smartwizard').smartWizard("fixHeight");
}

$(function() {
    // Leave step event is used for validating the forms
    $("#smartwizard").on("leaveStep", function(e, anchorObject, currentStepIdx, nextStepIdx, stepDirection) {
        // Validate only on forward movement
        if (stepDirection == 'forward') {
          let form = document.getElementById('form-' + (currentStepIdx + 1));
          if (form) {
            if (!form.checkValidity()) {
              form.classList.add('was-validated');
              $('#smartwizard').smartWizard("setState", [currentStepIdx], 'error');
              $("#smartwizard").smartWizard('fixHeight');
              return false;
            }
            $('#smartwizard').smartWizard("unsetState", [currentStepIdx], 'error');
          }
        }
    });

    // Step show event
    $("#smartwizard").on("showStep", function(e, anchorObject, stepIndex, stepDirection, stepPosition) {
        console.log('stepPosition: '+stepPosition)
        $("#prev-btn").removeClass('disabled').prop('disabled', false);
        $("#next-btn").removeClass('disabled').prop('disabled', false);
        if(stepPosition === 'first') {
            $("#prev-btn").addClass('disabled').prop('disabled', true);
        } else if(stepPosition === 'last') {
            $("#next-btn").addClass('disabled').prop('disabled', true);
        } else {
            $("#prev-btn").removeClass('disabled').prop('disabled', false);
            $("#next-btn").removeClass('disabled').prop('disabled', false);
        }

        // Get step info from Smart Wizard
        let stepInfo = $('#smartwizard').smartWizard("getStepInfo");
        $("#sw-current-step").text(stepInfo.currentStep + 1);
        $("#sw-total-step").text(stepInfo.totalSteps);

        if (stepPosition == 'last') {
          showConfirm();
          $("#btnFinish").prop('disabled', false);
        } else {
          $("#btnFinish").prop('disabled', true);
        }

        // Focus first name
        if (stepIndex == 1) {
          setTimeout(() => {
            $('#first-name').focus();
          }, 0);
        }
    });

    // Smart Wizard
    $('#smartwizard').smartWizard({
        selected: 0,
        // autoAdjustHeight: true,
        theme: 'arrows', // basic, arrows, square, round, dots
        transition: {
          animation:'none'
        },
        toolbar: {
          showNextButton: true, // show/hide a Next button
          showPreviousButton: true, // show/hide a Previous button
          position: 'bottom', // none/ top/ both bottom
          extraHtml: `<button class="btn btn-success" id="btnFinish" disabled onclick="onConfirm()">Submit Booking</button>
                      <button class="btn btn-danger" id="btnCancel" onclick="onCancel()">Cancel</button>`
        },
        anchor: {
            enableNavigation: true, // Enable/Disable anchor navigation
            enableNavigationAlways: false, // Activates all anchors clickable always
            enableDoneState: true, // Add done state on visited steps
            markPreviousStepsAsDone: true, // When a step selected by url hash, all previous steps are marked done
            unDoneOnBackNavigation: true, // While navigate back, done state will be cleared
            enableDoneStateNavigation: true // Enable/Disable the done state navigation
        },
    });

    $("#state_selector").on("change", function() {
        $('#smartwizard').smartWizard("setState", [$('#step_to_style').val()], $(this).val(), !$('#is_reset').prop("checked"));
        return true;
    });

    $("#style_selector").on("change", function() {
        $('#smartwizard').smartWizard("setStyle", [$('#step_to_style').val()], $(this).val(), !$('#is_reset').prop("checked"));
        return true;
    });

});
