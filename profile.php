<?php
session_start();
include 'includes/sidebar.php';
require_once 'controller/authCheck.php';
// TODO:
// - Add update profile image functionality
// - Fix the fucking layout - Take inspiration for SimpleMMO
//
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Battlewarz / Profile</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.2/dist/tailwind.min.css">
</head>

<body class="bg-white-100 ">
  <div class="w-full space-y-6 mx-auto" id="profile-container">
  <h1 class="text-x2 py-1 mb-1 flex items-center justify-between">
  <span>
    <span class="text-muted-foreground font-light">Battlewarz /</span>
    <span class="font-bold"> Profile</span>
  </span></h1>
    <div id="profile-content">
      <div class="text-center text-gray-500">Loading...</div>
    </div>
  </div>
  <script>
    document.addEventListener('DOMContentLoaded', async () => {
      const profileContent = document.getElementById('profile-content');
      try {
        const res = await fetch('api/getPlayerProfile.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' }
        });
        const data = await res.json();
        if (!data || data.status === 'error') {
          profileContent.innerHTML = `<div class="text-red-600 text-center">Failed to load profile.</div>`;
          return;
        }

        // Calculate progress percentages
        const expPercent = data.exp_req && data.exp ? Math.min(100, Math.round((data.exp / data.exp_req) * 100)) : 0;
        const hpPercent = data.max_hp && data.c_hp ? Math.min(100, Math.round((data.c_hp / data.max_hp) * 100)) : 0;
        const staminaPercent = data.max_stamina && data.stamina ? Math.min(100, Math.round((data.stamina / data.max_stamina) * 100)) : 0;

        // Main profile card
        let html = `
    <div class="flex flex-col md:flex-row gap-6">
      <!-- Left Column: Profile & Combat Stats -->
      <div class="w-full md:w-1/2 space-y-6">
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
          <div class="flex flex-col space-y-1.5 p-6 pb-2">
            <h3 class="tracking-tight text-2xl font-bold text-center">
              <span style="color: rgb(0, 0, 0);">${data.name}</span>
            </h3>
            <p class="text-sm text-muted-foreground text-center">
              Level ${data.level} ${data.class ? '• ' + data.class : ''}
            </p>
          </div>
          <div class="p-6 pt-0 flex flex-col items-center">
            <img id="profile-img"
                 src="${data.image_path}"
                 alt="Profile"
                 class="h-32 w-32 rounded-full border border-gray-300 object-cover mx-auto mb-4" />
            <div class="mb-4">
              <label for="profile-image" class="cursor-pointer bg-white rounded px-2 py-1 border text-xs font-medium">
                <input id="profile-image" type="file" accept="image/*" class="hidden">
                Change
              </label>
            </div>
            <div class="w-full space-y-3">
              <!-- Experience Progress -->
              <div class="space-y-1">
                <div class="flex justify-between text-sm">
                  <span>Experience</span>
                  <span>${data.exp ?? 0} / ${data.exp_req ?? 0}</span>
                </div>
                <div class="relative w-full overflow-hidden rounded-full bg-secondary h-2">
                  <div class="h-full flex-1 bg-primary transition-all" style="width:${expPercent}%;"></div>
                </div>
              </div>
              <!-- HP Progress -->
              <div class="space-y-1">
                <div class="flex justify-between text-sm">
                  <span class="flex items-center">

                    Health
                  </span>
                  <span>${data.c_hp ?? 0} / ${data.max_hp ?? 0}</span>
                </div>
                <div class="relative w-full overflow-hidden rounded-full h-2 bg-red-100">
                  <div class="h-full flex-1 bg-primary transition-all" style="width:${hpPercent}%;"></div>
                </div>
              </div>
              <!-- Stamina Progress -->
              <div class="space-y-1">
                <div class="flex justify-between text-sm">
                  <span class="flex items-center">

                    Stamina
                  </span>
                  <span>${data.stamina ?? 0}${data.max_stamina ? ' / ' + data.max_stamina : ''}</span>
                </div>
                <div class="relative w-full overflow-hidden rounded-full h-2 bg-amber-100">
                  <div class="h-full flex-1 bg-primary transition-all" style="width:${staminaPercent}%;"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Combat Stats Card -->
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
          <div class="flex flex-col space-y-1.5 p-6">
            <h3 class="font-semibold tracking-tight text-xl">Combat Stats</h3>
          </div>
          <div class="p-6 pt-0 space-y-2">
            <div class="flex items-center justify-between cursor-help">
              <div class="flex items-center">
   
                <span>Attack</span>
              </div>
              <span class="font-medium">${data.attack ?? 0}</span>
            </div>
            <div class="flex items-center justify-between cursor-help">
              <div class="flex items-center">
    
                <span>Defence</span>
              </div>
              <span class="font-medium">${data.defence ?? 0}</span>
            </div>
            <div class="flex items-center justify-between cursor-help">
              <div class="flex items-center">
      
                <span>Speed</span>
              </div>
              <span class="font-medium">${data.speed ?? 0}</span>
            </div>
            <div class="flex items-center justify-between cursor-help">
              <div class="flex items-center">
    
                <span>Health</span>
              </div>
              <span class="font-medium">${data.max_hp ?? 0}</span>
            </div>
            <div class="flex items-center justify-between cursor-help">
              <div class="flex items-center">

        
       
                <span>Health Regen</span>
              </div>
              <span class="font-medium">${data.health_regen ?? 0}</span>
            </div>
            <div class="flex items-center justify-between cursor-help">
              <div class="flex items-center">
       
                <span>Critical Chance</span>
              </div>
              <span class="font-medium">${data.crit_chance ?? 0}%</span>
            </div>
            <div class="flex items-center justify-between cursor-help">
              <div class="flex items-center">
   
                <span>Critical Multiplier</span>
              </div>
              <span class="font-medium">${data.crit_multi ?? '—'}%</span>
            </div>
            <div class="flex items-center justify-between cursor-help">
              <div class="flex items-center">
        
                <span>Life Steal</span>
              </div>
              <span class="font-medium">${data.life_steal ?? 0}%</span>
            </div>
          </div>
        </div>
      </div>
      <!-- Right Column: Statistics (Wider) -->
      <div class="w-full md:w-1/2">
      
      <div role="tablist" class="mb-2 h-10 items-center justify-center rounded-md bg-muted p-1 text-muted-foreground grid w-full grid-cols-2">
        <button id="statistics-tab" data-state="active" class="inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 data-[state=active]:bg-background data-[state=active]:text-foreground data-[state=active]:shadow-sm">
          Statistics
        </button>
        <button id="settings-tab" class="inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 data-[state=active]:bg-background data-[state=active]:text-foreground data-[state=active]:shadow-sm">
          Settings
        </button>
      </div>
      <div class="rounded-lg border bg-card text-card-foreground shadow-sm  flex flex-col">
        <!-- Statistics Panel -->
        <div id="statistics-panel" class="flex-1 overflow-visible">
          <div class="flex flex-col space-y-1.5 p-6">
            <h3 class="font-semibold tracking-tight text-xl flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trophy mr-2 h-5 w-5">
                <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"></path>
                <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"></path>
                <path d="M4 22h16"></path>
                <path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"></path>
                <path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"></path>
                <path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"></path>
              </svg>
              Achievements &amp; Stats
            </h3>
          </div>
          
          <div class="p-6 pt-0">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <!-- Combat Records -->
              <div class="border rounded-lg p-4">
                <h3 class="text-sm font-medium mb-3">Combat Records</h3>
                <div class="space-y-2">
                  <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Monsters Killed</span>
                    <span class="font-medium">${data.stats?.pve_kills ?? 0}</span>
                  </div>
                  <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Bosses Defeated</span>
                    <span class="font-medium">${data.stats?.bosses_defeated ?? 0}</span>
                  </div>
                  <div class="flex justify-between text-sm">
                    <span class="text-gray-600">PvP Wins</span>
                    <span class="font-medium">${data.stats?.pvp_kills ?? 0}</span>
                  </div>
                  <div class="flex justify-between text-sm">
                    <span class="text-gray-600">PvP Losses</span>
                    <span class="font-medium">${data.stats?.pvp_battles - data.stats?.pvp_kills ?? 0}</span>
                  </div>
                  <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Win Rate</span>
                    <span class="font-medium">${data.stats?.pvp_battles
            ? ((data.stats.pvp_kills ?? 0) / data.stats.pvp_battles * 100).toFixed(2) + '%'
            : '—'
          }</span>
                  </div>
                </div>
              </div>
              <!-- Adventure Log -->
              <div class="border rounded-lg p-4">
                <h3 class="text-sm font-medium mb-3">Adventure Log</h3>
                <div class="space-y-2">
                  <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Quests Completed</span>
                    <span class="font-medium">${data.stats?.quests_completed ?? 0}</span>
                  </div>
                  <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Dungeons Cleared</span>
                    <span class="font-medium">${data.stats?.dungeons_cleared ?? 0}</span>
                  </div>
                  <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Tiles Traveled</span>
                    <span class="font-medium">${data.stats?.travel_count ?? 0}</span>
                  </div>
                  <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Days Active</span>
                    <span class="font-medium">${data.stats?.days_active ?? 0}</span>
                  </div>
                </div>
              </div>
              <!-- Wealth -->
              <div class="border rounded-lg p-4">
                <h3 class="text-sm font-medium font-bold mb-3">Wealth</h3>
                <div class="space-y-2">
                  <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Gold Earned</span>
                    <span class="font-medium">${data.stats?.gold_earned ?? 0}</span>
                  </div>
                  <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Items Sold</span>
                    <span class="font-medium">${data.stats?.items_sold ?? 0}</span>
                  </div>
                  <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Trading Profit</span>
                    <span class="font-medium">${data.stats?.trading_profit ?? 0}</span>
                  </div>
                </div>
              </div>
              <!-- Miscellaneous -->
              <div class="border rounded-lg p-4">
                <h3 class="text-sm font-medium mb-3">Miscellaneous</h3>
                <div class="space-y-2">
                  <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Guild Rank</span>
                    <span class="font-medium">${data.stats?.guild_rank ?? '—'}</span>
                  </div>
                  <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Reputation</span>
                    <span class="font-medium">${data.stats?.reputation ?? '—'}</span>
                  </div>
                  <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Achievement Points</span>
                    <span class="font-medium">${data.stats?.achievement_points ?? 0}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- Settings Panel (hidden by default) -->
        <div id="settings-panel" class="hidden flex-1 overflow-visible">
          <div class="flex flex-col space-y-1.5 p-6">
         
            <h3 class="font-semibold tracking-tight text-xl flex items-center mb-3">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trophy mr-2 h-5 w-5">
                <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"></path>
                <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"></path>
                <path d="M4 22h16"></path>
                <path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"></path>
                <path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"></path>
                <path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"></path>
              </svg>
              Player Settings
            </h3>
             <div class="border rounded-lg p-4">
            <form id="password-form" class="max-w-md mx-auto space-y-4">
              <div>
                <label for="current-password" class=" text-sm font-medium mb-1">Current Password</label>
                <input type="password" id="current-password" name="current_password" class="w-full border rounded px-3 py-2" required>
              </div>
              <div>
                <label for="new-password" class=" text-sm font-medium mb-1">New Password</label>
                <input type="password" id="new-password" name="new_password" class="w-full border rounded px-3 py-2" required>
              </div>
              <div>
                <label for="confirm-password" class=" text-sm font-medium mb-1">Confirm New Password</label>
                <input type="password" id="confirm-password" name="confirm_password" class="w-full border rounded px-3 py-2" required>
              </div>
              <button type="submit" class="w-full bg-blue-600 text-white rounded px-3 py-2 font-medium hover:bg-blue-700 transition">Save Password</button>
              <div id="password-message" class="mt-2 text-center text-sm"></div>
            </form>
          </div>
        </div>
      </div>
    </div>
    `;

        profileContent.innerHTML = html;

        // Tab switching logic (must be here, after HTML is set)
        const statisticsTab = document.getElementById('statistics-tab');
        const settingsTab = document.getElementById('settings-tab');
        const statisticsPanel = document.getElementById('statistics-panel');
        const settingsPanel = document.getElementById('settings-panel');

        if (statisticsTab && settingsTab && statisticsPanel && settingsPanel) {
          statisticsTab.addEventListener('click', () => {
            statisticsTab.setAttribute('data-state', 'active');
            settingsTab.removeAttribute('data-state');
            statisticsPanel.classList.remove('hidden');
            settingsPanel.classList.add('hidden');
          });
          settingsTab.addEventListener('click', () => {
            settingsTab.setAttribute('data-state', 'active');
            statisticsTab.removeAttribute('data-state');
            settingsPanel.classList.remove('hidden');
            statisticsPanel.classList.add('hidden');
          });
        }

        // Password change form logic
        const passwordForm = document.getElementById('password-form');
        if (passwordForm) {
          passwordForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const current = document.getElementById('current-password').value;
            const next = document.getElementById('new-password').value;
            const confirm = document.getElementById('confirm-password').value;
            const msg = document.getElementById('password-message');
            msg.textContent = '';
            if (next !== confirm) {
              msg.textContent = 'New passwords do not match.';
              msg.className = 'mt-2 text-center text-sm text-red-600';
              return;
            }
            try {
              const res = await fetch('api/updatePassword.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ current_password: current, new_password: next })
              });
              const data = await res.json();
              if (data.status === 'success') {
                msg.textContent = 'Password updated successfully.';
                msg.className = 'mt-2 text-center text-sm text-green-600';
                passwordForm.reset();
              } else {
                msg.textContent = data.message || 'Failed to update password.';
                msg.className = 'mt-2 text-center text-sm text-red-600';
              }
            } catch {
              msg.textContent = 'Error updating password.';
              msg.className = 'mt-2 text-center text-sm text-red-600';
            }
          });
        }

        // Profile image upload logic
        const profileImageInput = document.getElementById('profile-image');
        const profileImg = document.getElementById('profile-img');
        const profileImgPreview = document.getElementById('profile-img-preview');
        if (profileImageInput) {
          profileImageInput.addEventListener('change', async (e) => {
            const file = e.target.files[0];
            if (!file) return;

            // Show preview immediately
            if (profileImgPreview) {
              const reader = new FileReader();
              reader.onload = function(ev) {
                profileImgPreview.src = ev.target.result;
                profileImgPreview.classList.remove('hidden');
                // Hide the old image while previewing
                if (profileImg) profileImg.classList.add('hidden');
              };
              reader.readAsDataURL(file);
            }

            const formData = new FormData();
            formData.append('profile_image', file);

            // Show uploading toast/message
            let toast = document.createElement('div');
            toast.textContent = 'Uploading...';
            toast.className = 'fixed top-4 right-4 bg-blue-600 text-white px-4 py-2 rounded shadow z-50';
            document.body.appendChild(toast);

            try {
              const res = await fetch('api/uploadProfileImage.php', {
                method: 'POST',
                body: formData
              });
              const result = await res.json();
              if (result.status === 'success') {
                // Update the profile image on the page
                if (profileImg) {
                  // Force reload with timestamp to avoid cache
                  profileImg.src = '/' + result.image_path.replace(/^\/?/, '') + '?t=' + Date.now();
                  profileImg.classList.remove('hidden'); // Always show after upload
                  // Debug: log the new src
                  console.log('Profile image updated:', profileImg.src);
                }
                if (profileImgPreview) profileImgPreview.classList.add('hidden');
                toast.textContent = 'Profile image updated!';
                toast.className = 'fixed top-4 right-4 bg-green-600 text-white px-4 py-2 rounded shadow z-50';
              } else {
                toast.textContent = result.message || 'Failed to upload image.';
                toast.className = 'fixed top-4 right-4 bg-red-600 text-white px-4 py-2 rounded shadow z-50';
                if (profileImgPreview) profileImgPreview.classList.add('hidden');
                if (profileImg) profileImg.classList.remove('hidden');
              }
            } catch {
              toast.textContent = 'Error uploading image.';
              toast.className = 'fixed top-4 right-4 bg-red-600 text-white px-4 py-2 rounded shadow z-50';
              if (profileImgPreview) profileImgPreview.classList.add('hidden');
              if (profileImg) profileImg.classList.remove('hidden');
            }
            setTimeout(() => {
              if (toast && toast.parentNode) toast.parentNode.removeChild(toast);
            }, 2500);
          });
        }
      } catch (e) {
        profileContent.innerHTML = `<div class="text-red-600 text-center">Error loading profile.</div>`;
      }
    });
  </script>
</body>

</html>
<script type="module" src="assets/js/playerStats.js"></script>