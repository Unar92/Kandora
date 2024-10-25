$(function () {
  $('.datepicker').datepicker({
    language: "es",
    autoclose: true,
    format: "dd/mm/yyyy"
  });

  $('.datepicker.datepicker-inline').css('display', 'none');
});

$('.datepicker.datepicker-inline').hide();

$('#fecha1').focus(function(){
    $('.datepicker.datepicker-inline').show();
});

$(document).mouseup(function(e){
    var container = $('.datepicker.datepicker-inline');
    if (!container.is(e.target) && container.has(e.target).length === 0) {
        container.hide();
    }
});

$(window).on("load", function () {
  // Customize Kandora Landing Page

  $(".kandora-style-slider").slick({
    dots: false,
    arrows: true,
    infinite: true,
    slidesToShow: 1,
    slidesToScroll: 1,
  });

  $(".slider-single-items").slick({
      dots: true,
      arrows: false,
      infinite: true,
      speed: 300,
      slidesToShow: 1,
      slidesToScroll: 1,
      autoplay: true,
      autoplaySpeed: 5000,
  }).slickAnimation();


  var $slider = $(".related-products-slider");
  var itemCount = $slider.children("div").length;
  $slider.slick({
    dots: itemCount > 4,
    arrows: false,
    infinite: false,
    speed: 300,
    slidesToShow: 4,
    slidesToScroll: 1,
    responsive: [
      {
        breakpoint: 1200,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 3,
          infinite: true,
          dots: itemCount > 3,
        },
      },
      {
        breakpoint: 991,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 2,
          dots: itemCount > 2,
        },
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
          dots: itemCount > 1,
        },
      },
    ],
  });

  
  $(".sizing-slider").slick({
    dots: false,
    arrows: true,
    infinite: true,
    speed: 300,
    slidesToShow: 5,
    slidesToScroll: 3,
        responsive: [
            {
            breakpoint: 400,
            settings: {
                slidesToShow: 3,
                slidesToScroll: 3,
            },
            },
        ]
    });

  // Modal Css Not Working Resolved Issue JS

  $(".our-exclusive-collection-item").on("mouseenter", function () {
    $(".our-exclusive-collection-item").removeClass("active-item");
    $(this).addClass("active-item");
    setTimeout(function () {
      $(".our-exclusive-collection-item").removeClass("active-item");
    }, 200);
  });

  $("#exampleModal").on("hidden.bs.modal", function () {
    setTimeout(function () {
      $(".our-exclusive-collection-item").addClass("active-item");
      $(".our-exclusive-collection-item").removeClass("active-item");
    }, 200);
  });

  

  // Modal Css Not Working Resolved Issue JS

  $(".customizer-item").on("mouseenter", function () {
    $(".customizer-item").removeClass("item-opened");
    $(this).addClass("item-opened");
  });

  // Country Code Selection

  $("#mobile_code").intlTelInput({
    initialCountry: "ae",
    separateDialCode: true,
  });
  $("#mobile_code2").intlTelInput({
    initialCountry: "ae",
    separateDialCode: true,
  });
  $("#mobile_code3").intlTelInput({
    initialCountry: "ae",
    separateDialCode: true,
  });
  $("#mobile_code4").intlTelInput({
    initialCountry: "ae",
    separateDialCode: true,
  });
  $("#mobile_code5").intlTelInput({
    initialCountry: "ae",
    separateDialCode: true,
  });
  $("#mobile_code6").intlTelInput({
    initialCountry: "ae",
    separateDialCode: true,
  });
  $("#mobile_code7").intlTelInput({
    initialCountry: "ae",
    separateDialCode: true,
  });
  $("#mobile_code8").intlTelInput({
    initialCountry: "ae",
    separateDialCode: true,
  });

  // Country Code Selection

  // Placeholder
  $(".formZn .form-control").focus(function () {
    $(this).siblings(".placeholder").hide();
  });

  $(".formZn .form-control").blur(function () {
    var $this = $(this);
    if ($this.val().length == 0) {
      $(this).siblings(".placeholder").show();
    }
  });

  $(".placeholder").click(function () {
    $(this).siblings("input").focus();
  });

  $(".formZn #mobile_code,.mobile-code-style input").blur(function () {
    var $this = $(this);
    if ($this.val().length == 0) {
      $(".placeholder.extrapaddingjs").show();
    }
  });

  $(".formZn #mobile_code,.mobile-code-style input").focus(function () {
    $(".placeholder.extrapaddingjs").hide();
  });

  $(".placeholder.extrapaddingjs").click(function () {
    $(".formZn #mobile_code,.mobile-code-style input").focus();
  });

  $(".mobile-code-style input[readonly]").blur();

    var itidropdown = $(".mobile-code-style").width();
      if (screen.width > 1200) {
          $('#iti-0__country-listbox').css('width' , itidropdown + 66 +'px');
      }
      else {

          $('#iti-0__country-listbox').css('width' , itidropdown + 0 +'px');
      }



  // Placeholder

  var paddingValue = $(".mobile-code-style input").css("padding-left");
  $(".extrapaddingjs").css("padding-left", paddingValue);

  // setTimeout(function () {
  //   $(".mobile-code-style input").focus();
  // }, 2000);

  $(".go-back-up").click(function () {
    $("html, body").animate({ scrollTop: 0 }, "slow");
  });
});

$(".heart-image-item").on("click", function () {
  $(this).toggleClass("active-heart");
});
// OTP PAGE

var timer;
var remainingTime = 120; // 2 minutes in seconds
var timerSpeed = 1000;

$(document).ready(function () {
  startTimer();
  auto_tab_input();
  auto_backspace();
});

function auto_tab_input() {
  $(".code-inputs .form-control").keyup(function () {
    if (this.value.length == this.maxLength) {
      $(this).nextAll(".code-inputs .form-control:enabled:first").focus();
    }
  });
}
function auto_backspace() {
  $(".code-inputs .form-control").keyup(function (e) {
    if (e.keyCode == 8) {
      if ($(this).prev().length > 0) {
        $(this).prev("input").focus();
      }
    }
  });
}

function startTimer() {
  timer = setInterval(updateTimer, timerSpeed);
}

function updateTimer() {
  var minutes = Math.floor(remainingTime / 60);
  var seconds = remainingTime % 60;
  $("#timer").text(minutes + ":" + seconds.toString().padStart(2, "0"));

  if (remainingTime <= 0) {
    clearInterval(timer);
    $("#resendBtn").prop("disabled", false);
    return;
  }

  remainingTime--;
}

function resendOTP() {
  clearInterval(timer);
  remainingTime = 120;
  startTimer();

  $("#resendBtn").prop("disabled", true);
}

$("#resendBtn").on("click", function () {
  resendOTP();
});

// OTP PAGE

// Override the default event handler for the toggle buttons
$(".card-header .btn").click(function (e) {
  e.preventDefault();

  var $panel = $(this).closest(".card");
  var $collapse = $panel.find(".collapse");

  // Toggle the collapse of the target panel
  $collapse.collapse("toggle");

  // Prevent the automatic closing of other panels
  if ($collapse.hasClass("show")) {
    $collapse.siblings(".collapse.show").collapse("hide");
  }
});

// Custom Select

$(document).on("click", function (event) {
  closeAllSelect(event.target);
});

$(
  ".custom-select-mine .select-selected,.custom-select-mine i.fa-chevron-down"
).on("click", function (event) {
  event.stopPropagation();
  closeAllSelect();
  $(this).parent().toggleClass("open");
  $(this).siblings(".select-items").slideDown("slow");
});

$(".custom-select-mine .select-items div").on("click", function () {
  const select = $(this).parent().parent().find(".select-selected");
  const currentOption = $(this).parent().prev();

  $(this).siblings().removeClass("same-as-selected");
  select.html($(this).html());
  currentOption.val($(this).html());
  $(this).addClass("same-as-selected");
});

function closeAllSelect(elmnt) {
  $(".custom-select-mine").each(function () {
    const options = $(this).find(".select-items");
    const select = $(this).find(".select-selected");
    if (elmnt !== select[0]) {
      $(this).removeClass("open");
      $(this).children(".select-items").slideUp("slow");
    }
  });
}

$(document).ready(function () {
  $(document).on("click", function (event) {
    if (!$(event.target).closest(".custom-search-parent").length) {
      $(".customsearch").removeClass("active");
      $(".customsearch").css("pointer-events", "none");
      $(".customsearch button").attr("type", "");
      $('.links-header ul li').css("pointer-events", "all");
    }
  });

  $(".custom-search-parent").on("click", function () {
    var searchBar = $(this).children(".customsearch");
    var searchInput = $(".customsearch input");
    $(".customsearch button").attr("type", "submit");
    searchBar.css("pointer-events", "initial");
    searchBar.addClass("active");
    searchInput.focus();
    $('.links-header ul li').css("pointer-events", "none");
  });
  $("#zoomimage").zoom();
});

