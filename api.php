<?php
require_once 'config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$action = $_POST['action'] ?? '';
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

try {
    if ($action === 'search') {
        $q = "%" . ($_POST['q'] ?? '') . "%";
        $stmt = $pdo->prepare("SELECT * FROM courses WHERE course_code LIKE ? OR course_name LIKE ?");
        $stmt->execute([$q, $q]);
        echo json_encode(['status' => 'success', 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
    } 
    
    elseif ($action === 'get_registered' && $role === 'student') {
        $stmt = $pdo->prepare("SELECT c.* FROM courses c JOIN registrations r ON c.id = r.course_id WHERE r.student_id = ?");
        $stmt->execute([$user_id]);
        echo json_encode(['status' => 'success', 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
    }
    elseif ($action === 'register' && $role === 'student') {
        $course_id = (int)$_POST['course_id'];
        $stmt = $pdo->prepare("SELECT id FROM registrations WHERE student_id = ? AND course_id = ?");
        $stmt->execute([$user_id, $course_id]);
        if ($stmt->rowCount() > 0) {
            echo json_encode(['status' => 'warning', 'message' => 'Already registered']);
        } else {
            $pdo->prepare("INSERT INTO registrations (student_id, course_id) VALUES (?, ?)")->execute([$user_id, $course_id]);
            echo json_encode(['status' => 'success', 'message' => 'Registered Successfully']);
        }
    }
    elseif ($action === 'drop' && $role === 'student') {
        $course_id = (int)$_POST['course_id'];
        $pdo->prepare("DELETE FROM registrations WHERE student_id = ? AND course_id = ?")->execute([$user_id, $course_id]);
        echo json_encode(['status' => 'success']);
    }

    elseif ($action === 'add_course' && $role === 'admin') {
        $pdo->prepare("INSERT INTO courses (course_code, course_name, credits) VALUES (?, ?, ?)")
            ->execute([trim($_POST['code']), trim($_POST['name']), (int)$_POST['credits']]);
        echo json_encode(['status' => 'success']);
    }

    elseif ($action === 'update_course' && $role === 'admin') {
        $pdo->prepare("UPDATE courses SET course_code=?, course_name=?, credits=? WHERE id=?")
            ->execute([trim($_POST['code']), trim($_POST['name']), (int)$_POST['credits'], (int)$_POST['id']]);
        echo json_encode(['status' => 'success']);
    }

    elseif ($action === 'delete_course' && $role === 'admin') {
        $pdo->prepare("DELETE FROM courses WHERE id=?")->execute([(int)$_POST['course_id']]);
        echo json_encode(['status' => 'success']);
    }

    elseif ($action === 'upload_avatar') {
        // File Handling Logic Requirement
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png'];
            $filename = $_FILES['avatar']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            if (in_array($ext, $allowed) && $_FILES['avatar']['size'] <= 2000000) { // 2MB limit
                if (!is_dir('uploads')) mkdir('uploads', 0777, true);
                
                $new_name = uniqid() . '.' . $ext;
                move_uploaded_file($_FILES['avatar']['tmp_name'], 'uploads/' . $new_name);
                
                $pdo->prepare("UPDATE users SET profile_pic = ? WHERE id = ?")->execute([$new_name, $user_id]);
                $_SESSION['profile_pic'] = $new_name;
                
                echo json_encode(['status' => 'success', 'message' => 'Avatar updated!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Invalid file type or size (>2MB)']);
            }
        }
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database Error']);
}
?>