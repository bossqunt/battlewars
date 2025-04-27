import { showToast } from './ui.js';
import { updatePlayerStats, fetchPlayerData } from './playerStats.js';
// import { updateGridLocation, updateGridOwner } from './grid.js';
import { travelPlayer, startCooldown, handleCooldownError } from './travel.js';
import { takeOwnership } from './ownership.js';
import { updateMonstersTable, createMonsterCard, battleMonster } from './monsters.js';
import { getBattleHistory, clearBattleModalRewards } from './battle.js';
// import { loadWorldEvents } from './worldEvents.js';

async function updateOnlinePlayers() {
  try {
    const response = await fetch('/bw2/api/getOnlinePlayers.php');
    const data = await response.json();

    const onlineCountElem = document.getElementById('online-player-count');
    if (onlineCountElem) {
      onlineCountElem.textContent = data.count;
    }

    // Attach click handler to the button (not the count span)
    const onlineBtn = document.getElementById('online-players');
    if (onlineBtn) {
      onlineBtn.onclick = () => {
        const listContainer = document.getElementById('online-players-list');
        const cardsContainer = document.getElementById('online-players-cards');
        if (!listContainer || !cardsContainer) return;

        // Toggle visibility
        if (listContainer.style.display === 'none' || listContainer.style.display === '') {
          // Show and populate
          listContainer.style.display = 'flex';
          // Optionally reduce grid width
          const gridController = document.getElementById('grid-controller');
          if (gridController) gridController.style.width = 'calc(100% - 660px)';
          // Build player cards
          cardsContainer.innerHTML = '';
          if (data.players && data.players.length > 0) {
            data.players.forEach(p => {
              const card = document.createElement('div');
              card.className = 'online-player-card flex items-center gap-2 p-2 border rounded bg-white mb-1';
              card.innerHTML = `<span class="text-xs font-bold text-blue-700">${p.name}</span>
                                <span class="text-xs text-gray-600">Lv.${p.level}</span>`;
              cardsContainer.appendChild(card);
            });
          } else {
            cardsContainer.innerHTML = '<div class="text-xs text-gray-500">No players online.</div>';
          }
        } else {
          // Hide
          listContainer.style.display = 'none';
          const gridController = document.getElementById('grid-controller');
          if (gridController) gridController.style.width = '';
        }
      };
    }
  } catch (e) {
    console.error('Error fetching online players:', e);
  }
}

// Render current location breadcrumbs
function renderLocationBreadcrumbs(playerData) {
  const breadcrumbs = document.getElementById('current-location-breadcrumbs');
  if (!breadcrumbs) return;
  breadcrumbs.innerHTML = ''; // Clear existing breadcrumbs

  let areaName = 'Unknown Area';
  if (playerData && playerData.area && playerData.area.length > 0 && playerData.area[0].name) {
    areaName = playerData.area[0].name;
  }
  breadcrumbs.innerHTML = `<span class="text-xs font-bold text-blue-700">${areaName}</span>`;
}

// Render area sidebar
function renderAreaSidebar(playerData) {
  const sidebar = document.getElementById('area-sidebar');
  if (!sidebar) return;

  const currentAreaId = playerData.area[0].area_id;
  const areasUnlocked = playerData.areasUnlocked || [];

  sidebar.innerHTML = `
    <div class="flex items-center justify-center gap-2 mb-1 w-full">
      <span class="font-bold text-center">Current Area:</span>
      <span class="text-blue-700 font-semibold mr-4">${playerData.area[0].name}</span>
      ${
        areasUnlocked.length > 0
          ? `<label for="area-select" class="font-bold">Travel to:</label>
             <select id="area-select" class="border rounded px-2 py-1 ml-2">
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
        const response = await fetch('/bw2/api/updateLocation.php', {
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
          renderAreaSidebar(data);
          renderLocationBreadcrumbs(data);
          updateGridLocation(data);
          updatePlayerStats();
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
  renderLocationBreadcrumbs(playerData);
  // ...other dashboard updates as needed...
}

document.addEventListener('DOMContentLoaded', () => {
  const travelBtn = document.getElementById('travel-button');
  if (travelBtn) travelBtn.addEventListener('click', travelPlayer);

  const ownershipBtn = document.getElementById('take-ownership-button');
  if (ownershipBtn) ownershipBtn.addEventListener('click', () => {
    clearBattleModalRewards();
    takeOwnership();
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

  loadWorldEvents();

  // Use setInterval with an async callback for proper async updates
  setInterval(async () => {
    await loadWorldEvents();
  }, 17000); // 17 seconds (between 15-20s, adjust as desired)

  updateOnlinePlayers();
  setInterval(updateOnlinePlayers, 15000); // update every 15s

  refreshDashboard();
});

getBattleHistory(playerId);