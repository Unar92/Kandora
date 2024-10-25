$(document).on("click",".select-style-btn",function() {

    $(".customization-summary").removeClass('d-none');

    $(".kandora-type").text($(this).data("value"));

    $(".kandora-type-checkmark img").removeClass('d-none');
});

$(document).on("click",".custom-radio input[type=radio]",function() {

    $(".customization-summary").removeClass('d-none');

    $("."+$(this).attr('name')).text($(this).data("value"));

    $("img",$("."+$(this).attr('name')).parents('tr')).removeClass('d-none');

    updateSection(this);
});

$(document).on("click",".image-border li",function() {

    $(".customization-summary ." + $("h4",$(this)).attr('class')).text($("h4",$(this)).text());

    $("img",$(".customization-summary ." + $("h4",$(this)).attr('class')).parents('tr')).removeClass('d-none');

    updateSection(this);
});

function updateSection(currentElement) {

    var mainCurrentClass = $(currentElement).closest('.customize-section-box');
    mainCurrentClass.find('.heading.active').removeClass('active');
    mainCurrentClass.next('.customize-section-box').find('.heading').addClass('active');
    mainCurrentClass.next('.customize-section-box').find('.heading').removeAttr('disabled');

    mainCurrentClass.find('.customize-option').css('display', 'none');
    mainCurrentClass.next('.customize-section-box').find('.customize-option').css('display', 'block');

}

$(document).on("click",".customize-kandora-reset",function() {

    $('input[type="radio"]').prop('checked', false);

    $(".table tr").each(function() {
        $(this).find("td:eq(1)").text("");
        $(this).find("td:eq(2) img").addClass("d-none");
    });

    $(".customization-summary").addClass('d-none');

    $(".heading").removeClass("active");

    $(".customize-section-box .customize-option").css("display", "none");

    $(".customize-section-box .heading:first").addClass('active');

    $(".customize-section-box .customize-option:first").css("display", "block");

    $('.heading:not(:first)').attr('disabled', 'disabled');

});