/*http://laravel.io/forum/02-08-2014-ajax-autocomplete-input*/
var countries = {
  AD: "Andorra",
  A2: "Andorra Test",
  AE: "United Arab Emirates",
  AF: "Afghanistan",
  AG: "Antigua and Barbuda",
  AI: "Anguilla",
  AL: "Albania",
  AM: "Armenia",
  AN: "Netherlands Antilles",
  AO: "Angola",
  AQ: "Antarctica",
  AR: "Argentina",
  AS: "American Samoa",
  AT: "Austria",
  AU: "Australia",
  AW: "Aruba",
  AX: "\u00c5land Islands",
  AZ: "Azerbaijan",
  BA: "Bosnia and Herzegovina",
  BB: "Barbados",
  BD: "Bangladesh",
  BE: "Belgium",
  BF: "Burkina Faso",
  BG: "Bulgaria",
  BH: "Bahrain",
  BI: "Burundi",
  BJ: "Benin",
  BL: "Saint Barth\u00e9lemy",
  BM: "Bermuda",
  BN: "Brunei",
  BO: "Bolivia",
  BQ: "British Antarctic Territory",
  BR: "Brazil",
  BS: "Bahamas",
  BT: "Bhutan",
  BV: "Bouvet Island",
  BW: "Botswana",
  BY: "Belarus",
  BZ: "Belize",
  CA: "Canada",
  CC: "Cocos [Keeling] Islands",
  CD: "Congo - Kinshasa",
  CF: "Central African Republic",
  CG: "Congo - Brazzaville",
  CH: "Switzerland",
  CI: "C\u00f4te d\u2019Ivoire",
  CK: "Cook Islands",
  CL: "Chile",
  CM: "Cameroon",
  CN: "China",
  CO: "Colombia",
  CR: "Costa Rica",
  CS: "Serbia and Montenegro",
  CT: "Canton and Enderbury Islands",
  CU: "Cuba",
  CV: "Cape Verde",
  CX: "Christmas Island",
  CY: "Cyprus",
  CZ: "Czech Republic",
  DD: "East Germany",
  DE: "Germany",
  DJ: "Djibouti",
  DK: "Denmark",
  DM: "Dominica",
  DO: "Dominican Republic",
  DZ: "Algeria",
  EC: "Ecuador",
  EE: "Estonia",
  EG: "Egypt",
  EH: "Western Sahara",
  ER: "Eritrea",
  ES: "Spain",
  ET: "Ethiopia",
  FI: "Finland",
  FJ: "Fiji",
  FK: "Falkland Islands",
  FM: "Micronesia",
  FO: "Faroe Islands",
  FQ: "French Southern and Antarctic Territories",
  FR: "France",
  FX: "Metropolitan France",
  GA: "Gabon",
  GB: "United Kingdom",
  GD: "Grenada",
  GE: "Georgia",
  GF: "French Guiana",
  GG: "Guernsey",
  GH: "Ghana",
  GI: "Gibraltar",
  GL: "Greenland",
  GM: "Gambia",
  GN: "Guinea",
  GP: "Guadeloupe",
  GQ: "Equatorial Guinea",
  GR: "Greece",
  GS: "South Georgia and the South Sandwich Islands",
  GT: "Guatemala",
  GU: "Guam",
  GW: "Guinea-Bissau",
  GY: "Guyana",
  HK: "Hong Kong SAR China",
  HM: "Heard Island and McDonald Islands",
  HN: "Honduras",
  HR: "Croatia",
  HT: "Haiti",
  HU: "Hungary",
  ID: "Indonesia",
  IE: "Ireland",
  IL: "Israel",
  IM: "Isle of Man",
  IN: "India",
  IO: "British Indian Ocean Territory",
  IQ: "Iraq",
  IR: "Iran",
  IS: "Iceland",
  IT: "Italy",
  JE: "Jersey",
  JM: "Jamaica",
  JO: "Jordan",
  JP: "Japan",
  JT: "Johnston Island",
  KE: "Kenya",
  KG: "Kyrgyzstan",
  KH: "Cambodia",
  KI: "Kiribati",
  KM: "Comoros",
  KN: "Saint Kitts and Nevis",
  KP: "North Korea",
  KR: "South Korea",
  KW: "Kuwait",
  KY: "Cayman Islands",
  KZ: "Kazakhstan",
  LA: "Laos",
  LB: "Lebanon",
  LC: "Saint Lucia",
  LI: "Liechtenstein",
  LK: "Sri Lanka",
  LR: "Liberia",
  LS: "Lesotho",
  LT: "Lithuania",
  LU: "Luxembourg",
  LV: "Latvia",
  LY: "Libya",
  MA: "Morocco",
  MC: "Monaco",
  MD: "Moldova",
  ME: "Montenegro",
  MF: "Saint Martin",
  MG: "Madagascar",
  MH: "Marshall Islands",
  MI: "Midway Islands",
  MK: "Macedonia",
  ML: "Mali",
  MM: "Myanmar [Burma]",
  MN: "Mongolia",
  MO: "Macau SAR China",
  MP: "Northern Mariana Islands",
  MQ: "Martinique",
  MR: "Mauritania",
  MS: "Montserrat",
  MT: "Malta",
  MU: "Mauritius",
  MV: "Maldives",
  MW: "Malawi",
  MX: "Mexico",
  MY: "Malaysia",
  MZ: "Mozambique",
  NA: "Namibia",
  NC: "New Caledonia",
  NE: "Niger",
  NF: "Norfolk Island",
  NG: "Nigeria",
  NI: "Nicaragua",
  NL: "Netherlands",
  NO: "Norway",
  NP: "Nepal",
  NQ: "Dronning Maud Land",
  NR: "Nauru",
  NT: "Neutral Zone",
  NU: "Niue",
  NZ: "New Zealand",
  OM: "Oman",
  PA: "Panama",
  PC: "Pacific Islands Trust Territory",
  PE: "Peru",
  PF: "French Polynesia",
  PG: "Papua New Guinea",
  PH: "Philippines",
  PK: "Pakistan",
  PL: "Poland",
  PM: "Saint Pierre and Miquelon",
  PN: "Pitcairn Islands",
  PR: "Puerto Rico",
  PS: "Palestinian Territories",
  PT: "Portugal",
  PU: "U.S. Miscellaneous Pacific Islands",
  PW: "Palau",
  PY: "Paraguay",
  PZ: "Panama Canal Zone",
  QA: "Qatar",
  RE: "R\u00e9union",
  RO: "Romania",
  RS: "Serbia",
  RU: "Russia",
  RW: "Rwanda",
  SA: "Saudi Arabia",
  SB: "Solomon Islands",
  SC: "Seychelles",
  SD: "Sudan",
  SE: "Sweden",
  SG: "Singapore",
  SH: "Saint Helena",
  SI: "Slovenia",
  SJ: "Svalbard and Jan Mayen",
  SK: "Slovakia",
  SL: "Sierra Leone",
  SM: "San Marino",
  SN: "Senegal",
  SO: "Somalia",
  SR: "Suriname",
  ST: "S\u00e3o Tom\u00e9 and Pr\u00edncipe",
  SU: "Union of Soviet Socialist Republics",
  SV: "El Salvador",
  SY: "Syria",
  SZ: "Swaziland",
  TC: "Turks and Caicos Islands",
  TD: "Chad",
  TF: "French Southern Territories",
  TG: "Togo",
  TH: "Thailand",
  TJ: "Tajikistan",
  TK: "Tokelau",
  TL: "Timor-Leste",
  TM: "Turkmenistan",
  TN: "Tunisia",
  TO: "Tonga",
  TR: "Turkey",
  TT: "Trinidad and Tobago",
  TV: "Tuvalu",
  TW: "Taiwan",
  TZ: "Tanzania",
  UA: "Ukraine",
  UG: "Uganda",
  UM: "U.S. Minor Outlying Islands",
  US: "United States",
  UY: "Uruguay",
  UZ: "Uzbekistan",
  VA: "Vatican City",
  VC: "Saint Vincent and the Grenadines",
  VD: "North Vietnam",
  VE: "Venezuela",
  VG: "British Virgin Islands",
  VI: "U.S. Virgin Islands",
  VN: "Vietnam",
  VU: "Vanuatu",
  WF: "Wallis and Futuna",
  WK: "Wake Island",
  WS: "Samoa",
  YD: "People's Democratic Republic of Yemen",
  YE: "Yemen",
  YT: "Mayotte",
  ZA: "South Africa",
  ZM: "Zambia",
  ZW: "Zimbabwe",
  ZZ: "Unknown or Invalid Region",
  AD: "Andorra",
  UAE: "United Arab Emirates",
  Omani: "Omani Kandora Collection",
  Kuwaiti: "Kuwaiti Kandora Collection",
  Emirati: "Emirati Kandora Collection",
  Iranian: "Iranian Kandora Collection",
  Pakistani: "Pakistani Kandora Collection",
  Indian: "Indian Kandora Collection",
  Omani2: "Omani Kandora Collection",
  Omani3: "Omani Kandora Collection",
};

