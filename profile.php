<?php
session_start();
include 'includes/sidebar.php';
require_once 'controller/authCheck.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Player Profile</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.2/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 ">
<div class="max-w-md mx-auto mt-10 p-6 bg-white rounded-lg shadow-md" id="profile-container">
  <h2 class="text-2xl font-bold text-center mb-4">Player Profile</h2>
  <div id="profile-content">
    <div class="text-center text-gray-500">Loading...</div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', async () => {
  const profileContent = document.getElementById('profile-content');
  try {
    // Use POST instead of GET for the fetch request
    const res = await fetch('api/getPlayerProfile.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' }
    });
    const data = await res.json();
    if (!data || data.status === 'error') {
      profileContent.innerHTML = `<div class="text-red-600 text-center">Failed to load profile.</div>`;
      return;
    }
    // Basic info
    let html = `
      <div class="flex justify-center mb-4">
        <img class="rounded-full h-24 w-24 object-cover border" src="${data.image_path || 'default.png'}" alt="Profile Image">
      </div>
      <div class="text-center">
        <h3 class="text-lg font-medium">${data.name}</h3>
        <p class="text-sm text-gray-500">Level ${data.level}</p>
      </div>
    `;
    // Stats section
    if (data.stats) {
      html += `
        <div class="mt-4">
          <h4 class="font-semibold mb-2 text-center">Statistics</h4>
          <div class="grid grid-cols-2 gap-2 text-sm">
            <div><span class="font-medium">Monster Battles:</span> ${data.stats.pve_battles ?? 0}</div>
            <div><span class="font-medium">Monster Kills:</span> ${data.stats.pve_kills ?? 0}</div>
            <div><span class="font-medium">Player Kills:</span> ${data.stats.pvp_kills ?? 0}</div>
            <div><span class="font-medium">Player Battles:</span> ${data.stats.pvp_battles ?? 0}</div>
            <div><span class="font-medium">Travels:</span> ${data.stats.travel_count ?? 0}</div>

          </div>
        </div>
      `;
    }
    // Attributes section
    html += `
      <div class="mt-4">
        <h4 class="font-semibold mb-2 text-center">Attributes</h4>
        <div class="grid grid-cols-2 gap-2 text-sm">
          <div><span class="font-medium">Attack:</span> ${data.attack ?? 0}</div>
          <div><span class="font-medium">Defence:</span> ${data.defence ?? 0}</div>
          <div><span class="font-medium">Speed:</span> ${data.speed ?? 0}</div>
          <div><span class="font-medium">Magic:</span> ${data.magic_level ?? 0}</div>
          <div><span class="font-medium">HP:</span> ${data.c_hp ?? 0} / ${data.max_hp ?? 0}</div>
          <div><span class="font-medium">Stamina:</span> ${data.stamina ?? 0}</div>
          <div><span class="font-medium">Gold:</span> ${data.gold ?? 0}</div>
          <div><span class="font-medium">EXP:</span> ${data.exp ?? 0} / ${data.exp_req ?? 0}</div>
        </div>
      </div>
    `;
    profileContent.innerHTML = html;
  } catch (e) {
    profileContent.innerHTML = `<div class="text-red-600 text-center">Error loading profile.</div>`;
  }
});
</script>
</body>
</html>
<script type="module" src="assets/js/playerStats.js"></script>
