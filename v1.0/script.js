  var submitted = document.getElementById("form"); //Get the form

  var check = function(ev){
    var isbn_check = document.getElementById('isbn');
    var author_check = document.getElementById('author');
    var year_check = document.getElementById('year');
    if(isbn_check.checkValidity() == false || author_check.checkValidity() == false || year_check.checkValidity() == false){
      ev.preventDefault(); //Prevent the form from submitting.
    }
  }
  submitted.addEventListener("submit", check);