var countriesArray = $.map(countries, function (value, key) {
  return { value: value, data: key };
});

$(document).ready(function() {
  $('.select2-basic-js').select2();
});

// Initialize ajax autocomplete:
$("#autocomplete,#autocomplete2,#autocomplete3,#countries,#countries2,#countries3,#countries4").autocomplete({
  // serviceUrl: '/autosuggest/service/url',
  //lookup: countriesString,
  lookup: countriesArray,
  lookupFilter: function (suggestion, originalQuery, queryLowerCase) {
    var re = new RegExp(
      "\\b" + $.Autocomplete.utils.escapeRegExChars(queryLowerCase),
      "gi"
    );
    return re.test(suggestion.value);
  },
  onSelect: function (suggestion) {
    $("#selction-ajax").html(
      "You selected: " + suggestion.value + ", " + suggestion.data
    );
  },
  onHint: function (hint) {
    $("#autocomplete-ajax-x").val(hint);
  },
  onInvalidateSelection: function () {
    $("#selction-ajax").html("You selected: none");
  },
});

// Counter

// AUTOCOMPLETE
var Areas = [
  'Downtown Dubai',
  'Dubai Marina',
  'Jumeirah Beach Residence (JBR)',
  'Palm Jumeirah',
  'Business Bay',
  'Jumeirah Lakes Towers (JLT)',
  'Bur Dubai',
  'Deira',
  'Al Barsha',
  'Al Satwa',
  'Al Safa',
  'Al Quoz',
  'Dubai Silicon Oasis',
  'Dubai International City',
  'Discovery Gardens',
  'Dubailand',
  'Arabian Ranches',
  'Jumeirah',
  'Umm Suqeim',
  'Mirdif',
  'Motor City',
  'Greens',
  'The Springs',
  'The Meadows',
  'Sports City',
  'Dubai Festival City',
  'International Media Production Zone (IMPZ)',
  'Al Garhoud',
  'Al Nahda',
  'Al Qusais',
  'Jebel Ali',
  'Jebel Ali Village',
  'Dubai Investment Park',
  'Remraam',
  'Al Furjan',
  'Al Warqa',
  'Al Mizhar',
  'Al Twar',
  'DIP',
  'Dubai Hills Estate',
  'The Villa',
  'Jumeirah Village Circle (JVC)',
  'Jumeirah Village Triangle (JVT)',
  'Muhaisnah',
  'Al Barari',
  'Al Mamzar',
  'Nad Al Sheba',
  'Ras Al Khor',
  'Meydan City',
  'Academic City',
  'Dubai Studio City',
  'Al Khail Heights',
  'Dubai Waterfront',
  'Culture Village',
  'Dubai Creek Harbour',
  'Jumeirah Golf Estates',
  'Al Quoz Industrial Area',
  'Al Sufouh',
  'Al Khawaneej',
  'Al Jaddaf',
  'Dubai Design District (D3)',
  'Dubai Science Park',
  'The Greens & Views',
  'Jumeirah Islands',
  'Al Waha',
  'Jumeirah Park',
  'The Lakes',
  'Al Mizhar 1',
  'Al Mizhar 2',
  'Al Mizhar 3',
  'Umm Al Sheif',
  'Umm Suqeim 1',
  'Umm Suqeim 2',
  'Umm Suqeim 3',
  'Umm Suqeim Road',
  'Sheikh Zayed Road',
  'Al Wasl',
  'Jumeirah Heights',
  'Dubai Investment Park (DIP)',
  'Dubai Sports City',
  'Al Quoz 1',
  'Al Quoz 2',
  'Al Quoz 3',
  'Al Quoz 4',
  'Al Quoz Industrial Area 1',
  'Al Quoz Industrial Area 2',
  'Al Quoz Industrial Area 3',
  'Al Quoz Industrial Area 4',
  'Al Quoz Industrial Area 5',
  'Al Quoz Industrial Area 6',
  'Al Quoz Industrial Area 7',
  'Al Quoz Industrial Area 8',
  'Al Quoz Industrial Area 9',
  'Al Quoz Industrial Area 10',
  'Al Quoz Industrial Area 11',
  'Al Quoz Industrial Area 12',
  'Al Quoz Industrial Area 13',
  'Al Quoz Industrial Area 14',
  'Al Quoz Industrial Area 15',
  'Al Quoz Industrial Area 16',
  'Al Quoz Industrial Area 17',
  'Al Quoz Industrial Area 18',
  'Al Quoz Industrial Area 19',
  'Al Quoz Industrial Area 20',
];

var countriesArray = $.map(Areas, function (value, key) {
  return { value: value, data: key };
});

// Initialize ajax autocomplete:
$("#autocomplete4").autocomplete({
  // serviceUrl: '/autosuggest/service/url',
  //lookup: countriesString,
  lookup: countriesArray,
  lookupFilter: function (suggestion, originalQuery, queryLowerCase) {
    var re = new RegExp(
      "\\b" + $.Autocomplete.utils.escapeRegExChars(queryLowerCase),
      "gi"
    );
    return re.test(suggestion.value);
  },
  onSelect: function (suggestion) {
    $("#selction-ajax").html(
      "You selected: " + suggestion.value + ", " + suggestion.data
    );
  },
  onHint: function (hint) {
    $("#autocomplete-ajax-x").val(hint);
  },
  onInvalidateSelection: function () {
    $("#selction-ajax").html("You selected: none");
  },
});
// AUTOCOMPLETE

// FIXED
window.addEventListener("scroll", function () {
  var header = document.getElementById("header");
  if (window.pageYOffset > 38) {
    header.classList.add("fixed");
  } else {
    header.classList.remove("fixed");
  }
});
// FIXED

// Filter Responsive
$('.sorting-filter-btn-for-mobile').on('click',function(){
  $('.sorting-filter-btn-for-mobile').toggleClass('open-nav-icon');
  $('.filter-row .filter-products').toggleClass('filter-products-are-opened');
})
$('.btn-tab-content-for-small-screen').on('click',function(){
  $('.btn-tab-content-for-small-screen').toggleClass('open-nav-icon');
  $('.my-profile-tabs-left').toggleClass('open-profile');
})

$('.tabs-styling .nav-link').on('click',function(){
  $('.btn-tab-content-for-small-screen').trigger('click');
})
// Filter Responsive 

var counterInput = $("#cart-counter");

$(".subtract").click(function () {
  var currentValue = parseInt(counterInput.val());
  if (currentValue > 0) {
    counterInput.val(currentValue - 1);
  }
});

$(".addition").click(function () {
  var currentValue = parseInt(counterInput.val());
  counterInput.val(currentValue + 1);
});

counterInput.on("input", function () {
  var maxLength = parseInt(counterInput.attr("maxlength"));
  if (counterInput.val().length > maxLength) {
    counterInput.val(counterInput.val().slice(0, maxLength)); // Truncate the input value
  }
});

// Counter

// Colors Select
$(".boxes-colors").on("click", function () {
  var colorText = $(this).find(".color-text").text();
  $(".boxes-colors span").removeClass("active-color");
  $(this).find("span").addClass("active-color");
  $(".selected-color span").text(colorText);
});
// Colors Select

// Image Select
$(".small-image").on("click", function () {
  var imageSourceQuickView = $(this).find("img").attr("src");
  $(".big-images-item > div > img").attr("src", imageSourceQuickView);
  $(".small-image").removeClass("imagesourceQuickViewactive");
  $(this).addClass("imagesourceQuickViewactive");
  if ($(window).width() > 991) {
    $("#zoomimage").trigger("zoom.destroy");
    $("#zoomimage").zoom();
  }
});

// Image Select

// Copy
$("#copyButton").on("click", function () {
  var copyText = $("#copyText");
  copyText.select();
  copyText[0].setSelectionRange(0, 99999);
  document.execCommand("copy");
  $(this).find(".text-copy").text("Copied!");
});
// Copy

