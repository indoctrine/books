<?php
  $servername = "localhost";
  $username = "books";
  $password = "abc123";
  $dbname = "bookshelf";

  $conn = new mysqli ($servername, $username, $password, $dbname);

  if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
  }

  function get_genre($conn)
  {
   $output = array();

   $result = mysqli_query( $conn, "SELECT genre_id, genre FROM genre ORDER by genre" );
   if (mysqli_num_rows($result) > 0){
     while( $row = mysqli_fetch_assoc( $result ) )
     {
      $output[$row["genre_id"]] = $row["genre"];
     }
   }
   return($output);
  }

function get_category($conn)
{
   $output = array();

   $result = mysqli_query( $conn, "SELECT category_id, category FROM category ORDER by category_id" );
   if (mysqli_num_rows($result) > 0){
     while( $row = mysqli_fetch_assoc( $result ) )
     {
      $output[$row["category_id"]] = $row["category"];
     }
   }
   return($output);
  }

function create($conn)
{
  $sql = "INSERT INTO books(title, author, year, isbn, category_id, genre_id, book_condition, format, page_count, location)
  VALUES (";

  $sql .= sprintf( "'%s', '%s', %d, %s, %d, %d, '%s', '%s', %d, '%s' )",
    $_POST['title'],
    $_POST['author'],
    $_POST['year'],
    isset($_POST['isbn']) && strlen($_POST['isbn']) > 0 ? $_POST['isbn'] : "NULL",
    $_POST['category'],
    $_POST['genre'],
    $_POST['book_condition'],
    $_POST['format'],
    $_POST['page_count'],
    $_POST['location']);


  if (mysqli_query($conn, $sql)) {
      echo "New record created successfully";
  }

  else {
      echo "Error: " . $sql . "<br>" . mysqli_error($conn);
  }
}

function delete_row($conn, $book_id)
{
  $sql = "DELETE from books WHERE book_id = " . $book_id;

  if(mysqli_query($conn, $sql)) {
    echo "Successfully deleted";
  }

  else {
      echo "Error: " . $sql . "<br>" . mysqli_error($conn);
  }
}
?>
