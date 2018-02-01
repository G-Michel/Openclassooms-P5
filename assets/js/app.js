// app.js

// JS is equivalent to the normal "bootstrap" package
// no need to set this to a variable, just require it
import 'bootstrap';
import './datepicker';



$(function() {

  // Datepicker date obs
  $('#observe_bird_moment_dateObs').datetimepicker();

  // Choose file picture
  bs_input_file();

  // Slider number birds
  if (document.getElementById("observe_bird_detail_bird_birdNumber")) {
    var slider = document.getElementById("observe_bird_detail_bird_birdNumber");
    var output = document.getElementById("birdNumber");
    output.innerHTML = 'Nombre d\'oiseaux : ' + slider.value; // Display the default slider value

    // Update the current slider value (each time you drag the slider handle)
    slider.oninput = function() {
        output.innerHTML = 'Nombre d\'oiseaux : ' + this.value;
    }
  }

});

// Function Choose file picture
function bs_input_file() {
  $(".input-file").before(
    function() {
      if ( $(this).prev().hasClass('form-control-file') ) {
        var element = $("#observe_bird_detail_picture_file");
        element.addClass('d-none')
        element.change(function(){
          element.next(element).find('input').val((element.val()).split('\\').pop());
        });
        $(this).find("button.btn-choose").click(function(){
          element.click();
        });
        // $(this).find("button.btn-reset").click(function(){
        //   element.val(null);
        //   $(this).parents(".input-file").find('input').val('');
        // });
        $(this).find('input').css("cursor","pointer");
        $(this).find('input').mousedown(function() {
          $(this).parents('.input-file').prev().click();
          return false;
        });
        return element;
      }
    }
  );
}

// Handling the modal confirmation message.
$(document).on('submit', 'form[data-confirmationDelete]', function (event) {
  var $form = $(this),
      $confirm = $('#deleteConfirmationModal');

  if ($confirm.data('result') !== 'yes') {
      //cancel submit event
      event.preventDefault();
      // console.log($confirm.modal('click', '#btnYes'))

      $confirm
          .off('click', '#btnYes')
          .on('click', '#btnYes', function () {
              $confirm.data('result', 'yes');
              $form.find('input[type="submit"]').attr('disabled', 'disabled');
              $form.submit();
          })
          .modal('show');
  }
});

// Handling the modal confirmation message.
$(document).on('submit', 'form[data-confirmationCheck]', function (event) {
  var $form = $(this),
      $confirm = $('#checkConfirmationModal');

  if ($confirm.data('result') !== 'yes') {
      //cancel submit event
      event.preventDefault();

      $confirm
          .off('click', '#btnYes')
          .on('click', '#btnYes', function () {
              var $check = $('input[name=customRadio]:checked').val();
              $confirm.data('result', 'yes');
              $form.find('#statusCode').attr('value', $check);
              $form.find('input[type="submit"]').attr('disabled', 'disabled');
              $form.submit();
          })
          .modal('show');
  }
});




  $('.dropdown-event-listener').on('hidden.bs.dropdown', function () {
    var result =$.ajax({
      url : symfoUrlRoute,
      type : 'GET',
      data : 'seen=true',
      dataType: 'html'
      });
  console.log(result);
    
  });