// Load More

$(document).ready(function () {
  if ($(window).width() < 991) {
    $("#zoomimage").trigger("zoom.destroy");
  }

  const $blogs = $(".blogs-main-parent");
  const $showMoreBtn = $("#showMore");
  const $showLessBtn = $("#showLess");
  let visibleCount = 5;

  function showBlogs(count) {
    $blogs.each(function (index) {
      if (index < count) {
        $(this).fadeIn();
      } else {
        $(this).fadeOut();
      }
    });
  }

  showBlogs(visibleCount);

  $showMoreBtn.on("click", function () {
    visibleCount += 1;
    showBlogs(visibleCount);

    if (visibleCount >= $blogs.length) {
      $showMoreBtn.hide();
      $showLessBtn.show();
    }
  });

  $showLessBtn.on("click", function () {
    visibleCount -= 1;
    showBlogs(visibleCount);

    if (visibleCount <= 4) {
      $showLessBtn.hide();
      $showMoreBtn.show();
    }
  });
});

// Load More

// Modal Image

$(".big-images-item").on("click", function () {
  var imageshtml = $(".small-images-multiple").html();
  $(".slick-slider-modal").html(imageshtml);
  setTimeout(function () {
    $(".slick-slider-modal").slick({
      dots: false,
      arrows: true,
      infinite: true,
      speed: 300,
      slidesToShow: 1,
      slidesToScroll: 1,
    });

    var firstSlideIndex = $(".imagesourceQuickViewactive").index();
    $(".slick-slider-modal").slick("slickGoTo", firstSlideIndex);
  }, 200);
});

$("#imageslickslider").on("hidden.bs.modal", function () {
  setTimeout(function () {
    $(".slick-slider-modal").slick("unslick");
  }, 200);
});

// Accordion Item

$(".faq-tabs .nav-pills .nav-link").on("shown.bs.tab", function () {
  $(".faq-tabs .tab-content > .active .accordion-item").hide();
  $(".faq-tabs .tab-content > .active .accordion-item:lt(6)").show();

  if ($(".faq-tabs .tab-content > .active .accordion-item").length > 6) {
    $(".faq-tabs .tab-content > .active .load-more-btn-accordions").show();
  } else {
    $(".faq-tabs .tab-content > .active .load-more-btn-accordions").hide();
  }

  $(".faq-tabs .tab-content > .active .load-more-btn-accordions").on(
    "click",
    function () {
      $(".faq-tabs .tab-content > .active .accordion-item:hidden").slideDown();
      $(this).hide();
      $(".faq-tabs .tab-content > .active .load-less-btn-accordions").show();
    }
  );

  $(".faq-tabs .tab-content > .active .load-less-btn-accordions").on(
    "click",
    function () {
      $(".faq-tabs .tab-content > .active .accordion-item:gt(5)").slideUp();
      $(this).hide();
      $(".faq-tabs .tab-content > .active .load-more-btn-accordions").show();
    }
  );
});

$(document).ready(function () {
  $(".faq-tabs .tab-content > .active .accordion-item").hide();
  $(".faq-tabs .tab-content > .active .accordion-item:lt(6)").show();

  if ($(".faq-tabs .tab-content > .active .accordion-item").length > 6) {
    $(".faq-tabs .tab-content > .active .load-more-btn-accordions").show();
  } else {
    $(".faq-tabs .tab-content > .active .load-more-btn-accordions").hide();
  }

  $(".faq-tabs .tab-content > .active .load-more-btn-accordions").on(
    "click",
    function () {
      $(".faq-tabs .tab-content > .active .accordion-item:hidden").slideDown();
      $(this).hide();
      $(".faq-tabs .tab-content > .active .load-less-btn-accordions").show();
    }
  );

  $(".faq-tabs .tab-content > .active .load-less-btn-accordions").on(
    "click",
    function () {
      $(".faq-tabs .tab-content > .active .accordion-item:gt(5)").slideUp();
      $(this).hide();
      $(".faq-tabs .tab-content > .active .load-more-btn-accordions").show();
    }
  );
});
// Accordion Item

// Video Modal

$('[data-target="#modalvideo"]').on("click", function () {
  var getclosestvideo = $(this).find(".hidden-video").html();
  $(".video-modal").html(getclosestvideo);
});

// Video Modal

// Upload File

$(document).ready(function () {
  $("#uploadefile").change(function () {
    var filename = $(this).val().split("\\").pop();
    if (filename) {
      $(this).siblings("label").text(filename);
      $("#clearInput").fadeIn(500);
    } else {
      $(this).siblings("label").text("Update Profile Picture");
    }
  });
  $("#clearInput").click(function () {
    $("#uploadefile").val("");
    $(this).siblings("label").text("Update Profile Picture");
    $(this).fadeOut(500);
  });
});

// Upload File

// Product Listings

$(".card-filter-button").click(function () {
  var filterBody = $(this).closest(".card-filter").find(".card-filter-body");
  filterBody.slideToggle();
  filterBody.parent().toggleClass("closed-card-filter");
});

$(".card-filter-button-select").click(function () {
  var filterBody = $(this).closest(".card-filter").find(".card-filter-body");
  var checkboxes = filterBody.find('input[type="checkbox"]');
  var selectAll = $(this).text();

  if (selectAll === "Select All") {
    checkboxes.prop("checked", true);
    $(this).text("Deselect All");
  } else {
    checkboxes.prop("checked", false);
    $(this).text("Select All");
  }
});

// Product Listings

// About Us
$(".timeline-parent-class .small-text").mouseenter(function () {
  $(".timeline-parent-class .small-text").removeClass("active-timeline");
  $(this).addClass("active-timeline");
});
// About Us

// Select Stars

$(".rate-this-product-stars i").click(function () {
  $(this).addClass("active-stars");
  $(this).prevAll().addClass("active-stars");
  $(this).nextAll().removeClass("active-stars");

  var selectedStars = $(this).prevAll().length + 1;
  $(".stars-selected").val(selectedStars);
});

// Select Stars

// My Orders

// $(".shop_table.my_account_orders.tbody-table .order-view").click(function () {
//   $(this).siblings(".items-inside-order-in-details").slideToggle();
//   $(this).parent().toggleClass("bg-white");
//   $(this).find(".eye-open, .cross-close").toggleClass("eye-open cross-close");
// });

$(".shop_table.my_account_orders.tbody-table .order-view").click(function () {
  // Close previously opened item
  $(".items-inside-order-in-details").not($(this).siblings(".items-inside-order-in-details")).slideUp();
  $(".shop_table.my_account_orders.tbody-table .order-view").not(this).parent().removeClass("bg-white");
  $(".shop_table.my_account_orders.tbody-table .order-view").not(this).find(".open-item-order").removeClass("show-this-order");

  // Toggle the visibility of the current item
  $(this).siblings(".items-inside-order-in-details").slideToggle();
  $(this).parent().toggleClass("bg-white");
  $(this).find(".open-item-order").toggleClass("show-this-order");
});

$(".delete-anchor").click(function (e) {
  e.preventDefault();
  $(this).closest("tr").remove();
});

// My Orders

// Select 2.0

$(document).ready(function () {
  $(".real-select").each(function () {
    var $selElmnt = $(this).find("select:first");
    var $a = $("<div class='real-select-selected'></div>").html(
      $selElmnt.find("option:selected").html()
    );
    $(this).append($a);

    var $b = $("<div class='real-select-items real-select-hide'></div>");
    $selElmnt
      .find("option")
      .slice(1)
      .each(function () {
        var $c = $("<div></div>").html($(this).html());

        if ($(this).hasClass("color-red")) {
          $c.prepend("<span class='color-red'>*</span>");
        }

        $c.on("click", function (e) {
          var $s = $(this).closest(".real-select").find("select:first");
          var $h = $(this).parent().prev();
          var $y = $(this).parent().find(".same-as-real-selected");

          $s.prop("selectedIndex", $(this).index() + 1);
          $h.html($(this).html());

          $y.removeClass("same-as-real-selected");
          $(this).addClass("same-as-real-selected");

          $h.click();
        });

        $b.append($c);
      });

    $(this).append($b);

    $a.on("click", function (e) {
      e.stopPropagation();
      closeAllSelect(this);
      $(this).next().toggleClass("real-select-hide");
      $(this).toggleClass("real-select-arrow-active");
    });
  });

  $(document).on("click", closeAllSelect);

  function closeAllSelect(elmnt) {
    var $x = $(".real-select-items");
    var $y = $(".real-select-selected");

    $y.not(elmnt).removeClass("real-select-arrow-active");
    $x.not($(elmnt).next()).addClass("real-select-hide");
  }
});

