import { showToast } from './ui.js';
import { updatePlayerStats, fetchPlayerData } from './playerStats.js';
import { updateGridLocation, updateGridOwner } from './grid.js';
import { travelPlayer, startCooldown, handleCooldownError } from './travel.js';
import { takeOwnership } from './ownership.js';
import { updateMonstersTable, createMonsterCard, battleMonster } from './monsters.js';
import { getBattleHistory, clearBattleModalRewards } from './battle.js';
import { loadWorldEvents } from './worldEvents.js';

async function updateOnlinePlayers() {
  try {
    const response = await fetch('/bw2/api/getOnlinePlayers.php');
    const data = await response.json();

    const onlineCountElem = document.getElementById('online-player-count');
    if (onlineCountElem) {
      onlineCountElem.textContent = data.count;

      // Add click event to show player list in a modal or alert
      onlineCountElem.onclick = () => {
        if (data.players && data.players.length > 0) {
          const list = data.players.map(p => `${p.name} (Lv.${p.level})`).join('<br>');
          // Simple modal or alert, replace with your own modal if desired
          const modal = document.createElement('div');
          modal.style.position = 'fixed';
          modal.style.top = '50%';
          modal.style.left = '50%';
          modal.style.transform = 'translate(-50%, -50%)';
          modal.style.background = '#fff';
          modal.style.padding = '20px';
          modal.style.border = '1px solid #888';
          modal.style.zIndex = 10000;
          modal.innerHTML = `<h3>Online Players (${data.count})</h3><div style="max-height:300px;overflow:auto;">${list}</div><button id="close-online-modal" style="margin-top:10px;">Close</button>`;
          document.body.appendChild(modal);
          document.getElementById('close-online-modal').onclick = () => modal.remove();
        } else {
          alert('No players online.');
        }
      };
    } else {
      console.log('Element #online-player-count not found');
    }
  } catch (e) {
    console.error('Error fetching online players:', e);
  }
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