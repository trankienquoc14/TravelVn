    <!DOCTYPE html>
    <html>

    <head>
        <title>Phân công Hướng dẫn viên</title>
        <style>
            body {
                background: #f5f5f5;
                font-family: Arial;
            }

            .container {
                max-width: 700px;
                margin: 40px auto;
                background: #fff;
                padding: 32px;
                border-radius: 8px;
                box-shadow: 0 2px 8px #ccc;
            }

            h2 {
                margin-bottom: 24px;
            }

            .form {
                display: flex;
                gap: 16px;
                flex-wrap: wrap;
                margin-bottom: 24px;
            }

            .form input {
                padding: 8px;
                border-radius: 4px;
                border: 1px solid #ccc;
            }

            .form button {
                padding: 8px 16px;
                border-radius: 4px;
                background: #28a745;
                color: #fff;
                border: none;
                cursor: pointer;
            }

            .form button:hover {
                background: #218838;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 24px;
            }

            th,
            td {
                padding: 12px;
                border: 1px solid #ccc;
                text-align: left;
            }

            th {
                background: #007bff;
                color: #fff;
            }

            tr:nth-child(even) {
                background: #f9f9f9;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <h2>Phân công Hướng dẫn viên</h2>
            <form class="form" method="POST" action="../controllers/GuideController.php">
    <input type="hidden" name="action" value="assign">
    
    <select name="departure_id" required>
        <option value="">-- Chọn lịch khởi hành --</option>
        <?php foreach ($departures as $d): ?>
            <option value="<?= $d['departure_id'] ?>">
                <?= htmlspecialchars($d['tour_name'] . ' - ' . $d['start_date']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <select name="staff_id" required>
        <option value="">-- Chọn hướng dẫn viên --</option>
        <?php foreach ($guides as $g): ?>
            <option value="<?= $g['user_id'] ?>">
                <?= htmlspecialchars($g['full_name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit">Phân công</button>
</form>
            <table>
                <tr>
                    <th>ID Lịch khởi hành</th>
                    <th>ID Nhân viên</th>
                </tr>
                <!-- Dữ liệu phân công -->
            </table>
        </div>
    </body>

    </html>