// Select 2.0

// Menu

$(".icon-sub-menu-button").on("click", function () {
  $(".icon-sub-menu-button button").toggleClass("open-nav-icon");
  $(".links-header > ul").toggleClass("opened-nav");
  $("body").toggleClass("body-color-blackish");
});

$(".links-header ul li.sub-menu-container > a").on("click", function () {
  var subMenu = $(this).siblings(".opened-nav .sub-menu");

  // Slide up all other .sub-menu elements
  $(".opened-nav .sub-menu").not(subMenu).slideUp("fast");

  // Slide toggle the clicked .sub-menu element
  if (subMenu.is(":hidden")) {
    subMenu.slideDown("fast");
  } else {
    subMenu.slideUp("fast");
  }
});


$(".sub-menu-opener i").on("click", function () {
  var subMenu2 = $(this).parent().siblings(".opened-nav .sub-menu");

  // Slide up all other .sub-menu elements
  $(".opened-nav .sub-menu").not(subMenu2).slideUp("fast");

  // Slide toggle the clicked .sub-menu element
  if (subMenu2.is(":hidden")) {
    subMenu2.slideDown("fast");
  } else {
    subMenu2.slideUp("fast");
  }
});

// Menu

//

$(window).on("load", function () {
  $(".buttons-view-cart").each(function () {
    if ($(this).children().length === 1) {
      $(this).addClass("full-single-btn");
    }
  });

  $(".share-this-page").on("click", function () {
    $(this).siblings(".copytoclipboard").slideToggle();
  });
  
  $(".modal-item-image .share-this-page").on("click", function () {
    $(this).parent().parent().toggleClass('mb-opened');
  });

  $(".read-more-btn").on("click", function () {
    var $extraText = $(this).prev().children(".extra-text");
    $(this).toggleClass("read-less-now");
    $extraText.slideToggle();

    var buttonText = $(this).find("span:first-child");
    buttonText.text(function (index, text) {
      return text === "Read More" ? "Read Less" : "Read More";
    });
  });
  $('.whatsapp-icon').addClass('animation-start')
});

//

$(document).ready(function() {
    function checkAndInitializeSlider(tabPaneId) {
      var slider = $("#" + tabPaneId + " .our-exclusive-collection-slider-double");
  
      var windowWidth = $(window).width();
  
      if (slider.children().length > 10 || windowWidth < 991) { // Adjust the breakpoint as needed
        slider.slick({
          rows: 2,
          dots: true,
          arrows: false,
          infinite: false,
          speed: 300,
          slidesToShow: 5,
          slidesToScroll: 5,
          responsive: [
            {
              breakpoint: 1439,
              settings: {
                slidesToShow: 4,
                slidesToScroll: 4,
                rows: 2,
              },
            },
            {
              breakpoint: 1200,
              settings: {
                slidesToShow: 3,
                slidesToScroll: 3,
                rows: 2,
              },
            },
            {
              breakpoint: 991,
              settings: {
                slidesToShow: 2,
                slidesToScroll: 2,
                rows: 2,
                dots:false,
                infinite:true,
                autoplay:true
              },
            },
            {
                breakpoint: 650,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    rows: 1,
                    dots:false,
                    infinite:true,
                    autoplay:true
                },
            },
          ],
        });
      } else {
        if (slider.hasClass("slick-initialized")) {
          slider.slick("unslick");
        }
      }
    }
  
    $(".tab-pane-custom").each(function() {
      checkAndInitializeSlider($(this).attr("id"));
    });
  
    $("a[data-toggle='tab']").on("shown.bs.tab", function (e) {
      var tabPaneId = $(e.target).attr("href").substring(1);
      checkAndInitializeSlider(tabPaneId);
    });

  });
  

$(document).ready(function() {
  $('.tab-heading button').on('click', function() {
      $('.tab-heading button').removeClass('active');
      $(this).addClass('active');
      $('.tab-pane-custom').removeClass('active');
      var targetId = $(this).attr('data-target');
      $(targetId).addClass('active');
      // $(targetId).show();
  });
  $('.tab-heading:first-child button').addClass('active');
  // $('.tab-pane-custom:first-child').show();
});

// Changes

$(document).ready(function () {
  let parentElement;
  function addAndRemoveClass() {
      if (parentElement) {
          parentElement.addClass('active-item');
          parentElement = undefined;
          setTimeout(function () {
              parentElement.removeClass('active-item');
          }, 2000);
      }
  }
  $('.buttons-view-cart button').click(function () {
      parentElement = $(this).closest('.our-exclusive-collection-item');
  });
  $('#quickviewitem').on('hidden.bs.modal', addAndRemoveClass);

  $('#modalvideo').on('hidden.bs.modal', function () {
    // Find the iframe inside the modal and set its src attribute to an empty string
    $('#modalvideo iframe').attr('src', '');
    $('#modalvideo video').attr('src', '');
  });
});

$('.play-icon-customize').on('click', function(){
  var customizevideo = $(this).find('.modal-video-customizeyourkandora').html();
  $('.video-modal').html(customizevideo);
})


// Filter Empty
$(document).ready(function() {
  var targetDiv = $('.filters-section .filter-products .accordion-product-filter');
  if ($.trim(targetDiv.html()) === '') {
      targetDiv.closest('.filter-row').addClass('empty-filter');
  }
});

$('.table-sliding-on-click').on('click', function() {
  $(this).children('.open-summary').slideToggle(500, "swing");
});


// Customize

$(document).ready(function() {
  // Function to update the images based on selected values
  function updateImage() {
    const colorValue = $('input[name="color_radio"]:checked').data('value');
    const buttonsValue = $('input[name="buttons_radio"]:checked').data('value');
    const TarbosshValue = $('input[name="tarboosh_radio"]:checked').data('value');

    // Check if "None" is selected for color and remove the main image
    if (colorValue === 'none') {
      $('.selected-images-changes-in-images .main-image').remove();
    } else {
      // If not "None", update the main image source
      const mainImageSource = `assets/images/customize/${colorValue}.png`;
      $('.selected-images-changes-in-images .main-image').attr('src', mainImageSource);
    }

    // Update the buttons image source
    const buttonsImageSource = `assets/images/customize/show-button-farukha-1.png`; // Update this based on your buttons selection
    $('.selected-images-changes-in-images .buttons-selected').attr('src', buttonsImageSource);

    // Update the tarboosh image source
    const tarbooshImageSource = `assets/images/customize/tarboosh.png`; // Update this based on your tarboosh selection
    $('.selected-images-changes-in-images .tarboosh-selected').attr('src', tarbooshImageSource);
  }

  // Attach the updateImage function to the change event of the radio buttons
  $('input[name="color_radio"], input[name="buttons_radio"]').change(updateImage);

  // Initial image update
  updateImage();
});

$(document).ready(function() {
  function updateImage() {
    const colorValue = $('input[name="color_radio"]:checked').data('value');
    const buttonsValue = $('input[name="buttons_radio"]:checked').data('value');
    const tarbooshValue = $('input[name="tarboosh_radio"]:checked').data('value');

    // Update the main image source
    const mainImageSource = `assets/images/customize/${colorValue}.png`;
    $('.selected-images-changes-in-images .main-image').attr('src', mainImageSource);

    // Update the buttons image source or hide the element
    if (buttonsValue === 'none') {
      $('.selected-images-changes-in-images .buttons-selected').fadeOut();
    } else {
      const buttonsValueImage = `assets/images/customize/show-button-farukha-1.png`;
      $('.selected-images-changes-in-images .buttons-selected').attr('src', buttonsValueImage).fadeIn().animate({ zoom: 1.5 }, 1500);
    }

    // Update the tarboosh image source or hide the element
    if (tarbooshValue === 'none') {
      $('.selected-images-changes-in-images .tarboosh-selected').fadeOut();
    } else {
      const tarbooshImageSource = `assets/images/customize/tarboosh.png`;
      $('.selected-images-changes-in-images .tarboosh-selected').attr('src', tarbooshImageSource).fadeIn().animate({ zoom: 1.5 }, 1500);
    }
  }

  // Use change event on radio buttons to trigger image update
  $('input[name="color_radio"], input[name="buttons_radio"], input[name="tarboosh_radio"]').change(updateImage);

  updateImage(); // Initial image update
});


var sliderSmallImages = $('.small-images-multiple');
var itemCountSmallImages = sliderSmallImages.children("div").length;

