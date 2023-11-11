<!DOCTYPE html>
<html>
<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <title>Salary Increase Summary XML Generation</title>
</head>
<body style="background-color: #FFFFCC; color: #800000">
    <h2>Salary Increase Summary XML Generation</h2>

    <!-- Form to input the department code -->
    <form action="generate_xml.php" method="post">
        <label for="dept_code">Enter Department Code (Enter 0 for all departments):</label>
        <input type="text" id="dept_code" name="dept_code" required>
        <input type="submit" value="Generate XML">
    </form>

</body>
</html>