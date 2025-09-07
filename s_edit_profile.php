<?php
include("includes/s_header.php");

// ✅ FIX: Adjust path depending on your structure
include("includes/db.php"); // if s_edit_profile.php is in root
// include("../includes/db.php"); // if inside /supplier folder

$supplier_id = $_SESSION['supplier_id'];
$success = $error = "";

// Fetch existing data
$result = $conn->query("SELECT * FROM supplier WHERE supplier_id = $supplier_id");
$user = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name  = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);

    // Profile picture (optional upload)
    $profile_sql = "";
    if (!empty($_FILES['profile']['name'])) {
        $target_dir = "assets/uploads/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $file_name = time() . "_" . basename($_FILES["profile"]["name"]);
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["profile"]["tmp_name"], $target_file)) {
            $profile_sql = ", profile_pic='$file_name'";
        }
    }

    $sql = "UPDATE supplier SET name='$name', email='$email' $profile_sql WHERE supplier_id=$supplier_id";

    if ($conn->query($sql)) {
        $_SESSION['name'] = $name;
        $success = "Profile updated successfully!";
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>

<div class="container mt-5">
  <h2>Edit Profile</h2>

  <?php if($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
  <?php if($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

  <form method="post" enctype="multipart/form-data" class="mt-3">
    <div class="mb-3">
      <label class="form-label">Full Name</label>
      <input type="text" name="name" value="<?= $user['name'] ?>" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Email</label>
      <input type="email" name="email" value="<?= $user['email'] ?>" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Profile Picture</label><br>
      <?php if (!empty($user['profile_pic'])): ?>
        <img src="assets/uploads/<?= $user['profile_pic'] ?>" width="80" class="rounded mb-2"><br>
      <?php endif; ?>
      <input type="file" name="profile" class="form-control">
    </div>
    <button type="submit" class="btn btn-warning">Update Profile</button>
  </form>
</div>

<?php include("includes/s_footer.php"); ?>