if (itemCountSmallImages > 5) { 
  sliderSmallImages.slick({
    slidesToShow: 5,
    slidesToScroll: 1,
    arrows: false,
    dots: false,
    vertical: true,
    verticalSwiping: true,
    infinite: true,
    responsive: [
      {
        breakpoint: 575,
        settings: {
          vertical: false,
          verticalSwiping: false,
          slidesToShow:4
        },
      },
      {
        breakpoint: 480,
        settings: {
          vertical: false,
          verticalSwiping: false,
          slidesToShow:3
        },
      }
    ]
  });


  $('.small-images-multiple').on('click', '.slick-slide', function() {
    var clickedIndex = $(this).data('slick-index');
    $('.small-images-multiple').slick('slickGoTo', clickedIndex);
  });
}
else{
  // alert(sliderSmallImages)
}



// $('#quickviewitem').on('shown.bs.modal', function(){
//   setTimeout(function(){
//     $('div#quickviewitem .small-images-multiple .small-image').removeClass('imagesourceQuickViewactive')
//     $('.small-images-multiple').slick('refresh');
//     if (sliderSmallImages > 5) { 
//       $('.small-images-multiple').on('click', '.slick-slide', function() {
//         var clickedIndex = $(this).data('slick-index');
//         $('.small-images-multiple').slick('slickGoTo', clickedIndex);
//       });
//     }
//     else{
//       alert('NOpe');
//     }
//   },2000)
// });

$('#quickviewitem').on('shown.bs.modal', function(){
  setTimeout(function(){
    var $smallImages = $('div#quickviewitem .small-images-multiple');
    if ($smallImages.length) {
      $smallImages.find('.small-image').removeClass('imagesourceQuickViewactive');
      if ($smallImages.hasClass('slick-initialized')) {
        $smallImages.slick('refresh');
        var sliderSmallImages = $smallImages.slick('getSlick').slideCount;
        if (sliderSmallImages > 5) { 
          $smallImages.on('click', '.slick-slide', function() {
            var clickedIndex = $(this).data('slick-index');
            $smallImages.slick('slickGoTo', clickedIndex);
          });
        } else {
          alert('NOpe');
        }
      } else {
        alert('Slick slider is not initialized on .small-images-multiple');
      }
    } else {
      alert('Element with class .small-images-multiple not found');
    }
  }, 2000);
});


function copyAddress() {
  var addressText = document.getElementById("addressCopy").innerText;
  var textarea = document.createElement("textarea");
  textarea.value = addressText;
  document.body.appendChild(textarea);
  textarea.select();
  document.execCommand("copy");
  document.body.removeChild(textarea);
  var copyButton = document.getElementById("copyButton");
    copyButton.innerText = "Copied!";

  setTimeout(function () {
      copyButton.innerText = "Copy Location";
  }, 5000);
  
  // alert("Address copied to clipboard: " + addressText);
}

$('.gifted-text-show').on('click',function() {
  $('.gifting-part-text-area').slideToggle();
})

$(document).ready(function() {
  $('.sub-menu-opener a').each(function() {
      var text = $(this).text().toLowerCase();
      if (text === 'accessories') {
          $(this).parent().siblings('.sub-menu-bigger').addClass('left-auto-right-0');
      } else if (text === 'مُكَمِّلات') {
        $(this).parent().siblings('.sub-menu-bigger').addClass('center-bigger-menu');
      }
  });

  $('.cookies-btns button.btn-reg').on('click', function(){
    $('.accept-cookies').slideUp();
  });
});

$(document).ready(function() {
  $('.customer-review-single-item').each(function() {
      var profileDiv = $(this).find('.profile-picture');
      var img = profileDiv.find('img');
      var name = $(this).find('h3').text();

      if (img.length === 0) {
          var initials = name.split(' ').map(word => word.charAt(0)).join('').substring(0, 2).toUpperCase();
          var initialElement = $('<p>').text(initials)
          profileDiv.append(initialElement);
      }
      else{
        profileDiv.css('background-color','transparent');
      }
  });
});

$(document).ready(function () {
  function slidersmallscreen() {
    $('.below767slider').slick({
      dots: false,
      arrows: false,
      infinite: true,
      autoplay: true,
      speed: 300,
      slidesToShow: 2,
      slidesToScroll: 1,
      responsive: [
        {
          breakpoint: 600,
          settings: {
            slidesToShow: 1,
          }
        }
      ]
    });
  }

  function tabletdualslider() {
    $('.below991slider').slick({
      dots: false,
      arrows: false,
      infinite: true,
      autoplay: true,
      speed: 300,
      slidesToShow: 3,
      slidesToScroll: 1,
      responsive: [
        {
          breakpoint: 600,
          settings: {
            slidesToShow: 1,
          }
        }
      ]
    });
  }

  function initOrDestroySlider() {
    if ($(window).width() <= 767) {
      if (!$('.below767slider').hasClass('slick-initialized')) {
        slidersmallscreen();
      }
    } else {
      if ($('.below767slider').hasClass('slick-initialized')) {
        $('.below767slider').slick('unslick');
      }
    }
  }

  function tabletdualsliderinitOrDestroySlider() {
    if ($(window).width() <= 991) {
      if (!$('.below991slider').hasClass('slick-initialized')) {
        tabletdualslider();
      }
    } else {
      if ($('.below991slider').hasClass('slick-initialized')) {
        $('.below991slider').slick('unslick');
      }
    }
  }

  // Initialize on document ready if needed
  initOrDestroySlider();
  tabletdualsliderinitOrDestroySlider();

  // Recheck on window resize
  $(window).resize(function () {
    tabletdualsliderinitOrDestroySlider();
    initOrDestroySlider();
  });
});


// Developer 2 Starts

// Date and Time Picker

var app = angular.module("dateTimeApp", []);

app.controller("dateTimeCtrl", function ($scope) {
  var ctrl = this;

  ctrl.selected_date = new Date();
  ctrl.selected_date.setHours(10);
  ctrl.selected_date.setMinutes(0);

  ctrl.updateDate = function (newdate) {
    // Do something with the returned date here.

    console.log(newdate);
  };
});

