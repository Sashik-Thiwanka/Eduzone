<?php
// account.php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit();
}

// Database configuration (Ensure these match your setup)
$servername = "sql100.infinityfree.com";
$username = "if0_40714375";
$password = "LkE4u5SOS16CPC";
$dbname = "if0_40714375_eduzone_db";
$user_id = $_SESSION['user_id'];
$displayName = htmlspecialchars($_SESSION['user_name'] ?? 'User'); // Use for navbar greeting

$userData = [
    'full_name' => '',
    'user_name' => '',
    'email' => '',
    'phone_number' => '',
];
$error = '';

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        throw new Exception("Database connection failed.");
    }

    // Fetch user data
    $sql = "SELECT full_name, user_name, email, phone_number FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $userData = $result->fetch_assoc();
    } else {
        throw new Exception("User data not found.");
    }

    $stmt->close();
    $conn->close();
    
} catch (Exception $e) {
    // Log error, but show a generic message to the user
    $error = "Failed to load account details. " . htmlspecialchars($e->getMessage());
}

// Check for success or error messages from the process script
if (isset($_SESSION['account_message'])) {
    $message = $_SESSION['account_message'];
    unset($_SESSION['account_message']);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - <?php echo htmlspecialchars($userData['user_name']); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        /* ---------------------------------------------------- */
        /* 1. THEME & VARIABLES */
        /* ---------------------------------------------------- */
        :root {
            /* Light Theme */
            --color-bg-main: #f4f7f9;       
            --color-bg-secondary: #ffffff;  
            --color-text-primary: #1f2937;  
            --color-text-secondary: #6b7280;
            --color-accent: #f97316;        
            --color-accent-dark: #ea580c;
            --color-header-bg: #4338ca;     
            --color-nav-text: #eef2ff;      
            --color-input-bg: #f5f8fa;      
            --color-danger: #dc2626;        
            --shadow-subtle: 0 10px 30px rgba(0, 0, 0, 0.08);
            --transition-speed: 0.3s;
            --header-height: 60px;
        }

        .dark-theme {
            --color-bg-main: #111827;
            --color-bg-secondary: #1f2937; 
            --color-text-primary: #f9fafb; 
            --color-text-secondary: #9ca3af; 
            --color-accent: #fcd34d;        
            --color-accent-dark: #d97706;
            --color-header-bg: #1f2937;
            --color-nav-text: #f9fafb;
            --color-input-bg: #374151; 
            --color-danger: #ef4444;
            --shadow-subtle: 0 10px 30px rgba(0, 0, 0, 0.4);
        }

        /* ---------------------------------------------------- */
        /* 2. BASE STYLING & ANIMATIONS */
        /* ---------------------------------------------------- */
        *, *::before, *::after { box-sizing: border-box; }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--color-bg-main);
            color: var(--color-text-primary);
            margin: 0;
            padding-top: var(--header-height); 
            transition: background-color var(--transition-speed), color var(--transition-speed);
        }
        
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        
        /* ---------------------------------------------------- */
        /* 3. NAVIGATION BAR & SIDE MENU */
        /* ---------------------------------------------------- */
        .headercont {
            position: fixed; top: 0; left: 0; width: 100%; height: var(--header-height); 
            display: flex; justify-content: space-between; align-items: center; padding: 0 30px;
            background-color: var(--color-header-bg); box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15); z-index: 1000;
        }
        .title h1 { font-size: 1.4em; margin: 0; color: var(--color-nav-text); font-weight: 800; display: flex; align-items: center; }
        .menu-icon { width: 20px; height: 20px; margin-right: 15px; cursor: pointer; display: none; filter: invert(1); }
        .menuoption { display: flex; align-items: center; }
        
        .but, .dropbtn, .welcome, .logout, .theme-toggle {
            padding: 8px 15px; margin-left: 10px; background: none; border: 1px solid transparent;
            color: var(--color-nav-text); cursor: pointer; border-radius: 6px; font-weight: 600;
            transition: all 0.2s ease-in-out; white-space: nowrap; text-decoration: none; display: inline-flex; align-items: center;
        }
        .but:hover, .dropbtn:hover, .welcome:hover, .logout:hover { 
            background-color: rgba(255, 255, 255, 0.1); border-color: var(--color-accent);
            transform: translateY(-1px); 
        }
        .but.welcome { border-color: var(--color-nav-text); }
        .but.logout { background-color: var(--color-danger); border-color: var(--color-danger); }
        .but.logout:hover { background-color: #b91c1c; border-color: #b91c1c; }

        /* Dropdown Styling - FIX: Ensure it is hidden by default */
        .dropdown { position: relative; display: inline-block; }
        .dropdown-content {
            display: none; 
            position: absolute; background-color: var(--color-bg-secondary);
            min-width: 200px; box-shadow: var(--shadow-subtle); z-index: 1;
            border-radius: 8px; padding: 8px 0; top: calc(100% + 5px); border: 1px solid var(--color-input-bg);
            animation: fadeIn 0.3s ease-out;
        }
        .dropdown-content a { color: var(--color-text-primary); padding: 10px 16px; text-decoration: none; display: block; font-weight: 500;}
        .dropdown-content a:hover { background-color: var(--color-input-bg); color: var(--color-accent); }
        .dropdown-content.show { display: block; }
        
        /* Side Menu Styling - FIX: Width should be 0 by default to be hidden */
        .side-menu {
            height: 100%; width: 0; 
            position: fixed; z-index: 1001; top: 0; left: 0;
            background-color: var(--color-bg-secondary); overflow-x: hidden;
            padding-top: var(--header-height); transition: 0.4s ease-in-out; 
            box-shadow: 4px 0 10px rgba(0,0,0,0.2);
        }
        .side-menu a {
            padding: 15px 15px 15px 32px; text-decoration: none; font-size: 1.1em;
            color: var(--color-text-primary); display: block; transition: 0.3s;
            border-bottom: 1px solid var(--color-input-bg);
        }
        .side-menu a:hover { color: var(--color-accent); background-color: var(--color-input-bg); }
        .side-menu .closebtn {
            position: absolute; top: 0; right: 25px; font-size: 36px; margin-left: 50px;
            padding: 0; color: var(--color-danger); border: none;
        }


        /* ---------------------------------------------------- */
        /* 4. ACCOUNT CARD STYLING & DELETE DIALOG FIX */
        /* ---------------------------------------------------- */
        .main-content {
            display: flex; flex-direction: column; align-items: center; 
            padding: 50px 20px;
        }
        
        /* Back to Home Button */
        .back-home-btn {
            background-color: var(--color-header-bg); color: white; padding: 10px 20px;
            border-radius: 8px; text-decoration: none; font-weight: 600; margin-bottom: 30px;
            transition: all 0.2s ease-in-out; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .back-home-btn:hover { background-color: #3730a3; transform: translateY(-2px); box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2); }

        .account-card {
            width: 100%; max-width: 700px; background-color: var(--color-bg-secondary);
            border-radius: 12px; box-shadow: var(--shadow-subtle); padding: 40px;
            animation: fadeIn 0.8s ease-out; 
            border-left: 5px solid var(--color-accent); 
        }
        h2 { text-align: center; color: var(--color-header-bg); margin-bottom: 30px; font-size: 2em; font-weight: 800; }
        
        /* Form Inputs & Buttons (Styles unchanged, assuming they work) */
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-weight: 700; margin-bottom: 8px; color: var(--color-text-primary); }
        .account-card input {
            width: 100%; padding: 12px; border: 2px solid var(--color-input-bg); border-radius: 8px;
            background-color: var(--color-input-bg); color: var(--color-text-primary); font-size: 1em;
            transition: all 0.2s;
        }
        .account-card input:not([disabled]):focus { border-color: var(--color-accent); box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.2); outline: none; background-color: var(--color-bg-secondary); }
        .account-card input[disabled] { opacity: 0.8; background-color: #eef2f6; cursor: not-allowed; border-style: dashed; }
        .message { padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center; font-weight: 600; }
        .success { background-color: #d1fae5; color: #065f46; border: 1px solid #065f46; }
        .error { background-color: #fee2e2; color: var(--color-danger); border: 1px solid var(--color-danger); }
        .button-group { display: flex; flex-wrap: wrap; justify-content: space-between; gap: 10px; margin-top: 30px; }
        .action-btn { padding: 12px 15px; border: none; border-radius: 8px; font-size: 0.95em; font-weight: 700; cursor: pointer; transition: all 0.2s; flex-grow: 1; min-width: 150px; }
        .action-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); }
        #editButton { background-color: var(--color-header-bg); color: white; }
        #saveButton { background-color: var(--color-accent); color: white; display: none; }
        #cancelButton { background-color: #9ca3af; color: white; display: none; }
        #deleteButton { background-color: var(--color-danger); color: white; }

        /* Delete Dialog Styles - FIX: Must be display: none by default */
        .delete-dialog { 
            position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
            background: rgba(0, 0, 0, 0.6); 
            display: none; /* Crucial: Hidden by default */
            justify-content: center; align-items: center; z-index: 2000; 
        }
        .dialog-content { 
            background: var(--color-bg-secondary); padding: 30px; border-radius: 10px; 
            text-align: center; width: 90%; max-width: 400px; box-shadow: var(--shadow-subtle); 
            animation: fadeIn 0.3s ease-in-out; 
        }
        .dialog-content button { margin: 10px; }

        /* ---------------------------------------------------- */
        /* 5. RESPONSIVENESS */
        /* ---------------------------------------------------- */
        @media (max-width: 900px) {
            .headercont { padding: 0 15px; }
            .title h1 { font-size: 1.3em; }
            .menu-icon { display: block; } /* Show menu icon on mobile */
            .menuoption { display: none; } /* Hide desktop nav on mobile */
        }
        @media (max-width: 600px) {
            .account-card { padding: 30px 20px; }
            .button-group { flex-direction: column; gap: 15px; }
            .action-btn { min-width: 100%; }
        }
    </style>
</head>
<body id="body">

    <div class="headercont">
        <div class="title">
            <h1>
                <img src="images/menu2.png" class="menu-icon" onclick="toggleMenu()" alt="Menu Icon">
                EDUZONE EDUCATION CENTRE
            </h1>
        </div>
        
        <div class="menuoption" id="menuoption">
            <div class="dropdown" id="materialsDropdown">
                <button class="but dropbtn" onclick="toggleDropdown(event)">LEARNING MATERIALS</button>
                <div class="dropdown-content" id="dropdownContent">
                    <a href="past-papers.html">Past Papers</a>
                    <a href="e-books.html">E-BOOKS</a>
                    <a href="model-papers.html">Model Papers</a>
                    <a href="lesson-videos.html">Lesson Videos</a>
                    <a href="software.html">Softwares</a>
                </div>
            </div>

            <a href="home.php" class="but">HOME</a>
            
            <?php
                if (isset($_SESSION['user_name'])) {
                    echo '<a href="account.php" class="but welcome">Welcome, ' . $displayName . '!</a>';
                    echo '<button onclick="window.location.href=\'logout.php\'" class="but logout">Logout</button>';
                } else {
                    echo '<a href="login.html" class="but">Login</a>';
                    echo '<a href="register.html" class="but">Register</a>';
                }
            ?>
            
            <button class="theme-toggle" onclick="toggleTheme()" aria-label="Toggle Dark Mode">
                <span class="icon" role="img" aria-label="Theme Icon">🌙</span>
            </button>
        </div>
    </div>
    
    <nav class="side-menu" id="sideMenu">
        <a href="javascript:void(0)" class="closebtn" onclick="toggleMenu()"> &times; </a>
        <a href="home.php">Home</a>
        <a href="past-papers.html">Past Papers</a>
        <a href="#about">About Us</a>
        <a href="#contact">Contact</a>
        <hr style="border-color: var(--color-input-bg); margin: 15px 0;">
        <a href="account.php" style="color: var(--color-accent); font-weight: bold;">My Account</a>
        <a href="logout.php" style="color: var(--color-danger); font-weight: bold;">Logout</a>
    </nav>
    
    <div class="main-content">
        
        <a href="home.php" class="back-home-btn">← Back to Home Dashboard</a>
        
        <div class="account-card">
            <h2>👋 Account Settings</h2>

            <?php 
            if ($error) {
                echo "<div class='message error'>$error</div>";
            } elseif (isset($message)) {
                $class = (strpos($message, 'successful') !== false) ? 'success' : 'error';
                echo "<div class='message $class'>$message</div>";
            }
            ?>

            <form id="accountForm" action="process_account.php" method="POST">
                <div class="form-group">
                    <label for="fullName">Full Name</label>
                    <input type="text" id="fullName" name="full_name" value="<?php echo htmlspecialchars($userData['full_name']); ?>" disabled required>
                </div>
                
                <div class="form-group">
                    <label for="userName">Username (Login ID)</label>
                    <input type="text" id="userName" name="user_name" value="<?php echo htmlspecialchars($userData['user_name']); ?>" disabled required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($userData['email']); ?>" disabled required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone_number" value="<?php echo htmlspecialchars($userData['phone_number'] ?? ''); ?>" disabled>
                </div>
                
                <input type="hidden" name="action" id="actionField" value="update">

                <div class="button-group">
                    <button type="button" id="editButton" class="action-btn">✏️ Edit Details</button>
                    <button type="submit" id="saveButton" class="action-btn">💾 Save Changes</button>
                    <button type="button" id="cancelButton" class="action-btn">❌ Cancel</button>
                    <button type="button" id="deleteButton" class="action-btn">🗑️ Delete Account</button>
                </div>
            </form>
        </div>
    </div>

    <div id="deleteDialog" class="delete-dialog">
        <div class="dialog-content">
            <h4>⚠️ Confirm Account Deletion</h4>
            <p>Are you sure you want to permanently delete your account? This action cannot be undone.</p>
            <button id="confirmDelete" class="action-btn" style="background-color: var(--color-danger); color: white;">Yes, Delete</button>
            <button id="cancelDelete" class="action-btn" style="background-color: #ccc;">Cancel</button>
        </div>
    </div>

    <script>
    // --- 1. Navigation Functions (Menu, Dropdown, Theme) ---
    const body = document.getElementById('body');
    const darkThemeClass = 'dark-theme';

    function toggleMenu() {
        const sideMenu = document.getElementById("sideMenu");
        // Set width to 250px to show, 0 to hide.
        sideMenu.style.width = sideMenu.style.width === '250px' ? '0' : '250px';
    }
    
    // FIX APPLIED HERE: Using parentNode.querySelector to correctly target the dropdown content.
    function toggleDropdown(event) {
        event.stopPropagation();
        
        // Find the correct dropdown content element by querying within the parent (.dropdown)
        const content = event.currentTarget.parentNode.querySelector('.dropdown-content');

        // Close any other open dropdowns
        document.querySelectorAll('.dropdown-content.show').forEach(openContent => {
            // Ensure we don't close the current dropdown
            if (openContent !== content) {
                openContent.classList.remove('show');
            }
        });
        
        // Toggle the current dropdown
        content.classList.toggle('show');
    }
    
    window.onclick = function(e) {
        // Close dropdown if clicked outside
        if (!e.target.closest('.dropdown')) {
            document.querySelectorAll('.dropdown-content.show').forEach(content => content.classList.remove('show'));
        }
        // Close side menu if clicked outside
        const sideMenu = document.getElementById("sideMenu");
        if (sideMenu && sideMenu.style.width === '250px' && !e.target.closest('.side-menu') && !e.target.closest('.menu-icon') && !e.target.closest('.title')) {
            sideMenu.style.width = '0';
        }
    }

    function setTheme(isDark) {
        isDark ? body.classList.add(darkThemeClass) : body.classList.remove(darkThemeClass);
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
    }
    function toggleTheme() {
        setTheme(!body.classList.contains(darkThemeClass));
    }
    (function() {
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') body.classList.add(darkThemeClass);
        else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) body.classList.add(darkThemeClass);
    })();


    // --- 2. Account Form Logic ---
    const form = document.getElementById('accountForm');
    const inputs = form.querySelectorAll('input:not([type="hidden"])'); 
    const editBtn = document.getElementById('editButton');
    const saveBtn = document.getElementById('saveButton');
    const cancelBtn = document.getElementById('cancelButton');
    const deleteBtn = document.getElementById('deleteButton');
    const deleteDialog = document.getElementById('deleteDialog');
    const confirmDeleteBtn = document.getElementById('confirmDelete');
    const cancelDeleteBtn = document.getElementById('cancelDelete'); 
    const actionField = document.getElementById('actionField');
    
    let initialValues = {};

    function saveInitialValues() {
        inputs.forEach(input => {
            initialValues[input.id] = input.value;
        });
    }
    
    function setEditMode(isEditing) {
        inputs.forEach(input => {
            // Email is usually immutable, keep it disabled
            if (input.id !== 'email') { 
                input.disabled = !isEditing;
            }
        });
        
        // Use 'flex' for buttons in the group
        editBtn.style.display = isEditing ? 'none' : 'flex';
        deleteBtn.style.display = isEditing ? 'none' : 'flex'; 
        saveBtn.style.display = isEditing ? 'flex' : 'none';
        cancelBtn.style.display = isEditing ? 'flex' : 'none';
    }

    // --- Event Handlers ---
    editBtn.addEventListener('click', () => {
        saveInitialValues();
        setEditMode(true);
    });

    cancelBtn.addEventListener('click', () => {
        // Revert inputs to initial values
        inputs.forEach(input => {
            input.value = initialValues[input.id];
        });
        setEditMode(false);
    });
    
    deleteBtn.addEventListener('click', () => {
        deleteDialog.style.display = 'flex'; // Show the dialog
    });

    cancelDeleteBtn.addEventListener('click', () => { 
        deleteDialog.style.display = 'none'; // Hide the dialog
    });

    confirmDeleteBtn.addEventListener('click', () => {
        actionField.value = 'delete';
        form.submit(); // Submit the form for deletion
    });

    // Initial setup when the page loads
    saveInitialValues();
    setEditMode(false);

</script>
</body>
</html>