<?php
include("config.php");

// Mengambil total record
$totalRecords = $conn->query("SELECT COUNT(*) AS total FROM quiz_questions")->fetch_assoc()['total'];

// Mengambil semua data quiz questions
$sql = "SELECT id, question, option_a, option_b, option_c, option_d, correct_option FROM quiz_questions";
$result = $conn->query($sql);

$questions = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $questions[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Pertanyaan Kuis</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
  <style>
    body {
      font-family: 'Arial', sans-serif;
      margin: 20px;
    }
    .card-panel {
      padding: 10px;
      border-radius: 3px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    #result {
      margin-top: 20px;
    }
    .editButton, .deleteButton, .addButton {
      cursor: pointer;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 4px;
      padding: 5px 10px;
      margin: 5px;
      transition: background-color 0.3s ease;
    }
    .deleteButton {
      background-color: #f44336;
    }
    .editButton:hover {
      background-color: #45a049;
    }
    .deleteButton:hover {
      background-color: #e53935;
    }
    .addButton {
      background-color: #2196F3;
    }
    .addButton:hover {
      background-color: #1976D2;
    }
  </style>
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</head>
<body>
  <div class="card-panel teal lighten-2 white-text">
    <h5 class="center-align">Total Kuis</h5>
    <p id="totalRecordsDisplay" class="center-align" style="font-size: 24px; font-weight: bold;"><?php echo $totalRecords; ?></p>
  </div>

  <button class="waves-effect waves-light btn" onclick="openAddModal()">Tambah Kuis Baru</button>
  <button class="waves-effect waves-light btn" onclick="copyURL()">Copy URL</button>
  <button class="waves-effect waves-light btn" onclick="copyURLAPI()">Copy URL API - get_question</button>

  <div id="result">
    <?php if (!empty($questions)) : ?>
      <table class="striped">
        <thead>
          <tr>
            <th>No</th>
            <th>Pertanyaan</th>
            <th>Pilihan A</th>
            <th>Pilihan B</th>
            <th>Pilihan C</th>
            <th>Pilihan D</th>
            <th>Pilihan Jawaban</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          $no = 1;
          foreach ($questions as $row) : ?>
            <tr>
              <td><?php echo $no++; ?></td>
              <td><?php echo $row['question']; ?></td>
              <td><?php echo $row['option_a']; ?></td>
              <td><?php echo $row['option_b']; ?></td>
              <td><?php echo $row['option_c']; ?></td>
              <td><?php echo $row['option_d']; ?></td>
              <td><?php echo $row['correct_option']; ?></td>
              <td>
                <button class="editButton" onclick="openEditModal(<?php echo $row['id']; ?>, '<?php echo $row['question']; ?>', '<?php echo $row['option_a']; ?>', '<?php echo $row['option_b']; ?>', '<?php echo $row['option_c']; ?>', '<?php echo $row['option_d']; ?>', '<?php echo $row['correct_option']; ?>')">Edit</button>
                <button class="deleteButton" onclick="deleteQuestion(<?php echo $row['id']; ?>)">Hapus</button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else : ?>
      <p>Tidak ada Kuis</p>
    <?php endif; ?>
  </div>

  <!-- Modal Structure -->
  <div id="editModal" class="modal">
    <div class="modal-content">
      <h4>Edit Kuis</h4>
	  <br>
      <form id="editForm">
        <input type="hidden" id="editId" name="id">
        <div class="input-field">
          <input type="text" id="editQuestion" name="question" required>
          <label for="editQuestion">Pertanyaan</label>
        </div>
        <div class="input-field">
          <input type="text" id="editOptionA" name="option_a" required>
          <label for="editOptionA">Pilihan A</label>
        </div>
        <div class="input-field">
          <input type="text" id="editOptionB" name="option_b" required>
          <label for="editOptionB">Pilihan B</label>
        </div>
        <div class="input-field">
          <input type="text" id="editOptionC" name="option_c" required>
          <label for="editOptionC">Pilihan C</label>
        </div>
        <div class="input-field">
          <input type="text" id="editOptionD" name="option_d" required>
          <label for="editOptionD">Pilihan D</label>
        </div>
        <div class="input-field">
          <select id="editCorrectOption" name="correct_option" required>
            <option value="" disabled selected>Pilih Jawaban Benar</option>
            <option value="A">A</option>
            <option value="B">B</option>
            <option value="C">C</option>
            <option value="D">D</option>
          </select>
          <label for="editCorrectOption">Jawaban</label>
        </div>
        <button type="submit" class="waves-effect waves-light btn">Update</button>
      </form>
    </div>
  </div>

  <div id="addModal" class="modal">
    <div class="modal-content">
      <h4>Tambah Kuis</h4>
	  <br>
      <form id="addForm">
        <div class="input-field">
          <input type="text" id="addQuestion" name="question" required>
          <label for="addQuestion">Pertanyaan</label>
        </div>
        <div class="input-field">
          <input type="text" id="addOptionA" name="option_a" required>
          <label for="addOptionA">Pilihan A</label>
        </div>
        <div class="input-field">
          <input type="text" id="addOptionB" name="option_b" required>
          <label for="addOptionB">Pilihan B</label>
        </div>
        <div class="input-field">
          <input type="text" id="addOptionC" name="option_c" required>
          <label for="addOptionC">Pilihan C</label>
        </div>
        <div class="input-field">
          <input type="text" id="addOptionD" name="option_d" required>
          <label for="addOptionD">Pilihan D</label>
        </div>
        <div class="input-field">
          <select id="addCorrectOption" name="correct_option" required>
            <option value="" disabled selected>Pilih Jawaban Benar</option>
            <option value="A">A</option>
            <option value="B">B</option>
            <option value="C">C</option>
            <option value="D">D</option>
          </select>
          <label for="addCorrectOption">Jawaban</label>
        </div>
        <button type="submit" class="waves-effect waves-light btn">Tambah</button>
      </form>
    </div>
  </div>

  <script>
    $(document).ready(function () {
      $('.modal').modal();
      $('select').formSelect();
      M.updateTextFields();

      $('#editForm').submit(function (e) {
        e.preventDefault();
        $.ajax({
          type: 'POST',
          url: '',
          data: {
            action: 'update',
            id: $('#editId').val(),
            question: $('#editQuestion').val(),
            option_a: $('#editOptionA').val(),
            option_b: $('#editOptionB').val(),
            option_c: $('#editOptionC').val(),
            option_d: $('#editOptionD').val(),
            correct_option: $('#editCorrectOption').val()
          },
          success: function (response) {
            M.toast({html: 'kuis berhasil diupdate'});
            setTimeout(() => {
              location.reload();
            }, 1500);
          },
          error: function () {
            M.toast({html: 'Error updating question'});
          }
        });
      });

      $('#addForm').submit(function (e) {
        e.preventDefault();
        $.ajax({
          type: 'POST',
          url: '',
          data: {
            action: 'add',
            question: $('#addQuestion').val(),
            option_a: $('#addOptionA').val(),
            option_b: $('#addOptionB').val(),
            option_c: $('#addOptionC').val(),
            option_d: $('#addOptionD').val(),
            correct_option: $('#addCorrectOption').val()
          },
          success: function (response) {
            M.toast({html: 'Pertanyaan berhasil ditambahkan'});
            setTimeout(() => {
              location.reload();
            }, 1500);
          },
          error: function () {
            M.toast({html: 'Error adding question'});
          }
        });
      });
    });

    function openEditModal(id, question, option_a, option_b, option_c, option_d, correct_option) {
      $('#editId').val(id);
      $('#editQuestion').val(question);
      $('#editOptionA').val(option_a);
      $('#editOptionB').val(option_b);
      $('#editOptionC').val(option_c);
      $('#editOptionD').val(option_d);
      $('#editCorrectOption').val(correct_option);
      M.updateTextFields();
      $('select').formSelect();
      $('#editModal').modal('open');
    }

    function openAddModal() {
      $('#addForm')[0].reset();
      M.updateTextFields();
      $('select').formSelect();
      $('#addModal').modal('open');
    }

    function deleteQuestion(id) {
      $.ajax({
        type: 'POST',
        url: '',
        data: { action: 'delete', id: id },
        success: function(response) {
          M.toast({html: 'kuis berhasil dihapus'});
          setTimeout(() => {
            location.reload();
          }, 1500);
        },
        error: function() {
          M.toast({html: 'Error deleting question'});
        }
      });
    }
    
    function copyToClipboard(text) {
      const tempInput = document.createElement('input');
      tempInput.value = text;
      document.body.appendChild(tempInput);
      tempInput.select();
      document.execCommand('copy');
      document.body.removeChild(tempInput);
    }

    function copyURL() {
      const domainURL = window.location.origin;
      copyToClipboard(domainURL);
      M.toast({html: 'URL berhasil disalin'});
    }

    function copyURLAPI() {
      const domainURL = window.location.origin + '/quiz/get_question.php';
      copyToClipboard(domainURL);
      M.toast({html: 'URL API berhasil disalin'});
    }
  </script>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if ($_POST['action'] === 'add') {
    $question = $_POST['question'];
    $option_a = $_POST['option_a'];
    $option_b = $_POST['option_b'];
    $option_c = $_POST['option_c'];
    $option_d = $_POST['option_d'];
    $correct_option = $_POST['correct_option'];

    $sql = "INSERT INTO quiz_questions (question, option_a, option_b, option_c, option_d, correct_option) VALUES ('$question', '$option_a', '$option_b', '$option_c', '$option_d', '$correct_option')";
    if ($conn->query($sql) === TRUE) {
      echo "Kuis Baru Berhasil di tambah";
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }
  } elseif ($_POST['action'] === 'update') {
    $id = $_POST['id'];
    $question = $_POST['question'];
    $option_a = $_POST['option_a'];
    $option_b = $_POST['option_b'];
    $option_c = $_POST['option_c'];
    $option_d = $_POST['option_d'];
    $correct_option = $_POST['correct_option'];

    $sql = "UPDATE quiz_questions SET question='$question', option_a='$option_a', option_b='$option_b', option_c='$option_c', option_d='$option_d', correct_option='$correct_option' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
      echo "Kuis Berhasil di Update";
    } else {
      echo "Error updating record: " . $conn->error;
    }
  } elseif ($_POST['action'] === 'delete') {
    $id = $_POST['id'];
    $sql = "DELETE FROM quiz_questions WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
      echo "Kuis Berhasil di Hapus";
    } else {
      echo "Error deleting record: " . $conn->error;
    }
  }
}
$conn->close();
?>