// Date Picker
app.directive("datePicker", function ($timeout, $window) {
  return {
    restrict: "AE",
    scope: {
      selecteddate: "=",
      updatefn: "&",
      open: "=",
      datepickerTitle: "@",
      customMessage: "@",
      picktime: "@",
      pickdate: "@",
      pickpast: "=",
      mondayfirst: "@",
    },
    transclude: true,
    link: function (scope, element, attrs, ctrl, transclude) {
      transclude(scope, function (clone, scope) {
        element.append(clone);
      });

      if (!scope.selecteddate) {
        scope.selecteddate = new Date();
      }

      if (attrs.datepickerTitle) {
        scope.datepicker_title = attrs.datepickerTitle;
      }

      scope.days = [
        { long: "Sunday", short: "Sun" },
        { long: "Monday", short: "Mon" },
        { long: "Tuesday", short: "Tue" },
        { long: "Wednesday", short: "Wed" },
        { long: "Thursday", short: "Thu" },
        { long: "Friday", short: "Fri" },
        { long: "Saturday", short: "Sat" },
      ];
      if (scope.mondayfirst == "true") {
        var sunday = scope.days[0];
        scope.days.shift();
        scope.days.push(sunday);
      }

      scope.monthNames = [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December",
      ];

      function getSelected() {
        if (scope.currentViewDate.getMonth() == scope.localdate.getMonth()) {
          if (
            scope.currentViewDate.getFullYear() == scope.localdate.getFullYear()
          ) {
            for (var number in scope.month) {
              if (scope.month[number].daydate == scope.localdate.getDate()) {
                scope.month[number].selected = true;
                if (scope.mondayfirst == "true") {
                  if (parseInt(number) === 0) {
                    number = 6;
                  } else {
                    number = number - 1;
                  }
                }
                scope.selectedDay =
                  scope.days[scope.month[number].dayname].long;
              }
            }
          }
        }
      }

      function getDaysInMonth() {
        var month = scope.currentViewDate.getMonth();
        var date = new Date(scope.currentViewDate.getFullYear(), month, 1);
        var days = [];
        var today = new Date();
        while (date.getMonth() === month) {
          var showday = true;
          if (!scope.pickpast && date < today) {
            showday = false;
          }
          if (
            today.getDate() == date.getDate() &&
            today.getYear() == date.getYear() &&
            today.getMonth() == date.getMonth()
          ) {
            showday = true;
          }
          var day = new Date(date);
          var dayname = day.getDay();
          var daydate = day.getDate();
          days.push({ dayname: dayname, daydate: daydate, showday: showday });
          date.setDate(date.getDate() + 1);
        }
        scope.month = days;
      }

      function initializeDate() {
        scope.currentViewDate = new Date(scope.localdate);
        scope.currentMonthName = function () {
          return scope.monthNames[scope.currentViewDate.getMonth()];
        };
        getDaysInMonth();
        getSelected();
      }

      // Takes selected time and date and combines them into a date object
      function getDateAndTime(localdate) {
        var time = scope.time.split(":");
        if (scope.timeframe == "am" && time[0] == "12") {
          time[0] = 0;
        } else if (scope.timeframe == "pm" && time[0] !== "12") {
          time[0] = parseInt(time[0]) + 12;
        }
        return new Date(
          localdate.getFullYear(),
          localdate.getMonth(),
          localdate.getDate(),
          time[0],
          time[1]
        );
      }

      // Convert to UTC to account for different time zones
      function convertToUTC(localdate) {
        var date_obj = getDateAndTime(localdate);
        var utcdate = new Date(
          date_obj.getUTCFullYear(),
          date_obj.getUTCMonth(),
          date_obj.getUTCDate(),
          date_obj.getUTCHours(),
          date_obj.getUTCMinutes()
        );
        return utcdate;
      }
      // Convert from UTC to account for different time zones
      function convertFromUTC(utcdate) {
        localdate = new Date(utcdate);
        return localdate;
      }

      // Returns the format of time desired for the scheduler, Also I set the am/pm
      function formatAMPM(date) {
        var hours = date.getHours();
        var minutes = date.getMinutes();
        hours >= 12 ? scope.changetime("pm") : scope.changetime("am");
        hours = hours % 12;
        hours = hours ? hours : 12; // the hour '0' should be '12'
        minutes = minutes < 10 ? "0" + minutes : minutes;
        var strTime = hours + ":" + minutes;
        return strTime;
      }

      scope.$watch("open", function () {
        if (scope.selecteddate !== undefined && scope.selecteddate !== null) {
          scope.localdate = convertFromUTC(scope.selecteddate);
        } else {
          scope.localdate = new Date();
          scope.localdate.setMinutes(
            Math.round(scope.localdate.getMinutes() / 60) * 30
          );
        }
        scope.time = formatAMPM(scope.localdate);
        scope.setTimeBar(scope.localdate);
        initializeDate();
        scope.updateInputTime();
      });

      scope.selectDate = function (day) {
        if (scope.pickdate == "true" && day.showday) {
          for (var number in scope.month) {
            var item = scope.month[number];
            if (item.selected === true) {
              item.selected = false;
            }
          }
          day.selected = true;
          scope.selectedDay = scope.days[day.dayname].long;
          scope.localdate = new Date(
            scope.currentViewDate.getFullYear(),
            scope.currentViewDate.getMonth(),
            day.daydate
          );
          initializeDate(scope.localdate);
          scope.updateDate();
        }
      };

      scope.updateDate = function () {
        if (scope.localdate) {
          var newdate = getDateAndTime(scope.localdate);
          scope.updatefn({ newdate: newdate });
        }
      };

      scope.moveForward = function () {
        scope.currentViewDate.setMonth(scope.currentViewDate.getMonth() + 1);
        if (scope.currentViewDate.getMonth() == 12) {
          scope.currentViewDate.setDate(
            scope.currentViewDate.getFullYear() + 1,
            0,
            1
          );
        }
        getDaysInMonth();
        getSelected();
      };

      scope.moveBack = function () {
        scope.currentViewDate.setMonth(scope.currentViewDate.getMonth() - 1);
        if (scope.currentViewDate.getMonth() == -1) {
          scope.currentViewDate.setDate(
            scope.currentViewDate.getFullYear() - 1,
            0,
            1
          );
        }
        getDaysInMonth();
        getSelected();
      };

      scope.calcOffset = function (day, index) {
        if (index === 0) {
          var offset = day.dayname * 14.2857142 + "%";
          if (scope.mondayfirst == "true") {
            offset = (day.dayname - 1) * 14.2857142 + "%";
          }
          return offset;
        }
      };

      ///////////////////////////////////////////////
      // Check size of parent element, apply class //
      ///////////////////////////////////////////////
      scope.checkWidth = function (apply) {
        var parent_width = element.parent().width();
        if (parent_width < 620) {
          scope.compact = true;
        } else {
          scope.compact = false;
        }
        if (apply) {
          scope.$apply();
        }
      };
      scope.checkWidth(false);

      //////////////////////
      // Time Picker Code //
      //////////////////////
      if (scope.picktime) {
        var currenttime;
        var timeline;
        var timeline_width;
        var timeline_container;
        var sectionlength;

        scope.getHours = function () {
          var hours = new Array(11);
          return hours;
        };

        scope.time = "12:00";
        scope.hour = 12;
        scope.minutes = 0;
        scope.currentoffset = 0;

        scope.timeframe = "am";

        scope.changetime = function (time) {
          scope.timeframe = time;
          scope.updateDate();
          scope.updateInputTime();
        };

        scope.edittime = {
          digits: [],
        };

        scope.updateInputTime = function () {
          scope.edittime.input = scope.time + " " + scope.timeframe;
          scope.edittime.formatted = scope.edittime.input;
        };

        scope.updateInputTime();

        function checkValidTime(number) {
          validity = true;
          switch (scope.edittime.digits.length) {
            case 0:
              if (number === 0) {
                validity = false;
              }
              break;
            case 1:
              if (number > 5) {
                validity = false;
              } else {
                validity = true;
              }
              break;
            case 2:
              validity = true;
              break;
            case 3:
              if (scope.edittime.digits[0] > 1) {
                validity = false;
              } else if (scope.edittime.digits[1] > 2) {
                validity = false;
              } else if (scope.edittime.digits[2] > 5) {
                validity = false;
              } else {
                validity = true;
              }
              break;
            case 4:
              validity = false;
              break;
          }
          return validity;
        }

        function formatTime() {
          var time = "";
          if (scope.edittime.digits.length == 1) {
            time = "--:-" + scope.edittime.digits[0];
          } else if (scope.edittime.digits.length == 2) {
            time = "--:" + scope.edittime.digits[0] + scope.edittime.digits[1];
          } else if (scope.edittime.digits.length == 3) {
            time =
              "-" +
              scope.edittime.digits[0] +
              ":" +
              scope.edittime.digits[1] +
              scope.edittime.digits[2];
          } else if (scope.edittime.digits.length == 4) {
            time =
              scope.edittime.digits[0] +
              scope.edittime.digits[1].toString() +
              ":" +
              scope.edittime.digits[2] +
              scope.edittime.digits[3];
            console.log(time);
          }
          return time + " " + scope.timeframe;
        }

        scope.changeInputTime = function (event) {
          var numbers = {
            48: 0,
            49: 1,
            50: 2,
            51: 3,
            52: 4,
            53: 5,
            54: 6,
            55: 7,
            56: 8,
            57: 9,
          };
          if (numbers[event.which] !== undefined) {
            if (checkValidTime(numbers[event.which])) {
              scope.edittime.digits.push(numbers[event.which]);
              console.log(scope.edittime.digits);
              scope.time_input = formatTime();
              scope.time = numbers[event.which] + ":00";
              scope.updateDate();
              scope.setTimeBar();
            }
          } else if (event.which == 65) {
            scope.timeframe = "am";
            scope.time_input = scope.time + " " + scope.timeframe;
          } else if (event.which == 80) {
            scope.timeframe = "pm";
            scope.time_input = scope.time + " " + scope.timeframe;
          } else if (event.which == 8) {
            scope.edittime.digits.pop();
            scope.time_input = formatTime();
            console.log(scope.edittime.digits);
          }
          scope.edittime.formatted = scope.time_input;
          // scope.edittime.input = formatted;
        };

        var pad2 = function (number) {
          return (number < 10 ? "0" : "") + number;
        };

        scope.moving = false;
        scope.offsetx = 0;
        scope.totaloffset = 0;
        scope.initializeTimepicker = function () {
          currenttime = $(".current-time");
          timeline = $(".timeline");
          if (timeline.length > 0) {
            timeline_width = timeline[0].offsetWidth;
          }
          timeline_container = $(".timeline-container");
          sectionlength = timeline_width / 24 / 6;
        };

        angular.element($window).on("resize", function () {
          scope.initializeTimepicker();
          if (timeline.length > 0) {
            timeline_width = timeline[0].offsetWidth;
          }
          sectionlength = timeline_width / 24;
          scope.checkWidth(true);
        });

        scope.setTimeBar = function (date) {
          currenttime = $(".current-time");
          var timeline_width = $(".timeline")[0].offsetWidth;
          var hours = scope.time.split(":")[0];
          if (hours == 12) {
            hours = 0;
          }
          var minutes = scope.time.split(":")[1];
          var minutes_offset = (minutes / 60) * (timeline_width / 12);
          var hours_offset = (hours / 12) * timeline_width;
          scope.currentoffset = parseInt(hours_offset + minutes_offset - 1);
          currenttime.css({
            transition: "transform 0.4s ease",
            transform: "translateX(" + scope.currentoffset + "px)",
          });
        };

        scope.getTime = function () {
          // get hours
          var percenttime = (scope.currentoffset + 1) / timeline_width;
          var hour = Math.floor(percenttime * 12);
          var percentminutes = percenttime * 12 - hour;
          var minutes = Math.round((percentminutes * 60) / 5) * 5;
          if (hour === 0) {
            hour = 12;
          }
          if (minutes == 60) {
            hour += 1;
            minutes = 0;
          }

          scope.time = hour + ":" + pad2(minutes);
          scope.updateInputTime();
          scope.updateDate();
        };

        var initialized = false;

        element.on("touchstart", function () {
          if (!initialized) {
            element
              .find(".timeline-container")
              .on("touchstart", function (event) {
                scope.timeSelectStart(event);
              });
            initialized = true;
          }
        });

        scope.timeSelectStart = function (event) {
          scope.initializeTimepicker();
          var timepicker_container = element.find(
            ".timepicker-container-inner"
          );
          var timepicker_offset = timepicker_container.offset().left;
          if (event.type == "mousedown") {
            scope.xinitial = event.clientX;
          } else if (event.type == "touchstart") {
            scope.xinitial = event.originalEvent.touches[0].clientX;
          }
          scope.moving = true;
          scope.currentoffset =
            scope.xinitial - timepicker_container.offset().left;
          scope.totaloffset =
            scope.xinitial - timepicker_container.offset().left;
          console.log(timepicker_container.width());
          if (scope.currentoffset < 0) {
            scope.currentoffset = 0;
          } else if (scope.currentoffset > timepicker_container.width()) {
            scope.currentoffset = timepicker_container.width();
          }
          currenttime.css({
            transform: "translateX(" + scope.currentoffset + "px)",
            transition: "none",
            cursor: "ew-resize",
          });
          scope.getTime();
        };

        angular.element($window).on("mousemove touchmove", function (event) {
          if (scope.moving === true) {
            event.preventDefault();
            if (event.type == "mousemove") {
              scope.offsetx = event.clientX - scope.xinitial;
            } else if (event.type == "touchmove") {
              scope.offsetx =
                event.originalEvent.touches[0].clientX - scope.xinitial;
            }
            var movex = scope.offsetx + scope.totaloffset;
            if (movex >= 0 && movex <= timeline_width) {
              currenttime.css({
                transform: "translateX(" + movex + "px)",
              });
              scope.currentoffset = movex;
            } else if (movex < 0) {
              currenttime.css({
                transform: "translateX(0)",
              });
              scope.currentoffset = 0;
            } else {
              currenttime.css({
                transform: "translateX(" + timeline_width + "px)",
              });
              scope.currentoffset = timeline_width;
            }
            scope.getTime();
            scope.$apply();
          }
        });

        angular.element($window).on("mouseup touchend", function (event) {
          if (scope.moving) {
            // var roundsection = Math.round(scope.currentoffset / sectionlength);
            // var newoffset = roundsection * sectionlength;
            // currenttime.css({
            //     transition: 'transform 0.25s ease',
            //     transform: 'translateX(' + (newoffset - 1) + 'px)',
            //     cursor: 'pointer',
            // });
            // scope.currentoffset = newoffset;
            // scope.totaloffset = scope.currentoffset;
            // $timeout(function () {
            //     scope.getTime();
            // }, 250);
          }
          scope.moving = false;
        });

        scope.adjustTime = function (direction) {
          event.preventDefault();
          scope.initializeTimepicker();
          var newoffset;
          if (direction == "decrease") {
            newoffset = scope.currentoffset - sectionlength;
          } else if (direction == "increase") {
            newoffset = scope.currentoffset + sectionlength;
          }
          if (newoffset < 0 || newoffset > timeline_width) {
            if (newoffset < 0) {
              newoffset = timeline_width - sectionlength;
            } else if (newoffset > timeline_width) {
              newoffset = 0 + sectionlength;
            }
            if (scope.timeframe == "am") {
              scope.timeframe = "pm";
            } else if (scope.timeframe == "pm") {
              scope.timeframe = "am";
            }
          }
          currenttime.css({
            transition: "transform 0.4s ease",
            transform: "translateX(" + (newoffset - 1) + "px)",
          });
          scope.currentoffset = newoffset;
          scope.totaloffset = scope.currentoffset;
          scope.getTime();
        };
      }

      // End Timepicker Code //
    },
  };
});

