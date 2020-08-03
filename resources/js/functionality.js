/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function() {
    $("#bvg-submit-data").validate({
        submitHandler: function(form) {
            if ($("input[type=checkbox].beverage-option:checked").length < 1) {
                alert("Please select atleast one beverage");
            } else {
                $.ajax({
                    url: "/api/v1/beverages/check",
                    data: $("#bvg-submit-data").serializeArray(),
                    method: "POST",
                    success: function(resp) {
                        if (resp.status == 200) {
                            $("#beverages-options-results .success").html(
                                resp.data.message
                            );
                            $("#beverages-options-results").removeClass(
                                "d-none"
                            );
                            $("#beverages-options").addClass("d-none");
                            $("#submitbutton, .selection-header").addClass("d-none");
                            $(".re-attempt").removeClass("d-none");
                        }
                    },
                    error: function(xhr) {

                        $('#server-error').html(xhr.responseJSON.error).removeClass('d-none');
                    }
                });
            }
            //form.submit();
            return false;
        }
    });
    $("#bvg-submit-data").on("change", ".checkbox", function() {
        let checkBox = $(this);

        let noOfDrinksWrapper = checkBox
            .closest(".checkbox-wrapper")
            .next(".no-of-drinks-wrapper");
        $(".no-of-drinks-wrapper").addClass("d-none");
        if (this.checked) {
            //Do stuff
            $("input.checkbox")
                .not(checkBox)
                .prop("checked", false);
            noOfDrinksWrapper.removeClass("d-none");
        } else {
            noOfDrinksWrapper.addClass("d-none");
        }
    });
    $.ajax({
        url: "/api/v1/beverages",
        success: function(resp) {
            if (resp.data.beverages.length > 0) {
                $.each(resp.data.beverages, function(index, data) {
                    $("#submitbutton").removeClass("d-none");
                    let optionTemplate = `<div class="form-check checkbox-wrapper"><div>
                                                <input name="beverages[${data.id}][id]" type="hidden" value="${data.id}" >
                                                <input name="beverages[${data.id}][drink_is_taken]" type="hidden" value="0" >
                                                <input name="beverages[${data.id}][drink_is_taken]" class="form-check-input checkbox beverage-option" type="checkbox" value="1" id="input-val-${data.id}">
                                                    <label class="form-check-label" for="input-val-${data.id}">
                                                    ${data.name}
                                                    </label>
                                                </div>
                                                <big id="mini-desc-for-input-${data.id}" class="form-text text-muted">${data.description}</big>
                                            </div>
                                            <div class="form-group no-of-drinks-wrapper d-none">
                                                <label for="had_count_${data.id}">How Many you had</label>
                                                <input name="beverages[${data.id}][qty]" type="number" min=1 class="form-control required" id="had_count_${data.id}" placeholder="How many you had ?">
                                            </div>`;
                    $("#beverages-options").append(optionTemplate);
                });
            }
        }
    });
});
