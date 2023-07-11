<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Company Registration</title>8
  </head>
  <body>
    <form action="company_proposal_update.php" method="post" enctype="multipart/form-data">

      <label for="min-graduation">Minimum Graduation:</label>
      <select id="min-graduation" name="min-graduation" required>
        <option value="btech">B.Tech</option>
        <option value="mtech">M.Tech</option>
        <option value="phd">PhD</option>
        <option value="others">Others</option>
      </select>

      <label for="min-cpi">Minimum CPI:</label>
      <input type="number" id="min-cpi" name="min-cpi" step="0.01" required>

      <label for="interview-mode">Interview Mode:</label>
      <select id="interview-mode" name="interview-mode" required>
        <option value="online">Online</option>
        <option value="offline">Offline</option>
      </select>

      <label for="salary">Salary (LPA):</label>
      <input type="number" id="salary" name="salary" step="0.01" required>

      <label for="role">Role:</label>
      <select id="role" name="role" required>
        <option value="SDE">SDE</option>
        <option value="ML ENGINEER">ML Engineer</option>
        <option value="RESEARCH">Research</option>
        <option value="DATA SCIENTIST">Da ta Scientist</option>
        <option value="ANALYST">Analytics</option>
        <option value="CONSULTANT">Consultant</option>
        <option value="HR">HR</option>
        <option value="core">Core</option>
        <option value="OTHERS">Others</option>

      </select>
      <label for="contact-name">Contact Person Name:</label>
      <input type="text" id="contact-name" name="contact-name" required>

      <label for="contact-email">Contact Person Email:</label>
      <input type="email" id="contact-email" name="contact-email" required>

      <button type="submit">Submit Form</button>
    </form>

    <style>
        body {
            background-color: #f2f2f2;
            font-family: Arial, sans-serif;
        }
    
        h2 {
            color: #008080;
            text-align: center;
        }
    
        form {
            margin: 0 auto;
            max-width: 500px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.2);
        }
    
        label {
            display: block;
            margin-bottom: 5px;
            color: #333333;
        }
    
        input[type=text],
        input[type=email],
        input[type=password],
        input[type=number],
        select {
            display: block;
            margin-bottom: 10px;
            padding: 10px;
            width: 100%;
            border-radius: 5px;
            border: 1px solid #cccccc;
            box-sizing: border-box;
            font-size: 16px;
            color: #333333;
        }
    
        input[type=checkbox] {
            margin-right: 5px;
        }
    
        button[type=submit] {
            background-color: #008080;
            color: #ffffff;
            padding: 10px 20px;
            border-radius: 5px;
            border: none;
            font-size: 16px;
            cursor: pointer;
        }
    
        button[type=submit]:hover {
            background-color: #005959;
        }
    </style>
    
  </body>
</html>

