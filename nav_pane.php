<div class="sidebar">
    <div class="brand">
        <div class="logo-hex">E</div>
        <span>EDUZONE PRO</span>
    </div>
    
    <div class="user-profile">
        <div class="avatar-glow">
            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($userName); ?>&background=00f2ff&color=000" alt="User">
        </div>
        <div class="user-info">
            <span class="u-name"><?php echo $userName; ?></span>
            <span class="u-status"><i class="fas fa-circle"></i> Pro Member</span>
        </div>
    </div>

    <nav class="nav-links">
        <a href="home.php" class="nav-item"><i class="fas fa-columns"></i> Dashboard</a>
        <a href="notes.php" class="nav-item active"><i class="fas fa-sticky-note"></i> Smart Notes</a>
        <a href="community.php" class="nav-item"><i class="fas fa-users"></i> Global Community</a>
        <a href="quizzes.php" class="nav-item"><i class="fas fa-bolt"></i> Exam Arena</a>
        <div class="nav-divider">PRO TOOLS</div>
        <a href="ai_tutor.php" class="nav-item ai-link"><i class="fas fa-robot"></i> AI Study Bot</a>
        <a href="settings.php" class="nav-item"><i class="fas fa-cog"></i> Preferences</a>
    </nav>
</div>