var buttonPlus = $(".addition");
var buttonMinus = $(".subtract");

var incrementPlus = buttonPlus.click(function () {
  var $n = $(this).parent(".counter-buttons").find(".cart-counter");
  $n.val(Number($n.val()) + 1);
});

var incrementMinus = buttonMinus.click(function () {
  var $n = $(this).parent(".counter-buttons").find(".cart-counter");
  var amount = Number($n.val());
  if (amount > 0) {
    $n.val(amount - 1);
  }
});

// Choose Payment Method
$(function () {
  $("input[name='radio']").click(function () {
    if ($(".tabbyshow").is(":checked")) {
      $("#tabby").slideDown();
      $(".card-box").slideUp();
    } else {
      $("#tabby").slideUp();
      $(".card-box").slideDown();
    }
  });
});

//  Customize Kandora Landing Page

$(document).ready(function () {
  $(".customize-section-box .customize-option:first").slideDown('slow');

  $(".customize-section-box .heading").click(function () {
    if ($(this).attr("disabled")) {
      return;
    }

    $(".customize-section-box .heading").removeClass("active");
    $(this).addClass("active");
    $(this).next().slideToggle(500);
    $(".customize-option").not($(this).next()).slideUp(500);
  });
});

$(document).ready(function () {
  $(".customize-section-box .images-option ul li").click(function () {
    $("li").removeClass("active");
    $(this).addClass("active");
  });
});

$(function () {
  $(".select-style-btn").click(function () {
    $(".kandora-image-select").slideDown();
    $(".kandora-style-slider").slideUp();
  });
});


// Request a Tailor Visit Step 1 of 2

jQuery(document).ready(function () {
  $(".requestatailorvisit-bg #step2").css("display", "none");
  $(".step1-hide").on("click", function () {
    $(".requestatailorvisit-bg #step1").css("display", "none");
    $(".requestatailorvisit-bg #step2").css("display", "flex");
  });
});

const input = document.querySelector(".area");
const suggestions = document.querySelector(".suggestions ul");

const fruit = [
  "Abu Dhabi",
  "Ajmān",
  "Al Ain",
  "Al Awdah",
  "Baqal",
  "Bidiyah",
  "Daftah",
  "Dhadna",
  "Fujairah",
  "Kalba",
  "Kawr Fakkān",
  "Ras al-Khaimah",
  "Umm al-Qaiwain",
  "Quţūf",
  "Ruwais",
  "Sharjah",
  "Sila",
];

function search(str) {
  let results = [];
  const val = str.toLowerCase();

  for (i = 0; i < fruit.length; i++) {
    if (fruit[i].toLowerCase().indexOf(val) > -1) {
      results.push(fruit[i]);
    }
  }

  return results;
}

function searchHandler(e) {
  const inputVal = e.currentTarget.value;
  let results = [];
  if (inputVal.length > 0) {
    results = search(inputVal);
  }
  showSuggestions(results, inputVal);
}

function showSuggestions(results, inputVal) {
  suggestions.innerHTML = "";

  if (results.length > 0) {
    for (i = 0; i < results.length; i++) {
      let item = results[i];
      // Highlights only the first match
      // TODO: highlight all matches
      const match = item.match(new RegExp(inputVal, "i"));
      item = item.replace(match[0], `<strong>${match[0]}</strong>`);
      suggestions.innerHTML += `<li>${item}</li>`;
    }
    suggestions.classList.add("has-suggestions");
  } else {
    results = [];
    suggestions.innerHTML = "";
    suggestions.classList.remove("has-suggestions");
  }
}

function useSuggestion(e) {
  input.value = e.target.innerText;
  input.focus();
  suggestions.innerHTML = "";
  suggestions.classList.remove("has-suggestions");
}

input?.addEventListener("keyup", searchHandler);
suggestions.addEventListener("click", useSuggestion);



	 