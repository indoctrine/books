<!doctype html>
<html>
  <head>
    <title>Bookshelf</title>
    <link rel="stylesheet" type="text/css" href="bookstyles.css" media="screen" />
  </head>
  <body>
    <?php
      include "sql.php";
      if(isset($_POST["action"])){
        if($_POST["action"] == "create"){
          create($conn);
        }

        if($_POST["action"] == "delete_row"){
          delete_row($conn, $_POST["book_id"]);
        }
      }
    ?>
    <form method="post" id="form">

      <h2> Add a Book </h2>

      <input type="hidden" name="action" value="create">

      <label for="title">Title:</label>
      <input type="text" required name="title" id="title" autofocus /><br />

      <label for="author">Author:</label>
      <input type="text" required name="author" id="author" pattern="[^0-9]+"/><br />

      <label for="year">Year:</label>
      <input type="number" name="year" id="year" min="1900" max="2999"/><br />

      <label for="isbn">ISBN:</label>
      <input type="text" name="isbn" id="isbn" pattern="\d{10}(\d{3})?" placeholder="XXXXXXXXXXXXX"/><br />

      <label for="format">Format:</label>
      <select name="format" id="format">
        <option value="Hardcover">Hardcover</option>
        <option value="Paperback">Paperback</option>
        <option value="Digital">Digital</option>
        <option value="Magazine">Magazine</option>
      </select><br />

      <label for="category">Category:</label>
      <select name="category" id="category">
        <option value="0">None</option>
        <?php
          $category_list = get_category($conn);
          foreach($category_list as $key => $category){
            echo "<option value=\"" . $key . "\">" . $category . "</option> \n"; //Output each category using its ID.
          }
        ?>
      </select>

      <label for="genre">Genre:</label>
      <select name="genre" id="genre">
        <option value="0">None</option>
        <?php
          $genre_list = get_genre($conn);
          foreach($genre_list as $key => $genres){
            echo "<option value=\"" . $key . "\">" . $genres . "</option> \n"; //Output each genre using its ID.
          }
        ?>
      </select>

      <label for="book_condition">Book Condition:</label>
      <select id="book_condition" name="book_condition">
        <option value="Not Applicable">N/A</option>
        <option value="Excellent">Excellent</option>
        <option value="Good">Good</option>
        <option value="Average">Average</option>
        <option value="Bad">Bad</option>
        <option value="Terrible">Terrible</option>
      </select>

      <br />

      <label for="page_count">Page Count:</label>
      <input type="number" name="page_count" id="page_count" min="0" /><br />

      <label for="location">Location:</label>
      <input type="text" name="location" id="location" /><br />

      <label id="errormsg" class="error"></label>

      <button type="submit" value="Add Book">Add Book</button>

      <button type="reset" value="Reset">Reset</button>

    </form>

    <?php
      $bookstable = "SELECT book_id, title, author, year, isbn, book_condition, format, page_count, location,
      genre.genre, category.category FROM books LEFT JOIN genre ON books.genre_id = genre.genre_id
      LEFT JOIN category ON books.category_id = category.category_id"; //Join genre names with IDs, also get everything
      $result = mysqli_query($conn, $bookstable);

      if (mysqli_num_rows($result) > 0)
      {
        // output data of each row
    ?>

        <table class='booktable'>

          <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Year</th>
            <th>ISBN</th>
            <th>Format</th>
            <th>Category</th>
            <th>Genre</th>
            <th>Book Condition</th>
            <th>Page Count</th>
            <th>Location</th>
          </tr>

          <form id="record_deleter" method="post">

          <input type="hidden" name="action" value="delete_row"/>

          <input type="hidden" name="book_id" id="book_id" value="-1"/>

          <?php
            while($row = mysqli_fetch_assoc($result))
            {
          ?>
              <tr>
                <td>
                  <?php echo $row["title"] ?>
                </td>

                <td>
                  <?php echo $row["author"] ?>
                </td>

                <td>
                <?php echo $row["year"] ?>
                </td>

                <td>
                  <?php echo (strlen($row["isbn"]) == 10 ? preg_replace ("/(\d{9})(\d)/", "$1-$2", $row["isbn"]) :  //Ternary to determine ISBN formatting
                  preg_replace ("/(\d{3})(\d)(\d{2})(\d{6})(\d)/", "$1-$2-$3-$4-$5", $row["isbn"])) ?>
                </td>

                <td>
                  <?php echo $row["format"] ?>
                </td>

                <td>
                  <?php echo $row["category"] ?>
                </td>

                <td>
                  <?php echo $row["genre"] ?>
                </td>

                <td>
                  <?php echo $row["book_condition"] ?>
                </td>

                <td>
                  <?php echo $row["page_count"] ?>
                </td>

                <td>
                  <?php echo $row["location"] ?>
                </td>

                <td>
                  <input type="submit" id="book_id" value="X"
                  onClick="document.getElementById('book_id').value='<?php echo $row["book_id"] ?>';this.form.submit()"/>
                </td>
              </tr>
          <?php
            }
          }

            else
            {
              echo "0 results";
            }

            mysqli_close($conn);
          ?>

          </form>
        </table>
        <script>
          var submitted = document.getElementById("form"); //Get the form
          var check = function(ev)
          {
              if(submitted.checkValidity() == false)
              {
                document.getElementById("errormsg").innerHTML = "Please check your inputs.";
                ev.preventDefault(); //Prevent the form from submitting.
              }
              else
              {
                document.getElementById("errormsg").innerHTML = "";
              }
          }
          submitted.addEventListener("submit", check);
        </script>

  </body>
</html>
