import { showToast } from './ui.js';
import { updatePlayerStats, fetchPlayerData } from './playerStats.js';
import { updateGridLocation, updateGridOwner } from './grid.js';
import { travelPlayer, startCooldown, handleCooldownError } from './travel.js';
import { takeOwnership } from './ownership.js';
import { updateMonstersTable, createMonsterCard, battleMonster } from './monsters.js';
import { getBattleHistory, clearBattleModalRewards } from './battle.js';
// import { loadWorldEvents } from './worldEvents.js';


// Render current location breadcrumbs
function renderLocationBreadcrumbs(playerData) {
  const breadcrumbs = document.getElementById('current-location-breadcrumbs');
  if (!breadcrumbs) return;
  breadcrumbs.innerHTML = ''; // Clear existing breadcrumbs

  let areaName = 'Unknown Area';
  if (playerData && playerData.area && playerData.area.length > 0 && playerData.area[0].name) {
    areaName = playerData.area[0].name;
  }
  breadcrumbs.innerHTML = `<span class=" font-bold text-blue-700">${areaName}</span>`;
}

// Render area sidebar
function renderAreaSidebar(playerData) {
  const sidebar = document.getElementById('travel-container');
  if (!sidebar) return;

  const currentAreaId = playerData.area[0].area_id;
  const areasUnlocked = playerData.areasUnlocked || [];

  sidebar.innerHTML = `
    <div class="flex items-center justify-center gap-2 mb-1 w-full">
      ${
        areasUnlocked.length > 0
          ? `<label for="area-select" class="font-semibold text-sm">Travel to:</label>
             <select id="area-select" class="border rounded px-2 py-1  text-xs">
               ${areasUnlocked.map(area => `
                 <option value="${area.area_id}" ${area.area_id == currentAreaId ? 'selected' : ''}>
                   ${area.name} (Lv. ${area.min_level}-${area.max_level})
                 </option>
               `).join('')}
             </select>`
          : ''
      }
    </div>
  `;

  // Add change listener to dropdown if present
  const select = sidebar.querySelector('#area-select');
  if (select) {
    select.addEventListener('change', async (e) => {
      const areaId = parseInt(select.value);
      if (areaId !== currentAreaId) {
        const response = await fetch('/api/updateLocation.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ id: playerId, x: 0, y: 0, area_id: areaId })
        });
        const data = await response.json();
        if (!data.error) {
          showToast(`Traveled to ${select.options[select.selectedIndex].text}`, 'success', 2000);
          // Clear monster list after traveling
          const monstersContainer = document.getElementById('monsters-nearby');
          if (monstersContainer) monstersContainer.innerHTML = '';
          // Refresh sidebar and grid with new data
          refreshDashboard();

        } else {
          showToast(data.error, 'error', 3000);
        }
      }
    });
  }
}

// After fetching player data, render the area sidebar
async function refreshDashboard() {
  const playerData = await fetchPlayerData();
  renderAreaSidebar(playerData);
  updateGridLocation(playerData);
  updatePlayerStats();
  updateGridOwner(playerData);
  renderLocationBreadcrumbs(playerData);
}

document.addEventListener('DOMContentLoaded', () => {
  const travelBtn = document.getElementById('travel-button');
  if (travelBtn) travelBtn.addEventListener('click', async () => {
    await travelPlayer();
    refreshDashboard();
  });

  const ownershipBtn = document.getElementById('take-ownership-button');
  if (ownershipBtn) ownershipBtn.addEventListener('click', async () => {
    clearBattleModalRewards();
    await takeOwnership();
    refreshDashboard(); // <-- Add this line to update the UI after taking ownership
  });

  const closeBattleBtn = document.getElementById('close-battle');
  if (closeBattleBtn) closeBattleBtn.addEventListener('click', () => {
    document.getElementById('battle-modal').classList.add('hidden');
    clearBattleModalRewards();
  });

  const closeModalBtn = document.getElementById('close-modal');
  if (closeModalBtn) closeModalBtn.addEventListener('click', () => {
    document.getElementById('battle-modal').classList.add('hidden');
    clearBattleModalRewards();
  });

  refreshDashboard();
});

getBattleHistory(playerId);