<style>
    .community-container { display: flex; gap: 30px; }
    .feed-section { flex: 2; }
    .sidebar-section { flex: 1; }

    .post-card { background: var(--glass); border: 1px solid var(--border); border-radius: 20px; padding: 20px; margin-bottom: 20px; }
    .post-header { display: flex; align-items: center; gap: 12px; margin-bottom: 15px; }
    .post-header img { width: 40px; height: 40px; border-radius: 50%; border: 2px solid var(--accent); }
    
    .interaction-bar { display: flex; gap: 20px; margin-top: 15px; border-top: 1px solid var(--border); padding-top: 15px; color: #94a3b8; }
    .interaction-bar span { cursor: pointer; transition: 0.3s; }
    .interaction-bar span:hover { color: var(--accent); }

    .trending-card { background: linear-gradient(135deg, rgba(0, 242, 255, 0.05), rgba(188, 19, 254, 0.05)); border: 1px solid var(--border); border-radius: 20px; padding: 20px; }
</style>

<main class="main-content">
    <h1>Global Community</h1>
    <div class="community-container">
        <div class="feed-section">
            <div class="post-card" style="background: rgba(0, 242, 255, 0.05);">
                <input type="text" placeholder="Ask a question or share a study tip..." style="width: 100%; background: transparent; border: none; color: #fff; font-size: 1.1rem; outline: none;">
            </div>

            <div class="post-card">
                <div class="post-header">
                    <img src="https://i.pravatar.cc/150?u=12" alt="Avatar">
                    <div>
                        <div style="font-weight: 700;">Kamal Perera <span class="tag" style="margin-left: 10px;">A/L Student</span></div>
                        <div style="font-size: 0.7rem; color: #64748b;">Posted 2 hours ago</div>
                    </div>
                </div>
                <p>Does anyone have a simplified explanation for the 3rd Law of Thermodynamics? Struggling with the entropy part for my finals.</p>
                <div class="interaction-bar">
                    <span><i class="fas fa-thumbs-up"></i> 24 Upvotes</span>
                    <span><i class="fas fa-comment"></i> 8 Answers</span>
                    <span><i class="fas fa-share"></i> Share</span>
                </div>
            </div>
        </div>

        <div class="sidebar-section">
            <div class="trending-card">
                <h3><i class="fas fa-fire" style="color: #ff4500;"></i> Trending Topics</h3>
                <ul style="list-style: none; padding: 0; color: #94a3b8;">
                    <li style="margin-bottom: 15px;">#ALCalculusTips <span style="display: block; font-size: 0.7rem;">450 students discussing</span></li>
                    <li style="margin-bottom: 15px;">#ICTPastPapers <span style="display: block; font-size: 0.7rem;">1.2k students discussing</span></li>
                </ul>
            </div>
        </div>
    </div>
</main>