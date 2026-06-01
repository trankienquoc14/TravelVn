<?php
require_once "../config/database.php";
require_once "../models/User.php";

class AdminController
{

    private $user;

    public function __construct()
    {
        require_once __DIR__ . '/../config/middleware.php';
        Middleware::adminOnly();
        $db = (new Database())->connect();
        $this->user = new User($db);
    }

    public function index()
    {
        $users = $this->user->getAllUsers();
        include "../views/admin/users.php";
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->user->createUser($_POST);
            header("Location: admin.php");
            exit();
        }
        include "../views/admin/create_user.php";
    }

    public function edit()
{
    $id = $_GET['id'];
    $user = $this->user->getUserById($id);

    if (!$user) {
        die("Không tìm thấy người dùng");
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // Không cho hạ quyền tài khoản admin
        if ($user['role'] === 'admin' && isset($_POST['role']) && $_POST['role'] !== 'admin') {
            $_SESSION['error'] = "Không thể hạ quyền tài khoản Admin!";
            header("Location: admin.php?action=edit&id=" . $id);
            exit();
        }

        $this->user->updateUser($id, $_POST);

        $_SESSION['success'] = "Cập nhật người dùng thành công!";
        header("Location: admin.php");
        exit();
    }

    include "../views/admin/edit_user.php";
}

    public function delete()
{
    $id = $_GET['id'];

    // Không cho xóa chính mình
    if (isset($_SESSION['user']['user_id']) && $_SESSION['user']['user_id'] == $id) {
        $_SESSION['error'] = "Không thể xóa chính tài khoản của mình!";
        header("Location: admin.php");
        exit();
    }

    // Không cho xóa tài khoản admin
    $user = $this->user->getUserById($id);

    if ($user && $user['role'] === 'admin') {
        $_SESSION['error'] = "Không thể xóa tài khoản Admin!";
        header("Location: admin.php");
        exit();
    }

    $this->user->deleteUser($id);

    $_SESSION['success'] = "Đã xóa người dùng thành công!";
    header("Location: admin.php");
    exit();
}

    public function toggle()
    {
        $this->user->toggleStatus($_GET['id']);
        header("Location: admin.php");
    }

    public function reset()
    {
        $this->user->resetPassword($_GET['id']);
        header("Location: admin.php");
    }
    public function chat()
    {
        // Khai báo biến này để Sidebar biết đang ở mục nào mà sáng lên
        $activeMenu = 'chat';
        // Trỏ thẳng về file giao diện quản lý chat mà mình đã tạo
        require_once __DIR__ . '/../views/admin/chat_manage.php';
    }
}
?>